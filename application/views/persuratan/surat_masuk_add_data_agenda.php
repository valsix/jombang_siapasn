<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkdDisposisi');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukBkdDisposisiKeterangan');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$tahunIni= date("Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;
$tempSatuanKerjaBkdId= $this->SATUAN_KERJA_BKD_ID;
$tempStatusMenuKhusus= $this->STATUS_MENU_KHUSUS;

if($reqId=="")
{
	$reqMode = 'insert';
	$reqSatuanKerjaTujuanId= $reqSatuanKerjaId;
	$reqKepada= $this->SATUAN_KERJA_LOGIN_KEPALA_JABATAN;
	//echo $reqKepada."--".$reqSatuanKerjaId;exit;
	
	// $statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahunIni."'";

	$statement= " 
	AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT A.NO_AGENDA, A.TANGGAL
			FROM PERSURATAN.SURAT_MASUK_BKD A
			INNER JOIN PERSURATAN.SURAT_MASUK_BKD_DISPOSISI B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
			WHERE SURAT_AWAL = 1 
			AND '".$tahunIni."' = TO_CHAR(B.TANGGAL, 'YYYY')
		) X
		WHERE A.NO_AGENDA = X.NO_AGENDA
		AND A.TANGGAL = X.TANGGAL
	)
	AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahunIni."'
	";

	$set= new SuratMasukBkdDisposisi();
	$set->selectByParamsNoAgenda($statement);
	// echo $set->query;exit();
	$set->firstRow();
	$reqNomorAgenda= $set->getField("NO_AGENDA_BARU");
	if($reqNomorAgenda == "")
	$reqNomorAgenda= "1";

	$reqTanggalTerima= date("d-m-Y");
	// $reqNomorAgenda= "0001";

	//echo $reqNomorAgenda;exit;
}
else
{
   $reqMode = 'update';
   $statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
   $set= new SuratMasukBkdDisposisi();
   $set->selectByParamsDataSuratLookup(array(), -1, -1, $reqSatuanKerjaId, $reqId, "", $statement);
   // $set->selectByParamsDataSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
   $set->firstRow();
   // echo $set->query;exit;
   
   $reqRowId= $set->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
   $reqJenis= $reqJenisId= $set->getField("JENIS_ID");
   $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
   $reqSatuanKerjaDiteruskanKepadaId= $set->getField("SATUAN_KERJA_DITERUSKAN_ID");
   $reqSatuanKerjaDiteruskanKepada= $set->getField("SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA");
   $reqNomor= $set->getField("NOMOR");
   $reqValNomorAgenda= $set->getField("VAL_NO_AGENDA");
   // $reqNomorAgenda= $set->getField("NO_AGENDA");
   $reqNomorAgenda= $reqValNomorAgenda;

   $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
   $reqTanggalTerima= datetimeToPage($set->getField("TANGGAL_TERIMA"), "date");
   $reqTanggalDisposisi= $set->getField("TANGGAL_DISPOSISI");
   $reqPerihal= $set->getField("PERIHAL");
   $reqTerdisposisi= $set->getField("TERDISPOSISI");
   $reqSuratAwal= $set->getField("SURAT_AWAL");
   $reqBatasSatuanKerjaCariId= $set->getField("BATAS_SATUAN_KERJA_CARI_ID");
   $reqIsi= $set->getField("ISI");
   
   $reqTerbaca= $set->getField("TERBACA");
   $reqTerbacaDisposisi= $set->getField("TERBACA_DISPOSISI");
   $reqStatusKelompokPegawaiUsul= $set->getField("STATUS_KELOMPOK_PEGAWAI_USUL");
   
   $reqPosisiTeknis= $set->getField("POSISI_TEKNIS");

   $reqKategori= $set->getField("KATEGORI");
   $reqKategoriNama= $set->getField("KATEGORI_NAMA");

   if(empty($reqValNomorAgenda))
   {
	   // tambahan kode
	   $statement= " 
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT A.NO_AGENDA, A.TANGGAL
					FROM PERSURATAN.SURAT_MASUK_BKD A
					INNER JOIN PERSURATAN.SURAT_MASUK_BKD_DISPOSISI B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
					WHERE SURAT_AWAL = 1 
					AND '".$tahunIni."' = TO_CHAR(B.TANGGAL, 'YYYY')
				) X
				WHERE A.NO_AGENDA = X.NO_AGENDA
				AND A.TANGGAL = X.TANGGAL
			)
			AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahunIni."'
			";

			$set= new SuratMasukBkdDisposisi();
			$set->selectByParamsNoAgenda($statement);
			// echo $set->query;exit();
			$set->firstRow();
			$reqNomorAgenda= $set->getField("NO_AGENDA_BARU");
			if($reqNomorAgenda == "")
			$reqNomorAgenda= "1";
	}

   
   if($reqJenisId == "")
   {
	  $tempPerihalInfo= $reqPerihal;
   }
   else
   {
	  /*$statement= " AND SMP.JENIS_ID = ".$reqJenisId." AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
	  $set_detil= new SuratMasukPegawai();
	  $set_detil->selectByParamsUsulanPegawai(array(), -1, -1, $statement, "ORDER BY A.PEGAWAI_ID");
	  //echo $set_detil->query;exit;
	  $tempJumlahDataUsulan= 0;
	  while($set_detil->nextRow())
	  {
	  //echo $set_detil->query;exit;
	  	if($tempJumlahDataUsulan == 0)
	  	$tempNamaSaja= $set_detil->getField("NAMA_SAJA");
		
		$tempJumlahDataUsulan++;
	  }
	  
	  $tempPerihalInfo= $reqPerihal." a.n ".$tempNamaSaja;
	  if($tempJumlahDataUsulan == 1){}
	  else
	  $tempPerihalInfo= $tempPerihalInfo." dkk";*/
	  
	  $tempPerihalInfo= $reqPerihal;
   }
   
   $statement= " AND A.SURAT_MASUK_BKD_DISPOSISI_ID = ".$reqRowId."";
   $set_detil_catatan= new SuratMasukBkdDisposisiKeterangan();
   $set_detil_catatan->selectByParams(array(), -1, -1, $statement);
   //echo $set_detil_catatan->query;exit;
   $set_detil_catatan->firstRow();
   $reqRowDetilId= $set_detil_catatan->getField("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID");
   $reqCatatan= $set_detil_catatan->getField("ISI");
   $reqPegawaiId= $set_detil_catatan->getField("PEGAWAI_ID");
   $reqPegawaiNama= $set_detil_catatan->getField("PEGAWAI_NAMA");
   //echo $reqPegawaiNama;exit;
}

$tempJudul= "Usulan Pelayanan";
if($reqJenisId == "")
$tempJudul= "Surat Masuk";

if($reqJenisId == "" && $reqId == "")
{
	$json="surat_masuk_add";
}
else
{
	$json="nomor_agenda_terima";
	if($reqTerbaca == "1" && $reqValNomorAgenda != "")
	$json="nomor_agenda_disposisi";
}

if($reqPosisiTeknis == 1)
$json="catatan";

if($reqTanggalDisposisi == "")
$reqTanggalDisposisi= dateToPageCheck($tanggalHariIni);

$arrHistori= [];
$index_data= 0;
if($reqId == ""){}
else
{
	$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
	$statementnode= " AND SATUAN_KERJA_ASAL_ID NOT IN (".$reqSatuanKerjaId.")";
	$set_detil= new SuratMasukBkdDisposisi();
	$set_detil->selectByParamsHistoriDisposisi($statement, $statementnode);
	// echo "s";
	// echo $set_detil->query;exit;
	while($set_detil->nextRow())
	{
		$arrHistori[$index_data]["SURAT_MASUK_BKD_DISPOSISI_ID"] = $set_detil->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
		$arrHistori[$index_data]["TANGGAL_DISPOSISI"] = $set_detil->getField("TANGGAL_DISPOSISI");
		$arrHistori[$index_data]["ISI"] = $set_detil->getField("ISI");
		$arrHistori[$index_data]["JABATAN_ASAL"] = $set_detil->getField("JABATAN_ASAL");
		$arrHistori[$index_data]["JABATAN_TUJUAN"] = $set_detil->getField("JABATAN_TUJUAN");
		$index_data++;
	}
	unset($set_detil);
}
$jumlah_histori= $index_data;

$statement_disposisi= " AND A.SURAT_MASUK_BKD_ID = ".$reqId." AND A.TERBACA = 1";
$set_disposisi= new SuratMasukBkdDisposisi();
$jumlah_terbaca= $set_disposisi->getCountByParams(array(), $statement_disposisi);
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
    $(function(){
		$("#reqsimpan").click(function() { 
			if($("#ff").form('validate') == false){
				return false;
			}
			
			<?
			//buat surat baru
			if($reqId == "")
			{
			?>
				<?php /*?><input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggal" id="reqTanggal" <?=$read?> value="<?=$reqTanggal?>" maxlength="10" onKeyDown="return format_date(event,'');"/><?php */?>

				// reqTanggal= $("#reqTanggal").val();
				// var s_url= "hari_libur_json/checkHariLibur?reqTanggal="+reqTanggal;
				// $.ajax({'url': s_url,'success': function(dataajax){
				// 	if(dataajax == '1')
				// 	{
				// 		mbox.alert('Data tidak bisa disimpan, karena tanggal yang anda tulis masuk dalam hari libur', {open_speed: 0});
				// 		return false;
				// 	}
				// 	else
				// 	{
						reqTanggal= $("#reqTanggalTerima").val();
						var s_url= "hari_libur_json/checkHariLibur?reqTanggal="+reqTanggal;
						$.ajax({'url': s_url,'success': function(dataajax){
							if(dataajax == '1')
							{
								mbox.alert('Data tidak bisa disimpan, karena tanggal yang anda tulis masuk dalam hari libur', {open_speed: 0});
								return false;
							}
							else
							{
								$("#reqSubmit").click();
							}
						}});
				// 	}
				// }});
			<?
			}
			else
			{
			//surat dari dinas/badan
			//surat kalau sudah terbaca
			if($reqTerbaca == "1" && $reqValNomorAgenda != "")
			{
				if($reqPosisiTeknis == "1")
				{
			?>
				$("#reqSubmit").click();
			<?
				}
			}
			else
			{
			  //surat kalau sudah terbaca
			  if($reqTerbaca == "1")
			  {
			  ?>
			    reqTanggal= $("#reqTanggalTerima").val();
				var s_url= "hari_libur_json/checkHariLibur?reqTanggal="+reqTanggal;
				$.ajax({'url': s_url,'success': function(dataajax){
					if(dataajax == '1')
					{
						mbox.alert('Data tidak bisa disimpan, karena tanggal yang anda tulis masuk dalam hari libur', {open_speed: 0});
						return false;
					}
					else
					{
						$("#reqSubmit").click();
					}
				}});
			  <?
			  }
			  else
			  {
			  ?>
			  $("#reqSubmit").click();
			  //alert('a');return false;
			  <?
			  }
			  ?>
			<?
			}
			}
			if($reqTerbaca == "1" && $reqValNomorAgenda != "")
			{
				//kalau login teknis maka tidak di munculkan entrian ini
				if($reqLoginLevel >= 30){}
				else
				{
			?>
				reqTanggal= $("#reqTanggalDisposisi").val();
				var s_url= "hari_libur_json/checkHariLibur?reqTanggal="+reqTanggal;
				$.ajax({'url': s_url,'success': function(dataajax){
					if(dataajax == '1')
					{
						mbox.alert('Data tidak bisa disimpan, karena tanggal yang anda tulis masuk dalam hari libur', {open_speed: 0});
						return false;
					}
					else
					{
						$("#reqSubmit").click();
					}
				}});
			<?
				}
			}
			?>
			
		});
		
		function setcombonamajabatan()
		{
			var reqSatuanKerjaDiteruskanKepadaSelect= "";
			reqSatuanKerjaDiteruskanKepadaSelect= $("#reqSatuanKerjaDiteruskanKepadaSelect").val();
			$("#reqSatuanKerjaDiteruskanKepadaSelect option").remove();
			$("#reqSatuanKerjaDiteruskanKepadaSelect").material_select();

			urlAjax= "satuan_kerja_json/namajabatancombo?reqId=<?=$reqBatasSatuanKerjaCariId?>";
			$("<option value=''></option>").appendTo("#reqJenisHukumanId");
			$.ajax({'url': urlAjax,'success': function(dataJson) {
				var data= JSON.parse(dataJson);

				var items = varselected= "";
				varselected= "<?=$reqSatuanKerjaDiteruskanKepadaId?>";
				items += "<option></option>";
				$.each(data, function (i, SingleElement) {
					if(varselected == SingleElement.id)
					{
						items += "<option value='" + SingleElement.id + "' selected>" + SingleElement.text + "</option>";
					}
					else
					{
						items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
					}
				});
				$("#reqSatuanKerjaDiteruskanKepadaSelect").html(items);
				$("#reqSatuanKerjaDiteruskanKepadaSelect").material_select();
			}});
		}

        $('#ff').form({
            url:'surat/surat_masuk_bkd_disposisi_json/<?=$json?>',
            onSubmit:function(){
				//return false;
                if($(this).form('validate')){}
                    else
                    {
                        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
                        return false;
                    }
                },
                success:function(data){
                // console.log(data);return false;
                // alert(data);return false;
                data = data.split("-");
                rowid= data[0];
                infodata= data[1];
                //$.messager.alert('Info', infodata, 'info');
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
						parent.reloadparenttab();
						<?
						if($reqId == "")
						{
							?>
							top.location.href= "app/loadUrl/persuratan/surat_masuk_add/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
							<?
						}
						else
						{
							?>
							document.location.href= "app/loadUrl/persuratan/surat_masuk_add_data_agenda/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
							<?
						}
						?>
						
						
					}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
				}

            }
        });
	    
	    setcombonamajabatan();
	    $("#reqSatuanKerjaDiteruskanKepadaSelect").change(function() { 
	    	var reqSatuanKerjaDiteruskanKepadaSelect= "";
	    	reqSatuanKerjaDiteruskanKepadaSelect= $("#reqSatuanKerjaDiteruskanKepadaSelect").val();
	    	// alert(reqSatuanKerjaDiteruskanKepadaSelect);
	    	$("#reqSatuanKerjaDiteruskanKepadaId").val(reqSatuanKerjaDiteruskanKepadaSelect);
	    	// setcombonamajabatan();
		});

		//$('input[id^="reqSatuanKerjaDiteruskanKepada"], input[id^="reqSatuanKerjaAsal"]').autocomplete({
		$('input[id^="reqSatuanKerjaDiteruskanKepada"],input[id^="reqPegawaiNama"]').each(function(){
		$(this).autocomplete({
        source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqSatuanKerjaDiteruskanKepada') !== -1)
        {
          var element= id.split('reqSatuanKerjaDiteruskanKepada');
          var indexId= "reqSatuanKerjaDiteruskanKepadaId"+element[1];
          urlAjax= "satuan_kerja_json/namajabatan?reqId=<?=$reqBatasSatuanKerjaCariId?>";
        }
		else if (id.indexOf('reqPegawaiNama') !== -1)
        {
          var element= id.split('reqPegawaiNama');
          var indexId= "reqPegawaiId"+element[1];
          urlAjax= "pegawai_json/nama_pegawai?reqSatuanKerjaId=<?=$tempSatuanKerjaBkdId?>";
        }
		else if (id.indexOf('reqSatuanKerjaAsal') !== -1)
        {
          var element= id.split('reqSatuanKerjaAsal');
          var indexId= "reqSatuanKerjaAsalId"+element[1];
          urlAjax= "satuan_kerja_json/namasatuankerja?reqId=0";
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
        if (id.indexOf('reqSatuanKerjaDiteruskanKepada') !== -1)
        {
          var element= id.split('reqSatuanKerjaDiteruskanKepada');
          var indexId= "reqSatuanKerjaDiteruskanKepadaId"+element[1];
		  //$("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
		  //$("#reqEselonId").val(ui.item.eselon_id).trigger('change');
		  //$("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
		else if (id.indexOf('reqPegawaiNama') !== -1)
        {
          var element= id.split('reqPegawaiNama');
          var indexId= "reqPegawaiId"+element[1];
        }
		else if (id.indexOf('reqSatuanKerjaAsal') !== -1)
        {
          var element= id.split('reqSatuanKerjaAsal');
          var indexId= "reqSatuanKerjaAsalId"+element[1];
		  //$("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
		  //$("#reqEselonId").val(ui.item.eselon_id).trigger('change');
		  //$("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
        
		//statusht= ui.item.statusht;
         $("#"+indexId).val(ui.item.id).trigger('change');
       },
       autoFocus: true
       }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      }
	  ;
	  });
	  
	  $("#reqIsManual").click(function () {
		//if($("#reqIsManual").prop('checked')) 
		//{
			//$("#reqNama,#reqNamaId").val("");
			//$("#reqJabatanFu, #reqSatker, #reqSatkerId").val("");
			//$("#reqSatker, #reqSatkerId").val("");
			$("#reqSatuanKerjaAsal, #reqSatuanKerjaAsalId").val("");
		//}
	 });
	 
	  $('input[id^="reqSatuanKerjaAsal"]').autocomplete({
        source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

		if (id.indexOf('reqSatuanKerjaAsal') !== -1)
        {
			if($("#reqIsManual").prop('checked')) 
			{
				return false;
			}
		}
		
		if (id.indexOf('reqSatuanKerjaAsal') !== -1)
        {
          var element= id.split('reqSatuanKerjaAsal');
          var indexId= "reqSatuanKerjaAsalId"+element[1];
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
		if (id.indexOf('reqSatuanKerjaAsal') !== -1)
        {
          var element= id.split('reqSatuanKerjaAsal');
          var indexId= "reqSatuanKerjaAsalId"+element[1];
		  //$("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
		  //$("#reqEselonId").val(ui.item.eselon_id).trigger('change');
		  //$("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
        
		//statusht= ui.item.statusht;
         $("#"+indexId).val(ui.item.id).trigger('change');
       },
       autoFocus: true
       }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };
	  
});

<?
//buat surat baru
if($reqId == "")
{
?>

$(function(){
	$("#reqTanggalTerima").keyup(function() {
		setnomorawal();
	});
});

function setnomorawal()
{
	var reqTanggal= "";
	reqTanggal= $("#reqTanggalTerima").val();
	panjangTanggal= reqTanggal.length;
	if(panjangTanggal == 10)
	{
		$.ajax({'url': "surat/surat_masuk_bkd_json/setnomorawal/?reqTanggal="+reqTanggal,'success': function(dataJson) {
			var data= JSON.parse(dataJson);
			reqNomorAwal= data.reqNomorAwal;
			$("#reqNomorAgenda").val(reqNomorAwal);
		}});
						
	}
	else
	{
		$("#reqNomorAgenda").val("");
	}
}
<?
}
?>
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
        <div class="col s12 m10 offset-m1">

            <ul class="collection card">
                <li class="collection-item ubah-color-warna"><?=$tempJudul?> <?=$reqJenisNama?></li>
                <li class="collection-item">

                  <div class="row">
                    <form id="ff" method="post" enctype="multipart/form-data">
                        <?
						//buat surat baru
						if($reqId == "")
						{
						?>
                        <div class="row">
                            <div class="input-field col s12">
                            <input type="checkbox" id="reqIsManual" name="reqIsManual" value="1" <? if($reqIsManual == 1) echo 'checked'?> />
                            <label for="reqIsManual"></label>
                            *centang jika satker luar kab jombang
                            </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12 m4">
                          	<label for="reqTanggalTerima">Di Terima Tanggal</label>
                            <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalTerima" id="reqTanggalTerima" <?=$read?> value="<?=$reqTanggalTerima?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalTerima');"/>
                          </div>
                          <div class="input-field col s12 m2">
                            <label for="reqNomorAgenda">Nomor Agenda</label>
                            <input type="text" id="reqNomorAgenda" name="reqNomorAgenda" value="<?=$reqNomorAgenda?>" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12">
                            <label for="reqSatuanKerjaAsalNama">Surat Dari</label>
                            <input type="hidden" name="reqSatuanKerjaTujuanId" id="reqSatuanKerjaTujuanId" value="<?=$reqSatuanKerjaTujuanId?>" />
                            <input type="hidden" name="reqKepada" id="reqKepada" value="<?=$reqKepada?>" />
                            <input type="text" id="reqSatuanKerjaAsal" name="reqSatuanKerjaAsal" />
                            <input type="hidden" name="reqSatuanKerjaAsalId" id="reqSatuanKerjaAsalId" />
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12 m4">
                            <label for="reqTanggal">Tanggal Surat</label>
                            <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggal" id="reqTanggal" <?=$read?> value="<?=$reqTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggal');"/>
                          </div>
                          <div class="input-field col s12 m8">
                          	<label for="reqNomor">Nomor Surat</label>
                            <input required class="easyui-validatebox" type="text" name="reqNomor" id="reqNomor" <?=$read?> value="<?=$reqNomor?>"/>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12 m12">
                          	<label for="reqPerihal">Perihal</label>
                            <input required class="easyui-validatebox" type="text" name="reqPerihal" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>"/>
                          </div>
                        </div>

                        <?
			            if($tempStatusMenuKhusus == "1"){}
			            else
			            {
			            ?>
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

                          	<!-- <label for="reqLinkFile">Maximum file upload size 2MB.</label>
                          	<input type="file" name="reqLinkFile" id="reqLinkFile" class="dropify-fr" data-max-file-size="2M" data-allowed-file-extensions="pdf"/> -->
                          	<!-- pdf png psd -->
                          </div>
                        </div>
                        <?
                    	}
                        ?>

                        <!-- <div class="row">
                          <div class="input-field col s12 m12">
                          	<label for="reqLinkFile">File Surat</label>
		                    <input type="file" name="reqLinkFile" id="reqLinkFile" class="easyui-validatebox" data-options="validType:['fileType[\'pdf\', \'pdf\', \'pdf\']']" />
		                    <input type="hidden" name="reqLinkFileTemp" value="<?=$tempLinkFileTemp?>" />
                          </div>
                        </div> -->
                        <?
						}
						else
						{
						//surat dari dinas/badan
                        ?>

                        <?
                        if($reqKategori == ""){}
                        else
                        {
                        ?>
                        <div class="row">
                          <div class="input-field col s12">
                        	<label for="reqKategori">Jenis Pensiun</label>
	                        <input type="hidden" name="reqKategori" id="reqKategori" value="<?=$reqKategori?>" />
	                        <input type="text" disabled value="<?=$reqKategoriNama?>" />
	                      </div>
	                    </div>
	                    <?
	                	}
	                    ?>
	                    
                        <div class="row">
                          <div class="input-field col s12 m4">
                            <label for="reqSatuanKerjaAsalNama">Surat Dari</label>
                            <input type="text" id="reqSatuanKerjaAsalNama" disabled value="<?=$reqSatkerAsalNama?>" />
                          </div>
                          <div class="input-field col s12 m4">
                            <label for="reqTanggal">Tanggal Surat</label>
                            <input type="text" id="reqTanggal" disabled value="<?=$reqTanggal?>" />
                          </div>
                          <div class="input-field col s12 m4">
                          	<label for="reqNomor">Nomor Surat</label>
                            <input type="text" id="reqNomor" disabled value="<?=$reqNomor?>" />
                            </div>
                        </div>
                        
                        <?
						//surat kalau sudah terbaca
						if($reqTerbaca == "1" && $reqValNomorAgenda != "")
						{
                        ?>
                        <div class="row">
                          <div class="input-field col s12 m4">
                            <label for="reqNomorAgenda">Nomor Agenda</label>
                            <input type="hidden" name="reqNomorAgenda" value="<?=$reqNomorAgenda?>" />
                            <input type="text" id="reqNomorAgenda" value="<?=$reqNomorAgenda?>" disabled />
                          </div>
                          
                          <div class="input-field col s12 m6">
                          	<label for="reqPerihal">Perihal</label>
                            <input type="hidden" name="reqPerihal" value="<?=$reqPerihal?>" />
                            <input type="text" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>" disabled />
                          </div>
                          
                          <div class="input-field col s12 m2">
                          	<input type="hidden" name="reqTanggal" value="<?=$reqTanggal?>" />
                            <label for="reqTanggalTerima">Di Terima Tanggal</label>
                            <input type="text" id="reqTanggalTerima" <?=$read?> value="<?=$reqTanggalTerima?>" disabled />
                            <?php /*?><input required class="easyui-validatebox" type="text" name="reqTanggalDisposisi" id="reqTanggalDisposisi" <?=$read?> value="<?=$reqTanggalDisposisi?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalDisposisi');"/><?php */?>
                            </div>
                        </div>
                        <?
						}
						else
						{
                        ?>
                        <div class="row">
                          <?
                          if($reqValNomorAgenda != "")
						  {
						  ?>
                          <div class="input-field col s12 m4">
                            <label for="reqNomorAgenda">Nomor Agenda</label>
                            <input type="hidden" name="reqNomorAgenda" value="<?=$reqNomorAgenda?>" />
                            <input type="text" id="reqNomorAgenda" value="<?=$reqNomorAgenda?>" disabled />
                          </div>
                          <?
						  }
                          ?>
                          <div class="input-field col s12 m6">
                          	<label for="reqPerihal">Perihal</label>
                            <input type="hidden" name="reqPerihal" value="<?=$reqPerihal?>" />
                            <input type="text" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>" disabled />
                          </div>
                          <?
                          //surat kalau sudah terbaca
                          if($reqTerbaca == "1")
                          {
						  ?>
                          <div class="input-field col s12 m6">
                          	<input type="hidden" name="reqTanggal" value="<?=$reqTanggal?>" />
                            <label for="reqTanggalTerima">Di Terima Tanggal</label>
                            <?php /*?><input type="text" id="reqTanggalTerima" <?=$read?> value="<?=$reqTanggal?>" disabled /><?php */?>
                            <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalTerima" id="reqTanggalTerima" <?=$read?> value="<?=$reqTanggalTerima?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalTerima');"/>
                            </div>
                          <?
						  }
                          ?>
                        </div>
                        <?
						}
						}
                        ?>
                        
                        <?
                        // tempat baru
						if($reqTerbaca == "1" && $reqValNomorAgenda != "")
						{
	                        if($jumlah_terbaca < 3)
	                        {
                        ?>
	                        <div class="row">
	                          <div class="input-field col s12 m12">
	                          	<div class="file_input_div">
	                          		<?
						            if($tempStatusMenuKhusus == "1"){}
						            else
						            {
						            ?>
		                          	<div class="file_input input-field col s12 m1">
		                      			<label class="labelupload">
		                      				<i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
		                      				<input id="file_input_file" name="reqLinkFile" class="none" type="file" />
		                      			</label>
		                      		</div>
		                      		<?
		                      		}
		                      		?>

		                      		<?
		                            $tempUrlFile= "uploadsurat/masuk/".$reqId.".pdf";
		                            if(file_exists($tempUrlFile))
									{
		                            ?>
		                            <div class="file_input input-field col s12 m1">
		                            	<label class="labelupload">
		                            		<a href="javascript:void(0)" title="Lihat File" onClick="parent.setload('surat_masuk_add_data_agenda_file?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqUrlFile=<?=$tempUrlFile?>')">
		                      					<i class="mdi-editor-attach-file" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Lihat File</i>
		                      				</a>
		                      			</label>
		                      		</div>

		                            <div id="file_input_text_div" class=" input-field col s12 m10">
		                      			<input class="file_input_text" type="text" disabled readonly id="file_input_text" />
		                      			<label for="file_input_text"></label>
		                      		</div>
		                            <?
		                        	}
		                        	else
		                        	{
		                            ?>

		                            <?
						            if($tempStatusMenuKhusus == "1"){}
						            else
						            {
						            ?>
		                      		<div id="file_input_text_div" class=" input-field col s12 m11">
		                      			<input class="file_input_text" type="text" disabled readonly id="file_input_text" />
		                      			<label for="file_input_text"></label>
		                      		</div>
		                      		<?
		                      		}
		                      		?>

		                      		<?
		                      		}
		                      		?>
		                      	</div>
	                          	<!-- <label for="reqLinkFile">Maximum file upload size 2MB.</label>
	                          	<input type="file" name="reqLinkFile" id="reqLinkFile" class="dropify-fr" data-max-file-size="2M" data-allowed-file-extensions="pdf"/> -->
	                          	<!-- pdf png psd -->
	                          </div>
	                        </div>
                        <?
                    		}
                    	}
                        ?>

                        <?
						if($reqTerbaca == "1" && $reqValNomorAgenda != "")
						{
							//kalau login teknis maka tidak di munculkan entrian ini
							if($reqLoginLevel >= 30){}
							else
							{
							// tempat lama
                        ?>
                        <?
                        	// end tempat lama
							}
						}
						else
						{
						  //surat kalau sudah terbaca
						  if($reqTerbaca == "1")
						  {
					    ?>
                        <div class="row">
                          <div class="input-field col s12 m2">
                            <label for="reqNomorAgenda">Nomor Agenda</label>
                            <?
							if($jumlah_histori > 0)
							{
                            ?>
                            <input type="hidden" name="reqNomorAgenda" value="<?=$reqNomorAgenda?>" />
                            <input type="text" id="reqNomorAgenda" <?=$read?> value="<?=$reqNomorAgenda?>" disabled />
                            <?
							}
							else
							{
                            ?>
                            <input type="text" class="easyui-validatebox" required name="reqNomorAgenda" id="reqNomorAgenda" value="<?=$reqNomorAgenda?>" maxlength="4" />
                            <?
							}
                            ?>
                          </div>
                        </div>
                        <?
						  }
						}
                        ?>
                        
                        <?
						if($jumlah_histori > 0)
						{
                        ?>
                        <div class="row">
                          <div class="collection-item ubah-color-warna col s12">Informasi Disposisi</div>
                        </div>
                        <?
						}
                        ?>
                        
                        <?
						for($index_loop=0; $index_loop < $jumlah_histori; $index_loop++)
						{
							$tempHistoriId= $arrHistori[$index_loop]["SURAT_MASUK_BKD_DISPOSISI_ID"];
							$tempHistoriTanggalDisposisi= $arrHistori[$index_loop]["TANGGAL_DISPOSISI"];
							$tempHistoriIsi= $arrHistori[$index_loop]["ISI"];
							$tempHistoriJabatanAsal= $arrHistori[$index_loop]["JABATAN_ASAL"];
							$tempHistoriJabatanTujuan= $arrHistori[$index_loop]["JABATAN_TUJUAN"];
                        ?>
                        <div class="row">
                          <div class="input-field col s12 m2">
                            <label for="reqTanggalDisposisi<?=$tempHistoriId?>">Tanggal Disposisi</label>
                            <input type="text" id="reqTanggalDisposisi<?=$tempHistoriId?>" value="<?=dateToPageCheck($tempHistoriTanggalDisposisi)?>" disabled />
                          </div>
                          <div class="input-field col s12 m4">
                          	<label for="reqAsalTujuanSurat<?=$tempHistoriId?>">Kepada</label>
                            <input type="text" id="reqAsalTujuanSurat<?=$tempHistoriId?>" <?=$read?> value="<?=$tempHistoriJabatanTujuan?>" disabled />
                          </div>
                          <div class="input-field col s12 m6">
                          	<?php /*?><textarea id="reqIsi<?=$tempHistoriId?>" class="materialize-textarea" disabled><?=$tempHistoriIsi?></textarea><?php */?>
                  			<label for="reqIsi<?=$tempHistoriId?>">Isi Disposisi</label>
                            <input type="text" id="reqIsi<?=$tempHistoriId?>" <?=$read?> value="<?=$tempHistoriIsi?>" disabled />
                          </div>
                        </div>
                        <?
						}
                        ?>
                        
                        <?
						//kalau kondisi teknis maka keluar simpan
						if($reqPosisiTeknis == "1")
						{
						?>
                        <div class="row">
                            <div class="input-field col s12 m4">
                            <label for="reqPegawaiNama">Di tujukan ke</label>
                            <input type="text" id="reqPegawaiNama" name="reqPegawaiNama" <?=$read?> value="<?=$reqPegawaiNama?>" class="easyui-validatebox" required />
                            <input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                            <label for="reqCatatan">Catatan</label>
                            <input type="text" id="reqCatatan" name="reqCatatan" value="<?=$reqCatatan?>" />
                            <input type="hidden" name="reqRowDetilId" value="<?=$reqRowDetilId?>" />
                            </div>
                        </div>
                        <?
						}
                        ?>

                        <?
                        // tempat baru
						if($reqTerbaca == "1" && $reqValNomorAgenda != "")
						{
							//kalau login teknis maka tidak di munculkan entrian ini
							if($reqLoginLevel >= 30){}
							else
							{
							// tempat lama
                        ?>
                        <div class="row">
                          <div class="input-field col s12 m4">
                            <label for="reqTanggalDisposisi">Tanggal Disposisi</label>
                            <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalDisposisi" id="reqTanggalDisposisi" <?=$read?> value="<?=datetimeToPage($reqTanggalDisposisi, "date")?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalDisposisi');"/>

                            <?
                            // if($jumlah_terbaca < 3)
                            // {
                            ?>
                            <?
                            $tempUrlFile= "uploadsurat/masuk/".$reqId.".pdf";
                            if(file_exists($tempUrlFile))
							{
                            ?>
                            <!-- <a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('surat_masuk_add_data_agenda_file?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqUrlFile=<?=$tempUrlFile?>')">
                            	<i class="mdi-editor-attach-file"></i>
                            </a> -->
                            <?
                        	}
                            ?>
                            <?
                        	// }
                            ?>

                            <!-- Trigger to open Modal -->
							<!-- <a href="uploadsurat/masuk/2610.pdf" class="trigger">Modal</a> -->

                            <!-- <a href="uploadsurat/masuk/2610.pdf" class="trigger">Modal</a>
                            <button data-izimodal-iframeurl="uploadsurat/masuk/2610.pdf" data-izimodal-open="#modal-iframe">Modal</button> -->

                            <!-- <a id="btnShow">this link</a>
							<div id="dialog" style="display: none;">
							    <div>
							        <iframe id="frame"></iframe>
							    </div>
							</div> -->

                          </div>
                          <div class="input-field col s12 m8">
                          	<select name="reqSatuanKerjaDiteruskanKepadaSelect" id="reqSatuanKerjaDiteruskanKepadaSelect">
								<option value=""></option>
							</select>
							<label for="reqSatuanKerjaDiteruskanKepadaSelect">Diteruskan Kepada</label>
                          	<!-- <label for="reqSatuanKerjaDiteruskanKepada">Diteruskan Kepada</label>
                            <input type="text" id="reqSatuanKerjaDiteruskanKepada" name="reqSatuanKerjaDiteruskanKepada" <?=$read?> value="<?=$reqSatuanKerjaDiteruskanKepada?>" class="easyui-validatebox" required /> -->
                            <input type="hidden" name="reqSatuanKerjaDiteruskanKepadaId" id="reqSatuanKerjaDiteruskanKepadaId" value="<?=$reqSatuanKerjaDiteruskanKepadaId?>" />
                          </div>
                        </div>



                        <div class="row">
                          <div class="input-field col s12">
                          	<?php /*?><textarea name="reqIsi" id="reqIsi" required class="easyui-validatebox materialize-textarea"><?=$reqIsi?></textarea><?php */?>
                  			<label for="reqIsi">Isi Disposisi</label>
                            <input type="text" required class="easyui-validatebox" id="reqIsi" name="reqIsi" <?=$read?> value="<?=$reqIsi?>" />
                          </div>
                        </div>
                        <?
                        	// end tempat lama
							}
						}
						?>
                        
                        <div class="row">
                            <div class="input-field col s12 m12">
                                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                                <input type="hidden" name="reqJenis" value="<?=$reqJenis?>" />
                                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                                <input type="hidden" name="reqSuratAwal" value="<?=$tempStatusKelompokPegawaiUsul?>" />
                                <?
								if($reqTerdisposisi == "1")
								{
									if($reqTerbacaDisposisi == "")
									{
								?>
                                <input type="hidden" name="reqModeUbah" value="1" />
                                <button type="submit" style="display:none" id="reqSubmit"></button>
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Ubah Disposisi
                                    <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
                                <?
									}
								}
								else
								{
								if($reqTerbaca == "1")
								{
									//kalau login teknis maka tidak di munculkan entrian ini
									if($reqLoginLevel >= 30){}
									else
									{
										 $tempInfoButtonSimpan= "Disposisi";
										 if($reqValNomorAgenda == "")
										 {
								?>
                                		<input type="hidden" name="reqModeUbah" value="1" />
                                <?
											 $tempInfoButtonSimpan= "Simpan";
										 }
								?>
                                <button type="submit" style="display:none" id="reqSubmit"></button>
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan"><?=$tempInfoButtonSimpan?>
                                    <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
                                
                                <?php /*?><button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakdisposisi">Cetak Lembar Disposisi
                                    <i class="mdi-content-inbox left hide-on-small-only"></i>
                                </button><?php */?>
                                <?
									}
								}
								else
								{
									if($reqLoginLevel >= 30)
									{
								?>
										<input type="hidden" name="reqPosisiTeknis" value="1" />
                                <?
									}
								?>
                                <button type="submit" style="display:none" id="reqSubmit"></button>
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                                	<?
									if($reqId == "")
									{
                                    ?>
                                    Buat Surat Baru
                                    <?
									}
									else
									{
                                    ?>
                                	Terima Berkas
                                    <?
									}
                                    ?>
                                    <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
                                <?
								}
								}
                                ?>
                                
                                <?
								if($reqId == ""){}
								else
								{
									//$reqTerdisposisi;$reqSuratAwal
									//if($reqValNomorAgenda != "")
									//if(($reqJenisId != "" && $reqSuratAwal == "1" && $reqValNomorAgenda != "") || ($reqJenisId == "" && $reqTerdisposisi == "" && $tempStatusKelompokPegawaiUsul == "1"))
									if(($reqJenisId != "" && $reqSuratAwal == "1" && $reqValNomorAgenda != "") || ($reqJenisId == "" && $tempStatusKelompokPegawaiUsul == "1"))
									{
										//if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
										//{
								?>

								<?
								if($tempStatusMenuKhusus == "1"){}
								else
								{
								?>
                                <button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakdisposisi">Cetak Lembar Disposisi
                                    <i class="mdi-content-inbox left hide-on-small-only"></i>
                                </button>
                                <?
                            	}
                                ?>

                                <?
										//}
									}
								}
                                ?>
                                
                                <?
								//kalau kondisi teknis maka keluar simpan
								if($reqPosisiTeknis == "1")
								{
                                ?>
                                <button type="submit" style="display:none" id="reqSubmit"></button>
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                                	Simpan
                                    <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
                                <?
								}
                                ?>

                                <?
                                if($reqId == ""){}
                                else
                                {
                                	if($reqJenis == "")
                                	{
                                		if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
                                		{
                                ?>

                                <?
								if($tempStatusMenuKhusus == "1"){}
								else
								{
								?>

                                <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="tambah">Tambah Lainnya
                                	<i class="mdi-content-add left hide-on-small-only"></i>
                                </button>
                                <?
                            	}
                                ?>

                                <?
                            			}
                            		}
                            	}
                                ?>

                                <?
								if($reqTerdisposisi == "1")
								{
									if($reqTerbacaDisposisi == "")
									{
								?>
                                <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="suratbelumbaca">Surat Berikutnya
                                	<i class="mdi-navigation-chevron-right left hide-on-small-only"></i>
                                </button>
                                <?
									}
								}
								?>

								<?
	                        	if($reqTerbaca == "1" && $reqValNomorAgenda != "")
								{
							        if($jumlah_terbaca > 2)
							        {
							    ?>

									<?
		                            $tempUrlFile= "uploadsurat/masuk/".$reqId.".pdf";
		                            if(file_exists($tempUrlFile))
									{
		                            ?>
		                            <button class="btn waves-effect waves-light purple" style="font-size:9pt" type="button" onClick="parent.setload('surat_masuk_add_data_agenda_file?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqUrlFile=<?=$tempUrlFile?>')">Lihat File
	                                    <i class="mdi-editor-attach-file left hide-on-small-only"></i>
	                                </button>
		                            <?
		                        	}
		                        	?>
		                        <?
                            		}
                            	}
                                ?>
	                        	
                                <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" onClick="parent.closeparenttab()">Close
                                   <i class="mdi-navigation-close left hide-on-small-only"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="row"><div class="input-field col s12">&nbsp;</div></div>

                </form>
            </div>
        </li>
    </ul>
</div>
</div>
</div>
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
<link href="lib/materializetemplate/js/plugins/prism/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/js/plugins/chartist-js/chartist.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!--dropify-->
<script type="text/javascript" src="lib/materializetemplate/js/plugins/dropify/js/dropify.min1.js"></script>
<link href="lib/materializetemplate/js/plugins/dropify/css/dropify.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();

    // Basic
    // $('.dropify').dropify();

    // Translated
    // $(".dropify-fr").fileinput({
    //     // rtl: true,
    //     // dropZoneEnabled: false,
    //     allowedFileExtensions: ["jpg", "png", "gif"]
    // });

    $('.dropify-fr').dropify({
        messages: {
            default: 'Upload File Pdf, Maksimal file upload 2 MB',
            replace: 'Nama File Anda',
            remove:  'Cancel',
            error:   'Gagal Upload'
        }
    });

    // Used events
    var drEvent = $('.dropify-event').dropify();

    drEvent.on('dropify.beforeClear', function(event, element){
        return confirm("Do you really want to delete \"" + element.filename + "\" ?");
    });

    drEvent.on('dropify.afterClear', function(event, element){
        alert('File deleted');
    });
	

	$("#suratbelumbaca").click(function() {
		$.getJSON("surat/surat_masuk_bkd_disposisi_json/statusbelumbaca/?reqId=<?=$reqId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>",
		function(data){

			if(data.iddata == "")
			{
				mbox.alert('Data surat berikut nya tidak ada', {open_speed: 0});
				return false;
			}
			else
			{
				top.location.href= "app/loadUrl/persuratan/surat_masuk_add/?reqId="+data.iddata+"&reqJenis="+data.jenis;
			}

			// $.messager.alert('Info', data.PESAN+'--'+data.iddata, 'info');
		});

		// document.location.href= "app/loadUrl/persuratan/surat_masuk_add_data_agenda/?reqId=&reqJenis=<?=$reqJenis?>&reqMode=<?=$reqMode?>";
	});

	$("#tambah").click(function() {
		document.location.href= "app/loadUrl/persuratan/surat_masuk_add_data_agenda/?reqId=&reqJenis=<?=$reqJenis?>&reqMode=<?=$reqMode?>";
	});

	$("#reqcetakdisposisi").click(function() { 
	  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_pengantar&reqUrl=cetak_disposisi&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId=<?=$reqSatuanKerjaId?>", 'Cetak');
	  newWindow.focus();
	});
	
	$("#reqkirim").click(function() { 
		var reqSatuanKerjaDiteruskanKepada= "";
		reqSatuanKerjaDiteruskanKepada= $("#reqSatuanKerjaDiteruskanKepada").val();
		$.messager.confirm('Konfirmasi', "Apakah Anda Yakin, Kirim Ke "+reqSatuanKerjaDiteruskanKepada+" ?",function(r){
			if (r){
				$.getJSON("surat/surat_masuk_bkd_disposisi_json/statuskirim/?reqRowId=<?=$reqRowId?>",
				function(data){
					$.messager.alert('Info', data.PESAN, 'info');
					parent.reloadparenttab();
					document.location.href= "app/loadUrl/persuratan/surat_masuk_add_data/?reqId=<?=$reqId?>";
				});
			}
		});
	  //document.location.href = "app/loadUrl/app/pegawai_add_anak_monitoring?reqId=<?=$reqId?>";
	});
	
	// var fileName = "uploadsurat/masuk/2610.pdf";
 //    $("#btnShow").click(function () {
 //        $("#dialog").dialog({
 //            modal: true,
 //            title: fileName,
 //            width: 540,
 //            height: 450,
 //            buttons: {
 //                Close: function () {
 //                    $(this).dialog('close');
 //                }
 //            },
 //            open: function () {
 //                var object = "<object data=\"{FileName}\" type=\"application/pdf\" width=\"500px\" height=\"300px\">";
 //                object += "If you are unable to view file, you can download from <a href = \"{FileName}\">here</a>";
 //              object += " or download <a target = \"_blank\" href = \"http://get.adobe.com/reader/\">Adobe PDF Reader</a> to view the file.";
 //                object += "</object>";
 //                object = object.replace(/{FileName}/g, "Files/" + fileName);
 //                $("#dialog").html(object);
 //            }
 //        });
 //    });

	// $('#btnShow').click(function(){
	// 	$("#dialog").dialog();
	// 	$("#frame").attr("src", "uploadsurat/masuk/2610.pdf");
	// }); 

	// $("#modal").iziModal();

});

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqNomorAgenda').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

//   $(document).on('click', '.trigger', function (event) {
//     event.preventDefault();
//     $('#modal-iframe').iziModal('open');
//     alert("");
//     // or
//     $('#modal-iframe').iziModal('open', event); // Use "event" to get URL href
// });

</script>


<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js"></script> -->

<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
<!-- <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/jquery-ui.js" type="text/javascript"></script>
<link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" /> -->

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
  	<?
    if($tempStatusMenuKhusus == "1"){}
    else
    {
  	if($reqTerbaca == "1" && $reqValNomorAgenda != "" || $reqId == "")
	{
        if($jumlah_terbaca < 3 || $reqId == "")
        {
    ?>
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
  	<?
  		}
  	}
  	}
  	?>
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

</body>
</html>