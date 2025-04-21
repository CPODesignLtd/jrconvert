using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class OlomoucController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}