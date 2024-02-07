<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$checkbox_index = rand();
?>
<base href="<?=base_url()?>" />

<div class="col s12 m4">
    <input type="text" id="reqPegawai<?=$checkbox_index?>"  name="reqPegawai[]" />
    <input type="hidden" name="reqPegawaiId[]" id="reqPegawaiId<?=$checkbox_index?>" /> 
	<input type="hidden" name="reqRowDetilId[]" id="reqRowDetilId<?=$checkbox_index?>" />
</div>
<div class="col s12 m4">
	<input name="reqTglAwal[]" id="reqTglAwal" type="text"  />
</div>
<div class="col s12 m4">
	<input name="reqTglAkhir[]" id="reqTglAkhir" type="text"  />
</div>

<script>
$(function(){
	$('input[id^="reqPegawai"]').each(function(){
		$(this).autocomplete({
			source:function(request, response){
				var id= this.element.attr('id');
				var replaceAnakId= replaceAnak= urlAjax= "";

				if (id.indexOf('reqPegawai') !== -1)
				{
					var element= id.split('reqPegawai');
					var indexId= "reqPegawaiId"+element[1];
					urlAjax= "pegawai_json/auto";
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
								return {desc: element['desc'], id: element['id'], label: element['label']};
							});
							response(array);
						}
					}
				})
			},
			focus: function (event, ui) 
			{ 
				var id= $(this).attr('id');
				if (id.indexOf('reqPegawai') !== -1)
				{
					var element= id.split('reqPegawai');
					var indexId= "reqPegawaiId"+element[1];
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
</script>