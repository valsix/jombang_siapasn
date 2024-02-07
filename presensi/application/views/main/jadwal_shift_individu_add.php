<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/KerjaJam');
$this->load->model('siap/SatuanKerja');

$jam_shift= new KerjaJam();
$reqId=  $this->input->get("reqId");
$reqTahun=  $this->input->get("reqTahun");
$reqBulan=  $this->input->get("reqBulan");
$reqPeriode= $reqBulan.$reqTahun;

// $reqTahun = 2021;
// $reqBulan = 01;

$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
// $reqSatuanKerjaId= 66;
if(!empty($reqSatuanKerjaId))
{

    $skerja= new SatuanKerja();
    $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
    // echo $skerja->query;exit();
    unset($skerja);
    // echo $reqSatuanKerjaId;exit;
    $satuankerjakondisi= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}
// echo $satuankerjakondisi;exit;

$arr_jsonShift= array();
$arr_jsonShift[0]['KERJA_JAM_SHIFT_ID']= "";
$arr_jsonShift[0]['NAMA_JAM_KERJA']= "-";
$jam_shift->selectByParamsJamShift(array(), -1, -1, $satuankerjakondisi);
// echo $jam_shift->query;exit;
$arrKolom = array("KERJA_JAM_SHIFT_ID", "NAMA_JAM_KERJA");
$a=1;
while ($jam_shift->nextRow()) 
{
    for($i=0;$i<count($arrKolom);$i++)
    {
        $kolom = $arrKolom[$i];
        $arr_jsonShift[$a][$kolom] = $jam_shift->getField($kolom); 
    }
    $a++;
}
// print_r($arr_jsonShift);exit();

$arr_json = array();
$statement = " AND A.PEGAWAI_ID IN (".$reqId.")";
$set= new KerjaJam();
$set->selectByParamsShiftJamPegawaiView(array(), -1, -1, $reqPeriode, $statement);
$arrColumn = array( "HARI1", "HARI2", "HARI3","HARI4","HARI5","HARI6","HARI7","HARI8","HARI9","HARI10","HARI11","HARI12","HARI13","HARI14","HARI15","HARI16","HARI17","HARI18","HARI19","HARI20","HARI21","HARI22","HARI23","HARI24","HARI25","HARI26","HARI27","HARI28","HARI29","HARI30","HARI31", "PEGAWAI_ID", "NAMA_LENGKAP", "NIP_BARU", "SATUAN_KERJA_INFO");
$a=0;
while ($set->nextRow()) 
{
    for($i=0;$i<count($arrColumn);$i++)
    {
        $kolom = $arrColumn[$i];
        $arr_json[$a][$kolom] = $set->getField($kolom); 
    }
    $a++;
}
// print_r($arr_json);exit();


if ($reqBulan == null || $reqBulan == '')   
  $currMonth= date("m");        
else
  $currMonth = intval($reqBulan);

$currYear = $reqTahun;
$startDate = strtotime($currYear . "-" . $currMonth . "-01 00:00:01");
$startDay= date("N", $startDate);
$monthName = date("M",$startDate );
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, date("m", $startDate), date( "Y", $startDate));
$endDate = strtotime($currYear . "-" . $currMonth . "-" .  $daysInMonth ." 00:00:01");

$endDay = date("N", $endDate);
// echo  $endDay;exit;
if ($startDay> 6)
  $startDay = 7 -$startDay;

$currElem = 0;
$dayCounter = 0;
$firstDayHasCome = false;
$arrCal = array();
for($i = 0; $i <= 5; $i ++) {
  for($j= 0; $j <= 6; $j++) {
    // decide what to show in the cell
    if($currElem < $startDay && !$firstDayHasCome)      
      $arrCal[$i][$j] = "";
    else if ($currElem == $startDay && !$firstDayHasCome) {
      $firstDayHasCome= true;
      $arrCal[$i][$j] = ++$dayCounter;
    }
    else if ($firstDayHasCome) {
      if ($dayCounter < $daysInMonth)
        $arrCal[$i][$j] = ++ $dayCounter; 
      else
        $arrCal[$i][$j] = ""; 
    }             

    $currElem ++;       
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>	
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui-autocomplete/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui-autocomplete/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Jadwal Jam Kerja Shift</span></div>
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <ul class="nav nav-tabs">
                <? 
                for ($i=0; $i < count($arr_json); $i++) 
                { 
                ?>
                    <li <?if ($i==0) {?> class="active" <? } ?> >
                        <a data-toggle="tab" href="#tab-<?=$arr_json[$i]['NIP_BARU']?>">
                            <?=$arr_json[$i]['NAMA_LENGKAP']?>
                        </a>
                    </li>  
                <?    
                }
                ?>
            </ul>

            <div class="tab-content">
                <?
                for ($z=0; $z < count($arr_json); $z++) 
                { 
                    $reqNipBaru= $arr_json[$z]['NIP_BARU'];
                    $reqNamaLengkap= $arr_json[$z]['NAMA_LENGKAP'];
                    $reqUnitKerja= $arr_json[$z]['SATUAN_KERJA_INFO'];
                    $reqPegawaiId= $arr_json[$z]['PEGAWAI_ID'];
                ?>
                    <div id="tab-<?=$reqNipBaru?>" class="tab-pane fade <?if($z==0) {?> in active <?} ?> ">

                        <div class="area-form-inner">
                            <div class="area-form-konten">

                                <div class="area-pegawai-profil-form" style="height: 150px; width: 250px;">
                                    <div class="foto">
                                        <img src="app/loadUrl/simpeg/image_script/?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=pegawai" width="60" height="77">
                                    </div>

                                    <div class="keterangan"> 
                                        <div class="nama"><strong><?=$reqNamaLengkap?></strong></div>

                                        <div class="nrp">NIP Baru: <?=$reqNipBaru?></div>
                                        <div class="jabatan" style="text-align: left;">Unit Kerja: <?=$reqUnitKerja?></div>

                                        <div class="item">Periode: <?=getSelectFormattedDate($reqBulan)." ".$reqTahun?></div>

                                        <!-- <div class="item">Hari Kerja : Belum Dientri</div> -->
                                    </div>
                                </div>

                                <div class="heading2">&nbsp;</div>
                                <div class="container_calendar">
                                    <div>
                                        <div class="day" style="border-top-left-radius:18px;">Minggu</div>
                                        <div class="day">Senin</div>
                                        <div class="day">Selasa</div>
                                        <div class="day">Rabu</div>
                                        <div class="day">Kamis</div>
                                        <div class="day">Jum'at</div>
                                        <div class="day" style="border-top-right-radius:18px;">Sabtu</div>
                                        <div class="clear"></div>
                                    </div>
                                    <?php
                                        $currElem = 0;
                                        $dayCounter = 0;
                                        $firstDayHasCome = false;
                                        $lowerLeftCorner= "style=\"border-bottom-left-radius:18px;\"";
                                        $lowerRightCorner= "style=\"border-bottom-right-radius:18px;\"";
                        
                                        for($i = 0; $i <= 5; $i ++) 
                                        {
                                            echo("<div>\r\n");
                                            for($j= 0; $j <= 6; $j++)
                                            { 
                                                $divId = $currYear . "-";
                                                $divId .= $currMonth . "-";
                                                if(intval($arrCal[$i][$j]) < 10)
                                                    $divId .= "0";
                                                $divId .= $arrCal[$i][$j];
                        
                                                $leftCorner = "";
                                                $rightCorner = "";
                                                if ($i == 5 && $j ==0)
                                                    $leftCorner = $lowerLeftCorner;
                                                if ($i == 5 && $j == 6)
                                                    $leftCorner = $lowerRightCorner;
                                                
                                                $ada = 1;
                                                if($currElem < $startDay && !$firstDayHasCome) 
                                                {
                                                    echo("<div class=\"date\"". $leftCorner .">\r\n");
                                                    echo("</div>\r\n");
                                                }
                                                else if ($firstDayHasCome==false) 
                                                {
                                                    if ($dayCounter < $daysInMonth) 
                                                    {
                                                        $reqTanggal= $arrCal[$i][$j];
                                                        $reqValPilih= $arr_json[$z]['HARI'.$reqTanggal];
                                                        ?>
                                                        <div id="<?=$divId?>" class="date" <?=$leftCorner?>  style="background-color: #FFC;">
                                                        <div class="top">
                                                        <div class="date_topright"><strong><?=$reqTanggal?></strong>
                                                            <?
                                                            $infodatanama= "";
                                                            $infosql= "select case status when -1 then 'AWFH' else 'Libur' end nama from presensi.kerja_jam_awfh_pegawai where pegawai_id = '".$reqId."' and hari = '".generateZero($reqTanggal,2)."' and periode = '".$reqPeriode."'";
                                                            $hasilsql= $this->db->query($infosql)->row();
                                                            if(!empty($hasilsql->nama))
                                                            {
                                                                $infodatanama= $hasilsql->nama;
                                                            }

                                                            if(!empty($infodatanama))
                                                            {
                                                            ?>
                                                            <span style="font-size: 10px; color: red">
                                                                <?=$infodatanama?>
                                                            </span>
                                                            <?
                                                            }
                                                            ?>
                                                            <br>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                    <select id="reqHari<?=$reqTanggal?>-<?=$reqPegawaiId?>">
                                                                        <?
                                                                        for ($stats=0; $stats < count($arr_jsonShift); $stats++) 
                                                                        { 
                                                                            $infoid= $arr_jsonShift[$stats]['KERJA_JAM_SHIFT_ID'];
                                                                            $infonama= $arr_jsonShift[$stats]['NAMA_JAM_KERJA'];
                                                                        ?>
                                                                            <option value="<?=$infoid?>" <? if($arr_json[$z]['HARI'.$reqTanggal] == $infoid) { ?> selected="selected" <? } ?> >
                                                                                <?=$infonama?>
                                                                            </option>
                                                                        <?
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <input type="hidden" value="<?if($reqValPilih!="") { ?><?=$reqTanggal?>;<?=$reqPegawaiId?>;<?=$reqValPilih?><? } ?>" name="reqValHari[]" id="reqValHari<?=$reqTanggal?>-<?=$reqPegawaiId?>" >
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        </div>
                                                        <!-- <div class="bottom">
                                                            <div class="part"></div>
                                                            <div class="part"></div>
                                                            <div class="part"></div>
                                                        </div> -->
                                                        </div>              
                                                        <?
                                                        $dayCounter ++;
                                                    } 
                                                    else 
                                                    {
                                                        echo("<div class=\"date\"". $leftCorner . ">\r\n");
                                                        echo("</div>\r\n");
                                                    }
                                                }
                                                $currElem ++;       
                                            }
                                            echo("</div>\r\n");
                                        } 
                                    ?>         
                                    <br>&nbsp;<br>
                                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
                                    <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>">
                                </div>
                                
                            </div>
                        </div>
                    
                    </div>
                <?  
                }
                ?>
                <div class="area-tombol-bawah" style="width: 805px; margin: 0 auto;" >
                    <input type="button" onclick="document.location.href='app/loadUrl/main/jadwal_shift_individu'" class="btn btn-primary" value="Kembali" />
                    <input type="submit" value="Submit">
                </div>
            </div>
        </form>
	</div>
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
</body>

<script type="text/javascript"> 
$(function(){
    $.fn.window.defaults.closable = false;
    $('#ff').form({
        url:'main/jadwal_json/jamkerjashiftpegawaiadd',
        onSubmit:function(){
            var win = $.messager.progress({title:'Proses simpan data', msg:'Proses simpan data...'});
            return $(this).form('validate');
        },
        success:function(data) {
            $.messager.progress('close');
            // console.log(data);return false;
            data = data.split("-");
            rowid= data[0];
            infodata= data[1];

            if(rowid == "xxx")
            {
                $.messager.alert('Error', infodata, 'error');
            }
            else
            {
                $.messager.alert('Info',infodata,'info',function(){
                    document.location.href = "app/loadUrl/main/jadwal_shift_individu";
                });
            }
        }
    });

    $('select[id^="reqHari"]').change(function(e) {
        var tempId= $(this).attr('id');
        var arrTempId= tempId.split("-");
        var reqPegawaiId= arrTempId[1];
        var lock= tempId.replace("reqHari","");
        var tgl= lock.replace("-"+reqPegawaiId,"");
        var tempVal= $('#'+tempId).val();
        $('#reqValHari'+lock).val(tgl+";"+reqPegawaiId+";"+tempVal);
        // console.log($('#reqValHari'+lock).val());
    });
});

</script>

</html>