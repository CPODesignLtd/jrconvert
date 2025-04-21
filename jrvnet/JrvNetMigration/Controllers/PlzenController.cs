using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class PlzenController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}