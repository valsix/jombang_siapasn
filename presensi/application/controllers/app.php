<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class App extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			redirect('login');
	     	redirect(site_url(),'refresh');
		}		

		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		
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
		$this->STATUS_KHUSUS_DINAS= $this->kauth->getInstance()->getIdentity()->STATUS_KHUSUS_DINAS;
		$this->AKSES_APP_ABSENSI_ID= $this->kauth->getInstance()->getIdentity()->AKSES_APP_ABSENSI_ID;
	}
	
	public function index()
	{
		// print_r($this->uri);exit();
		$pg = $this->uri->segment(3, "home");
		// $pg = $this->uri->segment(3, "");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");

		$view = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		$arrJudul = explode("_", $pg);
		$max = count($arrJudul) - 1;
		if($arrJudul[$max] == "add")
		{		
			$link_monitoring = str_replace("_add", "", $pg);
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Tambah ".$monitoring."</li>";
		}
		elseif($arrJudul[$max] == "kelompok")
		{		
			$link_monitoring = str_replace("_kelompok", "", $pg);
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Kelompok Shift</li>";
		}
		elseif($arrJudul[$max] == "pegawai")
		{		
			$link_monitoring = str_replace("_pegawai", "", $pg);
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Daftar Pegawai</li>";
		}
		elseif($arrJudul[$max] == "jadwal")
		{		
			if($pg == "permohonan_jadwal_shift_jadwal")
			{
			$link_monitoring = "permohonan_jadwal_shift";
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Jadwal Shift</li>";
			}
			else
			{		
			$link_monitoring = "permohonan_jadwal_keandalan";
			$monitoring = str_replace("_", " ", $link_monitoring);
			
			$breadcrumb = "<li><a href=\"app/index/".$link_monitoring."\">".$monitoring."</a></li>";
			$breadcrumb .= "<li> Jadwal Keandalan</li>";
			}
		}
		elseif($arrJudul[$max] == "login")
		{}
		else
			$breadcrumb = "<li>".str_replace("_", " ", $pg)."</li>";
		
				
		$data = array(
			'breadcrumb' => $breadcrumb,
			'content' => $this->load->view("main/".$pg,$view,TRUE),
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		$this->load->view('main/index', $data);
	}	
	
	public function admin()
	{
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
		//if($reqFolder == "main")
		$this->session->set_userdata('currentUrl', $reqFilename);
		$this->session->set_userdata('currentFolder', $reqFolder);
		
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	
	
	public function ubahFotoProfil()
	{
		$reqBrowse = $_FILES['reqBrowse'];
		
		$FILE_DIR = "uploads/foto/";
		
		if($reqBrowse['name'] == "")
		{}
		else			
		{
			$renameFile = $this->ID.".".getExtension($reqBrowse['name']);
			if (move_uploaded_file($reqBrowse['tmp_name'], $FILE_DIR.$renameFile))
			{
				if(createThumbnail($FILE_DIR.$renameFile, $FILE_DIR."profile-".$renameFile, 200, "FIT_HEIGHT"))
					unlink($FILE_DIR.$renameFile);
			}			
		}			
		
		$this->load->view('main/dashboard', $data);
		
	}

	public function checkUserLogin()
	{
		$linkdata= $this->uri->segment(2, "");
		if($linkdata == "statistik_load"){}
		else
		{
			if($this->USER_LOGIN_ID == "")
			{
			?>
				<script language="javascript">
				top.location.href= "../../../app";
				</script>
			<?
			}
		}
	}

	public function validasiakses($menuid)
	{
		$infosql= " select akses from akses_app_absensi_menu where akses_app_absensi_id = ".$this->AKSES_APP_ABSENSI_ID." and menu_id = '".$menuid."'";
		$hasilsql = $this->db->query($infosql)->row();
		return $hasilsql->akses;
	}

}

