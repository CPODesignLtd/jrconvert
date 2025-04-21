using Microsoft.AspNetCore.Mvc;

namespace JrvNetMigration.Controllers
{
    public class NovyVyhladavacController : Controller
    {
        public IActionResult Static()
        {
            return View();
        }
    }
}