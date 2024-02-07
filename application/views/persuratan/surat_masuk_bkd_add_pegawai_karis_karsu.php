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
  $set->selectByParamsMonitoringKaris(array(), -1, -1, $statement);
  $set->firstRow();
  //echo $set->query;exit;

  $reqSatuanKerjaPegawaiUsulanId= $set->getField('SATUAN_KERJA_ID');
  $reqPegawaiId= $set->getField('PEGAWAI_ID');
  
  $reqJabatanRiwayatAkhirId= $set->getField('JABATAN_RIWAYAT_AKHIR_ID');
  //$reqRowId= $set->getField('JABATAN_RIWAYAT_SEKARANG_ID');
  $reqPendidikanRiwayatAkhirId= $set->getField('PENDIDIKAN_RIWAYAT_AKHIR_ID');
  $reqRowDetilId= $set->getField('SURAT_MASUK_KARSU_ID');
  $reqGajiRiwayatAkhirId= $set->getField('GAJI_RIWAYAT_AKHIR_ID');
  //$reqRowId= $set->getField('GAJI_RIWAYAT_SEKARANG_ID');
  $reqPangkatRiwayatAkhirId= $set->getField('PANGKAT_RIWAYAT_AKHIR_ID');

  $reqSuamiIstriTerakhirId= $set->getField('SUAMI_ISTRI_ID');
  $reqSuamiIstriPisahTerakhirId= $set->getField('SUAMI_ISTRI_PISAH_ID');
  $reqSuamiIstriTerakhirNama= $set->getField('SUAMI_ISTRI_NAMA');
  $reqSuamiIstriTerakhirTanggalLahir= dateToPageCheck($set->getField('SUAMI_ISTRI_TANGGAL_LAHIR'));
  $reqSuamiIstriTerakhirTanggalNikah= dateToPageCheck($set->getField('SUAMI_ISTRI_TANGGAL_KAWIN'));
  $reqSuamiIstriTerakhirPernikahanPertamaPnsStatus= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS');
  $reqSuamiIstriTerakhirPernikahanPertamaPnsStatusNama= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA');
  $reqSuamiIstriTerakhirPernikahanPertamaPnsSi= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I');
  $reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA');
  $reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal= dateToPageCheck($set->getField('SUAMI_ISTRI_PERTAMA_PNS_TANGGAL'));
  $reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS');
  $reqSuamiIstriTerakhirPernikahanPertamaPasanganStatusNama= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_NAMA');
  // echo $reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus;exit();
  $reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I');
  $reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusNama= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I_NAMA');
  $reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal= dateToPageCheck($set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_TANGGAL'));

  $reqJenisKesalahan= $set->getField('JENIS_KESALAHAN');
  $reqTertulis= $set->getField('TERTULIS');
  $reqSeharusnya= $set->getField('SEHARUSNYA');
  //$reqRowId= $set->getField('PANGKAT_RIWAYAT_SEKARANG_ID');
          
  $reqNipBaru= $set->getField('NIP_BARU');
  $reqNamaPegawai= $set->getField('NAMA_LENGKAP');
  $reqJenisKarpeg= $set->getField('JENIS_KARSU');
  $reqNoSuratKehilangan= $set->getField('NO_SURAT_KEHILANGAN');
  $reqTanggalSuratKehilangan= dateToPageCheck($set->getField('TANGGAL_SURAT_KEHILANGAN'));
  $reqKeterangan= $set->getField('KETERANGAN');
  
  $reqJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
  $reqJabatanNamaEselon= $set->getField('JABATAN_RIWAYAT_ESELON');
  $reqJabatanNamaTmt= datetimeToPage($set->getField('JABATAN_RIWAYAT_TMT'), "date");
  $reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
  $reqPangkatRiwayatAkhirTmt= dateToPageCheck($set->getField('PANGKAT_RIWAYAT_TMT'));

  $reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_USULAN_NAMA');

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
    
    // $('#reqData4,#reqData6').validatebox({required: true});
    //$('#reqKeterangan').val("terdapat kesalahan pada "+reqKeteranganDetil+"... tertulis "+reqKeteranganDetil+"... seharusnya yang benar "+reqKeteranganDetil+"...");
    
    $('#reqKeterangan').validatebox({required: true});
  }
  
  function setPasanganPertama()
  {
    var reqSuamiIstriTerakhirPernikahanPertamaPnsSi= "";
    reqSuamiIstriTerakhirPernikahanPertamaPnsSi= $("#reqSuamiIstriTerakhirPernikahanPertamaPnsSi").val();

    $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel, #reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel").hide();
    if(reqSuamiIstriTerakhirPernikahanPertamaPnsSi == "0" || reqSuamiIstriTerakhirPernikahanPertamaPnsSi == ""){}
    else
    {
      $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel, #reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel").show();
    }

    var reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= "";
    reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus").val();
    $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusLabel").hide();
    // alert(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus);
    if(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus == "1" || reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus == "")
    {
      $('#reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal').validatebox({required: false});
      $('#reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal').removeClass('validatebox-invalid');
    }
    else
    {
      $('#reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal').validatebox({required: true});
      $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusLabel").show();
    }

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
    
    $("#reqKeteranganInfo").show();
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
      // $("#reqKeteranganInfo").hide();
      $("#keteranganlabeldetil").show();
      $("#reqData1").val("terdapat kesalahan pada "+reqKeteranganDetil);
      $("#reqData3").val("tertulis ");
      $("#reqData5").val("seharusnya yang benar ");
      
      // $('#reqData4,#reqData6').validatebox({required: true});
      //$('#reqKeterangan').val("terdapat kesalahan pada "+reqKeteranganDetil+"... tertulis "+reqKeteranganDetil+"... seharusnya yang benar "+reqKeteranganDetil+"...");
      <?
      }
      ?>
      // $('#reqKeterangan').validatebox({required: true});
      $("#reqKeteranganInfoDetil").show();
      $("#keteranganlabeldetil").show();
    }
    else
    {
      
    }

    if (window.parent && window.parent.document)
    {
      if (typeof window.parent.iframeLoaded === 'function')
      {
        parent.iframeLoaded();
      }
    }
  }

  // reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus;reqSelect1;reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus;reqSelect2
  function setSelect1()
  {
    var reqSelect1= "";
    reqSelect1= $("#reqSelect1").val();
    $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus").val(reqSelect1);
    setPasanganPertama();

    reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus").val();
    reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus").val();

    // alert(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus);

    if(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus == "1")
    {
      $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus,#reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal").val("");
    }
    else
    {
      if(reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus == "")
      $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus").val("2");
    }
  }

  function setSelect2()
  {
    var reqSelect2= "";
    reqSelect2= $("#reqSelect2").val();
    reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus").val();
    // alert(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus);

    if(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus == "1")
    {
      $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus,#reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal").val("");
    }
    else
    {
      $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus").val(reqSelect2);
    }
  }

  $(function(){
    $(".preloader-wrapper").hide();

	  $("#reqSelect1").change(function(){
       setSelect1();
      });

    $("#reqSelect2").change(function(){
        var reqSelect2= "";
    reqSelect2= $("#reqSelect2").val();
    $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus").val(reqSelect2);
      });

    setJenisKarpeg();
    setPasanganPertama();
    setSelect1();
    setSelect2();

    <?
    if($reqRowId == ""){}
    else
    {
    ?>
    // alert("<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsStatus?> + <?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus?>");
    $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel, #reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel").hide();
    if("<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsStatus?>" == "1")
    {
    $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel, #reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel").show();
    }

    $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusLabel").hide();
    if("<?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus?>" == "0")
    {
    $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusLabel").show();
    }
    <?
    }
    ?>

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
      url:'surat/surat_masuk_pegawai_json/add_karsu_dinas',
      onSubmit:function(){
  			// var reqPegawaiId= "";
        // reqPegawaiId= $("#reqPegawaiId").val();
  			// reqKeterangan= $("#reqKeterangan").val();

        // if(reqKeterangan == "")
        // {
        //   mbox.alert("Isi Keterangan terlebih dahulu", {open_speed: 0});
        //   //$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
        //   return false;
        // }

  			// if(reqPegawaiId == "")
  			// {
  			// 	mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
  			// 	//$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
  			// 	return false;
  			// }

        var reqPegawaiId= "";
        reqPegawaiId= $("#reqPegawaiId").val();
        reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus").val();
        reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus").val();
        reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal= $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal").val();
        // alert(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus);return false;
        
        if(reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus == "0" && reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal == "")
        {
          mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
          //$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
          return false;
        }

        if(reqPegawaiId == "")
        {
          mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
          //$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
          return false;
        }

        var reqJenisKarpeg= reqData4= reqData6= reqNoSuratKehilangan= reqTanggalSuratKehilangan= ""
        reqJenisKarpeg= $("#reqJenisKarpeg").val();
        reqData4= $("#reqData4").val();
        reqData6= $("#reqData6").val();
        reqNoSuratKehilangan= $("#reqNoSuratKehilangan").val();
        reqTanggalSuratKehilangan= $("#reqTanggalSuratKehilangan").val();

        if(reqJenisKarpeg == "2")
        {
          if(reqData4 == "" || reqData6 == "")
          {
            mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
            //$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
            return false;
          }
        }
        else if(reqJenisKarpeg == "3")
        {
          if(reqNoSuratKehilangan == "" || reqTanggalSuratKehilangan == "")
          {
            mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
            //$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
            return false;
          }
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
  				  document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_karis_karsu/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId="+rowid;
  				}, 1000));
  				$(".mbox > .right-align").css({"display": "none"});
  			}
          
      }
    });

    $('input[id^="reqNipBaru"]').autocomplete({
      source:function(request, response){
        // var win = $.messager.progress({title:'Proses Pencarian Data', msg:'Proses Pencarian Data...'});

        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqNipBaru') !== -1)
        {
          urlAjax= "pendidikan_riwayat_json/cari_pegawai_karsu_usulan?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqMode=1";
        }

        // cegah 10 karakter baru bisa cari
        valcari= request.term;
        panjangcari= valcari.length;
        if(panjangcari < 10) return false;
        // console.log(panjangcari);

        $(".preloader-wrapper").show();

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
                , pangkatkode: element['pangkatkode'], jabatannama: element['jabatannama']
                , jabataneselon: element['jabataneselon'], jabatantmt: element['jabatantmt'], pangkattmt: element['pangkattmt']
                , suamiistriid: element['suamiistriid'], suamiistrinama: element['suamiistrinama'], suamiistritanggallahir: element['suamiistritanggallahir']
                , suamiistritanggalkawin: element['suamiistritanggalkawin'], suamiistripertamapnsstatus: element['suamiistripertamapnsstatus']
                , suamiistripertamapnsstatusnama: element['suamiistripertamapnsstatusnama'], suamiistripertamapnsstatussi: element['suamiistripertamapnsstatussi'], suamiistripertamapnsstatussinama: element['suamiistripertamapnsstatussinama'], suamiistripertamapnstanggal: element['suamiistripertamapnstanggal'], suamiistripisahid: element['suamiistripisahid']
                };
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

          jabataneselon= ui.item.jabataneselon;
          jabatantmt= ui.item.jabatantmt;
          pangkattmt= ui.item.pangkattmt;

          suamiistriid= ui.item.suamiistriid;
          suamiistrinama= ui.item.suamiistrinama;
          suamiistritanggallahir= ui.item.suamiistritanggallahir;
          suamiistritanggalkawin= ui.item.suamiistritanggalkawin;

          suamiistripertamapnsstatus= ui.item.suamiistripertamapnsstatus;
          suamiistripertamapnsstatusnama= ui.item.suamiistripertamapnsstatusnama;
          suamiistripertamapnsstatussi= ui.item.suamiistripertamapnsstatussi;
          suamiistripertamapnsstatussinama= ui.item.suamiistripertamapnsstatussinama;
          suamiistripertamapnstanggal= ui.item.suamiistripertamapnstanggal;

          suamiistripisahid= ui.item.suamiistripisahid;

          $("#reqNamaPegawai").val(namapegawai);
          $("#reqSatuanKerjaPegawaiUsulanId").val(satuankerjaid);
          $("#reqJabatanRiwayatAkhirId").val(jabatanriwayatid);
          $("#reqPendidikanRiwayatAkhirId").val(pendidikanriwayatid);
          $("#reqGajiRiwayatAkhirId").val(gajiriwayatid);
          $("#reqPangkatRiwayatAkhirId").val(pangkatriwayatid);
          $("#reqPangkatRiwayatAkhir").val(pangkatkode);
          $("#reqSatuanKerjaNama").val(satuankerjanama);
          $("#reqJabatanNama").val(jabatannama);
          
          $("#reqJabatanNamaEselon").val(jabataneselon);
          $("#reqJabatanNamaTmt").val(jabatantmt);
          $("#reqPangkatRiwayatAkhirTmt").val(pangkattmt);

          $("#reqSuamiIstriTerakhirId").val(suamiistriid);
          $("#reqSuamiIstriPisahTerakhirId").val(suamiistripisahid);

          $("#reqSuamiIstriTerakhirNama").val(suamiistrinama);
          $("#reqSuamiIstriTerakhirTanggalLahir").val(suamiistritanggallahir);
          $("#reqSuamiIstriTerakhirTanggalNikah").val(suamiistritanggalkawin);

          $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel, #reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel").hide();
          if(suamiistripertamapnsstatus == "0"){}
          else
          {
            $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel, #reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel").show();
          }

          $("#reqSuamiIstriTerakhirPernikahanPertamaPnsStatus").val(suamiistripertamapnsstatus);
          $("#reqSuamiIstriTerakhirPernikahanPertamaPnsStatusNama").val(suamiistripertamapnsstatusnama);
          $("#reqSuamiIstriTerakhirPernikahanPertamaPnsSi").val(suamiistripertamapnsstatussi);
          // $("#reqSelectSuamiIstriTerakhirPernikahanPertamaPnsSiStatus").val(suamiistripertamapnsstatussi);
          $("#reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus").val(suamiistripertamapnsstatussinama);
          $("#reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal").val(suamiistripertamapnstanggal);
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

    $("#reqSelectSuamiIstriTerakhirPernikahanPertamaPnsSiStatus").change(function(){
      $("#reqSuamiIstriTerakhirPernikahanPertamaPnsSi").val($(this).val());
    });

    $("#reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus").change(function(){
      setPasanganPertama();
    });

    $("#reqJenisKarpeg").change(function(){
      setJenisKarpeg();
    });
    
    $("#reqKeteranganDetil").change(function(){
      reqKeteranganDetil= $("#reqKeteranganDetil").val();
      $("#reqJenisKesalahan").val(reqKeteranganDetil);
      // setdetil();
    });
    
    $("#reqData4,#reqData6").keyup(function(){
      // setdatadetil();
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
       <li class="collection-item ubah-color-warna white-text">Pegawai Usul <?=$reqJenisNama?></li>
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
                  <label for="reqJenisKarpeg">Jenis Karsu</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                    <label for="reqNipBaru">NIP Baru</label>
                    <?
                    if($reqRowId == "")
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
            
              <div class="row">
                <div class="input-field col s12">
                    <label for="reqNamaPegawai" class="active">Nama Pegawai</label>
                    <input id="reqNamaPegawai" class="easyui-validatebox" type="text" value="<?=$reqNamaPegawai?>" disabled />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqPangkatRiwayatAkhir">Gol Terakhir</label>
                  <input placeholder type="text" id="reqPangkatRiwayatAkhir" value="<?=$reqPangkatRiwayatAkhir?>" disabled />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqPangkatRiwayatAkhirTmt">TMT Terakhir</label>
                  <input placeholder type="text" id="reqPangkatRiwayatAkhirTmt" value="<?=$reqPangkatRiwayatAkhirTmt?>" disabled />
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqJabatanNamaEselon">Eselon</label>
                  <input placeholder type="text" id="reqJabatanNamaEselon" value="<?=$reqJabatanNamaEselon?>" disabled />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqJabatanNama">Jabatan</label>
                  <input placeholder type="text" id="reqJabatanNama" value="<?=$reqJabatanNama?>" disabled />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqJabatanNamaTmt">Tmt Jabatan</label>
                  <input placeholder type="text" id="reqJabatanNamaTmt" value="<?=$reqJabatanNamaTmt?>" disabled />
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqSatuanKerjaNama">Satuan Kerja</label>
                  <input placeholder type="text" id="reqSatuanKerjaNama" value="<?=$reqSatuanKerjaNama?>" disabled />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqSuamiIstriTerakhirNama">Nama Pasangan</label>
                  <input placeholder type="text" id="reqSuamiIstriTerakhirNama" value="<?=$reqSuamiIstriTerakhirNama?>" disabled />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqSuamiIstriTerakhirTanggalLahir">Tanggal Lahir</label>
                  <input placeholder type="text" id="reqSuamiIstriTerakhirTanggalLahir" value="<?=$reqSuamiIstriTerakhirTanggalLahir?>" disabled />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqSuamiIstriTerakhirTanggalNikah">Tanggal Nikah</label>
                  <input placeholder type="text" id="reqSuamiIstriTerakhirTanggalNikah" value="<?=$reqSuamiIstriTerakhirTanggalNikah?>" disabled />
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqSuamiIstriTerakhirPernikahanPertamaPnsStatusNama">Pernikahan Pertama PNS</label>
                  <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPnsStatus" id="reqSuamiIstriTerakhirPernikahanPertamaPnsStatus" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsStatus?>" />
                  <input placeholder type="text" id="reqSuamiIstriTerakhirPernikahanPertamaPnsStatusNama" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsStatusNama?>" disabled />
                </div>
                <div id="reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatusLabel">
                <div class="input-field col s12 m3">
                  <?
                  if($reqRowId == "")
                  {
                  ?>
                    <select id="reqSelectSuamiIstriTerakhirPernikahanPertamaPnsSiStatus">
                      <option value=""></option>
                      <option value="2">Cerai Hidup</option>
                      <option value="3">Cerai Mati</option>
                    </select>
                    <label for="reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus">Status</label>
                    <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPnsSi" id="reqSuamiIstriTerakhirPernikahanPertamaPnsSi" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsSi?>" />
                    <!-- <input placeholder type="text" id="reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus?>" disabled /> -->
                  <?
                  }
                  else
                  {
                  ?>
                    <label for="reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus">Status</label>
                    <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPnsSi" id="reqSuamiIstriTerakhirPernikahanPertamaPnsSi" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsSi?>" />
                    <input placeholder type="text" id="reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsSiStatus?>" disabled />
                  <?
                  }
                  ?>
                </div>
                </div>
                <div id="reqSuamiIstriTerakhirPernikahanPertamaPnsTanggalLabel">
                <div class="input-field col s12 m3">
                  <label for="reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal">Tanggal</label>
                  <input placeholder="" type="text" class="easyui-validatebox" id="reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal" name="reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal');" data-options="validType:'dateValidPicker'" <?=$read?> value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal?>" <?=$disabled?> />
                </div>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m3">
                  <?
                  if($reqRowId == "")
                  {
                  ?>
                  <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus" />
                  <select id="reqSelect1">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                  <label for="reqSelect1">Pernikahan Pertama Pasangan</label>
                  <?
                  }
                  else
                  {
                  ?>
                  <label for="reqSuamiIstriTerakhirPernikahanPertamaPasanganStatusNama">Pernikahan Pertama Pasangan</label>
                  <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus?>" />
                  <input placeholder type="text" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganStatusNama" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganStatusNama?>" disabled />
                  <?
                  }
                  ?>
                </div>
                <div id="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusLabel">
                <div class="input-field col s12 m3">
                  <?
                  if($reqRowId == "")
                  {
                  ?>
                  <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus" />
                  <select id="reqSelect2">
                    <option value="2">Cerai Hidup</option>
                    <option value="3">Cerai Mati</option>
                  </select>
                  <label for="reqSelect2">Status</label>
                  <?
                  }
                  else
                  {
                  ?>
                  <label for="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusNama">Status</label>
                  <input type="hidden" name="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus?>" />
                  <input placeholder type="text" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusNama" value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatusNama?>" disabled />
                  <?
                  }
                  ?>
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal">Tanggal</label>
                  <input type="text" class="easyui-validatebox" id="reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal" name="reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal');" data-options="validType:'dateValidPicker'" <?=$read?> value="<?=$reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal?>" <?=$disabled?> />
                </div>
              </div>
              </div>
              <div class="row" id="reqLabelHilang">
                <div class="input-field col s12 m6">
                  <label for="reqNoSuratKehilangan">No Surat Kehilangan</label>
                  <input placeholder="" type="text" class="easyui-validatebox" id="reqNoSuratKehilangan" name="reqNoSuratKehilangan" <?=$read?> value="<?=$reqNoSuratKehilangan?>" <?=$disabled?> />
                </div>
                
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSuratKehilangan">Tanggal Surat Kehilangan</label>
                  <input placeholder="" type="text" class="easyui-validatebox" id="reqTanggalSuratKehilangan" name="reqTanggalSuratKehilangan" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSuratKehilangan');" data-options="validType:'dateValidPicker'" <?=$read?> value="<?=$reqTanggalSuratKehilangan?>" <?=$disabled?> />
                </div>
              </div>  
              
              <?
              if($reqRowId == "" || $reqJenisKarpeg == "2")
              {
              ?>
                <div id="keteranganlabeldetil">
                  <div class="row">
                    <input type="hidden" id="reqData1" value="terdapat kesalahan pada " />
                    <input type="hidden" id="reqData2" name="reqData2" />
                    <input type="hidden" id="reqData3" value="tertulis " />
                    <input type="hidden" id="reqData5" value="seharusnya yang benar " />
                <?
                if($reqRowId == "")
                {
                ?>
                  <div class="input-field col s12 m4">
                    <input type="hidden" class="easyui-validatebox" id="reqJenisKesalahan" name="reqJenisKesalahan" value="Nama" />
                    <select id="reqKeteranganDetil">
                      <option value="NIP PNS">NIP PNS</option>
                      <option value="Nama PNS">Nama PNS</option>
                      <option value="Nama Pasangan">Nama Pasangan</option>
                      <option value="Tanggal Perkawinan">Tanggal Perkawinan</option>
                      <option value="Foto PNS">Foto PNS</option>
                      <option value="Lainnya">Lainnya</option>
                      <!-- <option value="Nama">Nama</option>
                      <option value="Tempat Lahir">Tempat Lahir</option>
                      <option value="Tanggal Lahir">Tanggal Lahir</option>
                      <option value="NIP PNS">NIP PNS</option>
                      <option value="Nama PNS">Nama PNS</option> -->
                    </select>
                    <label placeholder for="reqKeteranganDetil">Jenis Kesalahan</label>
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqData4">Tertulis Salah</label>
                    <input placeholder="" type="text" class="easyui-validatebox" id="reqData4" name="reqTertulis" />
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqData6">Seharusnya</label>
                    <input placeholder="" type="text" class="easyui-validatebox" id="reqData6" name="reqSeharusnya" />
                  </div>
                <?
                }
                else
                {
                ?>
                  <div class="input-field col s12 m4">
                    <label for="reqJenisKesalahan">Jenis Kesalahan</label>
                    <input placeholder="" type="text" name="reqJenisKesalahan" id="reqJenisKesalahan" value="<?=$reqJenisKesalahan?>" class="color-disb" reaonly />
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqTertulis">Tertulis Salah</label>
                    <input placeholder="" type="text" name="reqTertulis" id="reqTertulis" value="<?=$reqTertulis?>" class="color-disb" reaonly />
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqSeharusnya">Seharusnya</label>
                    <input placeholder="" type="text" name="reqSeharusnya" id="reqSeharusnya" value="<?=$reqSeharusnya?>" class="color-disb" reaonly />
                  </div>
                <?
                }
                ?>
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
                    <input type="hidden" name="reqSuamiIstriTerakhirId" id="reqSuamiIstriTerakhirId" value="<?=$reqSuamiIstriTerakhirId?>" />
                    <input type="hidden" name="reqSuamiIstriPisahTerakhirId" id="reqSuamiIstriPisahTerakhirId" value="<?=$reqSuamiIstriPisahTerakhirId?>" />
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
                        <button class="btn purple waves-effect waves-light" style="font-size:9pt" type="button" id="reqselanjutnya">Selanjutnya
                          <i class="mdi-content-inbox left hide-on-small-only"></i>
                        </button>

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
	
  $("#reqselanjutnya").click(function() { 
    document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_lookup_verfikasi_karsu/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
  });

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
                    document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_karis_karsu/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
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
    document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_karis_karsu/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
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