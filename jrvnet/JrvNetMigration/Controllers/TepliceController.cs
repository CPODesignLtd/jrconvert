using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class TepliceController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}