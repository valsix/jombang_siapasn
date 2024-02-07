<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

function checkwarna($value, $id, $arrdata="", $arrdetil="", $tempvalidasihapusid= "")
{
	$str = $value;
	$obj = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str), true );
	// print_r($obj);exit;
	// print_r($arrdata);exit;
	if($obj[strtoupper($id)][1] == strtoupper($id))
	{
		if(!empty($arrdata))
		{
			$infodata= $obj[$id][0];

			if($arrdata == "date")
			{
				$infodata= dateToPageCheck($infodata);
				$infowarna= "new-bg-danger text-white";
			}
			elseif($arrdata == "numberformat")
			{
				$infodata= numberToIna($infodata);
				$infowarna= "new-bg-danger text-white";
			}
			else
			{
				$infodetil= in_array_column($infodata, $arrdetil[0], $arrdata);
				$infodata= $arrdata[$infodetil[0]][$arrdetil[1]];
				// $infowarna= "wrap-ds-danger";
				$infowarna= "new-bg-danger text-white";
			}
		}
		else
		{
			$infodata= $obj[$id][0];
			$infowarna= "new-bg-danger text-white";
		}

		if(empty($infodata))
		{
			if($infodata == "0")
				$infodata= "'0";
			else
				$infodata= "Data kosong";
		}
	}
	elseif(!empty($tempvalidasihapusid))
	{
		$infowarna= "new-bg-danger text-white";
	}
	else
	{
		$infodata= $infowarna= "";
	}
	
	return array("data"=>$infodata, "warna"=>$infowarna);
}
?>