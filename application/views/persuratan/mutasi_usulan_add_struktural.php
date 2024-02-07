<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/UsJabatanRiwayat');
$this->load->model('persuratan/MutasiUsulan');

$eselon= new MutasiUsulan();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisMutasiId= $this->input->get("reqJenisMutasiId");
$reqJenisJabatanTugasId= $this->input->get("reqJenisJabatanTugasId");
$reqMode= $this->input->get("reqMode");
$sessionLoginLevel= $this->LOGIN_LEVEL;

if($reqId=="")
{
  $reqMode = 'insert';

  if($reqJenisMutasiId == "1")
  {
    $arrJenisJabatanTugasId= array("11", "12");
    $arrJenisJabatanTugasNama= array("Jabatan Struktural", "Pelaksana");
  }
  elseif($reqJenisMutasiId == "2")
  {
    $arrJenisJabatanTugasId= array("21", "22", "29");
    $arrJenisJabatanTugasNama= array("JFT Pendidikan", "JFT Kesehatan", "Mutasi Intern Pelaksana");
  }

}
else
{
	$reqMode = 'update';
	$set= new UsJabatanRiwayat();
	$statement= " AND A.US_JABATAN_RIWAYAT_ID = ".$reqId;
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqId= $set->getField('US_JABATAN_RIWAYAT_ID');
	$reqIsManual= $set->getField('IS_MANUAL');
	$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
	$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$reqSatkerId= $set->getField('SATKER_ID');
	$reqNoSk= $set->getField('NO_SK');
	$reqEselonId= $set->getField('ESELON_ID');
	$reqEselonNama= $set->getField('ESELON_NAMA');
	$reqNama= $set->getField('NAMA');
	$reqSatkerId= $set->getField('SATKER_ID');
	$reqSatker= $set->getField('SATUAN_KERJA_NAMA_DETIL');
	$reqNoPelantikan= $set->getField('NO_PELANTIKAN');
	$reqTunjangan= $set->getField('TUNJANGAN');
	$reqTglSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));
  $reqTmtJabatan= dateTimeToPageCheck($set->getField('TMT_JABATAN'));
	$reqTmtEselon= dateTimeToPageCheck($set->getField('TMT_ESELON'));
	$reqTglPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));
	$reqBlnDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));
	$reqKeteranganBUP= $set->getField('KETERANGAN_BUP');
	
	$reqTmtWaktuJabatan= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);
	if($reqTmtWaktuJabatan == "" || $reqTmtWaktuJabatan == "00:00"){}
	else
	$reqCheckTmtWaktuJabatan= "1";

  $reqPegawaiId= $set->getField('PEGAWAI_ID');
  $reqSatkerAsalId= $set->getField('SATKER_ASAL_ID');
  $reqSatkerAsalNama= $set->getField('SATKER_ASAL_NAMA');
  $reqNipBaru= $set->getField('NIP_BARU');
  $reqNamaPegawai= $set->getField('NAMA_LENGKAP');
  $reqJenisMutasiId= $set->getField('JENIS_MUTASI_ID');
  $reqJenisMutasiNama= $set->getField('JENIS_MUTASI_NAMA');
  $reqJenisJabatanTugasId= $set->getField('JENIS_JABATAN_TUGAS_ID');
  $reqJenisJabatanTugasNama= $set->getField('JENIS_JABATAN_TUGAS_NAMA');
  $reqLastLevel= $set->getField('LAST_LEVEL');

  $reqStatusUsulan= $set->getField('STATUS_USULAN');
  $reqUtamaRowId= $set->getField('JABATAN_RIWAYAT_ID');

}

$reqJenisJabatan= 1;
$reqTipePegawaiId= 11;

$eselon->selectByParamsEselon(array());
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
	
  function seinfodatacentang()
	{
		$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqinfoeselonselect").show();
			$("#reqSatker").attr("readonly", false);
			$("#reqSelectEselonId").material_select();
			//$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqSatker").attr("readonly", true);
			$("#reqinfoeselontext").show();
		}
	}
	
  function setcetang()
	{
		//alert($("#reqIsManual").prop('checked'));return false;
		$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqinfoeselonselect").show();
			$("#reqSelectEselonId,#reqEselonId, #reqEselonText, #reqNama, #reqSatker, #reqSatkerId").val("");
			$("#reqSatker").attr("readonly", false);
			$("#reqSelectEselonId").material_select();
			//$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqSatker").attr("readonly", true);
			$("#reqEselonId, #reqNama, #reqSatker, #reqSatkerId").val("");
			$("#reqinfoeselontext").show();
		}
	}
	
  $(function(){
   $(".preloader-wrapper").hide();
	 settimetmt(1);
	 <?
	 if($reqId == "")
	 {
	 ?>
	 setcetang();
	 <?
	 }
	 else
	 {
	 ?>
	 seinfodatacentang();
	 <?
	 }
	 ?>

   $('#ff').form({
    url:'surat/mutasi_usulan_json/add_usulan_jabatan_riwayat',
    onSubmit:function(){
      var reqEselonId= "";
      reqEselonId= $("#reqEselonId").val();
      if(reqEselonId == "")
      {
       $.messager.alert('Info', "Lengkapi data Eselon terlebih dahulu", 'info');
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
         reloadparenttab();
         mbox.close();
         document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_struktural/?reqId="+rowid;
       }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }

    }
  });

   $("#reqCheckTmtWaktuJabatan").click(function () {
     settimetmt(2);
   });
	 
	 $("#reqIsManual").click(function () {
		setcetang();
	 });
	 
	 $('#reqSelectEselonId').bind('change', function(ev) {
     $("#reqEselonId").val($(this).val());
   });
	 
   $('input[id^="reqNipBaru"]').each(function(){
    $(this).autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqNipBaru') !== -1)
        {
          $(".preloader-wrapper").show();
          urlAjax= "surat/mutasi_usulan_json/cari_pegawai_usulan?reqTipePegawaiId=11";
        }

        $.ajax({
          url: urlAjax,
          type: "GET",
          dataType: "json",
          data: { term: request.term },
          success: function(responseData){
            $(".preloader-wrapper").hide();

            if(responseData == null)
            {
              response(null);
            }
            else
            {
              var array = responseData.map(function(element) {
                return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai']
            , satuankerjaid: element['satuankerjaid'], satuankerjanama: element['satuankerjanama']};
              });
              response(array);
            }
          }
        })
      },
      focus: function (event, ui) 
      { 
        var id= $(this).attr('id');
        if (id.indexOf('reqNipBaru') !== -1)
        {
        var indexId= "reqPegawaiId";
        var namapegawai= satuankerjaid= "";
        namapegawai= ui.item.namapegawai;
        satuankerjaid= ui.item.satuankerjaid;
        satuankerjanama= ui.item.satuankerjanama;

        $("#reqNamaPegawai").val(namapegawai);
        $("#reqSatkerAsalId").val(satuankerjaid);
        $("#reqSatkerAsalNama").val(satuankerjanama);
        }

        $("#"+indexId).val(ui.item.id).trigger('change');
      },
      autoFocus: true
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
    }
    ;
    });

   $('input[id^="reqPejabatPenetap"], input[id^="reqNama"]').each(function(){
    $(this).autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
        else if (id.indexOf('reqNama') !== -1)
        {
          var reqSatkerAsalId= reqPegawaiId= "";
          reqSatkerAsalId= $("#reqSatkerAsalId").val();
          reqPegawaiId= $("#reqPegawaiId").val();

          if(reqPegawaiId == "") 
          {
            $("#reqNamaId,#reqNama").val("");
            return false;
          }

          var element= id.split('reqNama');
          var indexId= "reqNamaId"+element[1];
          reqTanggalBatas= $("#reqTglSk").val();
          urlAjax= "satuan_kerja_json/cari_nama_jabatan_mutasi?reqTanggalBatas="+reqTanggalBatas+"&reqTipeJabatanId=x2&reqSatkerAsalId="+reqSatkerAsalId+"&reqPegawaiId="+reqPegawaiId;
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          reqTanggalBatas= $("#reqTmtJabatan").val();
          urlAjax= "satuan_kerja_json/auto?reqTanggalBatas="+reqTanggalBatas;
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
                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], eselon_id: element['eselon_id'], eselon_nama: element['eselon_nama']};
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
        else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqSatkerId"+element[1];
          $("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
          $("#reqEselonId").val(ui.item.eselon_id).trigger('change');
          $("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          $("#reqNama").val("").trigger('change');
        }

        $("#"+indexId).val(ui.item.id).trigger('change');
      },
      autoFocus: true
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
    }
    ;
    });

      $("#reqJenisMutasiId,#reqJenisJabatanTugasId").change(function() { 
        var reqJenisMutasiId= reqJenisJabatanTugasId= "";
        reqJenisMutasiId= $("#reqJenisMutasiId").val();
        reqJenisJabatanTugasId= $("#reqJenisJabatanTugasId").val();
        // alert(reqJenisMutasiId);

        if(reqJenisMutasiId == "1")
        {
          if(reqJenisJabatanTugasId == "11")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_struktural?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "12")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_fungsional?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId;
        }
        else if(reqJenisMutasiId == "2")
        {
          if(reqJenisJabatanTugasId == "21")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_jft_pendidikan?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "22")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_jft_kesehatan?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "29")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_intern_pelaksana?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId;
        }
        else
        {
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add";
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
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">USUL MUTASI UNIT KERJA</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <?
                  if($reqId == "")
                  {
                  ?>
                  <select id="reqJenisMutasiId" name="reqJenisMutasiId" >
                    <option value="" <? if($reqJenisMutasiId == "") echo 'selected';?>></option>
                    <option value="1" <? if($reqJenisMutasiId == 1) echo 'selected';?>>Mutasi Struktural / Pelaksana</option>
                    <option value="2" <? if($reqJenisMutasiId == 2) echo 'selected';?>>Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana</option>
                  </select>
                  <label for="reqJenisMutasiId">Jenis Mutasi</label>
                  <?
                  }
                  else
                  {
                  ?>
                  <label for="reqJenisMutasiId">Jenis Mutasi</label>
                  <input name="reqJenisMutasiId" type="hidden" value="<?=$reqJenisMutasiId?>" />
                  <input required type="text" value="<?=$reqJenisMutasiNama?>" disabled />
                  <?
                  }
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <?
                  if($reqId == "")
                  {
                  ?>
                  <select id="reqJenisJabatanTugasId" name="reqJenisJabatanTugasId" >
                    <option value="" <? if($reqJenisJabatanTugasId == "") echo 'selected';?>></option>
                    <?
                    for($i=0; $i < count($arrJenisJabatanTugasId); $i++)
                    {
                    ?>
                    <option value="<?=$arrJenisJabatanTugasId[$i]?>" <? if($arrJenisJabatanTugasId[$i] == $reqJenisJabatanTugasId) echo 'selected';?>><?=$arrJenisJabatanTugasNama[$i]?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqJenisJabatanTugasId">Jenis Jabatan / Jenis Tugas</label>
                  <?
                  }
                  else
                  {
                  ?>
                  <label for="reqJenisJabatanTugasId">Jenis Jabatan / Jenis Tugas</label>
                  <input name="reqJenisJabatanTugasId" type="hidden" value="<?=$reqJenisJabatanTugasId?>" />
                  <input required type="text" value="<?=$reqJenisJabatanTugasNama?>" disabled />
                  <?
                  }
                  ?>
                </div>
              </div>

              <div class="row">
                  <div class="input-field col s12 m6">
                    <input type="hidden" name="reqJenisJabatan" value="<?=$reqJenisJabatan?>" />
                    <input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqSatkerAsalId" id="reqSatkerAsalId" value="<?=$reqSatkerAsalId?>" />
                    <label for="reqNipBaru">NIP Baru</label>
                    <?
                    if($reqId == "")
                    {
                    ?>
                    <input placeholder="" required id="reqNipBaru" class="easyui-validatebox" type="text" value="<?=$reqNipBaru?>" />
                    <?
                    }
                    else
                    {
                    ?>
                    <input id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
                    <input required type="text" value="<?=$reqNipBaru?>" disabled />
                    <?
                    }
                    ?>
                  </div>
                  <div class="input-field col s12 m6">
                      <label for="reqNamaPegawai" class="active">Nama</label>
                      <input placeholder="" id="reqNamaPegawai" class="easyui-validatebox" type="text" value="<?=$reqNamaPegawai?>" disabled />
                  </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                    <label for="reqSatkerAsalNama" class="active">Unit Kerja Asal</label>
                    <input placeholder="" id="reqSatkerAsalNama" class="easyui-validatebox" type="text" value="<?=$reqSatkerAsalNama?>" disabled />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk">No. SK</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSk">Tgl. SK</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk"  value="<?=$reqTglSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNama">Nama Jabatan</label>
                  <input placeholder="" type="text" id="reqNama"  name="reqNama" <?=$read?> value="<?=$reqNama?>" class="easyui-validatebox" required />
                </div>
                <div class="input-field col s12 m1">
                    <input type="checkbox" id="reqCheckTmtWaktuJabatan" name="reqCheckTmtWaktuJabatan" value="1" <? if($reqCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
                    <label for="reqCheckTmtWaktuJabatan"></label>
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTmtJabatan">TMT Jabatan</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtJabatan" id="reqTmtJabatan"  value="<?=$reqTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatan');"/>
                </div>
                <div class="input-field col s12 m2" id="reqInfoCheckTmtWaktuJabatan">
                	<input placeholder="00:00" id="reqTmtWaktuJabatan" name="reqTmtWaktuJabatan" type="text" class="" value="<?=$reqTmtWaktuJabatan?>" />
                    <label for="reqTmtWaktuJabatan">Time</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqSatker">Satuan Kerja</label>
                  <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                  <input placeholder="" type="text" id="reqSatker" name="reqSatker" <?=$read?> value="<?=$reqSatker?>" class="easyui-validatebox" required readonly />
                </div>
              </div>

              <div class="row">
                <input type="hidden" name="reqEselonId" id="reqEselonId" value="<?=$reqEselonId?>" />
                <div class="input-field col s12 m6" id="reqinfoeselontext">
                  <label for="reqEselonText">Eselon</label>
                  <input placeholder="" type="text" id="reqEselonText" value="<?=$reqEselonNama?>" disabled />
                </div>
                
                <div class="input-field col s12 m6" id="reqinfoeselonselect">
                  <select id="reqSelectEselonId">
                    <option value=""></option>
                  	<?
          					while($eselon->nextRow())
          					{
                    ?>
                    	<option value="<?=$eselon->getField("ESELON_ID")?>" <? if($eselon->getField("ESELON_ID") == $reqEselonId) echo "selected"?>><?=$eselon->getField("NAMA")?></option>
                    <?
					          }
                    ?>
                  </select>
                  <label for="reqEselonId">Eselon</label>
                </div>
                
                <div class="input-field col s12 m6">
                  <label for="reqTmtEselon">TMT Eselon</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtEselon" id="reqTmtEselon" value="<?=$reqTmtEselon?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtEselon');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoPelantikan">No. Pelantikan</label>
                  <input placeholder="" type="text" id="reqNoPelantikan" name="reqNoPelantikan" <?=$disabled?> value="<?=$reqNoPelantikan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglPelantikan">Tgl. Pelantikan</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglPelantikan" id="reqTglPelantikan"  value="<?=$reqTglPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqTglPelantikan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTunjangan">Tunjangan</label>
                  <input placeholder="" type="text" id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqBlnDibayar">Bln. Dibayar</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBlnDibayar" id="reqBlnDibayar"  value="<?=$reqBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqBlnDibayar');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap">Pejabat Penetap</label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input placeholder="" type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                    <div class="file_input_div">
                      <div class="file_input input-field col s12 m1">
                        <label class="labelupload">
                          <i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
                          <input id="file_input_file" name="reqLinkFile" class="none" type="file" />
                        </label>
                      </div>
                      <div id="file_input_text_div" class=" input-field col s12 m11">
                        <input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                        <label for="file_input_text"></label>
                      </div>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <?
                  $tempUrlFile= "uploadsurat/mutasi_struktural/".$reqId.".pdf";
                  if(file_exists($tempUrlFile))
                  {
                  ?>
                  <!-- onClick="parent.setload('surat_masuk_add_data_agenda_file?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqUrlFile=<?=$tempUrlFile?>')" -->
                    <button class="btn waves-effect waves-light purple" style="font-size:9pt" type="button" id='buttonframepdf'>
                      <input type="hidden" id="labelvpdf" value="Lihat File" />
                      <span id="labelframepdf">Lihat File</span>
                      <i class="mdi-editor-attach-file left hide-on-small-only"></i>
                    </button>
                  <?
                  }
                  ?>


                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      closeparenttab();
                    });

                    function closeparenttab()
                    {
                    if (window.opener && window.opener.document)
                        {
                            if (typeof window.opener.setCariInfo === 'function')
                            {
                                window.opener.setCariInfo();
                            }
                        }
                        window.close();
                    }

                    function reloadparenttab()
                    {
                    if (window.opener && window.opener.document)
                      {
                        if (typeof window.opener.setCariInfo === 'function')
                        {
                          window.opener.setCariInfo();
                        }
                      }
                    }
                  </script>

                  <input type="hidden" name="reqUtamaRowId" value="<?=$reqUtamaRowId?>" />
                  <input type="hidden" name="reqTipePegawaiId" value="<?=$reqTipePegawaiId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <?
                  if($reqStatusUsulan == "")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  // if($sessionLoginLevel >= $reqLastLevel)
                  if($sessionLoginLevel >= 30)
                  {
                    if($reqStatusUsulan == "")
                    {
                      if($reqId == ""){}
                      else
                      {
                  ?>
                  <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="reqkirim">Valid
                      <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <button class="btn black waves-effect waves-light" style="font-size:9pt" type="button" id="reqbatal">Tolak
                    <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <?
                      }
                    }
                  }
                  ?>

                </div>
              </div>

            </form>
          </li>
        </ul>
      </div>

      <div id='divframepdf' class="col s12 m6" style="padding-top: 10px;">
        <input type="hidden" id="vnewframe" value="">
        <span id="labelriwayatframepdf"></span>
        <!-- <img id="infonewimage" style="width:inherit; width: 100%; height: 100%" /> -->
        <iframe id="infonewframe" style="width: 100%; height: 160%" src=""></iframe>
      </div>

    </div>
  </div>

</div>

<div class="preloader-wrapper big active loader">
  <div class="spinner-layer spinner-blue-only">
    <div class="circle-clipper left">
      <div class="circle"></div>
    </div><div class="gap-patch">
      <div class="circle"></div>
    </div><div class="circle-clipper right">
      <div class="circle"></div>
    </div>
  </div>
</div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>   

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqTmtWaktuJabatan').formatter({
	'pattern': '{{99}}:{{99}}',
	});
	
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

    $("#reqkirim").click(function() { 

      mbox.custom({
       message: "Apakah Anda Yakin, valid. Pastikan entri data sudah sesuai ?",
       options: {close_speed: 100},
       buttons: [
       {
         label: 'Ya',
         color: 'green darken-2',
         callback: function() {
          var s_url= "surat/mutasi_usulan_json/valid?reqJenisJabatanTugasId=<?=$reqJenisJabatanTugasId?>&reqId=<?=$reqId?>";
           $.ajax({'url': s_url,'success': function(dataajax){
            // var requrl= requrllist= "";
            dataajax= String(dataajax);
            var element = dataajax.split('-'); 
            // dataajax= element[0];
            info= element[1];
            // requrllist= element[2];
            // requrlcss= element[3];
            mbox.alert(info, {open_speed: 500}, interval = window.setInterval(function() 
            {
              clearInterval(interval);
              reloadparenttab();
              mbox.close();
              document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_struktural/?reqId=<?=$reqId?>";
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }});
           mbox.close();
         }
       },
       {
         label: 'Tidak',
         color: 'grey darken-2',
         callback: function() {
           mbox.close();
         }
       }
       ]
     });

    });

    $("#reqbatal").click(function() { 
      infomessage= "Apakah Anda Yakin, tolak data.<br/>Entri alasan tolak ?";
      infomessage+= '<input placeholder="" id="reqalasan" class="easyui-validatebox" type="text" />';
      mbox.custom({
        message: infomessage,
        options: {close_speed: 100},
        buttons: [
        {
          label: 'Ya',
          color: 'green darken-2',
          callback: function() {
            reqalasan= $("#reqalasan").val();
            // if(reqalasan.length == 0)
            if(!reqalasan)
            {
              mbox.alert("Lengkapi alasan tolak terlebih dahulu", {open_speed: 0});
              return false;
            }
            else
            {
              var s_url= "surat/mutasi_usulan_json/reject?reqJenisJabatanTugasId=<?=$reqJenisJabatanTugasId?>&reqId=<?=$reqId?>&reqAlasan="+encodeURIComponent(reqalasan);
               $.ajax({'url': s_url,'success': function(dataajax){
                // console.log(dataajax);return false;
                dataajax= String(dataajax);
                var element = dataajax.split('-'); 
                info= element[1];
                mbox.alert(info, {open_speed: 500}, interval = window.setInterval(function() 
                {
                  clearInterval(interval);
                  reloadparenttab();
                  mbox.close();
                  document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_struktural/?reqId=<?=$reqId?>";
                }, 1000));
                $(".mbox > .right-align").css({"display": "none"});
              }});
            }
          }
        },
        {
          label: 'Tidak',
          color: 'grey darken-2',
          callback: function() {
            mbox.close();
          }
        }
       ]
      });

    });

      var fileInputTextDiv = document.getElementById('file_input_text_div');
      var fileInput = document.getElementById('file_input_file');
      var fileInputText = document.getElementById('file_input_text');

      fileInput.addEventListener('change', changeInputText);
      fileInput.addEventListener('change', changeState);

      function changeInputText() {
        var str = fileInput.value;
        var i;
        if (str.lastIndexOf('\\')) {
          i = str.lastIndexOf('\\') + 1;
        } else if (str.lastIndexOf('/')) {
          i = str.lastIndexOf('/') + 1;
        }
        fileInputText.value = str.slice(i, str.length);
      }

      function changeState() {
        if (fileInputText.value.length != 0) {
          if (!fileInputTextDiv.classList.contains("is-focused")) {
            fileInputTextDiv.classList.add('is-focused');
          }
        } else {
          if (fileInputTextDiv.classList.contains("is-focused")) {
            fileInputTextDiv.classList.remove('is-focused');
          }
        }
      }
    });
</script>

<style type="text/css">

.file_input_div {
  margin-top: -30px;
  /*margin: auto;*/

  /*width: 250px;*/

  /*height: 40px;*/
}

.labelupload {
  margin-left: -12px;
}

.file_input {
  /*float: left;*/
}

#file_input_text_div {
  /*width: 200px;*/
  /*margin-top: -22px;*/
  /*margin-top: 28px;*/
  /*margin-left: 5px;*/
}

.none {
  display: none;
}

</style>

<script type="text/javascript">
  vbase_url= "<?=base_url()?>";

  $("#vnewframe").val("");
  $('#divframepdf').hide();
  $('[id^="buttonframepdf"]').click(function(){
    infoid= $(this).attr('id');
    infoid= infoid.replace("buttonframepdf", "");
    buttonframepdf(infoid);
  });

  function buttonframepdf(infoid) {
    // $('[id^="buttonframepdf"]').hide();

    var element = document.getElementById("main");
    if ($("#divframepdf").css("visibility") == "hidden" || $('#divframepdf').is(':hidden')) {
      element.classList.remove("m12");
      element.classList.add("m6");
      $('#divframepdf').show();
      $("#vnewframe").val(infoid);

      labelvpdf= $("#labelvpdf"+infoid).val();
      $("#labelframepdf"+infoid).text("Tutup " + labelvpdf);
      $("#buttonframepdf"+infoid).show();

      vurl= "<?=$tempUrlFile?>";
      $("#infonewframe").show();
      var infonewframe= $('#infonewframe');
      infourl= vbase_url+'/lib/pdfjs/web/viewer.html?file=../../../'+vurl;
      // console.log(infourl);

      vnewframe= $("#vnewframe").val();
      infonewframe.attr("src", infourl);
      if(vnewframe == ""){}
      else
      {
        infonewframe.contentWindow.location.reload();
      }
    }
    else
    {
      labelvpdf= $("#labelvpdf"+infoid).val();
      $("#labelframepdf"+infoid).text(labelvpdf);

      element.classList.remove("m6");
      element.classList.add("m12");
      $('#divframepdf').hide();
      $("#vnewframe").val("");
    }
  }
</script>
</body>