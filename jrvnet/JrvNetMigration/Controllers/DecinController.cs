using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class DecinController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}