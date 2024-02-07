<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Cuti');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$checkquery= $this->input->get("c");

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0108";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

if($reqRowId == ""){
  $reqMode = "insert";
}
else
{
  $reqMode = 'update';
  $statement= " AND A.CUTI_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new Cuti();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqRowId= $set->getField('CUTI_ID');
  $reqNoSurat= $set->getField('NO_SURAT');
  $reqJenisCuti= $set->getField('JENIS_CUTI');
  $reqTanggalPermohonan= dateToPageCheck($set->getField('TANGGAL_PERMOHONAN'));
  $reqTanggalSurat= dateToPageCheck($set->getField('TANGGAL_SURAT'));
  $reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
  $reqTanggalSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));
  $reqLama= $set->getField('LAMA');
  $reqCutiKeterangan= $set->getField('CUTI_KETERANGAN');
  $reqKeterangan= $set->getField('KETERANGAN');
}

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "CUTI";
$reqDokumenKategoriFileId= "27"; // ambil dari table KATEGORI_FILE, cek sesuai mode
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


<script type="text/javascript">
  function intervalHari(date1, date2) {
    if (date1 > date2) { // swap
        var result = intervalHari(date2, date1);
        result.years  = -result.years;
        result.months = -result.months;
        result.days   = -result.days;
        result.hours  = -result.hours;
        return result;
    }
    result = {
        years:  date2.getYear()  - date1.getYear(),
        months: date2.getMonth() - date1.getMonth(),
        days:   date2.getDate()  - date1.getDate(),
        hours:  date2.getHours() - date1.getHours()

    };
    if (result.hours < 0) {
        result.days--;
        result.hours += 24;
    }
    if (result.days < 0) {
        result.months--;
        
        var copy1 = new Date(date1.getTime());
        copy1.setDate(31);
        result.days = 32-date1.getDate()-copy1.getDate()+date2.getDate();
    }
    if (result.months < 0) {
        result.years--;
        result.months+=12;
    }
    return result;
}

  
   
  function keterangaLama(lama){

     var date1 = new Date();
     var date2 = new  Date();
     date2.setDate(date2.getDate() + parseInt(lama));
     let dataObject =intervalHari(date1,date2);

     var textHtml = '';
     if(dataObject['years']>0){
      textHtml +=dataObject['years'] +' Tahun';
     } 
     if(dataObject['months']>0){
      textHtml +=" "+dataObject['months'] +' Bulan';
     }
     if(dataObject['days']>0 ){
      textHtml +=" "+dataObject['days'] +' Hari';
     }
  
  
     $("#reqLamaHariKeterangan").val(textHtml).focus();
      $("#reqLamaHariKeterangan2").val(textHtml);
     $("#reqLamaHari").focus();
  }
 
</script>


<script type="text/javascript"> 
  $(function(){
   
    $('#ff').form({
      url:'cuti_json/add',
      onSubmit:function(){
        if($(this).form('validate')){}
        else
        {
          $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
          return false;
        }
      },
      success:function(data){
        <?
        if(!empty($checkquery))
        {
        ?>
        console.log(data);return false;
        <?
        }
        ?>

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
            document.location.href= "app/loadUrl/app/pegawai_add_cuti_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
          }, 1000));
          $(".mbox > .right-align").css({"display": "none"});
        }
      }
    });
    $('#reqLamaHari').bind('keyup paste', function(){
      this.value = this.value.replace(/[^0-9]/g, '');
      keterangaLama(this.value);
    });
 

  });

</script>

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
          <li class="collection-item ubah-color-warna">EDIT CUTI</li>
          <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqJenisCuti" id="reqJenisCuti">
                    <option value="1" <? if($reqJenisCuti == 1) echo 'selected';?>>Cuti Tahunan</option>       
                    <option value="2" <? if($reqJenisCuti == 2) echo 'selected';?>>Cuti Besar</option>       
                    <option value="3" <? if($reqJenisCuti == 3) echo 'selected';?>>Cuti Sakit</option>       
                    <option value="4" <? if($reqJenisCuti == 4) echo 'selected';?>>Cuti Bersalin</option>        
                    <option value="5" <? if($reqJenisCuti == 5) echo 'selected';?>>Cuti Alasan Penting</option>       
                    <option value="6" <? if($reqJenisCuti == 6) echo 'selected';?>>Cuti Bersama</option>
                    <option value="7" <? if($reqJenisCuti == 7) echo 'selected';?>>CLTN</option>
                  </select>
                  <label for="reqJenisCuti">Jenis Cuti</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSurat">No Surat</label>
                  <input type="text" class="easyui-validatebox" required <?=$read?> name="reqNoSurat" id="reqNoSurat" value="<?=$reqNoSurat?>" title="No surat harus diisi" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSurat">Tanggal Surat</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSurat" id="reqTanggalSurat"  value="<?=$reqTanggalSurat?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSurat');"/>
                </div>
              </div>

               <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqLamaHari">Lama Hari</label>
                  <input type="text" class="easyui-validatebox" name="reqLamaHari" id="reqLamaHari" value="<?=$reqLama?>"  />
                </div>
                 <div class="input-field col s12 m6">
                   <label for="reqLamaHari">Lama keterangan</label>
                  <input type="text" class="easyui-validatebox"  id="reqLamaHariKeterangan" value="<?=$reqCutiKeterangan?>" disabled  />
                  <input type="hidden" class="easyui-validatebox"  name="reqLamaHariKeterangan" id="reqLamaHariKeterangan2" value="<?=$reqCutiKeterangan?>"   />
                </div>
              </div>

               <div class="row">
                <div class="input-field col s12 m6" style="display: none;">
                  <label for="reqCutiKeterangan">Lama</label>
                  <input type="hidden" <?=$read?> id="reqLama" name="reqLama" value="<?=$reqLama?>" />
                  <input type="text" class="easyui-validatebox" required <?=$read?> name="reqCutiKeterangan" id="reqCutiKeterangan" value="<?=$reqCutiKeterangan?>" />
                </div>
                 <div class="input-field col s12 m6">
                  <label for="reqTanggalPermohonan">Tanggal Permohonan</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPermohonan" id="reqTanggalPermohonan"  value="<?=$reqTanggalPermohonan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPermohonan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTanggalMulai">Tanggal Mulai</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMulai" id="reqTanggalMulai"  value="<?=$reqTanggalMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMulai');"/>
                </div>
                 <div class="input-field col s12 m6">
                  <label for="reqTanggalSelesai">Tanggal Selesai</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSelesai" id="reqTanggalSelesai"  value="<?=$reqTanggalSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSelesai');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqKeterangan">Keterangan</label>
                  <textarea class="easyui-validatebox materialize-textarea" <?=$disabled?> name="reqKeterangan" id="reqKeterangan"><?=$reqKeterangan?></textarea>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_cuti_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <input type="hidden" name="c" value="<?=$checkquery?>" />

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

            <br/><br/><br/>
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