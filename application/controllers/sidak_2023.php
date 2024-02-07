<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/browser.func.php");
include_once("functions/date.func.php");

class Sidak_2023 extends CI_Controller {

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
		$this->INFO_SIPETA= $this->kauth->getInstance()->getIdentity()->INFO_SIPETA;

		// if($this->USER_LOGIN_ID == "")
		// {
		// 	redirect('login');
		// 	exit();
		// }
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

	public function checkmenupegawai($userlogin, $menuid)
	{
		$this->load->model('AksesAppSimpeg');

		$statement= " AND M.MENU_ID = '".$menuid."' AND A.USER_LOGIN_ID = ".$userlogin;
		$akses= new AksesAppSimpeg();
		$akses->selectByParamsAkses($statement);
		$akses->firstRow();
		// echo $akses->query;exit();
		$tempAksesMenu= $akses->getField("AKSES");
		return $tempAksesMenu;
	}

	public function index()
	{
		// echo "a";exit();
		if($this->USER_LOGIN_ID == "")
		{
			$this->load->view('app/login', $data);
		}
		else
		{
			$pg = $this->uri->segment(3, "peta");
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
		
			$data = array(
				'breadcrumb' => $breadcrumb,
				'content' => $this->load->view("sidak_2023/".$pg,$view,TRUE),
				'pg' => $pg,
				'reqParse1' => $reqParse1,
				'reqParse2'	=> $reqParse2,
				'reqParse3'	=> $reqParse3,
				'reqParse4'	=> $reqParse4,
				'reqParse5'	=> $reqParse5
			);	
			// echo $pg; exit;
			$this->load->view('sidak_2023/index', $data);
		}
	}

	public function loadUrl()
	{
		// echo "d";exit();
		$reqFolder= $this->uri->segment(3, "");
		$reqFilename= $this->uri->segment(4, "");
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	

}