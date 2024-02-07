<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pendidikan');
$this->load->model('PendidikanRiwayat');

$pendidikan = new Pendidikan();

$pendidikan->selectByParams();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0105";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$tempKondisiCpns= "";
$statement= " AND A.STATUS IS NULL AND A.STATUS_PENDIDIKAN = '1' AND A.PEGAWAI_ID = ".$reqId;
$set= new PendidikanRiwayat();
$set->selectByParams(array(), -1, -1, $statement);
if($set->firstRow())
  $tempKondisiCpns= 1;
unset($set);
//echo $tempKondisiCpns;exit;

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new PendidikanRiwayat();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqRowId= $set->getField('PENDIDIKAN_RIWAYAT_ID');

  $reqNamaSekolah        = $set->getField('NAMA');
  $reqNamaFakultas= $set->getField('NAMA_FAKULTAS');
  $reqPendidikanId       = $set->getField('PENDIDIKAN_ID');
  $reqTglSttb        = dateToPageCheck($set->getField('TANGGAL_STTB'));
  $reqJurusan        = $set->getField('JURUSAN');
  $reqJurusanId        = $set->getField('PENDIDIKAN_JURUSAN_ID');
  $reqAlamatSekolah      = $set->getField('TEMPAT');
  $reqKepalaSekolah      = $set->getField('KEPALA');
  $reqNoSttb         = $set->getField('NO_STTB');
  // $reqPegawaiId      = $set->getField('PEGAWAI_ID');
 
  $reqStatus= $set->getField('STATUS');
  $reqStatusTugasIjinBelajar= $set->getField('STATUS_TUGAS_IJIN_BELAJAR');
  $reqStatusPendidikan= $set->getField('STATUS_PENDIDIKAN');
  $reqStatusPendidikanNama= $set->getField('STATUS_PENDIDIKAN_NAMA');
  $reqNoSuratIjin      = $set->getField('NO_SURAT_IJIN');
  $reqTglSuratIjin       = dateToPageCheck($set->getField('TANGGAL_SURAT_IJIN'));
  $reqGelarTipe       = $set->getField('GELAR_TIPE');
  $reqGelarNamaDepan= $set->getField('GELAR_DEPAN');
  $reqGelarNama       = $set->getField('GELAR_NAMA');
}

$pendidikan->selectByParams(array(), -1,-1);
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
      $('#ff').form({
        url:'pendidikan_riwayat_json/add',
        onSubmit:function(){
		  var reqJurusanId= reqPendidikanId= "";
      reqJurusanId= $("#reqJurusanId").val();
		  reqPendidikanId= $("#reqPendidikanId").val();

		  if(reqJurusanId == "" && parseInt(reqPendidikanId) > 6)
		  {
			$.messager.alert('Info', "Jurusan tidak ada dalam sistem, hubungi admin untuk menambahakan data jurusan", 'info');
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
          // alert(data);return false;
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
              document.location.href= "app/loadUrl/app/pegawai_add_pendidikan_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
          
        }


      });

      $('input[id^="reqJurusan"]').autocomplete({
        source:function(request, response){
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
          if (id.indexOf('reqJurusan') !== -1)
          {
            var element= id.split('reqJurusan');
            var indexId= "reqJurusanId"+element[1];
          }

          var statusht= "";
            //statusht= ui.item.statusht;
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

      $('#reqGelarTipe').bind('change', function(ev) {
       setGelarTipe();
     });

      $("#reqPendidikanId").change(function(){
       $("#reqJurusan, #reqJurusanId").val("");
     });

      setGelarTipe();

    });

    //
    function setGelarTipe()
    {
      var reqGelarTipe= "";
      reqGelarTipe= $("#reqGelarTipe").val();
      $("#reqInfoNamaGelarDepan,#reqInfoNamaGelarBelakang").hide();

      $('#reqGelarNamaDepan,#reqGelarNama').validatebox({required: false});
      $('#reqGelarNamaDepan,#reqGelarNama').removeClass('validatebox-invalid');

      if(reqGelarTipe == "")
      {
        $("#reqGelarNamaDepan,#reqGelarNama").val("");
      }
      else if(reqGelarTipe == "1")
      {
        $("#reqInfoNamaGelarDepan").show();
        $("#reqGelarNama").val("");
        $('#reqGelarNamaDepan').validatebox({required: true});
      }
      else if(reqGelarTipe == "2")
      {
        $("#reqInfoNamaGelarBelakang").show();
        $("#reqGelarNamaDepan").val("");
        $('#reqGelarNama').validatebox({required: true});
      }
      else
      {
        $("#reqInfoNamaGelarDepan,#reqInfoNamaGelarBelakang").show();
        $('#reqGelarNamaDepan,#reqGelarNama').validatebox({required: true});
      }
    }
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
         <li class="collection-item ubah-color-warna">EDIT PENDIDIKAN</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m3">
                  <select name="reqPendidikanId" id="reqPendidikanId" <?=$disabled?>>
                    <? 
                    while($pendidikan->nextRow()){
                      ?>
                      <option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($reqPendidikanId == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                      <? 
                    }?>
                  </select>
                  <label for="reqPendidikanId">Pendidikan</label>
                </div>
                <div class="input-field col s12 m3">
                  <?
                  if($tempKondisiCpns == "1" && $reqStatusPendidikan == "1")
                  {
                    ?>
                    <input type="hidden" name="reqStatusPendidikan" id="reqStatusPendidikan" value="<?=$reqStatusPendidikan?>" /> 
                    <input type="text" value="<?=$reqStatusPendidikanNama?>" disabled />
                    <?
                  }
                  elseif($tempKondisiCpns == "1")
                  {
                    ?>
                    <select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?>>
                      <option value="2" <? if($reqStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
                      <option value="3" <? if($reqStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
                      <option value="4" <? if($reqStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
                      <!-- <option value="5" <? if($reqStatusPendidikan == 5) echo 'selected'?>>Ijin belajar</option>
                      <option value="6" <? if($reqStatusPendidikan == 6) echo 'selected'?>>Tugas Belajar</option> -->
                    </select>
                    <?
                  }
                  else
                  {
                    ?>
                    <select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?>>
                      <option value="1" <? if($reqStatusPendidikan == 1) echo 'selected'?>>Pendidikan CPNS</option>
                      <option value="2" <? if($reqStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
                      <option value="3" <? if($reqStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
                      <option value="4" <? if($reqStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
                      <!-- <option value="5" <? if($reqStatusPendidikan == 5) echo 'selected'?>>Ijin belajar</option>
                      <option value="6" <? if($reqStatusPendidikan == 6) echo 'selected'?>>Tugas Belajar</option> -->
                    </select>
                    <?
                  }
                  ?>
                  <label for="reqStatusPendidikan">Status Pendidikan</label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqJurusan">Jurusan</label>
                  <input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$reqJurusanId?>" /> 
                  <input type="text" name="reqJurusan" id="reqJurusan" value="<?=$reqJurusan?>" title="Jurusan harus diisi" />
                </div>
              </div>    

              <div class="row">
                <?
                if($reqStatus == "3")
                {
                ?>
                  <div class="input-field col s12 m3">
                    <select name="reqStatusTugasIjinBelajar" id="reqStatusTugasIjinBelajar">
                      <option value="1" <? if($reqStatusTugasIjinBelajar == "1") echo "selected"?>>Ijin Belajar</option>
                      <option value="2" <? if($reqStatusTugasIjinBelajar == "2") echo "selected"?>>Tugas Belajar</option>
                    </select>
                    <label for="reqStatusTugasIjinBelajar">Status Ijin belajar / Tugas Belajar</label>
                  </div>
                <?
                }
                ?>
                <div class="input-field col s12 m3">
                  <select name="reqGelarTipe" id="reqGelarTipe" <?=$disabled?>>
                    <option value="" <? if($reqGelarTipe == "") echo 'selected';?>>Tanpa gelar</option>
                    <option value="1" <? if($reqGelarTipe == "1") echo 'selected';?>>Depan</option>
                    <option value="2" <? if($reqGelarTipe == "2") echo 'selected';?>>Belakang</option>
                    <option value="3" <? if($reqGelarTipe == "3") echo 'selected';?>>Depan Belakang</option>
                  </select>
                  <label for="reqGelarTipe">Tipe Gelar</label>
                </div>
                <div class="input-field col s12 m3" id="reqInfoNamaGelarDepan">
                  <label for="reqGelarNamaDepan">Gelar Depan</label>
                  <input type="text" class="easyui-validatebox" name="reqGelarNamaDepan" id="reqGelarNamaDepan" <?=$read?> value="<?=$reqGelarNamaDepan?>"/>
                </div>
                <div class="input-field col s12 m3" id="reqInfoNamaGelarBelakang">
                  <label for="reqGelarNama">Gelar Belakang</label>
                  <input type="text" class="easyui-validatebox" name="reqGelarNama" id="reqGelarNama" <?=$read?> value="<?=$reqGelarNama?>"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaSekolah">Nama Sekolah</label>
                  <input type="text" class="easyui-validatebox" required id="reqNamaSekolah"  name="reqNamaSekolah" <?=$read?> value="<?=$reqNamaSekolah?>" title="Nama sekolah harus diisi" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqKepalaSekolah">Kepala Sekolah</label>
                  <input type="text" class="easyui-validatebox" required id="reqKepalaSekolah"  name="reqKepalaSekolah" <?=$read?> value="<?=$reqKepalaSekolah?>" />
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSttb">No. STTB</label>
                  <input type="text" class="easyui-validatebox" required id="reqNoSttb" name="reqNoSttb" <?=$read?> value="<?=$reqNoSttb?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSttb">Tgl. STTB</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSttb" id="reqTglSttb"  value="<?=$reqTglSttb?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSttb');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSuratIjin">No. Surat Ijin / Tugas Belajar</label>
                  <input type="text"  id="reqNoSuratIjin" name="reqNoSuratIjin" <?=$read?> value="<?=$reqNoSuratIjin?>"  />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSuratIjin">Tgl. Surat Ijin / Tugas Belajar</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSuratIjin" id="reqTglSuratIjin"  value="<?=$reqTglSuratIjin?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSuratIjin');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12">
                  <?php /*?><textarea name="reqAlamatSekolah" id="reqAlamatSekolah" class="required materialize-textarea"><?=$reqAlamatSekolah?></textarea><?php */?>
                  <label for="reqAlamatSekolah">Tempat Sekolah</label>
                  <input type="text" class="easyui-validatebox" id="reqAlamatSekolah" name="reqAlamatSekolah" <?=$read?> value="<?=$reqAlamatSekolah?>" />
                </div>
              </div>  

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_pendidikan_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqNamaFakultas" value="<?=$reqNamaFakultas?>" />
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
                  <?
                  for($index_loop=0; $index_loop < 7; $index_loop++)
                  {
                  ?>
                  <br/>
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

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>