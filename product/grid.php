<?php
	include ("codebase/connector/grid_connector.php");
	include ("codebase/connector/db_mysqli.php");	
	$db_name = "chitrapa_fleetlist";			// database name
    $db_host='localhost';
    $db_user='chitrapa_root';
    $db_pass='?Zp!ONm2egYE';				// database password 
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);	
	$data = new GridConnector($mysqli,"MySQLi");  
    $data->render_sql("SELECT * from customer","a.id","customer,size,unit_qty,forecast_tire");
?>