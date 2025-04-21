using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class JihlavaController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}