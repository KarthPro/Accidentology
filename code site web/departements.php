<?php


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

$result = $db->query("SELECT code_dep, nom_dep FROM `choix_zone`");							// Select all the name of the departments

	echo '<OPTION value = "1000" selected>France';											// Add the 'France' choice in the HTML select tag
	
while($data = $result->fetch())
{
	switch ($data['code_dep'])																			// Display the departments in a HTML Select list 
	{						// We look for the area chosen by the user in the previous post and we select it so it appears next time the page refresh
						// Else we only create the HTML tags with the department/area code and its name
						// We adapt the output tag depending on the department
		case 201:	echo '<OPTION value = "'.$data['code_dep'].'">2A - '.$data['nom_dep'];				
		break;
		case 202: 	echo '<OPTION value = "'.$data['code_dep'].'">2B - '.$data['nom_dep'];
		break;
		case 971:
		case 972:
		case 973:
		case 974:
		case 976: 	echo '<OPTION value = "'.$data['code_dep'].'">'.$data['code_dep'].' - '.$data['nom_dep'];
		break;
		default: 	echo '<OPTION value = "'.$data['code_dep'].'">'. ($data['code_dep']/10) .' - '.$data['nom_dep'];
		break;
	}
}
$result->closeCursor();

?>