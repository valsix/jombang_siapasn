<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$checkbox_index = rand();
?>
<div class="col s12 m4">
	<input id="reqGaji<?=$checkbox_index?>" name="reqGaji[]" type="text" OnFocus="FormatAngka('reqGaji<?=$checkbox_index?>')" OnKeyUp="FormatUang('reqGaji<?=$checkbox_index?>')" OnBlur="FormatUang('reqGaji<?=$checkbox_index?>')" value="<?=numberToIna($reqGaji)?>" /> 
	<input type="hidden" name="reqRowDetilId[]" id="reqRowDetilId<?=$checkbox_index?>" />
</div>
<div class="col s12 m4">
	<input name="reqTglAwal[]" id="reqTglAwal<?=$checkbox_index?>" class="easyui-validatebox" data-options="validType:['dateValidPickerMulti[\'reqTglAwal\', \'<?=$checkbox_index?>\']']" maxlength="10" onKeyDown="return format_date(event,'reqTglAwal<?=$checkbox_index?>');" type="text"  />
</div>
<div class="col s12 m4">
	<input name="reqTglAkhir[]" id="reqTglAkhir<?=$checkbox_index?>" class="easyui-validatebox" data-options="validType:['dateValidPickerMulti[\'reqTglAkhir\', \'<?=$checkbox_index?>\']']" maxlength="10" onKeyDown="return format_date(event,'reqTglAkhir<?=$checkbox_index?>');" type="text"  />
</div>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>