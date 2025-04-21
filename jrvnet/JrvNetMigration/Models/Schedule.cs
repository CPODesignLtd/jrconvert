using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace JrvNetMigration.Models
{
    [Table("schedules")]
    public class Schedule
    {
        [Key]
        public int Id { get; set; }
        
        public int RouteId { get; set; }
        
        public int StationId { get; set; }
        
        [Required]
        public TimeOnly ArrivalTime { get; set; }
        
        [Required]
        public TimeOnly DepartureTime { get; set; }
        
        public int StopOrder { get; set; }
        
        public bool IsWeekday { get; set; }
        
        [ForeignKey("RouteId")]
        public Route Route { get; set; }
        
        [ForeignKey("StationId")]
        public Station Station { get; set; }
    }
}