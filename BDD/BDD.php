<?php
/* 
	Program that create the wanted database and insert the needed data 
	Source :  http://php.net/manual/fr/ ,  https://openclassrooms.com/courses/les-transactions-avec-mysql-et-pdo, some scripts from phpMyAdmin
	To be able to use this script, the following files must be in the same folder : import_data.php, caracteristiques.csv, usagers.csv, vehicules.csv
	PHP 7.1.9 - Configuration (php.ini) : upload_max_filesize = 256M , memory_limit = 1G, max_execution_time = 0 (for no limit, set it to another small value at the end of this script execution)
*/
define('MYHOST', 'tap_your_host_here');
define('USERNAME','tap_your_username_here');
define('PASSWORD','tap_your_password_here');

try
	{
		$db = new PDO("mysql:host=".MYHOST, USERNAME, PASSWORD);															// Connection to the selected host
	}
catch(PDOException $e)																							// Display error if the connection fails 
	{
		echo 'Échec de la connexion à la base de données<br/>';
		echo 'Error : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		exit();
	}
try																													// Creation of the database and its structure (tables)
{
	$db-> beginTransaction();
	$db->query("CREATE DATABASE IF NOT EXISTS `accidents_routiers` DEFAULT CHARACTER SET utf8 ");						
	$db->query("USE `accidents_routiers`");
	$db->query("DROP TABLE IF EXISTS `caracteristiques`");
	$db->query("CREATE TABLE IF NOT EXISTS `caracteristiques` (
	  `num_c` varchar(12) NOT NULL,
	  `an` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16') NOT NULL,
	  `mois` enum('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL,
	  `jour` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL,
	  `hrmn` smallint(4) NOT NULL,
	  `lum` enum('1','2','3','4','5') NOT NULL,
	  `agg` enum('1','2') NOT NULL,
	  `inter` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL,
	  `atm` smallint(1) NOT NULL,
	  `col` enum('0','1','2','3','4','5','6','7') NOT NULL,
	  `com` smallint(3) UNSIGNED NOT NULL,
	  `adr` varchar(50) NOT NULL,
	  `gps` varchar(1) NOT NULL,
	  `lat` decimal(17,6) NOT NULL,
	  `longi` decimal(17,6) NOT NULL,
	  `dep` smallint(3) UNSIGNED ZEROFILL NOT NULL
	) ENGINE=CSV DEFAULT CHARSET=utf8");


	$db->query("DROP TABLE IF EXISTS `usagers`");
	$db->query("CREATE TABLE IF NOT EXISTS `usagers` (
	  `num_u` varchar(12) NOT NULL,
	  `place` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL,
	  `catu` enum('0','1','2','3','4') NOT NULL,
	  `grav` enum('1','2','3','4') NOT NULL,
	  `sexe` enum('0','1','2') NOT NULL,
	  `trajet` enum('0','1','2','3','4','5','9') NOT NULL,
	  `secu` smallint(2) NOT NULL,
	  `locp` enum('0','1','2','3','4','5','6','7','8') NOT NULL,
	  `actp` enum('0','1','2','3','4','5','6','9') NOT NULL,
	  `etatp` enum('0','1','2','3') NOT NULL,
	  `an_nais` smallint(4) NOT NULL,
	  `num_veh` varchar(3) NOT NULL
	) ENGINE=CSV DEFAULT CHARSET=utf8");


	$db->query("DROP TABLE IF EXISTS `vehicules`");
	$db->query("CREATE TABLE IF NOT EXISTS `vehicules` (
	  `num_v` varchar(12) NOT NULL,
	  `senc` enum('0','1','2') NOT NULL,
	  `catv` smallint(2) NOT NULL,
	  `occutc` smallint(3) UNSIGNED NOT NULL,
	  `obs` smallint(2) NOT NULL,
	  `obsm` smallint(1) NOT NULL,
	  `choc` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL,
	  `manv` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24') NOT NULL,
	  `num_veh` varchar(3) NOT NULL
	) ENGINE=CSV DEFAULT CHARSET=utf8");
	
	$db->commit();
	echo 'Base de données avec sa structure créée.';
}
catch(PDOException $e)																								// Display error if one of the queries fails and cancel every change made in the 'try' 
{
	$db->rollback();
	echo 'Error : '.$e->getMessage().'<br />Aucune opération effectuée<br />';
	echo 'N° : '.$e->getCode();
	exit();
}


importFile(1,"caracteristiques.csv");																					//Import the files one after the other and print the number of imported values of each table of the database
importFile(2,"usagers.csv");
importFile(3,"vehicules.csv");
				
echo "Importation des données effectuée <br/>";


try																													// Drop the columns not used in this project 
{
	$db-> beginTransaction();
	$db-> query ("ALTER TABLE `caracteristiques` DROP `agg`");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `inter`");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `col`");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `hrmn`");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `gps`");
	$db-> query ("ALTER TABLE `usagers` DROP `locp`");
	$db-> query ("ALTER TABLE `usagers` DROP `actp`");
	$db-> query ("ALTER TABLE `usagers` DROP `etatp`");
	$db-> query ("ALTER TABLE `usagers` DROP `sexe`");
	$db-> query ("ALTER TABLE `usagers` DROP `secu`");
	$db-> query ("ALTER TABLE `usagers` DROP `place`");
	$db-> query ("ALTER TABLE `usagers` DROP `trajet`");
	$db-> query ("ALTER TABLE `usagers` DROP `catu`");
	$db-> query ("ALTER TABLE `vehicules` DROP `senc`"); 
	$db-> query ("ALTER TABLE `vehicules` DROP `occutc`"); 
	$db-> query ("ALTER TABLE `vehicules` DROP `choc`"); 
	$db-> query ("ALTER TABLE `vehicules` DROP `manv`"); 
	$db->commit();
	echo "Champs en surplus effacés. <br/>";
}
catch(PDOException $e)																								// Display error if one of the queries fails and cancel every change made in the 'try' 
{
	$db->rollback();
	echo 'Error : '.$e->getMessage().'<br />Aucune opération effectuée<br />';
	echo 'N° : '.$e->getCode();
	exit();
}
try
{
	$db->beginTransaction();
	$db->query("ALTER TABLE `caracteristiques` ENGINE = InnODB");													// We switch the tables engine to InnoDB which can manage primary and foreign keys
	$db->query("ALTER TABLE `usagers` ENGINE = InnODB");
	$db->query("ALTER TABLE `vehicules` ENGINE = InnODB");
	$db->commit();
	echo "Le moteur des tables a été changé : de CSV à InnoDB afin de prendre ";
	echo "en charge les systèmes de clés primaires et étrangères.<br>";
}
catch(PDOException $e)
{
	$db->rollback();
	echo 'Error : '.$e->getMessage().'<br />Aucune opération effectuée<br />';
	echo 'N° : '.$e->getCode();
	exit();
}
try																													
{
	$db->beginTransaction();
	$db-> query ("UPDATE `caracteristiques` SET dep=dep*10 WHERE dep < 10");																			// Correction of the broken data of the coordinates and the department
	$db-> query ("UPDATE `caracteristiques` SET lat = lat/100000, longi = longi/100000 WHERE dep NOT IN (971,972,973,974,976)");
	$db-> query ("UPDATE `caracteristiques` SET lat = lat/100000, longi = longi/(-100000) WHERE dep IN (971,972,973)");
	$db-> query ("UPDATE `caracteristiques` SET longi = longi/10 WHERE dep = 973 and longi < -100");
	$db-> query ("UPDATE `caracteristiques` SET lat = lat/(-100000),longi = longi/100000 WHERE dep = 974");
	$db-> query ("ALTER TABLE `caracteristiques` ADD `date_acc` DATE NOT NULL DEFAULT '2000-01-01' AFTER `num_c`");									// Create a date column using the 'an', 'mois' and 'jour' columns then drop those three
	$db-> query ("UPDATE `caracteristiques` SET date_acc = STR_TO_DATE(CONCAT(CAST(jour AS CHAR), ',',CAST(mois AS CHAR),',','200',CAST(an AS CHAR)),'%d,%m,%Y') WHERE an BETWEEN 5 AND 9");
	$db-> query ("UPDATE `caracteristiques` SET date_acc = STR_TO_DATE(CONCAT(CAST(jour AS CHAR), ',',CAST(mois AS CHAR),',','20',CAST(an AS CHAR)),'%d,%m,%Y') WHERE an bETWEEN 10 AND 16");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `an`");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `mois`");
	$db-> query ("ALTER TABLE `caracteristiques` DROP `jour`");
	$db-> query ("ALTER TABLE `caracteristiques` ADD `reg` SMALLINT(2) UNSIGNED ZEROFILL NOT NULL");														// Adding a French region column using the department 'dep' column
	$db-> query ("UPDATE `caracteristiques` SET reg = 84 WHERE dep IN (10,30,70,150,260,380,420,430,630,690,730,740)");												
	$db-> query ("UPDATE `caracteristiques` SET reg = 27 WHERE dep IN (210,250,390,580,700,710,890,900)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 53 WHERE dep IN (220,290,350,560)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 24 WHERE dep IN (180,280,360,370,410,450)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 94 WHERE dep IN (201,202)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 44 WHERE dep IN (80,100,510,520,540,550,570,670,680,880)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 32 WHERE dep IN (20,590,600,620,800)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 11 WHERE dep IN (750,770,780,910,920,930,940,950)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 28 WHERE dep IN (140,270,500,610,760)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 75 WHERE dep IN (160,170,190,230,240,330,400,470,640,790,860,870)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 76 WHERE dep IN (90,110,120,300,310,320,340,460,480,650,660,810,820)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 52 WHERE dep IN (440,490,530,720,850)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 93 WHERE dep IN (40,50,60,130,830,840)");
	$db-> query ("UPDATE `caracteristiques` SET reg = 1 WHERE dep = 971");
	$db-> query ("UPDATE `caracteristiques` SET reg = 2 WHERE dep = 972");
	$db-> query ("UPDATE `caracteristiques` SET reg = 3 WHERE dep = 973");
	$db-> query ("UPDATE `caracteristiques` SET reg = 4 WHERE dep = 974");
	$db-> query ("UPDATE `caracteristiques` SET reg = 6 WHERE dep = 976");
	$db-> query ("ALTER TABLE `caracteristiques` CHANGE `atm` `atm` smallint(1) NULL");
	$db-> query ("UPDATE `caracteristiques` SET atm = NULL WHERE atm = '0' and atm=''");
	$db-> query ("ALTER TABLE `caracteristiques` CHANGE `atm` `atm` ENUM('1','2','3','4','5','6','7','8','9') NULL");
	$db-> query ("ALTER TABLE `vehicules` CHANGE `obs` `obs` smallint(2) NULL");
	$db-> query ("UPDATE `vehicules` SET obs = NULL WHERE obs = '0' and obs =''");
	$db-> query ("ALTER TABLE `vehicules` CHANGE `obs` `obs` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16') NULL");
	$db-> query ("ALTER TABLE `vehicules` CHANGE `obsm` `obsm` smallint(1) NULL");
	$db-> query ("UPDATE `vehicules` SET obsm = NULL WHERE obsm = '0' and obsm =''");
	$db-> query ("ALTER TABLE `vehicules` CHANGE `obsm` `obsm` enum('1','2','4','5','6','9') NULL");
	$db-> query ("UPDATE `usagers` u JOIN `caracteristiques` c ON num_c = num_u SET age = YEAR(c.date_acc) - u.an_nais");
	$db-> query ("ALTER TABLE `usagers` DROP `an_nais`");
	$db->commit();
	echo "Latitudes et longitudes réparées. <br/> Départements 'réparés' (maintenant tous à 3 chiffres)<br/>";
	echo "Champs 'an', 'mois' et 'jour' concaténées en un seul champ de type DATE<br/>Régions ajoutées<br/>";
	echo "Colonne 'âge' ajoutée à la table 'usagers' et colonne 'an_nais' (pour année de naissance) retirée";
}
catch(PDOException $e)
{
	$db->rollback();
	echo 'Error : '.$e->getMessage().'<br />Aucune opération effectuée<br />';
	echo 'N° : '.$e->getCode();
	exit();
}

try																	// Adding primary and foreign keys to check the data integrity and delete the few occurrences that appears in one table but not the others (22 occurences, 0.001% of data ) 
{
	$db->beginTransaction();
	$db->query("ALTER TABLE `caracteristiques` ADD PRIMARY KEY(`num_c`)");
	$db->query("ALTER TABLE `vehicules` ADD PRIMARY KEY( `num_v`, `num_veh`)");
	$db->query("ALTER TABLE `usagers`  ADD `id_u` INT(7) UNSIGNED NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY  (`id_u`,`num_u`,`num_veh`)");
	$db->query("ALTER TABLE `vehicules` ADD CONSTRAINT vehicules_fkey FOREIGN KEY (`num_v`) references `caracteristiques` (`num_c`) ON DELETE CASCADE ON UPDATE CASCADE");
	$db->query ("DELETE FROM `usagers` WHERE NOT EXISTS (SELECT v.num_v, v.num_veh FROM `vehicules` v WHERE v.num_v = `usagers`.num_u and v.num_veh = `usagers`.num_veh)");	
	$db->query("ALTER TABLE `usagers` ADD CONSTRAINT usagers_fkey FOREIGN KEY (`num_u`,`num_veh`) references `vehicules` (`num_v`,`num_veh`) ON DELETE CASCADE ON UPDATE CASCADE");
	$db->commit();
	echo "Les lignes représentant un véhicule d'accidents apparaissant ";
	echo "dans la table usagers mais pas dans la table vehicules ont aussi été supprimées (au nombre de 22).<br/>";
	echo "Contraintes de clés primaires et étrangères ajoutées<br/>La base de données est opérationnelle et prête à être utilisée dès maintenant.";
}	
catch(PDOException $e)
{
	$db->rollback();
	echo 'Error : '.$e->getMessage().'<br />Aucune opération effectuée<br />';
	echo 'N° : '.$e->getCode();
	exit();
}

// Now creation of the info tables adding information on the primary tables contents (caracteristiques,vehicules,usagers) and two tables which allow us to retrieve faster data we need at every page load
// The information on our data added in the info tables can be found on : https://www.data.gouv.fr/s/resources/base-de-donnees-accidents-corporels-de-la-circulation/20170915-155209/Description_des_bases_de_donnees_ONISR_-Annees_2005_a_2016.pdf 
// More on the data here (if the pdf file is not available anymore) :  https://www.data.gouv.fr/fr/datasets/base-de-donnees-accidents-corporels-de-la-circulation/#_

$db->query("DROP TABLE IF EXISTS `info_carac`");
$db->query("CREATE TABLE IF NOT EXISTS `info_carac` (
  `num_ic` varchar(12) NOT NULL,
  `d_atm` varchar(20) DEFAULT NULL,
  `d_reg` varchar(32) DEFAULT NULL,
  `d_dep` varchar(32) DEFAULT NULL,
  `d_lum` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`num_ic`),
  KEY `info_carac_fkey` (`num_ic`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$db->query("INSERT INTO info_carac (num_ic) SELECT num_c FROM `caracteristiques`");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Normale' WHERE atm = 1");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Pluie légère' WHERE atm = 2");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Pluie forte' WHERE atm = 3");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Neige - Grêle' WHERE atm = 4");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Brouillard - Fumée ' WHERE atm = 5");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Vent fort - Tempête' WHERE atm = 6");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Temps éblouissant' WHERE atm = 7");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Temps couvert' WHERE atm = 8");
$db->query("UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_atm = 'Autre' WHERE atm = 9");
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Guadeloupe" WHERE reg =  1');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Martinique" WHERE reg =  2');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Guyane" WHERE reg =  3');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "La Réunion" WHERE reg =  4');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Mayotte" WHERE reg =  6');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Île-de-France" WHERE reg =  11');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Centre-Val de Loire" WHERE reg =  24');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Bourgogne-Franche-Comté" WHERE reg =  27');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Normandie" WHERE reg =  28');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Hauts-de-France" WHERE reg =  32');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Grand Est" WHERE reg =  44');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Pays de la Loire" WHERE reg =  52');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Bretagne" WHERE reg =  53');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Nouvelle-Aquitaine" WHERE reg =  75');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Occitanie" WHERE reg =  76');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Auvergne-Rhône-Alpes" WHERE reg =  84');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = '."Provence-Alpes-Côte d'Azur".' WHERE reg =  93');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_reg = "Corse" WHERE reg =  94');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Ain" WHERE dep = 10');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Aisne" WHERE dep = 20');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Allier" WHERE dep = 30');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Alpes-de-Haute-Provence" WHERE dep = 40');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Hautes-Alpes" WHERE dep = 50');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Alpes-Maritimes" WHERE dep = 60');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Ardèche" WHERE dep = 70');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Ardennes" WHERE dep = 80');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Ariège" WHERE dep = 90');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Aube" WHERE dep = 100');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Aude" WHERE dep = 110');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Aveyron" WHERE dep = 120');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Bouches-du-Rhône" WHERE dep = 130');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Calvados" WHERE dep = 140');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Cantal" WHERE dep = 150');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Charente" WHERE dep = 160');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Charente-Maritime" WHERE dep = 170');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Cher" WHERE dep = 180');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Corrèze" WHERE dep = 190');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = '."Côte-d'Or".' WHERE dep = 210');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = '."Côtes-d'Armor".' WHERE dep = 220');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Creuse" WHERE dep = 230');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Dordogne" WHERE dep = 240');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Doubs" WHERE dep = 250');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Drôme" WHERE dep = 260');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Eure" WHERE dep = 270');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Eure-et-Loir" WHERE dep = 280');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Finistère" WHERE dep = 290');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Gard" WHERE dep = 300');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Garonne" WHERE dep = 310');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Gers" WHERE dep = 320');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Gironde" WHERE dep = 330');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Hérault" WHERE dep = 340');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Ille-et-Vilaine" WHERE dep = 350');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Indre" WHERE dep = 360');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Indre-et-Loire" WHERE dep = 370');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Isère" WHERE dep = 380');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Jura" WHERE dep = 390');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Landes" WHERE dep = 400');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Loir-et-Cher" WHERE dep = 410');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Loire" WHERE dep = 420');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Loire" WHERE dep = 430');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Loire-Atlantique" WHERE dep = 440');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Loiret" WHERE dep = 450');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Lot" WHERE dep = 460');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Lot-et-Garonne" WHERE dep = 470');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Lozère" WHERE dep = 480');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Maine-et-Loire" WHERE dep = 490');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Manche" WHERE dep = 500');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Marne" WHERE dep = 510');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Marne" WHERE dep = 520');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Mayenne" WHERE dep = 530');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Meurthe-et-Moselle" WHERE dep = 540');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Meuse" WHERE dep = 550');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Morbihan" WHERE dep = 560');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Moselle" WHERE dep = 570');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Nièvre" WHERE dep = 580');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Nord" WHERE dep = 590');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Oise" WHERE dep = 600');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Orne" WHERE dep = 610');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Pas-de-Calais" WHERE dep = 620');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Puy-de-Dôme" WHERE dep = 630');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Pyrénées-Atlantiques" WHERE dep = 640');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Hautes-Pyrénées" WHERE dep = 650');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Pyrénées-Orientales" WHERE dep = 660');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Bas-Rhin" WHERE dep = 670');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haut-Rhin" WHERE dep = 680');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Rhône" WHERE dep = 690');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Saône" WHERE dep = 700');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Saône-et-Loire" WHERE dep = 710');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Sarthe" WHERE dep = 720');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Savoie" WHERE dep = 730');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Savoie" WHERE dep = 740');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Paris" WHERE dep = 750');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Seine-Maritime" WHERE dep = 760');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Seine-et-Marne" WHERE dep = 770');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Yvelines" WHERE dep = 780');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Deux-Sèvres" WHERE dep = 790');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Somme" WHERE dep = 800');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Tarn" WHERE dep = 810');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Tarn-et-Garonne" WHERE dep = 820');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Var" WHERE dep = 830');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Vaucluse" WHERE dep = 840');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Vendée" WHERE dep = 850');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Vienne" WHERE dep = 860');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Vienne" WHERE dep = 870');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Vosges" WHERE dep = 880');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Yonne" WHERE dep = 890');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Territoire de Belfort" WHERE dep = 900');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Essonne" WHERE dep = 910');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Hauts-de-Seine" WHERE dep = 920');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Seine-Saint-Denis" WHERE dep = 930');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Val-de-Marne" WHERE dep = 940');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = '."Val d'Oise".' WHERE dep = 950');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Corse-du-Sud" WHERE dep = 201');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Haute-Corse" WHERE dep = 202');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Guadeloupe" WHERE dep = 971');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Martinique" WHERE dep = 972');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Guyane" WHERE dep = 973');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Réunion" WHERE dep = 974');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_dep = "Mayotte" WHERE dep = 976');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_lum = "Plein jour" WHERE lum = 1');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_lum = "Crépuscule ou aube" WHERE lum = 2');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_lum = "Nuit sans éclairage public" WHERE lum = 3');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_lum = "Nuit avec éclairage public non allumé" WHERE lum = 4');
$db->query('UPDATE `info_carac` JOIN `caracteristiques` ON num_ic = num_c SET d_lum = "Nuit avec éclairage public allumé" WHERE lum = 5');

$db->query('ALTER TABLE `info_carac` ADD CONSTRAINT `info_carac_fkey` FOREIGN KEY (`num_ic`) REFERENCES `caracteristiques` (`num_c`) ON DELETE CASCADE ON UPDATE CASCADE');


$db->query("DROP TABLE IF EXISTS `info_usg`");
$db->query("CREATE TABLE IF NOT EXISTS `info_usg` (
  `id_iu` int(7) UNSIGNED NOT NULL,
  `num_iu` varchar(12) NOT NULL,
  `num_veh` varchar(3) NOT NULL,
  `d_grav` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_iu`,`num_iu`,`num_veh`),
  KEY `info_usg_fkey` (`id_iu`,`num_iu`,`num_veh`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$db->query("INSERT INTO `info_usg` (id_iu,num_iu,num_veh) SELECT id_u,num_u,num_veh FROM `usagers`");
$db->query("UPDATE `info_usg` iu JOIN `usagers`u ON id_iu = id_u and num_iu = num_u and iu.num_veh = u.num_veh SET d_grav = 'Indemne' WHERE grav = 1");
$db->query("UPDATE `info_usg` iu JOIN `usagers`u ON id_iu = id_u and num_iu = num_u and iu.num_veh = u.num_veh SET d_grav = 'Tué' WHERE grav = 2");
$db->query("UPDATE `info_usg` iu JOIN `usagers`u ON id_iu = id_u and num_iu = num_u and iu.num_veh = u.num_veh SET d_grav = 'Blessé hospitalisé' WHERE grav = 3");
$db->query("UPDATE `info_usg` iu JOIN `usagers`u ON id_iu = id_u and num_iu = num_u and iu.num_veh = u.num_veh SET d_grav = 'Blessé léger' WHERE grav = 4");

$db->query("ALTER TABLE `info_usg` ADD CONSTRAINT `info_usg_fkey` FOREIGN KEY (`id_iu`,`num_iu`,`num_veh`) REFERENCES `usagers` (`id_u`, `num_u`, `num_veh`) ON DELETE CASCADE ON UPDATE CASCADE");

$db->query("DROP TABLE IF EXISTS `info_veh`");
$db->query("CREATE TABLE IF NOT EXISTS `info_veh` (
  `num_iv` varchar(12) NOT NULL,
  `num_veh` varchar(3) NOT NULL,
  `d_catv` varchar(64) DEFAULT NULL,
  `d_obs` varchar(70) DEFAULT NULL,
  `d_obsm` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`num_iv`,`num_veh`),
  KEY `info_veh_fkey` (`num_iv`,`num_veh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$db->query("INSERT INTO `info_veh` (num_iv,num_veh) SELECT num_v,num_veh FROM `vehicules`");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Véhicule en stationnement' WHERE obs = 1"); 
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Arbre' WHERE obs = 2");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Glissière métallique' WHERE obs = 3");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Glissière béton' WHERE obs = 4");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Autre glissière' WHERE obs = 5");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Bâtiment - Mur - Pile de pont' WHERE obs = 6");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = "."Support de signalisation verticale ou poste d'appel urgence"." WHERE obs = 7");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Poteau' WHERE obs = 8");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Mobilier urbain' WHERE obs = 9");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Parapet' WHERE obs = 10");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Ilot - Refuge - Borne haute' WHERE obs = 11");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Bordure de trottoir' WHERE obs = 12");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Fossé - Talus - Paroi rocheuse' WHERE obs = 13");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Autre obstacle fixe sur chaussée' WHERE obs = 14");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Autre obstacle fixe sur trottoir ou accotement' WHERE obs = 15");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obs = 'Sortie de chaussée sans obstacle' WHERE obs = 16");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obsm = 'Piéton' WHERE obsm = 1");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obsm = 'Véhicule' WHERE obsm = 2");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obsm = 'Véhicule sur rail' WHERE obsm = 4");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obsm = 'Animal domestique' WHERE obsm = 5");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obsm = 'Animal sauvage' WHERE obsm = 6");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_obsm = 'Autre' WHERE obsm = 9");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_catv = '2 roues' WHERE catv IN (1,2,4,5,6,30,31,32,33,34)");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_catv = '4 roues' WHERE catv IN (7,8,9,10,11,12)");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_catv = 'Poids lourd' WHERE catv IN (13,14,15,16,17)");
$db->query("UPDATE `info_veh` iv JOIN `vehicules` v ON num_iv = num_v AND iv.num_veh = v.num_veh  SET d_catv = 'Autre véhicule' WHERE catv IN (3,18,19,20,21,35,36,37,38,39,40,99)");

$db->query("ALTER TABLE `info_veh`
  ADD CONSTRAINT `info_veh_fkey` FOREIGN KEY (`num_iv`,`num_veh`) REFERENCES `vehicules` (`num_v`, `num_veh`) ON DELETE CASCADE ON UPDATE CASCADE");

  
$db->query("DROP TABLE IF EXISTS `choix_zone`");
$db->query("CREATE TABLE IF NOT EXISTS `choix_zone` (
  `code_dep` varchar(3) NOT NULL,
  `nom_dep` varchar(32) NOT NULL,
  PRIMARY KEY (`code_dep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");


insertChoixZone();

$db->query("DROP TABLE IF EXISTS `taux_regions`");
$db->query("CREATE TABLE IF NOT EXISTS `taux_regions` (
  `num_t` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_reg` varchar(32) NOT NULL,
  `taux` decimal(4,2) NOT NULL,
  PRIMARY KEY (`num_t`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");

insertTauxRegions();

function importFile($type,$name)					// Import data of a file into a table.  						
{
	global $db;
	$counter_import = 0;							// Counter that displays at the end of the import the number of imported values in the table of the selected database
	$fp = fopen ($name, "r");						// Open the file in reading mode before importing it.
	switch ($type)															// Allow the program to select which query is to be prepared depending on the type of the file
	{
		case 1: $result = $db->prepare ("INSERT INTO `caracteristiques` VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");		// Prepare the adapted SQL query beforehand : Insert the data in these different fields into the 'caracteristiques' table
		break;
		case 2: $result = $db->prepare ("INSERT INTO `usagers` VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");						// Prepare the adapted SQL query beforehand : Insert the data in these different fields into the 'usagers' table
		break;
		case 3: $result = $db->prepare ("INSERT INTO `vehicules` VALUES (?,?,?,?,?,?,?,?,?)");							// Prepare the adapted SQL query beforehand  : Insert the data in these different fields into the 'vehicules' table
		break;
	}	
	if ($fp==FALSE)																										// Check if the file exists 
	{
		echo "<p align = 'center' >- Importation echouée -</p>
		<p align = 'center' ><B>Désolé, mais vous n'avez pas specifié de chemin valide ...</B></p>";
		exit();
	}	
	echo "<p align = 'center'>- Fichier valide -</p>";	
	 
	while (!feof($fp) and ($champs = fgetcsv($fp,0,',','"')) != null) 													// Start the importation of the file and proceed line by line until the end of the file 
	{																
		foreach($champs as &$field)																						// Retrieve data of a line and affects it by fields to be inserted into the database table 
		{
				$field = (isset($field)) ? $field : 0;
		}
		unset($field);
		$result->execute($champs) or die(print_r($db-> errorInfo()));													// Execute the prepared query done above which is to insert data into the selected table and display an error if there is any
		$counter_import++;
		 
	}
	$result->closeCursor();																								// End of the query
	fclose($fp);																										// Close the file
	echo "Nombre de lignes insérées : $counter_import <br/>";
}

function insertChoixZone()	//Insert lines in the ‘choix_zone’ table from columns 'dep' (department code) and 'd_dep' (department name).
{							// These columns comes respectively from ‘info_carac’ and ‘caracteristiques’ tables.
	global $db;
													// Select the name of the departments with their corresponding insee code
	$result = $db->query("SELECT DISTINCT dep, d_dep FROM `info_carac` ic JOIN `caracteristiques` c ON num_c = num_ic WHERE d_dep <> '' ORDER BY dep");		
	$departments = array();
	while($data = $result->fetch())
	{																													// Display the departments in a HTML Select list 
		$departments[] = array($data['dep'],$data['d_dep']);
	}
	$result->closeCursor();
	$requete = $db->prepare("INSERT INTO `choix_zone` (code_dep,nom_dep) VALUES (?,?)");
	foreach($departments as $line)
	{
		try
		{
			$requete->execute($line);
		}
		catch(PDOException $e)																							// Display error if the connection fails 
		{
			echo "Erreur à l'exécution de la requête<br/>";
			echo 'Error : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
			exit();
		}
	}
	$requete->closeCursor();
}

function insertTauxRegions()	//Insert lines in the ‘taux_regions’ table using the following columns : 'd_reg' (region name), reg (region code) and date_acc (accident date)
{								//These columns comes respectively from ‘info_carac’ and ‘caracteristiques’ tables.
	global $db;
		// Query that selects the name of the regions and the number of accidents of every single one in 2016 in descending order (highest to lowest number of accidents)  
	$result = $db->query("SELECT COUNT(num_c) as number ,d_reg 
								FROM `caracteristiques` c JOIN `info_carac` 
								ON num_c = num_ic WHERE YEAR(date_acc) = 2016
								GROUP BY reg ORDER BY COUNT(reg) DESC");
	$regions = array();
	$ranking=1;				
	while($data = $result->fetch())
	{
		$regions[] = array($ranking,$data['d_reg'],$data['number']);													// Create a array with a ranking, the name of the regions and the number of accidents during 2016
		$ranking++;			// Create a ranking by incrementing once every line
	}
	$result->closeCursor();
	$result = $db->query("SELECT COUNT(*) AS total FROM `caracteristiques` WHERE YEAR(date_acc) = 2016");		// Count the number of accidents in 2016
	$data = $result->fetch();
	$total = $data['total'];
	$result->closeCursor();
	$requete = $db->prepare("INSERT INTO `taux_regions` (num_t,nom_reg,taux) VALUES (?,?,?)");
	foreach($regions as &$line)
	{
		$ligne[2] = round($line[2]*100/$total,2);					// Replace the number of accidents by its rate, line by line.
		try
		{
			$requete->execute($line);
		}
		catch(PDOException $e)																							// Display error if the connection fails 
		{
			echo "Erreur à l'exécution de la requête<br/>";
			echo 'Error : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
			exit();
		}
	}
	$requete->closeCursor();
}	

?>