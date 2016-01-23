<?php
include "sql.php";
ini_set('memory_limit','5000M');

getSuperStats(getSupersByAlliance(getSuperKills()));

function getSupersByAlliance($pilotArray)
{
	$allianceSupers = array();
	foreach ($pilotArray as $pilot)
	{
		if (array_key_exists($pilot["allianceName"],$allianceSupers))
		{
		$allianceSupers[$pilot["allianceName"]] = $allianceSupers[$pilot["allianceName"]] +1;}
		else
		{
			$allianceSupers[$pilot["allianceName"]] = 1;
		}		
	}
	arsort($allianceSupers);
	return $allianceSupers;
}
function getSupersKillsByAlliance($pilotArray)
{
	$allianceSupers = array();
	foreach ($pilotArray as $pilot)
	{
		if (array_key_exists($pilot["allianceName"],$allianceSupers))
		{
		$allianceSupers[$pilot["allianceName"]] = $allianceSupers[$pilot["allianceName"]] + $pilot["COUNT(*)"];}
		else
		{
			$allianceSupers[$pilot["allianceName"]] = $pilot["COUNT(*)"];
		}		
	}	
	arsort($allianceSupers);
	return $allianceSupers;
}

function getSuperKills()
{
	$conn = connect();
	$sql = "SELECT * FROM killsbysuper";
	$sql .= " GROUP BY characterID";
	//$sql .= "GROUP BY KillID";
	//$sql .= " GROUP BY allianceID";
	//$sql .= " KillID";
	//$sql = "SELECT COUNT(DISTINCT allianceName,KillID), allianceName FROM `killsbysuper` GROUP BY allianceName";
	//$sql = "SELECT * FROM killsbysuper GROUP BY characterID";
	//echo $sql."/n";	
	return sql($sql);
}

function getSuperStats($allianceSupers)
{
		foreach ($allianceSupers as $key => $val)
		{
		echo $key;
		echo ";";
		echo $val;
		echo "<br>";
}	
}

?>