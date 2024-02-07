<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('SkCpns');
$this->load->model('Pangkat');
$this->load->model('PangkatRiwayat');
$this->load->model('FormasiCpns');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqPeriode= $this->input->get("reqPeriode");
$reqStatusFile= $this->input->get("reqStatusFile");

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0102";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$set= new SkCpns();
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField('SK_CPNS_ID');
$reqNoNotaBakn= $set->getField('NO_NOTA');
$reqTanggalNotaBakn= dateToPageCheck($set->getField('TANGGAL_NOTA'));
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');

$reqNamaPejabatPenetap= $set->getField('NAMA_PENETAP');
$reqNipPejabatPenetap= $set->getField('NIP_PENETAP');
$reqNoSuratKeputusan= $set->getField('NO_SK');
$reqTanggalSuratKeputusan= dateToPageCheck($set->getField('TANGGAL_SK'));
$reqTerhitungMulaiTanggal= dateToPageCheck($set->getField('TMT_CPNS'));
$reqGolRuang= $set->getField('PANGKAT_ID');
$reqPangkatNama= $set->getField('PANGKAT_KODE');
$reqTanggalTugas= dateToPageCheck($set->getField('TANGGAL_TUGAS'));
$reqTh= $set->getField('MASA_KERJA_TAHUN');
$reqBl= $set->getField('MASA_KERJA_BULAN');

$reqGajiPokok= $set->getField('GAJI_POKOK');
$reqTanggalPersetujuanNip= dateToPageCheck($set->getField("TANGGAL_PERSETUJUAN_NIP"));
$reqNoPersetujuanNip= $set->getField("NO_PERSETUJUAN_NIP");
$reqPendidikan= $set->getField("PENDIDIKAN_NAMA");
$reqJurusan= $set->getField("PENDIDIKAN_JURUSAN_NAMA");

$reqFormasiCpnsId= $set->getField("FORMASI_CPNS_ID");
$reqJabatanTugas= $set->getField("JABATAN_TUGAS");

$reqJenisFormasiTugasId= $set->getField("JENIS_FORMASI_TUGAS_ID");
$reqJabatanFuId= $set->getField("JABATAN_FU_ID");
$reqJabatanFtId= $set->getField("JABATAN_FT_ID");
$reqStatusSkCpns= $set->getField("STATUS_SK_CPNS");
$reqSpmtNomor= $set->getField("SPMT_NOMOR");
$reqSpmtTanggal= dateToPageCheck($set->getField("SPMT_TANGGAL"));
$reqSpmtTmt= dateToPageCheck($set->getField("SPMT_TMT"));

if($reqRowId == ""){
  $reqMode = "insert";
}
else
{
  $reqMode = "update";
}

$formasi_cpns= new FormasiCpns();
$formasi_cpns->selectByParams(array());

$pangkat= new Pangkat();
$pangkat->selectByParams(array(), -1,-1, " AND A.PANGKAT_ID <= 32");

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "PANGKAT_RIWAYAT";
$reqDokumenKategoriFileId= "1"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable, $reqDokumenKategoriFileId);
// print_r($arrsetriwayatfield);exit;

$setdetil= new PangkatRiwayat();
$setdetil->selectByParams(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqId." AND A.JENIS_RIWAYAT = 1");
$setdetil->firstRow();
$vpangkatriwayatid= $setdetil->getField("PANGKAT_RIWAYAT_ID");
$vRowId= $vpangkatriwayatid;
$suratmasukpegawaiid="";
$paramriwayatfield="skcpns,notausulcpns";
$arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $vRowId, $suratmasukpegawaiid, $paramriwayatfield);
$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
// print_r($arrlistpilihfile);exit;
$arrlistriwayat= $arrlistriwayatfilepegawai["riwayat"];

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
    function setvalidasitanggal(tanggalawal, tanggalakhir, kondisi)
    {
      var infotanggalawal= infotanggalakhir= panjangmulai= panjangakhir= "";
      infotanggalawal= $("#"+tanggalawal).val();
      infotanggalakhir= $("#"+tanggalakhir).val();

      panjangmulai= infotanggalawal.length;
      panjangakhir= infotanggalakhir.length;

      if(panjangmulai == 10 && panjangakhir == 10)
      {

        var dt1= parseInt(infotanggalawal.substring(0,2),10); 
        var mon1= parseInt(infotanggalawal.substring(3,5),10) - 1;
        var yr1= parseInt(infotanggalawal.substring(6,10),10); 
        var dt2= parseInt(infotanggalakhir.substring(0,2),10); 
        var mon2= parseInt(infotanggalakhir.substring(3,5),10) - 1; 
        var yr2= parseInt(infotanggalakhir.substring(6,10),10);

        var datemulai= new Date(yr1, mon1, dt1);
        var dateakhir= new Date(yr2, mon2, dt2);

        statuskondisi= "1";
        if(kondisi == "<=")
        {
          if(datemulai <= dateakhir)
          {
            statuskondisi= "";
          }
        }
        else if(kondisi == ">=")
        {
          if(datemulai >= dateakhir)
          {
            statuskondisi= "";
          }
        }
        else if(kondisi == "tmt")
        {
          var dateduatahun= dateakhir;
          dateduatahun.setFullYear(dateduatahun.getFullYear() + 2);
          // console.log(datemulai);
          // console.log(dateakhir);
          // console.log(dateduatahun);

          if(datemulai >= dateakhir && dateakhir <= dateduatahun)
          {
            statuskondisi= "";
          }
        }

        if(statuskondisi == "1")
        {
          infopesan= "";
          if(tanggalawal == "reqTanggalNotaBakn")
          {
            infopesan= "Tanggal Nota BAKN ("+infotanggalawal+") harus lebih kecil sama dengan dari Tanggal Surat Keputusan ("+infotanggalakhir+")";
          }
          else if(tanggalawal == "reqTanggalPersetujuanNip")
          {
            infopesan= "Tanggal Persetujuan NIP ("+infotanggalawal+") harus lebih kecil sama dengan dari Tanggal Surat Keputusan ("+infotanggalakhir+")";
          }
          else if(tanggalawal == "reqSpmtTanggal")
          {
            infopesan= "Tanggal SPMT ("+infotanggalawal+") harus lebih besar sama dengan dari Tanggal Surat Keputusan ("+infotanggalakhir+")";
          }
          else if(tanggalawal == "reqSpmtTmt")
          {
            infopesan= "TMT SPMT ("+infotanggalawal+") harus lebih besar sama dengan dari Terhitung Mulai Tanggal ("+infotanggalakhir+")";
          }
          else if(tanggalawal == "reqTerhitungMulaiTanggal")
          {
            var y = dateduatahun.getFullYear();
            var m = dateduatahun.getMonth() + 1;
            var d = dateduatahun.getDate();

            infoduatahun= (d < 10 ? '0' + d : d) + '-' + (m < 10 ? '0' + m : m) + '-' + y;
            infopesan= "Terhitung Mulai Tanggal ("+infotanggalawal+") harus lebih besar sama dengan dari Tanggal Surat Keputusan ("+infotanggalakhir+") dan maksimal dari Tanggal ("+infoduatahun+")";
          }

          if(infopesan == ""){}
          else
          {
            mbox.alert(infopesan, {open_speed: 0});

            $('.mbox-wrapper .mbox-ok-button').click(function() {
              $("#"+tanggalawal).val("");
            });

          }
        }

      }
    }

    function setGaji()
    {
      var reqTglSk= reqPangkatId= reqMasaKerja= "";
      reqTglSk= $("#reqTanggalSuratKeputusan").val();
      reqPangkatId= $("#reqGolRuang").val();
      reqMasaKerja= $("#reqTh").val();

      urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
      $.ajax({'url': urlAjax,'success': function(data){
       //if(data == ''){}
         //else
         //{
           tempValueGaji= parseFloat(data);
           tempValueGaji= (tempValueGaji * 80) / 100;
           $("#reqGajiPokok").val(FormatCurrency(tempValueGaji));
         //}
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

      $("#reqTanggalNotaBakn,#reqTanggalPersetujuanNip,#reqTanggalSuratKeputusan,#reqSpmtTanggal,#reqSpmtTmt,#reqTerhitungMulaiTanggal").keyup(function(){
        varid= $(this).attr('id');

        if(varid == "reqTanggalNotaBakn")
        {
          setvalidasitanggal("reqTanggalNotaBakn", "reqTanggalSuratKeputusan", "<=")
        }
        else if(varid == "reqTanggalPersetujuanNip")
        {
          setvalidasitanggal("reqTanggalPersetujuanNip", "reqTanggalSuratKeputusan", "<=")
        }
        else if(varid == "reqSpmtTanggal")
        {
          setvalidasitanggal("reqSpmtTanggal", "reqTanggalSuratKeputusan", ">=")
        }
        else if(varid == "reqSpmtTmt")
        {
          setvalidasitanggal("reqSpmtTmt", "reqTerhitungMulaiTanggal", ">=")
        }
        // else if(varid == "reqTerhitungMulaiTanggal")
        // {
        //   setvalidasitanggal("reqTerhitungMulaiTanggal", "reqTanggalSuratKeputusan", "tmt")
        // }

      });

      $("#reqJenisFormasiTugasId").change(function(){
        reqJenisFormasiTugasId= $(this).val();
        // console.log(reqJenisFormasiTugasId);
        if(reqJenisFormasiTugasId !== "")
        {
          if(reqJenisFormasiTugasId == "1")
          {
            $("#reqJabatanCariTugas,#reqJabatanTugas,#reqJabatanFtId").val("");
          }
          else if(reqJenisFormasiTugasId == "2")
          {
            $("#reqJabatanCariTugas,#reqJabatanTugas,#reqJabatanFuId").val("");
          }
        }
        else
        {
          $("#reqJabatanCariTugas,#reqJabatanTugas,#reqJabatanFuId,#reqJabatanFtId").val("");
        }
      });

      $("#reqJabatanCariTugas").each(function() {
          $(this).autocomplete({
              source:function(request, response) {
                  var id= this.element.attr('id');
                  var replaceAnakId= replaceAnak= urlAjax= "";
                  reqJenisFormasiTugasId= $('#reqJenisFormasiTugasId').val();

                  if(reqJenisFormasiTugasId !== "")
                  {
                    if(reqJenisFormasiTugasId == "1")
                    {
                      $("#reqJabatanTugas,#reqJabatanFtId").val("");
                      urlAjax= "jabatan_fu_json/namajabatan";
                    }
                    else if(reqJenisFormasiTugasId == "2")
                    {
                      $("#reqJabatanTugas,#reqJabatanFuId").val("");
                      urlAjax= "jabatan_ft_json/namajabatan";
                    }

                    $.ajax({
                      url: urlAjax,
                      type: "GET",
                      dataType: "json",
                      data: { term: request.term },
                      success: function(responseData) {
                          // console.log(responseData);
                          if(responseData == null)
                          {
                            response(null);
                          }
                          else
                          { 
                            var array = responseData.map(function(element) {
                              return {desc: element['desc'], id: element['id'], label: element['label']};
                            });
                            response(array);
                          }
                      }
                    })
                }
                else
                {
                  $("#"+id+",#reqJabatanFuId,#reqJabatanFtId").val("");
                }
              },
              // select: function (event, ui) 
              focus: function (event, ui) 
              {
                reqJenisFormasiTugasId= $('#reqJenisFormasiTugasId').val();
                if(reqJenisFormasiTugasId !== "")
                {
                  var id= $(this).attr('id');
                  var infoid= infolabel= "";
                  infoid= ui.item.id;
                  infolabel= ui.item.label;

                  if (id.indexOf('reqJabatanCariTugas') !== -1)
                  {
                    $("#reqJabatanTugas").val(infolabel);

                    if(reqJenisFormasiTugasId == "1")
                    {
                      $("#reqJabatanFuId").val(infoid);
                    }
                    else if(reqJenisFormasiTugasId == "2")
                    {
                      $("#reqJabatanFtId").val(infoid);
                    }
                  }
                }
                  
              },
              autoFocus: true
          })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
          .append( "<a>" + item.desc  + "</a>" )
          .appendTo( ul );
        }
        ;
      });
	  
      $("#reqGolRuang").change(function(){
        setGaji();
      });

      $("#reqTanggalSuratKeputusan, #reqTh").keyup(function(){
        setGaji();
      });

      $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        $("#reqSubmit").click();
      });

      $("#reqsimpansetfile").click(function() { 
        if($("#fileff").form('validate') == false){
          return false;
        }

        $("#reqsetfile").click();
      });

      $('#fileff').form({
        url:'pegawai_file_json/setting',
        onSubmit:function(){

          var reqRiwayatTable= reqKualitasFileId= "";
          reqRiwayatTable= $("#reqRiwayatTable").val();
          reqKualitasFileId= $("#reqKualitasFileId").val();

          if(reqRiwayatId == "")
          {
            mbox.alert("Isikan Jenis dokumen terlebih dahulu", {open_speed: 0});
            return false;
          }

          if(reqKualitasFileId == "")
          {
            mbox.alert("Isikan Kualitas dokumen terlebih dahulu", {open_speed: 0});
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
              document.location.href = "app/loadUrl/app/pegawai_add_sk_cpns?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqStatusFile=file";
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
        }
      });


      $("#cekfile").click(function() {
        document.location.href = "app/loadUrl/app/pegawai_add_sk_cpns?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqStatusFile=file";
      });

      $('#ff').form({
        url:'sk_cpns_json/add',
        onSubmit:function(){
            
            reqJenisFormasiTugasId= $("#reqJenisFormasiTugasId").val();
            if(reqJenisFormasiTugasId == "")
            {
              mbox.alert("Lengkapi data terlebih dahulu, Jenis Formasi Tugas / Jab", {open_speed: 0});
              return false;
            }

            reqStatusSkCpns= $("#reqStatusSkCpns").val();
            if(reqStatusSkCpns == "")
            {
              mbox.alert("Lengkapi data terlebih dahulu, SK CPNS Kab Jombang", {open_speed: 0});
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
              document.location.href= "app/loadUrl/app/pegawai_add_sk_cpns/?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>";

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
          <li class="collection-item ubah-color-warna">EDIT SK CPNS</li>
          <li class="collection-item">

            <div class="row">
              <form id="ff" method="post" enctype="multipart/form-data">

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="dis_surat_keputusan">No. Surat Keputusan</label>
                    <input placeholder="" <?=$disabled?> type="text" id="dis_surat_keputusan" class="easyui-validatebox" required name="reqNoSuratKeputusan" value="<?=$reqNoSuratKeputusan?>" />
                  </div>

                  <div class="input-field col s12 m6">
                    <label for="reqTanggalSuratKeputusan">Tanggal Surat Keputusan</label>
                    <input placeholder="" <?=$disabled?> required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSuratKeputusan" id="reqTanggalSuratKeputusan" value="<?=$reqTanggalSuratKeputusan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSuratKeputusan');"/>
                  </div>
                </div>
                
                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNoNotaBakn">No. Nota BAKN</label>
                    <input <?=$disabled?> placeholder type="text" name="reqNoNotaBakn" id="reqNoNotaBakn" value="<?=$reqNoNotaBakn?>"  />
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggalNotaBakn">Tanggal Nota BAKN</label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalNotaBakn" id="reqTanggalNotaBakn" value="<?=$reqTanggalNotaBakn?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalNotaBakn');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNoPersetujuanNip">No. Persetujuan NIP</label>
                    <input <?=$disabled?> placeholder type="text" name="reqNoPersetujuanNip" id="reqNoPersetujuanNip" value="<?=$reqNoPersetujuanNip?>" />
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggalPersetujuanNIP">Tanggal Persetujuan NIP</label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPersetujuanNip" id="reqTanggalPersetujuanNip" value="<?=$reqTanggalPersetujuanNip?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPersetujuanNip');"/>
                  </div>
                </div>
                
                <div class="row">
                  <div class="input-field col s12 m6">
                    <select name="reqGolRuang" <?=$disabled?> id="reqGolRuang" >
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
                    <label for="reqTerhitungMulaiTanggal">Terhitung Mulai Tanggal</label>
                    <input <?=$disabled?> placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTerhitungMulaiTanggal" id="reqTerhitungMulaiTanggal" value="<?=$reqTerhitungMulaiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTerhitungMulaiTanggal');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col s12 m6">
                    <div class="row">
                      <div class="input-field col s6 m6">
                        <label for="reqTh">Masa Kerja Tahun</label>
                        <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" required name="reqTh" id="reqTh" value="<?=$reqTh?>" />
                      </div>

                      <div class="input-field col s6 m6">
                        <label for="reqBl">Masa Kerja Bulan</label>
                        <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" required name="reqBl" id="reqBl" value="<?=$reqBl?>" />
                      </div>
                    </div>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqGajiPokok">Gaji Pokok (80%)</label>
                    <input <?=$disabled?> type="text" placeholder class="easyui-validatebox" required id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <select <?=$disabled?> name="reqFormasiCpnsId" id="reqFormasiCpnsId" >
                      <option value=""></option>
                      <? 
                      while($formasi_cpns->nextRow())
                      {
                        ?>
                        <option value="<?=$formasi_cpns->getField('FORMASI_CPNS_ID')?>" <? if($reqFormasiCpnsId == $formasi_cpns->getField('FORMASI_CPNS_ID')) echo 'selected';?>><?=$formasi_cpns->getField('NAMA')?></option>
                        <? 
                      }
                      ?>
                    </select>
                    <label for="reqFormasiCpnsId">Jalur Pengadaan (Formasi)</label>
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqPendidikan">Pendidikan</label>
                    <input <?=$disabled?> placeholder type="text" id="reqPendidikan" disabled value="<?=$reqPendidikan?>"  />
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqJurusan">Jurusan</label>
                    <input <?=$disabled?> placeholder type="text" id="reqJurusan" disabled value="<?=$reqJurusan?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <select <?=$disabled?> name="reqJenisFormasiTugasId" id="reqJenisFormasiTugasId">
                      <option value="" <? if($reqJenisFormasiTugasId == "") echo 'selected';?>></option>
                      <option value="1" <? if($reqJenisFormasiTugasId == "1") echo 'selected';?>>Pelaksana</option>
                      <option value="2" <? if($reqJenisFormasiTugasId == "2") echo 'selected';?>>Fungsional Tertentu</option>
                    </select>
                    <label for="reqJenisFormasiTugasId">Jenis Formasi Tugas / Jab</label>
                  </div>
                  <div class="input-field col s12 m6">
                    <input type="hidden" name="reqJabatanFuId" id="reqJabatanFuId" value="<?=$reqJabatanFuId?>" />
                    <input type="hidden" name="reqJabatanFtId" id="reqJabatanFtId" value="<?=$reqJabatanFtId?>" />
                    <input type="hidden" name="reqJabatanTugas" id="reqJabatanTugas" value="<?=$reqJabatanTugas?>" />
                    <label for="reqJabatanCariTugas">Tugas</label>
                    <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" value="<?=$reqJabatanTugas?>" id="reqJabatanCariTugas"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m4">
                    <select <?=$disabled?> name="reqStatusSkCpns" id="reqStatusSkCpns">
                      <option value="" <? if($reqStatusSkCpns == "") echo 'selected';?>></option>
                      <option value="1" <? if($reqStatusSkCpns == "1") echo 'selected';?>>Ya</option>
                      <option value="2" <? if($reqStatusSkCpns == "2") echo 'selected';?>>Tidak</option>
                    </select>
                    <label for="reqStatusSkCpns">SK CPNS Kab Jombang?</label>
                  </div>
                  <div class="input-field col s12 m8">
                    <label for="reqPejabatPenetap">Pejabat Penetap</label>
                    <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                    <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" required id="reqPejabatPenetap" name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m4">
                    <label for="reqSpmtNomor">No. SPMT</label>
                    <input <?=$disabled?> placeholder type="text" name="reqSpmtNomor" id="reqSpmtNomor" value="<?=$reqSpmtNomor?>"  />
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqSpmtTanggal">Tanggal SPMT</label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqSpmtTanggal" id="reqSpmtTanggal" value="<?=$reqSpmtTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSpmtTanggal');"/>
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqSpmtTmt">TMT SPMT</label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqSpmtTmt" id="reqSpmtTmt" value="<?=$reqSpmtTmt?>" maxlength="10" onKeyDown="return format_date(event,'reqSpmtTmt');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                      <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                    </button>

                    <script type="text/javascript">
                      $("#kembali").click(function() { 
                        document.location.href = "app/loadUrl/app/pegawai_add_cpns_pns_monitoring?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>";
                      });
                    </script>

                    <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                    <input type="hidden" name="vRowId" value="<?=$vRowId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />

                    <?
                    // A;R;D
                    if($tempAksesMenu == "A")
                    {
                    ?>
                    <button type="submit" style="display:none" id="reqSubmit"></button>
                    <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
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
            </div>
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

  $('#reqTh,#reqBl').bind('keyup paste', function(){
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