<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukBkd');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqJenisSuratRekomendasi= setjenissuratrekomendasiinfo($reqJenis);
$reqMode= $this->input->get("reqMode");

$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
$set= new SuratMasukBkd();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
unset($set);

$disabled="";
if($reqRowId=="")
{
  $reqMode = 'insert';
  $reqNamaPegawai= $reqNamaPegawai= $reqPendidikanRiwayatAkhir= $reqStatusPendidikanTerakhirNama= $reqJurusanTerakhir= $reqNamaSekolahTerakhir= " ";
}
else
{
  $disabled="disabled";
  $reqMode = 'update';
  $statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
  $set= new SuratMasukPegawai();
  $set->selectByParamsMonitoring(array(), -1, -1, $statement);
  $set->firstRow();
  //echo $set->query;exit;

  $reqSatuanKerjaPegawaiUsulanId= $set->getField('SATUAN_KERJA_ID');
  $reqSuratMasukUptId= $set->getField('SURAT_MASUK_UPT_ID');
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
  $reqJenisKarpeg= $set->getField('JENIS_KARPEG');
  $reqNoSuratKehilangan= $set->getField('NO_SURAT_KEHILANGAN');
  $reqTanggalSuratKehilangan= dateToPageCheck($set->getField('TANGGAL_SURAT_KEHILANGAN'));
  $reqKeterangan= $set->getField('KETERANGAN');
  
  $reqJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
  $reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
  $reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_USULAN_NAMA');

  $reqKartuPegawaiLama= $set->getField('KARTU_PEGAWAI');

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
  
  <link rel="stylesheet" type="text/css" href="css/gaya.css">
  
  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
  
  <script type="text/javascript"> 
  	function setdatadetil()
	{
		var reqData1= reqData2= reqData3= reqData4= reqData5= reqData6= "";
		$('#reqKeterangan').val("");
		reqData1= $('#reqData1').val();
		reqData2= $('#reqData2').val();
		reqData3= $('#reqData3').val();
		reqData4= $('#reqData4').val();
		reqData5= $('#reqData5').val();
		reqData6= $('#reqData6').val();

		$('#reqKeterangan').val(reqData1+reqData2+" "+reqData3+reqData4+" "+reqData5+reqData6);
	}
	
  	function setdetil()
	{
		var reqKeteranganDetil= "";
		reqKeteranganDetil= $("#reqKeteranganDetil").val();
		
		$("#reqData1").val("terdapat kesalahan pada "+reqKeteranganDetil);
		$("#reqData3").val("tertulis ");
		$("#reqData5").val("seharusnya yang benar ");
		
		$('#reqData4,#reqData6').validatebox({required: true});
		//$('#reqKeterangan').val("terdapat kesalahan pada "+reqKeteranganDetil+"... tertulis "+reqKeteranganDetil+"... seharusnya yang benar "+reqKeteranganDetil+"...");
		
		$('#reqKeterangan').validatebox({required: true});
	}
	
	function setJenisKarpeg()
	{
		<?
		if($reqRowId == "")
		{
		?>
		$('#reqKeterangan').val("");
		<?
		}
		?>
		
		var reqJenisKarpeg= $("#reqJenisKarpeg").val();
		$("#reqKeteranganInfoDetil,#reqLabelHilang").hide();
		$('#reqNoSuratKehilangan,#reqTanggalSuratKehilangan,#reqKeterangan').validatebox({required: false});
		$('#reqNoSuratKehilangan,#reqTanggalSuratKehilangan,#reqKeterangan').removeClass('validatebox-invalid');
		
    $("#reqKeteranganInfo,#kartupegawailabeldetil").show();
		$("#keteranganlabeldetil").hide();
		$('#reqData4,#reqData6').validatebox({required: false});
		$('#reqData4,#reqData6').removeClass('validatebox-invalid');
		
		if(reqJenisKarpeg == "3")
		{
			$('#reqNoSuratKehilangan,#reqTanggalSuratKehilangan').validatebox({required: true});
			$("#reqLabelHilang").show();
		}
		else if(reqJenisKarpeg == "2")
		{
			var reqKeteranganDetil= "";
			reqKeteranganDetil= $("#reqKeteranganDetil").val();
			
			<?
			if($reqRowId == "")
			{
			?>
			$("#reqKeteranganInfo").hide();
			$("#keteranganlabeldetil").show();
			$("#reqData1").val("terdapat kesalahan pada "+reqKeteranganDetil);
			$("#reqData3").val("tertulis ");
			$("#reqData5").val("seharusnya yang benar ");
			
			$('#reqData4,#reqData6').validatebox({required: true});
			//$('#reqKeterangan').val("terdapat kesalahan pada "+reqKeteranganDetil+"... tertulis "+reqKeteranganDetil+"... seharusnya yang benar "+reqKeteranganDetil+"...");
			<?
			}
			?>
			$('#reqKeterangan').validatebox({required: true});
			$("#reqKeteranganInfoDetil").show();
		}
    else
    {
      $("#kartupegawailabeldetil").hide();
    }

    parent.iframeLoaded();
	}
	
    $(function(){
    $(".preloader-wrapper").hide();
      
	  setJenisKarpeg();

      $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }
        
        var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_bkd?reqId=<?=$reqId?>";
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
		url:'surat/surat_masuk_pegawai_json/add_karpeg_dinas',
        onSubmit:function(){
			var reqPegawaiId= "";
			reqPegawaiId= $("#reqPegawaiId").val();
			
			if(reqPegawaiId == "")
			{
				mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
				//$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
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
				  document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_karpeg/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId="+rowid;
				}, 1000));
				$(".mbox > .right-align").css({"display": "none"});
			}
          
        }
      });

      $('input[id^="reqNipBaru"]').autocomplete({
        source:function(request, response){
          // var win = $.messager.progress({title:'Proses Pencarian Data', msg:'Proses Pencarian Data...'});
          $(".preloader-wrapper").show();

          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          if (id.indexOf('reqNipBaru') !== -1)
          {
            urlAjax= "pendidikan_riwayat_json/cari_pegawai_karpeg_usulan?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqMode=1";
          }

          $.ajax({
            url: urlAjax,
            type: "GET",
            dataType: "json",
            data: { term: request.term },
            success: function(responseData){
              // $.messager.progress('close');
              $(".preloader-wrapper").hide();
              
              if(responseData == null)
              {
                response(null);
              }
              else
              {
                var array = responseData.map(function(element) {
                  return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai']
                  , satuankerjaid: element['satuankerjaid'], satuankerjanama: element['satuankerjanama']
                  , jabatanriwayatid: element['jabatanriwayatid'], pendidikanriwayatid: element['pendidikanriwayatid']
                  , gajiriwayatid: element['gajiriwayatid'], pangkatriwayatid: element['pangkatriwayatid']
                  , pangkatkode: element['pangkatkode'], jabatannama: element['jabatannama'], kartupegawailama: element['kartupegawailama']};
                });
                response(array);
              }
            }
          })
        },
        select: function (event, ui) 
        { 
          var id= $(this).attr('id');
          if (id.indexOf('reqNipBaru') !== -1)
          {
            var indexId= "reqPegawaiId";
            var namapegawai= pangkatkode= satuankerjaid= jabatanriwayatid= jabatannama= pendidikanriwayatid= gajiriwayatid= pangkatriwayatid= satuankerjanama= kartupegawailama= "";
            namapegawai= ui.item.namapegawai;
            pangkatkode= ui.item.pangkatkode;
            satuankerjaid= ui.item.satuankerjaid;
            jabatanriwayatid= ui.item.jabatanriwayatid;
            jabatannama= ui.item.jabatannama;
            pendidikanriwayatid= ui.item.pendidikanriwayatid;
            gajiriwayatid= ui.item.gajiriwayatid;
            pangkatriwayatid= ui.item.pangkatriwayatid;
            satuankerjanama= ui.item.satuankerjanama;
            kartupegawailama= ui.item.kartupegawailama;

            $("#reqNamaPegawai").val(namapegawai);
            $("#reqSatuanKerjaPegawaiUsulanId").val(satuankerjaid);
            $("#reqJabatanRiwayatAkhirId").val(jabatanriwayatid);
            $("#reqPendidikanRiwayatAkhirId").val(pendidikanriwayatid);
            $("#reqGajiRiwayatAkhirId").val(gajiriwayatid);
            $("#reqPangkatRiwayatAkhirId").val(pangkatriwayatid);
            $("#reqPangkatRiwayatAkhir").val(pangkatkode);
            $("#reqSatuanKerjaNama").val(satuankerjanama);
            $("#reqJabatanNama").val(jabatannama);

            $("#reqKartuPegawaiLama").val(kartupegawailama);
          }

          $("#"+indexId).val(ui.item.id).trigger('change');
        },
        autoFocus: true
      }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };

      $("#reqJenisKarpeg").change(function(){
       	setJenisKarpeg();
      });
	  
	  $("#reqKeteranganDetil").change(function(){
       setdetil();
      });
	  
	  $("#reqData4,#reqData6").keyup(function(){
        setdatadetil();
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
                <div class="input-field col s12 m12">
                    <select id="reqJenisKarpeg" name="reqJenisKarpeg" <?=$disabled?>>
                         <option value="1" <? if(1==$reqJenisKarpeg) echo 'selected'?>>Baru</option>
                         <option value="2" <? if(2==$reqJenisKarpeg) echo 'selected'?>>Revisi</option>
                         <option value="3" <? if(3==$reqJenisKarpeg) echo 'selected'?>>Kehilangan</option>
                    </select>
            		<label for="reqJenisKarpeg">Jenis Karpeg</label>
                </div>
            </div>

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
                <?
                if($reqRowId == "")
                {
                ?>
                <div class="input-field col s12 m6" id="reqKeteranganInfoDetil">
                </div>
                <?
                }
                ?>
            </div>
            
            <div id="kartupegawailabeldetil">
            <div class="row">
                <div class="input-field col s12">
                    <label for="reqKartuPegawaiLama" class="active">Kartu Pegawai Lama</label>
                    <input placeholder id="reqKartuPegawaiLama" class="easyui-validatebox" type="text"  value="<?=$reqKartuPegawaiLama?>" disabled />
                </div>
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
                  <label for="reqPangkatRiwayatAkhir">Gol Terakhir</label>
                  <input placeholder type="text" id="reqPangkatRiwayatAkhir" value="<?=$reqPangkatRiwayatAkhir?>" disabled />
                </div>
                <div class="input-field col s12 m9">
                  <label for="reqJabatanNama">Jabatan</label>
                  <input placeholder type="text" id="reqJabatanNama" value="<?=$reqJabatanNama?>" disabled />
                </div>
              </div>   
              <div>
              	<div class="input-field col s12 m12">
                  <label for="reqSatuanKerjaNama">Satuan Kerja</label>
                  <input placeholder type="text" id="reqSatuanKerjaNama" value="<?=$reqSatuanKerjaNama?>" disabled />
                </div>
              </div>
              <div class="row" id="reqLabelHilang">
                <div class="input-field col s12 m6">
                  <label for="reqNoSuratKehilangan">No Surat Kehilangan</label>
                  <input type="text" class="easyui-validatebox" id="reqNoSuratKehilangan"  name="reqNoSuratKehilangan" <?=$read?> value="<?=$reqNoSuratKehilangan?>" <?=$disabled?> />
                </div>
                
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSuratKehilangan">Tanggal Surat Kehilangan</label>
                  <input type="text" class="easyui-validatebox" id="reqTanggalSuratKehilangan" name="reqTanggalSuratKehilangan" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSuratKehilangan');" data-options="validType:'dateValidPicker'" <?=$read?> value="<?=$reqTanggalSuratKehilangan?>" <?=$disabled?> />
                </div>
              </div>  
              
              <?
              if($reqRowId == "")
              {
              ?>
              <div id="keteranganlabeldetil">
              <div class="row">
              	<input type="hidden" id="reqData1" value="terdapat kesalahan pada " />
                <input type="hidden" id="reqData2" name="reqData2" />
                <input type="hidden" id="reqData3" value="tertulis " />
                <input type="hidden" id="reqData5" value="seharusnya yang benar " />
                <div class="input-field col s12 m4">
                	<select id="reqKeteranganDetil">
                    	<option value="Nama">Nama</option>
                        <option value="NIP">NIP</option>
                        <option value="Tanggal Lahir">Tanggal Lahir</option>
                        <option value="TMT CPNS">TMT CPNS</option>
                        <!-- <option value="TMT PNS">TMT PNS</option> -->
                    </select>
                    <label placeholder for="reqKeteranganDetil">Jenis Kesalahan</label>
                </div>
              	<div class="input-field col s12 m4">
                	<label placeholder for="reqData4">Tertulis Salah</label>
                    <input type="text" class="easyui-validatebox" id="reqData4" name="reqData4" />
                </div>
                <div class="input-field col s12 m4">
                	<label placeholder for="reqData6">Seharusnya</label>
                    <input type="text" class="easyui-validatebox" id="reqData6" name="reqData6" />
                </div>
              </div>
              <div class="row">&nbsp;</div>
              <div class="row">&nbsp;</div>
              </div>
              <?
			        }
              ?>
              
              <div class="row" id="reqKeteranganInfo">
                <div class="input-field col s12 m12">
                  <label for="reqKeterangan">Keterangan</label>
                  <textarea placeholder name="reqKeterangan" id="reqKeterangan" class="easyui-validatebox materialize-textarea" <?=$disabled?>><?=$reqKeterangan?></textarea>
                </div>
              </div>  

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>
				  <?
				  if($reqSuratMasukUptId == "")
				  {
                  ?>
                  <?
				  if($reqStatusKirim == "1"){}
				  else
				  {
				  ?>
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
                  
                  <?
                  if($reqRowId == "")
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
                  <button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqhapus">Hapus
                    <i class="mdi-content-inbox left hide-on-small-only"></i>
                  </button>
                  
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="tambah">Tambah Lainnya
                    <i class="mdi-content-add left hide-on-small-only"></i>
                  </button>
                  <?
					  }
				  }
				  }
				  else
				  {
                  ?>
                  <button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakrekomendasi">Cetak Rekomendasi
                    <i class="mdi-content-inbox left hide-on-small-only"></i>
                  </button>
                  <?
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
    $(".preloader-wrapper").hide();
    $('select').material_select();
	
	$("#reqhapus").click(function() { 
      var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_bkd?reqId=<?=$reqId?>";
        $.ajax({'url': s_url,'success': function(dataajax){
          var requrl= requrllist= "";
          dataajax= String(dataajax);
          if(dataajax == '1')
          {
            mbox.alert('Data sudah dikirim', {open_speed: 0});
            return false;
          }
          else
          {
            mbox.custom({
               message: "Apakah Anda Yakin, Hapus data terpilih ?",
               options: {close_speed: 100},
               buttons: [
                 {
                   label: 'Ya',
                   color: 'green darken-2',
                   callback: function() {
                    $.getJSON("surat/surat_masuk_pegawai_json/delete_pegawai/?reqId=<?=$reqRowId?>",
                    function(data){
                      mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
                      {
                        clearInterval(interval);
                        document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_karpeg/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
                      }, 1000));
                      $(".mbox > .right-align").css({"display": "none"});
                      //$(".right-align").hide();
                    });
                     //console.log('do action for yes answer');
                     mbox.close();
                   }
                 },
                 {
                   label: 'Tidak',
                   color: 'grey darken-2',
                   callback: function() {
                     //console.log('do action for no answer');
                     mbox.close();
                   }
                 }
               ]
            });
          }
      }});
		
	});
	
	$("#tambah").click(function() { 
	  document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_karpeg/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});
	
	$("#kembali").click(function() { 
	  document.location.href = "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});
	
	$("#reqcetakrekomendasi").click(function() { 
	  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_rekomendasi&reqUrl=<?=$reqJenisSuratRekomendasi?>&reqId=<?=$reqRowId?>", 'Cetak');
	  newWindow.focus();
	});
	
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>