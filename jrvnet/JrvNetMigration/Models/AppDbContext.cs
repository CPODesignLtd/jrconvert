using Microsoft.EntityFrameworkCore;

namespace JrvNetMigration.Models
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
        {
        }

        public DbSet<Station> Stations { get; set; }
        public DbSet<Route> Routes { get; set; }
        public DbSet<Schedule> Schedules { get; set; }
        public DbSet<Admin> Admins { get; set; }
        public DbSet<Location> Locations { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);
            
            // Station configuration
            modelBuilder.Entity<Station>()
                .HasIndex(s => s.Name);
            
            modelBuilder.Entity<Station>()
                .HasIndex(s => s.City);

            // Route configuration
            modelBuilder.Entity<Route>()
                .HasIndex(r => new { r.City, r.RouteNumber })
                .IsUnique();

            modelBuilder.Entity<Route>()
                .HasIndex(r => r.TransportType);

            // Schedule configuration
            modelBuilder.Entity<Schedule>()
                .HasIndex(s => new { s.RouteId, s.StationId, s.IsWeekday });
            
            modelBuilder.Entity<Schedule>()
                .HasOne(s => s.Route)
                .WithMany()
                .HasForeignKey(s => s.RouteId)
                .OnDelete(DeleteBehavior.Cascade);
            
            modelBuilder.Entity<Schedule>()
                .HasOne(s => s.Station)
                .WithMany()
                .HasForeignKey(s => s.StationId)
                .OnDelete(DeleteBehavior.Cascade);

            // Admin configuration
            modelBuilder.Entity<Admin>()
                .HasIndex(a => a.Username)
                .IsUnique();
            
            modelBuilder.Entity<Admin>()
                .HasIndex(a => a.Email)
                .IsUnique();

            modelBuilder.Entity<Admin>()
                .HasOne<Location>()
                .WithMany()
                .HasForeignKey(a => a.LocationId)
                .OnDelete(DeleteBehavior.SetNull);

            // Location configuration
            modelBuilder.Entity<Location>()
                .HasIndex(l => l.Name)
                .IsUnique();

            // Ensure proper collation for string comparisons
            modelBuilder.Entity<Station>()
                .Property(s => s.Name)
                .UseCollation("utf8mb4_unicode_ci");

            modelBuilder.Entity<Route>()
                .Property(r => r.City)
                .UseCollation("utf8mb4_unicode_ci");

            modelBuilder.Entity<Location>()
                .Property(l => l.Name)
                .UseCollation("utf8mb4_unicode_ci");
        }
    }
}