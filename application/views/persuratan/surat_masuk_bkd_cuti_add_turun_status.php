<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("base-cuti/CutiUsulan");

$reqRowId= $this->input->get("reqRowId");
$m= $this->input->get("m");
$kembali= $this->input->get("kembali");

$statement= " AND A.CUTI_USULAN_ID = ".$reqRowId;
$set= new CutiUsulan();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqPegawaiNama= $set->getField('NAMA_LENGKAP');
unset($set);
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
    url:'cuti_baru_json/turun_status',
    onSubmit:function(){
      var f = this;
      var opts = $.data(this, 'form').options;
      if($(this).form('validate') == false){
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

          if (window.parent && window.parent.document)
          {
            if (typeof window.parent.closeModal === 'function')
            {
              parent.closeModal();
            }

            kembali= "<?=$kembali?>";
            if(kembali == "")
            {
              if (typeof window.parent.setUrlInfo === 'function')
              {
                parent.setUrlInfo('app/loadUrl/persuratan/surat_masuk_bkd_cuti_add_turun_status?reqRowId=<?=$reqRowId?>');
              }
            }
            else
            {
              // if (typeof window.parent.mainframereload === 'function')
              // {
              //   parent.mainframereload('app/loadUrl/persuratan/'+kembali+'?m=<?=$m?>&reqRowId=<?=$reqRowId?>');
              // }

              if (typeof window.parent.topframereload === 'function')
              {
                <?
                if(!empty($m))
                {
                ?>
                parent.topframereload('app/loadUrl/persuratan/'+kembali+'?m=<?=$m?>&reqRowId=<?=$reqRowId?>');
                <?
                }
                else
                {
                ?>
                parent.topframereload('app/loadUrl/persuratan/'+kembali+'?reqRowId=<?=$reqRowId?>');
                <?
                }
                ?>
              }

              
            }
          }
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
  <div id="basic-form" class="section">
    <div class="row">
      <div class="col s12 m10 offset-m1">

        <ul class="collection card" style="height: 90vh">
          <li class="collection-item ubah-color-warna">Turun Status</li>
          <li class="collection-item">
            <div class="row">
              <form id="ff" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="input-field col s12">
                    <label for="reqKeterangan">Keterangan</label>
                    <input placeholder="" required class="easyui-validatebox" type="text" name="reqKeterangan" id="reqKeterangan" <?=$read?> value="<?=$reqKeterangan?>" />
                  </div>
                </div>
                        
                <div class="row">
                  <div class="input-field col s12 m12">
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                    <button type="submit" style="display:none" id="reqSubmit"></button>
                    <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                      <i class="mdi-content-save left hide-on-small-only"></i>
                    </button>
                  </div>
                </div>

              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('select').material_select();
	
	$("#reqsimpan").click(function() { 
		if($("#ff").form('validate') == false){
			return false;
		}
		
		mbox.custom({
		   message: "Apakah yakin untuk proses Turun Status an. <?=$reqPegawaiNama?> ?",
		   options: {close_speed: 100},
		   buttons: [
			   {
				   label: 'Ya',
				   color: 'green darken-2',
				   callback: function() {
					   $("#reqSubmit").click();
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
	
});

$('.materialize-textarea').trigger('autoresize');
</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>