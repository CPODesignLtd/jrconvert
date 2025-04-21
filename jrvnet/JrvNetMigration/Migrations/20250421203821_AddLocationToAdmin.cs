using Microsoft.EntityFrameworkCore.Migrations;

namespace JrvNetMigration.Migrations
{
    public partial class AddLocationToAdmin : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<int>(
                name: "LocationId",
                table: "admins",
                type: "int",
                nullable: true);

            migrationBuilder.CreateIndex(
                name: "IX_admins_LocationId",
                table: "admins",
                column: "LocationId");

            migrationBuilder.AddForeignKey(
                name: "FK_admins_location_LocationId",
                table: "admins",
                column: "LocationId",
                principalTable: "location",
                principalColumn: "IDLOCATION",
                onDelete: ReferentialAction.Restrict);
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropForeignKey(
                name: "FK_admins_location_LocationId",
                table: "admins");

            migrationBuilder.DropIndex(
                name: "IX_admins_LocationId",
                table: "admins");

            migrationBuilder.DropColumn(
                name: "LocationId",
                table: "admins");
        }
    }
}