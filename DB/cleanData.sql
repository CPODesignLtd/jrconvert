DROP TABLE IF EXISTS packetsToDrop;


CREATE TEMPORARY TABLE packetsToDrop (
`packet` int,
`IDLOCATION` int
);



insert into packetsToDrop 
select p.packet, p.location from packets p
where p.jr_do < '2024-01-01'; 

SELECT count(*) as 'Total 2 DROP' from packetsToDrop;



DELETE c.* from sdruz c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from jrvargrfs c
INNER JOIN jrtypes j on j.idtimepozn = c.idtimepozn
inner join packetsToDrop s on j.PACKET = s.packet and j.IDLOCATION = s.IDLOCATION;

DELETE c.* from jrtypes c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from distance c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from chronometr c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from info c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from pevnykod c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from kalendar c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from pesobus c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from prestupy c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from smer c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from prices c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;


DELETE c.* from spoje c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from lzastavky c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from zaslinky c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from linky c
inner join packetsToDrop s on c.PACKET = s.packet and c.IDLOCATION = s.IDLOCATION;

DELETE c.* from packets c
inner join packetsToDrop s on c.packet = s.packet and c.location = s.IDLOCATION;


OPTIMIZE TABLE chronometer;
OPTIMIZE TABLE info;
OPTIMIZE TABLE jrtypes;
OPTIMIZE TABLE jrvargrfs;
OPTIMIZE TABLE location;
OPTIMIZE TABLE packet;
OPTIMIZE TABLE pevnykod;
OPTIMIZE TABLE pictograms;
OPTIMIZE TABLE sdruz;
OPTIMIZE TABLE spojeni;
OPTIMIZE TABLE zaslinky;
OPTIMIZE TABLE zasspoje;
