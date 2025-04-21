using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class HradecController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}