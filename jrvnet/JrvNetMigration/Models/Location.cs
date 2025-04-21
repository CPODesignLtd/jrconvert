using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace JrvNetMigration.Models
{
    [Table("location")]
    public class Location
    {
        [Key]
        [Column("IDLOCATION")]
        public int Id { get; set; }

        [Required]
        [Column("NAZEV")]
        [StringLength(255)]
        public string Name { get; set; }

        [Column("ICON")]
        [StringLength(45)]
        public string Icon { get; set; }

        [Column("LOGO")]
        [StringLength(45)]
        public string Logo { get; set; }

        [Column("URL")]
        [StringLength(255)]
        public string Url { get; set; }

        public virtual ICollection<Admin> Admins { get; set; }
    }
}