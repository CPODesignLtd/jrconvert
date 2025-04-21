using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace JrvNetMigration.Migrations
{
    public partial class AddStoredProcedures : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            // Route and schedule related procedures
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `getOdjezdyZastavka`(
                    in location int, 
                    in packet int, 
                    in casod int, 
                    in casdo int, 
                    in cil int, 
                    in den varchar(255), 
                    in odZ varchar(5000), 
                    in outdoZ varchar(5000), 
                    in linky varchar(255)
                )
                BEGIN
                    select 
                        spoje.c_linky, 
                        spojeni.odZ, 
                        spojeni.doZ, 
                        spojeni.odT, 
                        spojeni.doT, 
                        spoje.smer,
                        (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
                        (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo
                    from spoje 
                    inner join chronometr on (
                        chronometr.idlocation = spoje.idlocation 
                        and chronometr.packet = spoje.packet 
                        and chronometr.c_linky = spoje.c_linky 
                        and chronometr.smer = spoje.smer 
                        and chronometr.chrono = spoje.chrono
                    )
                    inner join spojeni on (
                        spojeni.idlocation = spoje.idlocation 
                        and spojeni.packet = spoje.packet
                        and FIND_IN_SET(spojeni.odZ, odZ) > 0
                        and not FIND_IN_SET(spojeni.doZ, outdoZ) > 0
                    )
                    where spoje.idlocation = location 
                    and spoje.packet = packet
                    and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod 
                    and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo);
                END
            ");

            // Direct route procedure
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `prima_linka_pack`(
                    in loc int, 
                    in pack int,
                    in z int,
                    in d int,
                    in casod int,
                    in casdo int,
                    in den varchar(255)
                )
                BEGIN
                    select distinct
                        spoje.c_linky,
                        chronometr.c_zastavky as od_zastavky,
                        bchrono.c_zastavky as do_zastavky,
                        chronometr.c_tarif as od_tarif,
                        bchrono.c_tarif as do_tarif,
                        spoje.smer,
                        spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek as startCAS,
                        spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek as endCAS,
                        bchrono.doba_pocatek - chronometr.doba_pocatek as doba_jizdy
                    from spoje
                    inner join chronometr on (
                        chronometr.idlocation = loc 
                        and chronometr.c_linky = spoje.c_linky 
                        and chronometr.smer = spoje.smer
                        and chronometr.chrono = spoje.chrono 
                        and chronometr.packet = pack
                    )
                    where spoje.idlocation = loc 
                    and spoje.packet = pack
                    and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod
                    and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo);
                END
            ");

            // Utility procedures
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `OptimizeDbForTables`()
                BEGIN
                    OPTIMIZE TABLE jrtypes;
                    OPTIMIZE TABLE idtimepozn;
                    OPTIMIZE TABLE chronometr;
                    OPTIMIZE TABLE distance;
                    OPTIMIZE TABLE sdruz;
                    OPTIMIZE TABLE pesobus;
                    OPTIMIZE TABLE kalendar;
                    OPTIMIZE TABLE pevnykod;
                    OPTIMIZE TABLE linky;
                    OPTIMIZE TABLE prestupy;
                    OPTIMIZE TABLE prices;
                    OPTIMIZE TABLE smer;
                    OPTIMIZE TABLE spoje;
                    OPTIMIZE TABLE zastavky;
                    OPTIMIZE TABLE zaslinky;
                    OPTIMIZE TABLE info;
                    OPTIMIZE TABLE packets;
                    OPTIMIZE TABLE spojeni;
                END
            ");

            migrationBuilder.Sql(@"
                CREATE PROCEDURE `DropSelectedPackage`(IN locationId int, IN packetId int)
                BEGIN
                    DELETE FROM chronometr WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM distance WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM idtimepozn WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM info WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM jrtypes WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM kalendar WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM linky WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM pesobus WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM pevnykod WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM prestupy WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM prices WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM sdruz WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM smer WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM spoje WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM spojeni WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM zastavky WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM zaslinky WHERE idlocation=locationId and packet=packetId;
                    DELETE FROM packets WHERE location=locationId and packet=packetId;
                END
            ");
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `getOdjezdyZastavka`");
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `prima_linka_pack`");
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `OptimizeDbForTables`");
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `DropSelectedPackage`");
        }
    }
}