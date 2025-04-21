using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace JrvNetMigration.Migrations
{
    public partial class AddTransferProcedures : Migration
    {
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            // Transfer route procedure
            migrationBuilder.Sql(@"
                CREATE PROCEDURE `jeden_prestup_pack`(
                    in loc int, 
                    in pack int, 
                    in z int, 
                    in d int, 
                    in casod int, 
                    in casdo int, 
                    in minprestup int, 
                    in den varchar(255)
                )
                BEGIN
                    DROP TEMPORARY TABLE IF EXISTS zlink;
                    CREATE TEMPORARY TABLE zlink (
                        c_linky1 varchar(6),
                        c_tarif1 int(2),
                        c_zastavky1 int(4),
                        c_tarif2 int(2),
                        c_zastavky2 int(4),
                        c_linky2 varchar(6),
                        c_tarif3 int(2),
                        c_zastavky3 int(4),
                        c_tarif4 int(2),
                        c_zastavky4 int(4),
                        odjezd1 int(4),
                        prijezd1 int(4),
                        c_spojeodjezd2 int(2)
                    ) ENGINE = MEMORY;

                    INSERT INTO zlink
                    SELECT DISTINCT
                        z1.c_linky as c_linky1,
                        z1.c_tarif as c_tarif1,
                        z1.c_zastavky as c_zastavky1,
                        z2.c_tarif as c_tarif2,
                        z2.c_zastavky as c_zastavky2,
                        z3.c_linky as c_linky2,
                        z3.c_tarif as c_tarif3,
                        z3.c_zastavky as c_zastavky3,
                        z4.c_tarif as c_tarif4,
                        z4.c_zastavky as c_zastavky4,
                        s1.c_spoje as odjezd1,
                        s1.c_spoje as prijezd1,
                        s2.c_spoje as c_spojeodjezd2
                    FROM zaslinky z1
                    INNER JOIN zaslinky z2 ON (
                        z1.c_linky = z2.c_linky 
                        AND z1.idlocation = z2.idlocation 
                        AND z1.packet = z2.packet
                    )
                    INNER JOIN zaslinky z3 ON (
                        z2.c_zastavky = z3.c_zastavky 
                        AND z2.idlocation = z3.idlocation 
                        AND z2.packet = z3.packet
                    )
                    INNER JOIN zaslinky z4 ON (
                        z3.c_linky = z4.c_linky 
                        AND z3.idlocation = z4.idlocation 
                        AND z3.packet = z4.packet
                    )
                    INNER JOIN spoje s1 ON (
                        z1.c_linky = s1.c_linky 
                        AND z1.idlocation = s1.idlocation 
                        AND z1.packet = s1.packet
                    )
                    INNER JOIN spoje s2 ON (
                        z3.c_linky = s2.c_linky 
                        AND z3.idlocation = s2.idlocation 
                        AND z3.packet = s2.packet
                    )
                    WHERE z1.idlocation = loc 
                    AND z1.packet = pack
                    AND z1.c_zastavky = z
                    AND z4.c_zastavky = d;

                    SELECT * FROM zlink ORDER BY odjezd1, c_spojeodjezd2;
                END
            ");

            // Utility functions
            migrationBuilder.Sql(@"
                CREATE FUNCTION `bcode`(location int, packet int, pk int) 
                RETURNS int
                DETERMINISTIC
                BEGIN
                    SET @poradi := -1;
                    IF (pk = 0) THEN
                        RETURN 0;
                    ELSE
                        RETURN (
                            SELECT bcode FROM (
                                SELECT c_kodu, 
                                       @poradi := @poradi + 1 AS poradi, 
                                       POW(2, @poradi) AS bcode 
                                FROM pevnykod 
                                WHERE pevnykod.idlocation = location 
                                AND pevnykod.packet = packet 
                                AND pevnykod.caspozn = 1 
                                ORDER BY pevnykod.c_kodu
                            ) pozn
                            WHERE pozn.c_kodu = pk
                        );
                    END IF;
                END
            ");

            migrationBuilder.Sql(@"
                CREATE FUNCTION `bdecode`(location int, packet int, inbcode int, inc_kodu int) 
                RETURNS varchar(255)
                DETERMINISTIC
                BEGIN
                    SET @poradi := -1;
                    SET @outret := '';

                    SELECT GROUP_CONCAT(oznaceni SEPARATOR ', ') INTO @outret
                    FROM (
                        SELECT c_kodu, oznaceni
                        FROM (
                            SELECT c_kodu, 
                                   @poradi := @poradi + 1 AS poradi, 
                                   POW(2, @poradi) AS bcode, 
                                   oznaceni 
                            FROM pevnykod 
                            WHERE pevnykod.idlocation = location 
                            AND pevnykod.packet = packet 
                            AND pevnykod.caspozn = 1 
                            ORDER BY pevnykod.c_kodu
                        ) pozn
                        WHERE (bcode & inbcode) = bcode 
                        AND c_kodu = inc_kodu 
                        GROUP BY c_kodu
                    ) final;

                    RETURN @outret;
                END
            ");
        }

        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.Sql("DROP PROCEDURE IF EXISTS `jeden_prestup_pack`");
            migrationBuilder.Sql("DROP FUNCTION IF EXISTS `bcode`");
            migrationBuilder.Sql("DROP FUNCTION IF EXISTS `bdecode`");
        }
    }
}