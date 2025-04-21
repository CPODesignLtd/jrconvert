using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class BratislavaController : Controller
    {
        public IActionResult Index(int? page, int? kurz)
        {
            ViewData["Administrator"] = false;
            ViewData["CountTagMenu"] = 2;
            ViewData["ShowKurz"] = kurz == 1;
            ViewData["Page"] = page ?? 1;

            return View();
        }
    }
}