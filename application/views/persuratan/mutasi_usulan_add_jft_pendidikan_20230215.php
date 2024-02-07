<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/UsJabatanTambahan');

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
  $set= new UsJabatanTambahan();
  $statement= " AND A.US_JABATAN_TAMBAHAN_ID = ".$reqId;
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqId= $set->getField('US_JABATAN_TAMBAHAN_ID');
  $reqNamaTugas= $set->getField('NAMA');
  $reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
  $reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
  $reqNoSk= $set->getField('NO_SK');
  $reqTanggalSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));
  $reqTmtTugas= dateTimeToPageCheck($set->getField('TMT_JABATAN'));
  $reqTmtJabatanAkhir= dateTimeToPageCheck($set->getField('TMT_JABATAN_AKHIR'));
  $reqTugasTambahanId= $set->getField('TUGAS_TAMBAHAN_ID');
  $reqStatusPlt= $set->getField('STATUS_PLT');
  $reqSatker= $set->getField('SATKER_NAMA');
  $reqSatkerId= $set->getField('SATKER_ID');
  
  $reqIsManual= $set->getField('IS_MANUAL');
  $reqNoPelantikan= $set->getField('NO_PELANTIKAN');
  $reqTanggalPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));
  $reqTunjangan= $set->getField('TUNJANGAN');
  $reqBulanDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));
  //$reqTmtTugas= $set->getField('SATKER_ID');
  //$reqTmtWaktuTugas= $set->getField('SATKER_ID');
  $reqTmtWaktuTugas= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);
  if($reqTmtWaktuTugas == "" || $reqTmtWaktuTugas == "00:00"){}
  else
  $reqCheckTmtWaktuTugas= "1";
  
  if($reqTmtJabatanAkhir == ""){}
  else
  $reqIsAktif= "1";

  $reqIsDpkManual= $set->getField('IS_DPK_MANUAL');
  $reqSatkerDpkNama= $set->getField('SATKER_DPK_NAMA');
  // reqIsDpkManual;reqSatkerDpkNama

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
  // echo $sessionLoginLevel." > ".$reqLastLevel;exit;
  $reqUtamaRowId= $set->getField('JABATAN_TAMBAHAN_ID');
  
  //echo $reqStatusPlt;exit;
}

$reqStatusPlt= "21";
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
		$("#reqInfoCheckTmtWaktuTugas").hide();
		if($("#reqCheckTmtWaktuTugas").prop('checked')) 
		{
			$("#reqInfoCheckTmtWaktuTugas").show();
		}
		else
		{
			if(info == 2)
			$("#reqTmtWaktuTugas").val("");
		}
	}
	
	function setcetang()
	{
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqNamaTugas, #reqTugasTambahanId, #reqSatker, #reqSatkerId").val("");
			$("#reqSatker").attr("readonly", false);
		}
		else
		{
			$("#reqNamaTugas, #reqTugasTambahanId, #reqSatker, #reqSatkerId").val("");
			// $("#reqSatker").attr("readonly", true);
		}
	}

  function setdpkcetang()
  {
    if($("#reqIsDpkManual").prop('checked')) 
    {
      $("#reqDpkSatkerNama").val("");
      $("#reqDpkSatkerNama").attr("readonly", false);
    }
    else
    {
      $("#reqDpkSatkerNama").val("");
      $("#reqDpkSatkerNama").attr("readonly", true);
    }
  }
	
	function seinfodatacentang()
	{
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqSatker").attr("readonly", false);
		}
		else
		{
			// $("#reqSatker").attr("readonly", true);
		}
	}

  function seinfodatacentangdpk()
  {
    if($("#reqIsDpkManual").prop('checked')) 
    {
      $("#reqDpkSatkerNama").attr("readonly", false);
    }
    else
    {
      $("#reqDpkSatkerNama").attr("readonly", true);
    }
  }
	
	function setaktif()
	{
		if($("#reqIsAktif").prop('checked')) 
		{
		  $("#reqInfoCheckTmtSelesai").show();
		}
		else
		{
		  $("#reqInfoCheckTmtSelesai").hide();
		  $("#reqTmtJabatanAkhir").val('');
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
    setdpkcetang();
	  <?
	  }
	  else
	  {
	  ?>
	  seinfodatacentang();
    seinfodatacentangdpk();
	  <?
	  }
	  ?>

    $('#ff').form({
    url:'surat/mutasi_usulan_json/add_tugas',
    onSubmit:function(){
        var reqStatusPlt= $("#reqStatusPlt").val();

        if(reqStatusPlt == "")
        {
          mbox.alert("Lengkapi data Jenis Tugas terlebih dahulu", {open_speed: 0});
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
              document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_jft_pendidikan/?reqId="+rowid;
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
          
        }
    });

    $("#reqCheckTmtWaktuTugas").click(function () {
     settimetmt(2);
    });
	 
    $("#reqIsManual").click(function () {
      setcetang();
    });

    $("#reqIsDpkManual").click(function () {
      setdpkcetang();
    });

    setaktif();
    $("#reqIsAktif").click(function () {
      setaktif();
    });
	  
	  $("#reqStatusPlt").change(function() { 
  		$("#reqNamaTugas,#reqTugasTambahanId,#reqSatker,#reqSatkerId").val("");
	  });

    $('input[id^="reqNipBaru"]').each(function(){
    $(this).autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqNipBaru') !== -1)
        {
          $(".preloader-wrapper").show();
          $("#reqSatkerId,#reqSatker").val("");
          urlAjax= "surat/mutasi_usulan_json/cari_pegawai_usulan";
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

    $('input[id^="reqPejabatPenetap"], input[id^="reqNamaTugas"], input[id^="reqSatker"]').each(function(){
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
        else if (id.indexOf('reqNamaTugas') !== -1)
        {
          var reqStatusPlt= $("#reqStatusPlt").val();
          var element= id.split('reqNamaTugas');
          reqTanggalBatas= $("#reqTanggalSk").val();
          urlAjax= "jabatan_tambahan_json/namajabatan?reqTanggalBatas="+reqTanggalBatas+"&reqStatusPlt="+reqStatusPlt;
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var reqSatkerAsalId= reqPegawaiId= "";
          reqSatkerAsalId= $("#reqSatkerAsalId").val();
          reqPegawaiId= $("#reqPegawaiId").val();

          if(reqPegawaiId == "") 
          {
            $("#reqSatkerId,#reqSatker").val("");
            return false;
          }

          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          reqTanggalBatas= $("#reqTmtTugas").val();
          urlAjax= "satuan_kerja_json/cari_satuan_kerja_mutasi?reqTanggalBatas="+reqTanggalBatas+"&reqSatkerAsalId="+reqSatkerAsalId+"&reqPegawaiId="+reqPegawaiId;
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
                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], satuan_kerja_id: element['satuan_kerja_id'], satuan_kerja_validasi: element['satuan_kerja_validasi']};
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
          else if (id.indexOf('reqNamaTugas') !== -1)
          {
            var element= id.split('reqNamaTugas');
            var indexId= "reqTugasTambahanId";
          }
          else if (id.indexOf('reqSatker') !== -1)
          {
            var element= id.split('reqSatker');
            var indexId= "reqSatkerId"+element[1];
            //$("#reqNama").val("").trigger('change');
          }

          var statusht= "";
          $("#"+indexId).val(ui.item.id).trigger('change');
          if (id.indexOf('reqNamaTugas') !== -1)
          {
            var reqStatusPlt= $("#reqStatusPlt").val();

            $("#reqSatker").attr("readonly", true);
            if(reqStatusPlt == "plt"){}
            else
            {
              if(ui.item.satuan_kerja_validasi == "1")
              {
                $("#reqSatker").attr("readonly", false);
              }
            }

            $("#reqSatkerId").val(ui.item.satuan_kerja_id).trigger('change');
            $("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
          }
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

            <div class="row">
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
                      <input type="hidden" name="reqStatusPlt" id="reqStatusPlt" value="<?=$reqStatusPlt?>" />
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

                <!-- <div class="row">
                  <div class="input-field col s6 m6">
                    <select name="reqStatusPlt" id="reqStatusPlt">
                      <option value="" <? if($reqStatusPlt == "") echo "selected"?>></option>
                      <option value="plt" <? if($reqStatusPlt == "plt") echo "selected"?>>Plt.</option>
                      <option value="plh" <? if($reqStatusPlt == "plh") echo "selected"?>>Plh.</option>
                      <option value="21" <? if($reqStatusPlt == "21") echo "selected"?>>Pendidikan</option>
                      <option value="22" <? if($reqStatusPlt == "22") echo "selected"?>>Kesehatan</option>
                      <option value="23" <? if($reqStatusPlt == "23") echo "selected"?>>Lainnya</option>
                    </select>
                    <label for="reqJenisTugas">Jenis Tugas</label>
                  </div>
                </div> -->

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNoSk">No. SK</label>
                    <input placeholder="" type="text" class="easyui-validatebox"  id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggalSk">Tgl. SK</label>
                    <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSk" id="reqTanggalSk"  value="<?=$reqTanggalSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSk');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNamaTugas">Nama Tugas</label>
                    <input placeholder="" type="text" class="easyui-validatebox"  id="reqNamaTugas" name="reqNamaTugas" <?=$disabled?> value="<?=$reqNamaTugas?>" />
                    <input type="hidden" name="reqTugasTambahanId" id="reqTugasTambahanId" value="<?=$reqTugasTambahanId?>" />
                  </div>
                  <div class="input-field col s12 m2">
                    <label for="reqTmtTugas">TMT Tugas</label>
                    <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtTugas" id="reqTmtTugas"  value="<?=$reqTmtTugas?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtTugas');"/>
                  </div>
                  <div class="input-field col s12 m1">
                    <input type="checkbox" id="reqCheckTmtWaktuTugas" name="reqCheckTmtWaktuTugas" value="1" <? if($reqCheckTmtWaktuTugas == 1) echo 'checked'?>/>
                    <label for="reqCheckTmtWaktuTugas"></label>
                  </div>
                  <div class="input-field col s12 m1" id="reqInfoCheckTmtWaktuTugas">
                  	<input placeholder="00:00" id="reqTmtWaktuTugas" name="reqTmtWaktuTugas" type="text" class="" value="<?=$reqTmtWaktuTugas?>" />
                      <label for="reqTmtWaktuTugas">Time</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m12">
                    <label for="reqSatker">Satuan Kerja</label>
                    <input placeholder="" type="text" id="reqSatker" name="reqSatker" <?=$read?> <?php /*?>readonly<?php */?> value="<?=$reqSatker?>" class="easyui-validatebox" required />
                    <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                  </div>
                </div>


                <div class="row">
                  <div class="input-field col s12">
                    <input type="checkbox" id="reqIsDpkManual" name="reqIsDpkManual" value="1" <? if($reqIsDpkManual == 1) echo 'checked'?> />
                    <label for="reqIsDpkManual"></label>
                    *centang jika sebagai DPK
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <label for="reqSatkerDpkNama">Satuan Kerja untuk DPK</label>
                    <input placeholder="" type="text" class="easyui-validatebox"  id="reqDpkSatkerNama" name="reqSatkerDpkNama" <?=$disabled?> value="<?=$reqSatkerDpkNama?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNoPelantikan">No. Pelantikan</label>
                    <input placeholder="" type="text" class="easyui-validatebox"  id="reqNoPelantikan" name="reqNoPelantikan" <?=$disabled?> value="<?=$reqNoPelantikan?>" />
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggalPelantikan">Tgl. Pelantikan</label>
                    <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPelantikan" id="reqTanggalPelantikan"  value="<?=$reqTanggalPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPelantikan');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqTunjangan">Tunjangan</label>
                    <input placeholder="" type="text" class="easyui-validatebox" required id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqBlnDibayar">Bln. Dibayar</label>
                    <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBulanDibayar" id="reqBulanDibayar"  value="<?=$reqBulanDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqBulanDibayar');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m12">
                    <label for="reqPejabatPenetap">Pejabat Penetap</label>
                    <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                    <input placeholder="" type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                  </div>
                </div>

                <!-- <div class="row">
                  <div class="input-field col s12 m6">
                    <input type="checkbox" id="reqIsAktif" name="reqIsAktif" value="1" <? if($reqIsAktif == 1) echo 'checked'?> />
                    <label for="reqIsAktif"></label>
                    *centang jika sudah tidak aktif
                  </div>
                  <div class="input-field col s12 m2" id="reqInfoCheckTmtSelesai">
                    <label for="reqTmtJabatanAkhir">TMT Selesai Tugas</label>
                    <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtJabatanAkhir" id="reqTmtJabatanAkhir"  value="<?=$reqTmtJabatanAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatanAkhir');"/>
                  </div>
                </div> -->

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
                    $tempUrlFile= "uploadsurat/mutasi_tugas_pendidikan/".$reqId.".pdf";
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
                    if($sessionLoginLevel >= $reqLastLevel)
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
            </div>
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

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqKredit').bind('keyup paste', function(){
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
            // console.log(dataajax);return false;
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
              document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_jft_pendidikan/?reqId=<?=$reqId?>";
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
                  document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_jft_pendidikan/?reqId=<?=$reqId?>";
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