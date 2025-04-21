SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `import`.`chronometr`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SMER` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `CHRONO` int UNSIGNED NOT NULL DEFAULT 0,
  `C_TARIF` int NOT NULL DEFAULT 0,
  `C_ZASTAVKY` int NULL DEFAULT NULL,
  `DOBA_JIZDY` int NULL DEFAULT NULL,
  `DOBA_POCATEK` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NOT NULL DEFAULT 21,
  PRIMARY KEY (`IDLOCATION`, `PACKET`, `C_LINKY`, `SMER`, `CHRONO`, `C_TARIF`) USING BTREE,
  INDEX `Index_2`(`DOBA_JIZDY` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC) USING BTREE,
  INDEX `Index_1`(`C_LINKY` ASC, `SMER` ASC, `CHRONO` ASC, `C_TARIF` ASC) USING BTREE,
  INDEX `Index_4`(`IDLOCATION` ASC, `PACKET` ASC, `C_LINKY` ASC, `SMER` ASC, `CHRONO` ASC, `C_TARIF` ASC, `DOBA_JIZDY` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'InnoDB free: 192512 kB' ;

CREATE TABLE `import`.`distance`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `C_TARIF` int NULL DEFAULT NULL,
  `C_ZASTAVKY` int NULL DEFAULT NULL,
  `DISTANCE` int NULL DEFAULT NULL,
  `IDLOCATION` int NULL DEFAULT NULL,
  `PACKET` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ;

CREATE TABLE `import`.`info`  (
  `C_LINKY` varchar(11) CHARACTER SET ujis COLLATE ujis_japanese_ci NOT NULL,
  `C_TARIF` int NOT NULL,
  `INFO` text CHARACTER SET ujis COLLATE ujis_japanese_ci NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int UNSIGNED NOT NULL,
  `SMER` int NOT NULL,
  PRIMARY KEY (`C_LINKY`, `C_TARIF`, `IDLOCATION`, `PACKET`, `SMER`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = ujis COLLATE = ujis_japanese_ci ;

CREATE TABLE `import`.`jrtypes`  (
  `idlocation` int UNSIGNED NOT NULL,
  `packet` int UNSIGNED NOT NULL,
  `c_sloupce` tinyint UNSIGNED NOT NULL,
  `nazev_sloupce` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `idtimepozn` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idtimepozn`, `idlocation`, `packet`, `c_sloupce`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6114 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `import`.`jrvargrfs`  (
  `idtimepozn` int UNSIGNED NOT NULL,
  `c_kodu` int NOT NULL,
  PRIMARY KEY (`idtimepozn`, `c_kodu`) USING BTREE,
  INDEX `IDX_IDTIMEPOZN`(`idtimepozn`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ;

CREATE TABLE `import`.`kalendar`  (
  `DATUM` date NULL DEFAULT NULL,
  `PK` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NULL DEFAULT 21,
  INDEX `Index_1`(`IDLOCATION` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_czech_ci ;

CREATE TABLE `import`.`linky`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `NAZEV_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `REZERVA` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `C_LICENCE` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `LICENCE_OD` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `LICENCE_DO` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `JR_OD` date NULL DEFAULT NULL,
  `JR_DO` date NULL DEFAULT NULL,
  `C_LINKYSORT` int UNSIGNED NULL DEFAULT NULL,
  `DOPRAVA` varchar(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `vyber` tinyint(1) NULL DEFAULT 1,
  `SMERA` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `SMERB` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `PACKET` int NULL DEFAULT 21,
  `popis` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  INDEX `Index_2`(`C_LINKYSORT` ASC) USING BTREE,
  INDEX `Index_1`(`C_LINKY` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC) USING BTREE,
  INDEX `Index_4`(`IDLOCATION` ASC, `C_LINKY` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'InnoDB free: 316416 kB' ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`location`  (
  `IDLOCATION` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `NAZEV` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `ICON` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `LOGO` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `URL` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `SKELETON_USERNAME` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `SKELETON_TICKET` varchar(45) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `SKELETON_URL` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `MAP` tinyint(1) NULL DEFAULT 0,
  `SKELETON_VEHICLE` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  PRIMARY KEY (`IDLOCATION`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100 CHARACTER SET = utf8 COLLATE = utf8_czech_ci ;

CREATE TABLE `import`.`lzastavky`  (
  `c_linky` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `c_tarif` int UNSIGNED NOT NULL,
  `nazev_zastavky` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `smer` tinyint(1) UNSIGNED NOT NULL,
  `stavi` tinyint(1) UNSIGNED NOT NULL,
  `idlocation` int UNSIGNED NOT NULL,
  `packet` int UNSIGNED NOT NULL,
  INDEX `idlocation_packet`(`idlocation` ASC, `packet` ASC, `c_linky` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`packets`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `packet` int NULL DEFAULT NULL,
  `jr_od` date NULL DEFAULT NULL,
  `jr_do` date NULL DEFAULT NULL,
  `location` int NULL DEFAULT NULL,
  `jeplatny` tinyint NULL DEFAULT 1,
  `mobil_data_version` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2522 CHARACTER SET = utf8 COLLATE = utf8_czech_ci ;

CREATE TABLE `import`.`pesobus`  (
  `OD_ZASTAVKY` int UNSIGNED NOT NULL DEFAULT 0,
  `DO_ZASTAVKY` int UNSIGNED NOT NULL DEFAULT 0,
  `CAS` int UNSIGNED NULL DEFAULT NULL,
  `VZDALENOST` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL DEFAULT 0,
  `PACKET` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`IDLOCATION`, `PACKET`, `OD_ZASTAVKY`, `DO_ZASTAVKY`) USING BTREE,
  INDEX `OD_DO`(`OD_ZASTAVKY` ASC, `DO_ZASTAVKY` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`pevnykod`  (
  `C_KODU` int UNSIGNED NOT NULL,
  `OZNACENI` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `REZERVA` varchar(254) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CASPOZN` tinyint(1) NULL DEFAULT NULL,
  `SHOWING` tinyint(1) NULL DEFAULT NULL,
  `OBR` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NOT NULL,
  `I_P` tinyint(1) NULL DEFAULT NULL,
  `SDRUZ` int NULL DEFAULT 0,
  `SHOWING1` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`IDLOCATION`, `PACKET`, `C_KODU`) USING BTREE,
  INDEX `Index_2`(`IDLOCATION` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC, `CASPOZN` ASC) USING BTREE,
  INDEX `Index_1`(`IDLOCATION` ASC, `C_KODU` ASC, `CASPOZN` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `import`.`pictograms`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `idlocation` int NULL DEFAULT NULL,
  `nazev` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idlocation`(`idlocation` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`prestupy`  (
  `c_linky1` varchar(6) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `c_zastavky1` int UNSIGNED NULL DEFAULT NULL,
  `c_tarif1` int UNSIGNED NULL DEFAULT NULL,
  `c_linky2` varchar(6) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `c_zastavky2` int UNSIGNED NULL DEFAULT NULL,
  `c_tarif2` int UNSIGNED NULL DEFAULT NULL,
  `idlocation` int UNSIGNED NULL DEFAULT NULL,
  `packet` int UNSIGNED NULL DEFAULT 21,
  INDEX `Index_1`(`c_linky1`, `c_zastavky1`, `c_tarif1`, `c_linky2`, `c_zastavky2`, `c_tarif2`) USING BTREE,
  INDEX `Index_2`(`idlocation`, `packet`) USING BTREE,
  INDEX `Index_3`(`c_linky1`, `c_linky2`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_czech_ci PACK_KEYS = 1 ;

CREATE TABLE `import`.`prices`  (
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NOT NULL,
  `ZONA` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CENA` double NULL DEFAULT NULL,
  `MENA` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CAS` int NULL DEFAULT NULL,
  `COUNTZONE` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ;

CREATE TABLE `import`.`reklama`  (
  `ID` int NOT NULL AUTO_INCREMENT,
  `IDLOCATION` int UNSIGNED NULL DEFAULT NULL,
  `POPIS` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `SOUBOR` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `SHOW_OD` date NULL DEFAULT NULL,
  `SHOW_DO` date NULL DEFAULT NULL,
  `DELKA` int UNSIGNED NULL DEFAULT NULL,
  `ACTIVE` tinyint(1) NULL DEFAULT 0,
  `HEADERTXT` text CHARACTER SET utf8 COLLATE utf8_czech_ci NULL,
  `TXT` text CHARACTER SET utf8 COLLATE utf8_czech_ci NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  UNIQUE INDEX `ID_UNIQUE`(`ID` ASC) USING BTREE,
  INDEX `LOCATION`(`IDLOCATION` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 117 CHARACTER SET = utf8 COLLATE = utf8_czech_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`sdruz`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `idlocation` int NULL DEFAULT NULL,
  `packet` int NULL DEFAULT NULL,
  `bcode` int NULL DEFAULT NULL,
  `sc_kodu` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `i_loc`(`idlocation`, `packet`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 871 CHARACTER SET = utf8 COLLATE = utf8_general_ci ;

CREATE TABLE `import`.`smer`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SMERA` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `SMERB` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NULL DEFAULT 21,
  PRIMARY KEY (`C_LINKY`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `import`.`spoje`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `C_SPOJE` int UNSIGNED NULL DEFAULT NULL,
  `SMER` tinyint(1) UNSIGNED NULL DEFAULT NULL,
  `CHRONO` int UNSIGNED NULL DEFAULT NULL,
  `PK1` int UNSIGNED NULL DEFAULT NULL,
  `PK2` int UNSIGNED NULL DEFAULT NULL,
  `PK3` int UNSIGNED NULL DEFAULT NULL,
  `PK4` int UNSIGNED NULL DEFAULT NULL,
  `PK5` int UNSIGNED NULL DEFAULT NULL,
  `PK6` int UNSIGNED NULL DEFAULT NULL,
  `PK7` int UNSIGNED NULL DEFAULT NULL,
  `PK8` int UNSIGNED NULL DEFAULT NULL,
  `PK9` int UNSIGNED NULL DEFAULT NULL,
  `PK10` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `KURZ` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `C_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `C_ZASTAVKY` int UNSIGNED NULL DEFAULT NULL,
  `HH` tinyint(1) UNSIGNED NULL DEFAULT NULL,
  `MM` tinyint(1) UNSIGNED NULL DEFAULT NULL,
  `KODPOZN` int UNSIGNED NULL DEFAULT 0,
  `PACKET` int NULL DEFAULT 21,
  `OC_SPOJE` int NULL DEFAULT NULL,
  `IDKURZ` int NULL DEFAULT NULL,
  `DOLINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `DOKURZU` int NULL DEFAULT NULL,
  `KATEG` int NULL DEFAULT NULL,
  `VOZ` int NULL DEFAULT 1,
  `VLASTNOSTI` int NULL DEFAULT 0,
  INDEX `iC_LINKY`(`C_LINKY` ASC, `C_SPOJE` ASC, `SMER` ASC, `CHRONO` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC, `C_LINKY` ASC, `C_SPOJE` ASC, `SMER` ASC, `CHRONO` ASC, `C_TARIF` ASC, `C_ZASTAVKY` ASC, `HH` ASC, `MM` ASC) USING BTREE,
  INDEX `i4`(`IDLOCATION` ASC, `PACKET` ASC, `C_LINKY` ASC, `SMER` ASC, `KODPOZN` ASC, `CHRONO` ASC, `HH` ASC, `MM` ASC) USING BTREE,
  INDEX `i5`(`IDLOCATION` ASC, `PACKET` ASC, `KODPOZN` ASC, `DOLINKY` ASC, `DOKURZU` ASC) USING BTREE,
  INDEX `Index_2`(`IDLOCATION` ASC, `PACKET` ASC, `KODPOZN` ASC, `HH` ASC, `MM` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'InnoDB free: 365568 kB' ;

CREATE TABLE `import`.`spojeni`  (
  `odZ` int NOT NULL,
  `odT` int NOT NULL,
  `doLinka` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `smer` tinyint(1) NOT NULL,
  `doZ` int NOT NULL,
  `doT` int NOT NULL,
  `idlocation` int NOT NULL,
  `packet` int NOT NULL,
  `vaha` int NOT NULL,
  `distance` int NULL DEFAULT 0,
  PRIMARY KEY (`idlocation`, `packet`, `odZ`, `doZ`, `doLinka`, `odT`, `doT`, `smer`, `vaha`) USING BTREE,
  INDEX `i_linka`(`idlocation` ASC, `packet` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ;

CREATE TABLE `import`.`testtable`  (
  `idnew_table` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`idnew_table`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`USERS`  (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `PASS` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `IDLOCATION` int NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `import`.`zaslinky`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `C_TARIF` int UNSIGNED NOT NULL DEFAULT 0,
  `C_ZASTAVKY` int NULL DEFAULT NULL,
  `PK1` int UNSIGNED NULL DEFAULT NULL,
  `PK2` int UNSIGNED NULL DEFAULT NULL,
  `PK3` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `A1_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `A2_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `B1_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `B2_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `PACKET` int NOT NULL DEFAULT 21,
  `PRESTUP` int NULL DEFAULT 0,
  `VOZ_A` int NULL DEFAULT 0,
  `VOZ_B` int NULL DEFAULT 0,
  `VOZ` int NULL DEFAULT 1,
  `ZAST_A` int NULL DEFAULT 1,
  `ZAST_B` int NULL DEFAULT 1,
  `PK4` int NULL DEFAULT NULL,
  `PK5` int NULL DEFAULT NULL,
  `PK6` int NULL DEFAULT NULL,
  `ALT_NAZEV_A` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ALT_NAZEV_B` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`IDLOCATION`, `C_LINKY`, `C_TARIF`, `PACKET`) USING BTREE,
  INDEX `Index_1`(`C_TARIF` ASC, `C_ZASTAVKY` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC, `C_LINKY` ASC, `C_ZASTAVKY` ASC) USING BTREE,
  INDEX `Index_5`(`IDLOCATION` ASC, `PACKET` ASC, `C_ZASTAVKY` ASC, `PRESTUP` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`zasspoje`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `C_SPOJE` int UNSIGNED NOT NULL DEFAULT 0,
  `C_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `C_ZASTAVKY` int NULL DEFAULT NULL,
  `HH` tinyint(1) NOT NULL DEFAULT 0,
  `MM` tinyint(1) NOT NULL DEFAULT 0,
  `SMER` tinyint(1) NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NOT NULL DEFAULT 21,
  PRIMARY KEY (`IDLOCATION`, `HH`, `MM`, `C_LINKY`, `C_SPOJE`, `PACKET`) USING BTREE,
  INDEX `Index_2`(`IDLOCATION` ASC) USING BTREE,
  INDEX `Index_1`(`C_LINKY` ASC, `C_SPOJE` ASC, `SMER` ASC, `C_TARIF` ASC) USING BTREE,
  INDEX `FK_zasspoje_zaslinky`(`IDLOCATION` ASC, `C_LINKY` ASC, `C_TARIF` ASC) USING BTREE,
  INDEX `Index_6`(`HH` ASC, `MM` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC, `C_LINKY` ASC, `C_SPOJE` ASC, `SMER` ASC, `HH` ASC, `MM` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`zasspoje_pozn`  (
  `C_LINKY` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `C_SPOJE` int UNSIGNED NULL DEFAULT NULL,
  `C_TARIF` int UNSIGNED NULL DEFAULT NULL,
  `C_ZASTAVKY` int NULL DEFAULT NULL,
  `PK1` int UNSIGNED NULL DEFAULT NULL,
  `PK2` int UNSIGNED NULL DEFAULT NULL,
  `DPK1` int UNSIGNED NULL DEFAULT NULL,
  `DPK2` int UNSIGNED NULL DEFAULT NULL,
  `DPK3` int UNSIGNED NULL DEFAULT NULL,
  `DPK4` int UNSIGNED NULL DEFAULT NULL,
  `DPK5` int UNSIGNED NULL DEFAULT NULL,
  `DPK6` int UNSIGNED NULL DEFAULT NULL,
  `DPK7` int UNSIGNED NULL DEFAULT NULL,
  `DPK8` int UNSIGNED NULL DEFAULT NULL,
  `DPK9` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NULL DEFAULT 21,
  INDEX `iLSTZ`(`C_LINKY` ASC, `C_SPOJE` ASC, `C_TARIF` ASC, `C_ZASTAVKY` ASC, `IDLOCATION` ASC, `PACKET` ASC) USING BTREE,
  INDEX `Index_2`(`IDLOCATION` ASC, `PACKET` ASC, `C_LINKY` ASC, `C_SPOJE` ASC, `C_TARIF` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPRESSED;

CREATE TABLE `import`.`zastavky`  (
  `C_ZASTAVKY` int NOT NULL DEFAULT 0,
  `NAZEV` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `PK1` int UNSIGNED NULL DEFAULT NULL,
  `PK2` int UNSIGNED NULL DEFAULT NULL,
  `PK3` int UNSIGNED NULL DEFAULT NULL,
  `PK4` int UNSIGNED NULL DEFAULT NULL,
  `PK5` int UNSIGNED NULL DEFAULT NULL,
  `PK6` int UNSIGNED NULL DEFAULT NULL,
  `IDLOCATION` int UNSIGNED NOT NULL,
  `PACKET` int NULL DEFAULT 21,
  `LOCA` double NULL DEFAULT NULL,
  `LOCB` double NULL DEFAULT NULL,
  `ZKRATKA` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `C_ZASTAVKYSORT` int NULL DEFAULT 1,
  `PASSPORT` int NULL DEFAULT 0,
  INDEX `Index_1`(`C_ZASTAVKY` ASC) USING BTREE,
  INDEX `Index_2`(`IDLOCATION` ASC) USING BTREE,
  INDEX `Index_3`(`IDLOCATION` ASC, `C_ZASTAVKY` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`analysetable`()
BEGIN
select * from spoje procedure analyse(1);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` FUNCTION `import`.`bcode`(location int, packet int, pk int)
 RETURNS int(11)
BEGIN
SET @poradi := -1;
if (pk = 0) then
RETURN(0);
ELSE
RETURN(
select bcode from
(select c_kodu, @poradi:=@poradi+1 as poradi, pow(2, @poradi) as bcode 
from pevnykod where pevnykod.idlocation = location and pevnykod.packet = packet and pevnykod.caspozn = 1 order by pevnykod.c_kodu) pozn
where pozn.c_kodu = pk);
END IF;
/*RETURN(v_isodd);*/
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`bcodelist`(in location int, in packet int)
BEGIN
SET @poradi := -1;

select c_kodu, bcode from
(select c_kodu, @poradi:=@poradi+1 as poradi, pow(2, @poradi) as bcode 
from pevnykod where pevnykod.idlocation = location and pevnykod.packet = packet and pevnykod.caspozn = 1 order by pevnykod.c_kodu) pozn;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`bcodelist_day`(in location int, in packet int, in den varchar(255))
BEGIN
SET @poradi := -1;

select c_kodu, bcode, oznaceni, den from
(select c_kodu, @poradi:=@poradi+1 as poradi, pow(2, @poradi) as bcode, oznaceni
from pevnykod where pevnykod.idlocation = location and pevnykod.packet = packet and pevnykod.caspozn = 1 
order by pevnykod.c_kodu) pozn
where /*LOCATE(*/trim(pozn.oznaceni) in (den COLLATE utf8_general_ci)/*('X', 'PP')*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`bdecode`(in location int, in packet int, in inbcode int)
BEGIN
SET @poradi := -1;

select c_kodu, bcode, oznaceni, obr from
(select c_kodu, @poradi:=@poradi+1 as poradi, pow(2, @poradi) as bcode, oznaceni, obr 
from pevnykod where pevnykod.idlocation = location and pevnykod.packet = packet and pevnykod.caspozn = 1 order by pevnykod.c_kodu) pozn
where (bcode & inbcode) = bcode;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` FUNCTION `import`.`bdecode_one`(location int, packet int, inbcode int, inc_kodu int)
 RETURNS int(11)
BEGIN
SET @poradi := -1;
SET @outret := 0;

select count(c_kodu) into @outret from
(select c_kodu, @poradi:=@poradi+1 as poradi, pow(2, @poradi) as bcode, oznaceni, obr 
from pevnykod where pevnykod.idlocation = location and pevnykod.packet = packet and pevnykod.caspozn = 1 order by pevnykod.c_kodu) pozn
where (bcode & inbcode) = bcode and c_kodu = inc_kodu group by c_kodu;

RETURN @outret;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`check_prestupy`()
BEGIN
  DECLARE cursor_VAL INTEGER;
  DECLARE cursor_sousede_VAL INTEGER;
  DECLARE done INT DEFAULT FALSE;

BLOCK1: begin 
DECLARE cursor_i CURSOR FOR select c_zastavky from zastavky where idlocation = 3 and packet = 45 order by c_zastavky;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  
  OPEN cursor_i;
  read_loop: LOOP
    FETCH cursor_i INTO cursor_VAL;
    IF done THEN
      LEAVE read_loop;
    END IF;
    
    BLOCK2: begin
		DECLARE cursor_sousede CURSOR FOR select distinct count(a.kam) from (
			(select 
			(select c_zastavky from zaslinky where IDLOCATION = 3 and packet = 45 and c_tarif < 
			(select c_tarif from zaslinky where idlocation = 3 and packet = 45 and c_zastavky = cursor_VAL and c_linky = linky.c_linky limit 1) and c_linky = linky.c_linky order by c_tarif desc limit 1) as kam
			from linky where idlocation = 3 and packet = 45)
			union
			(select
			(select c_zastavky from zaslinky where IDLOCATION = 3 and packet = 45 and c_tarif > 
			(select c_tarif from zaslinky where idlocation = 3 and packet = 45 and c_zastavky = cursor_val and c_linky = linky.c_linky limit 1) and c_linky = linky.c_linky order by c_tarif limit 1) as kam
			from linky where idlocation = 3 and packet = 45)
			) a
			where a.kam is not null;
		OPEN cursor_sousede;
		SET cursor_sousede_VAL = 1000;
		FETCH cursor_sousede INTO cursor_sousede_VAL;
        IF (cursor_sousede_VAL <= 2) THEN
			UPDATE zaslinky set prestup = 1 where idlocation = 3 and packet = 45 and c_zastavky = cursor_VAL;
        END IF;
        CLOSE cursor_sousede;
    /*SET ret_val = concat(ret_val,';',cursor_val);/* + CAST(cursor_VAL as CHAR);*/
    end BLOCK2;
  END LOOP;
  CLOSE cursor_i;
END BLOCK1;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`clearStructures`()
BEGIN

-- declare the program variables where we'll hold the values we're sending into the procedure;
-- declare as many of them as there are input arguments to the second procedure,
-- with appropriate data types.
DECLARE _location, _packet INT DEFAULT NULL;

-- we need a boolean variable to tell us when the cursor is out of data

DECLARE done TINYINT DEFAULT FALSE;

-- declare a cursor to select the desired columns from the desired source table1
-- the input argument (which you might or might not need) is used in this example for row selection

DECLARE cursor1 -- cursor1 is an arbitrary label, an identifier for the cursor
 CURSOR FOR
 SELECT 
	location,
    packet 
FROM `packets` 
WHERE jr_do < '2019-01-01';
 
/* 
  SELECT 
	t1.c1, 
    t1.c2
  FROM table1 t1
  WHERE c3 = arg1; 
*/
-- this fancy spacing is of course not required; all of this could go on the same line.

-- a cursor that runs out of data throws an exception; we need to catch this.
-- when the NOT FOUND condition fires, "done" -- which defaults to FALSE -- will be set to true,
-- and since this is a CONTINUE handler, execution continues with the next statement.   

DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

-- open the cursor

OPEN cursor1;

my_loop: -- loops have to have an arbitrary label; it's used to leave the loop
LOOP

  -- read the values from the next row that is available in the cursor

  FETCH NEXT FROM cursor1 INTO _location, _packet;

  IF done THEN -- this will be true when we are out of rows to read, so we go to the statement after END LOOP.
    LEAVE my_loop; 
  ELSE -- val1 and val2 will be the next values from c1 and c2 in table t1, 
       -- so now we call the procedure with them for this "row"
    select _location, _packet;
    call DropSelectedPackage(_location,_packet);
  END IF;
END LOOP;

CLOSE cursor1;

END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`clearStructuresKeepOnlyJeplantyPackage`()
BEGIN

-- declare the program variables where we'll hold the values we're sending into the procedure;
-- declare as many of them as there are input arguments to the second procedure,
-- with appropriate data types.
DECLARE _location, _packet INT DEFAULT NULL;

-- we need a boolean variable to tell us when the cursor is out of data

DECLARE done TINYINT DEFAULT FALSE;

-- declare a cursor to select the desired columns from the desired source table1
-- the input argument (which you might or might not need) is used in this example for row selection

DECLARE cursor1 -- cursor1 is an arbitrary label, an identifier for the cursor
 CURSOR FOR
 SELECT 
	location,
    packet 
FROM `packets` 
WHERE jeplatny = 0;
 
/* 
  SELECT 
	t1.c1, 
    t1.c2
  FROM table1 t1
  WHERE c3 = arg1; 
*/
-- this fancy spacing is of course not required; all of this could go on the same line.

-- a cursor that runs out of data throws an exception; we need to catch this.
-- when the NOT FOUND condition fires, "done" -- which defaults to FALSE -- will be set to true,
-- and since this is a CONTINUE handler, execution continues with the next statement.   

DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

-- open the cursor

OPEN cursor1;

my_loop: -- loops have to have an arbitrary label; it's used to leave the loop
LOOP

  -- read the values from the next row that is available in the cursor

  FETCH NEXT FROM cursor1 INTO _location, _packet;

  IF done THEN -- this will be true when we are out of rows to read, so we go to the statement after END LOOP.
    LEAVE my_loop; 
  ELSE -- val1 and val2 will be the next values from c1 and c2 in table t1, 
       -- so now we call the procedure with them for this "row"
    select _location, _packet;
    call DropSelectedPackage(_location, _packet);


     
  END IF;
END LOOP;

CLOSE cursor1;

END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`create_lzastavky`(in location int, in packet int)
BEGIN
  DECLARE cl varchar(11);
  DECLARE ct int;
  DECLARE nz varchar(50);
  DECLARE st int;
  DECLARE bDone int;  
  
DECLARE curs CURSOR FOR select c_linky from linky where linky.idlocation = location and linky.packet = packet;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
  OPEN curs;
  SET bDone = 0;
  REPEAT
    FETCH curs INTO cl;
    INSERT INTO lzastavky (c_linky, c_tarif, nazev_zastavky, smer, stavi, idlocation, packet)
    select cl, zaslinky.c_tarif, (case when zastavky.nazev = '' then zastavky.zkratka else zastavky.nazev end), 0 as smer, st.stavi, location, packet
          from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = cl COLLATE utf8_general_ci and idlocation = location and
          packet = packet and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = cl COLLATE utf8_general_ci and
          idlocation = location and packet = packet and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = cl COLLATE utf8_general_ci and idlocation = location and packet = packet and
          smer = 0 group by c_tarif, smer, chrono) dis group by c_tarif) st
          on (zaslinky.c_tarif = st.c_tarif)
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
          where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_linky = cl COLLATE utf8_general_ci and zaslinky.voz = 1
          ORDER BY zaslinky.c_tarif;	
    INSERT INTO lzastavky (c_linky, c_tarif, nazev_zastavky, smer, stavi, idlocation, packet)
    select cl, zaslinky.c_tarif, (case when zastavky.nazev = '' then zastavky.zkratka else zastavky.nazev end), 1 as smer, st.stavi, location, packet
          from zaslinky left outer join (select c_tarif, (case when sum(globmaxdoba_pocatek - maxdoba_pocatek) = 0 then false else true end) as stavi
          from (select c_tarif, smer, chrono, (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = cl COLLATE utf8_general_ci and idlocation = location and
          packet = packet and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) as globmaxdoba_pocatek,
          case when doba_jizdy = -1 then (select max(doba_pocatek) from savvy_mhdspoje.chronometr ch where c_linky = cl COLLATE utf8_general_ci and
          idlocation = location and packet = packet and ch.smer = chronometr.smer and ch.chrono = chronometr.chrono) else max(doba_pocatek) end as maxdoba_pocatek,
          doba_jizdy from savvy_mhdspoje.chronometr where c_linky = cl COLLATE utf8_general_ci and idlocation = location and packet = packet and
          smer = 1 group by c_tarif, smer, chrono) dis group by c_tarif) st
          on (zaslinky.c_tarif = st.c_tarif)
          left outer join zastavky on (zaslinky.idlocation = zastavky.idlocation and
          zaslinky.packet = zastavky.packet and zaslinky.c_zastavky = zastavky.c_zastavky)
          where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_linky = cl COLLATE utf8_general_ci and zaslinky.voz = 1
          ORDER BY zaslinky.c_tarif DESC;	      
  UNTIL bDone END REPEAT;

  CLOSE curs;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`DropSelectedPackage`(IN locationId int(10), IN packetId int(11))
BEGIN

	-- todo: check if packet exists for location
    -- todo: reklama: define purge data for this
    -- todo: sdruz deine how to remove this
	-- select * from location where idlocation = location;
    -- Error Code: 1175. You are using safe update mode and you tried to update a table without a WHERE that uses a KEY column To disable safe mode, toggle the option in Preferences -> SQL Editor and reconnect.

    delete from jrtypes
    where idlocation=locationId
    and packet=packetId;

    delete from jrvargrfs
    where idtimepozn in (
    select idtimepozn from jrtypes
    where idlocation=locationId
    and packet=packetId);

    delete from chronometr
    where idlocation=locationId
    and packet=packetId;

    delete from distance
    where idlocation=locationId
    and packet=packetId;

    delete from sdruz
    where idlocation=locationId
    and packet=packetId;

    delete from pesobus
    where idlocation=locationId
    and packet=packetId;

    delete from kalendar
    where idlocation=locationId
    and packet=packetId;

    delete from pevnykod
    where idlocation=locationId
    and packet=packetId;

    delete from linky
    where idlocation=locationId
    and packet=packetId;

    delete from prestupy
    where idlocation=locationId
    and packet=packetId;

    delete from prices
    where idlocation=locationId
    and packet=packetId;

    delete from smer
    where idlocation=locationId
    and packet=packetId;

    delete from lzastavky
    where idlocation=locationId
    and packet=packetId;

    delete from spoje
    where idlocation=locationId
    and packet=packetId;

    delete from zastavky
    where idlocation=locationId
    and packet=packetId;

    delete from zaslinky
    where idlocation=locationId
    and packet=packetId;

    delete from info
    where idlocation=locationId
    and packet=packetId;

    delete from packets
    where location=locationId
    and packet=packetId;
	-- finally remove the packet itself
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`eliminate_spoje`(in location int, in packet int, in linka varchar(11))
BEGIN
  DECLARE cl varchar(11);
  DECLARE sm int;
  declare cs int;
  declare bDone int;  
  
IF linka is null THEN BEGIN 

DECLARE curs CURSOR FOR select ven.c_linky, ven.smer, s.c_spoje from (select * from 
(select chronometr.c_linky, chronometr.smer, chronometr.chrono, count(chronometr.doba_jizdy) as elim, chronometr.idlocation, chronometr.packet
from chronometr inner join zaslinky on (chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.c_zastavky = zaslinky.c_zastavky and
chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet) 
where chronometr.idlocation = location and chronometr.packet = packet and chronometr.doba_jizdy >= 0 
and ((chronometr.smer = 0 and zaslinky.zast_a = 1) or (chronometr.smer = 1 and zaslinky.zast_b = 1)) group by chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.idlocation, chronometr.packet) a where a.elim > 1) ven
inner join spoje s on (ven.c_linky = s.c_linky and ven.smer = s.smer and ven.chrono = s.chrono and ven.idlocation = s.idlocation and ven.packet = s.packet);

DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
  OPEN curs;
  SET bDone = 0;
  REPEAT
    FETCH curs INTO cl, sm, cs;
    update spoje set voz = 1 where spoje.c_linky = cl COLLATE utf8_general_ci and spoje.smer = sm  and spoje.c_spoje = cs and spoje.idlocation = location and spoje.packet = packet;
  UNTIL bDone END REPEAT;

  CLOSE curs;
END; 

ELSE BEGIN

DECLARE curs CURSOR FOR select ven.c_linky, ven.smer, s.c_spoje from (select * from 
(select chronometr.c_linky, chronometr.smer, chronometr.chrono, count(chronometr.doba_jizdy) as elim, chronometr.idlocation, chronometr.packet
from chronometr inner join zaslinky on (chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.c_zastavky = zaslinky.c_zastavky and
chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet) 
where chronometr.idlocation = location and chronometr.packet = packet and chronometr.doba_jizdy >= 0 and chronometr.c_linky = linka COLLATE utf8_general_ci
and ((chronometr.smer = 0 and zaslinky.zast_a = 1) or (chronometr.smer = 1 and zaslinky.zast_b = 1)) group by chronometr.c_linky, chronometr.smer, chronometr.chrono, chronometr.idlocation, chronometr.packet) a where a.elim > 1) ven
inner join spoje s on (ven.c_linky = s.c_linky and ven.smer = s.smer and ven.chrono = s.chrono and ven.idlocation = s.idlocation and ven.packet = s.packet);

DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
  OPEN curs;
  SET bDone = 0;
  REPEAT
    FETCH curs INTO cl, sm, cs;
    update spoje set voz = 1 where spoje.c_linky = cl COLLATE utf8_general_ci and spoje.smer = sm  and spoje.c_spoje = cs and spoje.idlocation = location and spoje.packet = packet;
  UNTIL bDone END REPEAT;

  CLOSE curs;
END; 
END IF;

  
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`existCil`(in location int, in packet int, in cil int)
BEGIN
SELECT 
count(doZ)
FROM
spojeni
WHERE
doZ = cil
and spojeni.idlocation = location and spojeni.packet = packet
group by doZ;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`existPrima`(in location int, in packet int, in pocatek int, in cil int)
BEGIN
SELECT 
count(spojeni.odZ)
FROM
spojeni
WHERE
spojeni.odZ = pocatek and spojeni.doZ = cil and idlocation=location and packet=packet
/*zaslinky a inner join zaslinky b on (a.c_linky = b.c_linky 
and a.idlocation = location and b.idlocation = location
and a.packet = packet and b.packet = packet)
WHERE
a.c_zastavky = pocatek and b.c_zastavky = cil*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`finalize_import`(in location int, in packet int)
BEGIN
update chronometr set c_zastavky = 
(select c_zastavky from zaslinky where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_linky = chronometr.c_linky and zaslinky.c_tarif = chronometr.c_tarif) 
where chronometr.idlocation = location and chronometr.packet = packet;

update zasspoje set c_zastavky = 
(select c_zastavky from zaslinky where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_linky = zasspoje.c_linky and zaslinky.c_tarif = zasspoje.c_tarif) 
where zasspoje.idlocation = location and zasspoje.packet = packet;

update zasspoje_pozn set c_zastavky = 
(select c_zastavky from zaslinky where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_linky = zasspoje_pozn.c_linky and zaslinky.c_tarif = zasspoje_pozn.c_tarif) 
where zasspoje_pozn.idlocation = location and zasspoje_pozn.packet = packet;

/*update spoje set voz = 0 where 1 in
(select 1 from (
select chronometr.c_linky, chronometr.smer, chronometr.chrono from chronometr left outer join zaslinky on
(chronometr.c_linky = zaslinky.c_linky and chronometr.c_tarif = zaslinky.c_tarif and chronometr.idlocation = zaslinky.idlocation and chronometr.packet = zaslinky.packet)
where chronometr.idlocation = location and chronometr.packet = packet and voz = 0 and doba_jizdy > -1 group by chronometr.c_linky, chronometr.smer, chronometr.chrono
) n where n.c_linky = spoje.c_linky and n.smer = spoje.smer and n.chrono = spoje.chrono)
and spoje.idlocation = location and spoje.packet= packet;*/

update zaslinky set zast_a = voz, zast_b = voz where zaslinky.idlocation = location and zaslinky.packet = packet;

update spoje set voz = 0 where spoje.idlocation = location and spoje.packet = packet;

IF location = 3 THEN 
/*  update spoje set vlastnosti = 1 where spoje.idlocation = location and spoje.packet = packet and spoje.vlastnosti = 2048;*/
  update spoje set vlastnosti = 1 where spoje.idlocation = location and spoje.packet = packet and spoje.vlastnosti > 1;
END IF;

call eliminate_spoje(location, packet, null);

update pevnykod set showing1 = showing where pevnykod.idlocation = location and pevnykod.packet = packet;

update spoje set 
c_zastavky = (select c_zastavky from zasspoje where spoje.c_linky = zasspoje.c_linky and spoje.c_spoje = zasspoje.c_spoje and spoje.idlocation = zasspoje.idlocation and spoje.packet = zasspoje.packet), 
c_tarif = (select c_tarif from zasspoje where spoje.c_linky = zasspoje.c_linky and spoje.c_spoje = zasspoje.c_spoje and spoje.idlocation = zasspoje.idlocation and spoje.packet = zasspoje.packet), 
hh = (select hh from zasspoje where spoje.c_linky = zasspoje.c_linky and spoje.c_spoje = zasspoje.c_spoje and spoje.idlocation = zasspoje.idlocation and spoje.packet = zasspoje.packet), 
mm = (select mm from zasspoje where spoje.c_linky = zasspoje.c_linky and spoje.c_spoje = zasspoje.c_spoje and spoje.idlocation = zasspoje.idlocation and spoje.packet = zasspoje.packet) 
where spoje.idlocation = location and spoje.packet = packet;

update spoje set kodpozn = coalesce(coalesce(bcode(location, packet, spoje.pk1), 0) + coalesce(bcode(location, packet, spoje.pk2), 0) + coalesce(bcode(location, packet, spoje.pk3), 0) + 
coalesce(bcode(location, packet, spoje.pk4), 0) + coalesce(bcode(location, packet, spoje.pk5), 0) + coalesce(bcode(location, packet, spoje.pk6), 0) + coalesce(bcode(location, packet, spoje.pk7), 0) + 
coalesce(bcode(location, packet, spoje.pk8), 0) + coalesce(bcode(location, packet, spoje.pk9), 0) + coalesce(bcode(location, packet, spoje.pk10), 0), 0) 
where idlocation = location and packet = packet;

update linky set linky.smera = null where linky.smera = 'null' and linky.idlocation = location and linky.packet = packet;
update linky set linky.smerb = null where linky.smerb = 'null' and linky.idlocation = location and linky.packet = packet;

update zastavky set zastavky.c_zastavkysort = zastavky.c_zastavky where zastavky.idlocation = location and zastavky.packet = packet;

IF location = 6 THEN
call create_lzastavky(location, packet);
END IF;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyCil`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int)
BEGIN
select 
spoje.c_linky, spojeni.doZ as odZ, spojeni.odZ as doZ, chronometr.c_tarif as odT, bchrono.c_tarif as doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo,
(spojeni.distance)
from spoje

inner join spojeni ON (spojeni.idlocation = location and spojeni.packet = packet and spojeni.idlocation = /*spojeni.idlocation*/location and spojeni.packet = /*spojeni.packet*/packet and spoje.c_linky = spojeni.doLinka and spoje.smer = (not spojeni.smer))
inner JOIN chronometr ON (
                    chronometr.idlocation = spojeni.idlocation AND
                    chronometr.packet = spojeni.packet AND
                    chronometr.c_linky = spojeni.doLinka
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_zastavky = spojeni.doZ
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif)))) <> -1
                    )        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = spojeni.idlocation AND
                    bchrono.packet = spojeni.packet AND
                    bchrono.c_linky = spojeni.doLinka
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_zastavky = spojeni.odZ)                    
WHERE (kodpozn & den) = kodpozn and
/*(
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )) and*/ spoje.idlocation = location and spoje.packet = packet
                  and spojeni.odZ = cil
                  and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
                  and ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.odT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.odT))
                  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo)
order by spojeni.doZ, /*spojeni.distance,/*spoje.smer desc, ABS(spojeni.odT - spojeni.doT) desc,*/ (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) desc/*, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) desc*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyCil1`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000))
BEGIN
select 
spoje.c_linky, spojeni.doZ as odZ, spojeni.odZ as doZ, chronometr.c_tarif as odT, bchrono.c_tarif as doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo
from spoje

inner join spojeni ON (spojeni.idlocation = location and spojeni.packet = packet and spojeni.idlocation = /*spojeni.idlocation*/location and spojeni.packet = /*spojeni.packet*/packet and spoje.c_linky = spojeni.doLinka and spoje.smer = (not spojeni.smer))
inner JOIN chronometr ON (
                    chronometr.idlocation = spojeni.idlocation AND
                    chronometr.packet = spojeni.packet AND
                    chronometr.c_linky = spojeni.doLinka
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_zastavky = spojeni.doZ
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif)))) <> -1
                    )        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = spojeni.idlocation AND
                    bchrono.packet = spojeni.packet AND
                    bchrono.c_linky = spojeni.doLinka
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_zastavky = spojeni.odZ)                    
WHERE (kodpozn & den) = kodpozn and
/*(
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )) and*/ spoje.idlocation = location and spoje.packet = packet
                  and spojeni.odZ = cil
/*                  and not FIND_IN_SET(spojeni.doZ, doZ COLLATE utf8_general_ci) > 0 */
/*                  and not FIND_IN_SET(spojeni.doZ, odZ COLLATE utf8_general_ci) > 0*/
                  and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
                  and ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.odT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.odT))
                  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo)
order by spojeni.doZ, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) desc/*, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) desc*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyPrestupy`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000), in outdoZ varchar(5000), in linky varchar(255))
BEGIN
/*explain extended*/ select /*STRAIGHT_JOIN*/SQL_CALC_FOUND_ROWS SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct /*spoje.kodpozn, (kodpozn & den) as er,*/
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo,
(spojeni.distance)
/*spoje.HH, spoje.MM, chronometr.doba_pocatek, bchrono.doba_pocatek*/
from spojeni
inner join spoje ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
inner JOIN chronometr ON (
                    chronometr.idlocation = location/*spojeni.idlocation*/ AND
                    chronometr.packet = packet/*spojeni.packet*/ AND
                    chronometr.c_linky = spoje.c_linky/*spojeni.doLinka/*c_linky*/
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT)        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location/*spojeni.idlocation*/ AND
                    bchrono.packet = packet/*spojeni.packet*/ AND
                    bchrono.c_linky = spoje.c_linky/*spojeni.doLinka/*c_linky*/
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = spojeni.doT)                    
WHERE (kodpozn & den) = kodpozn and
 spojeni.idlocation = location and spojeni.packet = packet and 
                  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
                  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo)
order by (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyPrima`(in location int, in packet int, in casod int, in casdo int, in pocatek int, in cil int, in den int)
BEGIN
select 
spoje.c_linky, a.c_zastavky as odZ, b.c_zastavky as doZ, a.c_tarif as odT, b.c_tarif as doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo

from zaslinky a 
inner join zaslinky b ON (a.c_linky = b.c_linky and a.idlocation = location and b.idlocation = location and a.packet = packet and b.packet = packet)
inner join spoje ON (spoje.c_linky = a.c_linky and ((spoje.smer = 0 and a.c_tarif < b.c_tarif) or (spoje.smer = 1 and a.c_tarif > b.c_tarif)))


inner JOIN chronometr ON (
                    chronometr.idlocation = a.idlocation AND
                    chronometr.packet = a.packet AND
                    chronometr.c_linky = a.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_zastavky = a.c_zastavky
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif)))) <> -1
                    )        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = b.idlocation AND
                    bchrono.packet = b.packet AND
                    bchrono.c_linky = b.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_zastavky = b.c_zastavky)                    
WHERE (kodpozn & den) = kodpozn and
                    spoje.idlocation = location and spoje.packet = packet
                    and a.c_zastavky = pocatek
                    and b.c_zastavky = cil
                    and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
/*                    and ((chronometr.smer = 0 and chronometr.c_tarif < a.c_tarif) or (chronometr.smer = 1 and chronometr.c_tarif > a.c_tarif))*/
                    and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo)
order by b.c_zastavky, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) asc/*, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) desc*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavka`(in location int, in packet int, in casod int, in casdo int, in cil int, in den varchar(255), in odZ varchar(5000), in outdoZ varchar(5000), in linky varchar(255))
BEGIN
/*select 
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
(zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) as CASod,
(zasspoje.HH * 60 + zasspoje.MM + bchrono.doba_pocatek) as CASdo 
from spoje 
left outer join spojeni ON (spoje.idlocation = spojeni.idlocation and spoje.packet = spojeni.packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
LEFT OUTER JOIN zasspoje ON ( zasspoje.c_linky = spoje.c_linky
                  AND zasspoje.c_spoje = spoje.c_spoje AND zasspoje.idlocation = spoje.idlocation 
                  AND zasspoje.packet = spoje.packet)
LEFT OUTER JOIN chronometr ON (
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT 
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1)        
LEFT OUTER JOIN chronometr bchrono ON ( bchrono.c_linky = spoje.c_linky
                  AND bchrono.smer = spoje.smer
                  AND bchrono.chrono = spoje.chrono
                  AND bchrono.c_tarif = spojeni.doT
                  AND bchrono.idlocation = spoje.idlocation AND bchrono.packet = spoje.packet)                    
WHERE (
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )) and spoje.idlocation = location and spoje.packet = packet and 
                  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.doT))
                  and (zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) <= casdo) and
                  FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 
                  and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0
order by doZ, (zasspoje.HH * 60 + zasspoje.MM + bchrono.doba_pocatek) desc, (zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) desc;*/
select * from (
(SELECT 
                  spoje.c_linky, spojeni.odZ, spojenido.doZ, spojeni.odT, spojenido.doT, spoje.smer,
                  (zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) as CASod,
                  (zasspoje.HH * 60 + zasspoje.MM + bchrono.doba_pocatek) as CASdo
                  FROM (
                  SELECT spoje.c_linky, spoje.c_spoje, spoje.smer, spoje.chrono, spoje.idlocation, spoje.packet from 
                (select distinct spojeni.doLinka, spojeni.smer, spojeni.idlocation, spojeni.packet from spojeni where spojeni.idlocation = location and spojeni.packet = packet and FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0) spojeni
                LEFT OUTER JOIN spoje ON (spoje.idlocation = spojeni.idlocation and spoje.packet = spojeni.packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
                WHERE (
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation =location and packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )
                  )
                  ) AS spoje
                  LEFT OUTER JOIN zasspoje ON ( zasspoje.c_linky = spoje.c_linky
                  AND zasspoje.c_spoje = spoje.c_spoje AND zasspoje.idlocation = spoje.idlocation 
                  AND zasspoje.packet = spoje.packet)
                  LEFT OUTER JOIN 
                  (select distinct spojeni.odZ, spojeni.odT, spojeni.doLinka, spojeni.smer from spojeni WHERE spojeni.idlocation = location and spojeni.packet = packet and FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0) spojeni ON (spojeni.doLinka = spoje.c_linky and spojeni.smer = spoje.smer)
                  LEFT OUTER JOIN chronometr ON (
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT 
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = location AND odch.packet = packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1)
                  LEFT OUTER JOIN spojeni spojenido ON ( spojenido.odZ = spojeni.odZ and spojenido.odT = spojeni.odT and spojenido.doZ <> spojeni.odZ and spojenido.doLinka = spoje.c_linky and spojenido.idlocation = location and spojenido.packet = packet and not FIND_IN_SET(spojenido.doZ, outdoZ COLLATE utf8_general_ci) > 0)

                  LEFT OUTER JOIN chronometr bchrono ON ( bchrono.c_linky = spoje.c_linky
                  AND bchrono.smer = spoje.smer
                  AND bchrono.chrono = spoje.chrono
                  AND bchrono.c_tarif = spojenido.doT
                  AND bchrono.idlocation = location AND bchrono.packet = packet)

                  WHERE NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojenido.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojenido.doT))
                  and zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek >= casod and (casdo = -1 or zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek <= casdo)
                  
)
                  
                  union
                  
(SELECT 
                  spoje.c_linky, spojeni.odZ, spojenido.doZ, spojeni.odT, spojenido.doT, spoje.smer,
                  (zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek) as CASod,
                  (zasspoje.HH * 60 + zasspoje.MM + bchrono.doba_pocatek) as CASdo
                  FROM (
                  SELECT spoje.c_linky, spoje.c_spoje, spoje.smer, spoje.chrono, spoje.idlocation, spoje.packet from 
(select distinct b.c_linky as doLinka,
case when a.c_tarif < b.c_tarif then 0 else 1 end as smer,
location as idlocation, packet as packet
from zaslinky a left outer join zaslinky b
on (a.idlocation = location and b.idlocation = location and a.packet = packet and b.packet = packet and a.c_linky = b.c_linky and a.c_zastavky <> b.c_zastavky)
where b.c_zastavky = cil and FIND_IN_SET(a.c_zastavky, odZ COLLATE utf8_general_ci) > 0) spojeni
                LEFT OUTER JOIN spoje ON (spoje.idlocation = spojeni.idlocation and spoje.packet = spojeni.packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
                WHERE (
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation =location and packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and idlocation = location and packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )
                  )
                  ) AS spoje
                  LEFT OUTER JOIN zasspoje ON ( zasspoje.c_linky = spoje.c_linky
                  AND zasspoje.c_spoje = spoje.c_spoje AND zasspoje.idlocation = spoje.idlocation 
                  AND zasspoje.packet = spoje.packet)
                  LEFT OUTER JOIN 
(select distinct a.c_zastavky as odZ, a.c_tarif as odT, b.c_linky as doLinka,
case when a.c_tarif < b.c_tarif then 0 else 1 end as smer,
b.c_zastavky as doZ, b.c_tarif as doT, location as idlocation, packet as packet
from zaslinky a left outer join zaslinky b
on (a.idlocation = location and b.idlocation = location and a.packet = packet and b.packet = packet and a.c_linky = b.c_linky and a.c_zastavky <> b.c_zastavky)
where b.c_zastavky = cil and FIND_IN_SET(a.c_zastavky, odZ COLLATE utf8_general_ci) > 0) spojeni ON (spojeni.doLinka = spoje.c_linky and spojeni.smer = spoje.smer)
                    LEFT OUTER JOIN chronometr ON (
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT 
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = location AND odch.packet = packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1)
                  LEFT OUTER JOIN 
(select distinct a.c_zastavky as odZ, a.c_tarif as odT, b.c_linky as doLinka,
case when a.c_tarif < b.c_tarif then 0 else 1 end as smer,
b.c_zastavky as doZ, b.c_tarif as doT, location as idlocation, packet as packet
from zaslinky a left outer join zaslinky b
on (a.idlocation = location and b.idlocation = location and a.packet = packet and b.packet = packet and a.c_linky = b.c_linky and a.c_zastavky <> b.c_zastavky)
where b.c_zastavky = cil and FIND_IN_SET(a.c_zastavky, odZ COLLATE utf8_general_ci) > 0) spojenido                  
ON ( spojenido.odZ = spojeni.odZ and spojenido.odT = spojeni.odT and spojenido.doZ <> spojeni.odZ and spojenido.doLinka = spoje.c_linky)

                  LEFT OUTER JOIN chronometr bchrono ON ( bchrono.c_linky = spoje.c_linky
                  AND bchrono.smer = spoje.smer
                  AND bchrono.chrono = spoje.chrono
                  AND bchrono.c_tarif = spojenido.doT
                  AND bchrono.idlocation = location AND bchrono.packet = packet)

                  WHERE NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojenido.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojenido.doT))
                  and zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek >= casod and (casdo = -1 or zasspoje.HH * 60 + zasspoje.MM + chronometr.doba_pocatek <= casdo))) q
                  
                  ORDER BY q.doZ, q.CASdo desc;                  


END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavka1`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000), in outdoZ varchar(5000), in linky varchar(255))
BEGIN
/*explain extended*/ select /*STRAIGHT_JOIN*/SQL_CALC_FOUND_ROWS SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct /*spoje.kodpozn, (kodpozn & den) as er,*/
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo,
(spojeni.distance)
/*spoje.HH, spoje.MM, chronometr.doba_pocatek, bchrono.doba_pocatek*/
from spojeni
inner join spoje ON (/*spojeni.idlocation = location and spojeni.packet = packet and */spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
/*LEFT OUTER JOIN zasspoje ON ( zasspoje.c_linky = spoje.c_linky
                  AND zasspoje.c_spoje = spoje.c_spoje AND zasspoje.idlocation = spoje.idlocation 
                  AND zasspoje.packet = spoje.packet)*/
inner JOIN chronometr ON (
                    chronometr.idlocation = location/*spojeni.idlocation*/ AND
                    chronometr.packet = packet/*spojeni.packet*/ AND
                    chronometr.c_linky = spoje.c_linky/*spojeni.doLinka/*c_linky*/
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT
/*                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1*/)        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location/*spojeni.idlocation*/ AND
                    bchrono.packet = packet/*spojeni.packet*/ AND
                    bchrono.c_linky = spoje.c_linky/*spojeni.doLinka/*c_linky*/
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = spojeni.doT
/*                    AND bchrono.idlocation = spoje.idlocation AND bchrono.packet = spoje.packet*/)                    
WHERE ((kodpozn & den) = kodpozn or kodpozn = 0) and
/*(
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )) and*/ spojeni.idlocation = location and spojeni.packet = packet and 
                  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 /*and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.doT))*/
                  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo) and
/*                  and spoje.HH >= (casod div 60) and spoje.MM >= (casod mod 60) and (casdo = -1 or (spoje.HH <= (casdo div 60) + 1)) and*/
                  FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 
/*                  spojeni.odZ = odZ*/
                  and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0
order by /*spojeni.doZ, spojeni.distance/*ABS(spojeni.odT - spojeni.doT)*/ (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) desc/*, ABS(spojeni.odT - spojeni.doT) desc*/;
/*order by doZ, spoje.HH, spoje.MM, bchrono.doba_pocatek desc;*/

/*union

(select 
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo 
from spoje
inner join (select distinct a.c_zastavky as odZ, a.c_zastavky as odT, b.c_linky as doLinka,
case when a.c_tarif < b.c_tarif then 0 else 1 end as smer, b.c_zastavky as doZ, b.c_tarif as doT,
location as idlocation, packet as packet
from zaslinky a left outer join zaslinky b
on (a.idlocation = location and b.idlocation = location and a.packet = packet and b.packet = packet and a.c_linky = b.c_linky and a.c_zastavky <> b.c_zastavky)
where b.c_zastavky = cil) spojeni
inner JOIN chronometr ON (
                    chronometr.idlocation = spojeni.idlocation AND
                    chronometr.packet = spojeni.packet AND
                    chronometr.c_linky = spojeni.doLinka
                    AND chronometr.smer = spojeni.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1)        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = spoje.idlocation AND
                    bchrono.packet = spoje.packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = spojeni.doT)                    
WHERE (
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )) and spoje.idlocation = location and spoje.packet = packet and 
                  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.doT))
                  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo) and
                  FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 
order by doZ, HH DESC, MM DESC)) q

order by q.doZ, q.CASdo desc, q.CASod desc;*/
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavka2`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000), in doZ varchar(5000), in linky varchar(255))
BEGIN
select HIGH_PRIORITY/*spoje.kodpozn, (kodpozn & den) as er,*/
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo 
/*spoje.HH, spoje.MM, chronometr.doba_pocatek, bchrono.doba_pocatek*/
from spoje
inner join spojeni ON (spojeni.idlocation = location and spojeni.packet = packet and spojeni.idlocation = /*spojeni.idlocation*/location and spojeni.packet = /*spojeni.packet*/packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
/*LEFT OUTER JOIN zasspoje ON ( zasspoje.c_linky = spoje.c_linky
                  AND zasspoje.c_spoje = spoje.c_spoje AND zasspoje.idlocation = spoje.idlocation 
                  AND zasspoje.packet = spoje.packet)*/
inner JOIN chronometr ON (
                    chronometr.idlocation = spojeni.idlocation AND
                    chronometr.packet = spojeni.packet AND
                    chronometr.c_linky = spojeni.doLinka/*c_linky*/
                    AND chronometr.smer = spojeni.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT
                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1)        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = spojeni.idlocation AND
                    bchrono.packet = spojeni.packet AND
                    bchrono.c_linky = spojeni.doLinka/*c_linky*/
                    AND bchrono.smer = spojeni.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = spojeni.doT
/*                    AND bchrono.idlocation = spoje.idlocation AND bchrono.packet = spoje.packet*/)                    
WHERE (kodpozn & den) = kodpozn and
/*(
                  (
                  FIND_IN_SET(spoje.pk1, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk2, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk3, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk4, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk5, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk6, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk7, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk8, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk9, den COLLATE utf8_general_ci) > 0
                  OR FIND_IN_SET(spoje.pk10, den COLLATE utf8_general_ci) > 0
                  )
                  OR (
                  NOT spoje.pk1
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk2
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk3
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk4
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk5
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk6
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet                  
                  )
                  AND NOT spoje.pk7
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk8
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk9
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  AND NOT spoje.pk10
                  IN (
                  SELECT pevnykod.c_kodu
                  FROM pevnykod
                  WHERE pevnykod.caspozn = 1 and pevnykod.idlocation = location and pevnykod.packet = packet
                  )
                  )
                  OR (
                  spoje.pk1 = 0
                  AND spoje.pk2 = 0
                  AND spoje.pk3 = 0
                  AND spoje.pk4 = 0
                  AND spoje.pk5 = 0
                  AND spoje.pk6 = 0
                  AND spoje.pk7 = 0
                  AND spoje.pk8 = 0
                  AND spoje.pk9 = 0
                  AND spoje.pk10 = 0
                  )) and*/ spoje.idlocation = location and spoje.packet = packet and 
                  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.doT))
                  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo) and
                  FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 
                  and FIND_IN_SET(spojeni.doZ, doZ COLLATE utf8_general_ci) > 0;
/*order by /*spojeni.doZ,*/ /*(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) desc*//*, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) desc*//*;*/

END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavka3`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000), in outdoZ varchar(5000), in linky varchar(255))
BEGIN
/*explain extended*/ select HIGH_PRIORITY distinct /*spoje.kodpozn, (kodpozn & den) as er,*/
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo
/*spoje.HH, spoje.MM, chronometr.doba_pocatek, bchrono.doba_pocatek*/
from spojeni
inner join spoje ON (spojeni.idlocation = location and spojeni.packet = packet and spojeni.idlocation = /*spojeni.idlocation*/location and spojeni.packet = /*spojeni.packet*/packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer)
/*LEFT OUTER JOIN zasspoje ON ( zasspoje.c_linky = spoje.c_linky
                  AND zasspoje.c_spoje = spoje.c_spoje AND zasspoje.idlocation = spoje.idlocation 
                  AND zasspoje.packet = spoje.packet)*/        
inner JOIN chronometr ON(
                    chronometr.idlocation = spojeni.idlocation AND
                    chronometr.packet = spojeni.packet AND
                    chronometr.c_linky = spojeni.doLinka/*c_linky*/
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT
                    /*AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif))
                    )) <> -1*/)                  
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = spojeni.idlocation AND
                    bchrono.packet = spojeni.packet AND
                    bchrono.c_linky = spojeni.doLinka/*c_linky*/
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = spojeni.doT
/*                    AND bchrono.idlocation = spoje.idlocation AND bchrono.packet = spoje.packet*/)                    
WHERE (kodpozn & den) = kodpozn and spoje.idlocation = location and spoje.packet = packet and 
                  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1 /*and 
                  ((chronometr.smer = 0 and chronometr.c_tarif < spojeni.doT) or (chronometr.smer = 1 and chronometr.c_tarif > spojeni.doT))*/
                  and /*(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo) and*/
                  FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 
                  and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0
having (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek)                
order by (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek), (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) ;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavka4`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000), in outdoZ varchar(5000), in linky varchar(255))
BEGIN
select HIGH_PRIORITY distinct
spoje.c_linky, a.c_zastavky as odZ, b.c_zastavky as doZ, a.c_tarif as odT, b.c_tarif as doT, spoje.smer,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo

from zaslinky a 
inner join zaslinky b ON (a.idlocation = location and b.idlocation = location and a.packet = packet and b.packet = packet and a.c_linky = b.c_linky/* and b.prestup = 1*/)
inner join spoje ON (spoje.c_linky = a.c_linky and ((spoje.smer = 0 and a.c_tarif < b.c_tarif) or (spoje.smer = 1 and a.c_tarif > b.c_tarif)))


inner JOIN chronometr ON (
                    chronometr.idlocation = a.idlocation AND
                    chronometr.packet = a.packet AND
                    chronometr.c_linky = a.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_zastavky = a.c_zastavky
/*                    AND 
                    (
                    select (sum(odch.doba_jizdy)/count(odch.doba_jizdy)) from chronometr odch
                    where ( odch.c_linky = spoje.c_linky
                    AND odch.smer = spoje.smer
                    AND odch.chrono = spoje.chrono
                    AND odch.idlocation = spoje.idlocation AND odch.packet = spoje.packet
                    AND ((odch.smer = 0 and odch.c_tarif > chronometr.c_tarif) or (odch.smer = 1 and odch.c_tarif < chronometr.c_tarif)))) <> -1*/
                    )        
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = b.idlocation AND
                    bchrono.packet = b.packet AND
                    bchrono.c_linky = b.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_zastavky = b.c_zastavky)                    
WHERE (kodpozn & den) = kodpozn and
                    spoje.idlocation = location and spoje.packet = packet
                    and b.prestup = 1
/*                    and a.c_zastavky = pocatek*/
/*                    and b.c_zastavky = cil*/
                    and FIND_IN_SET(a.c_zastavky, odZ COLLATE utf8_general_ci) > 0
                    and not FIND_IN_SET(b.c_zastavky, outdoZ COLLATE utf8_general_ci) > 0
                    and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
/*                    and ((chronometr.smer = 0 and chronometr.c_tarif < a.c_tarif) or (chronometr.smer = 1 and chronometr.c_tarif > a.c_tarif))*/
                    and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo)
order by b.c_zastavky, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) desc/*, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) desc*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavka5`(in location int, in packet int, in casod int, in casdo int, in cil int, in den int, in odZ varchar(5000), in outdoZ varchar(5000), in linky varchar(255), in odtarif int)
BEGIN
/*explain extended*/ select SQL_CALC_FOUND_ROWS SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer,
min((spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek)*100+(bchrono.doba_pocatek - chronometr.doba_pocatek)) as CASod
/*(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo,*/
/*(bchrono.doba_pocatek - chronometr.doba_pocatek) as dobajizdy,
(spojeni.distance)*/
/*spoje.HH, spoje.MM, chronometr.doba_pocatek, bchrono.doba_pocatek*/
from spojeni
inner join spoje ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = spojeni.doLinka and spoje.smer = spojeni.smer) 
inner JOIN chronometr ON (
                    chronometr.idlocation = spoje.idlocation AND
                    chronometr.packet = spoje.packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = spojeni.odT)
inner JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location AND
                    bchrono.packet = packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = spojeni.doT)                    
WHERE 
  (kodpozn & den) = kodpozn and
  spojeni.idlocation = location and spojeni.packet = packet and 
  NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
  and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= casod and (casdo = -1 or (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= casdo) and
  FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0
  and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0 
  and (spojeni.odT = odtarif or odtarif = -1)
group by spoje.c_linky, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spoje.smer
order by (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaCasy`(in location int, in packet int, in odCAS int, in doCAS int, in odZ int, in den int, in datum varchar(10))
BEGIN
select SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod

from

(select distinct linky.nazev_linky, linky.c_linkysort, linky.c_linky, zaslinky.c_zastavky,  zaslinky.c_tarif, doprava 
from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and 
zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet and jr_od <= datum and jr_do >= datum)
where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_zastavky = odZ and zaslinky.voz = 1) linky

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = linky.c_linky and spoje.voz = 1 AND (spoje.vlastnosti & 2048) <> 2048)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = linky.c_tarif)           
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = linky.c_zastavky)

WHERE /*((kodpozn & den) = case when kodpozn > den then den else kodpozn end or kodpozn = 0)*/((kodpozn & den) > 0 or kodpozn = 0)
and NOT chronometr.doba_jizdy = -1 and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= odCAS and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= doCAS
order by (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaCasytest`(in location int, in packet int, in odCAS int, in doCAS int, in odZ int, in den int, in datum varchar(10))
BEGIN
select SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod, spoje.kodpozn, spoje.c_linky, spoje.c_spoje

from

(select distinct linky.nazev_linky, linky.c_linkysort, linky.c_linky, zaslinky.c_zastavky,  zaslinky.c_tarif, doprava 
from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and 
zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet and jr_od <= datum and jr_do >= datum)
where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_zastavky = odZ and zaslinky.voz = 1) linky

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = linky.c_linky and spoje.voz = 1 AND (spoje.vlastnosti & 2048) <> 2048)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = linky.c_tarif)           
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = linky.c_zastavky)

WHERE /*((kodpozn & den) = case when kodpozn > den then den else kodpozn end or kodpozn = 0)*/((kodpozn & den) > 0 or kodpozn = 0)
and NOT chronometr.doba_jizdy = -1 and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= odCAS and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) <= doCAS
order by (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaSpoje`(in location int, in packet int, in odCAS int, in odZ int, in den int, in datum varchar(10))
BEGIN
/*explain extended*/ select SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
linky.c_linky, linky.nazev_linky, linky.doprava, linky.c_tarif, spoje.smer, 
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
spoje.c_spoje, spoje.chrono

from

/*(select distinct linky.nazev_linky, linky.c_linkysort, linky.c_linky, zaslinky.c_zastavky,  
(select azaslinky.c_tarif from zaslinky azaslinky where azaslinky.c_linky = linky.c_linky and azaslinky.idlocation = location
and azaslinky.packet = packet and azaslinky.c_zastavky = zaslinky.c_zastavky order by c_tarif LIMIT 1) as c_tarif, doprava 
from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and 
zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet)
where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_zastavky = odZ and zaslinky.voz = 1) linky*/

(select distinct linky.nazev_linky, linky.c_linkysort, linky.c_linky, zaslinky.c_zastavky,  zaslinky.c_tarif, doprava 
from zaslinky left outer join linky on (zaslinky.c_linky = linky.c_linky and 
zaslinky.idlocation = linky.idlocation and zaslinky.packet = linky.packet and jr_od <= datum and jr_do >= datum)
where zaslinky.idlocation = location and zaslinky.packet = packet and zaslinky.c_zastavky = odZ and zaslinky.voz = 1) linky

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = linky.c_linky and spoje.voz = 1 AND (spoje.vlastnosti & 2048) <> 2048)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = linky.c_tarif)           
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = linky.c_zastavky)

WHERE /*((kodpozn & den) = case when kodpozn > den then den else kodpozn end or kodpozn = 0)*/((kodpozn & den) > 0 or kodpozn = 0)
and NOT chronometr.doba_jizdy = -1 and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= odCas
/*group by linky.c_linky, spoje.smer*/
order by (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek), linky.c_linky, smer;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaTEST`(in location int, in packet int, in Cod int, in odZ int, in cil int, in outZ int, in den int)
BEGIN
/*explain extended*/ select SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
zl1.c_linky, zl1.od_zastavky as od_zastavky, zl1.do_zastavky as do_zastavky, 
zl1.od_tarif, zl1.do_tarif as do_tarif, spoje.smer, /*part1.idlocation, part1.packet,*/
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
/*(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo*/(bchrono.doba_pocatek - chronometr.doba_pocatek) as doba_jizdy/*bchrono.doba_jizdy*/,
spoje.c_spoje, spoje.chrono, zastavky.nazev, zastavky1.nazev
from

(select zl.c_linky, zl.od_zastavky as od_zastavky, zl.od_tarif, zl.do_zastavky as do_zastavky, zl.do_tarif do_tarif, zl.smer,
zl.dist, zl.idlocation, zl.packet from
(
select part1.c_linky, part1.od_zastavky as od_zastavky, od_tarif, part2.c_zastavky as do_zastavky, part2.c_tarif do_tarif, smer,
dist, part1.idlocation, part1.packet from 

(select a.c_linky as c_linky , a.c_zastavky as od_zastavky, a.c_tarif as od_tarif, case when a.c_tarif > b.c_tarif then 1 else 0 end as smer,
min(abs(CAST(a.c_tarif as SIGNED) - CAST(b.c_tarif as SIGNED))) as dist, a.idlocation as idlocation, a.packet as packet
from zaslinky a 
left outer join zaslinky b
on (a.c_linky = b.c_linky and not a.c_tarif = b.c_tarif and case when a.c_tarif > b.c_tarif then b.zast_b = 1 else b.zast_a = 1 end and a.idlocation = b.idlocation and a.packet = b.packet)
where a.c_zastavky = odZ and a.idlocation = location and a.packet = packet and b.idlocation = location and b.packet = packet and (b.prestup = 1 or b.c_zastavky = cil)
group by a.c_linky, a.c_tarif, smer) part1

left outer join zaslinky part2
on (part2.c_linky = part1.c_linky and not part2.c_zastavky = outZ and part2.c_tarif = case when part1.smer = 0 then part1.od_tarif + part1.dist else part1.od_tarif - part1.dist end 
and part2.idlocation = location and part2.packet = packet)

union

select * from 
(select a1.c_linky, a1.c_zastavky, a1.c_tarif as od_tarif, b1.c_zastavky as do_zastavky, b1.c_tarif, case when a1.c_tarif > b1.c_tarif then 1 else 0 end as smer,
(abs(CAST(a1.c_tarif as SIGNED) - CAST(b1.c_tarif as SIGNED))) as dist, a1.idlocation as idlocation, a1.packet as packet
from zaslinky a1 
left outer join zaslinky b1
on (a1.c_linky = b1.c_linky and a1.idlocation = b1.idlocation and a1.packet = b1.packet)
where a1.c_zastavky = odZ and b1.c_zastavky = cil and a1.idlocation = location and a1.packet = packet and b1.idlocation = location and b1.packet = packet) p2

) zl) zl1

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = zl1.c_linky and spoje.smer = zl1.smer)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = zl1.od_tarif)        
left outer JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location AND
                    bchrono.packet = packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = zl1.do_tarif)   
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = zl1.od_zastavky)
left outer JOIN zastavky zastavky1 ON (
                    zastavky1.idlocation = location AND
                    zastavky1.packet = packet AND
                    zastavky1.c_zastavky = zl1.do_zastavky)
WHERE (kodpozn & den) = kodpozn and
NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= Cod and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek <= Cod+120)
/*order by part1.c_linky, part1.od_tarif, part1.smer, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek)*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaTEST1`(in location int, in packet int, in Cod int, in odZ int, in den int)
BEGIN
/*explain extended*/ select SQL_CALC_FOUND_ROWS SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
part1.c_linky, part1.c_zastavky as od_zastavky, part2.c_zastavky as do_zastavky, 
part1.c_tarif as od_tarif, part2.c_tarif as do_tarif, case when part1.c_tarif > part2.c_tarif then 1 else 0 end as smer, part1.idlocation, part1.packet,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo,
spoje.c_spoje, spoje.chrono
from
zaslinky/*(select * from zaslinky a where a.idlocation = location and a.packet = packet and a.c_zastavky = odZ)*/ part1
left outer join 
zaslinky part2
on (part2.c_linky = part1.c_linky and not part2.c_tarif = part1.c_tarif and part2.idlocation = location and part1.idlocation = location 
and part2.packet = packet and part1.packet = packet and part1.c_zastavky = odZ)
/*(select a.c_linky, a.c_zastavky, a.c_tarif as od_tarif, case when a.c_tarif > b.c_tarif then 1 else 0 end as smer,
/*min(abs(CAST(a.c_tarif as SIGNED) - CAST(b.c_tarif as SIGNED))) as dist,*/ /*a.idlocation as idlocation, a.packet as packet
from zaslinky a 
left outer join zaslinky b
on (a.c_linky = b.c_linky and not a.c_tarif = b.c_tarif and case when a.c_tarif > b.c_tarif then b.zast_b = 1 else b.zast_a = 1 end and a.idlocation = b.idlocation and a.packet = b.packet)
where a.c_zastavky = odZ and a.idlocation = location and a.packet = packet
group by a.c_linky, a.c_tarif, smer) part1
left outer join zaslinky part2
on (part2.c_linky = part1.c_linky and part2.c_tarif = case when part1.smer = 0 then part1.od_tarif + part1.dist else part1.od_tarif - part1.dist end 
and part2.idlocation = part1.idlocation and part2.packet = part1.packet)*/
left outer join spoje ON (spoje.idlocation = part1.idlocation and spoje.packet = part1.packet and spoje.c_linky = part1.c_linky and spoje.smer = case when part1.c_tarif > part2.c_tarif then 1 else 0 end)
left outer JOIN chronometr ON (
                    chronometr.idlocation = part1.idlocation AND
                    chronometr.packet = part1.packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = part1.c_tarif)        
left outer JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = part1.idlocation AND
                    bchrono.packet = part1.packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = part2.c_tarif)                    
WHERE (kodpozn & den) = kodpozn and
NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= Cod and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek <= Cod+120)
order by part1.c_linky, part1.c_tarif, smer, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaTEST2`(in location int, in packet int, in Cod int, in odZ int, in pocatek int, in cil int, in outZ int, in den int)
BEGIN
/*explain extended*/ select SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
zl1.c_linky, zl1.od_zastavky as od_zastavky, zl1.do_zastavky as do_zastavky, 
zl1.od_tarif, zl1.do_tarif as do_tarif, spoje.smer, /*part1.idlocation, part1.packet,*/
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
/*(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo*/(bchrono.doba_pocatek - chronometr.doba_pocatek) as doba_jizdy/*bchrono.doba_jizdy*/,
spoje.c_spoje, spoje.chrono, zastavky.nazev, zastavky1.nazev
from

(select zl.c_linky, zl.od_zastavky as od_zastavky, zl.od_tarif, zl.do_zastavky as do_zastavky, zl.do_tarif do_tarif, zl.smer,
zl.dist, zl.idlocation, zl.packet from
(
select part1.c_linky, part1.od_zastavky as od_zastavky, od_tarif, part2.c_zastavky as do_zastavky, part2.c_tarif do_tarif, smer,
dist, part1.idlocation, part1.packet from 

(select a.c_linky as c_linky , a.c_zastavky as od_zastavky, a.c_tarif as od_tarif, case when a.c_tarif > b.c_tarif then 1 else 0 end as smer,
(abs(CAST(a.c_tarif as SIGNED) - CAST(b.c_tarif as SIGNED))) as dist, a.idlocation as idlocation, a.packet as packet
from zaslinky a 
left outer join zaslinky b
on (a.c_linky = b.c_linky and not a.c_tarif = b.c_tarif and case when a.c_tarif > b.c_tarif then b.zast_b = 1 else b.zast_a = 1 end and a.idlocation = b.idlocation and a.packet = b.packet)
where a.c_zastavky = odZ and a.idlocation = location and a.packet = packet and b.idlocation = location and b.packet = packet and (b.prestup = 1 or b.c_zastavky = cil)
/*group by a.c_linky, a.c_tarif, smer*/) part1

left outer join zaslinky part2
on (part2.c_linky = part1.c_linky and not part2.c_zastavky = outZ and not part2.c_zastavky = pocatek and part2.c_tarif = case when part1.smer = 0 then part1.od_tarif + part1.dist else part1.od_tarif - part1.dist end 
and part2.idlocation = location and part2.packet = packet)

union

select * from 
(select a1.c_linky, a1.c_zastavky, a1.c_tarif as od_tarif, b1.c_zastavky as do_zastavky, b1.c_tarif, case when a1.c_tarif > b1.c_tarif then 1 else 0 end as smer,
(abs(CAST(a1.c_tarif as SIGNED) - CAST(b1.c_tarif as SIGNED))) as dist, a1.idlocation as idlocation, a1.packet as packet
from zaslinky a1 
left outer join zaslinky b1
on (a1.c_linky = b1.c_linky and a1.idlocation = b1.idlocation and a1.packet = b1.packet)
where a1.c_zastavky = odZ and b1.c_zastavky = cil and a1.idlocation = location and a1.packet = packet and b1.idlocation = location and b1.packet = packet) p2

) zl) zl1

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = zl1.c_linky and spoje.smer = zl1.smer)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = zl1.od_tarif)        
left outer JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location AND
                    bchrono.packet = packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = zl1.do_tarif)   
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = zl1.od_zastavky)
left outer JOIN zastavky zastavky1 ON (
                    zastavky1.idlocation = location AND
                    zastavky1.packet = packet AND
                    zastavky1.c_zastavky = zl1.do_zastavky)
WHERE (kodpozn & den) = kodpozn and
NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= Cod and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek <= Cod+180)
/*order by part1.c_linky, part1.od_tarif, part1.smer, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek)*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaTEST3`(in location int, in packet int, in Cod int, in Cdo int, in odZ int, in doZ int, in pocatek int, in cil int, in outZ int, in den int)
BEGIN
/*explain extended*/ select /*SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT*/ distinct
zl1.c_linky, zl1.od_zastavky as od_zastavky, zl1.do_zastavky as do_zastavky, 
zl1.od_tarif, zl1.do_tarif as do_tarif, spoje.smer, /*part1.idlocation, part1.packet,*/
(spoje.HH * 60 + spoje.MM + CAST(chronometr.doba_pocatek as SIGNED)) as CASod,
/*(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo*/(CAST(bchrono.doba_pocatek as SIGNED) - CAST(chronometr.doba_pocatek as SIGNED)) as doba_jizdy/*bchrono.doba_jizdy*/,
spoje.c_spoje, spoje.chrono, zastavky.nazev, zastavky1.nazev, linky.nazev_linky, linky.doprava
from

(select zl.c_linky, zl.od_zastavky as od_zastavky, zl.od_tarif, zl.do_zastavky as do_zastavky, zl.do_tarif do_tarif, zl.smer,
zl.dist, zl.idlocation, zl.packet from
(
select part1.c_linky, part1.od_zastavky as od_zastavky, od_tarif, part2.c_zastavky as do_zastavky, part2.c_tarif do_tarif, smer,
dist, part1.idlocation, part1.packet from 

(select a.c_linky as c_linky , a.c_zastavky as od_zastavky, a.c_tarif as od_tarif, case when a.c_tarif > b.c_tarif then 1 else 0 end as smer,
(abs(CAST(a.c_tarif as SIGNED) - CAST(b.c_tarif as SIGNED))) as dist, a.idlocation as idlocation, a.packet as packet
from zaslinky a 
left outer join zaslinky b
on (a.c_linky = b.c_linky and not a.c_tarif = b.c_tarif and case when a.c_tarif > b.c_tarif then b.zast_b = 1 else b.zast_a = 1 end and a.idlocation = b.idlocation and a.packet = b.packet)
where a.c_zastavky = odZ and a.idlocation = location and a.packet = packet and b.idlocation = location and b.packet = packet and (b.prestup = 1 or b.c_zastavky = cil)
/*group by a.c_linky, a.c_tarif, smer*/) part1

left outer join zaslinky part2
on (part2.c_linky = part1.c_linky and not part2.c_zastavky = outZ and not part2.c_zastavky = pocatek and part2.c_tarif = case when part1.smer = 0 then part1.od_tarif + part1.dist else part1.od_tarif - part1.dist end 
and part2.idlocation = location and part2.packet = packet)

union

select * from 
(select a1.c_linky, a1.c_zastavky, a1.c_tarif as od_tarif, b1.c_zastavky as do_zastavky, b1.c_tarif, case when a1.c_tarif > b1.c_tarif then 1 else 0 end as smer,
(abs(CAST(a1.c_tarif as SIGNED) - CAST(b1.c_tarif as SIGNED))) as dist, a1.idlocation as idlocation, a1.packet as packet
from zaslinky a1 
left outer join zaslinky b1
on (a1.c_linky = b1.c_linky and a1.idlocation = b1.idlocation and a1.packet = b1.packet)
where a1.c_zastavky = odZ and b1.c_zastavky = cil and a1.idlocation = location and a1.packet = packet and b1.idlocation = location and b1.packet = packet) p2

) zl) zl1

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = zl1.c_linky and spoje.smer = zl1.smer)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = zl1.od_tarif)        
left outer JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location AND
                    bchrono.packet = packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = zl1.do_tarif)   
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = zl1.od_zastavky)
left outer JOIN zastavky zastavky1 ON (
                    zastavky1.idlocation = location AND
                    zastavky1.packet = packet AND
                    zastavky1.c_zastavky = zl1.do_zastavky)
left outer JOIN linky linky ON (
                    linky.idlocation = location AND
                    linky.packet = packet AND
                    linky.c_linky = spoje.c_linky
                    )

WHERE /*((kodpozn & den) = den or kodpozn = 0) */((kodpozn & den) = case when kodpozn > den then den else kodpozn end or kodpozn = 0)
and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
and (spoje.HH * 60 + spoje.MM + CAST(chronometr.doba_pocatek as SIGNED)) >= Cod and (spoje.HH * 60 + spoje.MM + CAST(chronometr.doba_pocatek AS SIGNED) <= Cdo)
and case when not doZ = -1 then zl1.do_zastavky = doZ else 1 end
/*order by part1.c_linky, part1.od_tarif, part1.smer, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek)*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getOdjezdyZastavkaTEST4`(in location int, in packet int, in Cod int, in Cdo int, in odZ int, in doZ int, in pocatek int, in cil int, in outZ int, in den int)
BEGIN
/*explain extended*/ select SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT distinct
zl1.c_linky, zl1.od_zastavky as od_zastavky, zl1.do_zastavky as do_zastavky, 
zl1.od_tarif, zl1.do_tarif as do_tarif, spoje.smer, /*part1.idlocation, part1.packet,*/
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
/*(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo*/(cast(bchrono.doba_pocatek as SIGNED) - cast(chronometr.doba_pocatek as SIGNED)) as doba_jizdy/*bchrono.doba_jizdy*/,
spoje.c_spoje, spoje.chrono, zastavky.nazev, zastavky1.nazev, linky.nazev_linky, linky.doprava,
(kodpozn & den), kodpozn, den
from

(select zl.c_linky, zl.od_zastavky as od_zastavky, zl.od_tarif, zl.do_zastavky as do_zastavky, zl.do_tarif do_tarif, zl.smer,
zl.dist, zl.idlocation, zl.packet from
(
select part1.c_linky, part1.od_zastavky as od_zastavky, od_tarif, part2.c_zastavky as do_zastavky, part2.c_tarif do_tarif, smer,
dist, part1.idlocation, part1.packet from 

(select a.c_linky as c_linky , a.c_zastavky as od_zastavky, a.c_tarif as od_tarif, case when a.c_tarif > b.c_tarif then 1 else 0 end as smer,
(abs(CAST(a.c_tarif as SIGNED) - CAST(b.c_tarif as SIGNED))) as dist, a.idlocation as idlocation, a.packet as packet
from zaslinky a 
left outer join zaslinky b
on (a.c_linky = b.c_linky and not a.c_tarif = b.c_tarif and case when a.c_tarif > b.c_tarif then b.zast_b = 1 else b.zast_a = 1 end and a.idlocation = b.idlocation and a.packet = b.packet)
where a.c_zastavky = odZ and a.idlocation = location and a.packet = packet and b.idlocation = location and b.packet = packet and (b.prestup = 1 or b.c_zastavky = cil)
/*group by a.c_linky, a.c_tarif, smer*/) part1

left outer join zaslinky part2
on (part2.c_linky = part1.c_linky and not part2.c_zastavky = outZ and not part2.c_zastavky = pocatek and part2.c_tarif = case when part1.smer = 0 then part1.od_tarif + part1.dist else part1.od_tarif - part1.dist end 
and part2.idlocation = location and part2.packet = packet)

union

select * from 
(select a1.c_linky, a1.c_zastavky, a1.c_tarif as od_tarif, b1.c_zastavky as do_zastavky, b1.c_tarif, case when a1.c_tarif > b1.c_tarif then 1 else 0 end as smer,
(abs(CAST(a1.c_tarif as SIGNED) - CAST(b1.c_tarif as SIGNED))) as dist, a1.idlocation as idlocation, a1.packet as packet
from zaslinky a1 
left outer join zaslinky b1
on (a1.c_linky = b1.c_linky and a1.idlocation = b1.idlocation and a1.packet = b1.packet)
where a1.c_zastavky = odZ and b1.c_zastavky = cil and a1.idlocation = location and a1.packet = packet and b1.idlocation = location and b1.packet = packet) p2

) zl) zl1

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = zl1.c_linky and spoje.smer = zl1.smer)
left outer JOIN chronometr ON (
                    chronometr.idlocation = location AND
                    chronometr.packet = packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = zl1.od_tarif)        
left outer JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = location AND
                    bchrono.packet = packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = zl1.do_tarif)   
left outer JOIN zastavky zastavky ON (
                    zastavky.idlocation = location AND
                    zastavky.packet = packet AND
                    zastavky.c_zastavky = zl1.od_zastavky)
left outer JOIN zastavky zastavky1 ON (
                    zastavky1.idlocation = location AND
                    zastavky1.packet = packet AND
                    zastavky1.c_zastavky = zl1.do_zastavky)
left outer JOIN linky linky ON (
                    linky.idlocation = location AND
                    linky.packet = packet AND
                    linky.c_linky = spoje.c_linky
                    )

WHERE ((kodpozn & den) = case when kodpozn > den then den else kodpozn end or kodpozn = 0)
and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
/*and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= Cod and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek <= Cdo)*/
/*and case when not doZ = -1 then zl1.do_zastavky = doZ else 1 end*/
order by zl1.od_zastavky, zl1.do_zastavky, zl1.c_linky, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek)
/*order by part1.c_linky, part1.od_tarif, part1.smer, (spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek)*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getSpojeOdDo`(in location int, in packet int, in pocatek int, in cil int, in cas int, in mincekani int, in maxcekani int, in den int)
BEGIN
/*explain extended*/ select /*SQL_BUFFER_RESULT SQL_CACHE HIGH_PRIORITY SQL_BIG_RESULT*/ distinct
 * from

(select distinct
zastavkyoddo.c_linky,
zastavkyoddo.od_zastavky,
zastavkyoddo.do_zastavky,
zastavkyoddo.od_tarif,
zastavkyoddo.do_tarif,
zastavkyoddo.smer,
spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek startCAS,
spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek endCAS,
bchrono.doba_pocatek - chronometr.doba_pocatek doba_jizdy
/*spoje.HH, spoje.MM,
chronometr.doba_pocatek as od_pocatek,
bchrono.doba_pocatek as do_pocatek*/
from

(select z1.c_linky as c_linky, z1.c_zastavky as od_zastavky, z1.c_tarif as od_tarif, z2.c_zastavky as do_zastavky, z2.c_tarif as do_tarif, case when z1.c_tarif < z2.c_tarif then 0 else 1 end as smer from zaslinky z1
left outer join zaslinky z2 
on (z1.idlocation = z2.idlocation and z1.packet = z2.packet and z1.c_linky = z2.c_linky and (z2.prestup = 1 or z2.c_zastavky = cil))
where z1.idlocation = location and z1.packet = packet and z1.c_zastavky = pocatek
and not z1.c_zastavky=z2.c_zastavky
and case when z1.c_tarif < z2.c_tarif then (z2.zast_a = 1 and z1.zast_a = 1) else (z2.zast_b = 1 and z1.zast_b = 1) end) zastavkyoddo

left outer join 
spoje
ON (spoje.idlocation = location and spoje.packet = packet and spoje.c_linky = zastavkyoddo.c_linky and spoje.smer = zastavkyoddo.smer)
left outer JOIN chronometr ON (
                    chronometr.idlocation = spoje.idlocation AND
                    chronometr.packet = spoje.packet AND
                    chronometr.c_linky = spoje.c_linky
                    AND chronometr.smer = spoje.smer
                    AND chronometr.chrono = spoje.chrono
                    AND chronometr.c_tarif = zastavkyoddo.od_tarif)        
left outer JOIN chronometr bchrono ON ( 
                    bchrono.idlocation = spoje.idlocation AND
                    bchrono.packet = spoje.packet AND
                    bchrono.c_linky = spoje.c_linky
                    AND bchrono.smer = spoje.smer
                    AND bchrono.chrono = spoje.chrono
                    AND bchrono.c_tarif = zastavkyoddo.do_tarif)
                    
WHERE ((spoje.kodpozn & den) > 0 or spoje.kodpozn = 0)
and NOT chronometr.doba_jizdy = -1 and not bchrono.doba_jizdy = -1
and (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) >= cas 
and (CAST((spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as SIGNED) - CAST(cas as SIGNED)) >= mincekani
and (CAST((spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as SIGNED) - CAST(cas as SIGNED)) <= maxcekani
order by zastavkyoddo.do_zastavky, (spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek)) odjezdy  

group by odjezdy.od_zastavky, odjezdy.do_zastavky;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getTrasy`(in location int, in packet int, in odZ varchar(5000), in outdoZ varchar(5000))
BEGIN
/*select * from (*/
SELECT distinct
spojeni.doLinka, spojeni.odZ, spojeni.doZ, spojeni.odT, spojeni.doT, spojeni.smer, spojeni.vaha
FROM
spojeni
WHERE
FIND_IN_SET(spojeni.odZ, odZ COLLATE utf8_general_ci) > 0 
and not FIND_IN_SET(spojeni.doZ, outdoZ COLLATE utf8_general_ci) > 0
and spojeni.idlocation = location and spojeni.packet = packet
/*group by doLinka, smer*/
/*order by doLinka, smer, doT, vaha desc) a*/
/*group by a.doLinka, a.smer*/;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`getTrasyCil`(in location int, in packet int, in cil int, in odZ varchar(5000))
BEGIN
select * from (
select 
(select count(doZ) from spojeni where spojeni.idlocation = location and spojeni.packet = packet and doZ = cil) as mam,
spojeni.doLinka, spojeni.doZ as odZ, spojeni.odZ as doZ, spojeni.doT as odT, spojeni.odT as doT, spojeni.smer/*,
(spoje.HH * 60 + spoje.MM + chronometr.doba_pocatek) as CASod,
(spoje.HH * 60 + spoje.MM + bchrono.doba_pocatek) as CASdo*/
from spojeni
                   
WHERE spojeni.idlocation = location and spojeni.packet = packet
                  and spojeni.odZ = cil
                  and FIND_IN_SET(spojeni.doZ, odZ COLLATE utf8_general_ci) > 0
/*                  and not FIND_IN_SET(spojeni.doZ, odZ COLLATE utf8_general_ci) > 0*/
) a where a.mam = 0;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`jeden_prestup_pack`(in loc int, in pack int, in z int, in d int, in casod int, in casdo int, in minprestup int, in den varchar(255))
BEGIN

DROP TEMPORARY TABLE IF EXISTS zlink;
CREATE TEMPORARY TABLE zlink
(
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

) ENGINE = MEMORY PACK_KEYS = 1 DEFAULT CHARSET=utf8;

insert into zlink
select
z.c_linky,
z.c_tarif,
z.c_zastavky,
z1.c_tarif,
z1.c_zastavky,
z2.c_linky,
z2.c_tarif,
z2.c_zastavky,
z3.c_tarif,
z3.c_zastavky,
spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek as odjezd1,
spoje.hh * 60 + spoje.mm + chronometrprijezd.doba_pocatek as prijezd1,
(select spoje.c_spoje from
spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = pack

where spoje.idlocation = loc and spoje.packet = pack and
spoje.c_linky =z2.c_linky and
spoje.smer = case when (z2.c_tarif > z3.c_tarif) then 1 else 0 end and
                        (
                        (
                        FIND_IN_SET(spoje.pk1, den) > 0
                        OR FIND_IN_SET(spoje.pk2, den) > 0
                        OR FIND_IN_SET(spoje.pk3, den) > 0
                        OR FIND_IN_SET(spoje.pk4, den) > 0
                        OR FIND_IN_SET(spoje.pk5, den) > 0
                        OR FIND_IN_SET(spoje.pk6, den) > 0
                        OR FIND_IN_SET(spoje.pk7, den) > 0
                        OR FIND_IN_SET(spoje.pk8, den) > 0
                        OR FIND_IN_SET(spoje.pk9, den) > 0
                        OR FIND_IN_SET(spoje.pk10, den) > 0
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )
and
chronometrodjezd.c_tarif = z2.c_tarif and

(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= prijezd1 + 2 and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= prijezd1 + minprestup + 60
limit 1
) as c_spojeodjezd2

from

zaslinky z

join

zaslinky z1

on
z.idlocation = loc and z.c_zastavky = z and z.c_linky = z1.c_linky and
z.c_linky in (select c_linky from zaslinky where idlocation = loc and c_zastavky = z and packet = pack)
and not z.c_linky in (select c_linky from zaslinky where idlocation = loc and c_zastavky = d and packet = pack) and z.packet = pack and z1.packet = pack

join

zaslinky z2

on
z1.idlocation = loc
and z1.c_zastavky = z2.c_zastavky and z2.packet = pack

join

zaslinky z3

on
z3.idlocation = loc and z3.c_zastavky = d and z3.c_linky = z2.c_linky and not z2.c_zastavky = z and z3.packet = pack

join

spoje spoje

on spoje.idlocation = loc and spoje.packet = pack and
spoje.c_linky = z.c_linky and
spoje.smer = case when (z.c_tarif > z1.c_tarif) then 1 else 0 end and
                        (
                        (
                        FIND_IN_SET(spoje.pk1, den) > 0
                        OR FIND_IN_SET(spoje.pk2, den) > 0
                        OR FIND_IN_SET(spoje.pk3, den) > 0
                        OR FIND_IN_SET(spoje.pk4, den) > 0
                        OR FIND_IN_SET(spoje.pk5, den) > 0
                        OR FIND_IN_SET(spoje.pk6, den) > 0
                        OR FIND_IN_SET(spoje.pk7, den) > 0
                        OR FIND_IN_SET(spoje.pk8, den) > 0
                        OR FIND_IN_SET(spoje.pk9, den) > 0
                        OR FIND_IN_SET(spoje.pk10, den) > 0
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )



join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.c_tarif = z.c_tarif and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = pack and

(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= casod and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= casdo

join

chronometr chronometrprijezd

on chronometrprijezd.idlocation = loc and chronometrprijezd.c_linky = spoje.c_linky and
chronometrprijezd.c_tarif = z1.c_tarif and chronometrprijezd.smer = spoje.smer and
chronometrprijezd.chrono = spoje.chrono and not chronometrprijezd.doba_jizdy = -1 and chronometrprijezd.packet = pack;


select * from
(select

c_linky1 as linka1,
(select nazev_linky from linky where idlocation = loc and c_linky = c_linky1 and packet = pack limit 1) as nazev_linky1,
(select doprava from linky where idlocation = loc and c_linky = c_linky1 and packet = pack limit 1) as doprava_linky1,
c_zastavky1 as zezastavky1,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky1 and packet = pack limit 1) as nazev_zezstavky1,
c_tarif1 as zetarif1,
c_zastavky2 as dozastavky1,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky2 and packet = pack limit 1) as nazev_dozstavky1,
c_tarif2 as dotarif1,
case when (c_tarif1 > c_tarif2) then 1 else 0 end as smer1,

c_linky2 as linka2,
(select nazev_linky from linky where idlocation = loc and c_linky = c_linky2 and packet = pack limit 1) as nazev_linky2,
(select doprava from linky where idlocation = loc and c_linky = c_linky2 and packet = pack limit 1) as doprava_linky2,
c_zastavky3 as zezastavky2,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky3 and packet = pack limit 1) as nazev_zezstavky2,
c_tarif3 as zetarif2,
c_zastavky4 as dozastavky2,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky4 and packet = pack limit 1) as nazev_dozstavky2,
c_tarif4 as dotarif2,
case when (c_tarif3 > c_tarif4) then 1 else 0 end as smer2,

odjezd1,
prijezd1 ,

c_spojeodjezd2,

(select  spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek from

spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and chronometrodjezd.packet = pack and spoje.packet = pack

where spoje.idlocation = loc and spoje.c_linky = c_linky2 and spoje.c_spoje = c_spojeodjezd2 and chronometrodjezd.c_tarif = c_tarif3 and not chronometrodjezd.doba_jizdy = -1
) odjezd2,

(select spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek from

spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and chronometrodjezd.packet = pack and spoje.packet = pack

where spoje.idlocation = loc and spoje.c_linky = c_linky2 and spoje.c_spoje = c_spojeodjezd2 and chronometrodjezd.c_tarif = c_tarif4 and not chronometrodjezd.doba_jizdy = -1
) prijezd2

 from zlink ) finish

 where not(c_spojeodjezd2 is null or prijezd2 is null);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`jeden_prestup_pack_debug`(in loc int, in pack int, in z int, in d int, in casod int, in casdo int, in minprestup int, in den varchar(255))
BEGIN

DROP TEMPORARY TABLE IF EXISTS zlink;
CREATE TEMPORARY TABLE zlink
(
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

) ENGINE = MEMORY PACK_KEYS = 1 DEFAULT CHARSET=utf8;

insert into zlink
select
z.c_linky,
z.c_tarif,
z.c_zastavky,
z1.c_tarif,
z1.c_zastavky,
z2.c_linky,
z2.c_tarif,
z2.c_zastavky,
z3.c_tarif,
z3.c_zastavky,
spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek as odjezd1,
spoje.hh * 60 + spoje.mm + chronometrprijezd.doba_pocatek as prijezd1,
(select spoje.c_spoje from
spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = pack

where spoje.idlocation = loc and spoje.packet = pack and
spoje.c_linky =z2.c_linky and
spoje.smer = case when (z2.c_tarif > z3.c_tarif) then 1 else 0 end and
                        (
                        (
                        FIND_IN_SET(spoje.pk1, den) > 0
                        OR FIND_IN_SET(spoje.pk2, den) > 0
                        OR FIND_IN_SET(spoje.pk3, den) > 0
                        OR FIND_IN_SET(spoje.pk4, den) > 0
                        OR FIND_IN_SET(spoje.pk5, den) > 0
                        OR FIND_IN_SET(spoje.pk6, den) > 0
                        OR FIND_IN_SET(spoje.pk7, den) > 0
                        OR FIND_IN_SET(spoje.pk8, den) > 0
                        OR FIND_IN_SET(spoje.pk9, den) > 0
                        OR FIND_IN_SET(spoje.pk10, den) > 0
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )
and
chronometrodjezd.c_tarif = z2.c_tarif /*and

(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= prijezd1 + 2 and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= prijezd1 + minprestup + 120*/
limit 1
) as c_spojeodjezd2

from

zaslinky z

join

zaslinky z1

on
z.idlocation = loc and z.c_zastavky = z and z.c_linky = z1.c_linky and
z.c_linky in (select c_linky from zaslinky where idlocation = loc and c_zastavky = z and packet = pack)
and not z.c_linky in (select c_linky from zaslinky where idlocation = loc and c_zastavky = d and packet = pack) and z.packet = pack and z1.packet = pack

join

zaslinky z2

on
z1.idlocation = loc
and z1.c_zastavky = z2.c_zastavky and z2.packet = pack

join

zaslinky z3

on
z3.idlocation = loc and z3.c_zastavky = d and z3.c_linky = z2.c_linky and not z2.c_zastavky = z and z3.packet = pack

join

spoje spoje

on spoje.idlocation = loc and spoje.packet = pack and
spoje.c_linky = z.c_linky and
spoje.smer = case when (z.c_tarif > z1.c_tarif) then 1 else 0 end and
                        (
                        (
                        FIND_IN_SET(spoje.pk1, den) > 0
                        OR FIND_IN_SET(spoje.pk2, den) > 0
                        OR FIND_IN_SET(spoje.pk3, den) > 0
                        OR FIND_IN_SET(spoje.pk4, den) > 0
                        OR FIND_IN_SET(spoje.pk5, den) > 0
                        OR FIND_IN_SET(spoje.pk6, den) > 0
                        OR FIND_IN_SET(spoje.pk7, den) > 0
                        OR FIND_IN_SET(spoje.pk8, den) > 0
                        OR FIND_IN_SET(spoje.pk9, den) > 0
                        OR FIND_IN_SET(spoje.pk10, den) > 0
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )



join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.c_tarif = z.c_tarif and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = pack /*and

(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= casod and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= casdo*/

join

chronometr chronometrprijezd

on chronometrprijezd.idlocation = loc and chronometrprijezd.c_linky = spoje.c_linky and
chronometrprijezd.c_tarif = z1.c_tarif and chronometrprijezd.smer = spoje.smer and
chronometrprijezd.chrono = spoje.chrono and not chronometrprijezd.doba_jizdy = -1 and chronometrprijezd.packet = pack;


select * from
(select

c_linky1 as linka1,
(select nazev_linky from linky where idlocation = loc and c_linky = c_linky1 and packet = pack limit 1) as nazev_linky1,
(select doprava from linky where idlocation = loc and c_linky = c_linky1 and packet = pack limit 1) as doprava_linky1,
c_zastavky1 as zezastavky1,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky1 and packet = pack limit 1) as nazev_zezstavky1,
c_tarif1 as zetarif1,
c_zastavky2 as dozastavky1,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky2 and packet = pack limit 1) as nazev_dozstavky1,
c_tarif2 as dotarif1,
case when (c_tarif1 > c_tarif2) then 1 else 0 end as smer1,

c_linky2 as linka2,
(select nazev_linky from linky where idlocation = loc and c_linky = c_linky2 and packet = pack limit 1) as nazev_linky2,
(select doprava from linky where idlocation = loc and c_linky = c_linky2 and packet = pack limit 1) as doprava_linky2,
c_zastavky3 as zezastavky2,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky3 and packet = pack limit 1) as nazev_zezstavky2,
c_tarif3 as zetarif2,
c_zastavky4 as dozastavky2,
(select nazev from zastavky where idlocation = loc and c_zastavky = c_zastavky4 and packet = pack limit 1) as nazev_dozstavky2,
c_tarif4 as dotarif2,
case when (c_tarif3 > c_tarif4) then 1 else 0 end as smer2,

odjezd1,
prijezd1 ,

c_spojeodjezd2,

(select  spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek from

spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and chronometrodjezd.packet = pack and spoje.packet = pack

where spoje.idlocation = loc and spoje.c_linky = c_linky2 and spoje.c_spoje = c_spojeodjezd2 and chronometrodjezd.c_tarif = c_tarif3 and not chronometrodjezd.doba_jizdy = -1
) odjezd2,

(select spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek from

spoje

join

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and chronometrodjezd.packet = pack and spoje.packet = pack

where spoje.idlocation = loc and spoje.c_linky = c_linky2 and spoje.c_spoje = c_spojeodjezd2 and chronometrodjezd.c_tarif = c_tarif4 and not chronometrodjezd.doba_jizdy = -1
) prijezd2

 from zlink ) finish

 where not(c_spojeodjezd2 is null or prijezd2 is null);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`OptimizeDbForTables`()
BEGIN
   -- Optimalizace tabulek 
   
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
	
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` FUNCTION `import`.`pokus`()
 RETURNS varchar(1000) CHARSET utf8
BEGIN
  DECLARE cursor_VAL VARCHAR(6);
  DECLARE ret_val VARCHAR(1000);
  DECLARE done INT DEFAULT FALSE;
  
DECLARE cursor_i CURSOR FOR select c_linky from zaslinky where idlocation = 6 and packet = 166 and c_zastavky = 80 order by c_linky;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  SET ret_val = '';
  OPEN cursor_i;
  read_loop: LOOP
    FETCH cursor_i INTO cursor_VAL;
    IF done THEN
      LEAVE read_loop;
    END IF;
    SET ret_val = concat(ret_val,';',cursor_val);/* + CAST(cursor_VAL as CHAR);*/
  END LOOP;
  CLOSE cursor_i;
RETURN ret_val;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` FUNCTION `import`.`price`(location int, packet int, cas int, czone int)
 RETURNS double
BEGIN
DECLARE min_cas INT;
DECLARE min_cena DOUBLE;
DECLARE a_cas INT;
DECLARE a_cena DOUBLE;
DECLARE x_cas INT;
DECLARE podil INT;
DECLARE zbytek INT;
DECLARE cena_result DOUBLE;
SET x_cas = cas;
SET cena_result = 0;
SELECT min(cas) INTO min_cas FROM prices WHERE prices.location = location and prices.packet = packet and prices.countzone = czone;

while (x_cas > min_cas) do 
  SELECT cas, cena INTO a_cas, a_cena FROM prices WHERE prices.location = location and prices.packet = packet and prices.countzone = czone and prices.cas <= cas order by prices.cas desc limit 1;
  SET podil = (x_cas DIV a_cas);
  SET x_cas = MOD(x_cas, a_cas);
  SET cena_result = cena_result + (podil * a_cena);  
end while;

if (x_cas != 0) then 
  SELECT min(cena) INTO min_cena FROM prices WHERE prices.location = location and prices.packet = packet and prices.countzone = czone and prices.cas = min_cas;
  SET cena_result = cena_result + min_cena;
end if;

RETURN (SELECT cena_result);
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`prima_linka_pack`(in loc int, in pack int, in zezast int, in dozast int, in casod int, in casdo int, in den varchar(255))
BEGIN
select
z1.c_linky as linka1,
(select distinct nazev_linky from linky where idlocation = loc and c_linky = z1.c_linky and packet = pack) as nazev_linky1,
(select distinct doprava from linky where idlocation = loc and c_linky = z1.c_linky and packet = pack) as doprava_linky1,
z1.c_zastavky as zezastavky1,
(select distinct nazev from zastavky where idlocation = loc and c_zastavky = z1.c_zastavky and packet = pack) as nazev_zezastavky1,
z1.c_tarif as zetarif1,
z2.c_zastavky as dozastavky1,
(select distinct nazev from zastavky where idlocation = loc and c_zastavky = z2.c_zastavky and packet = pack) as nazev_dozastavky1,
z2.c_tarif as dotarif1,
case when (z1.c_tarif > z2.c_tarif) then 1 else 0 end as smer1,
spoje.hh * 60 + spoje.mm + chronometrodjezd.doba_pocatek as odjezd1,
spoje.hh * 60 + spoje.mm + chronometrprijezd.doba_pocatek as prijezd1

from

(select * from zaslinky where packet = pack and c_linky in (select c_linky from linky where idlocation = loc and packet = pack /*and (jr_od <= '2001-09-01' or jr_od is null) and (jr_do >= '2020-09-01' or jr_do is null)*/)) z1

join

(select * from zaslinky where packet = pack and c_linky in (select c_linky from linky where idlocation = loc and packet = pack /*and (jr_od <= '2020-09-01' or jr_od is null) and (jr_do >= '2020-09-01' or jr_do is null)*/)) z2

on
z1.idlocation = loc and z2.idlocation = loc and
z1.c_zastavky = zezast and
z1.c_linky = z2.c_linky and
z2.c_zastavky = dozast

join

spoje spoje

on spoje.idlocation = loc and
spoje.c_linky = z1.c_linky and spoje.packet = pack and
spoje.smer = case when (z1.c_tarif > z2.c_tarif) then 1 else 0 end and
                        (
                        (
                        FIND_IN_SET(spoje.pk1, den) > 0
                        OR FIND_IN_SET(spoje.pk2, den) > 0
                        OR FIND_IN_SET(spoje.pk3, den) > 0
                        OR FIND_IN_SET(spoje.pk4, den) > 0
                        OR FIND_IN_SET(spoje.pk5, den) > 0
                        OR FIND_IN_SET(spoje.pk6, den) > 0
                        OR FIND_IN_SET(spoje.pk7, den) > 0
                        OR FIND_IN_SET(spoje.pk8, den) > 0
                        OR FIND_IN_SET(spoje.pk9, den) > 0
                        OR FIND_IN_SET(spoje.pk10, den) > 0
                        )
                        OR (
                        NOT spoje.pk1
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk2
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk3
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk4
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk5
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk6
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk7
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk8
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk9
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        AND NOT spoje.pk10
                        IN (
                        SELECT pevnykod.c_kodu
                        FROM pevnykod
                        WHERE pevnykod.caspozn = 1 and idlocation = loc
                        )
                        )
                        OR (
                        spoje.pk1 = 0
                        AND spoje.pk2 = 0
                        AND spoje.pk3 = 0
                        AND spoje.pk4 = 0
                        AND spoje.pk5 = 0
                        AND spoje.pk6 = 0
                        AND spoje.pk7 = 0
                        AND spoje.pk8 = 0
                        AND spoje.pk9 = 0
                        AND spoje.pk10 = 0
                        )
                        )

join

/*zasspoje zasspoje

on zasspoje.idlocation = loc and spoje.c_linky = zasspoje.c_linky and spoje.c_spoje = zasspoje.c_spoje and spoje.smer = zasspoje.smer

join*/

chronometr chronometrodjezd

on chronometrodjezd.idlocation = loc and chronometrodjezd.c_linky = spoje.c_linky and
chronometrodjezd.c_tarif = z1.c_tarif and chronometrodjezd.smer = spoje.smer and
chronometrodjezd.chrono = spoje.chrono and not chronometrodjezd.doba_jizdy = -1 and chronometrodjezd.packet = pack and spoje.packet = pack and
(spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) >= casod and (spoje.HH * 60 + spoje.MM + chronometrodjezd.doba_pocatek) <= casdo

join

chronometr chronometrprijezd

on chronometrprijezd.idlocation = loc and chronometrprijezd.c_linky = spoje.c_linky and
chronometrprijezd.c_tarif = z2.c_tarif and chronometrprijezd.smer = spoje.smer and chronometrprijezd.packet = pack and spoje.packet = pack and
chronometrprijezd.chrono = spoje.chrono and not chronometrprijezd.doba_jizdy = -1;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`setbcodespoje`(in location int, in packet int)
BEGIN
update spoje set kodpozn = coalesce(coalesce(bcode(location, packet, spoje.pk1), 0) + coalesce(bcode(location, packet, spoje.pk2), 0) + coalesce(bcode(location, packet, spoje.pk3), 0) + 
coalesce(bcode(location, packet, spoje.pk4), 0) + coalesce(bcode(location, packet, spoje.pk5), 0) + coalesce(bcode(location, packet, spoje.pk6), 0) + coalesce(bcode(location, packet, spoje.pk7), 0) + 
coalesce(bcode(location, packet, spoje.pk8), 0) + coalesce(bcode(location, packet, spoje.pk9), 0) + coalesce(bcode(location, packet, spoje.pk10), 0), 0) 
where idlocation = location and packet = packet;
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`seznamKurzuLinky`(in linka varchar(11), in kodpozn int, in location int, in packet int)
BEGIN
/*select c_linky, kurz, min(idkurz) from spoje where spoje.idlocation = location and spoje.packet = packet and c_linky = linka COLLATE utf8_general_ci and 
(spoje.kodpozn & kodpozn) > 0 and 
concat(c_linky,',',idkurz) not in 
(select concat(dolinky,',',dokurzu) from spoje 
where spoje.idlocation = location and spoje.packet = packet and 
(spoje.kodpozn & kodpozn) > 0 and 
dolinky is not null and dokurzu is not null) 
group by c_linky, kurz; */

select c_linky, kurz, min(idkurz) from spoje where spoje.idlocation = location and spoje.packet = packet and c_linky COLLATE utf8_general_ci = linka and 
(spoje.kodpozn & kodpozn) > 0 and not exists(select 1 from spoje 
where spoje.idlocation = location and spoje.packet = packet and 
(spoje.kodpozn & kodpozn) > 0 and c_linky COLLATE utf8_general_ci = linka and concat(spoje.dolinky,',',spoje.dokurzu) = concat(c_linky,',',idkurz) and dolinky is not null and dokurzu is not null)
group by c_linky, kurz; 
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`SpojeKurzyLinky`(in linka varchar(11), in kurz int, in kodpozn int, in location int, in packet int)
BEGIN
select * from spoje where spoje.idlocation = location and spoje.packet = packet and c_linky = linka COLLATE utf8_general_ci and 
(spoje.kodpozn & kodpozn) > 0 and spoje.idkurz = kurz; 
END;

CREATE DEFINER = `savvy_mhdspoje`@`%` PROCEDURE `import`.`SpojeKurzyLinkyOdjezdy`(in linka varchar(11), in kurz int, in kodpozn int, in location int, in packet int)
BEGIN
  SELECT spoje.c_spoje, spoje.c_tarif, spoje.chrono,
    spoje.pk1, spoje.pk2, spoje.pk3, spoje.pk4, spoje.pk5, spoje.pk6, spoje.pk7, spoje.pk8, spoje.pk9, spoje.pk10,                 
    spoje.HH, spoje.MM,
    chronometr.doba_jizdy,
    case 
      when (chronometr.doba_jizdy = -1) then '--'
        when ((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) div 60) mod 24) < 10 then
          concat('0', cast( ((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) div 60) mod 24) as char ) )
        else
          ((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) div 60) mod 24)
    end as odjezdHH,
    case 
      when (chronometr.doba_jizdy = -1) then '--'
        when (((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) mod 60) < 10 then
          concat('0', cast((((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) mod 60) as char))
        else
          (((spoje.HH * 60) + spoje.MM + chronometr.doba_pocatek) mod 60)
     end as odjezdMM,
     spoje.kurz,
     zaslinky.c_tarif as zaslinky_c_tarif,
     chronometr.doba_pocatek,
     chronometr.chrono,
     chronometr.c_tarif,
     spoje.dolinky,
     spoje.dokurzu,
     spoje.smer,
     case when zasspoje_pozn.pk1 is null then 0 else zasspoje_pozn.pk1 end as pk1,
     case when zasspoje_pozn.pk2 is null then 0 else zasspoje_pozn.pk2 end as pk2,
     case when zasspoje_pozn.dpk1 is null then 0 else zasspoje_pozn.dpk1 end as dpk1,
     case when zasspoje_pozn.dpk2 is null then 0 else zasspoje_pozn.dpk2 end as dpk2,
     case when zasspoje_pozn.dpk3 is null then 0 else zasspoje_pozn.dpk3 end as dpk3,
     case when zasspoje_pozn.dpk4 is null then 0 else zasspoje_pozn.dpk4 end as dpk4,
     case when zasspoje_pozn.dpk5 is null then 0 else zasspoje_pozn.dpk5 end as dpk5,
     case when zasspoje_pozn.dpk6 is null then 0 else zasspoje_pozn.dpk6 end as dpk6,
     case when zasspoje_pozn.dpk7 is null then 0 else zasspoje_pozn.dpk7 end as dpk7,
     case when zasspoje_pozn.dpk8 is null then 0 else zasspoje_pozn.dpk8 end as dpk8,
     case when zasspoje_pozn.dpk9 is null then 0 else zasspoje_pozn.dpk9 end as dpk9     
     FROM (
       select * from spoje where spoje.idlocation = location and spoje.packet = packet and c_linky = linka COLLATE utf8_general_ci and 
       (spoje.kodpozn & kodpozn) > 0 and spoje.idkurz = kurz
     ) AS spoje

     left outer join savvy_mhdspoje.zaslinky on (spoje.c_linky = zaslinky.c_linky and zaslinky.idlocation = spoje.idlocation and zaslinky.packet = spoje.packet)
     left outer join savvy_mhdspoje.chronometr on (spoje.c_linky = chronometr.c_linky and spoje.smer = chronometr.smer
     and spoje.chrono = chronometr.chrono and zaslinky.c_tarif = chronometr.c_tarif and chronometr.idlocation = spoje.idlocation and chronometr.packet = spoje.packet)
     LEFT OUTER JOIN zasspoje_pozn ON ( spoje.c_linky = zasspoje_pozn.c_linky
     AND spoje.c_spoje = zasspoje_pozn.c_spoje
     AND zasspoje_pozn.c_tarif = zaslinky.c_tarif and zasspoje_pozn.idlocation = location AND zasspoje_pozn.packet = packet)
     WHERE zaslinky.voz_a = 1 or zaslinky.voz_b = 1
     ORDER BY c_spoje, zaslinky_c_tarif, HH, MM;
--     CASE smer WHEN 1 THEN zaslinky.c_tarif END DESC ,CASE WHEN NOT smer = 1 THEN zaslinky.c_tarif END;
END;

CREATE ALGORITHM = UNDEFINED DEFINER = `savvy_mhdspoje`@`%` SQL SECURITY DEFINER VIEW `import`.`prujezdy` AS select high_priority distinct `spoje`.`IDLOCATION` AS `idlocation`,`spoje`.`PACKET` AS `packet`,`spoje`.`C_LINKY` AS `c_linky`,`spojeni`.`odZ` AS `odZ`,`spojeni`.`doZ` AS `doZ`,`spojeni`.`odT` AS `odT`,`spojeni`.`doT` AS `doT`,`spoje`.`SMER` AS `smer`,(((`spoje`.`HH` * 60) + `spoje`.`MM`) + `chronometr`.`DOBA_POCATEK`) AS `CASod`,(((`spoje`.`HH` * 60) + `spoje`.`MM`) + `bchrono`.`DOBA_POCATEK`) AS `CASdo` from (((`spoje` join `spojeni` on(((`spojeni`.`idlocation` = `spoje`.`IDLOCATION`) and (`spojeni`.`packet` = `spoje`.`PACKET`) and (`spoje`.`C_LINKY` = `spojeni`.`doLinka`) and (`spoje`.`SMER` = `spojeni`.`smer`)))) join `chronometr` on(((`chronometr`.`IDLOCATION` = `spojeni`.`idlocation`) and (`chronometr`.`PACKET` = `spojeni`.`packet`) and (`chronometr`.`C_LINKY` = `spojeni`.`doLinka`) and (`chronometr`.`SMER` = `spoje`.`SMER`) and (`chronometr`.`CHRONO` = `spoje`.`CHRONO`) and (`chronometr`.`C_TARIF` = `spojeni`.`odT`) and ((select (sum(`odch`.`DOBA_JIZDY`) / count(`odch`.`DOBA_JIZDY`)) from `chronometr` `odch` where ((`odch`.`C_LINKY` = `spoje`.`C_LINKY`) and (`odch`.`SMER` = `spoje`.`SMER`) and (`odch`.`CHRONO` = `spoje`.`CHRONO`) and (`odch`.`IDLOCATION` = `spoje`.`IDLOCATION`) and (`odch`.`PACKET` = `spoje`.`PACKET`) and (((`odch`.`SMER` = 0) and (`odch`.`C_TARIF` > `chronometr`.`C_TARIF`)) or ((`odch`.`SMER` = 1) and (`odch`.`C_TARIF` < `chronometr`.`C_TARIF`))))) <> -(1))))) join `chronometr` `bchrono` on(((`bchrono`.`IDLOCATION` = `spojeni`.`idlocation`) and (`bchrono`.`PACKET` = `spojeni`.`packet`) and (`bchrono`.`C_LINKY` = `spojeni`.`doLinka`) and (`bchrono`.`SMER` = `spoje`.`SMER`) and (`bchrono`.`CHRONO` = `spoje`.`CHRONO`) and (`bchrono`.`C_TARIF` = `spojeni`.`doT`)))) where ((`chronometr`.`DOBA_JIZDY` <> -(1)) and (`bchrono`.`DOBA_JIZDY` <> -(1)) and (((`chronometr`.`SMER` = 0) and (`chronometr`.`C_TARIF` < `spojeni`.`doT`)) or ((`chronometr`.`SMER` = 1) and (`chronometr`.`C_TARIF` > `spojeni`.`doT`))));

SET FOREIGN_KEY_CHECKS=1;