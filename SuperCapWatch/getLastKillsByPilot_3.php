<?php
include "sql.php";

getPilots();

function getPilots()
{
	$pilots = sql("SELECT t1.`characterID` FROM superpilots AS t1 LEFT JOIN issuper AS t2 ON t2.characterID = t1.characterID WHERE t2.characterID IS NULL GROUP BY t1.characterID");	
	$count = count($pilots);
	
	$i = 0;
	foreach ($pilots as $pilot)
	{
		echo $i."/".$count." ";
		$i = $i+1;
		writePurSuperPilots(getKillsByPilot($pilot["characterID"]),$pilot["characterID"]);		
	}	
}
function writePurSuperPilots($isSuper,$id)
{
	$sql = "INSERT INTO issuper (characterID, isSuper) VALUES (".$id.", ".$isSuper.")";
	sql_write($sql);
}

function getKillsByPilot($id)
{
	$isSuper = 0;
	$url = "https://zkillboard.com/api/characterID/".$id."/orderDirection/desc/no-items/limit/1";
	$kills = curl($url);	
	
	if ($kills[0]->victim->characterID != $id)
	{	
		foreach ($kills[0]->attackers  as $attacker)
		{			
			if ($attacker->characterID == $id)
			{
			//only supers/titans
			$id = $attacker->shipTypeID;
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
				
				$isSuper = 1;
				echo "Superpilot!: ".$attacker->characterName;
				
			}
				else
				{
					echo "No Superpilot: ".$attacker->characterName;
					
				}
			break;			
			}
		}
	}
	else
	{
		echo "No Superpilot: ".$kills[0]->victim->characterName;
	}
	echo "<br>";
	flush();
	return $isSuper;
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