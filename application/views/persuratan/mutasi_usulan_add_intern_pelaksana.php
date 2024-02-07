<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/UsJabatanMutasiIntern');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisMutasiId= $this->input->get("reqJenisMutasiId");
$reqJenisJabatanTugasId= $this->input->get("reqJenisJabatanTugasId");
$reqMode= $this->input->get("reqMode");
$sessionLoginLevel= $this->LOGIN_LEVEL;

if($reqId=="")
{
  $reqMode = 'insert';

  if($reqJenisMutasiId == "1")
  {
    $arrJenisJabatanTugasId= array("11", "12");
    $arrJenisJabatanTugasNama= array("Jabatan Struktural", "Pelaksana");
  }
  elseif($reqJenisMutasiId == "2")
  {
    $arrJenisJabatanTugasId= array("21", "22", "29");
    $arrJenisJabatanTugasNama= array("JFT Pendidikan", "JFT Kesehatan", "Mutasi Intern Pelaksana");
  }

}
else
{
	$reqMode = 'update';
	$set= new UsJabatanMutasiIntern();
	$statement= " AND A.US_JABATAN_MUTASI_INTERN_ID = ".$reqId;
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqId= $set->getField('US_JABATAN_MUTASI_INTERN_ID');
	$reqSatkerId= $set->getField('SATKER_ID');
	$reqSatker= $set->getField('SATUAN_KERJA_NAMA_DETIL');

  $reqPegawaiId= $set->getField('PEGAWAI_ID');
  $reqSatkerAsalId= $set->getField('SATKER_ASAL_ID');
  $reqSatkerAsalNama= $set->getField('SATKER_ASAL_NAMA');
  $reqNipBaru= $set->getField('NIP_BARU');
  $reqNamaPegawai= $set->getField('NAMA_LENGKAP');
  $reqJenisMutasiId= $set->getField('JENIS_MUTASI_ID');
  $reqJenisMutasiNama= $set->getField('JENIS_MUTASI_NAMA');
  $reqJenisJabatanTugasId= $set->getField('JENIS_JABATAN_TUGAS_ID');
  $reqJenisJabatanTugasNama= $set->getField('JENIS_JABATAN_TUGAS_NAMA');
  $reqLastLevel= $set->getField('LAST_LEVEL');

  $reqStatusUsulan= $set->getField('STATUS_USULAN');
  // $reqUtamaRowId= $set->getField('JABATAN_RIWAYAT_ID');

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
  $(function(){
   $(".preloader-wrapper").hide();
	
   $('#ff').form({
    url:'surat/mutasi_usulan_json/add_mutasi_intern',
    onSubmit:function(){
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
         reloadparenttab();
         mbox.close();
         document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_intern_pelaksana/?reqId="+rowid;
       }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }

    }
  });

   $('input[id^="reqNipBaru"]').each(function(){
    $(this).autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqNipBaru') !== -1)
        {
          $(".preloader-wrapper").show();
          urlAjax= "surat/mutasi_usulan_json/cari_pegawai_usulan?reqTipePegawaiId=12&reqSatuanKerjaId=<?=$this->SATUAN_KERJA_ID?>";
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
            , satuankerjaid: element['satuankerjaid'], satuankerjanama: element['satuankerjanama']};
              });
              response(array);
            }
          }
        })
      },
      focus: function (event, ui) 
      { 
        var id= $(this).attr('id');
        if (id.indexOf('reqNipBaru') !== -1)
        {
        var indexId= "reqPegawaiId";
        var namapegawai= satuankerjaid= "";
        namapegawai= ui.item.namapegawai;
        satuankerjaid= ui.item.satuankerjaid;
        satuankerjanama= ui.item.satuankerjanama;

        $("#reqNamaPegawai").val(namapegawai);
        $("#reqSatkerAsalId").val(satuankerjaid);
        $("#reqSatkerAsalNama").val(satuankerjanama);
        }

        $("#"+indexId).val(ui.item.id).trigger('change');
      },
      autoFocus: true
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
    }
    ;
    });

   $('input[id^="reqPejabatPenetap"], input[id^="reqSatker"]').each(function(){
    $(this).autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
        else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqNamaId"+element[1];
          reqTanggalBatas= $("#reqTglSk").val();
          urlAjax= "satuan_kerja_json/namajabatan?reqTanggalBatas="+reqTanggalBatas+"&reqTipeJabatanId=x2";
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var reqSatkerAsalId= reqPegawaiId= "";
          reqSatkerAsalId= $("#reqSatkerAsalId").val();
          reqPegawaiId= $("#reqPegawaiId").val();

          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          // reqTanggalBatas= $("#reqTmtJabatan").val();
          // urlAjax= "satuan_kerja_json/cari_satuan_kerja_mutasi?reqTanggalBatas=&reqSatkerAsalId="+reqSatkerAsalId+"&reqPegawaiId="+reqPegawaiId;
          urlAjax= "satuan_kerja_json/auto?reqSatuanKerjaIndukId=<?=$this->SATUAN_KERJA_ID?>";
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
        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
        }
        else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqSatkerId"+element[1];
          $("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
          $("#reqEselonId").val(ui.item.eselon_id).trigger('change');
          $("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          $("#reqNama").val("").trigger('change');
        }

        $("#"+indexId).val(ui.item.id).trigger('change');
      },
      autoFocus: true
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
    }
    ;
    });

      $("#reqJenisMutasiId,#reqJenisJabatanTugasId").change(function() { 
        var reqJenisMutasiId= reqJenisJabatanTugasId= "";
        reqJenisMutasiId= $("#reqJenisMutasiId").val();
        reqJenisJabatanTugasId= $("#reqJenisJabatanTugasId").val();
        // alert(reqJenisMutasiId);

        if(reqJenisMutasiId == "1")
        {
          if(reqJenisJabatanTugasId == "11")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_struktural?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "12")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_fungsional?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId;
        }
        else if(reqJenisMutasiId == "2")
        {
          if(reqJenisJabatanTugasId == "21")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_jft_pendidikan?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "22")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_jft_kesehatan?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "29")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_intern_pelaksana?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId;
        }
        else
        {
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add";
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
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">USUL MUTASI UNIT KERJA</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <?
                  if($reqId == "")
                  {
                  ?>
                  <select id="reqJenisMutasiId" name="reqJenisMutasiId" >
                    <option value="" <? if($reqJenisMutasiId == "") echo 'selected';?>></option>
                    <option value="1" <? if($reqJenisMutasiId == 1) echo 'selected';?>>Mutasi Struktural / Pelaksana</option>
                    <option value="2" <? if($reqJenisMutasiId == 2) echo 'selected';?>>Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana</option>
                  </select>
                  <label for="reqJenisMutasiId">Jenis Mutasi</label>
                  <?
                  }
                  else
                  {
                  ?>
                  <label for="reqJenisMutasiId">Jenis Mutasi</label>
                  <input name="reqJenisMutasiId" type="hidden" value="<?=$reqJenisMutasiId?>" />
                  <input required type="text" value="<?=$reqJenisMutasiNama?>" disabled />
                  <?
                  }
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <?
                  if($reqId == "")
                  {
                  ?>
                  <select id="reqJenisJabatanTugasId" name="reqJenisJabatanTugasId" >
                    <option value="" <? if($reqJenisJabatanTugasId == "") echo 'selected';?>></option>
                    <?
                    for($i=0; $i < count($arrJenisJabatanTugasId); $i++)
                    {
                    ?>
                    <option value="<?=$arrJenisJabatanTugasId[$i]?>" <? if($arrJenisJabatanTugasId[$i] == $reqJenisJabatanTugasId) echo 'selected';?>><?=$arrJenisJabatanTugasNama[$i]?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqJenisJabatanTugasId">Jenis Jabatan / Jenis Tugas</label>
                  <?
                  }
                  else
                  {
                  ?>
                  <label for="reqJenisJabatanTugasId">Jenis Jabatan / Jenis Tugas</label>
                  <input name="reqJenisJabatanTugasId" type="hidden" value="<?=$reqJenisJabatanTugasId?>" />
                  <input required type="text" value="<?=$reqJenisJabatanTugasNama?>" disabled />
                  <?
                  }
                  ?>
                </div>
              </div>

              <div class="row">
                  <div class="input-field col s12 m6">
                    <input type="hidden" name="reqJenisJabatan" value="<?=$reqJenisJabatan?>" />
                    <input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqSatkerAsalId" id="reqSatkerAsalId" value="<?=$reqSatkerAsalId?>" />
                    <label for="reqNipBaru">NIP Baru</label>
                    <?
                    if($reqId == "")
                    {
                    ?>
                    <input required id="reqNipBaru" class="easyui-validatebox" type="text" value="<?=$reqNipBaru?>" />
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
                <div class="input-field col s12 m6">
                    <label for="reqNamaPegawai" class="active">Nama</label>
                    <input placeholder id="reqNamaPegawai" class="easyui-validatebox" type="text" value="<?=$reqNamaPegawai?>" disabled />
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                    <label for="reqSatkerAsalNama" class="active">Unit Kerja Asal</label>
                    <input placeholder id="reqSatkerAsalNama" class="easyui-validatebox" type="text" value="<?=$reqSatkerAsalNama?>" disabled />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqSatker">Satuan Kerja Tujuan</label>
                  <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                  <input type="text" id="reqSatker" name="reqSatker" <?=$read?> value="<?=$reqSatker?>" class="easyui-validatebox" required />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      closeparenttab();
                    });

                    function closeparenttab()
                    {
                    if (window.opener && window.opener.document)
                        {
                            if (typeof window.opener.setCariInfo === 'function')
                            {
                                window.opener.setCariInfo();
                            }
                        }
                        window.close();
                    }

                    function reloadparenttab()
                    {
                    if (window.opener && window.opener.document)
                      {
                        if (typeof window.opener.setCariInfo === 'function')
                        {
                          window.opener.setCariInfo();
                        }
                      }
                    }
                  </script>

                  <input type="hidden" name="reqUtamaRowId" value="<?=$reqUtamaRowId?>" />
                  <input type="hidden" name="reqTipePegawaiId" value="<?=$reqTipePegawaiId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <?
                  if($reqStatusUsulan == "")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  if($sessionLoginLevel > $reqLastLevel)
                  {
                    if($reqStatusUsulan == "")
                    {
                      if($reqId == ""){}
                      else
                      {
                  ?>
                  <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="reqkirim">Valid
                      <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <?
                      }
                    }
                  }
                  ?>

                </div>
              </div>

              <!-- </div> -->
            </form>
          </li>
        </ul>
      </div>
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

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>   

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqTmtWaktuJabatan').formatter({
	'pattern': '{{99}}:{{99}}',
	});
	
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

  $("#reqkirim").click(function() { 

      mbox.custom({
       message: "Apakah Anda Yakin, valid. Pastikan entri data sudah sesuai ?",
       options: {close_speed: 100},
       buttons: [
       {
         label: 'Ya',
         color: 'green darken-2',
         callback: function() {
          var s_url= "surat/mutasi_usulan_json/valid?reqJenisJabatanTugasId=<?=$reqJenisJabatanTugasId?>&reqId=<?=$reqId?>";
           $.ajax({'url': s_url,'success': function(dataajax){
            // var requrl= requrllist= "";
            dataajax= String(dataajax);
            var element = dataajax.split('-'); 
            // dataajax= element[0];
            info= element[1];
            // requrllist= element[2];
            // requrlcss= element[3];
            mbox.alert(info, {open_speed: 500}, interval = window.setInterval(function() 
            {
              clearInterval(interval);
              reloadparenttab();
              mbox.close();
              document.location.href= "app/loadUrl/persuratan/mutasi_usulan_add_intern_pelaksana/?reqId=<?=$reqId?>";
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }});
           mbox.close();
         }
       },
       {
         label: 'Tidak',
         color: 'grey darken-2',
         callback: function() {
           mbox.close();
         }
       }
       ]
     });

    });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>