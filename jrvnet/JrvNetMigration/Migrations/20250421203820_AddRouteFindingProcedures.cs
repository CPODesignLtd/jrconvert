using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace JrvNetMigration.Migrations
{
    public partial class AddRouteFindingProcedures : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            // Station departures procedure
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `getOdjezdyZastavkaCasy`(
                    in location int, 
                    in packet int, 
                    in odCAS int, 
                    in doCAS int, 
                    in odZ int, 
                    in den int, 
                    in datum varchar(10)
                )
                BEGIN
                    select SQL_BUFFER_RESULT distinct
                        (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
                        linky.nazev_linky,
                        linky.c_linkysort,
                        linky.c_linky,
                        zaslinky.c_zastavky,
                        zaslinky.c_tarif,
                        spoje.doprava
                    from zaslinky 
                    left outer join linky on (
                        zaslinky.c_linky = linky.c_linky and 
                        zaslinky.idlocation = linky.idlocation and 
                        zaslinky.packet = linky.packet and 
                        jr_od <= datum and 
                        jr_do >= datum
                    )
                    inner join spoje on (
                        spoje.c_linky = zaslinky.c_linky and 
                        spoje.idlocation = location and 
                        spoje.packet = packet
                    )
                    inner join chronometr on (
                        chronometr.c_linky = spoje.c_linky and 
                        chronometr.smer = spoje.smer and 
                        chronometr.chrono = spoje.chrono and
                        chronometr.idlocation = location and 
                        chronometr.packet = packet
                    )
                    where zaslinky.idlocation = location 
                    and zaslinky.packet = packet
                    and zaslinky.c_zastavky = odZ
                    and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= odCAS
                    and (doCAS = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= doCAS)
                    and (spoje.kodpozn & den) > 0
                    order by CASod;
                END
            ");

            // Connection search procedure
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `getSpojeOdDo`(
                    in location int, 
                    in packet int, 
                    in pocatek int, 
                    in cil int, 
                    in cas int, 
                    in mincekani int, 
                    in maxcekani int, 
                    in den int
                )
                BEGIN
                    select distinct * from (
                        select distinct
                            zastavkyoddo.c_linky,
                            zastavkyoddo.od_zastavky,
                            zastavkyoddo.do_zastavky,
                            zastavkyoddo.od_tarif,
                            zastavkyoddo.do_tarif,
                            zastavkyoddo.smer,
                            spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek as startCAS,
                            spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek as endCAS,
                            bchrono.doba_pocatek - chronometr.doba_pocatek as doba_jizdy
                        from zastavkyoddo
                        inner join spoje on (
                            spoje.c_linky = zastavkyoddo.c_linky and 
                            spoje.idlocation = location and 
                            spoje.packet = packet and 
                            (spoje.kodpozn & den) > 0
                        )
                        inner join chronometr on (
                            chronometr.c_linky = spoje.c_linky and 
                            chronometr.smer = spoje.smer and
                            chronometr.chrono = spoje.chrono and
                            chronometr.idlocation = location and 
                            chronometr.packet = packet and
                            chronometr.c_tarif = zastavkyoddo.od_tarif
                        )
                        inner join chronometr bchrono on (
                            bchrono.c_linky = spoje.c_linky and 
                            bchrono.smer = spoje.smer and
                            bchrono.chrono = spoje.chrono and
                            bchrono.idlocation = location and 
                            bchrono.packet = packet and
                            bchrono.c_tarif = zastavkyoddo.do_tarif
                        )
                        where zastavkyoddo.idlocation = location 
                        and zastavkyoddo.packet = packet
                        and zastavkyoddo.od_zastavky = pocatek 
                        and zastavkyoddo.do_zastavky = cil
                        and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= cas
                        having doba_jizdy >= mincekani 
                        and (maxcekani = -1 or doba_jizdy <= maxcekani)
                    ) vysledek 
                    order by startCAS;
                END
            ");

            // Initialize structures procedure
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `finalize_import`(in location int, in packet int)
                BEGIN
                    update chronometr set c_zastavky = (
                        select c_zastavky 
                        from zaslinky 
                        where zaslinky.idlocation = location 
                        and zaslinky.packet = packet 
                        and zaslinky.c_linky = chronometr.c_linky 
                        and zaslinky.c_tarif = chronometr.c_tarif
                    ) 
                    where chronometr.idlocation = location 
                    and chronometr.packet = packet;

                    update zasspoje set c_zastavky = (
                        select c_zastavky 
                        from zaslinky 
                        where zaslinky.idlocation = location 
                        and zaslinky.packet = packet 
                        and zaslinky.c_linky = zasspoje.c_linky 
                        and zaslinky.c_tarif = zasspoje.c_tarif
                    ) 
                    where zasspoje.idlocation = location 
                    and zasspoje.packet = packet;

                    update linky set linky.smerb = null 
                    where linky.smerb = 'null' 
                    and linky.idlocation = location 
                    and linky.packet = packet;

                    update zastavky set zastavky.c_zastavkysort = zastavky.c_zastavky 
                    where zastavky.idlocation = location 
                    and zastavky.packet = packet;
                END
            ");
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `getOdjezdyZastavkaCasy`");
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `getSpojeOdDo`");
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `finalize_import`");
        }
    }
}