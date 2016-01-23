<?php


include "Crest.php";
$auth_code = $_GET["access_token"];
$name = $_GET["name"];
$id = 92439100;
$name2 = str_replace(" ","+",$name);
$name2 = getID($name2);

//print_r(getAllAllianceMembers((INT) $name2["ownerID"]));
addMembers($auth_code,$id,getAllMembers((INT) $name2["ownerID"],(INT) $name2["ownerGroupID"],$name));
//echo "FINISHED!";
function getAllMembers($corpID,$isCorp,$name)
{
	if ($isCorp == 2)
	{
		return getAllCorpMembers($corpID);
	}
	else
	{
		if ($isCorp == 32)
		{
		return getAllAllianceMembers($corpID);
		}
		else
		{
			if ($isCorp == 1)
			{
				$pilot[0] = new stdClass;
				$pilot[0]->name = $name;
				$pilot[0]->character_id = $corpID;
				return $pilot;
			}
			else
			{
				echo "No Char or Corp or Alliance with this name exists, please check spelling.";
			}
		}
	}
}


function addMembers($auth_code,$id,$pilots)
{	
	$count = count($pilots);
	$i = 1;
	$j = 0;
	foreach ($pilots as $pilot)
	{
	//	echo $i."/".$count." ".$pilot->name."<br>";
		
			if ( $j == 0)
			{
			$mh = curl_multi_init();
			$mhArray = [];			
			}			
			 $mhArray[$j] = curl_multi_post_handle("https://crest-tq.eveonline.com/characters/".$id."/contacts/",$auth_code,(int)$pilot->character_id,$pilot->name,$_GET["standing"],$_GET["watched"],$_GET["blocked"]);
			 curl_multi_add_handle($mh,$mhArray[$j]);			
			if ($j==19)
			{
			$j = 0;
			do {
				curl_multi_exec($mh, $running);
				curl_multi_select($mh);
				} while ($running > 0);
				curl_multi_close($mh);
			}
			else
			{
			$j = $j+1;
			}	
		//print_r( curl_post("https://crest-tq.eveonline.com/characters/".$id."/contacts/",$auth_code,(int)$pilot->character_id,$pilot->name));	
				$i = $i+1;
		flush();
	}
		do {
				curl_multi_exec($mh, $running);
				curl_multi_select($mh);
				} while ($running > 0);
				curl_multi_close($mh);
				echo $count;
}
	

function getAllCorpMembers($corpID)
{
	$pilots = curl("http://evewho.com/api.php?type=corplist&id=".$corpID);
	$count = $pilots->info->memberCount;
	$pages = ceil($count/200);
	$pilotArray = [];
	for ($i  = 0; $i < $pages ; $i++)
	{
		$pilots = curl("http://evewho.com/api.php?type=corplist&id=".$corpID."&page=".$i)->characters;
		$pilotArray = array_merge($pilotArray,$pilots);		
	}
	return $pilotArray;
}
function getAllAllianceMembers($corpID)
{
	$pilots = curl("http://evewho.com/api.php?type=allilist&id=".$corpID);
	$count = $pilots->info->memberCount;
	$pages = ceil($count/200);
	$pilotArray = [];
	for ($i  = 0; $i < $pages ; $i++)
	{
		$pilots = curl("http://evewho.com/api.php?type=allilist&id=".$corpID."&page=".$i)->characters;
		$pilotArray = array_merge($pilotArray,$pilots);		
	}
	return $pilotArray;	
}

function getID($name)
{
	$url = "https://api.eveonline.com/eve/OwnerID.xml.aspx?names=".$name;
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