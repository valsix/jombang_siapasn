<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

function checkwarna($value, $id, $arrdata=[], $arrdetil=[], $tempvalidasihapusid= "")
{
	$str = $value;
	$obj = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str), true );
	// print_r($obj);exit;
	// print_r($arrdata);exit;
	$lewati= "";
	if($obj[strtoupper($id)][1] == strtoupper($id))
	{
		// print_r($arrdata);exit;
		// print_r($obj[$id][0]);exit;
		if(!empty($arrdata))
		{
			$infodata= $obj[$id][0];

			if($arrdata[0] == "date")
			{
				$infodata= dateToPageCheck($infodata);
				$infowarna= "new-bg-danger text-white";
			}
			elseif($arrdata[0] == "numberformat")
			{
				$infodata= numberToIna($infodata);
				$infowarna= "new-bg-danger text-white";
			}
			else
			{
				if($obj[$id][0] == $arrdata[0])
				{
					$lewati= "1";
					$infodata= $infowarna= "";
				}
				else
				{
					$infodetil= in_array_column($infodata, $arrdetil[0], $arrdata);
					$infodata= $arrdata[$infodetil[0]][$arrdetil[1]];
					// $infowarna= "wrap-ds-danger";
					$infowarna= "new-bg-danger text-white";
				}
			}
		}
		else
		{
			$infodata= $obj[$id][0];
			// $infowarna= "bg-danger text-white";
			$infowarna= "new-bg-danger text-white";
		}

		if(empty($infodata) && empty($lewati))
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

function kondisikategori($tipekondisikategori=[], $tipe="")
{
	if($tipe == "1")
	{
		$arrField= array(4,5,6);
	}
	else
	{
		$arrField= array(1,2,3);
	}
	return $arrField;
}
?>