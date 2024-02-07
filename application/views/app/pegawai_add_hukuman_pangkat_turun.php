<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pangkat');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "1201";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$arrPangkat= [];
$index_data= 0;
$pangkat= new Pangkat();
$pangkat->selectByParams(array());
while($pangkat->nextRow())
{
	$arrPangkat[$index_data]["PANGKAT_ID"] = $pangkat->getField("PANGKAT_ID");
	$arrPangkat[$index_data]["KODE"] = $pangkat->getField("KODE");
	$index_data++;
}
unset($pangkat);
$jumlah_pangkat= $index_data;
?>
<div class="row">
    <div class="input-field col s12 m3">
        <select name="reqKenaikanPangkatTurunKode" id="reqKenaikanPangkatTurunKode" >
        <option value=""></option>
        <? 
        for($i=0; $i<$jumlah_pangkat; $i++)
        {
        ?>
        <option value="<?=$arrPangkat[$i]["PANGKAT_ID"]?>" <? if($reqGolRuang == $arrPangkat[$i]["PANGKAT_ID"]) echo 'selected';?>><?=$arrPangkat[$i]["KODE"]?></option>
        <? 
        }
        ?>
        </select>
        <label for="reqKenaikanPangkatTurunKode">Pangkat Satu Tingkat dibawah per TMT</label>
    </div>
    <div class="input-field col s6 m2">
      <label for="reqKenaikanPangkatTurunTh">MK Tahun</label>
      <input type="hidden" name="reqKenaikanPangkatTurunTh" id="reqKenaikanPangkatTurunTh" value="<?=$reqKenaikanPangkatTurunTh?>" />
      <input placeholder="" type="text" disabled id="reqKenaikanPangkatTurunThLabel" value="<?=$reqKenaikanPangkatTurunTh?> " />
    </div>
    <div class="input-field col s6 m2">
      <label for="reqKenaikanPangkatTurunBl">MK Bulan</label>
      <input type="hidden" name="reqKenaikanPangkatTurunBl" id="reqKenaikanPangkatTurunBl" value="<?=$reqKenaikanPangkatTurunBl?>" />
      <input placeholder="" type="text" disabled id="reqKenaikanPangkatTurunBlLabel" value="<?=$reqKenaikanPangkatTurunBl?> " />
    </div>
    <div class="input-field col s12 m2">
      <label for="reqKenaikanPangkatTurunGajiPokok">Gaji Pokok</label>
      <input type="hidden" name="reqKenaikanPangkatTurunGajiPokok" id="reqKenaikanPangkatTurunGajiPokok" value="<?=numberToIna($reqKenaikanPangkatTurunGajiPokok)?> " />
      <input placeholder="" type="text" disabled id="reqKenaikanPangkatTurunGajiPokokLabel" value="<?=numberToIna($reqKenaikanPangkatTurunGajiPokok)?> " />
    </div>
</div>

<div class="row">
    <div class="input-field col s12 m3">
        <select name="reqKenaikanPangkatKembaliKode" id="reqKenaikanPangkatKembaliKode" >
        <option value=""></option>
        <? 
        for($i=0; $i<$jumlah_pangkat; $i++)
        {
        ?>
        <option value="<?=$arrPangkat[$i]["PANGKAT_ID"]?>" <? if($reqGolRuang == $arrPangkat[$i]["PANGKAT_ID"]) echo 'selected';?>><?=$arrPangkat[$i]["KODE"]?></option>
        <? 
        }
        ?>
        </select>
        <label for="reqKenaikanPangkatKembaliKode">Pangkat Setelah Tgl Akhir</label>
    </div>
    <div class="input-field col s6 m2">
      <label for="reqKenaikanPangkatKembaliTh">MK Tahun</label>
      <input type="hidden" name="reqKenaikanPangkatKembaliTh" id="reqKenaikanPangkatKembaliTh" value="<?=$reqKenaikanPangkatKembaliTh?>" />
      <input placeholder="" type="text" disabled id="reqKenaikanPangkatKembaliThLabel" value="<?=$reqKenaikanPangkatKembaliTh?> " />
    </div>
    <div class="input-field col s6 m2">
      <label for="reqKenaikanPangkatKembaliBl">MK Bulan</label>
      <input type="hidden" name="reqKenaikanPangkatKembaliBl" id="reqKenaikanPangkatKembaliBl" value="<?=$reqKenaikanPangkatKembaliBl?>" />
      <input placeholder="" type="text" disabled id="reqKenaikanPangkatKembaliBlLabel" value="<?=$reqKenaikanPangkatKembaliBl?> " />
    </div>
    <div class="input-field col s12 m2">
      <label for="reqKenaikanPangkatKembaliGajiPokok">Gaji Pokok</label>
      <input type="hidden" name="reqKenaikanPangkatKembaliGajiPokok" id="reqKenaikanPangkatKembaliGajiPokok" value="<?=numberToIna($reqKenaikanPangkatKembaliGajiPokok)?> " />
      <input placeholder="" type="text" disabled id="reqKenaikanPangkatKembaliGajiPokokLabel" value="<?=numberToIna($reqKenaikanPangkatKembaliGajiPokok)?> " />
    </div>
</div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
	function setGajiTurun()
	{
		var reqTglSk= reqPangkatId= reqMasaKerja= "";
		reqTglSk= $("#reqTmtSk").val();
		reqPangkatId= $("#reqKenaikanPangkatTurunKode").val();
		reqMasaKerja= $("#reqKenaikanPangkatTurunTh").val();
		//;reqTanggalAkhir
		urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
		$.ajax({'url': urlAjax,'success': function(data){
		//if(data == ''){}
		//else
		//{
			tempValueGaji= parseFloat(data);
			$("#reqKenaikanPangkatTurunGajiPokok").val(FormatCurrency(tempValueGaji));
			$("#reqKenaikanPangkatTurunGajiPokokLabel").val(FormatCurrency(tempValueGaji));
		//}
		}});
	}
	
	function setGajiKembali()
	{
		var reqTglSk= reqPangkatId= reqMasaKerja= "";
		reqTglSk= $("#reqTanggalAkhir").val();
		reqPangkatId= $("#reqKenaikanPangkatKembaliKode").val();
		reqMasaKerja= $("#reqKenaikanPangkatKembaliTh").val();
		//;
		urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
		$.ajax({'url': urlAjax,'success': function(data){
		//if(data == ''){}
		//else
		//{
			tempValueGaji= parseFloat(data);
			$("#reqKenaikanPangkatKembaliGajiPokok").val(FormatCurrency(tempValueGaji));
			$("#reqKenaikanPangkatKembaliGajiPokokLabel").val(FormatCurrency(tempValueGaji));
		//}
		}});
	}

	$("#reqKenaikanPangkatTurunKode").change(function(){
		setGajiTurun();
	});
	
	$("#reqKenaikanPangkatKembaliKode").change(function(){
		setGajiKembali();
	});
	
    $('select').material_select();
  });
</script>