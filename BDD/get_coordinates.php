<?php
/* 
	Program that get coordinates from the corresponding addresses selected in the database and update the same database with those.
	Source : http://php.net/manual/fr/  ,  https://openclassrooms.com/courses/les-transactions-avec-mysql-et-pdo ,   https://www.codeofaninja.com/2014/06/google-maps-geocoding-example-php.html, http://www.infowebmaster.fr/tutoriel/vitesse-code-php
	PHP 7.1.9 - Configuration (php.ini) : memory_limit = 1G, max_execution_time = 1800 (in seconds => 30 min, 10 min allows us to process about 1000 queries (on our server and on our computers).
	Will depend on your computer configuration and your network connection speed)
*/
$tps_depart = microtime(true);																	//Optional : give the current time in seconds, see function chrono() for its usefulness (around line 268)
define('MYHOST', 'tap_your_host_here');
define('USERNAME','tap_your_username_here');
define('DBNAME','tap_your_dbname_here');
define('PASSWORD','tap_your_password_here');
try
{
	$db = new PDO("mysql:host=".MYHOST.";dbname=".DBNAME, USERNAME, PASSWORD);					// Connection to the selected database, change it to the database you're using, parameters : host (+ database), username, password 
}
catch(Exception $e)
{
	echo 'Échec de la connexion à la base de données<br/>';
	echo 'Error : '.$e->getMessage().'<br />';
	echo 'N° : '.$e->getCode();
	exit();
}


//Program starts here (after connecting to the database on line 10)

$result = $db->query ("SELECT num_c,dep,com,adr FROM caracteristiques WHERE lat = 0 and longi = 0 and adr <> '' ") or die(print_r($db-> errorInfo()));			// Query that selects the accidents with an address but without coordinates
$retrieve = array('etat' => "NONE");																// array that manage 
$counter = $counter_OK = 0;																			// Counters : $counter : count every line (one line -> one address) of the query response 
$num_key = 0;																						//	$counter_OK : count every address that successfully returned the corresponding coordinates  
$keys = array( "AIzaSyA2-YjaiNAzmmRNtqbG4hJjPEFmALRvCSo",											// $num_key : The current Google key used 
				"AIzaSyByU1EjeXidzB7Pk9TQXqXGzfMBKWzpklo",											// $keys : Array of Google keys that allows to make geocoding requests to Google (ask for coordinates)
				"AIzaSyC47WDo4vLkQbYqfhn0m0PMA95WUGRu3gY",
				"AIzaSyBEhahbgRzf3I_AY0ZgGeTf4TM8H9h5unE",
				"AIzaSyDrMnIMZyQI5dP6t_fyZBIzT6aa2M2CMdA",
				"AIzaSyC4kQk_1WIq1-3kkjJHLDNjnDXQ48i2lEU",
				"AIzaSyCBPDDr3RDr4jgaEo5GJ5EgKhWRjxKt0BY",
				"AIzaSyDwSdv7TLToUlJ3b5ARLUJIXccO7gKrg8g",
				"AIzaSyBc3ttaZZe9G-NFZ3tqYv44bp48GcbFLlI",
				"AIzaSyB2Cz9ZnIfNomToqDjDc8ibw03eyufGAEA",
				"AIzaSyCW8JDpJL3KIXgOsa62dtw8SjH21BXMvFM",
				"AIzaSyDQ7KPd6ep_22rqMOuwEJ3dcXUFm3ifWeE",
				"AIzaSyAXsqPLHzSLxV9p4vco1iOEn40ZwZQjGSg",
				"AIzaSyBbeU8YO6KG4tKGwsa3ONOS0GHXi9Onckk",
				"AIzaSyBGv8grtcJ1H6J1NrVDJTeySidXTPA2L2g",
				"AIzaSyD0Y2yW1OS-MM9mjZzcjaQP39I_zdGaSrk",
				"AIzaSyDfMunrmaj8TgSb_r8Fy9AsU2e-J5B_jq8",
				"AIzaSyCsPqF1zm60a7dBSnEkNBj8xCIK5q_TF7Q",
				"AIzaSyCUOWhaCmx4e4g1bnRUwGwO1JedXWTsno8",
				"AIzaSyCkIsqlZ_8U6huQ2z5YBY4o93uRNDhv1aM",
				"AIzaSyAOb8HqKD_Lpd3JmAWY9mV7uotqOsjvqXY",
				"AIzaSyDo-vp4rFCo_2RUR3Eg0EqLz2OiJ0t1ZF4",
				"AIzaSyCt3pMyf41L986qxF3u67mGwsqmWTbKPmI",
				"AIzaSyBaJz2_A0-neuRcTO8kr8UlvymqoEq6aPI",
				"AIzaSyAgbvWlBcjYgogKKdf5Mp18s3lluT_3s6I",
				"AIzaSyCEw4m5I1Rkmu6lYA1orZ2dR8hJ4edOHi8",
				"AIzaSyBIyCJJNLfIHKBUatdG106p0YZZQnPVJJU",
				"AIzaSyA9KG48Jo-pD5Zd8cSJfH5VTTfqf1G2kzI",
				"AIzaSyALXilqm0lV1_I-oflr-HegG-nGY_RR58Q",
				"AIzaSyAgwCIC7q_rUrpqBaCcWS1hd81obmrRSg0",
				"AIzaSyDKnBzaYfV0HWsnlS9DI66u8DgYLm0QD2c",
				"AIzaSyBVzMeMMbCKRWCp5CxVqb8C3NU8kvrOaPU",
				"AIzaSyDSSbAbLtWMNGKmy1dNfQ9VxzhX02ciR8o",
				"AIzaSyApKNHSr5EpR0pWi3RYxOMS9pqh0xiNtQw",
				"AIzaSyBUAtqs7IhjIVixny_iu52fR3_I3pakYpg",
				"AIzaSyDMY3VJx2yGtSVT24b0toaYlrRWPV32yQY",
				"AIzaSyDR2i6MA0wdkbQQ4QdA5WZvmjnIszWxz0I",
				"AIzaSyBw3sm9W9KN0GOqy1P7yxZKKVEdlacaYsU",
				"AIzaSyB6qAHk9J2o4Kbbc7KaTcOas8wqf8hAzUc",
				"AIzaSyDhv9_tj64skiAX8yFmBx-SsTFy8rDYc3I",
				"AIzaSyCdV8ILGRgZ_b-AXgP45IQ_gc98rWgPavc",
				"AIzaSyBoAjYT9I5ibAtWNsyVGZx6Rkk-J8-cn2c",
				"AIzaSyBIppQnsUG2pm-qcYaAQdG-7m0Czqb5X_U",
				"AIzaSyAVZIsoHmIIiP9BRgiKOzHkpVKG9jSDj_k",
				"AIzaSyBCyb1ISpzEn3xuqLycbfMVMGbkOnsXJaQ"
				);
$array_coord = array();																		// Array that will store the coordinates the getGoogleCoordinates function returns but also the corresponding 
																							//accident number ('num_c') and department ('dep')
while (($data = $result -> fetch()) AND $retrieve['etat'] != 'NO_MORE_KEYS')							// Thanks to the Google Geocoding API, find the coordinates of the given address, proceeding row by row  																			
{
	$retrieve = getGoogleCoordinates(1,0,$data['adr'],$data['dep'],$data['com']);  
	$array_coord[$counter] = array('lat' => $retrieve['lat'],'longi' => $retrieve['longi'],'num_c' => $data['num_c']);		// Add the coordinates in an array along with the 'num_c' value of the line (from the database)
	echo $array_coord[$counter]['num_c']."<br />";											//Optional : Helps knowing the progress of the program 
	$counter++;
}	
$result -> closeCursor();																									// Indicate we are done with the previous query 

// Second part : We update the database coordinates column with the new acquired ones
$requete = $db-> prepare("UPDATE `caracteristiques` SET lat = ?, longi = ? WHERE num_c = ?");								// Prepare the query that will update the coordinates of the selected rows from the previous query (see line 16) 																																			// Counts the number of queries that succeeded
foreach($array_coord as &$line)																								// Execute the previous query (line 171), proceeding row by row, with a last check of the coordinates beforehand
{		
	try
	{
		$requete-> execute([$line['lat'],$line['longi'],$line['num_c']]);												// Execute the query and replace the coordinates in the database by the ones found by Google API																
	}
	catch(Exception $e)
	{
		echo 'Erreur SQL<br/>';
		echo 'Error : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		exit();
	}
}
unset($line);
$requete -> closeCursor();																									// End of the query

// OPTIONAL OUTPUT
echo "Nombre d'adresses traitées : ". $counter. "<br/>"."Nombre d'adresses ayant abouties : ".$counter_OK."<br/>";				// Show the total number of processed addresses and the number that succeeded
$rate = $counter_OK * 100 / $counter;																							// Count the rate of success (in %)
echo "Pourcentage de requêtes ayant abouties : ". $rate."%<br/>";																	// Show the rate of success
chrono();

// FUNCTIONS

function getGoogleCoordinates($visit, $insee_code, $p_address, $department, $town)				// Function that take data of localisation of an accident in parameters and find thanks to Google API the corresponding coordinates 
{																								// Return an array with the new coordinates if the address has been found but also an variable that stores the request status.
	global $keys;
	global $num_key;
	global $counter_OK;
	if($visit == 1)
	{
		if ($department < 100)
			$insee_code = '0'.(string)($department * 100 + $town);
		else if ($department==201) 													// Adapt the address sent to Google API if it comes from Corse 
			$insee_code = '2A'.(string)$town;
		else if ($department==202) 
			$insee_code = '2B'.(string)$town;
		else
		{
			$insee_code = $department * 100 + $town;
		}
		$address = $p_address ." ". (string)$insee_code;						// Create an address from the original address along with the French department and the municipality insee code   
	}	
	else if ($visit == 2)
	{
		$address = (string)$insee_code;
	}
    $address = urlencode($address);																			// Encode the address ( make it adapted to the url type)
    do
	{
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&region=fr&key={$keys[$num_key]}";				// URL created using the selected address, the region wanted (France here) and a Google key 																					
		$response = json_decode(file_get_contents($url), true);																	// Get the JSON response and decode it
		if($response['status']=='OVER_QUERY_LIMIT')
		{
			echo "La clé utilisée à dépassé son quota de 2500 requêtes par jour, Changement de clé<br/>";			// Check if the quota limit of the Google key is exceeded
			if ($num_key < count($keys)-1)
			{
				$num_key++;
				echo "Changement de clé effectué !";
			}
				
			else
			{
				$response['status'] = 'NO_MORE_KEYS';
				echo 'Plus aucune clé disponible !';
			}
		}			
	}while($response['status']=='OVER_QUERY_LIMIT');
	if($response['status']=='OK')
	{																																							
        $coords = array(
						'lat' => $response['results'][0]['geometry']['location']['lat'], 												// Get the coordinates into the JSON file if the search worked
						'longi' => $response['results'][0]['geometry']['location']['lng'],
						'etat' => $response['status']
						);								
		
		switch($department)		// Check the validity of the coordinates and make a new coordinates search if the coordinates are incorrect (but only by municipality insee code this time
		{						// and only when they don't match the localization of the department (or area : check default case of the switch) their address is coming from)
								// If after making 2 searches the coordinates are still incorrect, we abort the search and affect 0 to both coordinates	
								//(which means we won't change anything in the database for the current accident coordinates)
			case 201:																																	// Corse : 201 is equivalent to 2A : Haute-Corse
			case 202:	if (($coords['lat'] > 44 OR $coords['lat'] < 41 OR $coords['longi'] > 10  OR $coords['longi'] < 8) AND $visit == 1)				// Corse : 202 is equivalent to 2B : Corse-du-Sud
						{
							$coords = getGoogleCoordinates(2, $insee_code, $p_address, $department, $town);
						}
						else if (($coords['lat'] > 44 OR $coords['lat'] < 41 OR $coords['longi'] > 10  OR $coords['longi'] < 8) AND $visit == 2)		// If after making 2 searches the coordinates are still incorrect,						
						{																																// we abort the search and affect 0 to both coordinates
							$coords['lat'] = $coords['longi'] = 0;																						//  (which means we won't anything in the database for the current accident coordinates)
						}
						else
						{
							$counter_OK++;
						}
							
			break;																																			// DOM-TOM (971,972,973,974,976)
			case 971:	if (($coords['lat'] > 17 OR $coords['lat'] < 15 OR $coords['longi'] > -60  OR $coords['longi'] < -62) AND $visit == 1)			
						{
							$coords = getGoogleCoordinates(2, $insee_code,$p_address, $department, $town);
						}
						else if (($coords['lat'] > 17 OR $coords['lat'] < 15 OR $coords['longi'] > -60  OR $coords['longi'] < -62) AND $visit == 2)
						{
							$coords['lat'] = $coords['longi'] = 0;
						}
						else
						{
							$counter_OK++;
						}
			break;			
			case 972:	if (($coords['lat'] > 15 OR $coords['lat'] < 14 OR $coords['longi'] > -60  OR $coords['longi'] < -62) AND $visit == 1)
						{
							$coords = getGoogleCoordinates(2, $insee_code,$p_address, $department, $town);
						}
						else if (($coords['lat'] > 15 OR $coords['lat'] < 14 OR $coords['longi'] > -60  OR $coords['longi'] < -62) AND $visit == 2)
						{
							$coords['lat'] = $coords['longi'] = 0;
						}
						else
						{
							$counter_OK++;
						}
			break;
			case 973:   if (($coords['lat'] > 6 OR $coords['lat'] < 2 OR $coords['longi'] > -50  OR $coords['longi'] < -55) AND $visit == 1)
						{
							$coords = getGoogleCoordinates(2, $insee_code,$p_address, $department, $town);
						}
						else if (($coords['lat'] > 6 OR $coords['lat'] < 2 OR $coords['longi'] > -50  OR $coords['longi'] < -55) AND $visit == 2)
						{
							$coords['lat'] = $coords['longi'] = 0;
						}
						else
						{
							$counter_OK++;
						}
			break;			
			case 974:	if (($coords['lat'] > -20 OR $coords['lat'] < -22 OR $coords['longi'] > 56 OR $coords['longi'] < 55) AND $visit == 1)
						{
							$coords = getGoogleCoordinates(2, $insee_code,$p_address, $department, $town);
						}
						else if (($coords['lat'] > -20 OR $coords['lat'] < -22 OR $coords['longi'] > 56 OR $coords['longi'] < 55) AND $visit == 2)
						{
							$coords['lat'] = $coords['longi'] = 0;
						}
						else
						{
							$counter_OK++;
						}
			break;
			case 976: 	if (($coords['lat'] > -12 OR $coords['lat'] < -14 OR $coords['longi'] > 46  OR $coords['longi'] < 45) AND $visit == 1)
						{
							$coords = getGoogleCoordinates(2, $insee_code,$p_address, $department, $town);
						}
						else if (($coords['lat'] > -12 OR $coords['lat'] < -14 OR $coords['longi'] > 46  OR $coords['longi'] < 45) AND $visit == 2)
						{
							$coords['lat'] = $coords['longi'] = 0;
						}
						else
						{
							$counter_OK++;
						}
			break;
			default:	if (($coords['lat'] > 52 OR $coords['lat'] < 42 OR $coords['longi'] > 9  OR $coords['longi'] < -5) AND $visit == 1)			// By default, we use the Metropolitan France coordinates limit
						{																												// and don't make a case for every single department in France (101 cases would be a lot)
							$coords = getGoogleCoordinates(2, $insee_code,$p_address, $department, $town);
						}
						else if (($coords['lat'] > 52 OR $coords['lat'] < 42 OR $coords['longi'] > 9  OR $coords['longi'] < -5) AND $visit == 2)
						{
							$coords['lat'] = $coords['longi'] = 0;
						}
						else
						{
							$counter_OK++;
						}
			break;			
		}
	}
	else if ($visit == 1 AND $response['status'] != 'NO_MORE_KEYS')				// A second search is made if the Google API couldn't find coordinates for the given address 
		$coords = getGoogleCoordinates(2, $insee_code, $p_address, $department, $town);
	else
	{
		$coords = array ('lat' => 0, 																
						'longi' => 0,
						'etat' => $response['status']
						);
		echo "Adresse pas trouvée !";				
	}		
	return $coords;												//Return the array of coordinates and the response status : whether the address has been found or not				
}	

function chrono()											//Optional : Give the execution time of the program (useful because it's usually a very long running time for this program)
{
	global $tps_depart;
	$tps_fin = microtime(true);
	$minutes = (int)((($tps_fin - $tps_depart)%3600)/60);
	$heures = (int)(($tps_fin - $tps_depart)/3600);
	echo "Temps d'exécution du script : " . $heures . ' heures '. $minutes . ' minutes.';
}

?>