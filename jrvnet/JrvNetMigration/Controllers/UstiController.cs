using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class UstiController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}