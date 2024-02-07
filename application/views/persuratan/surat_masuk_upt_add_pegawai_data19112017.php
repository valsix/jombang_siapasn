<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND SMP.JENIS_ID = ".$reqJenis." AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
$set= new SuratMasukPegawai();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
//echo $set->query;exit;
?>
<?
while($set->nextRow())
{
?>
<tr>
    <th class="material-font" style="text-align:center; width:60px"><img src="images/foto-profile.jpg" style="width:100%;height:100%;" /></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("NAMA_LENGKAP")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"))?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("SATUAN_KERJA_NAMA")?></th>
    <th class="material-font" style="text-align:center"><?=$set->getField("SATUAN_KERJA_INDUK")?></th>
    <th class="material-font" style="text-align:center">
    <a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('surat_masuk_upt_add_pegawai_pendidikan_data?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$set->getField("SURAT_MASUK_PEGAWAI_ID")?>')">
    	<i class="mdi-editor-mode-edit"></i>
    </a>
    </th>
</tr>
<?
}
?>