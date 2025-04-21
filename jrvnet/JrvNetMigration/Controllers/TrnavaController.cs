using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class TrnavaController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}