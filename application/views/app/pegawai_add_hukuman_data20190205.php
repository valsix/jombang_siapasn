<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Hukuman');
$this->load->model('PejabatPenetap');
$this->load->model('TingkatHukuman');
$this->load->model('JenisHukuman');
$this->load->model('JabatanRiwayat');
$this->load->model('Eselon');
$this->load->model('Pangkat');

$eselon= new Eselon();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0111";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);


if($reqRowId == ""){
	$reqMode = "insert";
  //$reqTingkatHukumanId= 2;
  //$reqJenisHukumanId= 6;

  //$reqTingkatHukumanId= 1;
  //$reqJenisHukumanId= 2;

  //$reqPermasalahan= $reqNoSk="asd";
  //$reqModeSimpan= "hukuman";
  //$reqTanggalSk= "01-01-2018";
  //$reqTmtSk= $reqTanggalMulai= "01-03-2018";
  //$reqTanggalAkhir= "01-03-2019";
  //$reqTanggalPemulihan= "31-12-2019";
}
else
{
	$reqMode = 'update';

	$set= new Hukuman();
	$statement= " AND A.HUKUMAN_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();

	$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
	$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$reqNoSk= $set->getField('NO_SK');
	$reqTanggalSk= dateToPageCheck($set->getField('TANGGAL_SK'));
	$reqTmtSk= dateToPageCheck($set->getField('TMT_SK'));
	$reqJenisHukumanId= $set->getField('JENIS_HUKUMAN_ID');
	$reqJenisHukumanNama= $set->getField('JENIS_HUKUMAN_NAMA');
	$reqTingkatHukumanId= $set->getField('TINGKAT_HUKUMAN_ID');
	$reqTingkatHukumanNama= $set->getField('TINGKAT_HUKUMAN_NAMA');

	$reqPeraturan= $set->getField('PERATURAN_ID');
	$reqPermasalahan= $set->getField('KETERANGAN');
	$reqBerlaku= $set->getField('STATUS_BERLAKU');
	$reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
	$reqTanggalAkhir= dateToPageCheck($set->getField('TANGGAL_AKHIR'));
	$reqTanggalPemulihan= dateToPageCheck($set->getField('TANGGAL_PEMULIHAN'));

	$reqKenaikanPangkatBerikutnya= dateToPageCheck($set->getField('TMT_BERIKUT_PANGKAT'));
	$reqKenaikanGajiBerikutnya= dateToPageCheck($set->getField('TMT_BERIKUT_GAJI'));

	$reqJabatanRiwayatId= $set->getField('JABATAN_RIWAYAT_ID');
	$reqGajiRiwayatTerakhirId= $set->getField('GAJI_RIWAYAT_TERAKHIR_ID');
	$reqPangkatRiwayatTerakhirId= $set->getField('PANGKAT_RIWAYAT_TERAKHIR_ID');
	$reqPangkatRiwayatTurunId= $set->getField('PANGKAT_RIWAYAT_TURUN_ID');
	$reqGajiRiwayatTurunId= $set->getField('GAJI_RIWAYAT_TURUN_ID');
	$reqPangkatRiwayatKembaliId= $set->getField('PANGKAT_RIWAYAT_KEMBALI_ID');
	$reqGajiRiwayatKembaliId= $set->getField('GAJI_RIWAYAT_KEMBALI_ID');
	$reqPegawaiStatusId= $set->getField('PEGAWAI_STATUS_ID');

	$set_detil= new Hukuman();
	$statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqPangkatRiwayatTerakhirId;
	$set_detil->selectByParamsPangkatDataRiwayat(array(), -1,-1, $statement);
	$set_detil->firstRow();
  //echo $set_detil->query;exit;
	$reqKenaikanPangkatTerakhir= $set_detil->getField("PANGKAT_KODE").", TMT : ".dateToPageCheck($set_detil->getField("PANGKAT_TMT"));
	$reqKenaikanPangkatTerakhirTh= $set_detil->getField("MASA_KERJA_TAHUN");
	$reqKenaikanPangkatTerakhirBl= $set_detil->getField("MASA_KERJA_BULAN");
	$reqKenaikanPangkatTerakhirGajiPokok= $set_detil->getField("GAJI_POKOK");

	$set_detil= new Hukuman();
	$statement= " AND A.GAJI_RIWAYAT_ID = ".$reqGajiRiwayatTerakhirId;
	$set_detil->selectByParamsGajiDataRiwayat(array(), -1,-1, $statement);
	$set_detil->firstRow();
  //echo $set_detil->query;exit;
	$reqKenaikanGajiTerakhir= $set_detil->getField("PANGKAT_KODE").", TMT : ".dateToPageCheck($set_detil->getField("TMT_SK"));
	$reqKenaikanGajiTerakhirTh= $set_detil->getField("MASA_KERJA_TAHUN");
	$reqKenaikanGajiTerakhirBl= $set_detil->getField("MASA_KERJA_BULAN");
	$reqKenaikanGajiTerakhirGajiPokok= $set_detil->getField("GAJI_POKOK");

	if($reqJenisHukumanId == 10 || $reqJenisHukumanId == 11 || $reqJenisHukumanId == 1 || $reqJenisHukumanId == 2 || $reqJenisHukumanId == 3 || $reqJenisHukumanId == 4 || $reqJenisHukumanId == 5)
	{
		$reqModeSimpan= "hukuman";
	}
	else if($reqJenisHukumanId == 9)
	{
		$reqModeSimpan= "jabatan_fungsional_umum";

		$set= new JabatanRiwayat();
		$statement= " AND A.JABATAN_RIWAYAT_ID = ".$reqJabatanRiwayatId." AND A.PEGAWAI_ID = ".$reqId;
		$set->selectByParams(array(), -1, -1, $statement);
		$set->firstRow();
	//echo $set->query;exit;
		$reqJabatanFuIsManual= $set->getField('IS_MANUAL');
		$reqJabatanFuSatkerId= $set->getField('SATKER_ID');
		$reqJabatanFuNoSk= $set->getField('NO_SK');
		$reqJabatanFuEselonId= $set->getField('ESELON_ID');
		$reqJabatanFuJabatanFuId= $set->getField('JABATAN_FU_ID');
		$reqJabatanFuNama= $set->getField('NAMA');
		$reqJabatanFuSatkerId= $set->getField('SATKER_ID');
		$reqJabatanFuSatker= $set->getField('SATUAN_KERJA_NAMA_DETIL');
		$reqJabatanFuNoPelantikan= $set->getField('NO_PELANTIKAN');
		$reqJabatanFuTunjangan= $set->getField('TUNJANGAN');
		$reqJabatanFuTglSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));
		$reqJabatanFuTmtJabatan= dateTimeToPageCheck($set->getField('TMT_JABATAN'));
		$reqJabatanFuTglPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));
		$reqJabatanFuBlnDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));
		$reqJabatanFuKeteranganBUP= $set->getField('KETERANGAN_BUP');

		$reqJabatanFuTmtWaktuJabatan= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);
		if($reqJabatanFuTmtWaktuJabatan == "" || $reqJabatanFuTmtWaktuJabatan == "00:00"){}
			else
				$reqJabatanFuCheckTmtWaktuJabatan= "1";

		}
		else if($reqJenisHukumanId == 8)
		{
			$reqModeSimpan= "jabatan_struktural";

			$set= new JabatanRiwayat();
			$statement= " AND A.JABATAN_RIWAYAT_ID = ".$reqJabatanRiwayatId." AND A.PEGAWAI_ID = ".$reqId;
			$set->selectByParams(array(), -1, -1, $statement);
			$set->firstRow();
	//echo $set->query;exit;
			$reqJabatanStrukturalIsManual= $set->getField('IS_MANUAL');
			$reqJabatanStrukturalPejabatPenetap= $set->getField('PEJABAT_PENETAP');
			$reqJabatanStrukturalPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
			$reqJabatanStrukturalSatkerId= $set->getField('SATKER_ID');
			$reqJabatanStrukturalNoSk= $set->getField('NO_SK');
			$reqJabatanStrukturalEselonId= $set->getField('ESELON_ID');
			$reqJabatanStrukturalEselonNama= $set->getField('ESELON_NAMA');
			$reqJabatanStrukturalNama= $set->getField('NAMA');
			$reqJabatanStrukturalSatkerId= $set->getField('SATKER_ID');
			$reqJabatanStrukturalSatker= $set->getField('SATUAN_KERJA_NAMA_DETIL');
			$reqJabatanStrukturalNoPelantikan= $set->getField('NO_PELANTIKAN');
			$reqJabatanStrukturalTunjangan= $set->getField('TUNJANGAN');
			$reqJabatanStrukturalTglSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));
			$reqJabatanStrukturalTmtJabatan= dateTimeToPageCheck($set->getField('TMT_JABATAN'));
			$reqJabatanStrukturalTglPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));
			$reqJabatanStrukturalBlnDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));
			$reqJabatanStrukturalKeteranganBUP= $set->getField('KETERANGAN_BUP');

			$reqJabatanStrukturalTmtWaktuJabatan= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);
			if($reqJabatanStrukturalTmtWaktuJabatan == "" || $reqJabatanStrukturalTmtWaktuJabatan == "00:00"){}
				else
					$reqJabatanStrukturalCheckTmtWaktuJabatan= "1";

			}
			else if($reqJenisHukumanId == 6 || $reqJenisHukumanId == 7)
			{
				$reqModeSimpan= "pangkat_riwayat";

				$set_detil= new Hukuman();
				$statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqPangkatRiwayatTurunId;
				$set_detil->selectByParamsPangkatDataRiwayat(array(), -1,-1, $statement);
				$set_detil->firstRow();
	  //echo $set_detil->query;exit;
				$reqKenaikanPangkatTurunKode= $set_detil->getField("PANGKAT_ID");
				$reqKenaikanPangkatTurunTh= $set_detil->getField("MASA_KERJA_TAHUN");
				$reqKenaikanPangkatTurunBl= $set_detil->getField("MASA_KERJA_BULAN");
				$reqKenaikanPangkatTurunGajiPokok= $set_detil->getField("GAJI_POKOK");

				$set_detil= new Hukuman();
				$statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqPangkatRiwayatKembaliId;
				$set_detil->selectByParamsPangkatDataRiwayat(array(), -1,-1, $statement);
				$set_detil->firstRow();
	  //echo $set_detil->query;exit;
				$reqKenaikanPangkatKembaliKode= $set_detil->getField("PANGKAT_ID");
				$reqKenaikanPangkatKembaliTh= $set_detil->getField("MASA_KERJA_TAHUN");
				$reqKenaikanPangkatKembaliBl= $set_detil->getField("MASA_KERJA_BULAN");
				$reqKenaikanPangkatKembaliGajiPokok= $set_detil->getField("GAJI_POKOK");

			}

		}



		$pejabat_penetap= new PejabatPenetap();
		$tingkat_hukuman= new TingkatHukuman();
		$jenis_hukuman= new JenisHukuman();

		$pejabat_penetap->selectByParams();
		$tingkat_hukuman->selectByParams();
		$jenis_hukuman->selectByParams(array('TINGKAT_HUKUMAN_ID'=>$reqTingkatHukumanId),-1,-1, '');

		$arrPangkat="";
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
		<html>
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
				function setInfoMasaBerlaku()
				{
					var reqTanggalSekarang= reqTanggalMulai= reqTanggalAkhir= panjangMulai= panjangAkhir= "";
					reqTanggalMulai= $("#reqTanggalMulai").val();
					reqTanggalAkhir= $("#reqTanggalAkhir").val();
					reqTanggalSekarang= "<?=date("d-m-Y")?>";

					panjangMulai= reqTanggalMulai.length;
					panjangAkhir= reqTanggalAkhir.length;

					if(panjangMulai == 10)
					{
						if(panjangAkhir == 10){}
							else
								reqTanggalAkhir= "<?=date("d-m-Y")?>";

							var dt1= parseInt(reqTanggalMulai.substring(0,2),10); 
							var mon1= parseInt(reqTanggalMulai.substring(3,5),10) - 1;
							var yr1= parseInt(reqTanggalMulai.substring(6,10),10); 
							var dt2= parseInt(reqTanggalAkhir.substring(0,2),10); 
							var mon2= parseInt(reqTanggalAkhir.substring(3,5),10) - 1; 
							var yr2= parseInt(reqTanggalAkhir.substring(6,10),10);
							var dt3= parseInt(reqTanggalSekarang.substring(0,2),10); 
							var mon3= parseInt(reqTanggalSekarang.substring(3,5),10) - 1; 
							var yr3= parseInt(reqTanggalSekarang.substring(6,10),10);

							var dateMulai= new Date(yr1, mon1, dt1);
							var dateAkhir= new Date(yr2, mon2, dt2);
							var dateSekarang= new Date(yr3, mon3, dt3);
			//alert(dateSekarang +"<="+ dateAkhir +"&&"+ dateSekarang +">="+ dateMulai);return false;
			if(dateSekarang <= dateAkhir && dateSekarang >= dateMulai)
				$("#reqBerlaku").prop('checked', true);
			else
				$("#reqBerlaku").prop('checked', false);
		}
		else
			$("#reqBerlaku").prop('checked', false);
	}
	
	function setInfoPangkatGajiTerakhirTurun()
	{
		var reqTmtSk= panjang= "";
		reqTmtSk= $("#reqTmtSk").val();
		
		panjang= reqTmtSk.length;
		//id= id.replace("reqTanggalKirimAwal", "")
		if(panjang == 10)
		{
			reqTmtSk= $("#reqTmtSk").val();
			$.ajax({'url': "hukuman_json/getpangkatriwayat/?reqId=<?=$reqId?>&reqTmtSk="+reqTmtSk,'success': function(dataJson) {
				var data= JSON.parse(dataJson);
				pangkatid= data.pangkatid;
				tempPangkat= data.pangkat;
				tempMasaKerjaTahun= data.masatahun;
				tempMasaKerjaBulan= data.masabulan;
				tempGajiPokok= data.gajipokok;
				tempTmt= data.tmt;
				
				pangkatidakhir= pangkatid;
				masakerjatahunakhir= tempMasaKerjaTahun;
				masakerjabulanakhir= tempMasaKerjaBulan;
				
				reqJenisHukumanId= $("#reqJenisHukumanId").val();
				if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
				{
					tempAwal= tempTmt;
					tempAkhir= $("#reqTmtSk").val();
					$.ajax({'url': "hukuman_json/getpangkatriwayatturun/?reqId=<?=$reqId?>&reqPangkatId="+pangkatidakhir+"&reqMasaKerjaTahun="+masakerjatahunakhir+"&reqMasaKerjaBulan="+masakerjabulanakhir+"&reqAwal="+tempAwal+"&reqAkhir="+tempAkhir+"&reqTmt="+tempTmt,'success': function(dataJson) {
						var data= JSON.parse(dataJson);
						tempPangkatId= data.pangkatid;
						tempMasaKerjaTahun= data.masatahun;
						tempMasaKerjaBulan= data.masabulan;
						tempGajiPokok= data.gajipokok;
						
						$("#reqKenaikanPangkatTurunKode").val(tempPangkatId);
						$("#reqKenaikanPangkatTurunTh").val(tempMasaKerjaTahun);
						$("#reqKenaikanPangkatTurunThLabel").val(tempMasaKerjaTahun);
						$("#reqKenaikanPangkatTurunBl").val(tempMasaKerjaBulan);
						$("#reqKenaikanPangkatTurunBlLabel").val(tempMasaKerjaBulan);
						$("#reqKenaikanPangkatTurunGajiPokok").val(tempGajiPokok);
						$("#reqKenaikanPangkatTurunGajiPokokLabel").val(tempGajiPokok);
						$("#reqKenaikanPangkatTurunKode").material_select();
						
						tempAwal= tempTmt;
						tempAkhir= $("#reqTanggalAkhir").val();
						$.ajax({'url': "hukuman_json/getpangkatriwayatkembali/?reqId=<?=$reqId?>&reqPangkatId="+pangkatidakhir+"&reqMasaKerjaTahun="+masakerjatahunakhir+"&reqMasaKerjaBulan="+masakerjabulanakhir+"&reqAwal="+tempAwal+"&reqAkhir="+tempAkhir+"&reqTmt="+tempTmt,'success': function(dataJson) {
							var data= JSON.parse(dataJson);
							tempPangkatId= data.pangkatid;
							tempMasaKerjaTahun= data.masatahun;
							tempMasaKerjaBulan= data.masabulan;
							tempGajiPokok= data.gajipokok;
							
							$("#reqKenaikanPangkatKembaliKode").val(tempPangkatId);
							$("#reqKenaikanPangkatKembaliTh").val(tempMasaKerjaTahun);
							$("#reqKenaikanPangkatKembaliThLabel").val(tempMasaKerjaTahun);
							$("#reqKenaikanPangkatKembaliBl").val(tempMasaKerjaBulan);
							$("#reqKenaikanPangkatKembaliBlLabel").val(tempMasaKerjaBulan);
							$("#reqKenaikanPangkatKembaliGajiPokok").val(tempGajiPokok);
							$("#reqKenaikanPangkatKembaliGajiPokokLabel").val(tempGajiPokok);
							$("#reqKenaikanPangkatKembaliKode").material_select();
							
						}});
						
					}});
				}

			}});
		}
		else
		{
			$("#reqKenaikanPangkatTerakhir").val("");
		}
		
	}
	
	function setInfoPangkatGajiTerakhirKembali()
	{
		var reqTmtSk= panjang= "";
		reqTmtSk= $("#reqTanggalAkhir").val();
		
		panjang= reqTmtSk.length;
		//id= id.replace("reqTanggalKirimAwal", "")
		if(panjang == 10)
		{
			reqTmtSk= $("#reqTmtSk").val();
			$.ajax({'url': "hukuman_json/getpangkatriwayat/?reqId=<?=$reqId?>&reqTmtSk="+reqTmtSk,'success': function(dataJson) {
				var data= JSON.parse(dataJson);
				pangkatid= data.pangkatid;
				tempPangkat= data.pangkat;
				tempMasaKerjaTahun= data.masatahun;
				tempMasaKerjaBulan= data.masabulan;
				tempGajiPokok= data.gajipokok;
				tempTmt= data.tmt;
				
				pangkatidakhir= pangkatid;
				masakerjatahunakhir= tempMasaKerjaTahun;
				masakerjabulanakhir= tempMasaKerjaBulan;
				
				reqJenisHukumanId= $("#reqJenisHukumanId").val();
				if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
				{
					tempAwal= tempTmt;
					tempAkhir= $("#reqTmtSk").val();
					$.ajax({'url': "hukuman_json/getpangkatriwayatturun/?reqId=<?=$reqId?>&reqPangkatId="+pangkatidakhir+"&reqMasaKerjaTahun="+masakerjatahunakhir+"&reqMasaKerjaBulan="+masakerjabulanakhir+"&reqAwal="+tempAwal+"&reqAkhir="+tempAkhir+"&reqTmt="+tempTmt,'success': function(dataJson) {
						var data= JSON.parse(dataJson);
						tempPangkatId= data.pangkatid;
						tempMasaKerjaTahun= data.masatahun;
						tempMasaKerjaBulan= data.masabulan;
						tempGajiPokok= data.gajipokok;
						
						tempAwal= tempTmt;
						tempAkhir= $("#reqTanggalAkhir").val();
						$.ajax({'url': "hukuman_json/getpangkatriwayatkembali/?reqId=<?=$reqId?>&reqPangkatId="+pangkatidakhir+"&reqMasaKerjaTahun="+masakerjatahunakhir+"&reqMasaKerjaBulan="+masakerjabulanakhir+"&reqAwal="+tempAwal+"&reqAkhir="+tempAkhir+"&reqTmt="+tempTmt,'success': function(dataJson) {
							var data= JSON.parse(dataJson);
							tempPangkatId= data.pangkatid;
							tempMasaKerjaTahun= data.masatahun;
							tempMasaKerjaBulan= data.masabulan;
							tempGajiPokok= data.gajipokok;
							
							$("#reqKenaikanPangkatKembaliKode").val(tempPangkatId);
							$("#reqKenaikanPangkatKembaliTh").val(tempMasaKerjaTahun);
							$("#reqKenaikanPangkatKembaliThLabel").val(tempMasaKerjaTahun);
							$("#reqKenaikanPangkatKembaliBl").val(tempMasaKerjaBulan);
							$("#reqKenaikanPangkatKembaliBlLabel").val(tempMasaKerjaBulan);
							$("#reqKenaikanPangkatKembaliGajiPokok").val(tempGajiPokok);
							$("#reqKenaikanPangkatKembaliGajiPokokLabel").val(tempGajiPokok);
							$("#reqKenaikanPangkatKembaliKode").material_select();
							
						}});
						
					}});
				}

			}});
		}
		else
		{
			//$("#reqKenaikanPangkatTerakhir").val("");
		}
		
	}
	
	function setInfoPangkatGajiTerakhir()
	{
		var reqTmtSk= panjang= "";
		reqTmtSk= $("#reqTmtSk").val();
		
		panjang= reqTmtSk.length;
		//id= id.replace("reqTanggalKirimAwal", "")
		if(panjang == 10)
		{
			$.ajax({'url': "hukuman_json/getpangkatriwayat/?reqId=<?=$reqId?>&reqTmtSk="+reqTmtSk,'success': function(dataJson) {
				var data= JSON.parse(dataJson);
				pangkatriwayatid= data.pangkatriwayatid;
				pangkatid= data.pangkatid;
				tempPangkat= data.pangkat;
				tempMasaKerjaTahun= data.masatahun;
				tempMasaKerjaBulan= data.masabulan;
				tempGajiPokok= data.gajipokok;
				tempTmt= data.tmt;
				
				pangkatidakhir= pangkatid;
				masakerjatahunakhir= tempMasaKerjaTahun;
				masakerjabulanakhir= tempMasaKerjaBulan;
				
				$("#reqPangkatRiwayatTerakhirId").val(pangkatriwayatid);
				$("#reqKenaikanPangkatTerakhir").val(tempPangkat);
				$("#reqKenaikanPangkatTerakhirTh").val(tempMasaKerjaTahun);
				$("#reqKenaikanPangkatTerakhirBl").val(tempMasaKerjaBulan);
				$("#reqKenaikanPangkatTerakhirGajiPokok").val(tempGajiPokok);
				
				reqJenisHukumanId= $("#reqJenisHukumanId").val();
				if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
				{
					/*tempAwal= tempTmt;
					tempAkhir= $("#reqTmtSk").val();
					$.ajax({'url': "hukuman_json/getpangkatriwayatturun/?reqId=<?=$reqId?>&reqPangkatId="+pangkatidakhir+"&reqMasaKerjaTahun="+masakerjatahunakhir+"&reqMasaKerjaBulan="+masakerjabulanakhir+"&reqAwal="+tempAwal+"&reqAkhir="+tempAkhir+"&reqTmt="+tempTmt,'success': function(dataJson) {
						var data= JSON.parse(dataJson);
						tempPangkatId= data.pangkatid;
						tempMasaKerjaTahun= data.masatahun;
						tempMasaKerjaBulan= data.masabulan;
						tempGajiPokok= data.gajipokok;
						
						$("#reqKenaikanPangkatTurunKode").val(tempPangkatId);
						$("#reqKenaikanPangkatTurunTh").val(tempMasaKerjaTahun);
						$("#reqKenaikanPangkatTurunThLabel").val(tempMasaKerjaTahun);
						$("#reqKenaikanPangkatTurunBl").val(tempMasaKerjaBulan);
						$("#reqKenaikanPangkatTurunBlLabel").val(tempMasaKerjaBulan);
						$("#reqKenaikanPangkatTurunGajiPokok").val(tempGajiPokok);
						$("#reqKenaikanPangkatTurunGajiPokokLabel").val(tempGajiPokok);
						$("#reqKenaikanPangkatTurunKode").material_select();
						
						tempAwal= tempTmt;
						tempAkhir= $("#reqTanggalAkhir").val();
						$.ajax({'url': "hukuman_json/getpangkatriwayatkembali/?reqId=<?=$reqId?>&reqPangkatId="+pangkatidakhir+"&reqMasaKerjaTahun="+masakerjatahunakhir+"&reqMasaKerjaBulan="+masakerjabulanakhir+"&reqAwal="+tempAwal+"&reqAkhir="+tempAkhir+"&reqTmt="+tempTmt,'success': function(dataJson) {
							var data= JSON.parse(dataJson);
							tempPangkatId= data.pangkatid;
							tempMasaKerjaTahun= data.masatahun;
							tempMasaKerjaBulan= data.masabulan;
							tempGajiPokok= data.gajipokok;
							
							$("#reqKenaikanPangkatKembaliKode").val(tempPangkatId);
							$("#reqKenaikanPangkatKembaliTh").val(tempMasaKerjaTahun);
							$("#reqKenaikanPangkatKembaliThLabel").val(tempMasaKerjaTahun);
							$("#reqKenaikanPangkatKembaliBl").val(tempMasaKerjaBulan);
							$("#reqKenaikanPangkatKembaliBlLabel").val(tempMasaKerjaBulan);
							$("#reqKenaikanPangkatKembaliGajiPokok").val(tempGajiPokok);
							$("#reqKenaikanPangkatKembaliGajiPokokLabel").val(tempGajiPokok);
							$("#reqKenaikanPangkatKembaliKode").material_select();
							
						}});
						
					}});*/
				}

			}});
		}
		else
		{
			$("#reqKenaikanPangkatTerakhir").val("");
		}
		
		if(panjang == 10)
		{
			$.ajax({'url': "hukuman_json/getgajiriwayat/?reqId=<?=$reqId?>&reqTmtSk="+reqTmtSk,'success': function(dataJson) {
				var data= JSON.parse(dataJson);
				gajiriwayatid= data.gajiriwayatid;
				tempPangkat= data.pangkat;
				tempMasaKerjaTahun= data.masatahun;
				tempMasaKerjaBulan= data.masabulan;
				tempGajiPokok= data.gajipokok;
				
				$("#reqGajiRiwayatTerakhirId").val(gajiriwayatid);
				$("#reqKenaikanGajiTerakhir").val(tempPangkat);
				$("#reqKenaikanGajiTerakhirTh").val(tempMasaKerjaTahun);
				$("#reqKenaikanGajiTerakhirBl").val(tempMasaKerjaBulan);
				$("#reqKenaikanGajiTerakhirGajiPokok").val(tempGajiPokok);
			}});
		}
		else
		{
			$("#reqKenaikanGajiTerakhir").val("");
		}
		
	}
	
	function setDisplayPangkatInfo()
	{
		reqJenisHukumanId= reqModeSimpan= "";
		reqJenisHukumanId= $("#reqJenisHukumanId").val();
		
		$("#labelpangkatinfo").hide();
		
		$('#reqTanggalPemulihan,#reqKenaikanPangkatBerikutnya,#reqKenaikanGajiBerikutnya,#reqTanggalAkhir').validatebox({required: false});
		$('#reqTanggalPemulihan,#reqKenaikanPangkatBerikutnya,#reqKenaikanGajiBerikutnya,#reqTanggalAkhir').removeClass('validatebox-invalid');
		if(reqJenisHukumanId == 10 || reqJenisHukumanId == 11 || reqJenisHukumanId == ""){}
			else
			{
				$("#labelpangkatinfo").show();
				$('#reqTanggalPemulihan,#reqKenaikanPangkatBerikutnya,#reqKenaikanGajiBerikutnya,#reqTanggalAkhir').validatebox({required: true});
			}

		//reqModeSimpan
		reqModeSimpan= infodetil= "";
		if(reqJenisHukumanId == 10 || reqJenisHukumanId == 11 || reqJenisHukumanId == 1 || reqJenisHukumanId == 2 || reqJenisHukumanId == 3 || reqJenisHukumanId == 4 || reqJenisHukumanId == 5)
		{
			reqModeSimpan= "hukuman";
		}
		else if(reqJenisHukumanId == 9)
		{
			reqModeSimpan= "jabatan_fungsional_umum";
			infodetil= "pegawai_add_hukuman_jabatan_fungsional_umum";
		}
		else if(reqJenisHukumanId == 8)
		{
			reqModeSimpan= "jabatan_struktural";
			infodetil= "pegawai_add_hukuman_jabatan_struktural";
		}
		else if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
		{
			reqModeSimpan= "pangkat_riwayat";
			infodetil= "pegawai_add_hukuman_pangkat_turun";
		}
		
		$('#labeldetilinfo').empty();
		if(infodetil == "")
		{
			parent.iframeLoaded();
		}
		else
		{
			$.ajax({'url': "app/loadUrl/app/"+infodetil+"/?reqId=<?=$reqId?>",'success': function(datahtml) {
				$('#labeldetilinfo').append(datahtml);
				parent.iframeLoaded();
				
				if(reqJenisHukumanId == 9)
				{
					$('#reqJabatanFu,#reqJabatanFuTmtJabatan,#reqJabatanFuSatkerNama').validatebox({required: true});
				}
				else if(reqJenisHukumanId == 8)
				{
					$('#reqJabatanStrukturalNama,#reqJabatanStrukturalTmtJabatan,#reqJabatanStrukturalSatker,#reqJabatanStrukturalNoPelantikan,#reqJabatanStrukturalTglPelantikan').validatebox({required: true});
				}
				else if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
				{
					//infodetil= "pegawai_add_hukuman_pangkat_turun";
				}
		
				
			}});
		}
		
		$("#reqModeSimpan").val(reqModeSimpan);
		
	}
	
	$(function(){
		<?
	    if($reqRowId == "")
	    {
		?>
		setDisplayPangkatInfo();
		<?
	    }
		?>

		<?
		if($reqJenisHukumanId == 11 || $reqJenisHukumanId == 12 || $reqJenisHukumanId == ""){}
			else
			{
				?>
		  //setInfoPangkatGajiTerakhir();
		  <?
		}
		?>

		<?
		if($reqJenisHukumanId == 6 || $reqJenisHukumanId == 7)
		{
			if($reqId == "")
			{
				?>
		  //setInfoPangkatGajiTerakhirKembali();
		  <?
		}
	}
	?>

	$('#ff').form({
		url:'hukuman_json/add',
		onSubmit:function(){
			var reqJenisHukumanId= "";
			reqJenisHukumanId= $("#reqJenisHukumanId").val();
			
			if(reqJenisHukumanId == "")
			{
				mbox.alert("Lengkapi data jenis hukuman terlebih dahulu", {open_speed: 0});
				return false;
			}

			if($(this).form('validate')){}
				else
				{
					$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
					return false;
				}
			},
			success:function(data){
            // alert(data);return false;
            data = data.split("-");
            rowid= data[0];
            infodata= data[1];

            if(rowid == "xxx")
            {
            	mbox.alert(infodata, {open_speed: 0});
            }
            else
            {
            	mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
            	{
					clearInterval(interval);
            		mbox.close();
            		document.location.href= "app/loadUrl/app/pegawai_add_hukuman_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
            	}, 1000));
				$(".mbox > .right-align").css({"display": "none"});
            }
        }
    });

	$("#reqTanggalMulai, #reqTanggalAkhir").keyup(function(){
		setInfoMasaBerlaku();
	});

	$("#reqTmtSk").keyup(function(){
		var tempVal= $(this).val();
		var reqJenisHukumanId= "";
		reqJenisHukumanId= $("#reqJenisHukumanId").val();
		
		if(reqJenisHukumanId == 11 || reqJenisHukumanId == 12 || reqJenisHukumanId == ""){}
			else
			{
				$("#reqTanggalMulaiInfo, #reqTanggalMulai").val(tempVal);
				setInfoPangkatGajiTerakhir();
				setInfoMasaBerlaku();
			}
			
			if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
			{
				setInfoPangkatGajiTerakhirTurun();
			}
			
		});

	$("#reqTanggalAkhir").keyup(function(){
		var tempVal= $(this).val();
		var reqJenisHukumanId= "";
		reqJenisHukumanId= $("#reqJenisHukumanId").val();
		
		if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
		{
			setInfoPangkatGajiTerakhirKembali();
		}
	});

	$("#reqJenisHukumanId").change(function() { 
		setDisplayPangkatInfo();

		reqJenisHukumanId= $("#reqJenisHukumanId").val();
		if(reqJenisHukumanId == 6 || reqJenisHukumanId == 7)
		{
			setInfoPangkatGajiTerakhirTurun();
		}
	});

	$("#reqTingkatHukumanId").change(function() { 
		var reqTingkatHukumanId= "";
		reqTingkatHukumanId= $("#reqTingkatHukumanId").val();
		$("#reqJenisHukumanId option").remove();
		$("#reqJenisHukumanId").material_select();

		$("<option value=''></option>").appendTo("#reqJenisHukumanId");
		$.ajax({'url': "jenis_hukuman_json/combo/?reqId="+reqTingkatHukumanId,'success': function(dataJson) {
			var data= JSON.parse(dataJson);

			var items = "";
			items += "<option></option>";
			$.each(data, function (i, SingleElement) {
				items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
			});
			$("#reqJenisHukumanId").html(items);
			$("#reqJenisHukumanId").material_select();
		}});
	});


	$('input[id^="reqPejabatPenetap"]').each(function(){
		$(this).autocomplete({
			source:function(request, response){
				var id= this.element.attr('id');
				var replaceAnakId= replaceAnak= urlAjax= "";

				if (id.indexOf('reqPejabatPenetap') !== -1)
				{
					var element= id.split('reqPejabatPenetap');
					var indexId= "reqPejabatPenetapId"+element[1];
					urlAjax= "pejabat_penetap_json/combo";
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
				if (id.indexOf('reqPejabatPenetap') !== -1)
				{
					var element= id.split('reqPejabatPenetap');
					var indexId= "reqPejabatPenetapId"+element[1];
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


});
</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<body>    
	<!--Basic Form-->
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">

				<ul class="collection card">
					<li class="collection-item ubah-color-warna">EDIT HUKUMAN</li>
					<li class="collection-item">
						<div class="row">
							<form id="ff" method="post" enctype="multipart/form-data">

								<div class="row">
									<div class="input-field col s12 m6">
										<?
										if($reqRowId == "")
										{
											?>
											<select <?=$disabled?> name="reqTingkatHukumanId" id="reqTingkatHukumanId">
												<option value=""></option>
												<? 
												while($tingkat_hukuman->nextRow())
												{
													?>
													<option value="<?=$tingkat_hukuman->getField('TINGKAT_HUKUMAN_ID')?>" <? if($tingkat_hukuman->getField('TINGKAT_HUKUMAN_ID')==$reqTingkatHukumanId) echo 'selected'?>><?=$tingkat_hukuman->getField('NAMA')?></option>
													<? 
												}
												?>
											</select>
											<label for="reqTingkatHukumanId">Tingkat Hukuman</label>
											<?
										}
										else
										{
											?>
											<label for="reqTingkatHukumanNama">Tingkat Hukuman</label>
											<input type="hidden" name="reqTingkatHukumanId" id="reqTingkatHukumanId"  value="<?=$reqTingkatHukumanId?>" />
											<input type="text" id="reqTingkatHukumanNama" disabled value="<?=$reqTingkatHukumanNama?> " />
											<?
										}
										?>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 ">
										<? 
										if($reqRowId == "")
										{ 
											?>
											<select <?=$disabled?> name="reqJenisHukumanId" id="reqJenisHukumanId">
												<option value=""></option>
												<? 
												while($jenis_hukuman->nextRow()) { 
													?>
													<option value="<?=$jenis_hukuman->getField('JENIS_HUKUMAN_ID')?>" <? if($jenis_hukuman->getField('JENIS_HUKUMAN_ID')==$reqJenisHukumanId) echo 'selected'?>><?=$jenis_hukuman->getField('NAMA')?></option>
													<?  
												} 
												?>
											</select>
											<label for="reqJenisHukumanId">Jenis Hukuman</label>
											<? 
										}
										else
										{ 
											?>
											<label for="reqTingkatHukumanNama">Jenis Hukuman</label>
											<input type="hidden" name="reqJenisHukumanId" id="reqJenisHukumanId"  value="<?=$reqJenisHukumanId?>" />
											<input type="text" id="reqJenisHukumanNama" disabled value="<?=$reqJenisHukumanNama?> " />
											<? 
										} 
										?>
									</div>
								</div>

              <?php /*?><div class="row">
                <div class="input-field col s12 ">
                  <label for="reqPeraturan">Peraturan ID</label>
                  <input type="text" name="reqPeraturan" id="reqPeraturan" maxlength="10" value="<?=$reqPeraturan?>" />
                </div>
            </div><?php */?>

            <div class="row">
            	<div class="input-field col s12 m6">
            		<label for="reqNoSk">No. SK</label>
            		<input placeholder type="text" required class="easyui-validatebox" name="reqNoSk" id="reqNoSk" value="<?=$reqNoSk?>" title="No SK harus diisi" />
            	</div>
            	<div class="input-field col s12 m3">
            		<label for="reqTanggalSk">Tanggal SK</label>
            		<input placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSk" id="reqTanggalSk"  value="<?=$reqTanggalSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSk');"/>
            	</div>
            	<div class="input-field col s12 m3">
            		<label for="reqTmtSk">TMT SK</label>
            		<input placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtSk" id="reqTmtSk"  value="<?=$reqTmtSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtSk');"/>
            	</div>
            </div>

            <div class="row">
            	<div class="input-field col s12 ">
            		<label for="reqPermasalahan">Permasalahan</label>
            		<textarea class="easyui-validatebox materialize-textarea" required <?=$disabled?> name="reqPermasalahan" id="reqPermasalahan"><?=$reqPermasalahan?></textarea>
            	</div>
            </div>

            <div class="row">
            	<div class="input-field col s12 ">
            		<label for="reqPejabatPenetap" >Pejabat Penetap</label>
            		<input type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
            		<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
            	</div>
            </div>

            <div id="labelpangkatinfo">
            	<div class="row">
            		<div class="input-field col s12 m3">
            			<label for="reqTanggalMulaiInfo">Tanggal Awal</label>
            			<input type="hidden" name="reqTanggalMulai" id="reqTanggalMulai"  value="<?=$reqTanggalMulai?>" />
            			<input type="text" id="reqTanggalMulaiInfo" disabled value="<?=$reqTanggalMulai?> " />
            		</div>
            		<div class="input-field col s12 m3">
            			<label for="reqTanggalAkhir">Tanggal Akhir</label>
            			<input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir"  value="<?=$reqTanggalAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');"/>
            		</div>

            		<div class="input-field col s12 m3">
            			<label for="reqTanggalPemulihan">Akhir Masa Pemulihan Hukdis</label>
            			<input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPemulihan" id="reqTanggalPemulihan"  value="<?=$reqTanggalPemulihan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPemulihan');"/>
            		</div>
            		<div class="input-field col s12 m2">
            			<input type="checkbox" disabled name="reqBerlaku" id="reqBerlaku" value="1" <? if($reqBerlaku == 1) echo 'checked'?>/>
            			<label for="reqBerlaku">Masih Berlaku</label>
            		</div>
            	</div>

            	<div class="row">
            		<div class="input-field col s12 m3">
            			<label for="reqKenaikanPangkatTerakhir">Pangkat Terakhir</label>
            			<input placeholder type="text" disabled class="easyui-validatebox" name="reqKenaikanPangkatTerakhir" id="reqKenaikanPangkatTerakhir" value="<?=$reqKenaikanPangkatTerakhir?> " />
            		</div>
            		<div class="input-field col s6 m2">
            			<label for="reqKenaikanPangkatTerakhirTh">MK Tahun</label>
            			<input placeholder type="text" disabled class="easyui-validatebox" name="reqKenaikanPangkatTerakhirTh" id="reqKenaikanPangkatTerakhirTh" value="<?=$reqKenaikanPangkatTerakhirTh?> " />
            		</div>
            		<div class="input-field col s6 m2">
            			<label for="reqKenaikanPangkatTerakhirBl">MK Bulan</label>
            			<input placeholder type="text" class="easyui-validatebox" disabled name="reqKenaikanPangkatTerakhirBl" id="reqKenaikanPangkatTerakhirBl" value="<?=$reqKenaikanPangkatTerakhirBl?> " />
            		</div>
            		<div class="input-field col s12 m2">
            			<label for="reqKenaikanPangkatTerakhirGajiPokok">Gaji Pokok</label>
            			<input placeholder type="text" class="easyui-validatebox" disabled name="reqKenaikanPangkatTerakhirGajiPokok" id="reqKenaikanPangkatTerakhirGajiPokok" value="<?=numberToIna($reqKenaikanPangkatTerakhirGajiPokok)?> " />
            		</div>
            		<div class="input-field col s12 m3">
            			<label for="reqKenaikanPangkatBerikutnya">Kenaikan Pangkat Berikutnya</label>
            			<input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqKenaikanPangkatBerikutnya" id="reqKenaikanPangkatBerikutnya"  value="<?=$reqKenaikanPangkatBerikutnya?>" maxlength="10" onKeyDown="return format_date(event,'reqKenaikanPangkatBerikutnya');"/>
            		</div>
            	</div>

            	<div class="row">
            		<div class="input-field col s12 m3">
            			<label for="reqKenaikanGajiTerakhir">Kenaikan Gaji Terakhir</label>
            			<input placeholder type="text" disabled class="easyui-validatebox" name="reqKenaikanGajiTerakhir" id="reqKenaikanGajiTerakhir" value="<?=$reqKenaikanGajiTerakhir?> " />
            		</div>
            		<div class="input-field col s6 m2">
            			<label for="reqKenaikanGajiTerakhirTh">MK Tahun</label>
            			<input placeholder type="text" disabled class="easyui-validatebox" name="reqKenaikanGajiTerakhirTh" id="reqKenaikanGajiTerakhirTh" value="<?=$reqKenaikanGajiTerakhirTh?> " />
            		</div>
            		<div class="input-field col s6 m2">
            			<label for="reqKenaikanGajiTerakhirBl">MK Bulan</label>
            			<input placeholder type="text" class="easyui-validatebox" disabled name="reqKenaikanGajiTerakhirBl" id="reqKenaikanGajiTerakhirBl" value="<?=$reqKenaikanGajiTerakhirBl?> " />
            		</div>
            		<div class="input-field col s12 m2">
            			<label for="reqKenaikanGajiTerakhirGajiPokok">Gaji Pokok</label>
            			<input placeholder type="text" class="easyui-validatebox" disabled name="reqKenaikanGajiTerakhirGajiPokok" id="reqKenaikanGajiTerakhirGajiPokok" value="<?=numberToIna($reqKenaikanGajiTerakhirGajiPokok)?> " />
            		</div>
            		<div class="input-field col s12 m3">
            			<label for="reqKenaikanGajiBerikutnya">Kenaikan Gaji Berikutnya</label>
            			<input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqKenaikanGajiBerikutnya" id="reqKenaikanGajiBerikutnya"  value="<?=$reqKenaikanGajiBerikutnya?>" maxlength="10" onKeyDown="return format_date(event,'reqKenaikanGajiBerikutnya');"/>
            		</div>
            	</div>
            </div>

            <div id="labeldetilinfo">
            	<?
            	if($reqRowId == ""){}
            		else
            		{
            			if($reqModeSimpan == "jabatan_fungsional_umum")
            			{
            				?>
            				<div class="row">
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanFu">Nama Jabatan</label>
            						<input type="hidden" name="reqJabatanFuId" id="reqJabatanFuId" value="<?=$reqJabatanFuId?>" /> 
            						<input type="text" id="reqJabatanFu" name="reqJabatanFuNama" <?=$read?> value="<?=$reqJabatanFuNama?>" class="easyui-validatebox" required />
            					</div>
            					<div class="input-field col s12 m1">
            						<input type="checkbox" id="reqJabatanFuCheckTmtWaktuJabatan" name="reqJabatanFuCheckTmtWaktuJabatan" value="1" <? if($reqJabatanFuCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
            						<label for="reqJabatanFuCheckTmtWaktuJabatan"></label>
            					</div>
            					<div class="input-field col s12 m3">
            						<label for="reqJabatanFuTmtJabatan">TMT Jabatan</label>
            						<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanFuTmtJabatan" id="reqJabatanFuTmtJabatan"  value="<?=$reqJabatanFuTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanFuTmtJabatan');"/>
            					</div>
            					<div class="input-field col s12 m2" id="reqJabatanFuInfoCheckTmtWaktuJabatan">
            						<input placeholder="00:00" id="reqJabatanFuTmtWaktuJabatan" name="reqJabatanFuTmtWaktuJabatan" type="text" class="" value="<?=$reqJabatanFuTmtWaktuJabatan?>" />
            						<label for="reqJabatanFuTmtWaktuJabatan">Time</label>
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12">
            						<input type="checkbox" id="reqJabatanFuIsManual" name="reqJabatanFuIsManual" value="1" <? if($reqJabatanFuIsManual == 1) echo 'checked'?> />
            						<label for="reqJabatanFuIsManual"></label>
            						*centang jika satker luar kab jombang / satker sebelum tahun 2012
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12 m12">
            						<label for="reqJabatanFuSatkerNama">Satuan Kerja</label>
            						<input type="text" id="reqJabatanFuSatkerNama"  name="reqJabatanFuSatker" <?=$read?> value="<?=$reqJabatanFuSatker?>" class="easyui-validatebox" required />
            						<input type="hidden" name="reqJabatanFuSatkerId" id="reqJabatanFuSatkerNamaId" value="<?=$reqJabatanFuSatkerId?>" />
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanFuTunjangan">Tunjangan</label>
            						<input type="text" id="reqJabatanFuTunjangan" name="reqJabatanFuTunjangan" OnFocus="FormatAngka('reqJabatanFuTunjangan')" OnKeyUp="FormatUang('reqJabatanFuTunjangan')" OnBlur="FormatUang('reqJabatanFuTunjangan')" value="<?=numberToIna($reqJabatanFuTunjangan)?>" />
            					</div>
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanFuBlnDibayar">Bln. Dibayar</label>
            						<input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanFuBlnDibayar" id="reqJabatanFuBlnDibayar"  value="<?=$reqJabatanFuBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanFuBlnDibayar');"/>
            					</div>
            				</div>
            				<?
            			}
            			elseif($reqModeSimpan == "jabatan_struktural")
            			{
            				?>
            				<div class="row">
            					<div class="input-field col s12">
            						<input type="checkbox" id="reqJabatanStrukturalIsManual" name="reqJabatanStrukturalIsManual" value="1" <? if($reqJabatanStrukturalIsManual == 1) echo 'checked'?> />
            						<label for="reqJabatanStrukturalIsManual"></label>
            						*centang jika jabatan luar kab jombang / jabatan sebelum tahun 2012
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanStrukturalNama">Nama Jabatan</label>
            						<input type="text" id="reqJabatanStrukturalNama"  name="reqJabatanStrukturalNama" <?=$read?> value="<?=$reqJabatanStrukturalNama?>" class="easyui-validatebox" required />
            					</div>
            					<div class="input-field col s12 m1">
            						<input type="checkbox" id="reqJabatanStrukturalCheckTmtWaktuJabatan" name="reqJabatanStrukturalCheckTmtWaktuJabatan" value="1" <? if($reqJabatanStrukturalCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
            						<label for="reqJabatanStrukturalCheckTmtWaktuJabatan"></label>
            					</div>
            					<div class="input-field col s12 m3">
            						<label for="reqJabatanStrukturalTmtJabatan">TMT Jabatan</label>
            						<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanStrukturalTmtJabatan" id="reqJabatanStrukturalTmtJabatan"  value="<?=$reqJabatanStrukturalTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanStrukturalTmtJabatan');"/>
            					</div>
            					<div class="input-field col s12 m2" id="reqJabatanStrukturalInfoCheckTmtWaktuJabatan">
            						<input placeholder="00:00" id="reqJabatanStrukturalTmtWaktuJabatan" name="reqJabatanStrukturalTmtWaktuJabatan" type="text" class="" value="<?=$reqJabatanStrukturalTmtWaktuJabatan?>" />
            						<label for="reqJabatanStrukturalTmtWaktuJabatan">Time</label>
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12 m12">
            						<label for="reqJabatanStrukturalSatker">Satuan Kerja</label>
            						<input type="hidden" name="reqJabatanStrukturalSatkerId" id="reqJabatanStrukturalSatkerId" value="<?=$reqJabatanStrukturalSatkerId?>" />
            						<input type="text" id="reqJabatanStrukturalSatker" name="reqJabatanStrukturalSatker" <?=$read?> value="<?=$reqJabatanStrukturalSatker?>" class="easyui-validatebox" required readonly />
            					</div>
            				</div>

            				<div class="row">
            					<input type="hidden" name="reqJabatanStrukturalEselonId" id="reqJabatanStrukturalEselonId" value="<?=$reqJabatanStrukturalEselonId?>" />
            					<div class="input-field col s12 m6" id="reqJabatanStrukturalinfoeselontext">
            						<label for="reqJabatanStrukturalEselonText">Eselon</label>
            						<input type="text" id="reqJabatanStrukturalEselonText" value="<?=$reqJabatanStrukturalEselonNama?>" disabled />
            					</div>

            					<div class="input-field col s12 m6" id="reqJabatanStrukturalinfoeselontext">
            						<select id="reqJabatanStrukturalSelectEselonId">
            							<option value=""></option>
            							<?
            							while($eselon->nextRow())
            							{
            								?>
            								<option value="<?=$eselon->getField("ESELON_ID")?>" <? if($eselon->getField("ESELON_ID") == $reqJabatanStrukturalEselonId) echo "selected"?>><?=$eselon->getField("NAMA")?></option>
            								<?
            							}
            							?>
            						</select>
            						<label for="reqJabatanStrukturalEselonId">Eselon</label>
            					</div>

            				</div>

            				<div class="row">
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanStrukturalNoPelantikan">No. Pelantikan</label>
            						<input class="easyui-validatebox" required type="text" id="reqJabatanStrukturalNoPelantikan" name="reqJabatanStrukturalNoPelantikan" <?=$disabled?> value="<?=$reqJabatanStrukturalNoPelantikan?>" />
            					</div>
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanStrukturalTglPelantikan">Tgl. Pelantikan</label>
            						<input class="easyui-validatebox" required data-options="validType:'dateValidPicker'" type="text" name="reqJabatanStrukturalTglPelantikan" id="reqJabatanStrukturalTglPelantikan"  value="<?=$reqJabatanStrukturalTglPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanStrukturalTglPelantikan');"/>
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanStrukturalTunjangan">Tunjangan</label>
            						<input type="text" id="reqJabatanStrukturalTunjangan" name="reqJabatanStrukturalTunjangan" OnFocus="FormatAngka('reqJabatanStrukturalTunjangan')" OnKeyUp="FormatUang('reqJabatanStrukturalTunjangan')" OnBlur="FormatUang('reqJabatanStrukturalTunjangan')" value="<?=numberToIna($reqJabatanStrukturalTunjangan)?>" />
            					</div>
            					<div class="input-field col s12 m6">
            						<label for="reqJabatanStrukturalBlnDibayar">Bln. Dibayar</label>
            						<input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqJabatanStrukturalBlnDibayar" id="reqJabatanStrukturalBlnDibayar"  value="<?=$reqJabatanStrukturalBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqJabatanStrukturalBlnDibayar');"/>
            					</div>
            				</div>

            				<?
            			}
            			elseif($reqModeSimpan == "pangkat_riwayat")
            			{
            				?>
            				<div class="row">
            					<div class="input-field col s12 m3">
                                	<label for="reqKenaikanPangkatTurunKode">Pangkat Satu Tingkat dibawah per TMT</label>
            						<select name="reqKenaikanPangkatTurunKode" id="reqKenaikanPangkatTurunKode" >
            							<option value=""></option>
            							<? 
            							for($i=0; $i<$jumlah_pangkat; $i++)
            							{
            								?>
            								<option value="<?=$arrPangkat[$i]["PANGKAT_ID"]?>" <? if($reqKenaikanPangkatTurunKode == $arrPangkat[$i]["PANGKAT_ID"]) echo 'selected';?>><?=$arrPangkat[$i]["KODE"]?></option>
            								<? 
            							}
            							?>
            						</select>
            					</div>
            					<div class="input-field col s6 m2">
            						<label for="reqKenaikanPangkatTurunTh">MK Tahun</label>
            						<input type="hidden" name="reqKenaikanPangkatTurunTh" id="reqKenaikanPangkatTurunTh" value="<?=$reqKenaikanPangkatTurunTh?>" />
            						<input type="text" disabled id="reqKenaikanPangkatTurunThLabel" value="<?=$reqKenaikanPangkatTurunTh?> " />
            					</div>
            					<div class="input-field col s6 m2">
            						<label for="reqKenaikanPangkatTurunBl">MK Bulan</label>
            						<input type="hidden" name="reqKenaikanPangkatTurunBl" id="reqKenaikanPangkatTurunBl" value="<?=$reqKenaikanPangkatTurunBl?>" />
            						<input type="text" disabled id="reqKenaikanPangkatTurunBlLabel" value="<?=$reqKenaikanPangkatTurunBl?> " />
            					</div>
            					<div class="input-field col s12 m2">
            						<label for="reqKenaikanPangkatTurunGajiPokok">Gaji Pokok</label>
            						<input type="hidden" name="reqKenaikanPangkatTurunGajiPokok" id="reqKenaikanPangkatTurunGajiPokok" value="<?=numberToIna($reqKenaikanPangkatTurunGajiPokok)?> " />
            						<input type="text" disabled id="reqKenaikanPangkatTurunGajiPokokLabel" value="<?=numberToIna($reqKenaikanPangkatTurunGajiPokok)?> " />
            					</div>
            				</div>

            				<div class="row">
            					<div class="input-field col s12 m3">
                                	<label for="reqKenaikanPangkatKembaliKode">Pangkat Setelah Tgl Akhir</label>
            						<select name="reqKenaikanPangkatKembaliKode" id="reqKenaikanPangkatKembaliKode" >
            							<option value=""></option>
            							<? 
            							for($i=0; $i<$jumlah_pangkat; $i++)
            							{
            								?>
            								<option value="<?=$arrPangkat[$i]["PANGKAT_ID"]?>" <? if($reqKenaikanPangkatKembaliKode == $arrPangkat[$i]["PANGKAT_ID"]) echo 'selected';?>><?=$arrPangkat[$i]["KODE"]?></option>
            								<? 
            							}
            							?>
            						</select>
            					</div>
            					<div class="input-field col s6 m2">
            						<label for="reqKenaikanPangkatKembaliTh">MK Tahun</label>
            						<input type="hidden" name="reqKenaikanPangkatKembaliTh" id="reqKenaikanPangkatKembaliTh" value="<?=$reqKenaikanPangkatKembaliTh?>" />
            						<input type="text" disabled id="reqKenaikanPangkatKembaliThLabel" value="<?=$reqKenaikanPangkatKembaliTh?> " />
            					</div>
            					<div class="input-field col s6 m2">
            						<label for="reqKenaikanPangkatKembaliBl">MK Bulan</label>
            						<input type="hidden" name="reqKenaikanPangkatKembaliBl" id="reqKenaikanPangkatKembaliBl" value="<?=$reqKenaikanPangkatKembaliBl?>" />
            						<input type="text" disabled id="reqKenaikanPangkatKembaliBlLabel" value="<?=$reqKenaikanPangkatKembaliBl?> " />
            					</div>
            					<div class="input-field col s12 m2">
            						<label for="reqKenaikanPangkatKembaliGajiPokok">Gaji Pokok</label>
            						<input type="hidden" name="reqKenaikanPangkatKembaliGajiPokok" id="reqKenaikanPangkatKembaliGajiPokok" value="<?=numberToIna($reqKenaikanPangkatKembaliGajiPokok)?> " />
            						<input type="text" disabled id="reqKenaikanPangkatKembaliGajiPokokLabel" value="<?=numberToIna($reqKenaikanPangkatKembaliGajiPokok)?> " />
            					</div>
            				</div>
            				<?
            			}
            		}
            		?>
            	</div>

            	<div class="row">
            		<div class="input-field col s12">
            			<button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
            				<i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
            			</button>

            			<script type="text/javascript">
            				$("#kembali").click(function() { 
            					document.location.href = "app/loadUrl/app/pegawai_add_hukuman_monitoring?reqId=<?=$reqId?>";
            				});
            			</script>

            			<input type="hidden" name="reqModeSimpan" id="reqModeSimpan" value="<?=$reqModeSimpan?>" />
            			<input type="hidden" name="reqJabatanRiwayatId" id="reqJabatanRiwayatId" value="<?=$reqJabatanRiwayatId?>" />
            			<input type="hidden" name="reqGajiRiwayatTerakhirId" id="reqGajiRiwayatTerakhirId" value="<?=$reqGajiRiwayatTerakhirId?>" />
            			<input type="hidden" name="reqPangkatRiwayatTerakhirId" id="reqPangkatRiwayatTerakhirId" value="<?=$reqPangkatRiwayatTerakhirId?>" />
            			<input type="hidden" name="reqPangkatRiwayatTurunId" id="reqPangkatRiwayatTurunId" value="<?=$reqPangkatRiwayatTurunId?>" />
            			<input type="hidden" name="reqGajiRiwayatTurunId" id="reqGajiRiwayatTurunId" value="<?=$reqGajiRiwayatTurunId?>" />
            			<input type="hidden" name="reqPangkatRiwayatKembaliId" id="reqPangkatRiwayatKembaliId" value="<?=$reqPangkatRiwayatKembaliId?>" />
            			<input type="hidden" name="reqGajiRiwayatKembaliId" id="reqJabatanRiwayatId" value="<?=$reqGajiRiwayatKembaliId?>" />
            			<input type="hidden" name="reqPegawaiStatusId" id="reqPegawaiStatusId" value="<?=$reqPegawaiStatusId?>" />

            			<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
            			<input type="hidden" name="reqId" value="<?=$reqId?>" />
            			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

            			<?
	                    // A;R;D
	                    if($tempAksesMenu == "A")
	                    {
	                    ?>

            			<?
            			if($reqRowId == "")
            			{
            				?>
            				<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
            					<i class="mdi-content-save left hide-on-small-only"></i>
            				</button>
            				<?
            			}
            			else
            			{
            				if($set->getField('STATUS') ==1 ){}
            					else
            					{
            						?>
            						<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
            							<i class="mdi-content-save left hide-on-small-only"></i>
            						</button>
            						<?
            					}
            				}
            				?>
            			<?
            			}
            			?>
            			</div>
            		</div>
            	</form>
            </div>
        </li>
    </ul>
</div>
</div>
</div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>   

<script type="text/javascript">
	// Materialize.updateTextFields();
	$(document).ready(function() {
		$('select').material_select();
	});

	$('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

<?
if($reqModeSimpan == "jabatan_fungsional_umum")
{
	?>
	<script type="text/javascript"> 
		function settimetmt(info)
		{
			$("#reqJabatanFuInfoCheckTmtWaktuJabatan").hide();
			if($("#reqJabatanFuCheckTmtWaktuJabatan").prop('checked')) 
			{
				$("#reqJabatanFuInfoCheckTmtWaktuJabatan").show();
			}
			else
			{
				if(info == 2)
					$("#reqJabatanFuTmtWaktuJabatan").val("");
			}
		}

		$(function(){
			settimetmt(1);
			$("#reqJabatanFuCheckTmtWaktuJabatan").click(function () {
				settimetmt(2);
			});

			$("#reqJabatanFuIsManual").click(function () {
				$("#reqJabatanFuSatkerNama, #reqJabatanFuSatkerNamaId").val("");
			});

			$('input[id^="reqJabatanFuSatkerNama"], input[id^="reqJabatanFu"]').each(function(){
				$(this).autocomplete({
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";

						if (id.indexOf('reqJabatanFuSatkerNama') !== -1)
						{
							if($("#reqJabatanFuIsManual").prop('checked')) 
							{
								return false;
							}
						}

						if (id.indexOf('reqJabatanFuSatkerNama') !== -1)
						{
							var element= id.split('reqJabatanFuSatkerNama');
							var indexId= "reqJabatanFuSatkerNamaId"+element[1];
							urlAjax= "satuan_kerja_json/auto";
		//urlAjax= "satuan_kerja_json/namajabatan";
	}
	//else if (id.indexOf('reqJabatanFu') !== -1)
	else if (id == 'reqJabatanFu')
	{
		var element= id.split('reqJabatanFu');
		var indexId= "reqJabatanFuId"+element[1];
		urlAjax= "jabatan_fu_json/namajabatan";
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
					return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja']};
				});
				response(array);
			}
		}
	})
},
focus: function (event, ui) 
{ 
	var id= $(this).attr('id');
	if (id.indexOf('reqJabatanFuSatkerNama') !== -1)
	{
		var element= id.split('reqJabatanFuSatkerNama');
		var indexId= "reqJabatanFuSatkerNamaId"+element[1];
		//$("#reqJabatanFuNama").val("").trigger('change');
	}
		//else if (id.indexOf('reqJabatanFu') !== -1)
		else if (id == 'reqJabatanFu')
		{
			var element= id.split('reqJabatanFu');
			var indexId= "reqJabatanFuId"+element[1];
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


		});

		$('#reqJabatanFuTmtWaktuJabatan').formatter({
			'pattern': '{{99}}:{{99}}',
		});
	</script>
	<?
}
elseif($reqModeSimpan == "jabatan_struktural")
{
	?>
	<script type="text/javascript"> 
		function settimetmt(info)
		{
			$("#reqJabatanStrukturalInfoCheckTmtWaktuJabatan").hide();
			if($("#reqJabatanStrukturalCheckTmtWaktuJabatan").prop('checked')) 
			{
				$("#reqJabatanStrukturalInfoCheckTmtWaktuJabatan").show();
			}
			else
			{
				if(info == 2)
					$("#reqJabatanStrukturalTmtWaktuJabatan").val("");
			}
		}

		function seinfodatacentang()
		{
			$("#reqJabatanStrukturalinfoeselontext,#reqJabatanStrukturalinfoeselontext").hide();
			if($("#reqJabatanStrukturalIsManual").prop('checked')) 
			{
				$("#reqJabatanStrukturalinfoeselontext").show();
				$("#reqJabatanStrukturalSatker").attr("readonly", false);
				$("#reqJabatanStrukturalSelectEselonId").material_select();
			}
			else
			{
				$("#reqJabatanStrukturalSatker").attr("readonly", true);
				$("#reqJabatanStrukturalinfoeselontext").show();
			}
		}

		function setcetang()
		{
			$("#reqJabatanStrukturalinfoeselontext,#reqJabatanStrukturalinfoeselontext").hide();
			if($("#reqJabatanStrukturalIsManual").prop('checked')) 
			{
				$("#reqJabatanStrukturalinfoeselontext").show();
				$("#reqJabatanStrukturalSelectEselonId,#reqJabatanStrukturalEselonId, #reqJabatanStrukturalEselonText, #reqJabatanStrukturalNama, #reqJabatanStrukturalSatker, #reqJabatanStrukturalSatkerId").val("");
				$("#reqJabatanStrukturalSatker").attr("readonly", false);
				$("#reqJabatanStrukturalSelectEselonId").material_select();
		//$("#reqJabatanStrukturalNama,#reqJabatanStrukturalNamaId").val("");
	}
	else
	{
		$("#reqJabatanStrukturalSatker").attr("readonly", true);
		$("#reqJabatanStrukturalEselonId, #reqJabatanStrukturalNama, #reqJabatanStrukturalSatker, #reqJabatanStrukturalSatkerId").val("");
		$("#reqJabatanStrukturalinfoeselontext").show();
	}
}

$(function(){
	settimetmt(1);
	seinfodatacentang();

	$("#reqJabatanStrukturalCheckTmtWaktuJabatan").click(function () {
		settimetmt(2);
	});

	$("#reqJabatanStrukturalIsManual").click(function () {
		setcetang();
	});

	$('#reqJabatanStrukturalSelectEselonId').bind('change', function(ev) {
		$("#reqJabatanStrukturalEselonId").val($(this).val());
	});

	$('input[id^="reqJabatanStrukturalNama"]').autocomplete({
		source:function(request, response){
			var id= this.element.attr('id');
			var replaceAnakId= replaceAnak= urlAjax= "";

			if (id.indexOf('reqJabatanStrukturalNama') !== -1 || id.indexOf('reqJabatanStrukturalSatker') !== -1)
			{
				if($("#reqJabatanStrukturalIsManual").prop('checked')) 
				{
					return false;
				}
			}

			if (id.indexOf('reqPejabatPenetap') !== -1)
			{
				var element= id.split('reqPejabatPenetap');
				var indexId= "reqPejabatPenetapId"+element[1];
				urlAjax= "pejabat_penetap_json/combo";
			}
			else if (id.indexOf('reqJabatanStrukturalNama') !== -1)
			{
				var element= id.split('reqJabatanStrukturalNama');
				var indexId= "reqJabatanStrukturalNamaId"+element[1];
				urlAjax= "satuan_kerja_json/namajabatan";
			}
			else if (id.indexOf('reqJabatanStrukturalSatker') !== -1)
			{
				var element= id.split('reqJabatanStrukturalSatker');
				var indexId= "reqJabatanStrukturalSatkerId"+element[1];
				urlAjax= "satuan_kerja_json/auto";
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
							return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], eselon_id: element['eselon_id'], eselon_nama: element['eselon_nama']};
						});
						response(array);
					}
				}
			})
		},
		focus: function (event, ui) 
		{ 
			var id= $(this).attr('id');
			if (id.indexOf('reqPejabatPenetap') !== -1)
			{
				var element= id.split('reqPejabatPenetap');
				var indexId= "reqPejabatPenetapId"+element[1];
			}
			else if (id.indexOf('reqJabatanStrukturalNama') !== -1)
			{
				var element= id.split('reqJabatanStrukturalNama');
				var indexId= "reqJabatanStrukturalSatkerId"+element[1];
				$("#reqJabatanStrukturalSatker").val(ui.item.satuan_kerja).trigger('change');
				$("#reqJabatanStrukturalEselonId").val(ui.item.eselon_id).trigger('change');
				$("#reqJabatanStrukturalEselonText").val(ui.item.eselon_nama).trigger('change');
			}
			else if (id.indexOf('reqJabatanStrukturalSatker') !== -1)
			{
				var element= id.split('reqJabatanStrukturalSatker');
				var indexId= "reqJabatanStrukturalSatkerId"+element[1];
				$("#reqJabatanStrukturalNama").val("").trigger('change');
			}

			var statusht= "";
		//statusht= ui.item.statusht;
		$("#"+indexId).val(ui.item.id).trigger('change');
	},
	  //minLength:3,
	  autoFocus: true
	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	//return
	return $( "<li>" )
	.append( "<a>" + item.desc + "</a>" )
	.appendTo( ul );
};
});

$('#reqJabatanStrukturalTmtWaktuJabatan').formatter({
	'pattern': '{{99}}:{{99}}',
});
</script>
<?
}
elseif($reqModeSimpan == "pangkat_riwayat")
{
	?>
	<script language="javascript">
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

$(document).ready(function() {
	$('label').addClass('active');
	$("#reqKenaikanPangkatTurunKode").change(function(){
		setGajiTurun();
	});
	
	$("#reqKenaikanPangkatKembaliKode").change(function(){
		setGajiKembali();
	});
});
</script>
<?
}
?>
</body>
</html>