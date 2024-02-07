<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/Pensiun');
$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('SuamiIstri');
$this->load->model('JenisKawin');
$this->load->model('Pendidikan');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqKpJenis= $this->input->get("reqKpJenis");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqKpTahun= $this->input->get("reqKpTahun");

$vfpeg= new globalfilepegawai();
$infotahunmundur= $vfpeg->gettahunmundur($reqKpTahun);
// echo $infotahunmundur;exit;
?>
<div class="row">
	<div class="input-field col s12 m12">
		Jika ada data penilaian skp/ppk belum ada di formulir usulan, silakan update di riwayat penilaian skp/ppk PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_skp_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
	</div>
</div>

<?
$reqPenilaianSkpJumlah= 0;
$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.TAHUN IN (".$infotahunmundur.")";
$set= new SuratMasukPegawaiCheck();
$set->selectByParamsPenilaianSkp(array(),-1,-1, $statement, "ORDER BY A.TAHUN DESC");
// echo $set->query;exit;
while($set->nextRow())
{
	$reqPenilaianSkpTahun= $set->getField("TAHUN");
	$reqPenilaianSkpHasil= $set->getField("PRESTASI_HASIL");
	$reqPenilaianSkpJumlah++;
?>
<div class="row">
	<div class="input-field col s12 m3">
		<label>Tahun</label>
		<input  placeholder="" type="text" value="<?=$reqPenilaianSkpTahun?>" disabled />
	</div>
	<div class="input-field col s12 m9">
		<label>Penilaian SKP</label>
		<input  placeholder="" type="text" value="<?=$reqPenilaianSkpHasil?>" disabled />
	</div>
</div>
<?
}
?>
<input type="hidden" name="reqPenilaianSkpJumlah" id="reqPenilaianSkpJumlah" value="<?=$reqPenilaianSkpJumlah?>" />

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('select').material_select();
});
</script>