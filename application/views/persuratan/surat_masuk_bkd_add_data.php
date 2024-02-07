<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
if($reqId=="")
{
  $reqMode = 'insert';
  $reqKepada= $this->SATUAN_KERJA_URUTAN_SURAT_JABATAN;
  $reqTanggal= $tanggalHariIni;
  $tempPerihalInfo= $reqPerihal= setjenisperihalinfo($reqJenis);

  $statement_pengaturan= "
  AND 
  (
    (
    CURRENT_DATE BETWEEN A.TANGGAL_BATAS_AWAL_USUL AND A.TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH
    )
    OR
    (
    CURRENT_DATE BETWEEN A.TANGGAL_BATAS_AWAL_USUL AND A.TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS
    )
  )";
  
  $pengaturan_pangkat= new SuratMasukPegawai();
  $pengaturan_pangkat->selectByParamsPengaturanKenaikanPangkat(array(),-1,-1, $statement_pengaturan);
  // echo $pengaturan_pangkat->query;exit();
}
else
{
  $reqMode = 'update';
  $statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
  $set= new SuratMasukBkd();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();
  // echo $set->query;exit();

  $reqNomor= $set->getField("NOMOR");
  $reqAgenda= $set->getField("NO_AGENDA");
  $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
  $reqTanggalDiteruskan= dateToPageCheck($set->getField("TANGGAL_DITERUSKAN"));
  $reqTanggalBatas= dateToPageCheck($set->getField("TANGGAL_BATAS"));
  $reqKepada= $set->getField("KEPADA");
  $reqPerihal= $set->getField("PERIHAL");
  $reqSatkerTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
  $reqSatkerAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");
   
  $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
  $reqSatkerTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");
  $reqStatusKirim= $set->getField("STATUS_KIRIM");
   
  if($reqStatusKirim == "1")
  {
	  /*$statement= " AND SMP.JENIS_ID = ".$reqJenis." AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
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
  else
  {
    $tempPerihalInfo= $reqPerihal;
  }
   
  $reqKategori= $set->getField("KATEGORI");
  $reqKategoriNama= $set->getField("KATEGORI_NAMA");

  $reqJenis= $set->getField("JENIS_ID");
  $reqPengaturanKenaikanPangkatId= $set->getField("PENGATURAN_KENAIKAN_PANGKAT_ID");
  $reqPengaturanKenaikanPangkatTanggalPeriode= $set->getField("PENGATURAN_KENAIKAN_PANGKAT_TANGGAL_PERIODE");

  $reqJkmNomor= $set->getField("JKM_NOMOR");
  $reqJkmTanggal= dateToPageCheck($set->getField("JKM_TANGGAL"));
}

$vfpeg= new globalfilepegawai();
$arrpilihpensiun= $vfpeg->pilihpensiun();
// print_r($arrpilihpensiun);exit;
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

  <?
  if($reqId == "" && $reqJenis == 7)
  {
  ?>

    function setinfopensiun()
    {
      var reqKategori= reqDataPerihal= reqDataPerihal1= reqDataPerihal2= "";
      reqKategori= $("#reqKategori").val();

      if(reqKategori == "bup")
      {
        $("#reqDataPerihal2").val("BUP");
      }
      else if(reqKategori == "meninggal")
      {
        $("#reqDataPerihal2").val("Janda/Duda");
      }
      else if(reqKategori == "dini")
      {
        $("#reqDataPerihal2").val("APS(Dini)");
      }
      else if(reqKategori == "udzur")
      {
        $("#reqDataPerihal2").val("Udzur");
      }

      $(".infojkm").hide();
      $("#reqJkmNomor,#reqJkmTanggal").validatebox({required: false});
      $("#reqJkmNomor,#reqJkmTanggal").removeClass('validatebox-invalid');
      if(reqKategori == "meninggal")
      {
        $(".infojkm").show();
        $("#reqJkmNomor,#reqJkmTanggal").validatebox({required: true});
        $("#reqJkmNomor").val("");
        $("#reqJkmTanggal").val("<?=$tanggalHariIni?>");
      }

      reqDataPerihal1= $("#reqDataPerihal1").val();
      reqDataPerihal2= $("#reqDataPerihal2").val();

      reqDataPerihal= reqDataPerihal1 + ' ' + reqDataPerihal2;
      $("#reqPerihal").val(reqDataPerihal);
      $("#reqDataPerihal").val(reqDataPerihal);
    }

    setinfopensiun();
    $("#reqKategori").change(function(){
      setinfopensiun();
    });

  <?
  }
  ?>
  
	$("#reqsimpan").click(function() { 
		if($("#ff").form('validate') == false){
			return false;
		}

    <?
    // kalau jenis kenaikan pangkat periode harus diisi
    if($reqJenis=="10")
    {
    ?>
      var reqPengaturanKenaikanPangkatId= "";
      reqPengaturanKenaikanPangkatId= $("#reqPengaturanKenaikanPangkatId").val();
      // console.log("--"+reqPengaturanKenaikanPangkatId);
      if(reqPengaturanKenaikanPangkatId == "" || reqPengaturanKenaikanPangkatId == null)
      {
        mbox.alert('Data tidak bisa disimpan, karena Periode belum diisi', {open_speed: 0});
        return false;
      }
    <?
    }
    ?>
		
		var reqTanggal= "";
		reqTanggal= $("#reqTanggal").val();
		
		var s_url= "hari_libur_json/checkHariLibur?reqTanggal="+reqTanggal;
		$.ajax({'url': s_url,'success': function(dataajax){
			if(dataajax == '1')
			{
				mbox.alert('Data tidak bisa disimpan, karena tanggal yang anda tulis masuk dalam hari libur', {open_speed: 0});
				return false;
			}
			else
			$("#reqSubmit").click();
		}});
	});

  $('#ff').form({
    url:'surat/surat_masuk_bkd_json/add',
    onSubmit:function(){
      if($(this).form('validate')){}
      else
      {
        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
        return false;
      }
    },
    success:function(data){
      //console.log(data);return false;
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
          parent.reloadparenttab();
          <?
          if($reqId == "")
          {
          ?>
            top.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
          <?
          }
          else
          {
          ?>
            parent.reloadinfosurat(rowid);
            document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_data/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
          <?
          }
          ?>
        }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }

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
      <div class="col s12 m12" style="padding-left: 15px;">
        
        <ul class="collection card">
          <li class="collection-item ubah-color-warna">Usulan Pelayanan <?=$reqJenisNama?></li>
          <li class="collection-item">

            <div class="row">
              <form id="ff" method="post" enctype="multipart/form-data">
              	<?
                if($reqJenis=="7")
                {
                ?>
                <div class="row">
                  <div class="input-field col s12">
                    <?
                    if($reqId=="")
                    {
                    ?>
                      <select name="reqKategori" <?=$disabled?> id="reqKategori">
                        <?
                        foreach ($arrpilihpensiun as $key => $value)
                        {
                          $optionid= $value["id"];
                          $optiontext= $value["nama"];
                        ?>
                          <option value="<?=$optionid?>"><?=$optiontext?></option>
                        <?
                        }
                        ?>
                      </select>
                      <label for="reqKategori">Jenis Pensiun</label>
                    <?
                    }
                    else
                    {
                    ?>
                      <label for="reqKategori">Jenis Pensiun</label>
                      <input type="hidden" name="reqKategori" id="reqKategori" value="<?=$reqKategori?>" />
                      <input type="text" disabled value="<?=$reqKategoriNama?>" />
                    <?
                    }
                    ?>
                  </div>
                </div>
                <?
                }
                elseif($reqJenis=="10")
                {
                  if($reqId=="")
                  {
                ?>
                  <div class="row">
                    <div class="input-field col s12">
                      <select name="reqPengaturanKenaikanPangkatId" <?=$disabled?> id="reqPengaturanKenaikanPangkatId">
                        <option value=""></option>
                        <?
                        while($pengaturan_pangkat->nextRow())
                        {
                        ?>
                            <option value="<?=$pengaturan_pangkat->getField("PENGATURAN_KENAIKAN_PANGKAT_ID")?>"><?=getFormattedDateJson($pengaturan_pangkat->getField("TANGGAL_PERIODE"))?></option>
                        <?
                        }
                        ?>
                        </select>
                        <label for="reqPengaturanKenaikanPangkatId">Periode</label>
                    </div>
                  </div>

                  <!-- <div class="row">
                    <div class="input-field col s12">
                      <select name="reqJenisKenaikanPangkat" <?=$disabled?> id="reqJenisKenaikanPangkat">
                        <option value="kpstruktural">Kenaikan Pangkat Struktural</option>
                        <option value="kpreguler34">Kenaikan Pangkat Reguler Gol III/d Ke bawah</option>
                        <option value="kpreguler41">Kenaikan Pangkat Reguler Gol IV/a Ke atas</option>
                      </select>
                      <label for="reqJenisKenaikanPangkat">Jenis Kenaikan Pangkat</label>
                    </div>
                  </div> -->
                  <?
                  }
                  else
                  {
                  ?>
                  <div class="row">
                    <div class="input-field col s12">
                      <label for="reqPengaturanKenaikanPangkatTanggalPeriode">Periode</label>
                      <input type="hidden" name="reqPengaturanKenaikanPangkatId" id="reqPengaturanKenaikanPangkatId" value="<?=$reqPengaturanKenaikanPangkatId?>" />
                      <input placeholder="" type="text" id="reqPengaturanKenaikanPangkatTanggalPeriode" disabled value="<?=getFormattedDateJson($reqPengaturanKenaikanPangkatTanggalPeriode)?>" />
                    </div>
                  </div>

                   <!-- <label for="reqJenisKenaikanPangkat">Jenis Kenaikan Pangkat</label>
                   <input type="hidden" name="reqJenisKenaikanPangkat" id="reqJenisKenaikanPangkat" value="<?=$reqJenisKenaikanPangkat?>" />
                   <input type="text" disabled value="<?=$reqJenisKenaikanPangkatNama?>" /> -->
            		  <?
            		  }
                  ?>
                    <!-- </div>
                  </div> -->
                <?
              	}
                ?>

                <div class="row">
                  <div class="input-field col s12">
                    <label for="reqSatuanKerjaAsalNama">Satuan Kerja</label>
                    <input type="hidden" name="reqSatuanKerjaAsalId" id="reqSatuanKerjaAsalId" value="<?=$this->SATUAN_KERJA_ID?>" />
                    <input placeholder="" type="text" id="reqSatuanKerjaAsalNama" disabled value="<?=$this->SATUAN_KERJA_NAMA?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqSatuanKerjaTujuanNama">Satuan Kerja Tujuan</label>
                    <input type="hidden" name="reqSatuanKerjaTujuanId" id="reqSatuanKerjaTujuanId" value="<?=$this->SATUAN_KERJA_URUTAN_SURAT?>" />
                    <input placeholder="" type="text" id="reqSatuanKerjaTujuanNama" disabled value="<?=$this->SATUAN_KERJA_URUTAN_SURAT_NAMA?>"  />
                  </div>
                  <div class="input-field col s12 m6">
                        <label for="reqKepada">Kepada</label>
                        <input type="hidden" name="reqKepada" value="<?=$reqKepada?>" />
                        <input placeholder="" type="text" id="reqKepada" <?=$read?> value="<?=$reqKepada?>" disabled />
                  </div>
                </div>

                <?
                // if($reqJenis == "7" || $reqJenis == "10")
                // {
                  $infolabel= "";
                  if($reqJenis == "7")
                    $infolabel= "Pensiun";
                  else if($reqJenis == "10")
                    $infolabel= "Kenaikan Pangkat";
                ?>
                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNomor">Nomor Pengantar <?=$infolabel?></label>
                    <input placeholder="" required class="easyui-validatebox" type="text" name="reqNomor" id="reqNomor" <?=$read?> value="<?=$reqNomor?>"/>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggal">Tanggal Pengantar <?=$infolabel?></label>
                    <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggal" id="reqTanggal" <?=$read?> value="<?=$reqTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggal');"/>
                  </div>
                </div>
                <?
                // }
                ?>

                <?
                $jkmrequired= "";
                $jkmshow= "none";
                if(!empty($reqId) && $reqJenis=="7" && $reqKategori == "meninggal")
                {
                  $jkmrequired= "required";
                  $jkmshow= "";
                }
                ?>
                <div class="row infojkm" style="display: <?=$jkmshow?>">
                  <div class="input-field col s12 m6">
                    <label for="reqJkmNomor">Nomor Pengantar Usul JKM/JKK</label>
                    <input <?=$jkmrequired?> placeholder="" class="easyui-validatebox" type="text" name="reqJkmNomor" id="reqJkmNomor" <?=$read?> value="<?=$reqJkmNomor?>"/>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqJkmTanggal">Tanggal Pengantar Usul JKM/JKK</label>
                    <input <?=$jkmrequired?> placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJkmTanggal" id="reqJkmTanggal" <?=$read?> value="<?=$reqJkmTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqJkmTanggal');"/>
                  </div>
                </div>
                  
                <div class="row">
                  <div class="input-field col s12">
                    <label for="reqPerihal">Perihal</label>
                    <input type="hidden" id="reqDataPerihal1" value="<?=$reqPerihal?>" />
                    <input type="hidden" id="reqDataPerihal2" value="BUP" />
                    <input type="hidden" name="reqPerihal" id="reqDataPerihal" value="<?=$reqPerihal?>" />
                    <input placeholder="" type="text" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>" disabled />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m12">
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqJenis" value="<?=$reqJenis?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <?
    								if($reqStatusKirim == "1"){}
    								else
    								{
    								?>
                      <button type="submit" style="display:none" id="reqSubmit"></button>
                      <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                      Simpan
                      <i class="mdi-content-save left hide-on-small-only"></i>
                      </button>
                    <?
    									if($reqId == ""){}
    									else
    									{
                    ?>
                      <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="reqkirim">Kirim
                          <i class="mdi-content-forward left hide-on-small-only"></i>
                      </button>
                    <?
                      }
					          }
                    ?>

                    <?
                    if($reqStatusKirim == "1")
                    {
                      $infocetakdetil= "";
                      if($reqJenis=="7")
                        $infocetakdetil= "Pensiun";
                      else if($reqJenis=="10")
                        $infocetakdetil= "Kenaikan Pangkat";

                    ?>
                      <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakpengantarmodal">Cetak Pengantar <?=$infocetakdetil?>
                          <i class="mdi-content-inbox left hide-on-small-only"></i>
                      </button>

                      <?
                      if(!empty($reqId) && $reqJenis=="7" && $reqKategori == "meninggal")
                      {
                      ?>
                      <button class="btn purple waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakpengantarjkmjkk">Cetak Pengantar JKM/JKK
                          <i class="mdi-content-inbox left hide-on-small-only"></i>
                      </button>
                    <?
                      }
                    }
                    ?>

                      <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="parent.closeparenttab()">Close
                         <i class="mdi-navigation-close left hide-on-small-only"></i>
                      </button>
                          
                  </div>
                </div>
              </form>
            </div>
          </li>
        </ul>

      </div>
    </div>
  </div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
	
  	$("#reqkirim").click(function() { 
      var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_dinas?reqId=<?=$reqId?>";
      $.ajax({'url': s_url,'success': function(dataajax){
        var requrl= requrllist= "";
        dataajax= String(dataajax);
        var element = dataajax.split('-'); 
        dataajax= element[0];
        requrl= element[1];
        requrllist= element[2];
        requrlcss= element[3];
  		
    		if(dataajax == '0')
    		{
    			mbox.alert('Pilih Usulan pegawai terlebih dahulu', {open_speed: 0});
    		}
        else if(dataajax == 'xxx')
        {
          mbox.alert('Ada Usulan pegawai e file belum valid.', {open_speed: 0});
        }
    		else
    		{
      		var reqSatuanKerjaTujuanNama= "";
      		reqSatuanKerjaTujuanNama= $("#reqSatuanKerjaTujuanNama").val();
  		
          mbox.custom({
            message: "Apakah Anda Yakin, Kirim Ke "+reqSatuanKerjaTujuanNama+". Pastikan usulan pegawai sudah di isi ?",
            options: {close_speed: 100},
            buttons: [
            {
  				   label: 'Ya',
  				   color: 'green darken-2',
  				   callback: function() {
  					   $.getJSON("surat/surat_masuk_bkd_json/statuskirim/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqPerihal=<?=$tempPerihalInfo?>&reqStatusBerkas=5",
  						function(data){
  							mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
  							{
  								clearInterval(interval);
  								//$.messager.alert('Info', data.PESAN, 'info');
  								parent.reloadparenttab();
  								document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
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
  	  //document.location.href = "app/loadUrl/app/pegawai_add_anak_monitoring?reqId=<?=$reqId?>";
  	});
	
    $("#reqcetakpengantarmodal").click(function() { 
      parent.openModal('app/loadUrl/persuratan/tanda_tangan_pilih_badan?reqStatusBkdUptId=2&reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqSatuanKerjaId=<?=$reqSatkerAsalId?>')
    });
	
    $("#reqcetakpengantar").click(function() { 
      var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_dinas?reqId=<?=$reqId?>";
      $.ajax({'url': s_url,'success': function(dataajax){
        var requrl= requrllist= "";
        dataajax= String(dataajax);
        var element = dataajax.split('-'); 
        dataajax= element[0];
        requrl= element[1];
        requrllist= element[2];
        requrlcss= element[3];

        newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>", 'Cetak');
        newWindow.focus();
      }});
    });

  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>