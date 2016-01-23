<?php
include "sql.php";

writeSuperChars(sql("SELECT t1.`characterID`,t1.`characterName`,t1.killtime,t1.shipTypeID FROM killsbysuper AS t1 LEFT JOIN superpilots AS t2 ON t2.characterID = t1.characterID WHERE t2.characterID IS NULL GROUP BY t1.characterID"));

function writeSuperChars($charArray)
{
	//print_r($charArray);
$i = 0;
$all = count($charArray);
foreach ($charArray as $char)
//$char = $charArray[0];
{
	$info = getCharInfos($char["characterID"]);	
	$char["corporationID"] =(String) $info["corporationID"];
	$char["corporationName"] = (String)$info["corporationName"];
	$char["allianceID"] = (String)$info["allianceID"];
	$char["allianceName"] = (String)$info["allianceName"];
	echo $char["characterName"]." ";
	$i = $i+1;	
	echo $i."/".$all;
	echo "\n";
	$sql  = "INSERT INTO superpilots (characterID, characterName, corporationID, corporationName, allianceID, allianceName,shipTypeID, lastKill)";
	
	$sql = $sql.' VALUES ("'.$char["characterID"].'", "'.$char["characterName"].'","'.$char["corporationID"].'","'.$char["corporationName"].'","'.$char["allianceID"].'","'.$char["allianceName"].'","'.$char["shipTypeID"].'","'.$char["killtime"].'")';
	//echo $sql;
	sql_write($sql);
	echo "<br>";
	flush();
	}
}

function getCharInfos($id)
{
	$url = "https://api.eveonline.com/eve/CharacterAffiliation.xml.aspx?ids=".$id;
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

$response = curl_exec($ch);
//print_r($response);
curl_close($ch);

$response = simplexml_load_string($response);
return $response->result->rowset->row;
}
?>