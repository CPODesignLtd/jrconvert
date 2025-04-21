using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class KosiceController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}