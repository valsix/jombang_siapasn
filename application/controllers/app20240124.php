<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/browser.func.php");
include_once("functions/date.func.php");
include_once("functions/encrypt.func.php");
class App extends CI_Controller {

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
	
	public function one()
	{
		echo "asd";exit();
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

	public function checkpegawai($id)
	{
		$tempStatusIjin= "1";
		if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->SATUAN_KERJA_ID == "" || $this->STATUS_SATUAN_KERJA_BKD == 1)
		{
			$tempStatusIjin= "";
		}
		else
		{
			$this->load->model('Pegawai');

			$set= new Pegawai();
			$tempSatuanKerjaId= $set->checkpegawaisatuankerja($id, $this->SATUAN_KERJA_ID);
			// print_r($tempSatuanKerjaId);exit();
			// echo "--".$this->SATUAN_KERJA_ID;exit();

			if (in_array($this->SATUAN_KERJA_ID, $tempSatuanKerjaId)) {
			    $tempStatusIjin= "";
			}
		}
		// echo $tempStatusIjin;exit();

		if($tempStatusIjin == "1")
		{
			echo "Anda Tidak berhak";
			exit();
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
	
	public function login()
	{
		$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');

		if (!$csrf->isTokenValid($_POST['_crfs_login']))
		{
		?>
			<script language="javascript">
				alert('<?=$respon?>');
				document.location.href = 'logout';
			</script>
		<?
			exit();
		}

		//redirect('app');exit;
		$reqUser= $this->input->post("reqUser");
		$reqPasswd= $this->input->post("reqPasswd");
		//echo $reqUser;exit;
		if(!empty($reqUser) AND !empty($reqPasswd))
		{
			$respon = $this->kauth->localAuthenticate($reqUser,$reqPasswd);
			if($respon == 1)
			{
				/*$ua=getBrowser();
				$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on ; " .$ua['platform'] . "; reports: <br >" . $ua['userAgent'];
				print_r($yourbrowser);exit();*/

				//$reqIp= getIpAddress();
				$reqIp= getClientIpEnv();
				$ua=getBrowser();
				$reqKeterangan= $ua['name'] . " " . $ua['version'] . " pada OS ( " .$ua['platform'] . ")";


				$this->load->model('SessionLog');
				$set_detil= new SessionLog();
				$set_detil->setField("IP_ADDRESS", $reqIp);
				$set_detil->setField("KETERANGAN", $reqKeterangan);
				$set_detil->setField("LAST_LEVEL", $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL);
				$set_detil->setField("LAST_USER", $this->kauth->getInstance()->getIdentity()->LOGIN_USER);
				$set_detil->setField("USER_LOGIN_ID", $this->kauth->getInstance()->getIdentity()->LOGIN_ID);
				$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID));
				$set_detil->setField("LAST_DATE", "NOW()");


				if($set_detil->insert())
				{
					$checkloginid= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
					// kondisi kalau admin saja
					//if($checkloginid == "0")

					if($checkloginid == "1" || $checkloginid == "411" || $checkloginid == "359" || $checkloginid == "376")
					{
						redirect('codeportal/index');
					}

					else {

						//script tambahan rendy, pembatasan akses cek user upt dan dinas by level, exclude kabid, MITA, ERFANDI
						// $checkloginlevel= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
						// if ($checkloginlevel <= 20 && $checkloginid <> 338 && $checkloginid <> 386 && $checkloginid <> 391  && $checkloginid <> 377 && $checkloginid <> 355 && $checkloginid <> 422 && $checkloginid <> 240 && $checkloginid <> 383 && $checkloginid <> 381 && $checkloginid <> 335 && $checkloginid <> 382 && $checkloginid <> 336 && $checkloginid <> 7 && $checkloginid <> 240){

						// 	$respon= "Mohon maaf, sedang dilaksanakan evaluasi dan integrasi data.";
						/*	?>
					 //        <script language="javascript">
						// 		alert('<?=$respon?>');
						// 		document.location.href = 'logout';
						// 	</script>
					 //  		<?
						// 	exit;
						// }
						*/
						//end script tambahan rendy, pembatasan akses cek user upt dan dinas by level, exclude kabid,  MITA, ERFANDI

						redirect('app/index');							

					}
				}
			}
			else
			{
				?>
                <script language="javascript">
					alert('<?=$respon?>');
					document.location.href = 'logout';
				</script>
                <?
				exit;
			}
		}
		else
		{
			redirect('app');
		}
	}
	
	public function logout()
	{
		$this->kauth->getInstance()->clearIdentity();
		redirect ('app');
	}
	
	public function index()
	{
		// echo "a";exit();
		if($this->USER_LOGIN_ID == "")
		{
			$this->load->view('app/login', $data);
		}
		else
		//$this->load->view('app/index_bak2', $data);
		$this->load->view('app/index', $data);
	}

	public function statistik()
	{
		// echo "c";exit();
		$this->load->view('app/statistik_tanpa_login');
	}

	public function statistik_load()
	{
		// echo "b";exit();
		$this->load->view('app/statistik');
	}
	
	public function loadUrl()
	{
		// echo "d";exit();
		$reqFolder= $this->uri->segment(3, "");
		$reqFilename= $this->uri->segment(4, "");
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	

}