<?php


$local = fread(utf8_fopen_read("Local.txt"),100000000);
//print_r($local);
(textToArray($local));

function textToArray($text)
{
	$personsArray = [];
	$textArray = [];
	$textArray = explode("\n",$text);
	//print_r($textArray);
	$i = 0;
		foreach ($textArray as $textElement)
	{
		$textPart[$i] = explode(">",$textElement,2);
		
	
		$textPart[$i][0]  = substr($textPart[$i][0],50,-1);
		if (array_key_exists($textPart[$i][0],$personsArray))
		{
		}
		else
		{
			$personsArray[$textPart[$i][0]] = [];
		}
			if (array_key_exists($textPart[$i][1],$personsArray[$textPart[$i][0]]))
			{
				$personsArray[$textPart[$i][0]][$textPart[$i][1]]++;
			}
			else
			{
				$personsArray[$textPart[$i][0]][$textPart[$i][1]] = 1;
			}			
			$i = $i+1;			
	}
	foreach ($personsArray as $person)
	{
		$diffText = count($person);
		$textAmount = 0;
		foreach ($person as $text)
		{
			$textAmount = $textAmount + $text;
		}
		if (($diffText/$textAmount)*100 < 10)
		{
			echo "Diffrent Messages: ". ($diffText/$textAmount)*100 . "%";
			print_r($person);
		}
		
	
	}
	return $personsArray;
}



function utf8_fopen_read($fileName) { 
    $fc = iconv('windows-1250', 'utf-8//IGNORE', file_get_contents($fileName)); 
    $handle=fopen("php://memory", "rw"); 
    fwrite($handle, $fc); 
    fseek($handle, 0); 
    return $handle; 
} 


?>