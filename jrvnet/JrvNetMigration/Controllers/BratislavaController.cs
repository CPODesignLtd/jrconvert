using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using JrvNetMigration.Models;

namespace JrvNetMigration.Controllers
{
    public class BratislavaController : Controller
    {
        private readonly AppDbContext _context;

        public BratislavaController(AppDbContext context)
        {
            _context = context;
        }

        public async Task<IActionResult> Index(int? page, int? kurz)
        {
            ViewData["Administrator"] = false;
            ViewData["CountTagMenu"] = 2;
            ViewData["ShowKurz"] = kurz == 1;
            ViewData["Page"] = page ?? 1;

            var stations = await _context.Stations
                .Where(s => s.City == "Bratislava")
                .OrderBy(s => s.Name)
                .ToListAsync();

            return View(stations);
        }

        public async Task<IActionResult> Search(string term)
        {
            var stations = await _context.Stations
                .Where(s => s.City == "Bratislava" && s.Name.Contains(term))
                .Select(s => new { id = s.Id, value = s.Name })
                .Take(10)
                .ToListAsync();

            return Json(stations);
        }
    }
}