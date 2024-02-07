<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>	
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">
<script type="text/javascript">

	function executeOnClick(varItem){
		$('a').css({'background-position': 'top'});

		if(varItem == 'kegiatan'){
			$('#kegiatan').css({'background-position': '0 -27px'});
			parent.mainFramePop.location.href='kegiatan_add_data.php?reqId=<?=$reqId?>&reqCabangId=<?=$reqCabangId?>';
			parent.document.getElementById('trdetil').style.display = 'none';	
		}
		else if(varItem == 'kegiatan_peserta'){
			$('#kegiatan_peserta').css({'background-position': '0 -27px'});
			parent.mainFramePop.location.href='kegiatan_peserta.php?reqId=<?=$reqId?>&reqCabangId=<?=$reqCabangId?>';
			parent.document.getElementById('trdetil').style.display = 'none';	
		}
		else if(varItem == 'kegiatan_peserta_pembagian'){
			$('#kegiatan_peserta_pembagian').css({'background-position': '0 -27px'});
			parent.mainFramePop.location.href='kegiatan_peserta_pembagian_add.php?reqId=<?=$reqId?>&reqCabangId=<?=$reqCabangId?>';
			parent.document.getElementById('trdetil').style.display = 'none';	
		}
		return true;
	}

	function setCariInfo(){
		$("iframe#idMainFrame")[0].contentWindow.setCariInfo();
	}

			
</script> 

</head>

<body>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tr>
			<td>
	            <div style="clear:both"></div>
	            <div id="menu-kiri">

	            	<div id="menu-kiri-title">Data</div>
	                <a href="#" id="kegiatan" class="menu-bg" onclick="executeOnClick('kegiatan');" style="background-position:0 -27px">
	                <div class="menu-kiri-text">
	                	<img src="images/icn-data.png" height="24" class="top" />Data Kegiatan
	                </div>
	                </a>

	                <?
	                if($reqId == ""){}
	                else
	                {
	                ?>
	                <a href="#" id="kegiatan_peserta" class="menu-bg" onclick="executeOnClick('kegiatan_peserta');" >
	                <div class="menu-kiri-text">
	                	<img src="../WEB-INF/images/icn-menu-datapeg.png" height="24" class="top" />Customer
	                </div>
	                </a>
	                <a href="#" id="kegiatan_peserta_pembagian" class="menu-bg" onclick="executeOnClick('kegiatan_peserta_pembagian');" >
	                <div class="menu-kiri-text">
	                	<img src="../WEB-INF/images/icn-menu-datapeg.png" height="24" class="top" />Pembagian Peserta
	                </div>
	                </a>
	                <?
	            	}
	                ?>
	            </div>
			</td>
		</tr>
	</table>
</body>
</html>