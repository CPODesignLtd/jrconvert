using System.ComponentModel.DataAnnotations;

namespace JrvNetMigration.Models.ViewModels
{
    public class RouteViewModel
    {
        public int Id { get; set; }

        [Required]
        [StringLength(50)]
        [Display(Name = "City")]
        public string City { get; set; } = string.Empty;

        [Required]
        [StringLength(20)]
        [Display(Name = "Route Number")]
        public string RouteNumber { get; set; } = string.Empty;

        [Required]
        [StringLength(20)]
        [Display(Name = "Transport Type")]
        public string TransportType { get; set; } = string.Empty;

        [StringLength(255)]
        [Display(Name = "Description")]
        public string? Description { get; set; }

        [Display(Name = "Active")]
        public bool IsActive { get; set; } = true;
    }
}