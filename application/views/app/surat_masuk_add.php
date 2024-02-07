<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasuk');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
 $reqMode = 'update';
 $statement= " AND A.SURAT_MASUK_ID = ".$reqRowId."";
 $set= new SuratMasuk();
 $set->selectByParams(array(), -1, -1, $statement);
 $set->firstRow();

   // print_r($set);

 $reqNomor= $set->getField("NOMOR");
 $reqAgenda= $set->getField("NO_AGENDA");
 $reqTgl= dateToPageCheck($set->getField("TANGGAL"));
 $reqTglDiteruskan= dateToPageCheck($set->getField("TANGGAL_DITERUSKAN"));
 $reqTglBatas= dateToPageCheck($set->getField("TANGGAL_BATAS"));
 $reqKepada= $set->getField("KEPADA");
 $reqPerihal= $set->getField("PERIHAL");
 $reqSatkerTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
 $reqSatkerAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");

 $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
 $reqSatkerTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");
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
            url:'surat_masuk_json/add',
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
                mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
                {
					clearInterval(interval);
                    mbox.close();
                    document.location.href= "app/loadUrl/app/surat_masuk_add/?reqRowId="+rowid;
                }, 1000));
				$(".mbox > .right-align").css({"display": "none"});

            }
        });

        $('input[id^="reqSatkerAsal"], input[id^="reqSatkerTujuan"]').autocomplete({
            source:function(request, response){
                var id= this.element.attr('id');
                var replaceAnakId= replaceAnak= urlAjax= "";

                if (id.indexOf('reqSatkerAsal') !== -1)
                {
                    var element= id.split('reqSatkerAsal');
                    var indexId= "reqSatkerAsalId"+element[1];
                    urlAjax= "satuan_kerja_json/auto";
                } 

                else if (id.indexOf('reqSatkerTujuan') !== -1)
                {
                    var element= id.split('reqSatkerTujuan');
                    var indexId= "reqSatkerTujuanId"+element[1];
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
                if (id.indexOf('reqSatkerAsal') !== -1)
                {
                    var element= id.split('reqSatkerAsal');
                    var indexId= "reqSatkerAsalId"+element[1];
                    //$("#reqSatkerAsal").val("").trigger('change');
                }

                else if (id.indexOf('reqSatkerTujuan') !== -1)
                {
                    var element= id.split('reqSatkerTujuan');
                    var indexId= "reqSatkerTujuanId"+element[1];
                    //$("#reqSatkerAsal").val("").trigger('change');
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



    // $('input[id^="reqSatkerAsal"], input[id^="reqSatkerTujuan"]').autocomplete({
    //   source:function(request, response){
    //     var id= this.element.attr('id');
    //     var replaceAnakId= replaceAnak= urlAjax= "";

    //     if (id.indexOf('reqSatkerAsal') !== -1)
    //     {
    //         var element= id.split('reqSatkerAsal');
    //         var indexId= "reqSatkerAsalId"+element[1];
    //         urlAjax= "satuan_kerja_json/auto";
    //     }
    //     else if (id.indexOf('reqSatkerTujuan') !== -1)
    //     {
    //         var element= id.split('reqSatkerTujuan');
    //         var indexId= "reqSatkerTujuanId"+element[1];
    //         urlAjax= "satuan_kerja_json/auto";
    //     }

    //     $.ajax({
    //         url: urlAjax,
    //         type: "GET",
    //         dataType: "json",
    //         data: { term: request.term },
    //         success: function(responseData){
    //             if(responseData == null)
    //             {
    //                 response(null);
    //             }
    //             else
    //             {
    //                 var array = responseData.map(function(element) {
    //                     return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja']};
    //                 });
    //                 response(array);
    //             }
    //         }
    //     })
    // },
    // focus: function (event, ui) 
    // { 
    //     var id= $(this).attr('id');
    //     if (id.indexOf('reqSatkerAsal') !== -1)
    //     {
    //         var element= id.split('reqSatkerAsal');
    //         var indexId= "reqSatkerAsalId"+element[1];
    //     }
    //     else if (id.indexOf('reqSatkerTujuan') !== -1)
    //     {
    //         var element= id.split('reqSatkerTujuan');
    //         var indexId= "reqSatkerTujuanId"+element[1];
    //     }

    //     var statusht= "";
    //         //statusht= ui.item.statusht;
    //         $("#"+indexId).val(ui.item.id).trigger('change');
    //     },
    //         //minLength:3,
    //         autoFocus: true
    //     }).autocomplete( "instance" )._renderItem = function( ul, item ) {
    //     //return
    //     return $( "<li>" )
    //     .append( "<a>" + item.desc + "</a>" )
    //     .appendTo( ul );
    // };

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
                <li class="collection-item ubah-color-warna">EDIT SURAT MASUK</li>
                <li class="collection-item">

                  <div class="row">
                    <form id="ff" method="post" enctype="multipart/form-data">


                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="reqKepada">Kepada</label>
                                <input type="text" name="reqKepada" id="reqKepada" <?=$read?> value="<?=$reqKepada?>"/>
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="reqPerihal">Perihal</label>
                                <input type="text" name="reqPerihal" id="reqPerihal" <?=$read?> value="<?=$reqPerihal?>"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="reqNomor">Nomor</label>
                                <input type="text" name="reqNomor" id="reqNomor" <?=$read?> value="<?=$reqNomor?>"/>
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="reqTgl">Tanggal</label>
                                <input type="text" name="reqTgl" id="reqTgl" <?=$read?> value="<?=$reqTgl?>" maxlength="10" onKeyDown="return format_date(event,'reqTgl');"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="reqTglDiteruskan">Tanggal Diteruskan</label>
                                <input type="text" name="reqTglDiteruskan" id="reqTglDiteruskan" <?=$read?> value="<?=$reqTglDiteruskan?>" maxlength="10" onKeyDown="return format_date(event,'reqTglDiteruskan');"/>
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="reqTglBatas">Tanggal Batas</label>
                                <input type="text" name="reqTglBatas" id="reqTglBatas" <?=$read?> value="<?=$reqTglBatas?>" maxlength="10" onKeyDown="return format_date(event,'reqTglBatas');"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="reqSatkerAsal">Satuan Kerja Asal</label>
                                <!-- <input type="text" name="reqSatkerAsalId" id="reqSatkerAsalId" <?=$read?> value="<?=$reqSatkerAsalId?>"/> -->
                                <input type="text" id="reqSatkerAsal" <?=$read?> value="<?=$reqSatkerAsalNama?>" class="easyui-validatebox" required />
                                <input type="hidden" name="reqSatkerAsalId" id="reqSatkerAsalId" value="<?=$reqSatkerAsalId?>" />
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="reqSatkerTujuan">Satuan Kerja Tujuan</label>
                                <!-- <input type="text" name="reqSatkerTujuanId" id="reqSatkerTujuanId" <?=$read?> value="<?=$reqSatkerTujuanId?>"/> -->
                                <input type="text" id="reqSatkerTujuan" <?=$read?> value="<?=$reqSatkerTujuanNama?>" class="easyui-validatebox" required />
                                <input type="hidden" name="reqSatkerTujuanId" id="reqSatkerTujuanId" value="<?=$reqSatkerTujuanId?>" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m12">
                                <label for="reqAgenda">Agenda</label>
                                <textarea <?=$disabled?> name="reqAgenda" id="reqAgenda" class="materialize-textarea"><?=$reqAgenda?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m12">
                                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                                    <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
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
});

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
</body>
</html>