<?php
set_time_limit(0);
function sql($sql)
{
	$conn = connect();
	$result = $conn->query($sql);
	$result = $result->fetch_all(MYSQL_ASSOC);
	$conn->close();
	//print_r($result);
	return $result;	
}
function sql_write($sql)
{
	$conn = connect();
	$result = $conn->query($sql);
	$conn->close();
}


function connect()
{
	$conn = new mysqli('localhost', 'root', '', 'superwatch');
	return $conn;
}
?>