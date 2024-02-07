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
$reqKategori= $this->input->get("reqKategori");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqPensiunRiwayatAkhirTmt= $this->input->get("reqPensiunRiwayatAkhirTmt");
$reqPensiunTmtTahun= $this->input->get("reqPensiunTmtTahun");

$arrkhususkategori= array("dini", "udzur");

$vfpeg= new globalfilepegawai();
$infotahunmundur= $vfpeg->gettahunmundur($reqPensiunTmtTahun);
// echo $infotahunmundur;exit;

$set= new JenisKawin();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrjeniskawin=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrjeniskawin, $arrdata);
}
// print_r($arrjeniskawin);exit;

$set= new Pendidikan();
$set->selectByParams(array());
// echo $set->query;exit;
$arrpendidikan=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("PENDIDIKAN_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrpendidikan, $arrdata);
}
// print_r($arrpendidikan);exit;

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
$set= new SuamiIstri();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrsuamiistri=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("SUAMI_ISTRI_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrsuamiistri, $arrdata);
}
?>
<div class="row">
	<div class="input-field col s12 m12">
		Jika ada data Suami/istri belum ada di formulir usulan, silakan update di riwayat Suami/istri PNS yang bersangkutan. Silakan <a href="javascript:void()" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_suami_istri_monitoring', 'surat_masuk_bkd_add_pegawai_pensiun')">klik disini.</a>
	</div>
</div>
<?
$nomor=1;
$sOrder= " ORDER BY A.TANGGAL_KAWIN ";
$statement= " AND (COALESCE(NULLIF(A.STATUS::TEXT, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqPegawaiId;
$suamiistri= new Pensiun();
$suamiistri->selectByParamsSuamiIstri(array(), -1, -1, $statement, $sOrder);
// echo $suamiistri->query;exit;
while($suamiistri->nextRow())
{
	$reqSuamiIstriId= $suamiistri->getField("SUAMI_ISTRI_ID");
	$reqSuamiIstriNama= $suamiistri->getField("NAMA");
	$reqSuamiIstriTanggalLahir= dateToPageCheck($suamiistri->getField("TANGGAL_LAHIR"));
	$reqSuamiIstriStatusNama= $suamiistri->getField("STATUS_S_I_NAMA");
	$reqSuamiIstriTanggalKawin= dateToPageCheck($suamiistri->getField("TANGGAL_KAWIN"));
	$reqSuamiIstriStatusSi= $suamiistri->getField("STATUS_S_I");
	$reqSuamiIstriTanggalCerai= dateToPageCheck($suamiistri->getField("CERAI_TMT"));
	$reqSuamiIstriTanggalKematian= dateToPageCheck($suamiistri->getField("KEMATIAN_TMT"));

	$reqSuamiIstriStatusHidup= $suamiistri->getField("STATUS_AKTIF");
	$reqSuamiIstriSuratNikah= $suamiistri->getField("SURAT_NIKAH");
	$reqSuamiIstriAktaNikahTanggal= dateToPageCheck($suamiistri->getField("AKTA_NIKAH_TANGGAL"));

	$reqSuamiIstriCeraiSurat= $suamiistri->getField("CERAI_SURAT");
	$reqSuamiIstriCeraiTanggal= dateToPageCheck($suamiistri->getField("CERAI_TANGGAL"));
	$reqSuamiIstriCeraiTmt= dateToPageCheck($suamiistri->getField("CERAI_TMT"));
	$reqSuamiIstriKematianNo= $suamiistri->getField('KEMATIAN_NO');
	$reqSuamiIstriKematianSurat= $suamiistri->getField("KEMATIAN_SURAT");
	$reqSuamiIstriKematianTanggal= dateToPageCheck($suamiistri->getField("KEMATIAN_TANGGAL"));
	$reqSuamiIstriKematianTmt= dateToPageCheck($suamiistri->getField("KEMATIAN_TMT"));
	$reqSuamiIstriTanggalMeninggal= dateToPageCheck($suamiistri->getField("TANGGAL_MENINGGAL"));

	$reqSuamiIstriAktaNikahNo= $suamiistri->getField('AKTA_NIKAH_NO');
	$reqSuamiIstriAktaNikahTanggal= dateToPageCheck($suamiistri->getField('AKTA_NIKAH_TANGGAL'));
	$reqSuamiIstriNikahTanggal= dateToPageCheck($suamiistri->getField('NIKAH_TANGGAL'));
	$reqSuamiIstriAktaCeraiNo= $suamiistri->getField('AKTA_CERAI_NO');
	$reqSuamiIstriAktaCeraiTanggal= dateToPageCheck($suamiistri->getField('AKTA_CERAI_TANGGAL'));
	$reqSuamiIstriCeraiTanggal= dateToPageCheck($suamiistri->getField('CERAI_TANGGAL'));
?>
	<div class="row">
		<div class="input-field col s12 m3">
			<label for="reqSuamiIstriNama<?=$nomor?>">Nama Suami Istri</label>
			<input placeholder="" type="text" id="reqSuamiIstriNama<?=$nomor?>" value="<?=$reqSuamiIstriNama?>" disabled />
			<input type="hidden" name="reqSuamiIstriId[]" value="<?=$reqSuamiIstriId?>" />
		</div>
		<div class="input-field col s12 m1">
			<label for="reqSuamiIstriTanggalLahir<?=$nomor?>">Tanggal Lahir</label>
			<input placeholder="" readonly type="text" class="color-disb" name="reqSuamiIstriTanggalLahir[]" id="reqSuamiIstriTanggalLahir<?=$nomor?>" value="<?=$reqSuamiIstriTanggalLahir?> " />
		</div>
		<div class="input-field col s12 m2">
			<select <?=$disabled?> name="reqSuamiIstriStatusHidup[]" id="reqSuamiIstriStatusHidup<?=$nomor?>">
				<option value="1" <? if($reqSuamiIstriStatusHidup == 1) echo 'selected';?>>Hidup</option>
				<option value="2" <? if($reqSuamiIstriStatusHidup == 2) echo 'selected';?>>Wafat</option>
			</select>
			<label for="reqSuamiIstriStatusHidup<?=$nomor?>">Status Hidup</label>
		</div>

		<?
		$reqSuamiIstriStatusHidupDisplay= "none";
		$reqSuamiIstriStatusHidupValidasi= "";
		if($reqSuamiIstriStatusHidup == "2")
		{
			$reqSuamiIstriStatusHidupDisplay= "";
			$reqSuamiIstriStatusHidupValidasi= "required";
		}
		?>
		<div style="display: <?=$reqSuamiIstriStatusHidupDisplay?>" class="input-field col s12 m2 reqSuamiIstriLabelTanggalMeninggal<?=$nomor?>">
			<label for="reqSuamiIstriKematianNo<?=$nomor?>">Surat Ket. Kematian</label>
			<input <?=$disabled?> <?=$reqSuamiIstriStatusHidupValidasi?> placeholder="" type="text" class="easyui-validatebox" name="reqSuamiIstriKematianNo[]" id="reqSuamiIstriKematianNo<?=$nomor?>" <?=$read?> value="<?=$reqSuamiIstriKematianNo?>" />
		</div>
		<div style="display: <?=$reqSuamiIstriStatusHidupDisplay?>" class="input-field col s12 m2 reqSuamiIstriLabelTanggalMeninggal<?=$nomor?>">
			<label class="active" for="reqSuamiIstriKematianTanggal<?=$nomor?>">Tanggal Surat Kematian</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> <?=$reqSuamiIstriStatusHidupValidasi?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriKematianTanggal[]" id="reqSuamiIstriKematianTanggal<?=$nomor?>" value="<?=$reqSuamiIstriKematianTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriKematianTanggal<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriKematianTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>

		<div style="display: <?=$reqSuamiIstriStatusHidupDisplay?>" class="input-field col s12 m2 reqSuamiIstriLabelTanggalMeninggal<?=$nomor?>">
			<label class="active" for="reqSuamiIstriTanggalMeninggal<?=$nomor?>">Tanggal Meninggal</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> <?=$reqSuamiIstriStatusHidupValidasi?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriTanggalMeninggal[]" id="reqSuamiIstriTanggalMeninggal<?=$nomor?>" value="<?=$reqSuamiIstriTanggalMeninggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriTanggalMeninggal<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriTanggalMeninggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?
	$suamiistrilabelceraidisplay= "none";
	$suamiistrilabelcerairequired= "";
	if($reqSuamiIstriStatusSi == "2")
	{
		$suamiistrilabelceraidisplay= "";
		$suamiistrilabelcerairequired= "required";
	}
	?>
	<div class="row">
		<div class="input-field col s12 m2">
			<select <?=$disabled?> name="reqSuamiIstriStatusSi[]" id="reqSuamiIstriStatusSi<?=$nomor?>">
				<option value=""></option>
				<?
                foreach ($arrjeniskawin as $key => $value)
                {
                  $optionid= $value["ID"];
                  $optiontext= $value["TEXT"];
                  $optionselected= "";

                  if($optionid == 4)
                  	continue;

                  if($reqSuamiIstriStatusSi == $optionid)
                    $optionselected= "selected";
                ?>
                  <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                <?
                }
                ?>
			</select>
			<label for="reqSuamiIstriStatusSi<?=$nomor?>">Status Pernikahan</label>
		</div>
		<div class="input-field col s12 m2">
			<label for="reqSuamiIstriSuratNikah<?=$nomor?>">Surat Nikah</label>
			<input <?=$disabled?> placeholder="" type="text" class="xcolor-disb" name="reqSuamiIstriSuratNikah[]" id="reqSuamiIstriSuratNikah<?=$nomor?>" value="<?=$reqSuamiIstriSuratNikah?> " />
		</div>
		<div class="input-field col s12 m2">
			<label class="active" for="reqSuamiIstriTanggalKawin<?=$nomor?>">Tanggal Nikah</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> placeholder="" class="xcolor-disb easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriTanggalKawin[]" id="reqSuamiIstriTanggalKawin<?=$nomor?>" value="<?=$reqSuamiIstriTanggalKawin?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriTanggalKawin<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriTanggalKawin<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>
		<div class="input-field col s12 m2">
			<label class="active" for="reqSuamiIstriAktaNikahTanggal<?=$nomor?>">Tanggal Akta Nikah</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> required placeholder="" class="xcolor-disb easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriAktaNikahTanggal[]" id="reqSuamiIstriAktaNikahTanggal<?=$nomor?>" value="<?=$reqSuamiIstriAktaNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriAktaNikahTanggal<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriAktaNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div style="display: none;" class="input-field col s12 m3 suamiistrilabelnikah<?=$nomor?>">
			<label for="reqSuamiIstriAktaNikahNo<?=$nomor?>">No Akta Nikah</label>
			<input <?=$disabled?> placeholder="" type="text" class="easyui-validatebox" name="reqSuamiIstriAktaNikahNo[]" id="reqSuamiIstriAktaNikahNo<?=$nomor?>" <?=$read?> value="<?=$reqSuamiIstriAktaNikahNo?>" />
		</div>

		<div style="display: none;" class="input-field col s12 m3 suamiistrilabelnikah<?=$nomor?>">
		</div>

		<div style="display: none;" class="input-field col s12 m2 suamiistrilabelnikah<?=$nomor?>">
			<label class="active" for="reqSuamiIstriNikahTanggal<?=$nomor?>">Tanggal Nikah</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriNikahTanggal[]" id="reqSuamiIstriNikahTanggal<?=$nomor?>" value="<?=$reqSuamiIstriNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriNikahTanggal<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>

		<div style="display: <?=$suamiistrilabelceraidisplay?>" class="input-field col s12 m4 suamiistrilabelcerai<?=$nomor?>">
			<label for="reqSuamiIstriAktaCeraiNo<?=$nomor?>">Surat Pengadilan / Cerai</label>
			<input <?=$disabled?> <?=$suamiistrilabelcerairequired?> placeholder="" type="text" class="easyui-validatebox" name="reqSuamiIstriAktaCeraiNo[]" id="reqSuamiIstriAktaCeraiNo<?=$nomor?>" <?=$read?> value="<?=$reqSuamiIstriAktaCeraiNo?>" />
		</div>
		<div style="display: <?=$suamiistrilabelceraidisplay?>" class="input-field col s12 m2 suamiistrilabelcerai<?=$nomor?>">
			<label class="active" for="reqSuamiIstriAktaCeraiTanggal<?=$nomor?>">Tanggal Akta Cerai</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> <?=$suamiistrilabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriAktaCeraiTanggal[]" id="reqSuamiIstriAktaCeraiTanggal<?=$nomor?>" value="<?=$reqSuamiIstriAktaCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriAktaCeraiTanggal<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriAktaCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>
		<div style="display: <?=$suamiistrilabelceraidisplay?>" class="input-field col s12 m2 suamiistrilabelcerai<?=$nomor?>">
			<label class="active" for="reqSuamiIstriCeraiTanggal">Tanggal Cerai</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input <?=$disabled?> <?=$suamiistrilabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriCeraiTanggal[]" id="reqSuamiIstriCeraiTanggal<?=$nomor?>" value="<?=$reqSuamiIstriCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriCeraiTanggal<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqSuamiIstriCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
<?
	$nomor++;
}
?>

<div class="row">
	<div class="input-field col s12 m12">
		Jika ada data anak belum ada di formulir usulan, silakan update di riwayat anak PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_anak_monitoring', 'surat_masuk_bkd_add_pegawai_pensiun')">klik disini.</a>
	</div>
</div>
<?
$statement= " AND A.NOMOR <= 5";
$set= new Pensiun();
$set->selectByParamsAnak($reqPegawaiId, $reqKategori, $statement);
// echo $set->query;exit;
$nomor=1;
while($set->nextRow())
{
	$reqAnakId= $set->getField("ANAK_ID");
	$reqAnakNama= $set->getField("NAMA");
	$reqAnakUsia= $set->getField("ANAK_USIA");
	$reqAnakTanggalLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
	$reqAnakStatusNama= $set->getField("ANAK_STATUS_NAMA");
	$reqSuamiIstriNama= $set->getField("SUAMI_ISTRI_NAMA");

	$reqAnakSuamiIstriId= $set->getField("SUAMI_ISTRI_ID");
	$reqAnakStatusKeluarga= $set->getField("STATUS_KELUARGA");
	$reqAnakPendidikanId= $set->getField("PENDIDIKAN_ID");
	$reqAnakStatusLulus= $set->getField("STATUS_LULUS");
	$reqAnakStatusBekerja= $set->getField("STATUS_BEKERJA");
	$reqAnakStatusAktif= $set->getField("STATUS_AKTIF");
	$reqAnakJenisKawinId= $set->getField("JENIS_KAWIN_ID");
	$reqAnakKematianNo= $set->getField('KEMATIAN_NO');
	$reqAnakKematianTanggal= dateToPageCheck($set->getField('KEMATIAN_TANGGAL'));
	$reqAnakTanggalMeninggal= dateToPageCheck($set->getField('TANGGAL_MENINGGAL'));

	$reqAnakAktaNikahNo= $set->getField('AKTA_NIKAH_NO');
	$reqAnakAktaNikahTanggal= dateToPageCheck($set->getField('AKTA_NIKAH_TANGGAL'));
	$reqAnakNikahTanggal= dateToPageCheck($set->getField('NIKAH_TANGGAL'));
	$reqAnakAktaCeraiNo= $set->getField('AKTA_CERAI_NO');
	$reqAnakAktaCeraiTanggal= dateToPageCheck($set->getField('AKTA_CERAI_TANGGAL'));
	$reqAnakCeraiTanggal= dateToPageCheck($set->getField('CERAI_TANGGAL'));
?>
	<div class="row">
		<div class="input-field col s12 m3">
			<label for="reqAnakNama<?=$nomor?>">Anak <?=$nomor?></label>
			<input placeholder="" type="text" id="reqAnakNama<?=$nomor?>" value="<?=$reqAnakNama?>" disabled />
			<input type="hidden" name="reqAnakId[]" value="<?=$reqAnakId?>" />
		</div>
		<div class="input-field col s12 m1">
			<label for="reqAnakTanggalLahir<?=$nomor?>">Tanggal Lahir</label>
			<input placeholder="" readonly type="text" class="color-disb" name="reqAnakTanggalLahir[]" id="reqAnakTanggalLahir<?=$nomor?>" value="<?=$reqAnakTanggalLahir?> " />
			<!-- <label class="active" for="reqAnakTanggalLahir<?=$nomor?>">Tanggal Lahir</label>
			<table>
				<tr> 
					<td style="padding: 0px;">
						<input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakTanggalLahir[]" id="reqAnakTanggalLahir<?=$nomor?>" value="<?=$reqAnakTanggalLahir?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakTanggalLahir<?=$nomor?>');" />
					</td>
					<td style="padding: 0px;">
						<label class="input-group-btn" for="reqAnakTanggalLahir<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
							<span class="mdi-notification-event-note"></span>
						</label>
					</td>
				</tr>
			</table> -->
		</div>
		<div class="input-field col s12 m1">
        	<select <?=$disabled?> name="reqAnakStatusAktif[]" id="reqAnakStatusAktif<?=$nomor?>">
        		<option value="1" <? if($reqAnakStatusAktif == 1) echo 'selected';?>>Hidup</option>
        		<option value="2" <? if($reqAnakStatusAktif == 2) echo 'selected';?>>Wafat</option>
        	</select>
        	<label for="reqAnakStatusAktif<?=$nomor?>">Status Hidup</label>
        </div>
		<div class="input-field col s12 m2">
			<select <?=$disabled?> name="reqAnakStatusKeluarga[]" id="reqAnakStatusKeluarga<?=$nomor?>">
				<option value="" <? if($reqAnakStatusKeluarga == "") echo 'selected';?>>Belum diisi</option>
				<option value="1" <? if($reqAnakStatusKeluarga == 1) echo 'selected';?>>Kandung</option>
				<option value="2" <? if($reqAnakStatusKeluarga == 2) echo 'selected';?>>Tiri</option>
				<option value="3" <? if($reqAnakStatusKeluarga == 3) echo 'selected';?>>Angkat</option>
			</select>
			<label for="reqAnakStatusKeluarga<?=$nomor?>">Status Keluarga</label>
		</div>
		<div class="input-field col s12 m1">
			<label for="reqAnakUsia<?=$nomor?>">Usia</label>
			<input placeholder="" readonly type="text" class="color-disb" id="reqAnakUsia<?=$nomor?>" value="<?=$reqAnakUsia?> " />
		</div>
		<div class="input-field col s12 m3">
			<select <?=$disabled?> rexxquired class="xxxeasyui-validatebox" name="reqAnakSuamiIstriId[]" id="reqAnakSuamiIstriId<?=$nomor?>">
              <option value="" selected></option>
              <?
              foreach ($arrsuamiistri as $key => $value)
              {
                $optionid= $value["ID"];
                $optiontext= $value["TEXT"];
                $optionselected= "";
                if($reqAnakSuamiIstriId == $optionid)
                  $optionselected= "selected";
              ?>
                <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
              <?
              }
              ?>
            </select>
            <label for="reqAnakSuamiIstriId<?=$nomor?>">Nama Orang tua</label>
		</div>
	</div>

	<div class="row">
		<div class="input-field col s12 m3">
			<select <?=$disabled?> rexxquired class="xxxeasyui-validatebox" name="reqAnakPendidikanId[]" id="reqAnakPendidikanId<?=$nomor?>">
              <option value="" selected></option>
              <?
              foreach ($arrpendidikan as $key => $value)
              {
                $optionid= $value["ID"];
                $optiontext= $value["TEXT"];
                $optionselected= "";
                if($reqAnakPendidikanId == $optionid)
                  $optionselected= "selected";
              ?>
                <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
              <?
              }
              ?>
            </select>
            <label for="reqAnakPendidikanId<?=$nomor?>">Pendidikan terakhir</label>
        </div>
        <div class="input-field col s12 m2" id="labelstatuslulus<?=$nomor?>">
        	<select <?=$disabled?> name="reqAnakStatusLulus[]" id="reqAnakStatusLulus<?=$nomor?>">
        		<option value="1" <? if($reqAnakStatusLulus == 1) echo 'selected';?>>Ya</option>
        		<option value="" <? if($reqAnakStatusLulus == "") echo 'selected';?>>Belum</option>
        	</select>
        	<label for="reqAnakStatusLulus<?=$nomor?>">Sudah Lulus?</label>
        </div>
        <div class="input-field col s12 m2">
        	<select <?=$disabled?> name="reqAnakStatusBekerja[]" id="reqAnakStatusBekerja<?=$nomor?>">
        		<option value="1" <? if($reqAnakStatusBekerja == 1) echo 'selected';?>>Sudah</option>
        		<option value="" <? if($reqAnakStatusBekerja == "") echo 'selected';?>>Belum</option>
        	</select>
        	<label for="reqAnakStatusBekerja<?=$nomor?>">Bekerja?</label>
        </div>
        <div class="input-field col s12 m2">
        	<select <?=$disabled?> rexxquired class="xxxeasyui-validatebox" name="reqAnakJenisKawinId[]" id="reqAnakJenisKawinId<?=$nomor?>">
        		<option value=""></option>
        		<?
                foreach ($arrjeniskawin as $key => $value)
                {
                  $optionid= $value["ID"];
                  $optiontext= $value["TEXT"];
                  $optionselected= "";
                  if($reqAnakJenisKawinId == $optionid)
                    $optionselected= "selected";
                ?>
                  <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                <?
                }
                ?>
        	</select>
        	<label for="reqAnakJenisKawinId<?=$nomor?>">Status Pernikahan</label>
        </div>
    </div>
    <?
    $anaklabelmeninggaldisplay= "none";
	$anaklabelmeninggalrequired= "";
	// if($reqAnakStatusAktif == "2")
	// {
	// 	$anaklabelmeninggaldisplay= "";
	// 	$anaklabelmeninggalrequired= "required";
	// }
    ?>
    <div class="row">
    	<div style="display: <?=$anaklabelmeninggaldisplay?>" class="input-field col s12 m3 reqLabelTanggalMeninggal<?=$nomor?>">
    		<label for="reqAnakKematianNo<?=$nomor?>">Surat Keterangan Kematian</label>
    		<input <?=$disabled?> <?=$anaklabelmeninggalrequired?> placeholder="" type="text" class="easyui-validatebox" name="reqAnakKematianNo[]" id="reqAnakKematianNo<?=$nomor?>" <?=$read?> value="<?=$reqAnakKematianNo?>" />
    	</div>
    	<div style="display: <?=$anaklabelmeninggaldisplay?>" class="input-field col s12 m3 reqLabelTanggalMeninggal<?=$nomor?>">
    		<label class="active" for="reqAnakKematianTanggal<?=$nomor?>">Tanggal Surat Kematian</label>
    		<table>
    			<tr> 
    				<td style="padding: 0px;">
    					<input <?=$disabled?> <?=$anaklabelmeninggalrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakKematianTanggal[]" id="reqAnakKematianTanggal<?=$nomor?>" value="<?=$reqAnakKematianTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakKematianTanggal<?=$nomor?>');" />
    				</td>
    				<td style="padding: 0px;">
    					<label class="input-group-btn" for="reqAnakKematianTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
    						<span class="mdi-notification-event-note"></span>
    					</label>
    				</td>
    			</tr>
    		</table>
    	</div>
    	<div style="display: <?=$anaklabelmeninggaldisplay?>" class="input-field col s12 m3 reqLabelTanggalMeninggal<?=$nomor?>">
    		<label class="active" for="reqAnakTanggalMeninggal<?=$nomor?>">Tanggal Meninggal</label>
    		<table>
    			<tr> 
    				<td style="padding: 0px;">
    					<input <?=$disabled?> <?=$anaklabelmeninggalrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakTanggalMeninggal[]" id="reqAnakTanggalMeninggal<?=$nomor?>" value="<?=$reqAnakTanggalMeninggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakTanggalMeninggal<?=$nomor?>');" />
    				</td>
    				<td style="padding: 0px;">
    					<label class="input-group-btn" for="reqAnakTanggalMeninggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
    						<span class="mdi-notification-event-note"></span>
    					</label>
    				</td>
    			</tr>
    		</table>
    	</div>
    </div>
    <?
	$anaklabelnikahdisplay= $anaklabelceraidisplay= "none";
	$anaklabelnikahrequired= $anaklabelcerairequired= "";
	if($reqAnakStatusAktif == "2"){}
	else
	{
		if($reqAnakJenisKawinId == "1")
		{
			$anaklabelnikahdisplay= "";
			$anaklabelnikahrequired= "required";
		}
		else if($reqAnakJenisKawinId == "2" || $reqAnakJenisKawinId == "3")
		{
			$anaklabelnikahdisplay= "";
			$anaklabelnikahrequired= "required";

			$anaklabelceraidisplay= "";
			$anaklabelcerairequired= "required";
		}
	}

    ?>
    <div class="row">
    	<div style="display: <?=$anaklabelnikahdisplay?>" class="input-field col s12 m3 anaklabelnikah<?=$nomor?>">
    		<label for="reqAnakAktaNikahNo<?=$nomor?>">No Akta Nikah</label>
    		<input <?=$disabled?> <?=$anaklabelnikahrequired?> placeholder="" type="text" class="easyui-validatebox" name="reqAnakAktaNikahNo[]" id="reqAnakAktaNikahNo<?=$nomor?>" <?=$read?> value="<?=$reqAnakAktaNikahNo?>" />
    	</div>
    	<div style="display: <?=$anaklabelnikahdisplay?>" class="input-field col s12 m3 anaklabelnikah<?=$nomor?>">
    		<label class="active" for="reqAnakAktaNikahTanggal<?=$nomor?>">Tanggal Akta Nikah</label>
    		<table>
    			<tr> 
    				<td style="padding: 0px;">
    					<input <?=$disabled?> <?=$anaklabelnikahrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakAktaNikahTanggal[]" id="reqAnakAktaNikahTanggal<?=$nomor?>" value="<?=$reqAnakAktaNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakAktaNikahTanggal<?=$nomor?>');" />
    				</td>
    				<td style="padding: 0px;">
    					<label class="input-group-btn" for="reqAnakAktaNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
    						<span class="mdi-notification-event-note"></span>
    					</label>
    				</td>
    			</tr>
    		</table>
    	</div>
    	<div style="display: <?=$anaklabelnikahdisplay?>" class="input-field col s12 m3 anaklabelnikah<?=$nomor?>">
    		<label class="active" for="reqAnakNikahTanggal<?=$nomor?>">Tanggal Nikah</label>
    		<table>
    			<tr> 
    				<td style="padding: 0px;">
    					<input <?=$disabled?> <?=$anaklabelnikahrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakNikahTanggal[]" id="reqAnakNikahTanggal<?=$nomor?>" value="<?=$reqAnakNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakNikahTanggal<?=$nomor?>');" />
    				</td>
    				<td style="padding: 0px;">
    					<label class="input-group-btn" for="reqAnakNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
    						<span class="mdi-notification-event-note"></span>
    					</label>
    				</td>
    			</tr>
    		</table>
    	</div>
    	<div style="display: <?=$anaklabelceraidisplay?>" class="input-field col s12 m3 anaklabelcerai<?=$nomor?>">
    		<label for="reqAnakAktaCeraiNo<?=$nomor?>">Surat Pengadilan / Cerai</label>
    		<input <?=$disabled?> <?=$anaklabelcerairequired?> placeholder="" type="text" class="easyui-validatebox" name="reqAnakAktaCeraiNo[]" id="reqAnakAktaCeraiNo<?=$nomor?>" <?=$read?> value="<?=$reqAnakAktaCeraiNo?>" />
    	</div>
    	<div style="display: <?=$anaklabelceraidisplay?>" class="input-field col s12 m3 anaklabelcerai<?=$nomor?>">
    		<label class="active" for="reqAnakAktaCeraiTanggal<?=$nomor?>">Tanggal Akta Cerai</label>
    		<table>
    			<tr> 
    				<td style="padding: 0px;">
    					<input <?=$disabled?> <?=$anaklabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakAktaCeraiTanggal[]" id="reqAnakAktaCeraiTanggal<?=$nomor?>" value="<?=$reqAnakAktaCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakAktaCeraiTanggal<?=$nomor?>');" />
    				</td>
    				<td style="padding: 0px;">
    					<label class="input-group-btn" for="reqAnakAktaCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
    						<span class="mdi-notification-event-note"></span>
    					</label>
    				</td>
    			</tr>
    		</table>
    	</div>
    	<div style="display: <?=$anaklabelceraidisplay?>" class="input-field col s12 m3 anaklabelcerai<?=$nomor?>">
    		<label class="active" for="reqAnakCeraiTanggal<?=$nomor?>">Tanggal Cerai</label>
    		<table>
    			<tr>
    				<td style="padding: 0px;">
    					<input <?=$disabled?> <?=$anaklabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakCeraiTanggal[]" id="reqAnakCeraiTanggal<?=$nomor?>" value="<?=$reqAnakCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakCeraiTanggal<?=$nomor?>');" />
    				</td>
    				<td style="padding: 0px;">
    					<label class="input-group-btn" for="reqAnakCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
    						<span class="mdi-notification-event-note"></span>
    					</label>
    				</td>
    			</tr>
    		</table>
    	</div>
    </div>
<?
$nomor++;
}
?>

<div class="row">
	<div class="input-field col s12 m12">
		Jika ada data penilaian skp/ppk belum ada di formulir usulan, silakan update di riwayat penilaian skp/ppk PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_skp_monitoring', 'surat_masuk_bkd_add_pegawai_pensiun')">klik disini.</a>
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

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('select').material_select();

	// untuk format date baru
	$('.formattanggalnew').datepicker({
		format: "dd-mm-yyyy"
	});

	$('#reqTmtPensiun').keyup(function() {
    	setpensiuntmt();
    });

    $("#reqTmtPensiun").datepicker({
    	format: "dd-mm-yyyy",
    	onSelect: function (date, datepicker) {
    		$(this).change();
    	}
    }).on('hide', function(e) {
		setpensiuntmt();
	});

    $("#reqTmtPensiun").change(function(){
    	setpensiuntmt();
    });

});

/*$('[id^="reqAnakSuamiIstriId"],[id^="reqAnakPendidikanId"],[id^="reqAnakJenisKawinId"]').change(function() {
	infoval= $(this).val();
	infoid= $(this).attr('id');
	removevalidateid(infoval, infoid)
});

function removevalidateid(infoval, infoid)
{
	if(infoval == "")
	{
		$("#"+infoid).validatebox({required: true});
	}
	else
	{
		$("#"+infoid).validatebox({required: false});
		$("#"+infoid).removeClass('validatebox-invalid');
	}
	$("#"+infoid).material_select();
}*/

$('[id^="reqSuamiIstriStatusHidup"]').change(function() { 
	infoid= $(this).attr('id');
	infoid= infoid.replace("reqSuamiIstriStatusHidup", "");

	setsuamiistristatusaktif("data", infoid);
});

function setsuamiistristatusaktif(infomode, infoid)
{
	var reqStatusHidup= $("#reqSuamiIstriStatusHidup"+infoid).val();
	$(".reqSuamiIstriLabelTanggalMeninggal"+infoid).hide();

	if(reqStatusHidup == "2")
	{
	  $("#reqSuamiIstriKematianTanggal"+infoid+", #reqSuamiIstriKematianNo"+infoid+", #reqSuamiIstriTanggalMeninggal"+infoid).validatebox({required: true});
	  $(".reqSuamiIstriLabelTanggalMeninggal"+infoid).show();
	}
	else
	{
	  $("#reqSuamiIstriKematianTanggal"+infoid+", #reqSuamiIstriKematianNo"+infoid+", #reqSuamiIstriTanggalMeninggal"+infoid).validatebox({required: false});
	  $("#reqSuamiIstriKematianTanggal"+infoid+", #reqSuamiIstriKematianNo"+infoid+", #reqSuamiIstriTanggalMeninggal"+infoid).removeClass('validatebox-invalid');
	  $("#reqSuamiIstriKematianTanggal"+infoid+", #reqSuamiIstriKematianNo"+infoid+", #reqSuamiIstriTanggalMeninggal"+infoid).val("");
	}

	setsuamiistrijeniskawinid(infomode, infoid);
}

$('[id^="reqSuamiIstriStatusSi"]').change(function() { 
	infoid= $(this).attr('id');
	infoid= infoid.replace("reqSuamiIstriStatusSi", "");
	setsuamiistrijeniskawinid("data", infoid);
});

function setsuamiistrijeniskawinid(infomode, infoid)
{
	reqStatusHidup= $("#reqSuamiIstriStatusHidup"+infoid).val();
	vinfodata= $("#reqSuamiIstriStatusSi"+infoid).val();

	if(infomode == ""){}
    else
    {
      $("#reqSuamiIstriAktaNikahNo"+infoid+", #reqxxxSuamiIstriAktaNikahTanggal"+infoid+", #reqSuamiIstriNikahTanggal"+infoid+", #reqSuamiIstriAktaCeraiNo"+infoid+", #reqSuamiIstriAktaCeraiTanggal"+infoid+", #reqSuamiIstriCeraiTanggal"+infoid).val("");
    }

	$("#reqSuamiIstriAktaNikahNo"+infoid+", #reqSuamiIstriAktaNikahTanggal"+infoid+", #reqSuamiIstriNikahTanggal"+infoid+", #reqSuamiIstriAktaCeraiNo"+infoid+", #reqSuamiIstriAktaCeraiTanggal"+infoid+", #reqSuamiIstriCeraiTanggal"+infoid).validatebox({reqSuamiIstriuired: false});
    $("#reqSuamiIstriAktaNikahNo"+infoid+", #reqSuamiIstriAktaNikahTanggal"+infoid+", #reqSuamiIstriNikahTanggal"+infoid+", #reqSuamiIstriAktaCeraiNo"+infoid+", #reqSuamiIstriAktaCeraiTanggal"+infoid+", #reqSuamiIstriCeraiTanggal"+infoid).removeClass('validatebox-invalid');

    $(".suamiistrilabelnikah"+infoid+", .suamiistrilabelcerai"+infoid).hide();
    if(reqStatusHidup == "2")
    {
    	$(".infohidup"+infoid).hide();
    }
    else
    {
		$(".infohidup"+infoid).show();
		$(".suamiistrilabelnikah"+infoid+", .suamiistrilabelcerai"+infoid).hide();
		if(vinfodata == "1")
		{
		}
		else if(vinfodata == "2")
		{
	        $("#reqSuamiIstriAktaCeraiNo"+infoid+", #reqSuamiIstriAktaCeraiTanggal"+infoid+", #reqSuamiIstriCeraiTanggal"+infoid).validatebox({required: true});
	        $(".suamiistrilabelcerai"+infoid).show();
	    }
    }
}

$('[id^="reqAnakStatusAktif"]').change(function() { 
	infoid= $(this).attr('id');
	infoid= infoid.replace("reqAnakStatusAktif", "");
	setanakstatusaktif("data", infoid);
});

function setanakstatusaktif(infomode, infoid)
{
	var reqStatusAktif= $("#reqAnakStatusAktif"+infoid).val();
	$(".reqLabelTanggalMeninggal"+infoid).hide();

	if(reqStatusAktif == "2")
	{
		// $("#reqAnakKematianTanggal"+infoid+", #reqAnakKematianNo"+infoid+", #reqAnakTanggalMeninggal"+infoid).validatebox({required: true});
		// $(".reqLabelTanggalMeninggal"+infoid).show();
	}
	else
	{
		$("#reqAnakKematianTanggal"+infoid+", #reqAnakKematianNo"+infoid+", #reqAnakTanggalMeninggal"+infoid).validatebox({required: false});
		$("#reqAnakKematianTanggal"+infoid+", #reqAnakKematianNo"+infoid+", #reqAnakTanggalMeninggal"+infoid).removeClass('validatebox-invalid');
		$("#reqAnakKematianTanggal"+infoid+", #reqAnakKematianNo"+infoid+", #reqAnakTanggalMeninggal"+infoid).val("");
	}

	setanakjeniskawinid(infomode, infoid);
}

$('[id^="reqAnakJenisKawinId"]').change(function() { 
	infoid= $(this).attr('id');
	infoid= infoid.replace("reqAnakJenisKawinId", "");
	setanakjeniskawinid("data", infoid);
});

function setanakjeniskawinid(infomode, infoid)
{
	reqStatusAktif= $("#reqAnakStatusAktif"+infoid).val();
	vinfodata= $("#reqAnakJenisKawinId"+infoid).val();

	if(infomode == ""){}
	else
	{
		$("#reqAnakAktaNikahNo"+infoid+", #reqxxxAnakAktaNikahTanggal"+infoid+", #reqAnakNikahTanggal"+infoid+", #reqAnakAktaCeraiNo"+infoid+", #reqAnakAktaCeraiTanggal"+infoid+", #reqAnakCeraiTanggal"+infoid).val("");
	}

    $("#reqAnakAktaNikahNo"+infoid+", #reqAnakAktaNikahTanggal"+infoid+", #reqAnakNikahTanggal"+infoid+", #reqAnakAktaCeraiNo"+infoid+", #reqAnakAktaCeraiTanggal"+infoid+", #reqAnakCeraiTanggal"+infoid+"").validatebox({required: false});
    $("#reqAnakAktaNikahNo"+infoid+", #reqAnakAktaNikahTanggal"+infoid+", #reqAnakNikahTanggal"+infoid+", #reqAnakAktaCeraiNo"+infoid+", #reqAnakAktaCeraiTanggal"+infoid+", #reqAnakCeraiTanggal"+infoid).removeClass('validatebox-invalid');
    
    if(reqStatusAktif == "2")
    {
    	// $(".infohidup"+infoid+"").hide();
    }
    else
    {
    	// $(".infohidup"+infoid+"").show();
    	$(".anaklabelnikah"+infoid+", .anaklabelcerai"+infoid+"").hide();
    	if(vinfodata == "1")
    	{
    		$("#reqAnakAktaNikahNo"+infoid+", #reqAnakAktaNikahTanggal"+infoid+", #reqAnakNikahTanggal"+infoid).validatebox({required: true});
    		$(".anaklabelnikah"+infoid+"").show();
    	}
    	else if(vinfodata == "2" || vinfodata == "3")
    	{
    		$("#reqAnakAktaNikahNo"+infoid+", #reqAnakAktaNikahTanggal"+infoid+", #reqAnakNikahTanggal"+infoid+", #reqAnakAktaCeraiNo"+infoid+", #reqAnakAktaCeraiTanggal"+infoid+", #reqAnakCeraiTanggal"+infoid).validatebox({required: true});
    		$(".anaklabelnikah"+infoid+", .anaklabelcerai"+infoid).show();
    	}
    }

}

/*tanggalsekarang= "<?=$reqPensiunRiwayatAkhirTmt?>";
$('#reqTanggalLahir').keyup(function() {
	infoid= $(this).attr('id');
	infoid= infoid.replace("reqAnakTanggalLahir", "");

	var vold= $('#reqAnakTanggalLahir'+infoid).val();
	var vnew= tanggalsekarang;

	getparamyearoldnew("reqAnakUsia"+infoid, vold, vnew);
});

$('[id^="reqAnakTanggalLahir"]').change(function() { 
	infoid= $(this).attr('id');
	infoid= infoid.replace("reqAnakTanggalLahir", "");

	var vold= $('#reqAnakTanggalLahir'+infoid).val();
	var vnew= tanggalsekarang;

	getparamyearoldnew("reqAnakUsia"+infoid, vold, vnew);
});*/

function getdatedifference(date1, date2)
{
    var daysDiff = Math.ceil((Math.abs(date1 - date2)) / (1000 * 60 * 60 * 24));

    var years = Math.floor(daysDiff / 365.25);
    var remainingDays = Math.floor(daysDiff - (years * 365.25));
    var months = Math.floor((remainingDays / 365.25) * 12);
    var days = Math.ceil(daysDiff - (years * 365.25 + (months / 12 * 365.25)));

    return {
        daysAll: daysDiff,
        years: years,
        months: months,
        days:days
    }
}

function getMonthDifference(startDate, endDate) {
	return (
		endDate.getMonth() -
		startDate.getMonth() +
		12 * (endDate.getFullYear() - startDate.getFullYear())
		);
}

function setchangedate(date) {
	vhari= date.substring(0,2);
	vbulan= date.substring(3,5);
	vtahun= date.substring(6,10);

	vreturn= vtahun+"-"+vbulan+"-"+vhari;
	return vreturn;
}

function setpensiuntmt()
{
	reqTmtPensiun= $('#reqTmtPensiun').val();
	var checkTmtPensiun= moment(reqTmtPensiun , 'DD-MM-YYYY', true).isValid();
	// console.log(checkTmtPensiun);
	if(checkTmtPensiun == true)
	{
		vbulan= reqTmtPensiun.substring(3,5);
		vtahun= reqTmtPensiun.substring(6,10);
		reqTmtPensiun= "01-"+vbulan+"-"+vtahun;
		$('#reqTmtPensiun').val(reqTmtPensiun);

		// reqTmtPensiun= setchangedate(reqTmtPensiun);
		// reqPangkatRiwayatAkhirTmt= setchangedate($('#reqPangkatRiwayatAkhirTmt').val());
		// // console.log(reqPangkatRiwayatAkhirTmt);
		// // console.log(reqTmtPensiun);

		// vgetMonthDifference= getdatedifference(
		// new Date(reqPangkatRiwayatAkhirTmt), new Date(reqTmtPensiun)
		// );
		// console.log(vgetMonthDifference);

		reqKategori= "<?=$reqKategori?>";
		if(reqKategori == "dini" || reqKategori == "udzur")
		{
			vnew= reqTmtPensiun;
			vold= $('#reqPangkatRiwayatAkhirTmt').val();
			yearsmontholdnew= getparamyearsmontholdnew(vold, vnew);
			// console.log(yearsmontholdnew);
			yearsmontholdnew= yearsmontholdnew.split('-');
			vyears= yearsmontholdnew[0];
			vmonth= yearsmontholdnew[1];

			reqPangkatRiwayatAkhirTh= $("#reqPangkatRiwayatAkhirTh").val();
			reqPangkatRiwayatAkhirBl= $("#reqPangkatRiwayatAkhirBl").val();
			// console.log(reqPangkatRiwayatAkhirTh);
			// console.log(vyears);

			caseyears= parseFloat(reqPangkatRiwayatAkhirTh) + parseFloat(vyears);
			casemonth= parseFloat(reqPangkatRiwayatAkhirBl) + parseFloat(vmonth);
			// console.log(caseyears);

			nyear= nmonth= 0;
			// console.log(casemonth);
			// console.log(casemonth % 12);

			nyear= parseFloat(caseyears);
			// console.log(nyear);

			if(parseFloat(casemonth) >= 12)
			{
				nyear= parseFloat(nyear) + 1;
			}
			// console.log(nyear);

			if(parseFloat(casemonth) >= 12)
			{
				nmonth= parseFloat(casemonth) - 12;
			}
			else
			{
				nmonth= parseFloat(casemonth);
			}

			$("#reqPensiunRiwayatAkhirTh").val(nyear);
			$("#reqPensiunRiwayatAkhirBl").val(nmonth);
		}
	}
}
</script>