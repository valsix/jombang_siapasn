<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/browser.func.php");
include_once("functions/date.func.php");

class Sidak extends CI_Controller {

	function __construct() {
		parent::__construct();

		//kauth
		// if (!$this->kauth->getInstance()->hasIdentity())
		// {
		// 	// trow to unauthenticated page!
		// 	redirect('app');
		// }

		$this->USER_LOGIN_ID= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP_ID= $this->kauth->getInstance()->getIdentity()->USER_GROUP_ID;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;

		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->AKSES_APP_SIMPEG_ID= $this->kauth->getInstance()->getIdentity()->AKSES_APP_SIMPEG_ID;
		$this->AKSES_APP_PERSURATAN_ID= $this->kauth->getInstance()->getIdentity()->AKSES_APP_PERSURATAN_ID;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->SATUAN_KERJA_NAMA= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_NAMA;
		$this->SATUAN_KERJA_URUTAN_SURAT= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_URUTAN_SURAT;
		$this->SATUAN_KERJA_URUTAN_SURAT_NAMA= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_URUTAN_SURAT_NAMA;
		$this->SATUAN_KERJA_URUTAN_SURAT_JABATAN= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_URUTAN_SURAT_JABATAN;
		$this->SATUAN_KERJA_LOGIN_KEPALA_JABATAN= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_LOGIN_KEPALA_JABATAN;
		$this->STATUS_KELOMPOK_PEGAWAI_USUL= $this->kauth->getInstance()->getIdentity()->STATUS_KELOMPOK_PEGAWAI_USUL;

		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
		$this->STATUS_MENU_KHUSUS= $this->kauth->getInstance()->getIdentity()->STATUS_MENU_KHUSUS;
		
		$this->PEGAWAI_NAMA_LENGKAP= $this->kauth->getInstance()->getIdentity()->PEGAWAI_NAMA_LENGKAP;
		$this->PEGAWAI_PANGKAT_RIWAYAT_KODE= $this->kauth->getInstance()->getIdentity()->PEGAWAI_PANGKAT_RIWAYAT_KODE;
		$this->PEGAWAI_PANGKAT_RIWAYAT_TMT= dateToPageCheck($this->kauth->getInstance()->getIdentity()->PEGAWAI_PANGKAT_RIWAYAT_TMT);
		$this->PEGAWAI_JABATAN_RIWAYAT_NAMA= $this->kauth->getInstance()->getIdentity()->PEGAWAI_JABATAN_RIWAYAT_NAMA;
		$this->PEGAWAI_JABATAN_RIWAYAT_ESELON= $this->kauth->getInstance()->getIdentity()->PEGAWAI_JABATAN_RIWAYAT_ESELON;
		$this->PEGAWAI_JABATAN_RIWAYAT_TMT= dateToPageCheck($this->kauth->getInstance()->getIdentity()->PEGAWAI_JABATAN_RIWAYAT_TMT);
		$this->PEGAWAI_PATH= $this->kauth->getInstance()->getIdentity()->PEGAWAI_PATH;
		$this->TAMPIL_RESET= $this->kauth->getInstance()->getIdentity()->TAMPIL_RESET;

		// if($this->USER_LOGIN_ID == "")
		// {
		// 	redirect('login');
		// 	exit();
		// }
	}
	
	public function index()
	{

		if($this->USER_LOGIN_ID == "")
		{
			$this->load->view('app/login', $data);
		}
		date_default_timezone_set('Asia/Jakarta');

		$pg = $this->uri->segment(3, "home");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");
		
		// echo $pg; exit;
		// if($this->ID == "" && $pg !== "home")
		// 	redirect("sidak/index/home");
		
		
		$view = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5,
			'reqFilename' => $pg
		);	
		
		// $arrJudul = explode("_", $pg);
		// $max = count($arrJudul) - 1;
		// if($arrJudul[$max] == "add")
		// {		
		// 	$link_monitoring = str_replace("_add", "", $pg);
		// 	$monitoring = str_replace("_", " ", $link_monitoring);
			
		// 	$breadcrumb = "<li><a href=\"sidak/index/".$link_monitoring."\">".$monitoring."</a></li>";
		// 	$breadcrumb .= "<li> Tambah ".$monitoring."</li>";
		// }
		// elseif($arrJudul[$max] == "lhkl")
		// {		
		// 	$link_monitoring = str_replace("_lhkl", "", $pg);
		// 	$monitoring = str_replace("_", " ", $link_monitoring);
			
		// 	$breadcrumb = "<li><a href=\"sidak/index/".$link_monitoring."\">".$monitoring."</a></li>";
		// 	$breadcrumb .= "<li> Laporan Hasil Kerja Lembur</li>";
		// }
		// elseif($arrJudul[$max] == "login")
		// {}
		// else
		// 	$breadcrumb = "<li>".str_replace("_", " ", $pg)."</li>";
		
				
		$data = array(
			'breadcrumb' => $breadcrumb,
			'content' => $this->load->view("sidak/".$pg,$view,TRUE),
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		// echo $pg; exit;
		$this->load->view('sidak/index', $data);
	}	
	

	public function error404(){
		$this->load->view("error/404");
	}



	public function admin()
	{
		

	    if(stristr($this->USER_GROUP_KODE, "ADMIN_HC") || stristr($this->USER_GROUP_KODE, "ADMIN_APP"))
	    {}
		else
			redirect("app/index/login");
	    	

		if($this->ID == "" && $pg !== "home")
			redirect("app/index/home");
		
		
		$this->load->view('app/index', $data);
	}
	
	public function loadUrl()
	{
		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	
	
	public function ubahFotoProfil()
	{
		$reqBrowse = $_FILES['reqBrowse'];
		
		$FILE_DIR = "uploads/foto_fix/";
		
		if($reqBrowse['name'] == "")
		{
			 $arrResult["status"] = "failed";
			 $arrResult["message"] = "Unggah foto gagal.";
			 echo json_encode($arrResult);
		}
		else			
		{
			$renameFile = "PROFIL".$this->ID.".".strtolower(getExtension($reqBrowse['name']));
			$thumbsFile = $this->ID.".".strtolower(getExtension($reqBrowse['name']));
			if (move_uploaded_file($reqBrowse['tmp_name'], $FILE_DIR.$renameFile))
			{
				if(createThumbnail($FILE_DIR.$renameFile, $FILE_DIR.$thumbsFile, 200, "FIT_HEIGHT"))
				{
					unlink($FILE_DIR.$renameFile);

					 $arrResult["status"] = "success";
					 $arrResult["message"] = "Unggah foto berhasil.";
					 echo json_encode($arrResult);
				}
				else
				{
					 $arrResult["status"] = "failed";
					 $arrResult["message"] = "Unggah foto gagal.";
					 echo json_encode($arrResult);	
				}

			}			
			else
			{
				 $arrResult["status"] = "failed";
				 $arrResult["message"] = "Unggah foto gagal.";
				 echo json_encode($arrResult);				
			}
		}			

		
	}
	
	function apikalender()
	{

		$reqPeriode = $this->input->get("reqPeriode");
		
		
		$cacheApi = $this->cache->get('cacheApiKalender'.$reqPeriode.$this->ID);
		if (!empty($cacheApi)) {
			
			//echo $cacheApi;
			//return;
		}

		
		$this->load->model("AbsensiRekap");
		$absensi_rekap = new AbsensiRekap();
		$absensi_rekap->selectByParamsRekapInformasiKehadiranPeriode(array("PEGAWAI_ID" => $this->ID, "PERIODE" => $reqPeriode), -1, -1, " AND TANGGAL_DATE <= SYSDATE  ");
		$arrResult = array();
		while($absensi_rekap->nextRow())
        {
            $badge = $absensi_rekap->getField("MASUK");
                                            
            if(($absensi_rekap->getField("MASUK") == "" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "SABTU" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "MINGGU" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "SATURDAY" || 
              trim($absensi_rekap->getField("NAMA_HARI")) == "SUNDAY") && $this->KELOMPOK == "N")
            {}
            else
            {
//				$arrResult[$absensi_rekap->getField("TANGGAL")]["number"] = "&nbsp;<br><br><br><span style='margin-left:-55px; color:#191919'>".$absensi_rekap->getField("INFO_JAM")."<span>";

            	if($badge == "alpha")
            	{}
            	else
            	{
	                $arrResult[$absensi_rekap->getField("TANGGAL")]["number"] = "<span class='info-jam'>".$absensi_rekap->getField("INFO_JAM")."<span>";
					$arrResult[$absensi_rekap->getField("TANGGAL")]["badgeClass"] = $badge;
					$arrResult[$absensi_rekap->getField("TANGGAL")]["keterangan"] = $absensi_rekap->getField("MASUK_KETERANGAN");
				 }
            }
        }
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		if(count($arrResult) > 0)
			$this->cache->save('cacheApiKalender'.$reqPeriode.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	
	function apikehadirantahun()
	{
		$cacheApi = $this->cache->get('cacheApiKehadiranTahun'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
		

		$this->load->model("AbsensiRekap");
		$absensi_rekap_tahunan = new AbsensiRekap();
		$absensi_rekap_tahunan->selectByParamsRekapInformasiKehadiranTahunan3($this->ID,  date("Y")); 
		$i = 0;
		$total_rekap_tahunan = 0;
		while($absensi_rekap_tahunan->nextRow())
		{
			$KETERANGAN = $absensi_rekap_tahunan->getField("KETERANGAN");
			$arrRekapTahunan[$i]["KETERANGAN"] = $absensi_rekap_tahunan->getField("KETERANGAN");
			$arrRekapTahunan[$i]["WARNA"] = $absensi_rekap_tahunan->getField("WARNA");
			$arrRekapTahunan[$i]["SIMBOL"] = $absensi_rekap_tahunan->getField("SIMBOL");
			$arrRekapTahunan[$i]["JUMLAH"] = ($KETERANGAN == "ALPHA") ? "0" : $absensi_rekap_tahunan->getField("JUMLAH");
			$i++;
			$total_rekap_tahunan += $absensi_rekap_tahunan->getField("JUMLAH");
		}
		
				
		if($total_rekap_tahunan == 0)
			$total_rekap_tahunan = 1;



		for($i=0;$i<count($arrRekapTahunan);$i++)
		{
			$arrRekapTahunan[$i]["PROSENTASE"] = round(($arrRekapTahunan[$i]["JUMLAH"] / $total_rekap_tahunan) * 100, 2);
			$appendTable .= '
            <div class="skillbar clearfix" data-percent="'.$arrRekapTahunan[$i]["PROSENTASE"].'%">
                <div class="skillbar-title"><span class="'.$arrRekapTahunan[$i]["SIMBOL"].'">'.$arrRekapTahunan[$i]["KETERANGAN"].'</span></div>
                <div class="skillbar-bar" style="background: #'.$arrRekapTahunan[$i]["WARNA"].';"></div>
                <div class="skill-bar-percent">'.$arrRekapTahunan[$i]["JUMLAH"].'</div>
            </div> 
			';
		}
		
		$this->load->model("HariLibur");
		$hari_libur = new HariLibur();
		$totalLibur = $hari_libur->getTotalLiburSetahun(date("Y"));
		if($total_rekap_tahunan == 1 || $totalLibur > $total_rekap_tahunan)
			$persenLibur = $totalLibur;
		else
			$persenLibur = round(($totalLibur / $total_rekap_tahunan) * 100, 2);
		
		$appendTable .= '
						<div class="skillbar clearfix " data-percent="'.$persenLibur.'%">
							<div class="skillbar-title"><span class="libur">Libur</span></div>
							<div class="skillbar-bar" style="background: #9F4184;"></div>
							<div class="skill-bar-percent">'.$totalLibur.'</div>
						</div>';
						
		$appendTable .= '<div class="clearfix"></div>';
			
		$arrResult["TABEL"] 		= $appendTable;
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		$this->cache->save('cacheApiKehadiranTahun'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	
	function apikehadirantahunV2()
	{
		$cacheApi = $this->cache->get('cacheApiKehadiranTahunV2'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
		

		$this->load->model("AbsensiRekap");
		$absensi_rekap_tahunan = new AbsensiRekap();
		$absensi_rekap_tahunan->selectByParamsRekapInformasiKehadiranTahunan3($this->ID,  date("Y")); 
		$i = 0;
		$total_rekap_tahunan = 0;
		while($absensi_rekap_tahunan->nextRow())
		{
			$KETERANGAN = $absensi_rekap_tahunan->getField("KETERANGAN");
			$arrResult[strtoupper($absensi_rekap_tahunan->getField("SIMBOL"))] =($KETERANGAN == "ALPHA") ? "0" : $absensi_rekap_tahunan->getField("JUMLAH");
		}
		
		
		$this->load->model("HariLibur");
		$hari_libur = new HariLibur();
		$totalLibur = $hari_libur->getTotalLiburSetahun(date("Y"));

		$arrResult["LIBUR"] 		= $totalLibur;
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		$this->cache->save('cacheApiKehadiranTahunV2'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	function apirekapcabang()
	{
		$cacheApi = $this->cache->get('cacheApiRekapCabang'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
		
		
		$this->load->model("Pegawai");
		$pegawai_kehadiran = new Pegawai();

		$statement_cabang = " AND NOT B.KETERANGAN IN ('BOC', 'BOD')  AND A.JABATAN_ID IS NOT NULL ";

		$pegawai_kehadiran->selectByParamsGrafikPresensi(array(), -1, -1, $statement_cabang);
		$i = 0;
		while($pegawai_kehadiran->nextRow())
		{
			$arrCabang[] 	 = $pegawai_kehadiran->getField("CABANG_ID");
			$arrHadir[]  	 = (int)$pegawai_kehadiran->getField("HADIR");
			$arrTidakHadir[] = (int)0;//$pegawai_kehadiran->getField("ALPHA");
			
			$appendTable .= '
			<tr>
				<td class="headerVer">'.($i+1).'</td>
				<td>'.$pegawai_kehadiran->getField("CABANG_ID").'</td>
				<td class="source-img">'.$pegawai_kehadiran->getField("NAMA").'</td>
				<td>'.$pegawai_kehadiran->getField("JUMLAH_PEGAWAI").'</td>
				<td>'.$pegawai_kehadiran->getField("HADIR").'</td>
			</tr>
			';
			
			$i++;	
		}		


		/*				<td><span class="alpha">'.$pegawai_kehadiran->getField("ALPHA").'</span></td>*/
		
		$arrResult["CABANG"] 	= $arrCabang;
		$arrResult["HADIR"] 	= $arrHadir;
		$arrResult["TIDAK_HADIR"] 	= $arrTidakHadir;
		$arrResult["TABEL"] 		= $appendTable;
		
		$return = json_encode($arrResult);
		// Save into the cache for 10 minutes
		$this->cache->save('cacheApiRekapCabang'.$this->ID, $return, 7200);
		
		echo $return;
		
	}
	
	
	function apirekapkaryawan()
	{
		$cacheApi = $this->cache->get('cacheApiRekapKaryawan'.$this->ID);
		if (!empty($cacheApi)) {
			
			echo $cacheApi;
			return;
		}
				
				
		$this->load->model("Pegawai");
		$this->load->model("PegawaiStatusPegawai");
		
		$pegawai = new Pegawai();
		$pegawai_status_pegawai = new PegawaiStatusPegawai();
		$pegawai_umur = new Pegawai();
		
		$pegawai->selectByParamsJenisKelamin(array(), -1, -1, " AND NOT STAFF_ID IN ('R0', 'R1')  AND A.JABATAN_ID IS NOT NULL ");
		while($pegawai->nextRow())
		{
			if($pegawai->getField("JENIS_KELAMIN") == "M")
				$totalLaki = $pegawai->getField("JUMLAH");
			else
				$totalPerempuan = $pegawai->getField("JUMLAH");
		}
		$pegawai_status_pegawai->selectByParamsGrafik(array());
		$pegawai_status_pegawai->firstRow();
		$totalOrganik = $pegawai_status_pegawai->getField("JUMLAH_ORGANIK");
		$totalNonOrganik = $pegawai_status_pegawai->getField("JUMLAH_NON_ORGANIK");


		$totalPegawai = $totalLaki + $totalPerempuan;
		$totalPegawai = numberToIna($totalPegawai);


		
		$pegawai_umur->selectByParamsGrafikUmur(array(), -1, -1, " AND NOT STAFF_ID IN ('R0', 'R1')  AND JABATAN_ID IS NOT NULL ");
		$i = 0;
		$arrColor   = array("#005CBD", "#17587C", "#8C9097", "#ffffff");
		
		$arrPieUmur = array();
		while($pegawai_umur->nextRow())
		{
        	$arrPieUmur[$i]["name"]  = "Usia ".$pegawai_umur->getField("KETERANGAN");
        	$arrPieUmur[$i]["y"] 	 = (float)$pegawai_umur->getField("PROSENTASE");
        	$arrPieUmur[$i]["color"] = $arrColor[$i];

			if($i == 0)
			{
				$arrPieUmur[$i]["sliced"] 	= "true";
				$arrPieUmur[$i]["selected"] = "true";
			}
			
			$appendTable .= '<div class="item">
                                <div class="judul">Usia '.$pegawai_umur->getField("KETERANGAN").'</div>
                                <div class="nilai">'.numberToIna($pegawai_umur->getField("JUMLAH")).'</div>
                            </div>';
			$i++;
		}

		$arrResult["TOTAL_PEGAWAI"] 	= $totalPegawai;
		$arrResult["TOTAL_LAKI"] 		= $totalLaki;
		$arrResult["TOTAL_WANITA"] 		= $totalPerempuan;
		$arrResult["TOTAL_ORGANIK"] 	= $totalOrganik;
		$arrResult["TOTAL_NONORGANIK"] 	= $totalNonOrganik;
		$arrResult["PIE_UMUR"] 			= $arrPieUmur;
		$arrResult["LEGEND_UMUR"] 		= $appendTable;
		
		$return = json_encode($arrResult);
		// Save into the cache for 1 hour
		$this->cache->save('cacheApiRekapKaryawan'.$this->ID, $return, 7200);
		
		echo $return;
		
	}


	function kirim_notifikasi_permohonan()
	{

		$reqId 	  = $this->input->post("reqId");
		$reqJenis = $this->input->post("reqJenis");
		$reqKolom = $this->input->post("reqKolom");

		if($reqJenis == "PERMOHONAN_LEMBUR")
			$statement = " AND TABEL_APPROVAL_KOLOM = '$reqKolom' ";

		$approvalId = $this->db->query(" SELECT APPROVAL_MANAGER_ID FROM APPROVAL_MANAGER WHERE PERMOHONAN_ID = '$reqId' AND TABEL = '$reqJenis' ".$statement)->row()->APPROVAL_MANAGER_ID;

		if($approvalId == "")
		{
			echo "Permohonan tidak dikenali. Hubungi Administrator.";
			return;
		}


        $this->load->library('usermobile'); 
        $userMobile = new usermobile();
        $statusEmail1 = $userMobile->pushEmailUlang($approvalId);
		echo $statusEmail1;
	}



	function hapus_permohonan()
	{

		$reqId 	  = $this->input->post("reqId");
		$reqJenis = $this->input->post("reqJenis");
		$reqKolom = $this->input->post("reqKolom");




		$periodeAktif = $this->db->query("SELECT TO_CHAR(TO_DATE((PABRP || PABRJ), 'MMYYYY') - INTERVAL '1' MONTH, 'MMYYYY') PERIODE FROM INTEGRASI_PERIODE_PAYROLL WHERE TRUNC(SYSDATE) BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR ")->row()->PERIODE;


		if($reqJenis == "PERMOHONAN_CUTI_TAHUNAN")
		{
			$reqJenisId = "PERMOHONAN_CUTI_TAHUNAN_ID";

			$periodeHapus = $this->db->query(" SELECT PERIODE FROM ".$reqJenis."  WHERE ".$reqJenisId." = '$reqId' AND PEGAWAI_ID = '".$this->ID."' ")->row()->PERIODE;

		}
		elseif($reqJenis == "PERMOHONAN_CUTI")
		{
			$reqJenisId = "PERMOHONAN_CUTI_ID";

			$periodeHapus = $this->db->query(" SELECT PERIODE FROM ".$reqJenis."  WHERE ".$reqJenisId." = '$reqId' AND PEGAWAI_ID = '".$this->ID."' ")->row()->PERIODE;

		}
		elseif($reqJenis == "PERMOHONAN_LAMBAT_PC")
		{
			$reqJenisId = "PERMOHONAN_LAMBAT_PC_ID";

			$periodeHapus = $this->db->query(" SELECT PERIODE FROM ".$reqJenis."  WHERE ".$reqJenisId." = '$reqId' AND PEGAWAI_ID = '".$this->ID."' ")->row()->PERIODE;

		}
		elseif($reqJenis == "PERMOHONAN_LEMBUR")
		{
			$reqJenisId = "PERMOHONAN_LEMBUR_ID";

			$periodeHapus = $this->db->query(" SELECT PERIODE FROM ".$reqJenis."  WHERE ".$reqJenisId." = '$reqId' AND PEGAWAI_ID = '".$this->ID."' ")->row()->PERIODE;

		}
		elseif($reqJenis == "PERMOHONAN_CUTI_REVISI")
		{
			$reqJenisId = "PERMOHONAN_CUTI_REVISI_ID";
		}
		else
		{
			echo "Gagal!";
			return;
		}


		if(!empty($periodeHapus))
		{

			$sql = " SELECT CASE WHEN TO_DATE('$periodeHapus', 'MMYYYY') >= TO_DATE('$periodeAktif', 'MMYYYY') THEN 1 ELSE 0 END BISA_HAPUS FROM DUAL ";
			$bisaHapus = $this->db->query($sql)->row()->BISA_HAPUS;

			if($bisaHapus == 0)
			{
				echo "Data gagal dihapus. Periode permohonan telah melewati periode payroll.";
				return;
			}


		}


		$result = $this->db->query(" DELETE FROM ".$reqJenis."  WHERE ".$reqJenisId." = '$reqId' AND PEGAWAI_ID = '".$this->ID."' AND NVL(APPROVAL1, 'X') = 'X' ");

		if($result)
		{
			echo "Data berhasil dihapus.";
			return;
		}



		echo "Data gagal dihapus.";
	}
	


	

	function whatsapp()
	{

		$reqWhatsapp = trim($this->input->post("reqWhatsapp"));


		$sql = " SELECT COUNT(1) ADA FROM WHATSAPP_LOG WHERE NOMOR = '$reqWhatsapp' AND MODUL = 'REGISTRASI_WHATSAPP' AND TRUNC(CREATED_DATE) = TRUNC(SYSDATE) ";

		$adaData = $this->db->query($sql)->row()->ADA;

		if($adaData > 3)
		{


			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Anda telah mencapai batas maksimal pengiriman OTP sebanyak 3x.";
			$rowResult["nomor"]   = $reqWhatsapp;
			echo json_encode($rowResult);
			return;
		}


		$reqOTP  = mt_rand(10000, 99999);


		$reqBody = "Yth. ".$this->NAMA.", masukkan OTP : ".$reqOTP." untuk mendaftarkan nomor whatsapp anda pada aplikasi ESSA.";

        /* API */
        $ch = curl_init();

		$data = array("xUsername" => "raka", 
					  "xPassword" => "rakar", 
					  "to" 		  => $reqWhatsapp, 
					  "body" 	  => $reqBody, 
					  "instance"  => "1", 
					  "appname"   => "36");

		$payload = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, "http://vera.teluklamong.co.id/whatsapp_api/send_message");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);

       
        $result = json_decode($response, 1);

        $reqBody = setQuote($reqBody);

        if($result[0]["sent"] == "true")
        {
	        $sql = " INSERT INTO WHATSAPP_LOG (
					   PEGAWAI_ID, MODUL, NOMOR, 
					   PESAN, OTP, CREATED_BY) 
					VALUES ('".$this->ID."', 'REGISTRASI_WHATSAPP', '$reqWhatsapp', 
					   '$reqBody', '$reqOTP', 'SYSTEM') ";

			$this->db->query($sql);
			
			$rowResult["status"]  = "success";
			$rowResult["message"] = "Masukkan OTP";
			$rowResult["nomor"]   = $reqWhatsapp;

			echo json_encode($rowResult);

		}
		else
		{

			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Kirim OTP Gagal. Ulangi beberapa saat lagi.";
			$rowResult["nomor"]   = $reqWhatsapp;
			echo json_encode($rowResult);
		}


	}


	function whatsapp_otp()
	{

		$reqOtp = $this->input->post("reqOtp");


		$sql = " SELECT COUNT(1) ADA FROM OTP_LOG WHERE PEGAWAI_ID = '".$this->ID."' AND MODUL = 'REGISTRASI_WHATSAPP' AND TRUNC(CREATED_DATE) = TRUNC(SYSDATE) ";

		$adaData = $this->db->query($sql)->row()->ADA;

		if($adaData > 3)
		{
			$rowResult["status"]  = "failed";
			$rowResult["message"] = "OTP yang anda masukkan salah sebanyak 3x. Perubahan data gagal.";
			$rowResult["nomor"]   = $reqWhatsapp;
			echo json_encode($rowResult);
			return;
		}
		
		$sql = " SELECT NOMOR FROM WHATSAPP_LOG WHERE PEGAWAI_ID = '".$this->ID."' AND MODUL = 'REGISTRASI_WHATSAPP' AND OTP = '$reqOtp'  ";
		$nomorWhatsapp = $this->db->query($sql)->row()->NOMOR;

		if($nomorWhatsapp == "")
		{

			$sql = " INSERT INTO OTP_LOG(PEGAWAI_ID, MODUL, OTP, PESAN, CREATED_BY) 
					 VALUES('".$this->ID."', 'REGISTRASI_WHATSAPP', '$reqOtp', 'OTP FAILED', ".$this->ID."') ";
			$this->db->query($sql);


			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Kode OTP yang anda masukkan salah.";
			$rowResult["nomor"]   = $reqWhatsapp;
			echo json_encode($rowResult);
			return;	
		}


		$this->db->query(" UPDATE KARYAWAN SET TELEPON_WHATSAPP = '$nomorWhatsapp' WHERE PEGAWAI_ID = '".$this->ID."' ");

		$rowResult["status"]  = "success";
		$rowResult["message"] = "Terima kasih registrasi nomor whatsapp anda berhasil.";
		$rowResult["nomor"]   = $nomorWhatsapp;
		echo json_encode($rowResult);

	}

	function whatsapp_kontak_otp()
	{

		$reqWhatsapp = trim($this->input->post("reqWhatsapp"));

		$reqOTP  = mt_rand(10000, 99999);


		$sql = " SELECT COUNT(1) ADA FROM WHATSAPP_LOG WHERE NOMOR = '$reqWhatsapp' AND MODUL = 'REGISTRASI_KONTAK' AND TRUNC(CREATED_DATE) = TRUNC(SYSDATE) ";

		$adaData = $this->db->query($sql)->row()->ADA;

		if($adaData > 3)
		{


			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Anda telah mencapai batas maksimal pengiriman OTP sebanyak 3x.";
			$rowResult["nomor"]   = $reqWhatsapp;
			echo json_encode($rowResult);
			return;
		}


		$reqBody = "Yth. ".$this->NAMA.", masukkan OTP : ".$reqOTP." untuk menambahkan kontak anda pada aplikasi ESSA.";

        /* API */
        $ch = curl_init();

		$data = array("xUsername" => "raka", 
					  "xPassword" => "rakar", 
					  "to" 		  => $reqWhatsapp, 
					  "body" 	  => $reqBody, 
					  "instance"  => "1", 
					  "appname"   => "36");

		$payload = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, "http://vera.teluklamong.co.id/whatsapp_api/send_message");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        curl_close($ch);

       
        $result = json_decode($response, 1);

        $reqBody = setQuote($reqBody);

        if($result[0]["sent"] == "true")
        {
	        $sql = " INSERT INTO WHATSAPP_LOG (
					   PEGAWAI_ID, MODUL, NOMOR, 
					   PESAN, OTP, CREATED_BY) 
					VALUES ('".$this->ID."', 'REGISTRASI_KONTAK', '$reqWhatsapp', 
					   '$reqBody', '$reqOTP', 'SYSTEM') ";

			$this->db->query($sql);
			
			$rowResult["status"]  = "success";
			$rowResult["message"] = "Masukkan OTP";
			$rowResult["nomor"]   = $reqWhatsapp;

			echo json_encode($rowResult);

		}
		else
		{
			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Kirim OTP Gagal. Ulangi beberapa saat lagi.";
			$rowResult["nomor"]   = $reqWhatsapp;
			echo json_encode($rowResult);
		}
	}

	function periode_payroll()
	{

		$reqTanggal = $this->input->get("reqTanggal");

		$periode = $this->db->query("SELECT TO_CHAR(TO_DATE((PABRP || PABRJ), 'MMYYYY')   + INTERVAL '1' MONTH, 'MMYYYY') PERIODE FROM INTEGRASI_PERIODE_PAYROLL WHERE TO_DATE('$reqTanggal', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR ")->row()->PERIODE;

		$rowResult["periode"] = $periode;
		$rowResult["periode_nama"] = getNamePeriode($periode);

		echo json_encode($rowResult);


	}

	function potongan_absen()
	{

		$reqPeriode = $this->input->get("reqPeriode");


		$sql = " SELECT JENIS_POTONGAN, SUM(ANZHL) JUMLAH FROM INTEGRASI_POTONGAN_ABSEN WHERE PEGAWAI_ID = '".$this->ID."' AND PERIODE = '$reqPeriode' 
					GROUP BY JENIS_POTONGAN  ORDER BY JENIS_POTONGAN
					";
		$query = $this->db->query($sql);
		$i = 0;
		foreach ($query->result_array() as $row)
		{
			$data = $row["JENIS_POTONGAN"]." : ".$row["JUMLAH"]." hari";

			if($i==0)
				$message = $data;
			else
				$message .= "<br>".$data;

			$i++;

		}

		if($message == "")
			$message = "Tidak ada potongan";
		else
			$message .= "<br><a onclick='openPopup(\"app/loadUrl/main/potongan_absen/?reqPeriode=".$reqPeriode."\")' style='color:#000'>Lihat Rincian Potongan</a>";

		$result["periode_nama"] = getNamePeriode($reqPeriode);
		$result["message"] = $message;

		echo json_encode($result);

	}





	function laporan_lembur()
	{

		$reqPeriode = $this->input->get("reqPeriode");


		$sql = " SELECT LGART, LGTXT, SUM(ANZHL) JUMLAH FROM INTEGRASI_LAPORAN_LEMBUR A WHERE PEGAWAI_ID = '".$this->ID."' AND 
					EXISTS(SELECT 1 FROM INTEGRASI_PERIODE_PAYROLL X 
                            WHERE X.PABRP || X.PABRJ = TO_CHAR(TO_DATE('$reqPeriode', 'MMYYYY') - INTERVAL '1' MONTH, 'MMYYYY') AND 
                            A.TANGGAL BETWEEN X.TANGGAL_AWAL AND X.TANGGAL_AKHIR)
					GROUP BY LGART, LGTXT  ORDER BY LGART
					";
		$query = $this->db->query($sql);
		$i = 0;
		foreach ($query->result_array() as $row)
		{
			$data = $row["LGTXT"]." : ".$row["JUMLAH"]." jam";

			if($i==0)
				$message = $data;
			else
				$message .= "<br>".$data;

			$i++;

		}

		if($message == "")
			$message = "Tidak ada lembur";
		else
			$message .= "<br><a onclick='openPopup(\"app/loadUrl/main/laporan_lembur/?reqPeriode=".$reqPeriode."\")' style='color:#000'>Lihat Rincian Lembur</a>";

		$result["periode_nama"] = getNamePeriode($reqPeriode);
		$result["message"] = $message;

		echo json_encode($result);

	}






	function email()
	{

		$reqEmail = trim($this->input->post("reqEmail"));


		$reqOTP  = mt_rand(10000, 99999);


		$reqBody = "Yth. ".$this->NAMA.", masukkan OTP : ".$reqOTP." untuk mendaftarkan email pribadi anda pada aplikasi ESSA.";

                
        $this->load->library("KMail");
        $mail = new KMail();
        $mail->Subject = "OTP Perubahan Email Pribadi";
        $mail->AddAddress($reqEmail,$this->NAMA);            
        $body = file_get_contents($this->config->item("base_report")."report/loadUrl/email/otp/".$reqOTP."/".$this->ID);        
       	$mail->MsgHTML($body);

        if($mail->Send())
        {
	        $sql = " INSERT INTO WHATSAPP_LOG (
					   PEGAWAI_ID, MODUL, NOMOR, 
					   PESAN, OTP, CREATED_BY) 
					VALUES ('".$this->ID."', 'REGISTRASI_EMAIL', '$reqEmail', 
					   '$reqBody', '$reqOTP', 'SYSTEM') ";

			$this->db->query($sql);
			
			$rowResult["status"]  = "success";
			$rowResult["message"] = "Masukkan OTP";
			$rowResult["email"]   = $reqEmail;

			echo json_encode($rowResult);

		}
		else
		{

			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Kirim OTP Gagal. Ulangi beberapa saat lagi.";
			$rowResult["email"]   = $reqEmail;
			echo json_encode($rowResult);
		}


	}


	function email_otp()
	{

		$reqOtp = $this->input->post("reqOtp");


		
		$sql = " SELECT NOMOR FROM WHATSAPP_LOG WHERE PEGAWAI_ID = '".$this->ID."' AND MODUL = 'REGISTRASI_EMAIL' AND OTP = '$reqOtp'  ";
		$nomorWhatsapp = $this->db->query($sql)->row()->NOMOR;

		if($nomorWhatsapp == "")
		{
			$rowResult["status"]  = "failed";
			$rowResult["message"] = "Kode OTP yang anda masukkan salah.";
			$rowResult["email"]   = $reqEmail;
			echo json_encode($rowResult);
			return;	
		}


		$this->db->query(" UPDATE KARYAWAN SET EMAIL_PRIBADI = '$nomorWhatsapp' WHERE PEGAWAI_ID = '".$this->ID."' ");

		$rowResult["status"]  = "success";
		$rowResult["message"] = "Terima kasih registrasi email pribadi anda berhasil.";
		$rowResult["email"]   = $nomorWhatsapp;
		echo json_encode($rowResult);

	}


	function tesemail()
	{

        $this->load->library("KMail");
        $mail = new KMail();
        $mail->Subject = "OTP Perubahan Email Pribadi";
        $mail->AddAddress("helpdesk@valsix.xyz","helpdesk@valsix.xyz");            
        $body = file_get_contents($this->config->item("base_report")."report/loadUrl/email/otp/555/1931000209");        
       	$mail->MsgHTML($body);

        if($mail->Send())
        {
        	echo "sukss";
		}
		else
		{
        	echo "gagal";
		}

	}
}