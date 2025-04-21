using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class TrebicController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}