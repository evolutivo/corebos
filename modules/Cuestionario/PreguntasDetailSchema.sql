 CREATE TABLE `vtiger_cuestiones` (
`cuestionesid` INT( 19 ) NOT NULL AUTO_INCREMENT ,
`cuestionarioid` INT( 19 ) NOT NULL ,
`pregunta` VARCHAR(250)  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`categoria` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`subcategoria` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`yes_points` FLOAT NOT NULL ,
`no_points` FLOAT NOT NULL ,
PRIMARY KEY ( `cuestionesid` ) ,
INDEX (`cuestionarioid`)
) ENGINE = INNODB 