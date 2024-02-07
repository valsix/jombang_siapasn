<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/UsulanSurat');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND A.USULAN_SURAT_ID = ".$reqId."";
$set= new UsulanSurat();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
unset($set);

// $sOrder= " ORDER BY SMP.USULAN_SURAT_URUT ASC";
$sOrder= " ORDER BY COALESCE(SMP.USULAN_SURAT_URUT, 99), SMP.SURAT_MASUK_PEGAWAI_ID";
$statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
$set= new SuratMasukPegawai();
$set->selectByParamsUsulanPegawai(array(), -1, -1, $statement, $sOrder);
// echo $set->query;exit;
?>
<?
while($set->nextRow())
{
    $reqNomorSuratKeluar= $set->getField("NOMOR_SURAT_KELUAR");
    $reqStatusSuratKeluar= $set->getField("STATUS_SURAT_KELUAR");

	$stlePernahTurun= "";
	if($set->getField("STATUS_PERNAH_TURUN") == "1")
	{
		if($set->getField("STATUS_KEMBALI") == "1")
		$stlePernahTurun= "background-color:#F0F;";
		else
		$stlePernahTurun= "background-color:#FB99E1;";
	}
    elseif($set->getField("USULAN_SURAT_URUT") == "")
        $stlePernahTurun= "background-color:#FF99E1;";
?>
<tr>
	<th class="material-font" style=" <?=$stlePernahTurun?>text-align:center"><?=$set->getField("NIP_BARU")?></th>
    <th class="material-font" style=" <?=$stlePernahTurun?>text-align:center"><?=$set->getField("NAMA_LENGKAP")?></th>
    <th class="material-font" style=" <?=$stlePernahTurun?>text-align:center"><?=$set->getField("SATUAN_KERJA_NAMA_BKD")?></th>
    <th class="material-font" style=" <?=$stlePernahTurun?>text-align:center"><?=$set->getField("NOMOR_USUL_BKDPP")?></th>
    <th class="material-font" style=" <?=$stlePernahTurun?>text-align:center"><?=datetimeToPage($set->getField("PROSES_TANGGAL"), "datetime")?></th>
    <th class="material-font" style=" <?=$stlePernahTurun?>text-align:center"><?=$set->getField("PROSES_STATUS")?></th>
    <th class="material-font" style=" <?=$stlePernahTurun?>text-align:center">
        <?
		if($reqStatusKirim == "")
		{
        ?>
        <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="hapusdatabidang('<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')">
          <i class="mdi-action-delete"></i>
        </a>
        <?
		}
        ?>
        <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Kelengkapan" onClick="parent.setload('<?=setlinkbkdverifikasilookup($reqJenis)?>?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')"><i class="mdi-editor-attach-file"></i></a>
        <?
        // if($reqNomorSuratKeluar == ""){}
        // else
        // {
        ?>
        <a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Download File" onClick="downloadfilepegawai('<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')">
          <i class="mdi-file-attachment"></i>
        </a>
        <?
        // }
        ?>
        <?
        if($reqStatusSuratKeluar == "4")
        {
        ?>
        <a href="javascript:void(0)" class="round waves-effect waves-light green white-text" title="Bisa di ambil" onClick="dapatdiambil('<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')">
          <i class="mdi-action-thumb-up"></i>
        </a>
        <!-- <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" onClick="dapatdiambil('<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')">di ambil
            <i class="mdi-content-forward left hide-on-small-only"></i>
        </button> -->
        <?
        }
        ?>
        

    </th>
</tr>
<?
}
?>