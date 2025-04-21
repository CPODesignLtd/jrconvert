using Microsoft.EntityFrameworkCore;
using JrvNetMigration.Utils;

namespace JrvNetMigration.Models
{
    public static class DbInitializer
    {
        public static async Task Initialize(AppDbContext context)
        {
            // Just ensure database is created and migrations are applied
            await context.Database.MigrateAsync();
            
            // Check if admin user exists, if not create default admin
            if (!await context.Admins.AnyAsync())
            {
                var admin = new Admin
                {
                    Username = "admin",
                    PasswordHash = "jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=", // admin123
                    Email = "admin@example.com",
                    IsActive = true,
                    CreatedAt = DateTime.UtcNow
                };

                context.Admins.Add(admin);
                await context.SaveChangesAsync();
            }
        }
    }
}