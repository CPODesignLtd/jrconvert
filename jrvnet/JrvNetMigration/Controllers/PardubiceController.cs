using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class PardubiceController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}