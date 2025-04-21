using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace JrvNetMigration.Models
{
    [Table("stations")]
    public class Station
    {
        [Key]
        public int Id { get; set; }
        
        [Required]
        [StringLength(100)]
        public string Name { get; set; }
        
        [Required]
        [StringLength(50)]
        public string City { get; set; }
        
        public double Latitude { get; set; }
        public double Longitude { get; set; }
        
        [StringLength(255)]
        public string? Description { get; set; }
    }
}