<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");

if(empty($reqJenisJabatan))
	$reqJenisJabatan= "1";

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "1201";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);
?>
<div class="row">
	<div class="input-field col s12 m6">
		<select id="reqJenisJabatan" name="reqJenisJabatan">
			<option value="1" <? if($reqJenisJabatan==1) echo 'selected';?>>Jabatan Struktural</option>
			<option value="2" <? if($reqJenisJabatan==2) echo 'selected';?>>Jabatan Fungsional Umum</option>
		</select>
		<label for="reqJenisJabatan">Jenis Jabatan</label>
	</div>
</div>

<div class="row">
	<div class="input-field col s12 m6">
		<label for="reqJabatanStrukturalNama">Nama Jabatan</label>
		<input type="hidden" name="reqJabatanStrukturalJabatanFuId" id="reqJabatanStrukturalJabatanFuId" value="<?=$reqJabatanStrukturalJabatanFuId?>" />
		<input placeholder="" type="text" id="reqJabatanStrukturalJabatanFu" name="reqJabatanStrukturalJabatanFu" <?=$read?> value="<?=$reqJabatanStrukturalJabatanFu?>" class="easyui-validatebox" required />
	</div>
	<div class="input-field col s12 m1">
		<input type="checkbox" id="reqJabatanStrukturalCheckTmtWaktuJabatan" name="reqJabatanStrukturalCheckTmtWaktuJabatan" value="1" <? if($reqJabatanStrukturalCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
		<label for="reqJabatanStrukturalCheckTmtWaktuJabatan"></label>
	</div>
	<div class="input-field col s12 m3">
		<label for="reqJabatanStrukturalTmtJabatan">TMT Jabatan</label>
		<input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanStrukturalTmtJabatan" id="reqJabatanStrukturalTmtJabatan"  value="<?=$reqJabatanStrukturalTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanStrukturalTmtJabatan');"/>
	</div>
	<div class="input-field col s12 m2" id="reqJabatanStrukturalInfoCheckTmtWaktuJabatan">
		<input placeholder="00:00" id="reqJabatanStrukturalTmtWaktuJabatan" name="reqJabatanStrukturalTmtWaktuJabatan" type="text" class="" value="<?=$reqJabatanStrukturalTmtWaktuJabatan?>" />
		<label for="reqJabatanStrukturalTmtWaktuJabatan">Time</label>
	</div>
</div>

<div class="row">
	<div class="input-field col s12">
		<input type="checkbox" id="reqJabatanStrukturalIsManual" name="reqJabatanStrukturalIsManual" value="1" <? if($reqJabatanStrukturalIsManual == 1) echo 'checked'?> />
		<label for="reqJabatanStrukturalIsManual"></label>
		*centang jika satker luar kab jombang / satker sebelum tahun 2012
	</div>
</div>

<div class="row">
	<div class="input-field col s12 m12">
		<label for="reqJabatanStrukturalSatker">Satuan Kerja</label>
		<input placeholder="" type="text" id="reqJabatanStrukturalSatker" name="reqJabatanStrukturalSatker" <?=$read?> value="<?=$reqJabatanStrukturalSatker?>" class="easyui-validatebox" required />
		<input type="hidden" name="reqJabatanStrukturalSatkerId" id="reqJabatanStrukturalSatkerId" value="<?=$reqJabatanStrukturalSatkerId?>" />
	</div>
</div>

<div class="row">
	<div class="input-field col s12 m6">
		<label for="reqJabatanStrukturalTunjangan">Tunjangan</label>
		<input placeholder="" type="text" id="reqJabatanStrukturalTunjangan" name="reqJabatanStrukturalTunjangan" OnFocus="FormatAngka('reqJabatanStrukturalTunjangan')" OnKeyUp="FormatUang('reqJabatanStrukturalTunjangan')" OnBlur="FormatUang('reqJabatanStrukturalTunjangan')" value="<?=numberToIna($reqJabatanStrukturalTunjangan)?>" />
	</div>
	<div class="input-field col s12 m6">
		<label for="reqJabatanStrukturalBlnDibayar">Bln. Dibayar</label>
		<input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanStrukturalBlnDibayar" id="reqJabatanStrukturalBlnDibayar"  value="<?=$reqJabatanStrukturalBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanStrukturalBlnDibayar');"/>
	</div>
</div>

<script type="text/javascript"> 
function settimetmt(info)
{
	$("#reqJabatanStrukturalInfoCheckTmtWaktuJabatan").hide();
	if($("#reqJabatanStrukturalCheckTmtWaktuJabatan").prop('checked')) 
	{
		$("#reqJabatanStrukturalInfoCheckTmtWaktuJabatan").show();
	}
	else
	{
		if(info == 2)
		$("#reqJabatanStrukturalTmtWaktuJabatan").val("");
	}
}

function setcetang()
{
	/*if($("#reqJabatanStrukturalIsManual").prop('checked')) 
    {
      $("#reqJabatanStrukturalSatkerId").val("");
    }
    else
    {
      $("#reqJabatanStrukturalSatker, #reqJabatanStrukturalSatkerId").val("");
    }*/
    $("#reqJabatanStrukturalSatker, #reqJabatanStrukturalSatkerId").val("");
}

$(function(){
	settimetmt(1);
	setcetang();

	$("#reqJabatanStrukturalCheckTmtWaktuJabatan").click(function () {
		settimetmt(2);
	});

	$("#reqJabatanStrukturalIsManual").click(function () {
		setcetang();
	});
});
</script>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>
	
<script type="text/javascript">
	$(document).ready(function() {
		$('select').material_select();

		$('#reqJabatanStrukturalNama,#reqJabatanStrukturalTmtJabatan,#reqJabatanStrukturalSatker,#reqJabatanStrukturalJabatanFu').validatebox({required: true});

		$('input[id^="reqJabatanStrukturalSatker"]').autocomplete({
			source:function(request, response){
				var id= this.element.attr('id');
				var replaceAnakId= replaceAnak= urlAjax= "";

				if (id.indexOf('reqJabatanStrukturalSatker') !== -1)
				{
					if($("#reqJabatanStrukturalIsManual").prop('checked')) 
					{
						return false;
					}
				}
		
			    if (id.indexOf('reqJabatanStrukturalSatker') !== -1)
			    {
			    	var element= id.split('reqJabatanStrukturalSatker');
			    	var indexId= "reqJabatanStrukturalSatkerId"+element[1];
			    	reqTanggalBatas= $("#reqJabatanStrukturalTmtJabatan").val();
			    	urlAjax= "satuan_kerja_json/auto?reqTanggalBatas="+reqTanggalBatas;
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
						      return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], rumpun_id: element['rumpun_id'], rumpun_nama: element['rumpun_nama']};
		              });
		              response(array);
		            }
		          }
		        })
      		},
      		focus: function (event, ui) 
      		{ 
      			var id= $(this).attr('id');
      			if (id.indexOf('reqJabatanStrukturalSatker') !== -1)
      			{
      				var element= id.split('reqJabatanStrukturalSatker');
      				var indexId= "reqJabatanStrukturalSatkerId"+element[1];

      				$("#reqRumpunJabatan").val(ui.item.rumpun_id).trigger('change');
      				$("#reqRumpunJabatanNama").val(ui.item.rumpun_nama).trigger('change');
      			}

      			$("#"+indexId).val(ui.item.id).trigger('change');
      		},
      		autoFocus: true
    	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
    	return $( "<li>" )
    	.append( "<a>" + item.desc + "</a>" )
    	.appendTo( ul );
    	};
	  
    	$('input[id^="reqJabatanStrukturalJabatanFu"]').autocomplete({
    		source:function(request, response){
    			var id= this.element.attr('id');
    			var replaceAnakId= replaceAnak= urlAjax= "";

    			if (id.indexOf('reqJabatanStrukturalJabatanFu') !== -1)
    			{
    				var element= id.split('reqJabatanStrukturalJabatanFu');
    				var indexId= "reqJabatanStrukturalJabatanFuId"+element[1];
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
      			if (id.indexOf('reqJabatanStrukturalJabatanFu') !== -1)
      			{
      				var element= id.split('reqJabatanStrukturalJabatanFu');
      				var indexId= "reqJabatanStrukturalJabatanFuId"+element[1];
      			}
      			$("#"+indexId).val(ui.item.id).trigger('change');
      		},
      		autoFocus: true
    	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( "<a>" + item.desc + "</a>" )
		.appendTo( ul );
    	};

		$("#reqJenisJabatan").change(function() { 
			var jenis_jabatan = $("#reqJenisJabatan").val();

			$('#labeldetilinfo').empty();

			if(jenis_jabatan==1)
			{
				infodetil= "pegawai_add_hukuman_jabatan_struktural";
			}
			else if(jenis_jabatan==2)
			{
				infodetil= "pegawai_add_hukuman_jabatan_fungsional";
			}

			$.ajax({'url': "app/loadUrl/app/"+infodetil+"/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqMode=<?=$reqMode?>&reqJenisJabatan="+jenis_jabatan,'success': function(datahtml) {
				$('#labeldetilinfo').append(datahtml);
			}});

		});
	});
</script>