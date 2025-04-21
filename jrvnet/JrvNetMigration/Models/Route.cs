using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace JrvNetMigration.Models
{
    [Table("routes")]
    public class Route
    {
        [Key]
        public int Id { get; set; }
        
        [Required]
        [StringLength(20)]
        public string RouteNumber { get; set; }
        
        [Required]
        [StringLength(50)]
        public string City { get; set; }
        
        [Required]
        [StringLength(20)]
        public string TransportType { get; set; } // bus, tram, etc.
        
        [StringLength(255)]
        public string? Description { get; set; }
        
        public bool IsActive { get; set; } = true;
    }
}