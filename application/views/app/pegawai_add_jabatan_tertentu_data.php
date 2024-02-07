<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('JabatanRiwayat');
$this->load->model('Rumpun');
$this->load->model('KualitasFile');
$this->load->model('PegawaiFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$sessionLoginLevel= $this->LOGIN_LEVEL;
$tempMenuId= "010401";
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

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
	$reqMode = 'update';
    $set= new JabatanRiwayat();
	$statement= " AND A.JABATAN_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqRowId= $set->getField('JABATAN_RIWAYAT_ID');
	$reqIsManual= $set->getField('IS_MANUAL');
	$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
	$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$reqSatkerId= $set->getField('SATKER_ID');
	$reqNoSk= $set->getField('NO_SK');
	$reqEselonId= $set->getField('ESELON_ID');
	$reqNama= $set->getField('NAMA');
	$reqJabatanFtId= $set->getField('JABATAN_FT_ID');
	$reqSatkerId= $set->getField('SATKER_ID');
	$reqSatker= $set->getField('SATUAN_KERJA_NAMA_DETIL');
	$reqNoPelantikan= $set->getField('NO_PELANTIKAN');
	$reqTunjangan= $set->getField('TUNJANGAN');
	$reqTglSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));
	$reqTmtJabatan= dateTimeToPageCheck($set->getField('TMT_JABATAN'));
	$reqTglPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));
	$reqBlnDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));
  $reqTmtSpmt= dateTimeToPageCheck($set->getField('TMT_SPMT'));
  $reqKeteranganBUP= $set->getField('KETERANGAN_BUP');
  $reqTipePegawaiId= $set->getField('TIPE_PEGAWAI_ID');
	$reqKredit= dotToComma($set->getField('KREDIT'));
  $reqLastLevel= $set->getField('LAST_LEVEL');

  $reqSkDasarJabatan= $set->getField('STATUS_SK_DASAR_JABATAN');
  $reqTmtSpmt= dateTimeToPageCheck($set->getField('TMT_SPMT'));
  $reqTanggalSelesai= dateTimeToPageCheck($set->getField('TMT_SELESAI_JABATAN'));
  $reqLamaMenjabat= $set->getField('LAMA_JABATAN_HITUNG');
  $reqLamaBulanMenjabat= $set->getField('LAMA_JABATAN_BULAN_HITUNG');
  $reqNilaiRekam= $set->getField('NILAI_REKAM_JEJAK_HITUNG');
  $reqRumpunJabatan= $set->getField('RUMPUN_ID');
  $reqRumpunJabatanNama= $set->getField('RUMPUN_NAMA');
  // $reqFileUpload= $set->getField('FILE_UPLOAD');
	
  $infodatahukuman= $set->getField('DATA_HUKUMAN');
  $infodatastatus= $set->getField('STATUS');

	$reqTmtWaktuJabatan= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);
	if($reqTmtWaktuJabatan == "" || $reqTmtWaktuJabatan == "00:00"){}
	else
	$reqCheckTmtWaktuJabatan= "1";

  if($reqJabatanFtId == "")
    $reqNama= "";

  $vidsapk= $set->getField("ID_SAPK");

  $reqBidangJabatanTerkaitId= $set->getField('BIDANG_JABATAN_TERKAIT_ID');
  $reqRumpunNamaKompetensi= $set->getField('RUMPUN_NAMA');
}
$reqJenisJabatan= 3;

$rumpun= new Rumpun();
$rumpun->selectByParams(array());

$set= new Rumpun();
$set->selectbidangjabatanterkait(array());
// echo $set->query;exit;
$arrbidangjabatanterkait=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("BIDANG_JABATAN_TERKAIT_ID");
  $arrdata["nama"]= $set->getField("NAMA");
  $arrdata["rumpunid"]= $set->getField("RUMPUN_ID");
  $arrdata["rumpun"]= $set->getField("RUMPUN_NAMA");
  array_push($arrbidangjabatanterkait, $arrdata);
}
// print_r($arrbidangjabatanterkait);exit;

$statement= " AND A.JENIS_JABATAN_ID = ".$reqJenisJabatan;
$skdasarjabatan= new Rumpun();
$skdasarjabatan->selectskdasarjabatan(array(), -1,-1, $statement);

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$hanyalihatfile= "";
// kalau bukan hukuman, hanya lihat saja
if($infodatahukuman == 0 || empty($infodatahukuman))
{
  $riwayattable= "JABATAN_RIWAYAT";
  $reqDokumenKategoriFileId= "3"; // ambil dari table KATEGORI_FILE, cek sesuai mode
}
else
{
  $hanyalihatfile= "1";
  $riwayattable= "HUKUMAN";
  $reqDokumenKategoriFileId= "28"; // ambil dari table KATEGORI_FILE, cek sesuai mode
}

$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
// print_r($arrsetriwayatfield);exit;

if(empty($reqRowId))
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru");
else
{
  if($infodatahukuman == 0 || empty($infodatahukuman))
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId);
  else
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $infodatahukuman);
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
    function settimetmt(info)
    {
      $("#reqInfoCheckTmtWaktuJabatan").hide();
      if($("#reqCheckTmtWaktuJabatan").prop('checked')) 
      {
        $("#reqInfoCheckTmtWaktuJabatan").show();
      }
      else
      {
        if(info == 2)
          $("#reqTmtWaktuJabatan").val("");
      }
    }

    function setcetang()
    {
      $("#labelrumpunset").show();
      $("#labelrumpunselect").hide();
      if($("#reqIsManual").prop('checked')) 
      {
        $("#labelrumpunset").hide();
        $("#labelrumpunselect").show();

        $("#reqNamaId").val("");
      }
      else
      {
        $("#reqNama, #reqNamaId").val("");
      }

      // $("#reqNama, #reqNamaId").val("");
    }
	
    $(function(){
      arrbidangjabatanterkait= JSON.parse('<?=JSON_encode($arrbidangjabatanterkait)?>');
      // console.log(arrbidangjabatanterkait);

      $("#reqBidangJabatanTerkaitId").change(function() { 
        var reqBidangJabatanTerkaitId= $("#reqBidangJabatanTerkaitId").val();

        reqNilaiKompentensiText= 0;
        infoid= reqBidangJabatanTerkaitId;
        varrbidangjabatanterkait= arrbidangjabatanterkait.filter(item => item.id === infoid);
        // console.log(varrbidangjabatanterkait);
        vtextid= vtext= "";
        if(Array.isArray(varrbidangjabatanterkait) && varrbidangjabatanterkait.length)
        {
          vtextid= varrbidangjabatanterkait[0]["rumpunid"];
          vtext= varrbidangjabatanterkait[0]["rumpun"];
        }

        $("#reqRumpunJabatan").val(vtextid);
        $("#reqRumpunNamaKompetensi").val(vtext);
      });

      settimetmt(1);

      $("#reqRumpunJabatanSelect").change(function() { 
        var reqRumpunJabatan= $("#reqRumpunJabatanSelect").val();
        var reqRumpunJabatanNama= $("#reqRumpunJabatanSelect option:selected").text();
        // console.log(reqRumpunJabatanNama);

        // $("#reqRumpunJabatan").val(reqRumpunJabatan);
        // $("#reqRumpunJabatanNama").val(reqRumpunJabatanNama);
      });

      $('#ff').form({
        url:'jabatan_riwayat_json/add',
        onSubmit:function(){
          reqTipePegawaiId= $("#reqTipePegawaiId").val();
          if(reqTipePegawaiId == "")
          {
            mbox.alert('Data tidak bisa disimpan, karena Jenis JFT belum diisi.', {open_speed: 0});
            return false;
          }

          reqBidangJabatanTerkaitId= $("#reqBidangJabatanTerkaitId").val();
          if(reqBidangJabatanTerkaitId == "")
          {
            mbox.alert('Data tidak bisa disimpan, karena Bidang Urusan belum diisi.', {open_speed: 0});
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
              document.location.href= "app/loadUrl/app/pegawai_add_jabatan_tertentu_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }

        }

      });

      $("#reqCheckTmtWaktuJabatan").click(function () {
       settimetmt(2);
      });

      $("#labelrumpunset").show();
      $("#labelrumpunselect").hide();
      reqIsManual= "<?=$reqIsManual?>";
      if(reqIsManual == "1")
      {
        $("#labelrumpunset").hide();
        $("#labelrumpunselect").show();
      }

      $("#reqIsManual").click(function () {
    		//if($("#reqIsManual").prop('checked')) 
    		//{
    			//$("#reqNama,#reqNamaId").val("");
    			//$("#reqJabatanFu, #reqSatker, #reqSatkerId").val("");

    			// $("#reqNama, #reqNamaId").val("");
          setcetang();

    		//}
  	  });
	 
  	  $('input[id^="reqNama"]').autocomplete({
  	  //$('input[id^="reqPejabatPenetap"], input[id^="reqSatker"]').autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";
		
          if (id.indexOf('reqNama') !== -1)
          {
            if($("#reqIsManual").prop('checked')) 
            {
              return false;
            }
          }
		
  		    if (id.indexOf('reqNama') !== -1)
          {
            var element= id.split('reqNama');
            var indexId= "reqNamaId"+element[1];
            reqTanggalBatas= $("#reqTmtJabatan").val();
  		      urlAjax= "satuan_kerja_json/auto?reqTanggalBatas="+reqTanggalBatas;
            //urlAjax= "satuan_kerja_json/namajabatan";
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
  				      return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], rumpun_id: element['rumpun_id'], rumpun_nama: element['rumpun_nama']};
                });
                response(array);
              }
            }
          })
        },
        focus: function (event, ui) 
        { 
          var id= $(this).attr('id');
          if (id.indexOf('reqNama') !== -1)
          {
            var element= id.split('reqNama');
      		  var indexId= "reqNamaId"+element[1];
      		  //$("#reqNama").val("").trigger('change');

            // $("#reqRumpunJabatan").val(ui.item.rumpun_id).trigger('change');
            // $("#reqRumpunJabatanNama").val(ui.item.rumpun_nama).trigger('change');
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
	  
  	  $('input[id^="reqPejabatPenetap"], input[id^="reqJabatanFt"], input[id^="reqSatker"]').autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";
		
          if (id.indexOf('reqPejabatPenetap') !== -1)
          {
            var element= id.split('reqPejabatPenetap');
            var indexId= "reqPejabatPenetapId"+element[1];
            urlAjax= "pejabat_penetap_json/combo";
          }
  		    else if (id.indexOf('reqJabatanFt') !== -1)
          {
            var reqTipePegawaiId= "";
            reqTipePegawaiId= $("#reqTipePegawaiId").val();
            var element= id.split('reqJabatanFt');
            var indexId= "reqJabatanFtId"+element[1];
            urlAjax= "jabatan_ft_json/namajabatan?reqTipePegawaiId="+reqTipePegawaiId;
          }
  		    else if (id.indexOf('reqSatker') !== -1)
          {
            var element= id.split('reqSatker');
            var indexId= "reqSatkerId"+element[1];
            urlAjax= "satuan_kerja_json/auto";
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
  				      return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja']};
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
		    else if (id.indexOf('reqJabatanFt') !== -1)
        {
          var element= id.split('reqJabatanFt');
          var indexId= "reqJabatanFtId"+element[1];
        }
		    else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
    		  var indexId= "reqSatkerId"+element[1];
    		  $("#reqNama").val("").trigger('change');
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

      $("#reqJenisJabatan").change(function() { 
        var jenis_jabatan = $("#reqJenisJabatan").val();
        if(jenis_jabatan=="")
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==1)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_struktural_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==2)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_fungsional_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==3)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_tertentu_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
      });
    });
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
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT JABATAN TERTENTU</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <select id="reqJenisJabatan" name="reqJenisJabatan" <?=$disabled?>>
                  	<option value="" <? if($reqJenisJabatan=="") echo 'selected';?>></option>
                    <option value="1" <? if($reqJenisJabatan==1) echo 'selected';?>>Jabatan Struktural</option>
                    <option value="2" <? if($reqJenisJabatan==2) echo 'selected';?>>Jabatan Fungsional Umum</option>
                    <option value="3" <? if($reqJenisJabatan==3) echo 'selected';?>>Jabatan Fungsional Tertentu</option>
                    <!-- <option value="4" <? if($reqJenisJabatan==4) echo 'selected';?>>Tugas Tambahan</option> -->
                  </select>
                  <label for="reqJenisJabatan">Jenis Jabatan</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <select id="reqTipePegawaiId" name="reqTipePegawaiId" <?=$disabled?> >
                    <option value="" <? if($reqTipePegawaiId == "") echo 'selected';?>></option>
                    <option value="21" <? if($reqTipePegawaiId == 21) echo 'selected';?>>Pendidikan</option>
                    <option value="22" <? if($reqTipePegawaiId == 22) echo 'selected';?>>Kesehatan</option>
                    <option value="23" <? if($reqTipePegawaiId == 23) echo 'selected';?>>Lain-Lain</option>
                  </select>
                  <label for="reqJenisJFT">Jenis JFT</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk">No. SK</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSk">Tgl. SK</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk" value="<?=$reqTglSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
                </div>
              </div>
			  
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqJabatanFt">Nama Jabatan Fungsional</label>
                  <input type="hidden" name="reqJabatanFtId" id="reqJabatanFtId" value="<?=$reqJabatanFtId?>" /> 
                  <input placeholder="" type="text" id="reqJabatanFt" name="reqNama" <?=$read?> value="<?=$reqNama?>" class="easyui-validatebox" required />
                </div>
                
                <div class="input-field col s12 m1">
                    <input type="checkbox" id="reqCheckTmtWaktuJabatan" name="reqCheckTmtWaktuJabatan" value="1" <? if($reqCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
                    <label for="reqCheckTmtWaktuJabatan"></label>
                </div>
                
                <div class="input-field col s12 m3">
                  <label for="reqTmtJabatan">TMT Jabatan Fungsional</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtJabatan" id="reqTmtJabatan" value="<?=$reqTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatan');"/>
                </div>
                
                <div class="input-field col s12 m2" id="reqInfoCheckTmtWaktuJabatan">
                	<input placeholder="00:00" id="reqTmtWaktuJabatan" name="reqTmtWaktuJabatan" type="text" class="" value="<?=$reqTmtWaktuJabatan?>" />
                    <label for="reqTmtWaktuJabatan">Time</label>
                </div>
                
              </div>

               <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqKredit">Angka Kredit</label>
                  <input placeholder="" type="text" id="reqKredit" name="reqKredit" <?=$read?> value="<?=$reqKredit?>" onkeypress='kreditvalidate(event, this)' />
                </div>
              </div>

              <div class="row">
              	<div class="input-field col s12">
                  <input type="checkbox" id="reqIsManual" name="reqIsManual" value="1" <? if($reqIsManual == 1) echo 'checked'?> />
                  <label for="reqIsManual"></label>
                  <a style="font-size: 12px;">*centang jika satker luar kab jombang / satker sebelum tahun 2012</a>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqNama">Satuan Kerja</label>
                  <input placeholder="" type="text" id="reqNama" name="reqSatker" <?=$read?> value="<?=$reqSatker?>" class="easyui-validatebox" required />
                  <input type="hidden" name="reqSatkerId" id="reqNamaId" value="<?=$reqSatkerId?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m4">
                  <label for="reqBlnDibayar">TMT SPMT</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtSpmt" id="reqTmtSpmt" value="<?=$reqTmtSpmt?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtSpmt');"/>
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqTunjangan">No Pelantikan</label>
                  <input placeholder="" type="text" id="reqNoPelantikan" name="reqNoPelantikan" value="<?=$reqNoPelantikan?>" />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqBlnDibayar">Tgl. Pelantikan</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglPelantikan" id="reqTglPelantikan" value="<?=$reqTglPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqTglPelantikan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqTunjangan">Tunjangan</label>
                  <input placeholder="" type="text" id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqBlnDibayar">Bln. Dibayar</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBlnDibayar" id="reqBlnDibayar" value="<?=$reqBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqBlnDibayar');"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqPejabatPenetap">Pejabat Penetap</label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input placeholder="" type="text" id="reqPejabatPenetap" name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                </div>
                <div class="input-field col s12 m6">
                  <select id="reqSkDasarJabatan" name="reqSkDasarJabatan" >
                    <option value=""></option>
                    <?
                    // foreach ($arrskdasarjabatan as $key => $value) 
                    // $optionid= $value["id"];
                    while($skdasarjabatan->nextRow())
                    {
                      $optionid= $skdasarjabatan->getField("SK_DASAR_JABATAN_ID");
                      $optiontext= $skdasarjabatan->getField("NAMA");
                    ?>
                    <option value="<?=$optionid?>" <? if($reqSkDasarJabatan == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqSkDasarJabatan">SK Dasar Jabatan</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqTanggalSelesai">Tanggal Selesai Menjabat</label>
                  <input type="hidden" name="reqTanggalSelesai" value="<?=$reqTanggalSelesai?>" />
                  <input placeholder="" disabled class="easyui-validatebox" type="text" id="reqTanggalSelesai" value="<?=$reqTanggalSelesai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqLamaMenjabat">Lama Menjabat (tahun)</label>
                  <input type="hidden" id="reqLamaMenjabat" name="reqLamaMenjabat" OnFocus="FormatAngka('reqLamaMenjabat')" OnKeyUp="FormatUang('reqLamaMenjabat')" OnBlur="FormatUang('reqLamaMenjabat')" value="<?=$reqLamaMenjabat?>" />
                  <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqLamaMenjabatValue" value="<?=$reqLamaMenjabat?>" />
                </div>
                <div class="input-field col s12 m1">
                  <label for="reqLamaMenjabat">bulan</label>
                  <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqLamaBulanMenjabatValue" value="<?=$reqLamaBulanMenjabat?>" />
                </div>
                <div class="input-field col s12 m1">
                  <label for="reqNilaiRekam">Nilai Rekam Jejak</label>
                  <input type="hidden" id="reqNilaiRekam" name="reqNilaiRekam" OnFocus="FormatAngka('reqNilaiRekam')" OnKeyUp="FormatUang('reqNilaiRekam')" OnBlur="FormatUang('reqNilaiRekam')" value="<?=$reqNilaiRekam?>" />
                  <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqNilaiRekamValue" value="<?=$reqNilaiRekam?>" />
                </div>

                <div class="input-field col s3 m4">
                  
                  <select id="reqBidangJabatanTerkaitId" name="reqBidangJabatanTerkaitId">
                    <option value=""></option>
                    <?
                    // area untuk upload file
                    foreach ($arrbidangjabatanterkait as $key => $value)
                    {
                      $optionid= $value["id"];
                      $optiontext= $value["nama"];
                    ?>
                      <option value="<?=$optionid?>" <? if($reqBidangJabatanTerkaitId == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqBidangJabatanTerkaitId">Bidang Urusan</label>
                </div>

                <div class="input-field col s3 m2">
                    <label for="reqRumpunNamaKompetensi">Rumpun Kompetensi</label>
                    <input type="hidden" name="reqRumpunJabatan" id="reqRumpunJabatan" value="<?=$reqRumpunJabatan?>" />
                    <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunNamaKompetensi" value="<?=$reqRumpunNamaKompetensi?>" />
                </div>

                <!-- <div class="input-field col s12 m3" id="labelrumpunset">
                    <label for="reqRumpunJabatanNama">Rumpun Jabatan</label>
                    <input type="hidden" name="reqRumpunJabatan" id="reqRumpunJabatan" value="<?=$reqRumpunJabatan?>" />
                    <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunJabatanNama" value="<?=$reqRumpunJabatanNama?>" />
                </div>

                <div class="input-field col s12 m3" id="labelrumpunselect">
                  <select id="reqRumpunJabatanSelect">
                    <option value=""></option>
                    <?
                    while ($rumpun->nextRow())
                    {
                      $optionid= $rumpun->getField("RUMPUN_ID");
                      $optiontext= $rumpun->getField("KETERANGAN");
                    ?>
                      <option value="<?=$optionid?>" <? if($reqRumpunJabatan == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqRumpunJabatan">Rumpun Jabatan</label>
                </div> -->

              </div>

              <div class="row">
                <div class="input-field col s6 m6">
                  <label for="reqEofficeJabatan">Nama Jabatan Eoffice</label>
                  <input type="hidden" class="easyui-validatebox"  id="reqEofficeJabatanId" name="reqEofficeJabatanId" value="<?=$reqEofficeJabatanId?>" />
                  <input placeholder="" type="text" class="easyui-validatebox" id="reqEofficeJabatanNama" name="reqEofficeJabatanNama" value="<?=$reqEofficeJabatanNama?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqEofficeSatker">Satuan Kerja Eoffice</label>
                  <input type="hidden"  id="reqEofficeSatkerId" name="reqEofficeSatkerId" value="<?=$reqEofficeSatkerId?>" />
                  <input placeholder="" type="text" id="reqEofficeSatkerNama" name="reqEofficeSatkerNama" value="<?=$reqEofficeSatkerNama?>" />
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_jabatan_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <?
                  if($reqRowId == "")
                  {
                    // A;R;D
                    if($tempAksesMenu == "A")
                    {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                    }
                  }
                  else
                  {
                    if($infodatastatus ==1 ){}
                    else
                    {
                     if($infodatahukuman == 0)
                     {
                      if($tempAksesMenu == "A")
                      {
                        // rendy :: cek jika level user lebih kecil maka tombol simpan di sembunyikan
                        // if($sessionLoginLevel>=$reqLastLevel)
                        // {
                        ?>
                            <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                              <i class="mdi-content-save left hide-on-small-only"></i>
                            </button>
                  
                        <?
                        // }
                      }
					           }
				            }
				          }
                  ?>
                  
                  <?
                  if(!empty($reqRowId) && !empty($kirimsapk))
                  {
                  ?>
                  <button class="btn  waves-effect waves-light" style="font-size:9pt;background: #9C28B0;" type="button" id='buttonbtn'>
                    <input type="hidden" id="reqIdField" value="<?=$reqRowId?>" />
                    <input type="hidden" id="reqIdBkn" value="<?=$vidsapk?>" />
                    <input type="hidden" id="reqUrlBkn" value="jabatan_json" />
                     <i class="mdi-content-save left hide-on-small-only"></i> <span > UPDATE KE SIASN BKN</span>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  if(!empty($vidsapk) && !empty($lihatsapk))
                  {
                    $vdetilsapk= $vidsapk."___pegawai_add_jabatan_sapk_data";
                    $vdetillabelsapk= "Data SAPK BKN";
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id='buttondatasapk<?=$vdetilsapk?>'>
                    <input type="hidden" id="labelvsapk<?=$vdetilsapk?>" value="<?=$vdetillabelsapk?>" />
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

</div>
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>   

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<!-- tambahan lib cek tanggal -->
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  function getMonthDifference(startDate, endDate) {
    return (
      endDate.getMonth() -
      startDate.getMonth() +
      12 * (endDate.getFullYear() - startDate.getFullYear())
      );
  }

  $(document).ready(function() {
    $('select').material_select();
  });
  
	$('#reqTmtWaktuJabatan').formatter({
	'pattern': '{{99}}:{{99}}',
	});
  
  $('#reqTmtSpmt').keyup(function() {
    var reqTanggalSelesai= $('#reqTmtSpmt').val();
    var reqTmtJabatan= $('#reqTmtJabatan').val();
    var checktanggalselesai= moment(reqTanggalSelesai , 'DD-MM-YYYY', true).isValid();
    var checktmtjabatan= moment(reqTmtJabatan , 'DD-MM-YYYY', true).isValid();
    // console.log(checktanggalselesai+'_'+checktmtjabatan);
    if(checktanggalselesai == true && checktmtjabatan == true)
    {
      var tglselesai = moment(reqTanggalSelesai, 'DD-MM-YYYY');  // format tanggal
      var tmtjabatan = moment(reqTmtJabatan, 'DD-MM-YYYY'); 

      if (tglselesai.isSameOrAfter(tmtjabatan)) {} 
      else 
      {
         $('#reqTmtSpmt').val(reqTmtJabatan);
      }
    }

  });

  $('#reqTanggalSelesai').keyup(function() {
    var reqTanggalSelesai= $('#reqTanggalSelesai').val();
    var reqTmtJabatan= $('#reqTmtJabatan').val();
    var checktanggalselesai= moment(reqTanggalSelesai , 'DD-MM-YYYY', true).isValid();
    var checktmtjabatan= moment(reqTmtJabatan , 'DD-MM-YYYY', true).isValid();
    // console.log(checktanggalselesai+'_'+checktmtjabatan);
    if(checktanggalselesai == true && checktmtjabatan == true)
    {
      var tglselesai = moment(reqTanggalSelesai, 'DD-MM-YYYY');  // format tanggal
      var tmtjabatan = moment(reqTmtJabatan, 'DD-MM-YYYY'); 

      if (tglselesai.isSameOrAfter(tmtjabatan)) {} 
      else 
      {
         $('#reqTanggalSelesai').val(reqTmtJabatan);
      }

      // hitung ulang
      tglselesai= reqTanggalSelesai.substring(6,10)+"-"+reqTanggalSelesai.substring(3,5)+"-"+reqTanggalSelesai.substring(0,2);
      tmtjabatan= reqTmtJabatan.substring(6,10)+"-"+reqTmtJabatan.substring(3,5)+"-"+reqTmtJabatan.substring(0,2);
      // console.log(tglselesai+";"+tmtjabatan)
      totalbulan= getMonthDifference(new Date(tmtjabatan), new Date(tglselesai));
      totalbulan= parseInt(totalbulan / 12);
      // console.log(totalbulan);
      tambahbulan= 0;
      if(totalbulan > 2)
        tambahbulan= 1;

      vlamajabatanhitung= (parseInt(reqTanggalSelesai.substring(6,10)) - reqTmtJabatan.substring(6,10)) + tambahbulan;
      // $("#reqLamaMenjabatValue").val(vlamajabatanhitung);

      vnilairekam= 0;
      if(vlamajabatanhitung >= 5)
        vnilairekam= 100;
      else if(vlamajabatanhitung >= 4)
        vnilairekam= 80;
      else if(vlamajabatanhitung >= 3)
        vnilairekam= 60;
      else if(vlamajabatanhitung >= 2)
        vnilairekam= 40;
      else if(vlamajabatanhitung >= 1)
        vnilairekam= 20;

      $("#reqNilaiRekamValue").val(vnilairekam);
    }

    // $("#reqLamaMenjabatValue").val(10);

  });

  $('.materialize-textarea').trigger('autoresize');

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

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">


<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

</body>