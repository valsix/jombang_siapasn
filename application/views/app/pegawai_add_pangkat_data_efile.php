<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PangkatRiwayat');
$this->load->model('Pangkat');
$this->load->model('PegawaiFile');
$pangkat= new Pangkat();

$sessionLoginLevel= $this->LOGIN_LEVEL;
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisKp= $this->input->get("reqJenisKp");
$reqMode= $this->input->get("reqMode");
$reqPeriode= $this->input->get("reqPeriode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010301";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);


$pangkat->selectByParams(array());

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new PangkatRiwayat();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();
  // echo $set->query;exit();

  $reqRowId= $set->getField('PANGKAT_RIWAYAT_ID');
  // $reqNoDiklatPrajabatan = $set->getField('PEJABAT_PENETAP_ID');
  $reqTglStlud= dateToPageCheck($set->getField('TANGGAL_STLUD'));
  if($reqTglStlud == "01-01-0001")
    $reqTglStlud= "";
  // echo $reqTglStlud;exit();

  $reqStlud= $set->getField('STLUD');
  $reqNoStlud= $set->getField('NO_STLUD');
  $reqNoNota= $set->getField('NO_NOTA');
  $reqNoSk= $set->getField('NO_SK');
  $reqTh= $set->getField('MASA_KERJA_TAHUN');
  $reqBl= $set->getField('MASA_KERJA_BULAN');
  $reqKredit= dotToComma($set->getField('KREDIT'));
  $reqJenisKp= $set->getField('JENIS_RIWAYAT');
  $reqJenisKpNama= $set->getField('JENIS_RIWAYAT_NAMA');
  $reqKeterangan= $set->getField('KETERANGAN');
  $reqGajiPokok= $set->getField('GAJI_POKOK');
  $reqTglNota= dateToPageCheck($set->getField('TANGGAL_NOTA'));
  $reqTglSk= dateToPageCheck($set->getField('TANGGAL_SK'));
  $reqTmtGol= dateToPageCheck($set->getField('TMT_PANGKAT'));
  $reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
  $reqPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');
  $reqGolRuang= $set->getField('PANGKAT_ID');
  $reqNoUrutCetak= $set->getField('NO_URUT_CETAK');
  
  $LastLevel= $set->getField('LAST_LEVEL');
}

$tempAksiProses= "";
if($sessionLoginLevel < $LastLevel)
$tempAksiProses= "1";
// echo $set->query;exit;
// $reqRowId= $set->getField('PANGKAT_ID');


$statement= " AND A.RIWAYAT_TABLE='PANGKAT_RIWAYAT' AND A.RIWAYAT_ID=".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set= new PegawaiFile();
$set->selectByParamsFile(array(), -1, -1, $statement, $reqId);
// echo $set->query;exit();

while($set->nextRow()){
  $reqPegawaiFieldId= $set->getField('PEGAWAI_FILE_ID');
  $reqPath= $set->getField('PATH');
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
  	function setGaji()
    {
      var reqTglSk= reqPangkatId= reqMasaKerja= "";
      reqTglSk= $("#reqTglSk").val();
      reqPangkatId= $("#reqGolRuang").val();
      reqMasaKerja= $("#reqTh").val();

      urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
      $.ajax({'url': urlAjax,'success': function(data){
       //if(data == ''){}
         //else
         //{
           tempValueGaji= parseFloat(data);
           //tempValueGaji= (tempValueGaji * 80) / 100;
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
       
       $("#reqGolRuang").change(function(){
        setGaji();
      });

       $("#reqTglSk, #reqTh").keyup(function(){
        setGaji();
      });

       $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        // var reqTanggal= "";
        // reqTanggal= $("#reqTglSk").val();

        // var s_url= "hari_libur_json/pangkat?reqTanggal="+reqTanggal;
        // $.ajax({'url': s_url,'success': function(dataajax){
        //   if(dataajax == '1')
        //   {
        //     mbox.alert('Anda tidak berhak menambah data di atas tanggal sk 01-04-2017', {open_speed: 0});
        //     return false;
        //   }
        //   else
        //     $("#reqSubmit").click();
        // }});

        var reqTanggal= "";
        reqTanggal= $("#reqTmtGol").val();
        var s_url= "hari_libur_json/hakentrigaji?reqTanggal="+reqTanggal;
        $.ajax({'url': s_url,'success': function(dataajax){
          // return false;
          dataajax= dataajax.split(";");
          rowid= parseInt(dataajax[0]);
          infodata= dataajax[1];
          if(rowid == 1)
          {
            mbox.alert('Hubungi Sub Bidang Kepangkatan, Anda tidak berhak menambah data di atas TMT ' + infodata, {open_speed: 0});
            return false;
          }
          else
            $("#reqSubmit").click();
        }});
            
      });


       $('#ff').form({
        url:'pangkat_riwayat_json/add',
        onSubmit:function(){
          // return false;
         var reqJenisKp= $("#reqJenisKp").val();

         if(reqJenisKp == "")
         {
          mbox.alert("Lengkapi data Jenis Riwayat Pangkat terlebih dahulu", {open_speed: 0});
          return false;
        }
        
        var reqGolRuang= "";
        reqGolRuang= $("#reqGolRuang").val();
        
        if(reqGolRuang == "")
        {
         mbox.alert("Lengkapi data golongan ruang terlebih dahulu", {open_speed: 0});
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
            document.location.href= "app/loadUrl/app/pegawai_add_pangkat_data/?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqRowId="+rowid;
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
      
      setJenisKenaikanPangkat();
      $('#reqJenisKp').bind('change', function(ev) {
       setJenisKenaikanPangkat();
     });

    });
    
    function setJenisKenaikanPangkat()
    {
      var reqJenisKp= "";
      reqJenisKp= $("#reqJenisKp").val();
		//$("#reqinfobkn,#reqinfokredit").hide();
		$("#reqinfostlud,#reqinfokredit").hide();
		$("#reqinfobkn,#setinfopangkat").show();
		$('#reqKredit').validatebox({required: false});
    $('#reqKredit').removeClass('validatebox-invalid');
    
    if(reqJenisKp == "")
    {
     document.location.href = "app/loadUrl/app/pegawai_add_pangkat_baru?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqRowId=<?=$reqRowId?>";
   }
   else if(reqJenisKp == "7")
   {
			//$("#reqinfobkn").show();
			$("#reqinfostlud").show();
			$("#reqKredit").val("");
		}
		else if(reqJenisKp == "6")
		{
			$("#reqinfokredit").show();
			$('#reqKredit').validatebox({required: true});
      // console.log("s");
		}
		else
		{
     //  if(reqJenisKp == "10")
     //  {
     //    $("#reqStlud,#reqNoStlud,#reqTglStlud,#reqKredit").val("");
     //    $("#reqStlud").material_select();
     //  }
     //  else
     //  {
  			// $("#reqStlud,#reqNoStlud,#reqTglStlud,#reqKredit,#reqNoNota,#reqTglNota").val("");
  			// $("#reqStlud").material_select();
     //  }

     $("#reqStlud,#reqNoStlud,#reqTglStlud,#reqKredit").val("");
     $("#reqStlud").material_select();
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
     <div class="col s12 m6">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT PANGKAT</li>
         <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
              <div class="input-field col s12 m6">
               <?
               if($reqJenisKp == 1 || $reqJenisKp == 2)
               {
                ?>
                <label for="reqJenisKpNama">Jenis Riwayat Pangkat</label>
                <input type="hidden" id="reqJenisKp" name="reqJenisKp" value="<?=$reqJenisKp?>" />
                <input type="text" id="reqJenisKpNama" value="<?=$reqJenisKpNama?>" disabled />
                <?
              }
              else
              {
                ?>
                <label for="reqJenisKp" class="active">Jenis Riwayat Pangkat</label>
                <select <?=$disabled?> name="reqJenisKp" id="reqJenisKp" >
                  <option value="" <? if($reqJenisKp == "") echo 'selected'?>></option>
                  <option value="4" <? if($reqJenisKp == 4) echo 'selected'?>>Reguler</option>
                  <option value="11" <? if($reqJenisKp == 11) echo 'selected'?>>Kenaikan Pangkat Pengabdian</option>
                  <option value="5" <? if($reqJenisKp == 5) echo 'selected'?>>Pilihan Struktural</option>
                  <option value="6" <? if($reqJenisKp == 6) echo 'selected'?>>Pilihan JFT</option>
                  <option value="7" <? if($reqJenisKp == 7) echo 'selected'?>>Pilihan PI/UD</option>
                  <option value="10" <? if($reqJenisKp == 10) echo 'selected'?>>Penambahan Masa Kerja</option>
                  <option value="8" <? if($reqJenisKp == 8) echo 'selected'?>>Hukuman disiplin</option>
                  <option value="9" <? if($reqJenisKp == 9) echo 'selected'?>>Pemulihan hukuman disiplin</option>
                </select>
                <? } ?>
              </div>
            </div>
            
            <div class="row">
            	
            </div>
            
            <span id="setinfopangkat">
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk">No SK</label>
                  <input type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$read?> value="<?=$reqNoSk?>" title="No SK harus diisi"  />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTglSk">Tgl SK</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk"  value="<?=$reqTglSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqNoUrutCetak">No. Urut</label>
                  <input type="text" class="easyui-validatebox" id="reqNoUrutCetak" name="reqNoUrutCetak" <?=$read?> value="<?=$reqNoUrutCetak?>" />
                </div>
              </div>    

              <div class="row" id="reqinfostlud">
                <div class="input-field col s12 m4">
                  <select  name="reqStlud" id="reqStlud">
                    <option value=""></option>
                    <option value="1" <? if($reqStlud == 1) echo 'selected'?>>Tingkat I</option>
                    <option value="2" <? if($reqStlud == 2) echo 'selected'?>>Tingkat II</option>
                    <option value="3" <? if($reqStlud == 3) echo 'selected'?>>Tingkat III</option>
                  </select>
                  <label for="reqStlud">STLUD</label>
                </div> 
                <div class="input-field col s12 m4">
                  <label for="reqNoStlud">No. STLUD</label>
                  <input type="text" id="reqNoStlud" name="reqNoStlud" <?=$read?> value="<?=$reqNoStlud?>" />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqTglStlud">Tgl. STLUD</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglStlud" id="reqTglStlud"  value="<?=$reqTglStlud?>" maxlength="10" onKeyDown="return format_date(event,'reqTglStlud');"/>
                </div>
              </div>

              <div class="row" id="reqinfobkn">
                <div class="input-field col s12 m6">
                  <label for="reqNoNota">No Nota BKN</label>
                  <input type="text" name="reqNoNota" id="reqNoNota" <?=$read?> value="<?=$reqNoNota?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglNota">Tgl Nota BKN</label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglNota" id="reqTglNota"  value="<?=$reqTglNota?>" maxlength="10" onKeyDown="return format_date(event,'reqTglNota');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <select name="reqGolRuang" <?=$disabled?> id="reqGolRuang" >
                    <option value=""></option>
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
                  <label for="reqTmtGol">TMT SK</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtGol" id="reqTmtGol"  value="<?=$reqTmtGol?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtGol');"/>
                </div>
              </div>        

              <div class="row">
                <div class="input-field col s6 m3">
                  <label for="reqTh">Masa Kerja Tahun</label>
                  <input type="hidden" name="reqTempTh" value="<?=$reqTh?>" />
                  <input type="text" class="easyui-validatebox" required name="reqTh" <?=$read?> value="<?=$reqTh?>" id="reqTh" title="Masa kerja tahun harus diisi" />
                </div>

                <div class="input-field col s6 m3">
                  <label for="reqBl">Masa Kerja Bulan</label>
                  <input type="hidden" name="reqTempBl" value="<?=$reqBl?>" />
                  <input type="text" class="easyui-validatebox" required name="reqBl" <?=$read?> value="<?=$reqBl?>" id="reqBl" title="Masa kerja bulan diisi" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqGajiPokok">Gaji Pokok</label>
                  <input type="text" placeholder class="easyui-validatebox" required id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
                </div>
                <div class="input-field col s12 m3" id="reqinfokredit">
                  <label for="reqKredit">Angka Kredit</label>
                  <input type="text" id="reqKredit" name="reqKredit" <?=$read?>  class="easyui-validatebox" value="<?=$reqKredit?>" onkeypress='kreditvalidate(event, this)' />
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap">Pejabat Penetapan</label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <textarea <?=$disabled?> name="reqKeterangan" id="reqKeterangan" class="required materialize-textarea"><?=$reqKeterangan?></textarea>
                  <label for="reqKeterangan">Keterangan</label>
                </div>
              </div>  

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_pangkat_monitoring?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>";
                    });
                  </script>

                  <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <?
                  if($reqRowId == "")
                  {
                    ?>

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

                    <?
                  }
                  else
                  {
                    if($set->getField('STATUS') ==1 ){}
                      else
                      {
                       if($set->getField('DATA_HUKUMAN') == 0)
                       {
						   if($tempAksiProses == "1"){}
					  		else
							{
                        ?>
                        
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

                        <?
							}
                      }
                    }
                  }
                  ?>
                </div>
              </div>
              
            </span>

            <!-- </div> -->
          </form>
        </li>
      </ul>
     </div>

     <div class="col s12 m6">

                <div class="row">
                  <div id="pdfWrapper">
                    <iframe src="<?=$reqPath?>" frameborder="0" style='height: 590; width: 100%;'></iframe> 
                  </div>
                </div>


    </div>
  </div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>