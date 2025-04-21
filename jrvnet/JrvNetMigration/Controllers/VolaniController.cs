using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using JrvNetMigration.Models;
using System.Linq;

namespace JrvNetMigration.Controllers
{
    public class VolaniController : Controller
    {
        private readonly AppDbContext _context;

        public VolaniController(AppDbContext context)
        {
            _context = context;
        }

        public async Task<IActionResult> Index()
        {
            var routes = await _context.Routes
                .Where(r => r.IsActive)
                .OrderBy(r => r.City)
                .ThenBy(r => r.RouteNumber)
                .ToListAsync();

            return View(routes);
        }

        [HttpGet]
        [Route("Volani/GetStations/{routeId}")]
        public async Task<IActionResult> GetStations(int routeId)
        {
            var stations = await _context.Schedules
                .Include(s => s.Station)
                .Where(s => s.RouteId == routeId)
                .OrderBy(s => s.StopOrder)
                .Select(s => new { id = s.Station.Id, name = s.Station.Name })
                .Distinct()
                .ToListAsync();

            return Json(stations);
        }

        [HttpGet]
        [Route("Volani/GetSchedule/{routeId}/{stationId}/{isWeekday}")]
        public async Task<IActionResult> GetSchedule(int routeId, int stationId, bool isWeekday)
        {
            var schedules = await _context.Schedules
                .Include(s => s.Station)
                .Where(s => s.RouteId == routeId && s.IsWeekday == isWeekday)
                .OrderBy(s => s.StopOrder)
                .Select(s => new
                {
                    stopOrder = s.StopOrder,
                    station = new { name = s.Station.Name },
                    arrivalTime = s.ArrivalTime.ToString("HH:mm"),
                    departureTime = s.DepartureTime.ToString("HH:mm")
                })
                .ToListAsync();

            return Json(schedules);
        }
    }
}