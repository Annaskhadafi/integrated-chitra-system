<?php
	require_once __DIR__ . "/koneksi.php";
	include ("codebase/connector/grid_connector.php");
	include ("codebase/connector/db_mysqli.php");	

	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name2, $db_port);	
	$data = new GridConnector($mysqli,"MySQLi");  
    $data->render_sql("SELECT * from customer","a.id","customer,size,unit_qty,forecast_tire");
?>
