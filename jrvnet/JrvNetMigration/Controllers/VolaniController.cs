using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class VolaniController : Controller
    {
        public IActionResult Index()
        {
            // Add any logic needed for processing packet data here
            return View();
        }
    }
}