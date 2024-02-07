
<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pegawai');
$this->load->model('Bank');
$this->load->model('Agama');
$this->load->model('SatuanKerja');
$this->load->model('JenisPegawai');
$this->load->model('PegawaiFile');
$this->load->model('base-api/DataCombo');

$tempLoginLevel= $this->LOGIN_LEVEL;

$set= new Pegawai();
$setBank= new Bank();
$setAgama= new Agama();
// $setSatuanKerja= new SatuanKerja();
$setJenisPegawai= new JenisPegawai();

$setBank->selectByParams();
$setAgama->selectByParams();
// $setSatuanKerja->selectByParams();
$setJenisPegawai->selectByParams();

$reqId = $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0101";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);


// kondisi untuk menu
$this->load->library('globalmenusapk');
$vmenusapk= new globalmenusapk();
$arrmenusapk= $vmenusapk->setmenusapk($tempMenuId);
// print_r($arrmenusapk);exit;
$lihatsapk= $arrmenusapk["lihat"];
$kirimsapk= $arrmenusapk["kirim"];
$tariksapk= $arrmenusapk["tarik"];
$syncsapk= $arrmenusapk["sync"];

if($reqId == "")
{
	$reqMode = "insert";
	$reqSatuanKerjaId= -1;
	$reqJenisPegawaiId= 15;
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("A.PEGAWAI_ID"=>$reqId));
	// echo $set->query;exit;
	$set->firstRow();
	$reqStatus= $set->getField("STATUS");
	$reqSatuanKerjaNamaDetil= $set->getField("SATUAN_KERJA_NAMA_DETIL");
	$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID");

	$reqJenisPegawaiId= $set->getField("JENIS_PEGAWAI_ID");
	$reqJenisPegawaiNama= $set->getField("JENIS_PEGAWAI_NAMA");

	$reqBpjs= $set->getField("BPJS");
	$reqBpjsTanggal= dateToPageCheck($set->getField("BPJS_TANGGAL"));
	$reqNpwpTanggal= dateToPageCheck($set->getField("NPWP_TANGGAL"));

	$reqEmailKantor= $set->getField("EMAIL_KANTOR");
	$reqRekeningNama= $set->getField("REKENING_NAMA");
	$reqGajiPokok= $set->getField("GAJI_POKOK");
	$reqTunjangan= $set->getField("TUNJANGAN");
	$reqTunjanganKeluarga= $set->getField("TUNJANGAN_KELUARGA");
	$reqGajiBersih= $set->getField("GAJI_BERSIH");
	$reqStatusMutasi= $set->getField("STATUS_MUTASI");
	$reqTmtMutasi= dateToPageCheck($set->getField("TMT_MUTASI"));
	$reqInstansiSebelum= $set->getField("INSTANSI_SEBELUM");

	$reqJenisPegawai= $set->getField("JENIS_PEGAWAI_ID");
	$reqTipePegawai= $set->getField("TIPE_PEGAWAI_ID");
	$reqStatusPegawai= $set->getField("STATUS_PEGAWAI");
	$reqPegawaiStatusNama= $set->getField("PEGAWAI_STATUS_NAMA");
	$reqPegawaiKedudukanTmt= dateToPageCheck($set->getField("PEGAWAI_KEDUDUKAN_TMT"));
	$reqPegawaiKedudukanNama= $set->getField("PEGAWAI_KEDUDUKAN_NAMA");
	$reqNipLama= $set->getField("NIP_LAMA");
	$reqNipBaru= $set->getField("NIP_BARU");
	$reqNama= $set->getField("NAMA");
	$reqGelarDepan= $set->getField("GELAR_DEPAN");
	$reqGelarBelakang= $set->getField("GELAR_BELAKANG");
	$reqTempatLahir= $set->getField("TEMPAT_LAHIR");
	$reqTanggalLahir= $set->getField("TANGGAL_LAHIR");
	$reqJenisKelamin= $set->getField("JENIS_KELAMIN");
	$reqStatusKawin= $set->getField("STATUS_KAWIN");
	$reqSukuBangsa= $set->getField("SUKU_BANGSA");
	$reqGolonganDarah= $set->getField("GOLONGAN_DARAH");
	$reqEmail= $set->getField("EMAIL");
	$reqAlamat= $set->getField("ALAMAT");
	$reqAlamatKeterangan= $set->getField("ALAMAT_KETERANGAN");
	$reqRt= $set->getField("RT");
	$reqRw= $set->getField("RW");
	$reqKodePos= $set->getField("KODEPOS");
	$reqTelepon= $set->getField("TELEPON");
	$reqHp= $set->getField("HP");
	$reqKartuPegawai= $set->getField("KARTU_PEGAWAI");
	$reqAskes= $set->getField("ASKES");
	$reqTaspen= $set->getField("TASPEN");
	$reqNpwp= $set->getField("NPWP");
	$reqNik= $set->getField("NIK");
	$reqNoRekening= $set->getField("NO_REKENING");
	$reqSkKonversiNip= $set->getField("SK_KONVERSI_NIP");
	$reqBank= $set->getField("BANK_ID");
	$reqAgama= $set->getField("AGAMA_ID");
	$reqUrut= $set->getField("NO_URUT");
	$reqNoKk= $set->getField("NO_KK");
	$reqNoRakBerkas= $set->getField("NO_RAK_BERKAS");
	$reqTeleponKantor= $set->getField("TELEPON_KANTOR");
	$reqFacebook= $set->getField("FACEBOOK");
	$reqTwitter= $set->getField("TWITTER");
	$reqWhatsApp= $set->getField("WHATSAPP");
	$reqTelegram= $set->getField("TELEGRAM");
	$reqKeterangan1= $set->getField("KETERANGAN_1");
	$reqKeterangan2= $set->getField("KETERANGAN_2");

	$reqPropinsiId= $set->getField("PROPINSI_ID");
	$reqPropinsi= $set->getField("PROPINSI_NAMA");
	$reqKabupatenId= $set->getField("KABUPATEN_ID");
	$reqKabupaten= $set->getField("KABUPATEN_NAMA");
	$reqKecamatanId= $set->getField("KECAMATAN_ID");
	$reqKecamatan= $set->getField("KECAMATAN_NAMA");
	$reqDesaId= $set->getField("DESA_ID");
	$reqDesa= $set->getField("DESA_NAMA");

	$statement= " AND A.RIWAYAT_ID = 3 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$tempPathKarpeg= $pegawai_file->getField("PATH");

	$statement= " AND A.RIWAYAT_ID = 4 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$tempPathTaspen= $pegawai_file->getField("PATH");

	$statement= " AND A.RIWAYAT_ID = 2 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$tempPathSkKonversiNip= $pegawai_file->getField("PATH");

	$statement= " AND A.RIWAYAT_ID = 5 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$tempPathNik= $pegawai_file->getField("PATH");

	$statement= " AND A.RIWAYAT_ID = 6 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$tempPathKartuKeluarga= $pegawai_file->getField("PATH");

	$statement= " AND A.RIWAYAT_ID = 7 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$tempPathNpwp= $pegawai_file->getField("PATH");

	$statement= " AND A.RIWAYAT_ID = 9 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
	$pegawai_file= new PegawaiFile();
	$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
	$pegawai_file->firstRow();
	$reqPathBpjs= $pegawai_file->getField("PATH");

	$vidsapk= $set->getField("PEGAWAI_ID_SAPK");
	$reqRowId= $reqId;
}

$arrdatabkn= [];
$reqDataBKN =$this->input->get('reqDataBkn');
$arrDataSetDetail = $_SESSION['DATA_'.$reqNipBaru];
$dataFromBkn = '';

if(!empty($arrDataSetDetail))
{

		foreach ($arrDataSetDetail as  $value) {
	
		$arrdata= [];
		$reqBkn= $arrdata["id"]= $value["id"];
		$dataFromBkn = $value["id"]?$value["id"]:'';

		$reqBknNipBaru= $arrdata["nipBaru"]= $value["nipbaru"];
		$reqBknNipLama= $arrdata["nipLama"]= $value["niplama"];
		$reqBknNama= $arrdata["nama"]= $value["nama"];
		$reqBknGelarDepan= $arrdata["gelarDepan"]= $value["gelardepan"];
		$reqBknGelarBelakang= $arrdata["gelarBelakang"]= $value["gelarbelakang"];
		$reqBknTempatLahir= $arrdata["tempatLahir"]= $value["tempatlahir"];
		$arrdata["tempatLahirId"]= $value["tempatlahirid"];
		$arrdata["tglLahir"]= $value["tgllahir"];
		$reqBknAgama= $arrdata["agama"]= $value["agama"];
		$arrdata["agamaId"]= $value["agamaid"];
		$arrdata["email"]= $value["email"];
		$arrdata["emailGov"]= $value["emailgov"];
		$arrdata["nik"]= $value["nik"];
		$arrdata["alamat"]= $value["alamat"];
		$arrdata["noHp"]= $value["nohp"];
		$arrdata["noTelp"]= $value["notelp"];
		$arrdata["jenisPegawaiId"]= $value["jenispegawaiid"];
		$arrdata["mkTahun"]= $value["mktahun"];
		$arrdata["mkBulan"]= $value["mkbulan"];
		$reqBknJenisPegawaiNama= $arrdata["jenisPegawaiNama"]= $value["jenispegawainama"];
		$arrdata["kedudukanPnsId"]= $value["kedudukanpnsid"];
		$reqBknPegawaiKedudukanNama= $arrdata["kedudukanPnsNama"]= $value["kedudukanpnsnama"];
		$reqBknStatusPegawai= $arrdata["statusPegawai"]= $value["statuspegawai"];
		$reqBknJenisKelamin= $arrdata["jenisKelamin"]= $value["jeniskelamin"];
		if($reqBknJenisKelamin == "M")
			$reqBknJenisKelamin= "Laki-laki";
		else
			$reqBknJenisKelamin= "Perempuan";

		$arrdata["jenisIdDokumenId"]= $value["jenisiddokumenid"];
		$arrdata["jenisIdDokumenNama"]= $value["jenisiddokumennama"];
		$arrdata["nomorIdDocument"]= $value["nomoriddocument"];
		$arrdata["noSeriKarpeg"]= $value["noserikarpeg"];
		$arrdata["tkPendidikanTerakhirId"]= $value["tkpendidikanterakhirid"];
		$arrdata["tkPendidikanTerakhir"]= $value["tkpendidikanterakhir"];
		$arrdata["pendidikanTerakhirId"]= $value["pendidikanterakhirid"];
		$reqBknPendidikanAkhir= $arrdata["pendidikanTerakhirNama"]= $value["pendidikanterakhirnama"];
		$arrdata["tahunLulus"]= $value["tahunlulus"];
		$arrdata["tmtPns"]= $value["tmtpns"];
		$arrdata["tmtPensiun"]= $value["tmtpensiun"];
		$arrdata["bupPensiun"]= $value["buppensiun"];
		$arrdata["tglSkPns"]= $value["tglskpns"];
		$arrdata["tmtCpns"]= $value["tmtcpns"];
		$arrdata["tglSkCpns"]= $value["tglskcpns"];
		$arrdata["instansiIndukId"]= $value["instansiindukid"];
		$arrdata["instansiIndukNama"]= $value["instansiinduknama"];
		$arrdata["satuanKerjaIndukId"]= $value["satuankerjaindukid"];
		$arrdata["satuanKerjaIndukNama"]= $value["satuankerjainduknama"];
		$arrdata["kanregId"]= $value["kanregid"];
		$arrdata["kanregNama"]= $value["kanregnama"];
		$arrdata["instansiKerjaId"]= $value["instansikerjaid"];
		$arrdata["instansiKerjaNama"]= $value["instansikerjanama"];
		$arrdata["instansiKerjaKodeCepat"]= $value["instansikerjakodecepat"];
		$arrdata["satuanKerjaKerjaId"]= $value["satuankerjakerjaid"];
		$arrdata["satuanKerjaKerjaNama"]= $value["satuankerjakerjanama"];
		$arrdata["unorId"]= $value["unorid"];
		$reqBknSatuanKerjaNamaDetil= $arrdata["unorNama"]= $value["unornama"];
		$arrdata["unorIndukId"]= $value["unorindukid"];
		$arrdata["unorIndukNama"]= $value["unorindukid"];
		$arrdata["jenisJabatanId"]= $value["jenisjabatanid"];
		$arrdata["jenisJabatan"]= $value["jenisjabatan"];
		$arrdata["jabatanNama"]= $value["jabatannama"];
		$arrdata["jabatanStrukturalId"]= $value["jabatanstrukturalid"];
		$arrdata["jabatanStrukturalNama"]= $value["jabatanstrukturalnama"];
		$arrdata["jabatanFungsionalId"]= $value["jabatanfungsionalid"];
		$arrdata["jabatanFungsionalNama"]= $value["jabatanfungsionalnama"];
		$arrdata["jabatanFungsionalUmumId"]= $value["jabatanfungsionalumumid"];
		$arrdata["jabatanFungsionalUmumNama"]= $value["jabatanfungsionalumumnama"];

		$infonamajabatan= 1;
		if(!empty($arrdata["jabatanFungsionalId"]))
			$infonamajabatan= 3;
		else if(!empty($arrdata["jabatanFungsionalUmumId"]))
			$infonamajabatan= 2;

		$arrNamaJabatan = array();
		$arrNamaJabatan[1]='Jabatan Struktural';
		$arrNamaJabatan[2]='Jabatan Fungsional Umum';
		$arrNamaJabatan[3]='Jabatan Fungsional Tertentu';
		$reqBknJabatanAkhir= $arrNamaJabatan[$infonamajabatan];

		$arrdata["tmtJabatan"]= $value["tmtjabatan"];
		$arrdata["lokasiKerjaId"]= $value["lokasikerjaid"];
		$arrdata["lokasiKerja"]= $value["lokasikerja"];
		$arrdata["golRuangAwalId"]= $value["golruangawalid"];
		$arrdata["golRuangAwal"]= $value["golruangawal"];
		$arrdata["golRuangAkhirId"]= $value["golruangakhirid"];
		$arrdata["golRuangAkhir"]= $value["golruangakhir"];
		$arrdata["tmtGolAkhir"]= $value["tmtgolakhir"];
		$arrdata["masaKerja"]= $value["masakerja"];
		$arrdata["eselon"]= $value["eselon"];
		$arrdata["eselonId"]= $value["eselonid"];
		$arrdata["eselonLevel"]= $value["eselonlevel"];
		$arrdata["tmtEselon"]= $value["tmteselon"];
		$arrdata["gajiPokok"]= $value["gajipokok"];
		$arrdata["kpknId"]= $value["kpknid"];
		$arrdata["kpknNama"]= $value["kpknnama"];
		$arrdata["ktuaId"]= $value["ktuaid"];
		$arrdata["ktuaNama"]= $value["ktuanama"];
		$arrdata["taspenId"]= $value["taspenid"];
		$arrdata["taspenNama"]= $value["taspennama"];
		$arrdata["jenisKawinId"]= $value["jeniskawinid"];
		$arrdata["statusPerkawinan"]= $value["statusperkawinan"];
		$arrdata["statusHidup"]= $value["statushidup"];
		$arrdata["tglSuratKeteranganDokter"]= $value["tglsuratketerangandokter"];
		$arrdata["noSuratKeteranganDokter"]= $value["nosuratketerangandokter"];
		$arrdata["jumlahIstriSuami"]= $value["jumlahistrisuami"];
		$arrdata["jumlahAnak"]= $value["jumlahanak"];
		$arrdata["noSuratKeteranganBebasNarkoba"]= $value["nosuratketeranganbebasnarkoba"];
		$arrdata["tglSuratKeteranganBebasNarkoba"]= $value["tglsuratketeranganbebasnarkoba"];
		$arrdata["skck"]= $value["tglskck"];
		$arrdata["tglSkck"]= $value["tglskck"];
		$arrdata["akteKelahiran"]= $value["aktekelahiran"];
		$arrdata["akteMeninggal"]= $value["aktemeninggal"];
		$arrdata["tglMeninggal"]= $value["tglmeninggal"];
		$reqBknNpwp= $arrdata["noNpwp"]= $value["nonpwp"];
		$arrdata["tglNpwp"]= $value["tglnpwp"];
		$arrdata["noAskes"]= $value["noaskes"];
		$reqBknBpjs= $arrdata["bpjs"]= $value["bpjs"];
		$arrdata["kodePos"]= $value["kodepos"];
		$arrdata["noSpmt"]= $value["nospmt"];
		$arrdata["noTaspen"]= $value["notaspen"];
		$arrdata["bahasa"]= $value["bahasa"];
		$arrdata["kppnId"]= $value["kppnid"];
		$arrdata["kppnNama"]= $value["kppnnama"];
		$reqBknPangkatAkhir= $arrdata["pangkatakhir"]= $value["pangkatakhir"];
		$arrdata["tglSttpl"]= $value["tglsttpl"];
		$arrdata["nomorSttpl"]= $value["nomorsttpl"];
		$arrdata["nomorSkCpns"]= $value["nomorskcpns"];
		$arrdata["nomorSkPns"]= $value["nomorskpns"];
		$arrdata["jenjang"]= $value["jenjang"];
		$arrdata["jabatanAsn"]= $value["jabatanasn"];
		$reqBknKartuAsn= $arrdata["kartuAsn"]= $value["kartuasn"];
		array_push($arrdatabkn, $arrdata);

	
	}
}
// print_r($arrdatabkn);exit;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<!-- AUTO KOMPLIT -->
	<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
	<script src="lib/autokomplit/jquery-ui.js"></script>


	<script type="text/javascript">	
		$(function(){

			// $('input[id^="reqSatuanKerja"]').each(function(){
			$("#reqSatuanKerja, #reqJenisxxxPegawai").each(function(){
				$(this).autocomplete({
						source:function(request, response){
							var id= this.element.attr('id');
							var replaceAnakId= replaceAnak= urlAjax= "";

							if (id.indexOf('reqSatuanKerja') !== -1)
							{
								var element= id.split('reqSatuanKerja');
								var indexId= "reqSatuanKerjaId"+element[1];
								urlAjax= "satuan_kerja_json/auto";
							}
							else if (id.indexOf('reqJenisPegawai') !== -1)
							{
								var element= id.split('reqJenisPegawai');
								var indexId= "reqJenisPegawaiId"+element[1];
								urlAjax= "jenis_pegawai_json/auto";
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
							if (id.indexOf('reqSatuanKerja') !== -1)
							{
								var element= id.split('reqSatuanKerja');
								var indexId= "reqSatuanKerjaId"+element[1];
							}
							else if (id.indexOf('reqJenisPegawai') !== -1)
							{
								var element= id.split('reqJenisPegawai');
								var indexId= "reqJenisPegawaiId"+element[1];
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

			$('#ff').form({
				url:'pegawai_json/add',
				onSubmit:function(){
					if($(this).form('validate')){}
					else
					{
						$.messager.alert('Info', "Lengkapi data terlebih dahulu dan pastikan data NIK dan No KK yang diisikan benar", 'info');
						return false;
					}

					var reqNipBaru= "";
					reqNipBaru= $("#reqNipBaru").val();
					if(reqNipBaru == "")
					{
						mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
						return false;
					}
					//return $(this).form('validate');
				},
				success:function(data){
				  // console.log(data);return false;
				  data = data.split("-");
				  rowid= data[0];
				  infodata= data[1];
				  //$.messager.alert('Info', infodata, 'info');
					/*data = data.split("-");
					rowid= data[0];
					infodata= data[1];
					$.messager.alert('Info', infodata, 'info');
					parent.reloadparenttab();
					*/

					mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
					{
					  clearInterval(interval);
					  mbox.close();
					<?
					//if($reqId == "")
					//{
					?>
						if(rowid == "xxx"){}
						else
							top.location.href= "app/loadUrl/app/pegawai_add/?reqId="+rowid;
					<?
					//}
					//else
					//{
					?>
						//document.location.href= "app/loadUrl/app/pegawai_add_data/?reqId="+rowid;
					<?
					//}
					?>
					}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
				}
			});
			

			$('input[id^="reqPropinsi"], input[id^="reqKabupaten"], input[id^="reqKecamatan"], input[id^="reqDesa"]').each(function(){
				$(this).autocomplete({
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";

						if (id.indexOf('reqPropinsi') !== -1)
						{
							var element= id.split('reqPropinsi');
							var indexId= "reqPropinsiId"+element[1];
							urlAjax= "propinsi_json/combo";
							replaceAnakId= "reqKabupatenId";
							replaceAnak= "reqKabupaten";
							$("#reqKabupatenId, #reqKecamatanId, #reqDesaId").val("");
							$("#reqKabupaten, #reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKabupaten') !== -1)
						{
							var element= id.split('reqKabupaten');
							var indexId= "reqKabupatenId"+element[1];
							var idPropVal= $("#reqPropinsiId").val();
							urlAjax= "kabupaten_json/combo?reqPropinsiId="+idPropVal;
							replaceAnakId= "reqKecamatanId";
							replaceAnak= "reqKecamatan";
							$("#reqKecamatanId, #reqDesaId").val("");
							$("#reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKecamatan') !== -1)
						{
							var element= id.split('reqKecamatan');
							var indexId= "reqKecamatanId"+element[1];
							var idPropVal= $("#reqPropinsiId").val();
							var idKabVal= $("#reqKabupatenId").val();
							urlAjax= "kecamatan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal;
							replaceAnakId= "reqDesaId";
							replaceAnak= "reqDesa";
							$("#reqDesaId").val("");
							$("#reqDesa").val("");
						}
						else if (id.indexOf('reqDesa') !== -1)
						{
							var element= id.split('reqDesa');
							var indexId= "reqDesaId"+element[1];
							var idPropVal= $("#reqPropinsiId").val();
							var idKabVal= $("#reqKabupatenId").val();
							var idKecVal= $("#reqKecamatanId").val();
							urlAjax= "kelurahan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal+"&reqKecamatanId="+idKecVal;
						}

						$.ajax({
							url: urlAjax,
							type: "GET",
							dataType: "json",
							data: { term: request.term },
							success: function(responseData){
								$("#"+indexId).val("").trigger('change');
								if(replaceAnakId == ""){}
								else
								{
									$("#"+replaceAnakId).val("").trigger('change');
									$("#"+replaceAnak).val("").trigger('change');
								}

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

						var replaceAnakId= replaceAnak= "";
						
						if (id.indexOf('reqPropinsi') !== -1)
						{
							var element= id.split('reqPropinsi');
							var indexId= "reqPropinsiId"+element[1];
							replaceAnakId= "reqKabupatenId";
							replaceAnak= "reqKabupaten";
							$("#reqKabupatenId, #reqKecamatanId, #reqDesaId").val("");
							$("#reqKabupaten, #reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKabupaten') !== -1)
						{
							var element= id.split('reqKabupaten');
							var indexId= "reqKabupatenId"+element[1];
							replaceAnakId= "reqKecamatanId";
							replaceAnak= "reqKecamatan";
							$("#reqKecamatanId, #reqDesaId").val("");
							$("#reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKecamatan') !== -1)
						{
							var element= id.split('reqKecamatan');
							var indexId= "reqKecamatanId"+element[1];
							replaceAnakId= "reqDesaId";
							replaceAnak= "reqDesa";
							$("#reqDesaId").val("");
							$("#reqDesa").val("");
						}
						else if (id.indexOf('reqDesa') !== -1)
						{
							var element= id.split('reqDesa');
							var indexId= "reqDesaId"+element[1];
						}

						$("#"+indexId).val(ui.item.id).trigger('change');
					},
					autoFocus: true
				})
				.autocomplete( "instance" )._renderItem = function( ul, item ) {
					return $( "<li>" )
					.append( "<a>" + item.desc  + "</a>" )
					.appendTo( ul );
				};
			});

			<?php /*?>$('#reqSatuanKerjaJsonId').combotree('setValue', "<?=$reqSatuanKerjaId?>");
			var url = "satuan_kerja_json/combotree";
			$('#reqSatuanKerjaJsonId').combotree('reload', url);<?php */?>

			setmutasiluardaerah();
			$('#reqStatusMutasi').bind('change', function(ev) {
				setmutasiluardaerah();
			});

		});

		function setmutasiluardaerah()
		{
			var reqStatusMutasi= "";
			reqStatusMutasi= $("#reqStatusMutasi").val();
			$("#reqInstansiSebelum, #reqTmtMutasi").validatebox({required: false});
			$("#reqInstansiSebelum, #reqTmtMutasi").removeClass('validatebox-invalid');

			if(reqStatusMutasi == "")
			{
				$(".setstatusmutasi").hide();
				$("#reqInstansiSebelum, #reqTmtMutasi").val("");
			}
			else
			{
				$(".setstatusmutasi").show();
				$("#reqInstansiSebelum, #reqTmtMutasi").validatebox({required: true});
			}
		}
	</script>

	<!-- SIMPLE TAB -->
	<!-- <script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
	<link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet"> -->


	<!-- CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- CSS style Horizontal Nav-->    
	<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- Custome CSS-->    
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<style>
		span.combo
		{
			width:50% !important
		}
		
		.combo-label
		{
			top:-2rem !important;
		}
		
		@media screen and (max-width:767px) {
			span.combo
			{
				width:100% !important
			}
		}
	</style>
	<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>
	<script type="text/javascript" src="lib/easyui/pelayanan-bkndetil.js"></script>


    <link href="lib/mbox/mbox.css" rel="stylesheet">
  	<script src="lib/mbox/mbox.js"></script>
    <link href="lib/mbox/mbox-modif.css" rel="stylesheet">

    <style type="text/css">
    	@media screen and (max-width:767px) {
    		ul.collection.card li.collection-item.ubah-color-warna.skin-purple {
                color: #FFFFFF !important;
            }
        }
    </style>
    
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m12" style="padding-left: 15px;">
				
				<ul class="collection card">
					<li class="collection-item ubah-color-warna">PEGAWAI</li>
					<li class="collection-item">

						<div class="col s12">
							<ul class="tabs">
								<li class="tab col s3"><a class="active" href="#tabdatasiapasn">Data SIAPASN</a></li>
								<li class="tab col s3"><a href="#tabdatabkn" onclick="loadingDataBkn()">Data BKN</a></li>
							</ul>
						</div>

						<div id="tabdatasiapasn" class="row">
							<form id="ff" method="post"  novalidate enctype="multipart/form-data">

								<div id="tabs" style="height:100%;">
                                    <ul id="tabs-swipe-demo" class="tabs">
                                        <li class="tab col s3"><a class="active" href="#swipe-1">Data Pribadi</a></li>
                                        <li class="tab col s3"><a href="#swipe-2">Dokumen Pribadi</a></li>
                                        <li class="tab col s3"><a href="#swipe-3">Alamat</a></li>
                                        <li class="tab col s3"><a href="#swipe-4">Kontak</a></li>
                                        <li class="tab col s3"><a href="#swipe-5">Finansial</a></li>
                                        <li class="tab col s3"><a href="#swipe-6">Lainnya</a></li>
                                    </ul>
                                </div>

								<div id="swipe-1" class="col s12" style="height:auto !important">
									<?
									if($tempLoginLevel == "99" || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
									{
									?>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqNipBaru">NIP Baru</label>
											<input required name="reqNipBaru" id="reqNipBaru" class="easyui-validatebox"  type="text"  value="<?=$reqNipBaru?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqNipLama">NIP Lama</label>
											<input placeholder="" name="reqNipLama" id="reqNipLama" class="easyui-validatebox" type="text" value="<?=$reqNipLama?>" />
										</div>
									</div>		
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqNama">Nama</label>
											<input required name="reqNama" id="reqNama" class="easyui-validatebox" type="text" value="<?=$reqNama?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqGelarDepan">Gelar Depan</label>
											<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqGelarDepan?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqGelarDepan">Gelar Belakang</label>
											<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqGelarBelakang?>" />
										</div>
									</div>	
									
									<?
									}
									else
									{
									?>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqNipBaruInfo">NIP Baru</label>
											<input name="reqNipBaru" id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
											<input id="reqNipBaruInfo" type="text" value="<?=$reqNipBaru?>" disabled />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqNipLamaInfo">NIP Lama</label>
											<input name="reqNipLama" id="reqNipLama" type="hidden"  value="<?=$reqNipLama?>" />
											<input id="reqNipLamaInfo" type="text" value="<?=$reqNipLama?>" disabled />
										</div>
									</div>		
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqNamaInfo">Nama</label>
											<input name="reqNama" id="reqNama" type="hidden"  value="<?=$reqNama?>" />
											<input id="reqNamaInfo" type="text" value="<?=$reqNama?>" disabled />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqGelarDepan">Gelar Depan</label>
											<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqGelarDepan?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqGelarDepan">Gelar Belakang</label>
											<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqGelarBelakang?>" />
										</div>
									</div>	
									<?
									}
									?>
									<div class="row">
										<div class="input-field col s12 m3">
											<label for="reqStatusPegawai">Status Pegawai</label>
                                            <input id="reqStatusPegawai" type="text" disabled="disabled"  value="<?=$reqPegawaiStatusNama?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqPegawaiKedudukanNama">Kedudukan</label>
											<input name="reqPegawaiKedudukanNama" id="reqPegawaiKedudukanNama" class="easyui-validatebox" type="text" readonly="readonly" value="<?=$reqPegawaiKedudukanNama?>" />
										</div>
                                        <div class="input-field col s12 m3">
                                            <label for="reqPegawaiKedudukanTmt">TMT Kedudukan</label>
                                            <input type="text" id="reqPegawaiKedudukanTmt" disabled value="<?=$reqPegawaiKedudukanTmt?> " />
              							</div>
									</div>		
									<div class="row">
										<div class="input-field col s12 m3">
											<label for="reqTempatLahir">Tempat Lahir</label>
											<input name="reqTempatLahir" id="reqTempatLahir" class="easyui-validatebox" type="text" value="<?=$reqTempatLahir?>" />
										</div>
                                        <div class="input-field col s12 m2">
											<select id="reqJenisKelamin" name="reqJenisKelamin" style="padding:0" class="form-control"  >
                                            	<option value=""></option>
                                                <option value="L" <? if($reqJenisKelamin == "L") echo "selected"?>>Laki-laki</option>
                                                <option value="P" <? if($reqJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                                            </select>
                                            <label for="reqJenisKelamin">Jenis Kelamin</label>
										</div>
                                        <div class="input-field col s12 m1">
											<select name="reqGolonganDarah" id="reqGolonganDarah" style="padding:0" class="form-control" >
                                            	<option value="" <? if($reqGolonganDarah=="")echo "selected"?>></option>
												<option value="A" <? if($reqGolonganDarah=='A') echo ' selected'?>>A</option>
												<option value="B" <? if($reqGolonganDarah=='B') echo ' selected'?>>B</option>
												<option value="AB" <? if($reqGolonganDarah=='AB') echo ' selected'?>>AB</option>
												<option value="O" <? if($reqGolonganDarah=='O') echo ' selected'?>>O</option>
											</select>
											<label for="reqGolonganDarah">Gol Darah</label>
										</div>
										<div class="input-field col s12 m2">
											<select name="reqAgama" id="reqAgama" style="padding:0" class="form-control"  >
												<?
												while ($setAgama->nextRow()) 
												{
													?>
													<option value="<?=$setAgama->getField('AGAMA_ID')?>" <? if($reqAgama==$setAgama->getField("AGAMA_ID")) echo ' selected'?>><?=$setAgama->getField("NAMA")?></option>
													<?
												}
												?>
											</select>
											<label for="reqAgama">Agama</label>
										</div>
										<div class="input-field col s12 m4">
											<label for="reqSukuBangsa">Suku Bangsa</label>
											<input name="reqSukuBangsa" id="reqSukuBangsa" class="easyui-validatebox" type="text" value="<?=$reqSukuBangsa?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<?
											if($tempLoginLevel == "99" || isStrContain(strtoupper($this->USER_GROUP), "TEKNIS MUTASI") == "1"  || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
											{
											?>
											<select name="reqJenisPegawaiId" id="reqJenisPegawaiId" style="padding:0" class="form-control"  >
                                            	<option value="" <? if($reqJenisPegawaiId=="")echo "selected"?>></option>
												<?
												while ($setJenisPegawai->nextRow()) 
												{
													?>
													<option value="<?=$setJenisPegawai->getField('JENIS_PEGAWAI_ID')?>" <? if($reqJenisPegawaiId == $setJenisPegawai->getField("JENIS_PEGAWAI_ID")) echo ' selected'?>><?=$setJenisPegawai->getField("NAMA")?></option>
													<?
												}
												?>
											</select>
											<label for="reqJenisPegawaiId">Jenis Pegawai</label>
                                        	<!-- <label for="reqJenisPegawai">Jenis Pegawai</label>
                                            <input id="reqJenisPegawai" placeholder type="text" value="<?=$reqJenisPegawaiNama?>" />
											<input type="hidden" name="reqJenisPegawaiId" id="reqJenisPegawaiId" value="<?=$reqJenisPegawaiId?>" /> -->
											<?
											}
											else
											{
											?>
											<label for="reqLabelJenisPegawai">Jenis Pegawai</label>
                                            <input id="reqLabelJenisPegawai" placeholder type="text" disabled="disabled"  value="<?=$reqJenisPegawaiNama?>" />
											<input type="hidden" name="reqJenisPegawaiId" id="reqJenisPegawaiId" value="<?=$reqJenisPegawaiId?>" />
											<?
											}
											?>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<?
											if($tempLoginLevel == "99" || isStrContain(strtoupper($this->USER_GROUP), "TEKNIS MUTASI") == "1"  || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
											{
											?>
                                        	<label for="reqSatuanKerja">Satuan Kerja</label>
                                            <input id="reqSatuanKerja" type="text" value="<?=$reqSatuanKerjaNamaDetil?>" />
											<input type="hidden" name="reqSatuanKerjaId" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
											<?
											}
											else
											{
											?>
											<label for="reqLabelSatuanKerja">Satuan Kerja</label>
                                            <input id="reqLabelSatuanKerja" type="text" disabled="disabled"  value="<?=$reqSatuanKerjaNamaDetil?>" />
											<input type="hidden" name="reqSatuanKerjaId" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
											<?
											}
											?>
										</div>
									</div>		
								</div>

								<div id="swipe-2" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqNik">NIK</label>
											<input placeholder name="reqNik" class="easyui-validatebox" data-options="validType:'length[16,16]'" id="reqNik"  type="text" value="<?=$reqNik?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathNik == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathNik?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
										<div class="input-field col s12 m5">
											<label for="reqNoKk">No KK</label>
											<input placeholder name="reqNoKk" class="easyui-validatebox" data-options="validType:'length[16,16]'" id="reqNoKk"  type="text"  value="<?=$reqNoKk?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathKartuKeluarga == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathKartuKeluarga?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqKartuPegawai">Kartu Pegawai</label>
											<input name="reqKartuPegawai" id="reqKartuPegawai" class="easyui-validatebox"   type="text" value="<?=$reqKartuPegawai?>" />
										</div>
										<div class="col s12 m1">
										  <?
						                  if($tempPathKarpeg == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathKarpeg?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
					                    
										<div class="input-field col s12 m5" style="display: none;">
											<label for="reqTaspen">Taspen</label>
											<input name="reqTaspen" id="reqTaspen" class="easyui-validatebox" type="text" value="<?=$reqTaspen?>" />
										</div>
					                    <div class="col s12 m1" style="display: none;">
					                      <?
						                  if($tempPathTaspen == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathTaspen?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqBpjs">BPJS</label>
											<input placeholder name="reqBpjs" class="easyui-validatebox" id="reqBpjs" type="text" value="<?=$reqBpjs?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($reqPathBpjs == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$reqPathBpjs?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
					                    <div class="input-field col s12 m4">
					                    	<label for="reqBpjsTanggal">Tanggal BPJS</label>
					                    	<input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBpjsTanggal" id="reqBpjsTanggal" value="<?=$reqBpjsTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqBpjsTanggal');"/>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqNpwp">NPWP</label>
											<input placeholder name="reqNpwp" class="easyui-validatebox" id="reqNpwp" type="text" value="<?=$reqNpwp?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathNpwp == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathNpwp?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
					                    <div class="input-field col s12 m4">
					                    	<label for="reqNpwpTanggal">Tanggal NPWP</label>
					                    	<input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqNpwpTanggal" id="reqNpwpTanggal" value="<?=$reqNpwpTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqNpwpTanggal');"/>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqSkKonversiNip">SK Konversi NIP</label>
											<input placeholder name="reqSkKonversiNip" id="reqSkKonversiNip" class="easyui-validatebox" type="text" value="<?=$reqSkKonversiNip?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathSkKonversiNip == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathSkKonversiNip?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
										<div class="input-field col s12 m4">
											<label for="reqUrut">No Urut SK Konversi NIP</label>
											<input placeholder name="reqUrut" class="easyui-validatebox" id="reqUrut" type="text"  value="<?=$reqUrut?>" />
										</div>
										<div class="input-field col s12 m2">
											<label for="reqNoRakBerkas">No Rak Berkas Arsip</label>
											<input placeholder name="reqNoRakBerkas" class="easyui-validatebox" id="reqNoRakBerkas"  type="text" value="<?=$reqNoRakBerkas?>" />
										</div>
									</div>
								</div>

								<div id="swipe-3" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqPropinsi">Propinsi</label>
											<input type="hidden" name="reqPropinsiId" id="reqPropinsiId" value="<?=$reqPropinsiId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqPropinsi" 
							                data-options="validType:['sameAutoLoder[\'reqPropinsi\', \'\']']"
							                value="<?=$reqPropinsi?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqKabupaten">Kabupaten</label>
											<input type="hidden" name="reqKabupatenId" id="reqKabupatenId" value="<?=$reqKabupatenId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqKabupaten" 
							                data-options="validType:['sameAutoLoder[\'reqKabupaten\', \'\']']"
							                value="<?=$reqKabupaten?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqKecamatan">Kecamatan</label>
											<input type="hidden" name="reqKecamatanId" id="reqKecamatanId" value="<?=$reqKecamatanId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqKecamatan" 
							                data-options="validType:['sameAutoLoder[\'reqKecamatan\', \'\']']"
							                value="<?=$reqKecamatan?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqDesa">Desa</label>
											<input type="hidden" name="reqDesaId" id="reqDesaId" value="<?=$reqDesaId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqDesa" 
							                data-options="validType:['sameAutoLoder[\'reqDesa\', \'\']']"
							                value="<?=$reqDesa?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqRt">RT</label>
											<input name="reqRt" id="reqRt" class="easyui-validatebox" type="text" value="<?=$reqRt?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqRw">RW</label>
											<input name="reqRw" id="reqRw" class="easyui-validatebox " type="text" value="<?=$reqRw?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqAlamat">Alamat</label>
											<input name="reqAlamat" id="reqAlamat" class="easyui-validatebox" type="text" value="<?=$reqAlamat?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqAlamatKeterangan">Keterangan</label>
											<input placeholder name="reqAlamatKeterangan" id="reqAlamatKeterangan" class="easyui-validatebox" type="text" value="<?=$reqAlamatKeterangan?>" />
										</div>
									</div>
								</div>

								<div id="swipe-4" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqHp">No HP</label>
											<input placeholder name="reqHp" id="reqHp" class="easyui-validatebox" type="text" value="<?=$reqHp?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqTeleponKantor">No Telepon Kantor</label>
											<input placeholder name="reqTeleponKantor" id="reqTeleponKantor" class="easyui-validatebox" type="text" value="<?=$reqTeleponKantor?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqTelepon">No Telepon Rumah</label>
											<input placeholder name="reqTelepon" id="reqTelepon" class="easyui-validatebox" type="text" value="<?=$reqTelepon?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqEmail">Email Pribadi</label>
											<input placeholder name="reqEmail" id="reqEmail" class="easyui-validatebox" type="text" value="<?=$reqEmail?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqEmailKantor">Email go.id</label>
											<input placeholder name="reqEmailKantor" id="reqEmailKantor" class="easyui-validatebox" type="text" value="<?=$reqEmailKantor?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqFacebook">Facebook</label>
											<input placeholder name="reqFacebook" id="reqFacebook" class="easyui-validatebox" type="text" value="<?=$reqFacebook?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqTwitter">Twitter</label>
											<input placeholder name="reqTwitter" id="reqTwitter" class="easyui-validatebox" type="text" value="<?=$reqTwitter?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqWhatsApp">WhatsApp</label>
											<input placeholder name="reqWhatsApp" id="reqWhatsApp" class="easyui-validatebox" type="text" value="<?=$reqWhatsApp?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqTelegram">Telegram</label>
											<input placeholder name="reqTelegram" id="reqTelegram" class="easyui-validatebox" type="text" value="<?=$reqTelegram?>" />
										</div>
									</div>	
								</div>

								<div id="swipe-5" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m3">
											<select name="reqBank" id="reqBank" style="padding:0" class="form-control"  >
                                            	<option value="" <? if($reqBank=="")echo "selected"?>></option>
												<?
												while ($setBank->nextRow()) 
												{
													?>
													<option value="<?=$setBank->getField('BANK_ID')?>" <? if($reqBank==$setBank->getField("BANK_ID")) echo ' selected'?>><?=$setBank->getField("NAMA")?></option>
													<?
												}
												?>
											</select>
											<label for="reqBank">Bank</label>
										</div>
										<div class="input-field col s12 m3">
											<label for="reqNoRekening">No Rekening</label>
											<input placeholder name="reqNoRekening" id="reqNoRekening" class="easyui-validatebox" type="text" value="<?=$reqNoRekening?>" />
										</div>										
										<div class="input-field col s12 m6">
											<label for="reqRekeningNama">Nama Pemilik Rekening (Sesuai Buku Rekening)</label>
											<input placeholder name="reqRekeningNama" id="reqRekeningNama" class="easyui-validatebox" type="text" value="<?=$reqRekeningNama?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m3">
											<label for="reqGajiPokok">Gaji Pokok</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqTunjanganKeluarga">Tunjangan Keluarga</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqTunjanganKeluarga" name="reqTunjanganKeluarga" OnFocus="FormatAngka('reqTunjanganKeluarga')" OnKeyUp="FormatUang('reqTunjanganKeluarga')" OnBlur="FormatUang('reqTunjanganKeluarga')" value="<?=numberToIna($reqTunjanganKeluarga)?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqTunjangan">Tunjangan / Penghasilan Lainnya</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqGajiBersih">Gaji Bersih</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqGajiBersih" name="reqGajiBersih" OnFocus="FormatAngka('reqGajiBersih')" OnKeyUp="FormatUang('reqGajiBersih')" OnBlur="FormatUang('reqGajiBersih')" value="<?=numberToIna($reqGajiBersih)?>" />
										</div>
									</div>
								</div>

								<div id="swipe-6" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m6">
											<select name="reqStatusMutasi" id="reqStatusMutasi" style="padding:0" class="form-control" >
                                            	<option value="" <? if($reqStatusMutasi == "") echo "selected"?>>Tidak</option>
                                            	<option value="1" <? if($reqStatusMutasi == "1") echo "selected"?>>Ya</option>
											</select>
											<label for="reqStatusMutasi">Mutasi Masuk Luar Daerah?</label>
										</div>
										<div class="input-field col s12 m6 setstatusmutasi">
											<label for="reqTmtMutasi">TMT Mutasi Masuk</label>
					                    	<input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtMutasi" id="reqTmtMutasi" value="<?=$reqTmtMutasi?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtMutasi');"/>
										</div>
									</div>
									<div class="row setstatusmutasi">
										<div class="input-field col s12 m12">
											<label for="reqInstansiSebelum">Instansi Sebelumnya</label>
											<input placeholder name="reqInstansiSebelum" id="reqInstansiSebelum" class="easyui-validatebox" type="text" value="<?=$reqInstansiSebelum?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqKeterangan1">Keterangan 1</label>
											<input placeholder name="reqKeterangan1" id="reqKeterangan1" class="easyui-validatebox" type="text" value="<?=$reqKeterangan1?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqKeterangan2">Keterangan 2</label>
											<input placeholder name="reqKeterangan2" id="reqKeterangan2" class="easyui-validatebox" type="text" value="<?=$reqKeterangan2?>" />
										</div>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m12">
										<?
										if($tempLoginLevel == "99" || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
										{
										?>
										<br/><br/><br/><br/><br/><br/>
										<?
										}
										?>
										<input type="hidden" name="reqId" value="<?=$reqId?>" />
										<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

										<?
										// A;R;D
										if($tempAksesMenu == "A")
										{
										?>
										<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
											<i class="mdi-content-save left hide-on-small-only"></i>
										</button>
										<?
										}
										?>

										<?
										if(!empty($reqRowId) && !empty($kirimsapk))
										{
											?>
											<button class="btn  waves-effect waves-light" style="font-size:9pt;background: #9C28B0;" type="button" id='buttonbtn'>
												<input type="hidden" id="reqIdField" value="<?=$reqRowId?>" />
												<input type="hidden" id="reqIdBkn" value="<?=$vidsapk?>" />
												<input type="hidden" id="reqUrlBkn" value="data_utama_json" />
												<i class="mdi-content-save left hide-on-small-only"></i> <span > UPDATE KE SIASN BKN</span>
											</button>
											<?
										}
										?>

										<?
										if($reqId == ""){}
										else
										{
										?>
										<button class="btn waves-effect waves-light red" style="font-size:9pt" type="button" id="buttonwafattewas">Laporkan
											<i class="mdi-image-timer-off left hide-on-small-only"></i>
										</button>
										<!-- <button class="btn waves-effect waves-light red" style="font-size:9pt" type="button" id="buttonwafat">Laporkan Wafat
											<i class="mdi-image-timer-off left hide-on-small-only"></i>
										</button>
										<button class="btn waves-effect waves-light red" style="font-size:9pt" type="button" id="buttontewas">Laporkan Tewas
											<i class="mdi-image-timer-off left hide-on-small-only"></i>
										</button> -->
										<?
										}
										?>
									</div>
								</div>

							</form>
						</div>

						<div id="tabdatabkn" class="row">
							<div id="bkntabs" style="height:100%;">
                                <ul id="bkntabs-swipe-demo" class="tabs">
                                    <li class="tab col s3"><a class="active" href="#bknswipe-1">Data Pribadi</a></li>
                                    <li class="tab col s3"><a href="#bknswipe-2">Lainnya</a></li>
                                    <!-- <li class="tab col s3"><a href="#bknswipe-3">Lainnya</a></li> -->
                                    <!-- <li class="tab col s3"><a href="#bknswipe-4">Kontak</a></li>
                                    <li class="tab col s3"><a href="#bknswipe-5">Finansial</a></li>
                                    <li class="tab col s3"><a href="#bknswipe-6">Lainnya</a></li> -->
                                </ul>
                            </div>

							<div id="bknswipe-1" class="col s12" style="height:auto !important">
								<div class="row">
									<div class="input-field col s12 m6">
										<label>NIP Baru</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknNipBaru?>" />
									</div>
									<div class="input-field col s12 m6">
										<label>NIP Lama</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknNipLama?>" />
									</div>
								</div>		
								<div class="row">
									<div class="input-field col s12 m6">
										<label>Nama</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknNama?>" />
									</div>
									<div class="input-field col s12 m3">
										<label>Gelar Depan</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknGelarDepan?>" />
									</div>
									<div class="input-field col s12 m3">
										<label>Gelar Belakang</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknGelarBelakang?>" />
									</div>
								</div>
								

								<div class="row">
									<div class="input-field col s12 m3">
										<label>Status Pegawai</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknStatusPegawai?>" />
									</div>
									<div class="input-field col s12 m6">
										<label>Kedudukan</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknPegawaiKedudukanNama?>" />
									</div>
									<!-- <div class="input-field col s12 m3">
										<label>TMT Kedudukan</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknPegawaiKedudukanTmt?>" />
									</div> -->
								</div>
								<div class="row">
									<div class="input-field col s12 m6">
										<label>Tempat Lahir</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknTempatLahir?>" />
									</div>
                                    <div class="input-field col s12 m3">
                                        <label>Jenis Kelamin</label>
                                        <input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknJenisKelamin?>" />
									</div>
									<div class="input-field col s12 m3">
										<label>Agama</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknAgama?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m12">
										<label>Jenis Pegawai</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknJenisPegawaiNama?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m12">
										<label>Satuan Kerja</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$reqBknSatuanKerjaNamaDetil?>" />
									</div>
								</div>
							</div>
							<div id="bknswipe-2" class="col s12" style="height:auto !important">
								<div class="row">
									<div class="input-field col s12 m6">
										<label>NIK</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknNik?>" />
									</div>

									<div class="input-field col s12 m6">
										<label>NPWP</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknNpwp?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m6">
										<label>Email</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$arrdata["email"]?>" />
									</div>
									<div class="input-field col s12 m3">
										<label>No Telp</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$arrdata["noTelp"]?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m6">
										<label>Alamat</label>
										<input placeholder disabled class="easyui-validatebox" type="text" value="<?=$arrdata["alamat"]?>" />
									</div>
									
								</div>
								<div class="row">
									<div class="input-field col s12 m6">
										<label>Kartu ASN</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknKartuAsn?>" />
									</div>
									<div class="input-field col s12 m6">
										<label>BPJS</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknBpjs?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m12">
										<label>Pangkat Akhir</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknPangkatAkhir?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m12">
										<label>Jabatan Akhir</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknJabatanAkhir?>" />
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12 m12">
										<label>Pendidikan Akhir</label>
										<input placeholder="" disabled class="easyui-validatebox" type="text" value="<?=$reqBknPendidikanAkhir?>" />
									</div>
								</div>
							</div>
						</div>


					</li>
				</ul>

			</div>
		</div>
	</div>

	<!--materialize js-->
	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<script type="text/javascript">
		$('#reqNoKk,#reqNik,#reqHp,#reqTeleponKantor,#reqTelepon').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		$(document).ready(function() {

			$("#tabs").tabs({
			  swipeable: true
			});
			
			$("#buttonwafat,#buttontewas,#buttonwafattewas").click(function() { 
				var id= $(this).attr('id');

				if(id == "buttonwafat")
				{
					info= "Apakah yakin untuk an. <?=$reqNama?>, laporkan wafat?";
					reqStatusPegawaiKedudukanId= "24";
					reqStatusPegawaiId= "3";
				}
				else if(id == "buttontewas")
				{
					info= "Apakah yakin untuk an. <?=$reqNama?>, laporkan tewas?";
					reqStatusPegawaiKedudukanId= "25";
					reqStatusPegawaiId= "3";
				}
				else if(id == "buttonwafattewas")
				{
					// info= "Apakah yakin untuk an. <?=$reqNama?>, laporkan wafat/tewas?";
					// reqStatusPegawaiKedudukanId= "24,25";
					// reqStatusPegawaiId= "3";

					info= "Apakah yakin untuk Laporakan an. <?=$reqNama?>?";
					// reqStatusPegawaiKedudukanId= "21,23,24,25,28";
					// reqStatusPegawaiId= "3";
					reqStatusPegawaiId= reqStatusPegawaiKedudukanId= "";
				}

				
				mbox.custom({
					message: info,
					options: {close_speed: 100},
					buttons: [
					{
						label: 'Ya',
						color: 'green darken-2',
						callback: function() {
							document.location.href= "app/loadUrl/app/pegawai_add_pegawai_status_lapor?reqId=<?=$reqId?>&reqStatusPegawaiKedudukanId="+reqStatusPegawaiKedudukanId+"&reqStatusPegawaiId="+reqStatusPegawaiId;
							mbox.close();
						}
					},
					{
						label: 'Tidak',
						color: 'grey darken-2',
						callback: function() {
					   //console.log('do action for no answer');
					   mbox.close();
					}
				}
				]
				});
			});

			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');

	</script>

	<script type="text/javascript">
		function loadingDataBkn(){
			<?
			if(empty($dataFromBkn)){
			?>
			var infodata='Loading';
			mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
			{
				clearInterval(interval);
				   $.get("pegawai_data_sync_json?reqNipBaru=<?=$reqNipBaru?>" )
              .done(function( data ) {
              	 var   datass = JSON.parse(data);
              	 if(datass['status']=='succes'){
              	 	mbox.close();
              	 	window.location.href="app/loadUrl/app/pegawai_add_data/?reqId=<?=$reqId?>";
              	 }
              	 });

				
			}, 1000));
			$(".mbox > .right-align").css({"display": "none"});
			
			<?
			}
			?>
		}
	</script>

    
    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

	<style type="text/css">
	ul.dropdown-content.select-dropdown {
		/*border: 2px solid red;*/
		height: 200px !important;
		overflow: auto !important;
	}
</style>

</body>
</html>
