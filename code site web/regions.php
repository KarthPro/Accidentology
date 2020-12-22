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
																																			// Query that selects the name and the rate of accidents in 2016 of the French regions
$result = $db->query("SELECT num_t,nom_reg,taux FROM `taux_regions`");
$region = array();
while($data = $result->fetch())
{
	echo "<tr><td>$data['num_t']</td><td>$data['nom_reg']</td><td>".$data['taux']."%</td></tr>";	// Display the array in a HTML table.
}
$result->closeCursor();

?>