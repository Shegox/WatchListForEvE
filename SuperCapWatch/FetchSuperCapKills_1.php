<?php
include "sql.php";
//writeKills(getKillsBySupers(curl("https://zkillboard.com/api/kills/groupID/11567,659/killes/orderDirection/asc/")));
set_time_limit(0);
echo getMaxKillID();
loadKills();

function loadKills()
{	
	$killID = getMaxKillID();
	if ($killID == NULL)
	{
		$killID = 0;
		echo "killID = 0";
	}
	echo $killID;
	$curl_result = "Test";
	while ($curl_result != NULL)
	{
		$url = "https://zkillboard.com/api/kills/groupID/11567,659/killes/orderDirection/asc/no-items/afterKillID/".$killID;
		echo " ".$url."<br>";
		$curl_result = curl($url);
	//	print_r($curl_result);
		writeKills(getKillsBySupers($curl_result));
		$killID = getMaxKillID();		
		flush();
	}
}
function getMaxKillID()
{
$conn = connect();

$sql = "SELECT MAX(KillID) FROM killsbysuper";
$result = $conn->query($sql);
$result = mysqli_fetch_array($result);
$conn->close();
//print_r($result);
return $result[0];	
}
function getKillsBySupers($killArray)
{
	$superkills = [];
//	echo count($killArray);
	foreach ($killArray as $kill)
	{
	//	echo $kill->killID;
	//print_r($kill);
		$attackers = $kill->attackers;		
		foreach ($attackers as $pilot)
		{   
			//only supers/titans
			$id = $pilot->shipTypeID;
			if (
			$id == 11567 || //Avatar
			$id == 671   || //Erebus
			$id == 23773 || //Ragnarok
			$id == 3764  || //Leviathan
			
			$id == 23919 || //Aeon
			$id == 22852 || //Hel
			$id == 23913 || //Nyx
			$id == 23917 || //Wyvern
			$id == 3514 )   //Revenant
			{
			$pilot->KillID = $kill->killID;
			$pilot->killTime = $kill->killTime;
			//print_r($pilot);
			array_push($superkills,$pilot);
			}
		}		
	}
return $superkills; 
}

function writeKills($superkills)
{	
	//	print_r($conn);
	echo "Number of new Kills by Super:".count ($superkills);
	foreach ($superkills as $kill)
	{
		
		$conn = connect();
	$sql  = "INSERT INTO killsbysuper (characterID, characterName, corporationID, corporationName, allianceID, allianceName, damageDone, finalBlow, weaponTypeID, shipTypeID, KillID, killTime)";
	//echo $sql;
	$sql = $sql.'VALUES ("'.$kill->characterID.'", "'.$kill->characterName.'", "'.$kill->corporationID.'", "'.$kill->corporationName.'", "'.$kill->allianceID.'", "'.$kill->allianceName.'", "'.$kill->damageDone.'", "'.$kill->finalBlow.'", "'.$kill->weaponTypeID.'", "'.$kill->shipTypeID.'", "'.$kill->KillID.'", "'.$kill->killTime.'")';
	//echo $sql;
	//echo "<br>";	
	//	$sql = $sql." WHERE NOT EXISTS (SELECT 1 FROM killsbysuper WHERE KillID = ".$kill->KillID." AND characterID = ".$kill->characterID.")";
	//	echo $sql;
				$conn->query($sql);
				$conn->close();
	}
	//$conn->close();
}

function curl($url)
{
 $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Set so curl_exec returns the result instead of outputting it.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Does not verify peer
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Get the response and close the channel.
$headers = Array(
"Reddit: Shegox",
"IGN: Shegox Gabriel"
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_ENCODING, "gzip");
//print_r($ch);
$response = curl_exec($ch);
//print_r($response);
curl_close($ch);
$response = json_decode($response);
return $response;
}

?>