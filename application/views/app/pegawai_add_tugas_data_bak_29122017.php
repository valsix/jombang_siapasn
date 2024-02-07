<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('JabatanTambahan');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");
$reqMode= $this->input->get("reqMode");

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
	$reqMode = 'update';
  $set= new JabatanTambahan();
  $statement= " AND A.JABATAN_TAMBAHAN_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqRowId= $set->getField('JABATAN_TAMBAHAN_ID');
  $reqNamaTugas= $set->getField('NAMA');
  $reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
  $reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
  $reqNoSk= $set->getField('NO_SK');
  $reqTglSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));
  $reqTmtJabatan= dateTimeToPageCheck($set->getField('TMT_JABATAN'));
  $reqTmtJabatanAkhir= dateTimeToPageCheck($set->getField('TMT_JABATAN_AKHIR'));
  $reqTugasTambahan= $set->getField('TUGAS_TAMBAHAN_ID');
  $reqStatusPlt= $set->getField('STATUS_PLT');
  $reqSatker= $set->getField('SATKER_NAMA');
  $reqSatkerId= $set->getField('SATKER_ID');
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
     $('#ff').form({
      url:'jabatan_tambahan_json/add',
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
          mbox.alert(infodata, {open_speed: 500}, window.setInterval(function() 
          {
           mbox.close();
           document.location.href= "app/loadUrl/app/pegawai_add_tugas_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
         }, 1000));
          
        }
      });

     $('input[id^="reqPejabatPenetap"], input[id^="reqJabatanFt"], input[id^="reqSatker"]').autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
        else if (id.indexOf('reqJabatanFt') !== -1)
        {
          var element= id.split('reqJabatanFt');
          var indexId= "reqJabatanFtId"+element[1];
          urlAjax= "jabatan_ft_json/namajabatan";
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          urlAjax= "satuan_kerja_json/auto";
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
                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja']};
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
        else if (id.indexOf('reqJabatanFt') !== -1)
        {
          var element= id.split('reqJabatanFt');
          var indexId= "reqJabatanFtId"+element[1];
        }
        else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          $("#reqNama").val("").trigger('change');
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

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT TUGAS</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqNamaTugas">Nama</label>
                  <input type="text" class="easyui-validatebox"  id="reqNamaTugas" name="reqNamaTugas" <?=$disabled?> value="<?=$reqNamaTugas?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTugasTambahan">Tugas Tambahan</label>
                  <input type="text" class="easyui-validatebox"  id="reqTugasTambahan" name="reqTugasTambahan" <?=$disabled?> value="<?=$reqTugasTambahan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqStatusPlt">Status PLT</label>
                  <input type="text" class="easyui-validatebox"  id="reqStatusPlt" name="reqStatusPlt" <?=$disabled?> value="<?=$reqStatusPlt?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk">No. SK</label>
                  <input type="text" class="easyui-validatebox"  id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSk">Tgl. SK</label>
                  <input type="text" class="easyui-validatebox"  id="reqTglSk" name="reqTglSk" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');" <?=$disabled?> value="<?=$reqTglSk?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTmtJabatan">TMT Jabatan</label>
                  <input type="text" class="easyui-validatebox"  id="reqTmtJabatan" name="reqTmtJabatan" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatan');" <?=$disabled?> value="<?=$reqTmtJabatan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTmtJabatanAkhir">TMT Jabatan Akhir</label>
                  <input type="text" class="easyui-validatebox"  id="reqTmtJabatanAkhir" name="reqTmtJabatanAkhir" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatanAkhir');" <?=$disabled?> value="<?=$reqTmtJabatanAkhir?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqSatkerId">Satuan Kerja</label>
                  <input type="text" id="reqSatker"  name="reqSatker" <?=$read?> value="<?=$reqSatker?>" class="easyui-validatebox" required />
                  <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap">Pejabat Penetap</label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox"  />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_tugas_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
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
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqKredit').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>
</body>