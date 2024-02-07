<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

// $this->load->model('main/PermohonanLambatPc');
$reqId= $this->input->get("reqId");

// $set= new PermohonanLambatPc();
if($reqId == "")
{
	$reqMode = "insert";
	$reqTanggal = date("d-m-Y");
}
else
{
	$reqMode = "update";
	// $set->selectByParams(array("A.PERMOHONAN_LAMBAT_PC_ID" => $reqId));
	// // echo $set->query;exit();
	// $set->firstRow();
	// $reqPegawaiId = $set->getField("PEGAWAI_ID");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>	
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">
<script language="JavaScript">
function closeparenttab()
{
  // console.log("a");
  if (window.parent && window.parent.document)
  {
    if (typeof window.parent.setCariInfo === 'function')
    {
      window.parent.setCariInfo();
    }
  }
  window.close();
}

function reloadparenttab()
{
  // console.log("a");
  if (window.parent && window.parent.document)
  {
    if (typeof window.parent.setCariInfo === 'function')
    {
      window.parent.setCariInfo();
    }
  }
}

function setCariInfo(){
  // console.log('test');
  $("iframe#idMainFrame")[0].contentWindow.setCariInfo();
}

function OptionSetDokumen(id){
	$("iframe#idMainFrameDetil")[0].contentWindow.OptionSetDokumen(id);
}
</script>

</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Kegiatan</span></div>

		<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="100%" bgcolor="#F0F0F0" style="overflow:hidden; height: 90vh">
		   	<tr> 
		    	<td height="100%" valign="top" class="menu" width="1"> 
		      		<table width="242" border="0" cellpadding="0" cellspacing="0" height="100%" id="menuFrame">
		        		<tr> 
				  			<td height="100%"></td>
		         			<td valign="top">
						  	<iframe src="app/loadUrl/main/kegiatan_add_menu?reqId=<?=$reqId?>" name="menuFramePop" width="100%" height="100%" frameborder="0"></iframe>		  
				  			</td>
		        		</tr>
		      		</table>
				</td>
		    	<td valign="top" height="100%" width="100%">
		            <table cellpadding="0" cellspacing="0"  width="100%" height="100%">
		            	<tr height="100%">
		                	<td><iframe src="app/loadUrl/main/kegiatan_add_data?reqId=<?=$reqId?>" class="mainframe" id="idMainFrame" name="mainFramePop" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe></td>
		                </tr>
		            </table>			
				</td>
			</tr>
		</table>
		
	</div>
</body>
</html>