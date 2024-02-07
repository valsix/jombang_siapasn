<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/UsulanSurat');
$this->load->model('persuratan/JenisPelayanan');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
if($reqId=="")
{
	$statement= " AND A.JENIS_PELAYANAN_ID = ".$reqJenis."";
	$set= new JenisPelayanan();
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqMode = 'insert';
	$reqKepada= $set->getField("KEPADA");
	$reqTanggal= "";
	$reqSatuanKerjaTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");;
	$tempPerihalInfo= $reqPerihal= setjenisperihalinfo($reqJenis);
}
else
{
 $reqMode = 'update';
 $statement= " AND A.USULAN_SURAT_ID = ".$reqId."";
 $set= new UsulanSurat();
 $set->selectByParams(array(), -1, -1, $statement);
 $set->firstRow();
 //echo $set->query;exit;

 $reqNomor= $set->getField("NOMOR");
 $reqAgenda= $set->getField("NO_AGENDA");
 $reqIdSementara= $set->getField("ID_SEMENTARA");
 $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
 $reqTanggalDiteruskan= dateToPageCheck($set->getField("TANGGAL_DITERUSKAN"));
 $reqTanggalBatas= dateToPageCheck($set->getField("TANGGAL_BATAS"));
 $reqKepada= $set->getField("KEPADA");
 $reqPerihal= $set->getField("PERIHAL");
 $reqSatkerTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
 $reqSatkerAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");

   //echo $reqSatkerTujuanId."--".$reqSatkerAsalId;exit;
 $reqSatuanKerjaTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");

 $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
 $reqStatusKirim= $set->getField("STATUS_KIRIM");

 if($reqStatusKirim == "")
 {
   if($reqJenis == 12 || $reqJenis == 8)
   $tempPerihalInfo= $reqPerihal;
   else
   $tempPerihalInfo= setjenisperihalinfo($reqJenis);
 }
 else
 {
  $tempPerihalInfo= $reqPerihal;
 }

 $reqKategori= $set->getField("KATEGORI");
 $reqKategoriNama= $set->getField("KATEGORI_NAMA");

 $statement_satuan_kerja= " AND STATUS_SATUAN_KERJA_BKPP = 1";
 $skerja= new SuratMasukPegawai();
 $tempsatuankerjaidbkdpp= $skerja->getSatuanKerjaId($statement_satuan_kerja);

 $skerja= new SuratMasukPegawai();
 $reqSatuanKerjaBkdId= $skerja->getSatuanKerja($tempsatuankerjaidbkdpp);
 unset($skerja);

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
      <?
      if($reqId == "" && ($reqJenis == "8"  || $reqJenis=="12"))
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
      $("#reqSubmit").click();

    });

      $('#ff').form({
        url:'surat/usulan_surat_json/add',
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
                  parent.reloadparenttab();
						//alert(rowid);
						<?
						if($reqId == "")
						{
							?>
							top.location.href= "app/loadUrl/persuratan/usulan_surat_add/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
							<?
						}
						else
						{
							?>
							parent.reloadinfosurat(rowid);
							document.location.href= "app/loadUrl/persuratan/usulan_surat_add_data/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
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
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
      <div class="col s12 m10 offset-m1">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna">Usulan Pelayanan <?=$reqJenisNama?></li>
          <li class="collection-item">

            <div class="row">
              <form id="ff" method="post" enctype="multipart/form-data">

                <?
                if($reqJenis=="8" || $reqJenis=="12")
                {
                ?>
                  <div class="row">
                      <div class="input-field col s12">
                      <?
                      if($reqId=="")
                      {
                      ?>
                      <select name="reqKategori" <?=$disabled?> id="reqKategori">
                        <option value="bup">Pensiun BUP</option>
                        <option value="meninggal">Pensiun Janda/Duda</option>
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
                ?>

                <div class="row">
                  <div class="input-field col s12">
                    <label for="reqIdSementara">ID Sementara</label>
                    <input required class="easyui-validatebox" type="text" name="reqIdSementara" id="reqIdSementara" <?=$read?> value="<?=$reqIdSementara?>"/>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqSatuanKerjaTujuanNama">Satuan Kerja Tujuan</label>
                    <input type="hidden" name="reqSatuanKerjaTujuanNama" value="<?=$reqSatuanKerjaTujuanNama?>" />
                    <input type="text" id="reqSatuanKerjaTujuanNama" disabled value="<?=$reqSatuanKerjaTujuanNama?>"  />
                  </div>

                  <div class="input-field col s12 m6">
                    <label for="reqKepada">Kepada</label>
                    <input type="hidden" name="reqKepada" value="<?=$reqKepada?>" />
                    <input type="text" id="reqKepada" <?=$read?> value="<?=$reqKepada?>" disabled />
                  </div>

                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNomor">Nomor</label>
                    <input placeholder readonly class="color-disb easyui-validatebox" type="text" name="reqNomor" id="reqNomor" <?=$read?> value="<?=$reqNomor?>"/>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggal">Tanggal</label>
                    <input placeholder readonly class="color-disb easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggal" id="reqTanggal" <?=$read?> value="<?=$reqTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggal');"/>
                  </div>
                </div>

                <div class="row">
                 <div class="input-field col s12">
                  <label for="reqPerihal">Perihal</label>
                  <input type="hidden" id="reqDataPerihal1" value="<?=$reqPerihal?>" />
                  <input type="hidden" id="reqDataPerihal2" value="BUP" />
                  <input type="hidden" name="reqPerihal" id="reqDataPerihal" value="<?=$reqPerihal?>" />
                  <input type="text" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>" disabled />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                 <input type="hidden" name="reqSatuanKerjaAsalId" id="reqSatuanKerjaAsalId" value="<?=$this->SATUAN_KERJA_ID?>" />
                 <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                 <input type="hidden" name="reqId" value="<?=$reqId?>" />
                 <input type="hidden" name="reqJenis" value="<?=$reqJenis?>" />
                 <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                 <?
                 if($reqStatusKirim == ""){
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
                    <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqkirim">Usul Nomor Keluar
                      <i class="mdi-content-forward left hide-on-small-only"></i>
                    </button>
                    <?
                  }
                }
                ?>

                <?
                if($reqStatusKirim == "1")
                {
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqbatal">Batal Usul Nomor
                    <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <?
                }
                ?>

                <?
                if($reqStatusKirim == "2")
                {
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqtujuankirim">Kirim ke Kanreg
                    <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <?
                }
                ?>

                <?
                if($reqStatusKirim == "3")
                {
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqsudahkirim">Sudah di Kirim ke Kanreg
                    <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <?
                }
                ?>

                <?
                if($reqId == ""){}
                else
                {
                  if($reqJenis == 12)
                  {
                ?>
                    <button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakpengantar">Cetak Pengantar Ke BKN Pusat
                      <i class="mdi-content-inbox left hide-on-small-only"></i>
                    </button>
                <?
                  }
                  else
                  {
                ?>
                    <button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakpengantar">Cetak Pengantar Ke Kanreg II BKN SBY
                      <i class="mdi-content-inbox left hide-on-small-only"></i>
                    </button>
                <?
                  }
                }
                ?>
                  <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="parent.closeparenttab()">Close
                   <i class="mdi-navigation-close left hide-on-small-only"></i>
                 </button>

                <?
                if($reqStatusKirim >= "2")
                {
                ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqdownloadfile">Download File PDF
                    <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                <?
                }
                ?>

               </div>
             </div>
           </div>
         </form>
       </div>
     </li>
   </ul>
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

    $("#reqsudahkirim,#reqtujuankirim,#reqkirim,#reqbatal").click(function() { 
     var id= $(this).attr('id');
     var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_usulan?reqId=<?=$reqId?>";
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
      else
      {
        var reqSatuanKerjaTujuanNama= "";
        reqSatuanKerjaTujuanNama= $("#reqSatuanKerjaTujuanNama").val();

        info= reqStatusKirim= "";
        if(id == "reqkirim")
        {
         info= "Apakah Anda Yakin, usul nomor surat Keluar. Pastikan usulan pegawai sudah di isi ?";
         reqStatusKirim= 1;
        }
        else if(id == "reqtujuankirim")
        {
         info= "Apakah anda yakin merubah status menjadi di kirim ke Kanreg II BKN?";
         // info= "Apakah Anda Yakin, usul nomor surat Keluar di kirim ke Kanreg II BKN ?";
         reqStatusKirim= 3;
        }
        else if(id == "reqsudahkirim")
        {
         info= "Apakah anda yakin merubah status menjadi di sudah kirim ke Kanreg II BKN?";
         // info= "Apakah Anda Yakin, usul nomor surat Keluar sudah di kirim ke Kanreg II BKN ?";
         reqStatusKirim= 4;
        }
        else if(id == "reqbatal")
        {
         info= "Apakah Anda Yakin, batal kirim ?";
        }

       mbox.custom({
         message: info,
         options: {close_speed: 100},
         buttons: [
         {
           label: 'Ya',
           color: 'green darken-2',
           callback: function() {
            $.getJSON("surat/usulan_surat_json/statuskirim/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqPerihal=<?=$tempPerihalInfo?>&reqStatusKirim="+reqStatusKirim,
              function(data){
               mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
               {
                clearInterval(interval);
                parent.reloadparenttab();
                document.location.href= "app/loadUrl/persuratan/usulan_surat_add_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
              }, 1000));
               $(".mbox > .right-align").css({"display": "none"});
             });

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
     }
   }});
   });

    $("#reqcetakpengantar").click(function() { 
        parent.openModal('app/loadUrl/persuratan/tanda_tangan_pilih_badan?reqStatusBkdUptId=8&reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqSatuanKerjaId=<?=$reqSatuanKerjaBkdId?>')
    });

    $("#reqdownloadfile").click(function() { 
      var s_url= "app/loadUrl/persuratan/download_file?reqMode=usulan&reqId=<?=$reqId?>";
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
        mbox.alert('File download belum ada', {open_speed: 0});
      }
      else
      {
        newWindow = window.open("app/loadUrl/persuratan/download_file?reqDownload=1&reqMode=usulan&reqId=<?=$reqId?>", 'Cetak');
        newWindow.focus();
      }
      }});

      
    });

    // $("#reqcetakpengantar").click(function() { 
    //   var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_usulan?reqId=<?=$reqId?>";
    //   // alert(s_url);return false;
    //   $.ajax({'url': s_url,'success': function(dataajax){
    //     var requrl= requrllist= "";
    //     dataajax= String(dataajax);
    //     var element = dataajax.split('-'); 
    //     dataajax= element[0];
    //     requrl= element[1];
    //     requrllist= element[2];
    //     requrlcss= element[3];

    //     if(dataajax == '0')
    //     {
    //       mbox.alert('Pilih Usulan pegawai terlebih dahulu', {open_speed: 0});
    //     }
    //     else
    //     {
    //       newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=3&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>", 'Cetak');
    //       newWindow.focus();
    //     }
    //     }});
    // });

  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>