using System.ComponentModel.DataAnnotations;

namespace JrvNetMigration.Models.ViewModels
{
    public class ScheduleViewModel
    {
        public int Id { get; set; }

        [Required]
        [Display(Name = "Route")]
        public int RouteId { get; set; }

        [Required]
        [Display(Name = "Station")]
        public int StationId { get; set; }

        [Required]
        [Display(Name = "Stop Order")]
        [Range(1, int.MaxValue, ErrorMessage = "Stop order must be greater than 0")]
        public int StopOrder { get; set; }

        [Required]
        [Display(Name = "Arrival Time")]
        [DataType(DataType.Time)]
        public TimeOnly ArrivalTime { get; set; }

        [Required]
        [Display(Name = "Departure Time")]
        [DataType(DataType.Time)]
        public TimeOnly DepartureTime { get; set; }

        [Display(Name = "Weekday Schedule")]
        public bool IsWeekday { get; set; }

        // Navigation properties for dropdowns
        public IEnumerable<Route> AvailableRoutes { get; set; } = Enumerable.Empty<Route>();
        public IEnumerable<Station> AvailableStations { get; set; } = Enumerable.Empty<Station>();
    }
}