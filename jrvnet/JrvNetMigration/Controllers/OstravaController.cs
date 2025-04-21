using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class OstravaController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}