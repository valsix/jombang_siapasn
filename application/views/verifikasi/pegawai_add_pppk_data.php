<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/SkPppk');
$this->load->model('GolonganPppk');
$this->load->model('FormasiCpns');
$this->load->model('KualitasFile');
$this->load->model('PegawaiFile');
$this->load->model('PendidikanRiwayat');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqPeriode= $this->input->get("reqPeriode");
$reqStatusFile= $this->input->get("reqStatusFile");

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0102";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$arrstatusvalidasi= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Valid")
  , array("id"=>"2", "text"=>"Ditolak")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusvalidasi, $arrdata);
}

$arrpangkat= [];
$set= new GolonganPppk();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("GOLONGAN_PPPK_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrpangkat, $arrdata);
}

$arrformasicpns= [];
$set= new FormasiCpns();
$set->selectByParams(array());
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("FORMASI_CPNS_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrformasicpns, $arrdata);
}

$arrstatusskcpns= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Ya")
  , array("id"=>"2", "text"=>"Tidak")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusskcpns, $arrdata);
}

$arrjenisformasitugasid= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Jabatan Pimpinan Tinggi")
  , array("id"=>"2", "text"=>"Fungsional Tertentu")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrjenisformasitugasid, $arrdata);
}

$statement= " AND A.STATUS IS NULL AND A.PPPK_STATUS = 1 AND A.PEGAWAI_ID = ".$reqId;
$arrpendidikan= [];
$set= new PendidikanRiwayat();
$set->selectByParams(array(),-1,-1,$statement);
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("PENDIDIKAN_RIWAYAT_ID");
  $arrdata["text"]= $set->getField("PENDIDIKAN_NAMA");
  array_push($arrpendidikan, $arrdata);
}


$statement= "";
$set= new SkPppk();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

// echo $set->query;exit;
$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqValRowId= $set->getField('SK_PPPK_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqNoNotaBakn= $set->getField('NO_NOTA');$valNoNotaBakn= checkwarna($reqPerubahanData, 'NO_NOTA');
$reqTanggalNotaBakn= dateToPageCheck($set->getField('TANGGAL_NOTA'));$valTanggalNotaBakn= checkwarna($reqPerubahanData, 'TANGGAL_NOTA', "date");
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqNamaPejabatPenetap= $set->getField('NAMA_PENETAP');
$reqNipPejabatPenetap= $set->getField('NIP_PENETAP');
$reqNoSuratKeputusan= $set->getField('NO_SK');$valNoSuratKeputusan= checkwarna($reqPerubahanData, 'NO_SK');
$reqTanggalSuratKeputusan= dateToPageCheck($set->getField('TANGGAL_SK'));$valTanggalSuratKeputusan= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqTerhitungMulaiTanggal= dateToPageCheck($set->getField('TMT_PPPK'));$valTerhitungMulaiTanggal= checkwarna($reqPerubahanData, 'TMT_PPPK', "date");
$reqGolonganPppkId= $set->getField('GOLONGAN_PPPK_ID');$valGolonganPppkId= checkwarna($reqPerubahanData, 'GOLONGAN_PPPK_ID', $arrpangkat, array("id", "text"));
$reqGolonganPppkNama= $set->getField('GOLONGAN_PPPK_KODE');
$reqTanggalTugas= dateToPageCheck($set->getField('TANGGAL_TUGAS'));
$reqTh= $set->getField('MASA_KERJA_TAHUN');$valTh= checkwarna($reqPerubahanData, 'MASA_KERJA_TAHUN');
$reqBl= $set->getField('MASA_KERJA_BULAN');$valBl= checkwarna($reqPerubahanData, 'MASA_KERJA_BULAN');
$reqGajiPokok= $set->getField('GAJI_POKOK');$valGajiPokok= checkwarna($reqPerubahanData, 'GAJI_POKOK', "numberformat");
$reqPendidikanRiwayatId= $set->getField("PENDIDIKAN_RIWAYAT_ID");$valPendidikanRiwayatId= checkwarna($reqPerubahanData, 'PENDIDIKAN_RIWAYAT_ID', $arrpendidikan, array("id", "text"));
$reqPendidikan= $set->getField("PENDIDIKAN_NAMA");
$reqJurusan= $set->getField("PENDIDIKAN_JURUSAN_NAMA");
$reqFormasiPppkId= $set->getField("FORMASI_PPPK_ID");$valFormasiPppkId= checkwarna($reqPerubahanData, 'FORMASI_PPPK_ID', $arrjenisformasitugasid, array("id", "text"));
// echo($reqFormasiPppkId);exit;
$reqJabatanTugas= $set->getField("JABATAN_TUGAS");
$reqJenisFormasiTugasId= $set->getField("JENIS_FORMASI_TUGAS_ID");$valJenisFormasiTugasId= checkwarna($reqPerubahanData, 'JENIS_FORMASI_TUGAS_ID', $arrjenisformasitugasid, array("id", "text"));
$reqJabatanFuId= $set->getField("JABATAN_FU_ID");
$reqJabatanFtId= $set->getField("JABATAN_FT_ID");
$reqSpmtNomor= $set->getField("SPMT_NOMOR");$valSpmtNomor= checkwarna($reqPerubahanData, 'SPMT_NOMOR');
$reqSpmtTanggal= dateToPageCheck($set->getField("SPMT_TANGGAL"));$valSpmtTanggal= checkwarna($reqPerubahanData, 'SPMT_TANGGAL', "date");
$reqSpmtTmt= dateToPageCheck($set->getField("SPMT_TMT"));$valSpmtTmt= checkwarna($reqPerubahanData, 'SPMT_TMT', "date");
$reqNipPPPK= $set->getField("NIP_PPPK");


if(!empty($reqStatusFile))
{
  $arrKategoriDokumen= [];
  $index_data= 0;
  $set_detil= new PegawaiFile();
  $set_detil->selectByParamsKategoriDokumen(array(), -1,-1, " AND A.KATEGORI_FILE_ID = 21");
  // echo $set_detil->query;exit;
  while($set_detil->nextRow())
  {
    $arrKategoriDokumen[$index_data]["KATEGORI_FILE_ID"] = $set_detil->getField("KATEGORI_FILE_ID");
    $arrKategoriDokumen[$index_data]["NAMA"] = $set_detil->getField("NAMA");
    $index_data++;
  }
  unset($set_detil);
  $jumlah_kategori_dokumen= $index_data;

  $kualitas= new KualitasFile();
  $kualitas->selectByParams(array());

  $lokasi_link_file= "uploads/".$reqId."/";
  $ambil_data_file= lihatfiledirektori($lokasi_link_file);
  // print_r($ambil_data_file);exit;

  $arrPegawaiDokumen= [];
  $index_data= 0;
  if($reqKategoriFileId == ""){}
  else
  $statement.= " AND A.KATEGORI_FILE_ID = ".$reqKategoriFileId;
  $set_detil= new PegawaiFile();
  $set_detil->selectByParamsFile(array(), -1,-1, $statement, $reqId);
  // echo $set_detil->query;exit;
  while($set_detil->nextRow())
  {
    $arrPegawaiDokumen[$index_data]["PEGAWAI_FILE_ID"] = $set_detil->getField("PEGAWAI_FILE_ID");
    $arrPegawaiDokumen[$index_data]["ROWID"] = $set_detil->getField("PATH");
    $arrPegawaiDokumen[$index_data]["JENIS_DOKUMEN"] = $set_detil->getField("RIWAYAT_TABLE").";".$set_detil->getField("RIWAYAT_ID").";".$set_detil->getField("RIWAYAT_FIELD");
    $arrPegawaiDokumen[$index_data]["FILE_KUALITAS_ID"] = $set_detil->getField("FILE_KUALITAS_ID");
    $arrPegawaiDokumen[$index_data]["FILE_KUALITAS_NAMA"] = $set_detil->getField("FILE_KUALITAS_NAMA");
    $arrPegawaiDokumen[$index_data]["PEGAWAI_ID"] = $set_detil->getField("PEGAWAI_ID");
    $arrPegawaiDokumen[$index_data]["RIWAYAT_TABLE"] = $set_detil->getField("RIWAYAT_TABLE");
    $arrPegawaiDokumen[$index_data]["RIWAYAT_FIELD"] = $set_detil->getField("RIWAYAT_FIELD");
    $arrPegawaiDokumen[$index_data]["RIWAYAT_ID"] = $set_detil->getField("RIWAYAT_ID");
    $arrPegawaiDokumen[$index_data]["INFO_DATA"] = $set_detil->getField("INFO_DATA");
    $arrPegawaiDokumen[$index_data]["KATEGORI_FILE_ID"] = $set_detil->getField("KATEGORI_FILE_ID");
    $arrPegawaiDokumen[$index_data]["INFO_GROUP_DATA"] = $set_detil->getField("INFO_GROUP_DATA");

    $index_data++;
  }
  unset($set_detil);
  $jumlah_pegawai_dokumen= $index_data;
  // print_r($arrPegawaiDokumen);exit;

  $statement= " AND A.RIWAYAT_TABLE='SK_PPPK' AND A.RIWAYAT_ID=".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $pegawai_file= new PegawaiFile();
  $pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
  $pegawai_file->firstRow();
  // echo $pegawai_file->query;exit();
  $reqNamaFile= $pegawai_file->getField("PATH");
  // echo $reqNamaFile;exit;

  if(empty($reqNamaFile))
  {
    $disabled= "disabled";
  }
  // $disabled= "disabled";
  // $disabled= "readonly";
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
            infopesan= "Tanggal Pertek NI PPPK ("+infotanggalawal+") harus lebih kecil sama dengan dari Tanggal SK PPPK ("+infotanggalakhir+")";
          }
          else if(tanggalawal == "reqSpmtTanggal")
          {
            infopesan= "Tanggal SPMT ("+infotanggalawal+") harus lebih besar sama dengan dari Tanggal SK PPPK ("+infotanggalakhir+")";
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
            infopesan= "Terhitung Mulai Tanggal ("+infotanggalawal+") harus lebih besar sama dengan dari Tanggal SK PPPK ("+infotanggalakhir+") dan maksimal dari Tanggal ("+infoduatahun+")";
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

    function setinfojurusan()
    {
      var reqPendidikanRiwayatId= "";
      reqPendidikanRiwayatId= $("#reqPendidikanRiwayatId").val();

      urlAjax= "sk_pppk_json/combogetjurusan?reqId="+reqPendidikanRiwayatId;
      $.ajax({'url': urlAjax,'success': function(data){
           infovalue= data;
           $("#reqJurusan").val(infovalue);
       }});
    }

    function setGaji()
    {
      var reqTglSk= reqGolonganPppkId= reqMasaKerja= "";
      reqTglSk= $("#reqTanggalSuratKeputusan").val();
      reqGolonganPppkId= $("#reqGolonganPppkId").val();
      reqMasaKerja= $("#reqTh").val();

      urlAjax= "golongan_pppk_gaji_json/gajipokok?reqGolonganPppkId="+reqGolonganPppkId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
      $.ajax({'url': urlAjax,'success': function(data){
       //if(data == ''){}
         //else
         //{
           tempValueGaji= parseFloat(data);
           // tempValueGaji= (tempValueGaji * 80) / 100;
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

      $("#reqTanggalNotaBakn,#reqTanggalSuratKeputusan,#reqSpmtTanggal,#reqSpmtTmt,#reqTerhitungMulaiTanggal").keyup(function(){
        varid= $(this).attr('id');

        if(varid == "reqTanggalNotaBakn")
        {
          setvalidasitanggal("reqTanggalNotaBakn", "reqTanggalSuratKeputusan", "<=")
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
                      reqJabatanCariTugas= $('#reqJabatanCariTugas').val();
                      $("#reqJabatanFuId,#reqJabatanFtId").val("");
                      $("#reqJabatanTugas").val(reqJabatanCariTugas);
                      // urlAjax= "jabatan_fu_json/namajabatan";
                      urlAjax= "xxxx";
                      return false;
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

      $("#reqPendidikanRiwayatId").change(function(){
        setinfojurusan();
      });
	  
      $("#reqGolonganPppkId").change(function(){
        setGaji();
      });

      $("#reqTanggalSuratKeputusan, #reqTh").keyup(function(){
        setGaji();
      });

      $("#reqBl").keyup(function(){
        reqBl= $("#reqBl").val();
        if(parseInt(reqBl) > 11 )
          $("#reqBl").val(11);
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
              document.location.href = "app/loadUrl/app/pegawai_add_pppk_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqStatusFile=file";
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
        }
      });


      $("#cekfile").click(function() {
        document.location.href = "app/loadUrl/app/pegawai_add_pppk_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqStatusFile=file";
      });

      $('#ff').form({
        url:'validasi/sk_pppk_json/add',
        onSubmit:function(){
            
            reqJenisFormasiTugasId= $("#reqJenisFormasiTugasId").val();
            if(reqJenisFormasiTugasId == "")
            {
              mbox.alert("Lengkapi data terlebih dahulu, Jenis Jabatan", {open_speed: 0});
              return false;
            }

            // reqStatusSkPppk= $("#reqStatusSkPppk").val();
            // if(reqStatusSkPppk == "")
            // {
            //   mbox.alert("Lengkapi data terlebih dahulu, SK PPPK Kab Jombang", {open_speed: 0});
            //   return false;
            // }

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

              <?
              if(empty($reqStatusFile))
              {
              ?>
              document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
              <?
              }
              else
              {
              ?>
              document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
              <?
              }
              ?>

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
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">

      <?
      if($reqStatusFile == "file")
      {
      ?>
      <div class="col s12 m6">
      <?
      }
      else
      {
      ?>
      <div class="col s12 m10 offset-m1">
      <?
      }
      ?>

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> SK PPPK</li>
         <li class="collection-item">
           <div class="">

            <div class="row">

              <?
              if(empty($disabled))
              {
              ?>
              <form id="ff" method="post" enctype="multipart/form-data">
              <?
              }
              ?>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="dis_surat_keputusan" class="<?=$valNoSuratKeputusan['warna']?>">
                    No. SK PPPK
                    <?
                    if(!empty($valNoSuratKeputusan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSuratKeputusan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                    </label>
                    <input <?=$disabled?> placeholder type="text" id="dis_surat_keputusan" class="easyui-validatebox" required name="reqNoSuratKeputusan" value="<?=$reqNoSuratKeputusan?>" />
                  </div>
                  
                  <div class="input-field col s12 m6">
                    <label for="reqTanggalSuratKeputusan" class="<?=$valTanggalSuratKeputusan['warna']?>" >
                      Tanggal SK PPPK
                      <?
                      if(!empty($valTanggalSuratKeputusan['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalSuratKeputusan['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSuratKeputusan" id="reqTanggalSuratKeputusan" value="<?=$reqTanggalSuratKeputusan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSuratKeputusan');"/>
                  </div>
                </div>
                
                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNipPPPK" class="<?=$valNipPPPK['warna']?>">
                      NIP PPPK
                      <?
                      if(!empty($valNipPPPK['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNipPPPK['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder type="text" name="reqNipPPPK" id="reqNipPPPK" value="<?=$reqNipPPPK?>" class="easyui-validatebox" required />
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqNoNotaBakn">No. Pertek NI PPPK</label>
                    <label for="reqNoNotaBakn" class="<?=$valNoNotaBakn['warna']?>">
                      No. Pertek NI PPPK
                      <?
                      if(!empty($valNoNotaBakn['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoNotaBakn['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder type="text" name="reqNoNotaBakn" id="reqNoNotaBakn" value="<?=$reqNoNotaBakn?>"  />
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqTanggalNotaBakn" class="<?=$valTanggalNotaBakn['warna']?>">
                      Tanggal Pertek NI PPPK
                      <?
                      if(!empty($valTanggalNotaBakn['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalNotaBakn['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalNotaBakn" id="reqTanggalNotaBakn" value="<?=$reqTanggalNotaBakn?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalNotaBakn');"/>
                  </div>
                </div>
                
                <div class="row">
                  <div class="input-field col s12 m6">
                    <select name="reqGolonganPppkId" <?=$disabled?> id="reqGolonganPppkId" >
                      <?
                      foreach($arrpangkat as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqGolonganPppkId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                      }
                      ?>
                    </select>
                    <label class="<?=$valGolRuang['warna']?>">
                      Gol/Ruang
                      <?
                      if(!empty($valGolRuang['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGolRuang['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTerhitungMulaiTanggal" class="<?=$valTerhitungMulaiTanggal['warna']?>">
                      Terhitung Mulai Tanggal
                      <?
                      if(!empty($valTerhitungMulaiTanggal['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTerhitungMulaiTanggal['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTerhitungMulaiTanggal" id="reqTerhitungMulaiTanggal" value="<?=$reqTerhitungMulaiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTerhitungMulaiTanggal');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col s12 m6">
                    <div class="row">
                      <div class="input-field col s6 m6">
                        <label for="reqTh" class="<?=$valTh['warna']?>">
                          Masa Kerja Tahun
                          <?
                          if(!empty($valTh['data']))
                          {
                          ?>
                          <a class="tooltipe" href="javascript:void(0)">
                            <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTh['data']?></span>
                          </a>
                          <?
                          }
                          ?>
                        </label>
                        <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" required name="reqTh" id="reqTh" value="<?=$reqTh?>" />
                      </div>

                      <div class="input-field col s6 m6">
                        <label for="reqBl" class="<?=$valBl['warna']?>">
                          Masa Kerja Bulan
                          <?
                          if(!empty($valBl['data']))
                          {
                          ?>
                          <a class="tooltipe" href="javascript:void(0)">
                            <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBl['data']?></span>
                          </a>
                          <?
                          }
                          ?>
                        </label>
                        <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" required name="reqBl" id="reqBl" value="<?=$reqBl?>" />
                      </div>
                    </div>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqGajiPokok" class="<?=$valGajiPokok['warna']?>">
                      Gaji Pokok
                      <?
                      if(!empty($valGajiPokok['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGajiPokok['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> type="text" placeholder class="easyui-validatebox" required id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
                  </div>
                </div>


                <div class="row">
                  <div class="input-field col s12 m6">
                    <select <?=$disabled?> name="reqPendidikanRiwayatId" id="reqPendidikanRiwayatId" >
                      <option value=""></option>
                      <?
                      foreach($arrpendidikan as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqPendidikanRiwayatId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqPendidikanRiwayatId" class="<?=$valPendidikanRiwayatId['warna']?>">
                      Pendidikan
                      <?
                      if(!empty($valPendidikanRiwayatId['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPendidikanRiwayatId['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqJurusan">Jurusan</label>
                    <input <?=$disabled?> placeholder type="text" id="reqJurusan" disabled value="<?=$reqJurusan?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <select <?=$disabled?> name="reqFormasiPppkId" id="reqFormasiPppkId" >
                      <option value=""></option>
                      <?
                      foreach($arrformasicpns as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqFormasiPppkId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                      }
                      ?>
                    </select>
                    <label for="reqFormasiPppkId" class="<?=$valFormasiPppkId['warna']?>">
                      Jalur Pengadaan (Formasi)
                      <?
                      if(!empty($valFormasiPppkId['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valFormasiPppkId['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <select <?=$disabled?> name="reqJenisFormasiTugasId" id="reqJenisFormasiTugasId">
                      <option value="" <? if($reqJenisFormasiTugasId == "") echo 'selected';?>></option>
                      <?
                      foreach($arrjenisformasitugasid as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqJenisFormasiTugasId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                      }
                      ?>
                    </select>
                    <label for="reqJenisFormasiTugasId">Jenis Jabatan</label>
                  </div>
                  <div class="input-field col s12 m6">
                    <input type="hidden" name="reqJabatanFuId" id="reqJabatanFuId" value="<?=$reqJabatanFuId?>" />
                    <input type="hidden" name="reqJabatanFtId" id="reqJabatanFtId" value="<?=$reqJabatanFtId?>" />
                    <input type="hidden" name="reqJabatanTugas" id="reqJabatanTugas" value="<?=$reqJabatanTugas?>" />
                    <label for="reqJabatanCariTugas" class="<?=$valJabatanCariTugas['warna']?>">
                      Tugas
                      <?
                      if(!empty($valJabatanCariTugas['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJabatanCariTugas['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" value="<?=$reqJabatanTugas?>" id="reqJabatanCariTugas"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m12">
                    <label for="reqPejabatPenetap" class="<?=$valPejabatPenetap['warna']?>">
                      Pejabat Penetapan
                      <?
                      if(!empty($valPejabatPenetap['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPejabatPenetap['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                    <input <?=$disabled?> placeholder type="text" class="easyui-validatebox" required id="reqPejabatPenetap" name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m4">
                    <label for="reqSpmtNomor" class="<?=$valSpmtNomor['warna']?>">
                      No. SPMT
                      <?
                      if(!empty($valSpmtNomor['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSpmtNomor['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder type="text" name="reqSpmtNomor" id="reqSpmtNomor" value="<?=$reqSpmtNomor?>"  />
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqSpmtTanggal" class="<?=$valSpmtTanggal['warna']?>">
                      Tanggal SPMT
                      <?
                      if(!empty($valSpmtTanggal['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSpmtTanggal['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqSpmtTanggal" id="reqSpmtTanggal" value="<?=$reqSpmtTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSpmtTanggal');"/>
                  </div>
                  <div class="input-field col s12 m4">
                    <label for="reqSpmtTmt" class="<?=$valSpmtTmt['warna']?>">
                      TMT SPMT
                      <?
                      if(!empty($valSpmtTmt['data']))
                      {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSpmtTmt['data']?></span>
                      </a>
                      <?
                      }
                      ?>
                    </label>
                    <input <?=$disabled?> placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqSpmtTmt" id="reqSpmtTmt" value="<?=$reqSpmtTmt?>" maxlength="10" onKeyDown="return format_date(event,'reqSpmtTmt');"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m12">
                    <select <?=$disabled?> name="reqStatusValidasi" id="reqStatusValidasi">
                      <option value="" <? if($reqStatusSkCpns == "") echo 'selected';?>></option>
                      <?
                      foreach($arrstatusvalidasi as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqStatusValidasi == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqStatusValidasi">Status Klarifikasi</label>
                  </div>
                </div>

                <?
                if(empty($disabled))
                {
                ?>
                <div class="row">
                  <div class="input-field col s12">
                    <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                      <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                    </button>

                    <script type="text/javascript">
                      $("#kembali").click(function() { 
                        document.location.href = "app/loadUrl/verifikasi/validasi_verifikator";
                      });
                    </script>

                    <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
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
                    if(!empty($reqRowId) && empty($reqStatusFile))
                    {
                    ?>
                    <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="cekfile">Cek eFile
                      <i class="mdi-navigation-arrow-forward right hide-on-small-only"></i>
                    </button>
                    <?
                    }
                    ?>

                    <?
                    }
                    ?>
                  </div>
                </div>
                <?
                }
                ?>

              <?
              if(empty($disabled))
              {
              ?>
              </form>
              <?
              }
              ?>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <?
    if(!empty($reqStatusFile))
    {
    ?>
    <div class="col s12 m6">
      <div class="row">

        <?
        if(!empty($reqNamaFile))
        {
        ?>
          <div class="row">
              <div class="input-field col s12">
                <!-- <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                  <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>

                <script type="text/javascript">
                  $("#kembali").click(function() { 
                    document.location.href = "app/loadUrl/app/pegawai_add_sk_cpns?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
                  });
                </script> -->
              </div>
          </div>

          <div id="pdfWrapper">
            <iframe src="<?=$reqNamaFile?>" frameborder="0" style='height: 540; width: 100%;'></iframe> 
          </div>
        <?
        }
        else
        {
        ?>
          <div class="col s12">
            <ul class="collection card">
              <li class="collection-item ubah-color-warna">Set File</li>
              <li class="collection-item">

                <form id="fileff" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="input-field col s12">
                      <label for="reqNamaFile" class="active">Nama File Dokumen</label>
                      <select name="reqNamaFile" id="reqNamaFile">
                        <option value=""></option>
                        <?
                        for($index_file=0; $index_file < count($ambil_data_file); $index_file++)
                        {
                          $reqRowId= $tempKategoriFileId= $tempRiwayatTable= $tempFileKualitasId= $tempFileKualitasNama= "";
                          $tempUrlFile= $ambil_data_file[$index_file];
                          $tempNamaUrlFile= pathinfo($tempUrlFile, PATHINFO_BASENAME);
                          $tempRiwayatTable= $tempInfoGroupData= $tempFileKualitasNama= $tempFileKualitasId= "";

                          $arrayKey= [];
                          $arrayKey= in_array_column($tempUrlFile, "ROWID", $arrPegawaiDokumen);
                          if(!empty($arrayKey))
                          {
                            $index_row= $arrayKey[0];
                            $reqRowId= $arrPegawaiDokumen[$index_row]["PEGAWAI_FILE_ID"];
                            $tempInfoGroupData= $arrPegawaiDokumen[$index_row]["INFO_GROUP_DATA"];
                            $tempKategoriFileId= $arrPegawaiDokumen[$index_row]["KATEGORI_FILE_ID"];
                            $tempRiwayatTable= $arrPegawaiDokumen[$index_row]["JENIS_DOKUMEN"];
                            $tempFileKualitasId= $arrPegawaiDokumen[$index_row]["FILE_KUALITAS_ID"];
                            $tempFileKualitasNama= $arrPegawaiDokumen[$index_row]["FILE_KUALITAS_NAMA"];
                          }

                          if(!empty($reqRowId))
                          {
                            continue;
                          }

                          $tempFileDelete= likeMatch("%_delete_%", $tempNamaUrlFile);
                          if($tempFileDelete == 1)
                          {
                            continue;
                          }

                          $infourlfile= $reqRowId."valsixbatas".$tempUrlFile;
                        ?>
                          <option value="<?=$infourlfile?>"><?=$tempNamaUrlFile?></option>
                        <?
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="row infokategori">
                    <div class="input-field col s6">
                      <label for="reqKategoriFileId" class="active">Kategori Dokumen</label>
                      <select name="reqKategoriFileId" id="reqKategoriFileId">
                        <option></option>
                        <?
                        for($index_loop=0; $index_loop < $jumlah_kategori_dokumen; $index_loop++)
                        {
                          $tempValId= $arrKategoriDokumen[$index_loop]["KATEGORI_FILE_ID"];
                          $tempNama= $arrKategoriDokumen[$index_loop]["NAMA"];
                        ?>
                          <option value="<?=$tempValId?>" <? if($reqKategoriFileId == $tempValId) echo "selected"?>><?=$tempNama?></option>
                        <?
                        }
                        ?>
                      </select>
                    </div>
                    <div class="input-field col s6">
                      <select name="reqJenisDokumen" id="reqJenisDokumen">
                        <option></option>
                        <?
                        for($index_loop=0; $index_loop < $jumlah_jenis_dokumen; $index_loop++)
                        {
                          $arrJenisDokumen[$index_loop]["NO_URUT"];
                          $arrJenisDokumen[$index_loop]["PEGAWAI_ID"];
                          $tempRiwayatTable= $arrJenisDokumen[$index_loop]["RIWAYAT_TABLE"];
                          $tempRiwayatTableNext= $arrJenisDokumen[$index_loop+1]["RIWAYAT_TABLE"];
                          $tempRiwayatField= $arrJenisDokumen[$index_loop]["RIWAYAT_FIELD"];
                          $tempRiwayatId= $arrJenisDokumen[$index_loop]["RIWAYAT_ID"];
                          $tempInfoData= $arrJenisDokumen[$index_loop]["INFO_DATA"];
                          $tempInfoGroupData= $arrJenisDokumen[$index_loop]["INFO_GROUP_DATA"];
                          $tempValue= $tempRiwayatTable.";".$tempRiwayatId.";".$tempRiwayatField;
                        ?>
                          <option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?>><?=$tempInfoData?></option>
                        <?
                        }
                        ?>
                      </select>
                      <label for="reqJenisDokumen">Jenis Dokumen</label>
                    </div>
                  </div>

                  <div class="row infokategori">
                    <div class="input-field col s12">
                      <select name="reqKualitasFileId" id="reqKualitasFileId">
                        <option></option>
                        <?
                        while($kualitas->nextRow())
                        {
                        ?>
                          <option value="<?=$kualitas->getField('KUALITAS_FILE_ID')?>" <? if($reqKualitasFileId == $kualitas->getField('KUALITAS_FILE_ID')) echo "selected"?>><?=$kualitas->getField('NAMA')?></option>
                        <? 
                        }
                        ?>
                      </select>
                      <label for="reqKualitasFileId">Kualitas Dokumen</label>
                    </div>
                  </div>

                  <div class="row">
                      <div class="input-field col s12">
                        <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                          <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                        </button>

                        <script type="text/javascript">
                          $("#kembali").click(function() { 
                            document.location.href = "app/loadUrl/app/pegawai_add_pppk_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
                          });
                        </script>
                        <input type="hidden" id="reqCheckImage" />

                        <input type="hidden" name="reqRiwayatTable" id="reqRiwayatTable" value="<?=$reqRiwayatTable?>" />
                        <input type="hidden" name="reqRiwayatField" id="reqRiwayatField" value="<?=$reqRiwayatField?>" />
                        <input type="hidden" name="reqRiwayatId" id="reqRiwayatId" value="<?=$reqRiwayatId?>" />
                        <input type="hidden" name="reqUrlFile" id="reqUrlFile" />
                        <input type="hidden" name="reqRowId" id="reqRowId" />
                        <input type="hidden" name="reqId" value="<?=$reqId?>" />
                        <input type="hidden" name="reqMode" id="reqMode" value="" />

                        <button type="submit" style="display:none" id="reqsetfile"></button>
                        <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpansetfile">Simpan
                          <i class="mdi-content-save left hide-on-small-only"></i>
                        </button>
                      </div>
                  </div>

                  <div class="row">
                    <div id="pdfWrapper">
                      <iframe id="setfilepath" frameborder="0" style='height: 350; width: 100%;'></iframe> 
                    </div>
                  </div>
                </form>

              </li>
            </ul>
          </div>
        <?
        }
        ?>

      </div>
    </div>
    <?
    }
    ?>


  </div>
</div>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  ul.dropdown-content.select-dropdown {
    /*border: 2px solid red;*/
    height: 250px !important;
    overflow: auto !important;
  }
</style>

<script type="text/javascript">

<?
// http://localhost/simpeg/jombang-allnew/app/loadUrl/app/pegawai_add_e_file_data?reqId=8300&reqRowId=11055&reqUrlFile=uploads/8300/SK_PPPK_198305022011011001_lama.pdf
// http://localhost/simpeg/jombang-allnew/app/loadUrl/app/pegawai_add_sk_cpns/?reqId=8300&reqPeriode=
if(!empty($reqStatusFile))
{
?>
  function getFileExtension(filename)
  {
    var ext = /^.+\.([^.]+)$/.exec(filename);
    ext= ext == null ? "" : ext[1];

    ext= ext.toUpperCase();

    if(ext == "JPG" || ext == "JPEG" || ext == "PNG")
    {
      return "1";
    }
    else if(ext == "PDF")
    {
      return "2";
    }
    else
    return "";
  }

  function setinfokategori()
  {
    reqNamaFile= $("#reqNamaFile").val();
    // console.log(reqNamaFile);
    $(".infokategori").hide();
    if(reqNamaFile !== "")
    {
      arrfilenama= reqNamaFile.split('valsixbatas');
      reqRowId= arrfilenama[0];
      reqNamaFile= arrfilenama[1];

      reqMode= "update";
      if(reqRowId == "")
        reqMode= "insert";


      $(".infokategori").show();
      $("#setfilepath").attr('src', reqNamaFile);
      reqCheckImage= getFileExtension(reqNamaFile);
      $("#reqCheckImage").val(reqCheckImage);

      $("#reqUrlFile").val(reqNamaFile);
      $("#reqRowId").val(reqRowId);
      $("#reqMode").val(reqMode);

      $("#reqKategoriFileId").val("");
      $("#reqKategoriFileId").material_select();
      $("#reqJenisDokumen option").remove();
      $("#reqJenisDokumen").material_select();
    }
  }

  function setJenisDokumen()
  {
    var reqJenisDokumen= reqRiwayatTable= reqRiwayatField= reqRiwayatId= "";
    reqJenisDokumen= $("#reqJenisDokumen").val();
    //alert(reqJenisDokumen);return false;
    reqJenisDokumen= String(reqJenisDokumen);
    reqJenisDokumen= reqJenisDokumen.split(';'); 
    //$tempRiwayatTable.";".$tempRiwayatId.";".$tempRiwayatField.";".$tempRiwayatId;
    reqRiwayatTable= reqJenisDokumen[0];
    if(typeof reqRiwayatTable == "undefined") reqRiwayatTable= "";
    $("#reqRiwayatTable").val(reqRiwayatTable);
    reqRiwayatId= reqJenisDokumen[1];
    if(typeof reqRiwayatId == "undefined") reqRiwayatId= "";
    $("#reqRiwayatId").val(reqRiwayatId);
    reqRiwayatField= reqJenisDokumen[2];
    if(typeof reqRiwayatField == "undefined") reqRiwayatField= "";
    $("#reqRiwayatField").val(reqRiwayatField);
  }

  $(function(){

    <?
    if(empty($reqNamaFile))
    {
    ?>
    setinfokategori();
    <?
    }
    ?>

    $("#reqNamaFile").change(function(){
      setinfokategori();
    });

    $("#reqJenisDokumen").change(function(){
      setJenisDokumen();
    });

    $("#reqKategoriFileId").change(function(){
      var reqKategoriFileId= "";
      reqKategoriFileId= $("#reqKategoriFileId").val();
      $("#reqJenisDokumen option").remove();
      $("#reqJenisDokumen").material_select();
      reqCheckImage= $("#reqCheckImage").val();


      $("<option value=''></option>").appendTo("#reqJenisDokumen");
      $.ajax({'url': "pegawai_file_json/jenis_dokumen/?reqId=<?=$reqId?>&reqKategoriFileId="+reqKategoriFileId+"&reqCheckImage="+reqCheckImage,'success': function(dataJson) {
        var data= JSON.parse(dataJson);
        for(i=0;i<data.arrID.length; i++)
        {
          valId= data.arrID[i]; valNama= data.arrNama[i];
          $("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#reqJenisDokumen");
        }
        $("#reqJenisDokumen").material_select();
      }});
      
    });

  });
<?
}
?>

  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqTh,#reqBl,#reqNipPPPK').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });
</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>