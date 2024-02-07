<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('JabatanTambahan');
$this->load->model('PegawaiFile');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010402";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $set= new JabatanTambahan();
  $statement= " AND A.JABATAN_TAMBAHAN_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqRowId= $set->getField('JABATAN_TAMBAHAN_ID');
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
  
  //echo $reqStatusPlt;exit;
}

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "JABATAN_TAMBAHAN";
$reqDokumenKategoriFileId= "20"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
// print_r($arrsetriwayatfield);exit;

if(empty($reqRowId))
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru");
else
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId);

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
		//alert($("#reqIsManual").prop('checked'));return false;
		//$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqNamaTugas, #reqTugasTambahanId, #reqSatker, #reqSatkerId").val("");
			$("#reqSatker").attr("readonly", false);
		  //$("#reqinfoeselonselect").show();
		  //$("#reqSelectEselonId,#reqEselonId, #reqEselonText, #reqNama, #reqSatker, #reqSatkerId").val("");
		  //$("#reqSelectEselonId").material_select();
		  //$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqNamaTugas, #reqTugasTambahanId, #reqSatker, #reqSatkerId").val("");
			// $("#reqSatker").attr("readonly", true);
		  //$("#reqEselonId, #reqNama, #reqSatker, #reqSatkerId").val("");
		  //$("#reqinfoeselontext").show();
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
      settimetmt(1);
	  <?
	  if($reqRowId == "")
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
        url:'jabatan_tambahan_json/add',
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
              mbox.close();
              document.location.href= "app/loadUrl/app/pegawai_add_tugas_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
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

      setaktif();
	  $("#reqIsAktif").click(function () {
        setaktif();
      });
	  
	  $("#reqStatusPlt").change(function() { 
	  	//var reqStatusPlt= $("#reqStatusPlt").val();
		$("#reqNamaTugas,#reqTugasTambahanId,#reqSatker,#reqSatkerId").val("");
	  });

      //$('input[id^="reqPejabatPenetap"], input[id^="reqNamaTugas"], input[id^="reqSatker"]').autocomplete({
	  $('input[id^="reqPejabatPenetap"], input[id^="reqNamaTugas"], input[id^="reqSatker"]').each(function(){
  		$(this).autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";
		  
		if (id.indexOf('reqNamaTugas') !== -1 || id.indexOf('reqSatker') !== -1)
		{
			if($("#reqIsManual").prop('checked')) 
			{
				$("#reqSatker").attr("readonly", false);
				return false;
			}
		}

          if (id.indexOf('reqPejabatPenetap') !== -1)
          {
            var element= id.split('reqPejabatPenetap');
            var indexId= "reqPejabatPenetapId"+element[1];
            urlAjax= "pejabat_penetap_json/combo";
          }
          //else if (id.indexOf('reqNamaTugas') !== -1)
		  else if (id.indexOf('reqNamaTugas') !== -1)
          {
			var reqStatusPlt= $("#reqStatusPlt").val();
            var element= id.split('reqNamaTugas');
            reqTanggalBatas= $("#reqTanggalSk").val();
            urlAjax= "jabatan_tambahan_json/namajabatan?reqTanggalBatas="+reqTanggalBatas+"&reqStatusPlt="+reqStatusPlt;
          }
          else if (id.indexOf('reqSatker') !== -1)
          {
            var element= id.split('reqSatker');
            var indexId= "reqSatkerId"+element[1];
            reqTanggalBatas= $("#reqTmtTugas").val();
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
            //statusht= ui.item.statusht;
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
          //minLength:3,
          autoFocus: true
        })
		/*.data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.label + "</a>" )
			.appendTo( ul );
		  }*/
			/*.data('ui-autocomplete')._renderItem = function(ul, item) {
				return $('<li>')
				.append('<a>' + item.label  + '<br>' + item.label  + '</a><br><br>')
				.appendTo(ul);
		
		  }*/
		.autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
      }
	  ;
	  });
		
	  //$('input[id^="reqPejabatPenetap"], input[id^="reqNamaTugas"], input[id^="reqSatker"]').autocomplete( "search", "" );
	  //$('input[id^="reqPejabatPenetap"], input[id^="reqNamaTugas"], input[id^="reqSatker"]').autocomplete().clear();
	  
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
          <li class="collection-item ubah-color-warna">EDIT TUGAS</li>
          <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
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
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk">No. SK</label>
                  <input type="text" class="easyui-validatebox"  id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSk">Tgl. SK</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSk" id="reqTanggalSk"  value="<?=$reqTanggalSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSk');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <input type="checkbox" id="reqIsManual" name="reqIsManual" value="1" <? if($reqIsManual == 1) echo 'checked'?> />
                  <label for="reqIsManual"></label>
                  *centang jika nama Tugas luar kab jombang / Tugas sebelum tahun 2012
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaTugas">Nama Tugas</label>
                  <input type="text" class="easyui-validatebox"  id="reqNamaTugas" name="reqNamaTugas" <?=$disabled?> value="<?=$reqNamaTugas?>" />
                  <input type="hidden" name="reqTugasTambahanId" id="reqTugasTambahanId" value="<?=$reqTugasTambahanId?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTmtTugas">TMT Tugas</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtTugas" id="reqTmtTugas"  value="<?=$reqTmtTugas?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtTugas');"/>
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
                  <input type="text" id="reqSatker" name="reqSatker" <?=$read?> <?php /*?>readonly<?php */?> value="<?=$reqSatker?>" class="easyui-validatebox" required />
                  <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoPelantikan">No. Pelantikan</label>
                  <input type="text" class="easyui-validatebox"  id="reqNoPelantikan" name="reqNoPelantikan" <?=$disabled?> value="<?=$reqNoPelantikan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalPelantikan">Tgl. Pelantikan</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPelantikan" id="reqTanggalPelantikan"  value="<?=$reqTanggalPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPelantikan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTunjangan">Tunjangan</label>
                  <input type="text" class="easyui-validatebox" required id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqBlnDibayar">Bln. Dibayar</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBulanDibayar" id="reqBulanDibayar"  value="<?=$reqBulanDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqBulanDibayar');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap">Pejabat Penetap</label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <input type="checkbox" id="reqIsAktif" name="reqIsAktif" value="1" <? if($reqIsAktif == 1) echo 'checked'?> />
                  <label for="reqIsAktif"></label>
                  *centang jika sudah tidak aktif
                </div>
                <div class="input-field col s12 m2" id="reqInfoCheckTmtSelesai">
                  <label for="reqTmtJabatanAkhir">TMT Selesai Tugas</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtJabatanAkhir" id="reqTmtJabatanAkhir"  value="<?=$reqTmtJabatanAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatanAkhir');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_tugas_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <?
                  // A;R;D
                  if($tempAksesMenu == "A")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
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

                  <select id="reqDokumenPilih<?=$riwayatfield?>" name="reqDokumenPilih[]">
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
                  <select <?=$disabled?> name="reqDokumenFileKualitasId[]" id="reqDokumenFileKualitasId<?=$riwayatfield?>">
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
                  <select id="reqDokumenIndexId<?=$riwayatfield?>" name="reqDokumenIndexId[]">
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

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('#reqKredit').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
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

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>