<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PangkatRiwayat');
$this->load->model('Pangkat');
$this->load->model('PegawaiFile');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$pangkat= new Pangkat();

$sessionLoginLevel= $this->LOGIN_LEVEL;
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisKp= $this->input->get("reqJenisKp");
$reqMode= $this->input->get("reqMode");
$reqPeriode= $this->input->get("reqPeriode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010301";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);


// kondisi untuk menu
$this->load->library('globalmenusapk');
$vmenusapk= new globalmenusapk();
$arrmenusapk= $vmenusapk->setmenusapk($tempMenuId);
// print_r($arrmenusapk);exit;
$lihatsapk= $arrmenusapk["lihat"];
$kirimsapk= $arrmenusapk["kirim"];
$tariksapk= $arrmenusapk["tarik"];
$syncsapk= $arrmenusapk["sync"];

$pangkat->selectByParams(array());

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new PangkatRiwayat();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();
  // echo $set->query;exit();

  $reqRowId= $set->getField('PANGKAT_RIWAYAT_ID');
  // $reqNoDiklatPrajabatan = $set->getField('PEJABAT_PENETAP_ID');
  $reqTglStlud= dateToPageCheck($set->getField('TANGGAL_STLUD'));
  if($reqTglStlud == "01-01-0001")
    $reqTglStlud= "";
  // echo $reqTglStlud;exit();

  $reqStlud= $set->getField('STLUD');
  $reqNoStlud= $set->getField('NO_STLUD');
  $reqNoNota= $set->getField('NO_NOTA');
  $reqNoSk= $set->getField('NO_SK');
  $reqTh= $set->getField('MASA_KERJA_TAHUN');
  $reqBl= $set->getField('MASA_KERJA_BULAN');
  $reqKredit= dotToComma($set->getField('KREDIT'));
  $reqJenisKp= $set->getField('JENIS_RIWAYAT');
  $reqJenisKpNama= $set->getField('JENIS_RIWAYAT_NAMA');
  $reqKeterangan= $set->getField('KETERANGAN');
  $reqGajiPokok= $set->getField('GAJI_POKOK');
  $reqTglNota= dateToPageCheck($set->getField('TANGGAL_NOTA'));
  $reqTglSk= dateToPageCheck($set->getField('TANGGAL_SK'));
  $reqTmtGol= dateToPageCheck($set->getField('TMT_PANGKAT'));
  $reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
  $reqPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');
  $reqGolRuang= $set->getField('PANGKAT_ID');
  $reqNoUrutCetak= $set->getField('NO_URUT_CETAK');
  
  $vidsapk= $set->getField("ID_SAPK");

  $LastLevel= $set->getField('LAST_LEVEL');
}

$tempAksiProses= "";
if($sessionLoginLevel < $LastLevel)
$tempAksiProses= "1";
// echo $set->query;exit;
// $reqRowId= $set->getField('PANGKAT_ID');

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$hanyalihatfile= "";
// kalau bukan pangkat, hanya lihat saja

$riwayattable= "PANGKAT_RIWAYAT";
if($reqJenisKp == "1")
{
  $hanyalihatfile= "1";
  $reqDokumenKategoriFileId= "1";
}
else if($reqJenisKp == "2")
{
  $hanyalihatfile= "1";
  $reqDokumenKategoriFileId= "2";
}
else
{
  $reqDokumenKategoriFileId= "";
}

if(!empty($reqDokumenKategoriFileId))
  $arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable, $reqDokumenKategoriFileId);
else
{
  $arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
  $reqDokumenKategoriFileId= "4";
}

// print_r($arrsetriwayatfield);exit;

// kalau cpns
if($reqJenisKp == "1")
{
  $setdetil= new PangkatRiwayat();
  $setdetil->selectByParams(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqId." AND A.JENIS_RIWAYAT = 1");
  $setdetil->firstRow();
  $vpangkatriwayatid= $setdetil->getField("PANGKAT_RIWAYAT_ID");
  $vRowId= $vpangkatriwayatid;
  $suratmasukpegawaiid="";
  $paramriwayatfield="skcpns,notausulcpns";
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $vRowId, $suratmasukpegawaiid, $paramriwayatfield);
}
// kalau pns
else if($reqJenisKp == "2")
{
  $setdetil= new PangkatRiwayat();
  $setdetil->selectByParams(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqId." AND A.JENIS_RIWAYAT = 2");
  $setdetil->firstRow();
  $vpangkatriwayatid= $setdetil->getField("PANGKAT_RIWAYAT_ID");
  $vRowId= $vpangkatriwayatid;
  $suratmasukpegawaiid="";
  $paramriwayatfield="skpns,notausulpns,suratujikesehatan,sttplprajabatan,bapsumpah";
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $vRowId, $suratmasukpegawaiid, $paramriwayatfield);
}
else
{
  if(empty($reqRowId))
    $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru");
  else
    $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId);

  $vRowId= $reqRowId;
}

$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
// print_r($arrlistpilihfile);exit;
$arrlistriwayat= $arrlistriwayatfilepegawai["riwayat"];

// $keymode= $riwayattable.";".$reqRowId.";foto";

$arrlistpilihfilefield= [];
$reqDokumenPilih= [];
foreach ($arrsetriwayatfield as $key => $value)
{
  $keymode= $value["riwayatfield"];
  $arrlistpilihfilefield[$keymode]= [];

  if(!empty($arrlistpilihfile))
  {
    $arrlistpilihfilefield[$keymode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode);

    $reqDokumenPilih[$keymode]= "";
    $infocari= "selected";
    $arraycari= in_array_column($infocari, "selected", $arrlistpilihfilefield[$keymode]);
    // print_r($arraycari);exit;
    if(!empty($arraycari))
    {
      // print_r($arraycari);exit;
      $reqDokumenPilih[$keymode]= 2;
    }
  }
}
// print_r($reqDokumenPilih);exit;
// print_r($arrlistpilihfilefield);exit;

$set= new KualitasFile();
$set->selectByParams(array());
// echo $set->query;exit;
$arrkualitasfile=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("KUALITAS_FILE_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrkualitasfile, $arrdata);
}

$disabledfile= "";
if(!empty($hanyalihatfile))
{
  $disabledfile= "disabled";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="msapplication-tap-highlight" content="no">
<meta name="description" content="Simpeg Jombang">
<meta name="keywords" content="Simpeg Jombang">
<title>Simpeg Jombang</title>
<base href="<?=base_url()?>" />

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>
  
<script type="text/javascript"> 
  function setGaji()
  {
    var reqTglSk= reqPangkatId= reqMasaKerja= "";
    reqTglSk= $("#reqTglSk").val();
    reqPangkatId= $("#reqGolRuang").val();
    reqMasaKerja= $("#reqTh").val();

    urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
    $.ajax({'url': urlAjax,'success': function(data){
      tempValueGaji= parseFloat(data);
      $("#reqGajiPokok").val(FormatCurrency(tempValueGaji));
    }});
  }

  $(function(){
    <?
    if($reqGajiPokok == "")
    {
    ?>
      setGaji();
    <?
    }
    ?>
       
    $("#reqGolRuang").change(function(){
      setGaji();
    });

    $("#reqTglSk, #reqTh").keyup(function(){
      setGaji();
    });

    $("#reqsimpan").click(function() { 
      if($("#ff").form('validate') == false){
        return false;
      }

      var reqTanggal= "";
      reqTanggal= $("#reqTmtGol").val();
      var s_url= "hari_libur_json/hakentrigaji?reqTanggal="+reqTanggal;
      $.ajax({'url': s_url,'success': function(dataajax){
        // return false;
        dataajax= dataajax.split(";");
        rowid= parseInt(dataajax[0]);
        infodata= dataajax[1];
        if(rowid == 1)
        {
          mbox.alert('Hubungi Sub Bidang Kepangkatan, Anda tidak berhak menambah data di atas TMT ' + infodata, {open_speed: 0});
          return false;
        }
        else
          $("#reqSubmit").click();
      }});
            
    });

    $('#ff').form({
      url:'pangkat_riwayat_json/add',
      onSubmit:function(){
        var reqJenisKp= $("#reqJenisKp").val();

        if(reqJenisKp == "")
        {
          mbox.alert("Lengkapi data Jenis Riwayat Pangkat terlebih dahulu", {open_speed: 0});
          return false;
        }
          
        var reqGolRuang= "";
        reqGolRuang= $("#reqGolRuang").val();

        if(reqGolRuang == "")
        {
          mbox.alert("Lengkapi data golongan ruang terlebih dahulu", {open_speed: 0});
          return false;
        }

        if($(this).form('validate')){}
        else
        {
          $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
          return false;
        }
      },
      success:function(data){
        // console.log(data);return false;
        data = data.split("-");
        rowid= data[0];
        infodata= data[1];
          
        if(rowid == "xxx")
        {
          mbox.alert(infodata, {open_speed: 0});
        }
        else
        {
          mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
          {
            clearInterval(interval);
            mbox.close();
            document.location.href= "app/loadUrl/app/pegawai_add_pangkat_data/?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqRowId="+rowid;
          }, 1000));
          $(".mbox > .right-align").css({"display": "none"});
        }
           
      }
    });

    $('input[id^="reqPejabatPenetap"]').autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
        $.ajax({
          url: urlAjax,
          type: "GET",
          dataType: "json",
          data: { term: request.term },
          success: function(responseData){
            if(responseData == null)
            {
              response(null);
            }
            else
            {
              var array = responseData.map(function(element) {
                return {desc: element['desc'], id: element['id'], label: element['label'], statusht: element['statusht']};
              });
              response(array);
            }
          }
        })
      },
      focus: function (event, ui) 
      { 
        var id= $(this).attr('id');
        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
        }

        var statusht= "";
          //statusht= ui.item.statusht;
          $("#"+indexId).val(ui.item.id).trigger('change');
        },
        //minLength:3,
        autoFocus: true
      }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      //return
      return $( "<li>" )
      .append( "<a>" + item.desc + "</a>" )
      .appendTo( ul );
    };
      
    setJenisKenaikanPangkat();
    $('#reqJenisKp').bind('change', function(ev) {
      setJenisKenaikanPangkat();
    });

  });
    
  function setJenisKenaikanPangkat()
  {
    var reqJenisKp= "";
    reqJenisKp= $("#reqJenisKp").val();
		// $("#reqinfobkn,#reqinfokredit").hide();
		$("#reqinfostlud,#reqinfokredit").hide();
		$("#reqinfobkn,#setinfopangkat").show();
		$('#reqKredit').validatebox({required: false});
    $('#reqKredit').removeClass('validatebox-invalid');
    
    if(reqJenisKp == "")
    {
     document.location.href = "app/loadUrl/app/pegawai_add_pangkat_baru?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqRowId=<?=$reqRowId?>";
    }
    else if(reqJenisKp == "7")
    {
			//$("#reqinfobkn").show();
			$("#reqinfostlud").show();
			$("#reqKredit").val("");
		}
		else if(reqJenisKp == "6")
		{
			$("#reqinfokredit").show();
			$('#reqKredit').validatebox({required: true});
      // console.log("s");
		}
		else
		{
      $("#reqStlud,#reqNoStlud,#reqTglStlud,#reqKredit").val("");
      $("#reqStlud").material_select();
    }

	}
</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<body>    
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna">EDIT PANGKAT</li>
          <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
              <div class="input-field col s12 m6">
                <?
                if($reqJenisKp == 1 || $reqJenisKp == 2)
                {
                ?>
                  <label for="reqJenisKpNama">Jenis Riwayat Pangkat</label>
                  <input type="hidden" id="reqJenisKp" name="reqJenisKp" value="<?=$reqJenisKp?>" />
                  <input type="text" id="reqJenisKpNama" value="<?=$reqJenisKpNama?>" disabled />
                <?
                }
                else
                {
                ?>
                  <label for="reqJenisKp" class="active">Jenis Riwayat Pangkat</label>
                  <select <?=$disabled?> name="reqJenisKp" id="reqJenisKp" >
                    <option value="" <? if($reqJenisKp == "") echo 'selected'?>></option>
                    <option value="4" <? if($reqJenisKp == 4) echo 'selected'?>>Reguler</option>
                    <option value="11" <? if($reqJenisKp == 11) echo 'selected'?>>Kenaikan Pangkat Pengabdian</option>
                    <option value="5" <? if($reqJenisKp == 5) echo 'selected'?>>Pilihan Struktural</option>
                    <option value="6" <? if($reqJenisKp == 6) echo 'selected'?>>Pilihan JFT</option>
                    <option value="7" <? if($reqJenisKp == 7) echo 'selected'?>>Pilihan PI/UD</option>
                    <option value="10" <? if($reqJenisKp == 10) echo 'selected'?>>Penambahan Masa Kerja</option>
                    <option value="8" <? if($reqJenisKp == 8) echo 'selected'?>>Hukuman disiplin</option>
                    <option value="9" <? if($reqJenisKp == 9) echo 'selected'?>>Pemulihan hukuman disiplin</option>
                  </select>
                <?
                }
                ?>
              </div>
            </div>
            
            <div class="row">
            </div>
            
            <span id="setinfopangkat">
              <div class="row">
                <div class="input-field col s12 m4">
                  <label for="reqNoSk">No SK</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$read?> value="<?=$reqNoSk?>" title="No SK harus diisi"  />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqTglSk">Tgl SK</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk"  value="<?=$reqTglSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqNoUrutCetak">No. Urut Cetak</label>
                  <input placeholder="" type="text" class="easyui-validatebox" id="reqNoUrutCetak" name="reqNoUrutCetak" <?=$read?> value="<?=$reqNoUrutCetak?>" />
                </div>
              </div>    

              <div class="row" id="reqinfostlud">
                <div class="input-field col s12 m4">
                  <select  name="reqStlud" id="reqStlud">
                    <option value=""></option>
                    <option value="1" <? if($reqStlud == 1) echo 'selected'?>>Tingkat I</option>
                    <option value="2" <? if($reqStlud == 2) echo 'selected'?>>Tingkat II</option>
                    <option value="3" <? if($reqStlud == 3) echo 'selected'?>>Tingkat III</option>
                  </select>
                  <label for="reqStlud">STLUD</label>
                </div> 
                <div class="input-field col s12 m4">
                  <label for="reqNoStlud">No. STLUD</label>
                  <input placeholder="" type="text" id="reqNoStlud" name="reqNoStlud" <?=$read?> value="<?=$reqNoStlud?>" />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqTglStlud">Tgl. STLUD</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglStlud" id="reqTglStlud"  value="<?=$reqTglStlud?>" maxlength="10" onKeyDown="return format_date(event,'reqTglStlud');"/>
                </div>
              </div>

              <div class="row" id="reqinfobkn">
                <div class="input-field col s12 m6">
                  <label for="reqNoNota">No Nota BKN</label>
                  <input placeholder="" type="text" name="reqNoNota" id="reqNoNota" <?=$read?> value="<?=$reqNoNota?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglNota">Tgl Nota BKN</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglNota" id="reqTglNota"  value="<?=$reqTglNota?>" maxlength="10" onKeyDown="return format_date(event,'reqTglNota');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <select name="reqGolRuang" <?=$disabled?> id="reqGolRuang" >
                    <option value=""></option>
                    <? 
                    while($pangkat->nextRow())
                    {
                    ?>
                      <option value="<?=$pangkat->getField('PANGKAT_ID')?>" <? if($reqGolRuang == $pangkat->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat->getField('KODE')?></option>
                    <? 
                    }
                    ?>
                  </select>
                  <label>Gol/Ruang</label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTmtGol">TMT SK</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtGol" id="reqTmtGol"  value="<?=$reqTmtGol?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtGol');"/>
                </div>
              </div>        

              <div class="row">
                <div class="input-field col s6 m3">
                  <label for="reqTh">Masa Kerja Tahun</label>
                  <input type="hidden" name="reqTempTh" value="<?=$reqTh?>" />
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqTh" <?=$read?> value="<?=$reqTh?>" id="reqTh" title="Masa kerja tahun harus diisi" />
                </div>

                <div class="input-field col s6 m3">
                  <label for="reqBl">Masa Kerja Bulan</label>
                  <input type="hidden" name="reqTempBl" value="<?=$reqBl?>" />
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqBl" <?=$read?> value="<?=$reqBl?>" id="reqBl" title="Masa kerja bulan diisi" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqGajiPokok">Gaji Pokok</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
                </div>
                <div class="input-field col s12 m3" id="reqinfokredit">
                  <label for="reqKredit">Angka Kredit</label>
                  <input placeholder="" type="text" id="reqKredit" name="reqKredit" <?=$read?>  class="easyui-validatebox" value="<?=$reqKredit?>" onkeypress='kreditvalidate(event, this)' />
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap">Pejabat Penetapan</label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input placeholder="" type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <textarea <?=$disabled?> name="reqKeterangan" id="reqKeterangan" class="required materialize-textarea"><?=$reqKeterangan?></textarea>
                  <label for="reqKeterangan">Keterangan</label>
                </div>
              </div>  

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_pangkat_monitoring?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>";
                    });
                  </script>

                  <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="vRowId" value="<?=$vRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <?
                  if($reqRowId == "")
                  {
                    if($tempAksesMenu == "A")
                    {
                    ?>
                    <button type="submit" style="display:none" id="reqSubmit"></button>
                    <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                      <i class="mdi-content-save left hide-on-small-only"></i>
                    </button>
                    <?
                    }
                  }
                  else
                  {
                    if($set->getField('STATUS') ==1 ){}
                    else
                    {
                      if($set->getField('DATA_HUKUMAN') == 0)
                      {
                        if($tempAksiProses == "1"){}
                        else
                        {
                          if($tempAksesMenu == "A")
                          {
                  ?>
                            <button type="submit" style="display:none" id="reqSubmit"></button>
                            <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                              <i class="mdi-content-save left hide-on-small-only"></i>
                            </button>
                  <?
                          }
                        }
                      }
                    }
                  }
                  ?>

                    <?
                    if(!empty($vidsapk) && !empty($lihatsapk))
                    {
                      $vdetilsapk= $vidsapk."___pegawai_add_pangkat_sapk_data";
                      $vdetillabelsapk= "Data SAPK BKN";
                    ?>
                    <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id='buttondatasapk<?=$vdetilsapk?>'>
                      <input type="hidden" id="labelvsapk<?=$vdetilsapk?>" value="<?=$vdetillabelsapk?>" />
                      <input type="hidden" id="<?=$vidsapk?>" value="<?=$reqId?>" />
                      <span id="labelframesapk<?=$vdetilsapk?>">Cek <?=$vdetillabelsapk?></span>
                    </button>
                    <?
                    }
                    ?>
                </div>
              </div>

              <div class="row">
                  <div class="input-field col s12 m12">
                  <?
                  // area untuk upload file
                  foreach ($arrsetriwayatfield as $key => $value)
                  {
                    $riwayatfield= $value["riwayatfield"];
                    $riwayatfieldtipe= $value["riwayatfieldtipe"];
                    $riwayatfieldinfo= $value["riwayatfieldinfo"];
                    $riwayatfieldstyle= $value["riwayatfieldstyle"];
                    // echo $riwayatfieldstyle;exit;
                  ?>
                    <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$riwayatfieldstyle?>" type="button" id='buttonframepdf<?=$riwayatfield?>'>
                      <input type="hidden" id="labelvpdf<?=$riwayatfield?>" value="<?=$riwayatfieldinfo?>" />
                      <span id="labelframepdf<?=$riwayatfield?>"><?=$riwayatfieldinfo?></span>
                    </button>
                  <?
                  }
                  ?>
                  </div>
                </div>

                <div class="row"><div class="col s12 m12"><br/></div></div>

                <?
                // area untuk upload file
                foreach ($arrsetriwayatfield as $key => $value)
                {
                  $riwayatfield= $value["riwayatfield"];
                  $riwayatfieldtipe= $value["riwayatfieldtipe"];
                  $vriwayatfieldinfo= $value["riwayatfieldinfo"];
                  $riwayatfieldinfo= " - ".$vriwayatfieldinfo;
                  $riwayatfieldrequired= $value["riwayatfieldrequired"];
                  $riwayatfieldrequiredinfo= $value["riwayatfieldrequiredinfo"];
                  $vriwayattable= $value["vriwayattable"];
                  $vriwayatid= "";
                  $vpegawairowfile= $reqDokumenKategoriFileId."-".$vriwayattable."-".$riwayatfield."-".$vriwayatid;
                ?>
                <div class="row">
                  <div class="input-field col s12 m4">
                    <input type="hidden" id="reqDokumenRequired<?=$riwayatfield?>" name="reqDokumenRequired[]" value="<?=$riwayatfieldrequired?>" />
                    <input type="hidden" id="reqDokumenRequiredNama<?=$riwayatfield?>" name="reqDokumenRequiredNama[]" value="<?=$vriwayatfieldinfo?>" />
                    <input type="hidden" id="reqDokumenRequiredTable<?=$riwayatfield?>" name="reqDokumenRequiredTable[]" value="<?=$vriwayattable?>" />
                    <input type="hidden" id="reqDokumenRequiredTableRow<?=$riwayatfield?>" name="reqDokumenRequiredTableRow[]" value="<?=$vpegawairowfile?>" />
                    <input type="hidden" id="reqDokumenFileId<?=$riwayatfield?>" name="reqDokumenFileId[]" />
                    <input type="hidden" id="reqDokumenKategoriFileId<?=$riwayatfield?>" name="reqDokumenKategoriFileId[]" value="<?=$reqDokumenKategoriFileId?>" />
                    <input type="hidden" id="reqDokumenKategoriField<?=$riwayatfield?>" name="reqDokumenKategoriField[]" value="<?=$riwayatfield?>" />
                    <input type="hidden" id="reqDokumenPath<?=$riwayatfield?>" name="reqDokumenPath[]" value="" />
                    <input type="hidden" id="reqDokumenTipe<?=$riwayatfield?>" name="reqDokumenTipe[]" value="<?=$riwayatfieldtipe?>" />

                    <select <?=$disabledfile?> id="reqDokumenPilih<?=$riwayatfield?>" name="reqDokumenPilih[]">
                      <?
                      foreach ($arrpilihfiledokumen as $key => $value)
                      {
                        $optionid= $value["id"];
                        $optiontext= $value["nama"];
                      ?>
                        <option value="<?=$optionid?>" <? if($reqDokumenPilih[$riwayatfield] == $optionid) echo "selected";?>><?=$optiontext?></option>
                      <?
                      }
                      ?>
                    </select>
                    <label for="reqDokumenPilih<?=$riwayatfield?>">
                      File Dokumen<?=$riwayatfieldinfo?>
                      <span id="riwayatfieldrequiredinfo<?=$riwayatfield?>" style="color: red;"><?=$riwayatfieldrequiredinfo?></span>
                    </label>
                  </div>

                  <div class="input-field col s12 m4">
                    <select <?=$disabledfile?> name="reqDokumenFileKualitasId[]" id="reqDokumenFileKualitasId<?=$riwayatfield?>">
                      <option value=""></option>
                      <?
                      foreach ($arrkualitasfile as $key => $value)
                      {
                        $optionid= $value["ID"];
                        $optiontext= $value["TEXT"];
                        $optionselected= "";
                        if($reqDokumenFileKualitasId == $optionid)
                          $optionselected= "selected";

                        $arrkecualitipe= [];
                        $arrkecualitipe= $vfpeg->kondisikategori($riwayatfieldtipe);
                        if(!in_array($optionid, $arrkecualitipe))
                          continue;
                      ?>
                        <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                      <?
                      }
                      ?>
                    </select>
                    <label for="reqDokumenFileKualitasId<?=$riwayatfield?>">Kualitas Dokumen<?=$riwayatfieldinfo?></label>
                  </div>

                  <div id="labeldokumenfileupload<?=$riwayatfield?>" class="input-field col s12 m4" style="margin-top: -25px; margin-bottom: 10px;">
                    <div class="file_input_div">
                      <div class="file_input input-field col s12 m4">
                        <label class="labelupload">
                          <i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
                          <input id="file_input_file" name="reqLinkFile[]" class="none" type="file" />
                        </label>
                      </div>
                      <div id="file_input_text_div" class=" input-field col s12 m8">
                        <input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                        <label for="file_input_text"></label>
                      </div>
                    </div>
                  </div>

                  <div id="labeldokumendarifileupload<?=$riwayatfield?>" class="input-field col s12 m4">
                    <select <?=$disabledfile?> id="reqDokumenIndexId<?=$riwayatfield?>" name="reqDokumenIndexId[]">
                      <option value="" selected></option>
                      <?
                      $arrlistpilihfilepegawai= $arrlistpilihfilefield[$riwayatfield];
                      foreach ($arrlistpilihfilepegawai as $key => $value)
                      {
                        $optionid= $value["index"];
                        $optiontext= $value["nama"];
                        $optionselected= $value["selected"];
                      ?>
                        <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                      <?
                      }
                      ?>
                    </select>
                    <label for="reqDokumenIndexId<?=$riwayatfield?>">Nama e-File<?=$riwayatfieldinfo?></label>
                  </div>

                </div>
                <?
                }
                // area untuk upload file
                ?>
              
            </span>

          </form>
          </li>
        </ul>
      </div>

      <div id='divframepdf' class="col s12 m6" style="padding-top: 10px;">
        <input type="hidden" id="vnewframe" value="">
        <span id="labelriwayatframepdf"></span>
        <img id="infonewimage" style="width:inherit; width: 100%; height: 100%" />
        <iframe id="infonewframe" style="width: 100%; height: 160%" src=""></iframe>
      </div>

    </div>
  </div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
  });

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);

  // apabila butuh kualitas dokumen di ubah
  vselectmaterial= "1";
  // untuk area untuk upload file

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>
<script type="text/javascript" src="lib/easyui/pelayanan-bkndetil.js"></script>
<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>