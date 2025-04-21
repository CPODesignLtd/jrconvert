using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class LiberecController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}