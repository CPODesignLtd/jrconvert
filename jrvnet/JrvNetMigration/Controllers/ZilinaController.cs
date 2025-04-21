using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class ZilinaController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}