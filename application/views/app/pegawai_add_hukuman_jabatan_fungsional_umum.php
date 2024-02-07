<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "1201";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

?>
<div class="row">
	<div class="input-field col s12 m6">
	  <label for="reqJabatanFu">Nama Jabatan</label>
	  <input type="hidden" name="reqJabatanFuId" id="reqJabatanFuId" value="<?=$reqJabatanFuId?>" /> 
	  <input placeholder="" type="text" id="reqJabatanFu" name="reqJabatanFuNama" <?=$read?> value="<?=$reqJabatanFuNama?>" class="easyui-validatebox" required />
	</div>
	<div class="input-field col s12 m1">
	    <input type="checkbox" id="reqJabatanFuCheckTmtWaktuJabatan" name="reqJabatanFuCheckTmtWaktuJabatan" value="1" <? if($reqJabatanFuCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
	    <label for="reqJabatanFuCheckTmtWaktuJabatan"></label>
	</div>
	<div class="input-field col s12 m3">
	  <label for="reqJabatanFuTmtJabatan">TMT Jabatan</label>
	  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanFuTmtJabatan" id="reqJabatanFuTmtJabatan"  value="<?=$reqJabatanFuTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanFuTmtJabatan');"/>
	</div>
	<div class="input-field col s12 m2" id="reqJabatanFuInfoCheckTmtWaktuJabatan">
	    <input placeholder="00:00" id="reqJabatanFuTmtWaktuJabatan" name="reqJabatanFuTmtWaktuJabatan" type="text" class="" value="<?=$reqJabatanFuTmtWaktuJabatan?>" />
	    <label for="reqJabatanFuTmtWaktuJabatan">Time</label>
	</div>
</div>

<div class="row">
	<div class="input-field col s12">
	  <input type="checkbox" id="reqJabatanFuIsManual" name="reqJabatanFuIsManual" value="1" <? if($reqJabatanFuIsManual == 1) echo 'checked'?> />
	  <label for="reqJabatanFuIsManual"></label>
	  *centang jika satker luar kab jombang / satker sebelum tahun 2012
	</div>
</div>

<div class="row">
	<div class="input-field col s12 m12">
	  <label for="reqJabatanFuSatkerNama">Satuan Kerja</label>
	  <input placeholder="" type="text" id="reqJabatanFuSatkerNama"  name="reqJabatanFuSatker" <?=$read?> value="<?=$reqJabatanFuSatker?>" class="easyui-validatebox" required />
	  <input type="hidden" name="reqJabatanFuSatkerId" id="reqJabatanFuSatkerNamaId" value="<?=$reqJabatanFuSatkerId?>" />
	</div>
</div>

<div class="row">
	<div class="input-field col s12 m6">
	  <label for="reqJabatanFuTunjangan">Tunjangan</label>
	  <input placeholder="" type="text" id="reqJabatanFuTunjangan" name="reqJabatanFuTunjangan" OnFocus="FormatAngka('reqJabatanFuTunjangan')" OnKeyUp="FormatUang('reqJabatanFuTunjangan')" OnBlur="FormatUang('reqJabatanFuTunjangan')" value="<?=numberToIna($reqJabatanFuTunjangan)?>" />
	</div>
	<div class="input-field col s12 m6">
	  <label for="reqJabatanFuBlnDibayar">Bln. Dibayar</label>
	  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanFuBlnDibayar" id="reqJabatanFuBlnDibayar"  value="<?=$reqJabatanFuBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanFuBlnDibayar');"/>
	</div>
</div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>

<script type="text/javascript"> 
function settimetmt(info)
{
	$("#reqJabatanFuInfoCheckTmtWaktuJabatan").hide();
	if($("#reqJabatanFuCheckTmtWaktuJabatan").prop('checked')) 
	{
		$("#reqJabatanFuInfoCheckTmtWaktuJabatan").show();
	}
	else
	{
		if(info == 2)
		$("#reqJabatanFuTmtWaktuJabatan").val("");
	}
}

$(function(){
 settimetmt(1);
 $("#reqJabatanFuCheckTmtWaktuJabatan").click(function () {
	 settimetmt(2);
 });
 
 $("#reqJabatanFuIsManual").click(function () {
		$("#reqJabatanFuSatkerNama, #reqJabatanFuSatkerNamaId").val("");
 });
 
 $('input[id^="reqJabatanFuSatkerNama"], input[id^="reqJabatanFu"]').each(function(){
	$(this).autocomplete({
	source:function(request, response){
	  var id= this.element.attr('id');
	  var replaceAnakId= replaceAnak= urlAjax= "";
	  
	if (id.indexOf('reqJabatanFuSatkerNama') !== -1)
	{
		if($("#reqJabatanFuIsManual").prop('checked')) 
		{
			return false;
		}
	}
	
	if (id.indexOf('reqJabatanFuSatkerNama') !== -1)
	{
		var element= id.split('reqJabatanFuSatkerNama');
		var indexId= "reqJabatanFuSatkerNamaId"+element[1];
		urlAjax= "satuan_kerja_json/auto";
		//urlAjax= "satuan_kerja_json/namajabatan";
	}
	//else if (id.indexOf('reqJabatanFu') !== -1)
	else if (id == 'reqJabatanFu')
	{
	  var element= id.split('reqJabatanFu');
	  var indexId= "reqJabatanFuId"+element[1];
	  urlAjax= "jabatan_fu_json/namajabatan";
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
		if (id.indexOf('reqJabatanFuSatkerNama') !== -1)
		{
		var element= id.split('reqJabatanFuSatkerNama');
		var indexId= "reqJabatanFuSatkerNamaId"+element[1];
		//$("#reqJabatanFuNama").val("").trigger('change');
		}
		//else if (id.indexOf('reqJabatanFu') !== -1)
		else if (id == 'reqJabatanFu')
		{
		  var element= id.split('reqJabatanFu');
		  var indexId= "reqJabatanFuId"+element[1];
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
  

});

$('#reqJabatanFuTmtWaktuJabatan').formatter({
'pattern': '{{99}}:{{99}}',
});
</script>