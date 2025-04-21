using System.Security.Claims;
using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using JrvNetMigration.Models;
using JrvNetMigration.Models.ViewModels;
using Microsoft.AspNetCore.Authorization;
using JrvNetMigration.Utils;
using Route = JrvNetMigration.Models.Route;
using Microsoft.AspNetCore.Hosting;
using System.IO;

namespace JrvNetMigration.Controllers
{
    public class AdminController : Controller
    {
        private readonly AppDbContext _context;
        private readonly ILogger<AdminController> _logger;
        private readonly IWebHostEnvironment _environment;

        public AdminController(AppDbContext context, ILogger<AdminController> logger, IWebHostEnvironment environment)
        {
            _context = context;
            _logger = logger;
            _environment = environment;
        }

        [Authorize(Roles = "Admin")]
        public IActionResult Index()
        {
            return View();
        }

        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Routes()
        {
            var routes = await _context.Routes
                .OrderBy(r => r.City)
                .ThenBy(r => r.RouteNumber)
                .ToListAsync();
            return View(routes);
        }

        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Stations()
        {
            var stations = await _context.Stations
                .OrderBy(s => s.City)
                .ThenBy(s => s.Name)
                .ToListAsync();
            return View(stations);
        }

        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Schedules()
        {
            var schedules = await _context.Schedules
                .Include(s => s.Route)
                .Include(s => s.Station)
                .OrderBy(s => s.Route.City)
                .ThenBy(s => s.Route.RouteNumber)
                .ThenBy(s => s.StopOrder)
                .ToListAsync();
            return View(schedules);
        }

        [HttpGet]
        public IActionResult Login(string? returnUrl = null)
        {
            ViewData["ReturnUrl"] = returnUrl;
            return View();
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Login(LoginViewModel model, string? returnUrl = null)
        {
            ViewData["ReturnUrl"] = returnUrl;

            if (ModelState.IsValid)
            {
                var admin = await _context.Admins
                    .FirstOrDefaultAsync(a => a.Username == model.Username);

                if (admin != null && admin.IsActive)
                {
                    if (PasswordHasher.VerifyPassword(model.Password, admin.PasswordHash))
                    {
                        admin.LastLoginAt = DateTime.UtcNow;
                        await _context.SaveChangesAsync();

                        await SignInUserAsync(admin);

                        if (!admin.LocationId.HasValue)
                        {
                            return RedirectToAction(nameof(SelectLocation));
                        }

                        if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
                        {
                            return Redirect(returnUrl);
                        }
                        return RedirectToAction(nameof(Index));
                    }
                }

                ModelState.AddModelError(string.Empty, "Invalid login attempt.");
            }

            return View(model);
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        [Authorize(Roles = "Admin")]
        public async Task<IActionResult> Logout()
        {
            await HttpContext.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
            _logger.LogInformation("User logged out.");
            return RedirectToAction(nameof(Login));
        }

        public async Task<IActionResult> SelectLocation()
        {
            var locations = await _context.Locations
                .OrderBy(l => l.Name)
                .ToListAsync();
        
            if (!locations.Any())
            {
                return RedirectToAction("Index", "Home");
            }
        
            return View(locations);
        }

        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> SelectLocation(int locationId)
        {
            var username = User.Identity.Name;
            var admin = await _context.Admins.FirstOrDefaultAsync(a => a.Username == username);
            
            if (admin == null)
            {
                return Unauthorized();
            }

            var location = await _context.Locations.FindAsync(locationId);
            if (location == null)
            {
                return NotFound("Location not found");
            }

            admin.LocationId = locationId;
            await _context.SaveChangesAsync();

            // Re-sign in the user with the new location claim
            await SignInUserAsync(admin);

            return RedirectToAction("Index", "Home");
        }

        private async Task SignInUserAsync(Admin admin)
        {
            var claims = new List<Claim>
            {
                new Claim(ClaimTypes.Name, admin.Username),
                new Claim(ClaimTypes.Email, admin.Email),
                new Claim(ClaimTypes.Role, "Admin"),
            };

            // Add location claim if admin has an assigned location
            if (admin.LocationId.HasValue)
            {
                claims.Add(new Claim("LocationId", admin.LocationId.Value.ToString()));
            }

            var identity = new ClaimsIdentity(claims, CookieAuthenticationDefaults.AuthenticationScheme);
            var principal = new ClaimsPrincipal(identity);

            await HttpContext.SignInAsync(CookieAuthenticationDefaults.AuthenticationScheme, principal);
        }

        #region Routes Management
        [Authorize(Roles = "Admin")]
        [HttpGet]
        public IActionResult CreateRoute()
        {
            return View(new RouteViewModel());
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> CreateRoute(RouteViewModel model)
        {
            if (ModelState.IsValid)
            {
                var route = new Route
                {
                    City = model.City,
                    RouteNumber = model.RouteNumber,
                    TransportType = model.TransportType,
                    Description = model.Description ?? string.Empty,
                    IsActive = model.IsActive
                };

                _context.Routes.Add(route);
                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Routes));
            }
            return View(model);
        }

        [Authorize(Roles = "Admin")]
        [HttpGet]
        public async Task<IActionResult> EditRoute(int id)
        {
            var route = await _context.Routes.FindAsync(id);
            if (route == null)
            {
                return NotFound();
            }

            var viewModel = new RouteViewModel
            {
                Id = route.Id,
                City = route.City,
                RouteNumber = route.RouteNumber,
                TransportType = route.TransportType,
                Description = route.Description,
                IsActive = route.IsActive
            };

            return View(nameof(CreateRoute), viewModel);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> EditRoute(int id, RouteViewModel model)
        {
            if (id != model.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                var route = await _context.Routes.FindAsync(id);
                if (route == null)
                {
                    return NotFound();
                }

                route.City = model.City;
                route.RouteNumber = model.RouteNumber;
                route.TransportType = model.TransportType;
                route.Description = model.Description ?? string.Empty;
                route.IsActive = model.IsActive;

                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Routes));
            }
            return View(nameof(CreateRoute), model);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteRoute(int id)
        {
            var route = await _context.Routes.FindAsync(id);
            if (route != null)
            {
                _context.Routes.Remove(route);
                await _context.SaveChangesAsync();
            }
            return RedirectToAction(nameof(Routes));
        }
        #endregion

        #region Stations Management
        [Authorize(Roles = "Admin")]
        [HttpGet]
        public IActionResult CreateStation()
        {
            return View(new StationViewModel());
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> CreateStation(StationViewModel model)
        {
            if (ModelState.IsValid)
            {
                var station = new Station
                {
                    Name = model.Name,
                    City = model.City,
                    Description = model.Description ?? string.Empty,
                    Latitude = model.Latitude,
                    Longitude = model.Longitude
                };

                _context.Stations.Add(station);
                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Stations));
            }
            return View(model);
        }

        [Authorize(Roles = "Admin")]
        [HttpGet]
        public async Task<IActionResult> EditStation(int id)
        {
            var station = await _context.Stations.FindAsync(id);
            if (station == null)
            {
                return NotFound();
            }

            var viewModel = new StationViewModel
            {
                Id = station.Id,
                Name = station.Name,
                City = station.City,
                Description = station.Description,
                Latitude = station.Latitude,
                Longitude = station.Longitude
            };

            return View(nameof(CreateStation), viewModel);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> EditStation(int id, StationViewModel model)
        {
            if (id != model.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                var station = await _context.Stations.FindAsync(id);
                if (station == null)
                {
                    return NotFound();
                }

                station.Name = model.Name;
                station.City = model.City;
                station.Description = model.Description ?? string.Empty;
                station.Latitude = model.Latitude;
                station.Longitude = model.Longitude;

                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Stations));
            }
            return View(nameof(CreateStation), model);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteStation(int id)
        {
            var station = await _context.Stations.FindAsync(id);
            if (station != null)
            {
                _context.Stations.Remove(station);
                await _context.SaveChangesAsync();
            }
            return RedirectToAction(nameof(Stations));
        }
        #endregion

        #region Schedules Management
        [Authorize(Roles = "Admin")]
        [HttpGet]
        public async Task<IActionResult> CreateSchedule()
        {
            var viewModel = new ScheduleViewModel
            {
                AvailableRoutes = await _context.Routes.OrderBy(r => r.City).ThenBy(r => r.RouteNumber).ToListAsync(),
                AvailableStations = await _context.Stations.OrderBy(s => s.City).ThenBy(s => s.Name).ToListAsync()
            };
            return View(viewModel);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> CreateSchedule(ScheduleViewModel model)
        {
            if (ModelState.IsValid)
            {
                var schedule = new Schedule
                {
                    RouteId = model.RouteId,
                    StationId = model.StationId,
                    StopOrder = model.StopOrder,
                    ArrivalTime = model.ArrivalTime,
                    DepartureTime = model.DepartureTime,
                    IsWeekday = model.IsWeekday
                };

                _context.Schedules.Add(schedule);
                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Schedules));
            }

            model.AvailableRoutes = await _context.Routes.OrderBy(r => r.City).ThenBy(r => r.RouteNumber).ToListAsync();
            model.AvailableStations = await _context.Stations.OrderBy(s => s.City).ThenBy(s => s.Name).ToListAsync();
            return View(model);
        }

        [Authorize(Roles = "Admin")]
        [HttpGet]
        public async Task<IActionResult> EditSchedule(int id)
        {
            var schedule = await _context.Schedules.FindAsync(id);
            if (schedule == null)
            {
                return NotFound();
            }

            var viewModel = new ScheduleViewModel
            {
                Id = schedule.Id,
                RouteId = schedule.RouteId,
                StationId = schedule.StationId,
                StopOrder = schedule.StopOrder,
                ArrivalTime = schedule.ArrivalTime,
                DepartureTime = schedule.DepartureTime,
                IsWeekday = schedule.IsWeekday,
                AvailableRoutes = await _context.Routes.OrderBy(r => r.City).ThenBy(r => r.RouteNumber).ToListAsync(),
                AvailableStations = await _context.Stations.OrderBy(s => s.City).ThenBy(s => s.Name).ToListAsync()
            };

            return View(nameof(CreateSchedule), viewModel);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> EditSchedule(int id, ScheduleViewModel model)
        {
            if (id != model.Id)
            {
                return NotFound();
            }

            if (ModelState.IsValid)
            {
                var schedule = await _context.Schedules.FindAsync(id);
                if (schedule == null)
                {
                    return NotFound();
                }

                schedule.RouteId = model.RouteId;
                schedule.StationId = model.StationId;
                schedule.StopOrder = model.StopOrder;
                schedule.ArrivalTime = model.ArrivalTime;
                schedule.DepartureTime = model.DepartureTime;
                schedule.IsWeekday = model.IsWeekday;

                await _context.SaveChangesAsync();
                return RedirectToAction(nameof(Schedules));
            }

            model.AvailableRoutes = await _context.Routes.OrderBy(r => r.City).ThenBy(r => r.RouteNumber).ToListAsync();
            model.AvailableStations = await _context.Stations.OrderBy(s => s.City).ThenBy(s => s.Name).ToListAsync();
            return View(nameof(CreateSchedule), model);
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> DeleteSchedule(int id)
        {
            var schedule = await _context.Schedules.FindAsync(id);
            if (schedule != null)
            {
                _context.Schedules.Remove(schedule);
                await _context.SaveChangesAsync();
            }
            return RedirectToAction(nameof(Schedules));
        }
        #endregion

        #region File Upload
        [Authorize(Roles = "Admin")]
        public IActionResult Upload()
        {
            return View();
        }

        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ValidateAntiForgeryToken]
        public async Task<IActionResult> Upload(IFormFile file)
        {
            if (file == null || file.Length == 0)
            {
                ModelState.AddModelError(string.Empty, "Please select a file to upload.");
                return View();
            }

            if (!Path.GetExtension(file.FileName).Equals(".zip", StringComparison.OrdinalIgnoreCase))
            {
                ModelState.AddModelError(string.Empty, "Only .zip files are allowed.");
                return View();
            }

            try
            {
                var uploadsFolder = Path.Combine(_environment.WebRootPath, "uploads");
                Directory.CreateDirectory(uploadsFolder);

                var uniqueFileName = $"{DateTime.Now:yyyyMMddHHmmss}_{file.FileName}";
                var filePath = Path.Combine(uploadsFolder, uniqueFileName);

                using (var stream = new FileStream(filePath, FileMode.Create))
                {
                    await file.CopyToAsync(stream);
                }

                // TODO: Process the zip file and import data into database
                _logger.LogInformation("File {FileName} uploaded successfully", uniqueFileName);
                TempData["Message"] = "File uploaded successfully.";

                return RedirectToAction(nameof(Index));
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Error uploading file {FileName}", file.FileName);
                ModelState.AddModelError(string.Empty, "Error uploading file. Please try again.");
                return View();
            }
        }
        #endregion
    }
}