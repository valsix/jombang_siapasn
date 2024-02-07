<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$reqCount = $this->input->get("reqCount");
?>
<base href="<?=base_url()?>" />

<!-- <tr>
	<td>
		<input name="reqNIP[]" id="reqNIP<?=$reqCount?>" type="text" class="easyui-validatebox" style="width:100%" value="<?=$tempNIP?>" />
	</td>
	<td>
		<input type="hidden" name="reqRowDetilId[]" id="reqRowDetilId<?=$reqCount?>" value="<?=$tempRowDetilId?>" />
		<input name="reqNama[]" id="reqNama<?=$reqCount?>" type="text" class="easyui-validatebox" style="width:100%" value="<?=$tempNama?>" />
	</td>
</tr> -->

<div class="col s12 m3">
	<input name="reqNamaJabatan[]" id="reqNamaJabatan" type="text"  />
	<input type="hidden" name="reqRowJabatanId[]" id="reqRowJabatanId" />
</div>
<div class="col s12 m3">
	<input name="reqNip[]" id="reqNip" type="text"  />
</div>
<div class="col s12 m3">
	<input name="reqTglAwal[]" id="reqTglAwal" type="text"  />
</div>
<div class="col s12 m3">
	<input name="reqTglAkhir[]" id="reqTglAkhir" type="text"  />
</div>