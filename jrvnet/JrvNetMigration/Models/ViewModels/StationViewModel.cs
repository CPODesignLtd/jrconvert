using System.ComponentModel.DataAnnotations;

namespace JrvNetMigration.Models.ViewModels
{
    public class StationViewModel
    {
        public int Id { get; set; }

        [Required]
        [StringLength(100)]
        [Display(Name = "Station Name")]
        public string Name { get; set; } = string.Empty;

        [Required]
        [StringLength(50)]
        [Display(Name = "City")]
        public string City { get; set; } = string.Empty;

        [StringLength(255)]
        [Display(Name = "Description")]
        public string? Description { get; set; }

        [Required]
        [Range(-90, 90)]
        [Display(Name = "Latitude")]
        public double Latitude { get; set; }

        [Required]
        [Range(-180, 180)]
        [Display(Name = "Longitude")]
        public double Longitude { get; set; }
    }
}