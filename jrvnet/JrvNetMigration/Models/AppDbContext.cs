using Microsoft.EntityFrameworkCore;

namespace JrvNetMigration.Models
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
        {
        }

        // Define DbSet properties for your tables here
        // Example: public DbSet<User> Users { get; set; }
    }
}