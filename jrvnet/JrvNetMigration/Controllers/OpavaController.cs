using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class OpavaController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}