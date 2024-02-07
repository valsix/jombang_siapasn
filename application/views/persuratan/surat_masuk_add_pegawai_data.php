<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
$set= new SuratMasukBkd();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
unset($set);

$statement= " AND SMP.JENIS_ID = ".$reqJenis." AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
$set= new SuratMasukPegawai();
$set->selectByParamsUsulanPegawai(array(), -1, -1, $statement);
//echo $set->query;exit;
?>
<?
while($set->nextRow())
{
?>
<tr>
	<th class="material-font" style="text-align:center"><?=$set->getField("NIP_BARU")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("NAMA_LENGKAP")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("SATUAN_KERJA_NAMA_BKD")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("NOMOR_USUL_BKDPP")?></th>
    <th class="material-font" style="text-align:center"><?=datetimeToPage($set->getField("PROSES_TANGGAL"), "datetime")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("PROSES_STATUS")?></th>
    <th class="material-font" style="text-align:center">
    <?php /*?><th class="material-font" style="text-align:center">
    	<?
		if($this->LOGIN_LEVEL >= 30)
		{
		?>
    	<a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('surat_masuk_add_verifikasi?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')"><i class="mdi-editor-mode-edit"></i></a>
		<?
		}
        ?>
    </th><?php */?>
    <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Kelengkapan" onClick="parent.setload('<?=setlinkbkdverifikasilookup($reqJenis)?>?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')"><i class="mdi-editor-attach-file"></i></a>

</tr>
<?
}
?>