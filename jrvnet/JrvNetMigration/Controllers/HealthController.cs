using Microsoft.AspNetCore.Mvc;
using JrvNetMigration.Models;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;

namespace JrvNetMigration.Controllers
{
    [ApiController]
    [Route("[controller]")]
    public class HealthController : ControllerBase
    {
        private readonly AppDbContext _context;
        private readonly ILogger<HealthController> _logger;

        public HealthController(AppDbContext context, ILogger<HealthController> logger)
        {
            _context = context;
            _logger = logger;
        }

        [HttpGet]
        public async Task<IActionResult> Get()
        {
            try
            {
                // Check database connectivity
                bool canConnectToDb = await _context.Database.CanConnectAsync();
                if (!canConnectToDb)
                {
                    _logger.LogError("Health check failed: Cannot connect to database");
                    return StatusCode(503, new { status = "error", message = "Database connection failed" });
                }

                return Ok(new { status = "healthy", message = "Service is running" });
            }
            catch (Exception ex)
            {
                _logger.LogError(ex, "Health check failed with exception");
                return StatusCode(503, new { status = "error", message = ex.Message });
            }
        }
    }
}