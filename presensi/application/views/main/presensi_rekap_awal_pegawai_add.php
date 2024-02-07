<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('main/PresensiRekap');

$CI =& get_instance();
$CI->checkUserLogin();

$reqId=  $this->input->get("reqId");
$reqTahun=  $this->input->get("reqTahun");
$reqBulan=  $this->input->get("reqBulan");

if($reqTahun == "") {
    $reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
    $reqBulan = strftime ( '%m' , $reqLastMonth );
    // $reqBulan= "11";
    $reqTahun = strftime ( '%Y' , $reqLastMonth );
}

// $reqTahun = 2019; $reqBulan = 12;
$reqPeriode= $reqBulan.$reqTahun;
// echo $reqPeriode;exit;

$set= new PresensiRekap();
$arrInfoLog= [];
$set->selectpermohonanlog($reqPeriode, $reqId);
// echo $set->query;exit;
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["KEY"]= $set->getField("PEGAWAI_ID")."-".$set->getField("INFOHARI");
    $arrdata["JAM"]= $set->getField("JAM");
    array_push($arrInfoLog, $arrdata);
}
unset($set);
// print_r($arrInfoLog);exit;

$arrdata= [];
$statement = " AND P.PEGAWAI_ID IN (".$reqId.")";
$set= new PresensiRekap();
$set->selectByParamsRekapAwalPegawaiDetil(array(), -1, -1, $reqPeriode, $statement);
// echo $set->query;exit;
$infofield= array( "JAM_MASUK_", "MASUK_", "JAM_PULANG_", "PULANG_", "EX_JAM_MASUK_", "EX_MASUK_", "LEMBUR_JAM_MASUK_", "LEMBUR_JAM_PULANG_", "ONCALL_JAM_MASUK_", "ONCALL_JAM_PULANG_", "INFO_LOG_");

$index=0;
while ($set->nextRow()) 
{
    $vpegawaiid= $set->getField("PEGAWAI_ID");
    $arrdata[$index]["PEGAWAI_ID"]= $vpegawaiid;
    $arrdata[$index]["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrdata[$index]["NAMA_LENGKAP"]= $set->getField("NAMA_LENGKAP");

    for($i=0; $i<count($infofield); $i++)
    {
        for($n=1; $n <= 31; $n++)
        {
            $fieldkolom= $infofield[$i].$n;
            // echo $fieldkolom;exit;

            if($infofield[$i] == "INFO_LOG_")
            {
                $vinfolog= "";
                $infocarikey= $vpegawaiid."-".generateZero($n,2).$reqPeriode;
                // echo $infocarikey;exit;
                $logcheck= in_array_column($infocarikey, "KEY", $arrInfoLog);
                // print_r($logcheck);exit;

                if(!empty($logcheck))
                {
                    foreach ($logcheck as $vlog)
                    {
                        $vinfolog= getconcatseparator($vinfolog, $arrInfoLog[$vlog]["JAM"]);
                    }
                }
                $arrdata[$index][$fieldkolom]= $vinfolog;
            }
            else
            {
                $arrdata[$index][$fieldkolom]= $set->getField($fieldkolom);
            }
        }
    }
    $index++;
}
// print_r($arrdata);exit();

$jumlah_hari =  cal_days_in_month(CAL_GREGORIAN,(int)$reqBulan,$reqTahun);
//echo $jumlah_hari;exit;

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
        <div class="judul-monitoring"><span>Rekap Awal Detail PNS</span></div>

        <div id="info-tambahan" class="parameter-tabel">
            <label>Bulan</label>
            <select name="reqBulan" id="reqBulan">
                <?
                for($i=1; $i<=12; $i++)
                {
                    $tempNama=getNameMonth($i);
                    $temp=generateZeroDate($i,2);
                    ?>
                    <option value="<?=$temp?>" <? if($temp == $reqBulan) echo 'selected'?>><?=$tempNama?></option>
                    <?
                }
                ?>
            </select>
            <label>Tahun</label>
            <select name="reqTahun" id="reqTahun">
                <? 
                for($i=date("Y")-2; $i < date("Y")+2; $i++)
                {
                    ?>
                    <option value="<?=$i?>" <? if($i == $reqTahun) echo 'selected'?>><?=$i?></option>
                    <?
                }
                ?>
            </select>
        </div>

        <ul class="nav nav-tabs">
            <? 
            for ($i=0; $i < count($arrdata); $i++) 
            { 
            ?>
                <li <?if ($i==0) {?> class="active" <? } ?> >
                    <a data-toggle="tab" href="#tab-<?=$arrdata[$i]['NIP_BARU']?>">
                        <?=$i+1?>
                    </a>
                </li>  
            <?    
            }
            ?>
        </ul>

        <div class="tab-content">
            <?
            for ($z=0; $z < count($arrdata); $z++) 
            { 
                $reqNipBaru= $arrdata[$z]['NIP_BARU'];
                $reqNamaLengkap= $arrdata[$z]['NAMA_LENGKAP'];
                $reqUnitKerja= $arrdata[$z]['SATUAN_KERJA_INFO'];
                $reqPegawaiId= $arrdata[$z]['PEGAWAI_ID'];
            ?>
                <div id="tab-<?=$reqNipBaru?>" class="tab-pane fade <?if($z==0) {?> in active <?} ?> ">

                    <div class="area-form-inner">
                        <div class="area-form-konten">
                            <table class="infotable">
                                <thead>
                                <tr>
                                    <td><?=$reqNamaLengkap?> / <?=$reqNipBaru?></td>
                                    <td style="text-align: right;"><input type="button" id="btncetak<?=$reqPegawaiId?>" class="btn btn-primary" value="Export" /></td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="review-presensi-wrapper">
                        <table class="review-presensi">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center; width: 4%">Tgl</th>
                                    <th rowspan="2" style="text-align:center; width: 8%">Hari</th>
                                    <th colspan="2" style="text-align:center; width: 14%">Masuk</th>
                                    <th colspan="2" style="text-align:center; width: 14%">Pulang</th>
                                    <th colspan="2" style="text-align:center; width: 14%">A/S/K</th>
                                    <th colspan="2" style="text-align:center; width: 14%">Lembur</th>
                                    <th colspan="2" style="text-align:center; width: 14%">On Call</th>
                                    <th rowspan="2" style="text-align:center">Log</th>
                                </tr>
                                <tr>
                                    <th style="text-align:center">Log</th>
                                    <th style="text-align:center">Status</th>
                                    <th style="text-align:center">Log</th>
                                    <th style="text-align:center">Status</th>
                                    <th style="text-align:center">Log</th>
                                    <th style="text-align:center">Status</th>
                                    <th style="text-align:center">Masuk</th>
                                    <th style="text-align:center">Pulang</th>
                                    <th style="text-align:center">Masuk</th>
                                    <th style="text-align:center">Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for($i=1; $i<=$jumlah_hari; $i++)
                            {
                                $date = generateZero($i,2).'-'.$reqBulan.'-'.$reqTahun;
                                $nama_hari = date('l', strtotime($date));
                                
                                if($nama_hari == "Saturday")
                                    $classHari = "sabtu";
                                elseif($nama_hari == "Sunday")
                                    $classHari = "minggu";
                                else
                                {
                                    $arrHari = explode(',', $hari_libur);
                                
                                    for($j=1; $j<=count($arrHari); $j++)
                                    {
                                        if($i == $arrHari[$j])
                                        {
                                            $classHari = "libur";
                                            break;
                                        }
                                        else
                                            $classHari = "";
                                    }
                                }
                            ?>
                            <tr>
                                <th style="text-align:center;"><?=$i?></th>
                                <td><span class="<?=$classHari?>"><?=getNamaHariIndo($nama_hari)?></span></td>
                                <?
                                for($n=0; $n<count($infofield); $n++)
                                {
                                    $fieldkolom= $infofield[$n].$i;
                                    // echo $fieldkolom;exit;
                                    $infolabel= $arrdata[$z][$fieldkolom];

                                    $infocenter= "center";
                                    if($n >= count($infofield) - 1)
                                        $infocenter= "";
                                ?>
                                <td style="text-align:<?=$infocenter?>;"><?=$infolabel?></td>
                                <?
                                }
                                ?>
                            </tr>
                            <?
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                
                </div>
            <?  
            }
            ?>
        </div>

        <!-- <div class="area-tombol-bawah" style="float: left; margin: 0 auto;" >
            <input type="button" onclick="document.location.href='app/loadUrl/main/presensi_rekap_awal_pegawai'" class="btn btn-primary" value="Kembali" />
        </div> -->
    </div>
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
    <script type="text/javascript">
    $(function(){
        $("#reqBulan,#reqTahun").change(function() {
            var reqBulan= reqTahun= "";
            reqBulan= $("#reqBulan").val();
            reqTahun= $("#reqTahun").val();
            document.location.href = "app/loadUrl/main/presensi_rekap_awal_pegawai_add?reqId=<?=$reqId?>&reqBulan="+reqBulan+"&reqTahun="+reqTahun;
        });

        $('[id^="btncetak"]').on("click", function () {
            var reqBulan= reqTahun= "";
            reqBulan= $("#reqBulan").val();
            reqTahun= $("#reqTahun").val();

            infoid= $(this).attr('id');
            infoid= infoid.replace("btncetak", "");
            // alert(infoid);
            if(infoid !== "")
            {
                opUrl= "app/loadUrl/main/presensi_rekap_awal_pegawai_excel?reqId="+infoid+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;

                newWindow = window.open(opUrl, 'download'+Math.floor(Math.random()*999999));
                newWindow.focus();
            }

            // btncetak

            /*var reqSatuanKerjaId= reqStatus= reqBulan= reqTahun= reqCariFilter= "";
            // reqCariFilter= $("#reqCariFilter").val();
            reqCariFilter= $('#example_filter input').val();
            reqStatus= $("#reqStatus").val();
            reqBulan= $("#reqBulan").val();
            reqTahun= $("#reqTahun").val();
            reqSatuanKerjaId= $("#reqSatuanKerjaId").val();

            */
        });
        
    });
    </script>
</body>
</html>