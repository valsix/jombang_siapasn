<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukUpt');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqMode= $this->input->get("reqMode");

$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
$set= new SuratMasukUpt();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
$reqSatkerAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");
unset($set);

if($reqRowId=="")
{
  $reqMode = 'insert';
  $reqNamaPegawai= $reqNamaPegawai= $reqPendidikanRiwayatAkhir= $reqStatusPendidikanTerakhirNama= $reqJurusanTerakhir= $reqNamaSekolahTerakhir= " ";
}
else
{
  $reqMode = 'update';
  $statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
  $set= new SuratMasukPegawai();
  $set->selectByParamsMonitoring(array(), -1, -1, $statement);
  $set->firstRow();
  //echo $set->query;exit;

  $reqSatuanKerjaPegawaiUsulanId= $set->getField('SATUAN_KERJA_ID');
  $reqPegawaiId= $set->getField('PEGAWAI_ID');
  $reqJurusanId= $set->getField('PENDIDIKAN_JURUSAN_ID_US');
  
  $reqJabatanRiwayatAkhirId= $set->getField('JABATAN_RIWAYAT_AKHIR_ID');
  //$reqRowId= $set->getField('JABATAN_RIWAYAT_SEKARANG_ID');
  $reqPendidikanRiwayatAkhirId= $set->getField('PENDIDIKAN_RIWAYAT_AKHIR_ID');
  $reqRowDetilId= $set->getField('PENDIDIKAN_RIWAYAT_SEKARANG_ID');
  $reqGajiRiwayatAkhirId= $set->getField('GAJI_RIWAYAT_AKHIR_ID');
  //$reqRowId= $set->getField('GAJI_RIWAYAT_SEKARANG_ID');
  $reqPangkatRiwayatAkhirId= $set->getField('PANGKAT_RIWAYAT_AKHIR_ID');
  //$reqRowId= $set->getField('PANGKAT_RIWAYAT_SEKARANG_ID');
					
  $reqNipBaru= $set->getField('NIP_BARU');
  $reqNamaPegawai= $set->getField('NAMA_LENGKAP');
  
  $reqPendidikanId= $set->getField('PENDIDIKAN_ID_US');
  $reqPendidikanAkhirId= $set->getField('PENDIDIKAN_ID');
  $reqPendidikanRiwayatAkhir= $set->getField('PENDIDIKAN_NAMA');
  $reqNamaFakultas= $set->getField('NAMA_FAKULTAS_US');
  $reqJurusan= $set->getField('JURUSAN_US');
  $reqNamaSekolah= $set->getField('NAMA_SEKOLAH_US');
  $reqAlamatSekolah= $set->getField('TEMPAT_US');
  
  $reqStatusTugasIjinBelajar= $set->getField('STATUS_TUGAS_IJIN_BELAJAR');
  $reqStatusPendidikanTerakhirNama= $set->getField('STATUS_PENDIDIKAN_NAMA');
  $reqJurusanTerakhir= $set->getField('JURUSAN');
  //$reqRowId= $set->getField('NAMA_SEKOLAH');
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
  	function setoptionpendidikan()
	{
		$("#reqPendidikanId :selected").remove();
		$("#reqPendidikanId option").remove();
		var s_url= "pendidikan_json/option?reqPendidikanMinimalId=<?=$reqPendidikanAkhirId?>";
		$.ajax({'url': s_url,'success': function(dataJson){
			var data= JSON.parse(dataJson);
			for(i=0;i<data.arrID.length; i++)
			{
				valId= data.arrID[i]; valNama= data.arrNama[i];
				if(valId == "<?=$reqPendidikanId?>")
				{
					$("<option value='" + valId + "' selected='selected'>" + valNama + "</option>").appendTo("#reqPendidikanId");
				}
				else
					$("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#reqPendidikanId");
			}
			
			$('#reqPendidikanId').material_select();
		}});
	}
	
    $(function(){
      $(".preloader-wrapper").hide();
      
	  <?
	  if($reqRowId == ""){}
	  else
	  {
	  ?>
	  setoptionpendidikan();
	  <?
	  }
	  ?>

    $("#reqsimpan").click(function() { 
      if($("#ff").form('validate') == false){
        return false;
      }
      
      var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_upt?reqId=<?=$reqId?>";
      $.ajax({'url': s_url,'success': function(dataajax){
        var requrl= requrllist= "";
        dataajax= String(dataajax);
        if(dataajax == '1')
        {
          mbox.alert('Data sudah dikirim', {open_speed: 0});
          return false;
        }
        else
        $("#reqSubmit").click();
      }});
    });

      $('#ff').form({
		url:'surat/surat_masuk_pegawai_json/add_pendidikan',
        onSubmit:function(){
			var reqPegawaiId= reqPendidikanId= reqJurusanId= "";
			reqPegawaiId= $("#reqPegawaiId").val();
			reqPendidikanId= $("#reqPendidikanId").val();
			reqJurusanId= $("#reqJurusanId").val();
			
			if(reqPegawaiId == "" || reqPendidikanId == "")
			{
				mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
				//$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
				return false;
			}
			
			if(reqJurusanId == "")
			{
				mbox.alert("Jurusan tidak ada dalam sistem, hubungi admin untuk menambahakan data jurusan", {open_speed: 0});
				//$.messager.alert('Info', "Jurusan tidak ada dalam sistem, hubungi admin untuk menambahakan data jurusan", 'info');
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
          //alert(data);return false;
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
				  document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_pendidikan_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId="+rowid;
				}, 1000));
				$(".mbox > .right-align").css({"display": "none"});
				$(".mbox > .right-align").css({"display": "none"});
			}
          
        }
      });

      $('input[id^="reqJurusan"], input[id^="reqNipBaru"]').autocomplete({
        source:function(request, response){
          $(".preloader-wrapper").show();

          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          if (id.indexOf('reqJurusan') !== -1)
          {
            var reqPendidikanId= "";
            reqPendidikanId= $("#reqPendidikanId").val();
            var element= id.split('reqJurusan');
            var indexId= "reqJurusanId"+element[1];
            urlAjax= "pendidikan_jurusan_json/combo?reqId="+reqPendidikanId;
          }
		  else if (id.indexOf('reqNipBaru') !== -1)
          {
            urlAjax= "pendidikan_riwayat_json/cari_pegawai_usulan?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
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
				  , satuankerjaid: element['satuankerjaid']
				  , jabatanriwayatid: element['jabatanriwayatid'], pendidikanriwayatid: element['pendidikanriwayatid']
				  , gajiriwayatid: element['gajiriwayatid'], pangkatriwayatid: element['pangkatriwayatid']
				  , pendidikannama: element['pendidikannama'], pendidikanid: element['pendidikanid']
				  , pendidikanstatus: element['pendidikanstatus'], pendidikanjurusan: element['pendidikanjurusan']
				  , pendidikannamasekolah: element['pendidikannamasekolah']};
                });
                response(array);
              }
            }
          })
        },
        select: function (event, ui) 
        { 
          var id= $(this).attr('id');
          if (id.indexOf('reqJurusan') !== -1)
          {
            var element= id.split('reqJurusan');
            var indexId= "reqJurusanId"+element[1];
          }
		  else if (id.indexOf('reqNipBaru') !== -1)
          {
            var indexId= "reqPegawaiId";
			var namapegawai= pendidikanid= satuankerjaid= jabatanriwayatid= pendidikanriwayatid= gajiriwayatid= pangkatriwayatid= pendidikannama= pendidikanstatus= pendidikanjurusan= pendidikannamasekolah= "";
            namapegawai= ui.item.namapegawai;
			pendidikanid= ui.item.pendidikanid;
			satuankerjaid= ui.item.satuankerjaid;
			jabatanriwayatid= ui.item.jabatanriwayatid;
			pendidikanriwayatid= ui.item.pendidikanriwayatid;
			gajiriwayatid= ui.item.gajiriwayatid;
			pangkatriwayatid= ui.item.pangkatriwayatid;
			pendidikannama= ui.item.pendidikannama;
			pendidikanstatus= ui.item.pendidikanstatus;
			pendidikanjurusan= ui.item.pendidikanjurusan;
			pendidikannamasekolah= ui.item.pendidikannamasekolah;
			
			$("#reqNamaPegawai").val(namapegawai);
			$("#reqSatuanKerjaPegawaiUsulanId").val(satuankerjaid);
			$("#reqJabatanRiwayatAkhirId").val(jabatanriwayatid);
			$("#reqPendidikanRiwayatAkhirId").val(pendidikanriwayatid);
			$("#reqGajiRiwayatAkhirId").val(gajiriwayatid);
			$("#reqPangkatRiwayatAkhirId").val(pangkatriwayatid);
			$("#reqPendidikanRiwayatAkhir").val(pendidikannama);
			$("#reqStatusPendidikanTerakhirNama").val(pendidikanstatus);
			$("#reqJurusanTerakhir").val(pendidikanjurusan);
			$("#reqNamaSekolahTerakhir").val(pendidikannamasekolah);
			
			$("#reqPendidikanId :selected").remove();
			$("#reqPendidikanId option").remove();
			var s_url= "pendidikan_json/option?reqPendidikanMinimalId="+pendidikanid;
			$.ajax({'url': s_url,'success': function(dataJson){
				var data= JSON.parse(dataJson);
				for(i=0;i<data.arrID.length; i++)
				{
					valId= data.arrID[i]; valNama= data.arrNama[i];
					/*if(valId == tempTipeKambingId)
					{
						$("<option value='" + valId + "' selected='selected'>" + valNama + "</option>").appendTo("#reqTipeKambingId"+id);
					}
					else*/
						$("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#reqPendidikanId");
				}
				
				$('#reqPendidikanId').material_select();
			}});
			
          }
		  
          $("#"+indexId).val(ui.item.id).trigger('change');
        },
          //minLength:3,
        autoFocus: true
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };

      $("#reqPendidikanId").change(function(){
       $("#reqJurusan, #reqJurusanId").val("");
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
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">Pegawai Usul <?=$reqJenisNama?></li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="input-field col s12 m6">
                    <label for="reqNipBaru">NIP Baru</label>
                    <?
					if($reqRowId == "")
					{
					?>
                    <input required id="reqNipBaru" class="easyui-validatebox" type="text"  value="<?=$reqNipBaru?>" />
                    <?
					}
					else
					{
					?>
                    <input id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
                    <input required type="text"  value="<?=$reqNipBaru?>" disabled />
                    <?
					}
                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="input-field col s12">
                    <label for="reqNamaPegawai" class="active">Nama Pegawai</label>
                    <input id="reqNamaPegawai" class="easyui-validatebox" type="text"  value="<?=$reqNamaPegawai?>" disabled />
                </div>
            </div>
                                        
              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqPendidikanRiwayatAkhir">Pendidikan Terakhir</label>
                  <input type="text" id="reqPendidikanRiwayatAkhir" value="<?=$reqPendidikanRiwayatAkhir?>" disabled />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqStatusPendidikanTerakhirNama">Status Pendidikan</label>
                  <input type="text" id="reqStatusPendidikanTerakhirNama" value="<?=$reqStatusPendidikanTerakhirNama?>" disabled />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqJurusanTerakhir">Jurusan Terakhir</label>
                  <input type="text" id="reqJurusanTerakhir" value="<?=$reqJurusanTerakhir?>" disabled />
                </div>
              </div>    
              
              <?php /*?><div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaSekolahTerakhir">Nama Sekolah Terakhir</label>
                  <input type="text" id="reqNamaSekolahTerakhir" <?=$read?> value="<?=$reqNamaSekolahTerakhir?>" disabled/>
                </div>
              </div><?php */?>
              
              <div class="row">
                <!-- <div class="input-field col s12 m3">
                  <select name="reqStatusTugasIjinBelajar" id="reqStatusTugasIjinBelajar">
                    <option value="1" <? if($reqStatusTugasIjinBelajar == "1") echo "selected"?>>Ijin Belajar</option>
                    <option value="2" <? if($reqStatusTugasIjinBelajar == "2") echo "selected"?>>Tugas Belajar</option>
                  </select>
                  <label for="reqStatusTugasIjinBelajar">Status Ijin belajar / Tugas Belajar</label>
                </div> -->

                <div class="input-field col s12 m3">
                  <select name="reqPendidikanId" id="reqPendidikanId" <?=$disabled?>></select>
                  <label for="reqPendidikanId">Tk. Pendidikan yang di mohonkan</label>
                </div>
                
                <div class="input-field col s12 m5">
                  <label for="reqNamaFakultas">Fakultas</label>
                  <input type="text" required class="easyui-validatebox" id="reqNamaFakultas" name="reqNamaFakultas" value="<?=$reqNamaFakultas?>" />
                </div>
                
              </div>
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqJurusan">Jurusan/Program Studi</label>
                  <input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$reqJurusanId?>" /> 
                  <input type="text" name="reqJurusan" id="reqJurusan" value="<?=$reqJurusan?>" title="Jurusan harus diisi" />
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaSekolah">Nama PT / Sekolah</label>
                  <input type="text" required class="easyui-validatebox" required id="reqNamaSekolah"  name="reqNamaSekolah" <?=$read?> value="<?=$reqNamaSekolah?>" title="Nama sekolah harus diisi" />
                </div>
                
                <div class="input-field col s12 m6">
                  <label for="reqAlamatSekolah">Kota Tempat PT / Sekolah</label>
                  <input type="text" required class="easyui-validatebox" id="reqAlamatSekolah" name="reqAlamatSekolah" <?=$read?> value="<?=$reqAlamatSekolah?>" />
                  <?php /*?><textarea name="reqAlamatSekolah" id="reqAlamatSekolah" class="required materialize-textarea"><?=$reqAlamatSekolah?></textarea><?php */?>
                </div>
              </div>  

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>
                  
                  <?
				  if($reqStatusKirim == "1"){}
				  else
				  {
				  ?>
                  <input type="hidden" name="reqStatusTugasIjinBelajar" id="reqStatusTugasIjinBelajar" value="1" />
                  <input type="hidden" name="reqSatuanKerjaPegawaiUsulanId" id="reqSatuanKerjaPegawaiUsulanId" value="<?=$reqSatuanKerjaPegawaiUsulanId?>" />
                  <input type="hidden" name="reqJabatanRiwayatAkhirId" id="reqJabatanRiwayatAkhirId" value="<?=$reqJabatanRiwayatAkhirId?>" />
                  <input type="hidden" name="reqPendidikanRiwayatAkhirId" id="reqPendidikanRiwayatAkhirId" value="<?=$reqPendidikanRiwayatAkhirId?>" />
                  <input type="hidden" name="reqGajiRiwayatAkhirId" id="reqGajiRiwayatAkhirId" value="<?=$reqGajiRiwayatAkhirId?>" />
                  <input type="hidden" name="reqPangkatRiwayatAkhirId" id="reqPangkatRiwayatAkhirId" value="<?=$reqPangkatRiwayatAkhirId?>" />
                  
                  <input type="hidden" name="reqJenis" id="reqJenis" value="<?=$reqJenis?>" />
                  <input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqRowDetilId" value="<?=$reqRowDetilId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <button type="submit" style="display:none" id="reqSubmit"></button>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                    Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
				  }
                  ?>
                  
                  <?
				  if($reqRowId == ""){}
				  else
				  {
                  ?>
                  <!-- <button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakrekomendasimodal">Cetak Rekomendasi
                    <i class="mdi-content-inbox left hide-on-small-only"></i>
                  </button> -->
                  <?
				  }
                  ?>
                  
                  <?
				  if($reqRowId == ""){}
				  else
				  {
					  if($reqStatusKirim == "1"){}
					  else
					  {
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="tambah">Tambah Lainnya
                    <i class="mdi-content-add left hide-on-small-only"></i>
                  </button>
                  <?
					  }
				  }
                  ?>
                  
                </div>
              </div>

              <!-- </div> -->
            </form>
          </div>
        </li>
      </ul>
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

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
	
	$("#tambah").click(function() { 
	  document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_pendidikan_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});
	
	$("#kembali").click(function() { 
	  document.location.href = "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});
	
  $("#reqcetakrekomendasimodal").click(function() { 
    parent.openModal('app/loadUrl/persuratan/tanda_tangan_pilih_badan?reqStatusBkdUptId=1&reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqSatuanKerjaId=<?=$reqSatkerAsalId?>')
    // parent.openModal('app/loadUrl/persuratan/tanda_tangan_pilih_usulan?reqUptBkdJenis=&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenis=<?=$reqJenis?>&reqSatuanKerjaId=<?=$reqSatkerAsalId?>')
  });

	$("#reqcetakrekomendasi").click(function() { 
	  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_rekomendasi&reqUrl=ijin_belajar_surat_rekomendasi&reqId=<?=$reqRowId?>", 'Cetak');
	  newWindow.focus();
	});
	
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>