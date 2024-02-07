<?
$reqIndex= $this->input->get('reqIndex');
$reqAreaCode= $this->input->get('reqAreaCode');
$reqAreaCodeLain= $this->input->get('reqAreaCodeLain');
$x= $reqIndex;
?>
<tr class="md-text">
	<td>
		<input type="hidden" id="reqAreaCode<?=$x?>" name="reqAreaCode[]" value="<?=$arrAreaAbsensi[$x]["AREA_CODE"]?>" />
		<input type="text" id="reqAreaLabelCode<?=$x?>" style="width: 100%" />
	</td>
    <td style="text-align:center">
    	<span>
            <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onclick="$(this).parent().parent().parent().remove();">
            	<img src="images/icon-hapus.png" />
            </a>
        </span>
    </td>
</tr>

<script>
reqAreaCode= "<?=$reqAreaCode?>";
reqAreaCodeLain= "<?=$reqAreaCodeLain?>";
reqIndex= "<?=$reqIndex?>";
$(function(){
	$('input[id^="reqAreaLabelCode"]').each(function(){
	  $(this).autocomplete({
	    source:function(request, response){
	      // $(".preloader-wrapper").show();
	      var id= this.element.attr('id');
	      var replaceAnakId= replaceAnak= urlAjax= "";

	      urlAjax= "siap/satuan_kerja_json/automesin?reqDefault=2&reqPakaiId="+reqAreaCode+"&reqPakaiLainId="+reqAreaCodeLain;

	      $.ajax({
	        url: urlAjax,
	        type: "GET",
	        dataType: "json",
	        data: { term: request.term },
	        success: function(responseData){
	          // $(".preloader-wrapper").hide();

	          // console.log(responseData);
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
	    // focus: function (event, ui) 
	    select: function (event, ui) 
	    { 
	      var id= $(this).attr('id');
	      // var indexId= "reqPegawaiId";
	      var idrow= namapegawai= "";
	      idrow= ui.item.id;
	      namalabel= ui.item.label;

	      if (id.indexOf('reqAreaLabelCode') !== -1)
	      {
	        $("#reqAreaCode"+reqIndex).val(idrow);
	      }
	    },
	    autoFocus: true
	  })
	  .autocomplete( "instance" )._renderItem = function( ul, item ) {
	    return $( "<li>" )
	    .append( "<a>" + item.desc  + "</a>" )
	    .appendTo( ul );
	  }
	  ;
	});
});
</script>