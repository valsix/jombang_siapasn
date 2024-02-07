<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';

class kauth {
    //put your code here
    private $ldap_config = array('server1'=>array(   'host'=>'10.0.0.11',
                                    'useStartTls'=>false,
                                    'accountDomainName'=>'pp3.co.id',
                                    'accountDomainNameShort'=>'PP3',
                                    'accountCanonicalForm'=>3,
                                    'baseDn'=>"DC=pp3,DC=co,DC=id"));


        function __construct() {
//        load the auth class
        kloader::load('Zend_Auth');
        kloader::load('Zend_Auth_Storage_Session');
        
//        set the unique storege
        // Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("pr3sEns1s1ap0nlin3"));
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("s1ap0nlin3"));
    }
    
    public function ldapAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		// $url = "http://login.ptpjb.com/ldap_api/auth_opendj/".$username."/".$credential;
		$url = "http://192.168.3.203/ldap_api/auth_opendj/".$username."/".$credential;
		$json = file_get_contents($url, true);
		$arr = json_decode($json);	
		
		if($arr->valid == "1")
		{
			$username = $arr->nid;
			if($username == ""){
				return false;
			}
			else
			{
				//if (strlen($username)<8)
				if (is_numeric($username))
				{
					$nid = "000".$username;
				}
				else
				{
					$nid = $username;
				}
				$this->getLoginInformation($nid);   						
				return true;
			}			
		}
		else
		{
			return false;	
		}

		// if($arr['valid'] == "1")
		// {
		// 	$username = $arr['NID'];
		// 	if($username == ""){
		// 		return false;
		// 	}
		// 	else
		// 	{
		// 		$this->getLoginInformation($username);   						
		// 		return true;
		// 	}			
		// }
		// else
		// {
		// 	return false;	
		// }

    }
	
	
	public function localAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		
		$CI =& get_instance();

		/* USER AUTH */
		$CI->load->model("UserLogin");

		$username= $CI->db->escape($username);
		$credential= $CI->db->escape(md5($credential));
		$statement= " AND LOGIN_USER = ".$username." AND LOGIN_PASS = ".$credential;
		// echo $statement;exit;

		$user_login = new UserLogin();
		$user_login->selectByParamsLogin(array(), -1,-1, $statement);
		// echo $user_login->query;exit;
		// echo $user_login->errorMsg;
		if($user_login->firstRow())
		{
            $identity = new stdClass();
            $identity->USER_LOGIN_ID= $user_login->getField("USER_LOGIN_ID");
			$identity->USER_GROUP_ID= $user_login->getField("USER_GROUP_ID");
			$identity->LOGIN_ID= $user_login->getField("USER_LOGIN_ID");
			$identity->LOGIN_PEGAWAI_ID= $user_login->getField("PEGAWAI_ID");
			
			$identity->LOGIN_USER= $user_login->getField("LOGIN_USER");
			$identity->LOGIN_LEVEL= $user_login->getField("LOGIN_LEVEL");
			$identity->USER_GROUP= $user_login->getField("USER_GROUP");
			$identity->AKSES_APP_SIMPEG_ID= $user_login->getField("AKSES_APP_SIMPEG_ID");
			$identity->AKSES_APP_PERSURATAN_ID= $user_login->getField("AKSES_APP_PERSURATAN_ID");
			$identity->SATUAN_KERJA_ID= $user_login->getField("SATUAN_KERJA_ID");
			$identity->SATUAN_KERJA_NAMA= $user_login->getField("SATUAN_KERJA_NAMA");
			$identity->SATUAN_KERJA_URUTAN_SURAT= $user_login->getField("SATUAN_KERJA_URUTAN_SURAT");
			$identity->SATUAN_KERJA_URUTAN_SURAT_NAMA= $user_login->getField("SATUAN_KERJA_URUTAN_SURAT_NAMA");
			$identity->SATUAN_KERJA_URUTAN_SURAT_JABATAN= $user_login->getField("SATUAN_KERJA_URUTAN_SURAT_JABATAN");
			$identity->SATUAN_KERJA_LOGIN_KEPALA_JABATAN= $user_login->getField("SATUAN_KERJA_LOGIN_KEPALA_JABATAN");
			$identity->SATUAN_KERJA_TIPE= $user_login->getField("SATUAN_KERJA_TIPE");
			$identity->STATUS_KELOMPOK_PEGAWAI_USUL= $user_login->getField("STATUS_KELOMPOK_PEGAWAI_USUL");

			$identity->STATUS_SATUAN_KERJA_BKD= $user_login->getField("STATUS_SATUAN_KERJA_BKD");
			$identity->STATUS_MENU_KHUSUS= $user_login->getField("STATUS_MENU_KHUSUS");

			$identity->PEGAWAI_ID= $user_login->getField("PEGAWAI_ID");
			$identity->PEGAWAI_NAMA_LENGKAP= $user_login->getField("PEGAWAI_NAMA_LENGKAP");
			$identity->PEGAWAI_PANGKAT_RIWAYAT_KODE= $user_login->getField("PEGAWAI_PANGKAT_RIWAYAT_KODE");
			$identity->PEGAWAI_PANGKAT_RIWAYAT_TMT= $user_login->getField("PEGAWAI_PANGKAT_RIWAYAT_TMT");
			$identity->PEGAWAI_JABATAN_RIWAYAT_NAMA= $user_login->getField("PEGAWAI_JABATAN_RIWAYAT_NAMA");
			$identity->PEGAWAI_JABATAN_RIWAYAT_ESELON= $user_login->getField("PEGAWAI_JABATAN_RIWAYAT_ESELON");
			$identity->PEGAWAI_JABATAN_RIWAYAT_TMT= $user_login->getField("PEGAWAI_JABATAN_RIWAYAT_TMT");
			$identity->PEGAWAI_PATH= $user_login->getField("PATH");
			$identity->SATUAN_KERJA_BKD_ID= $user_login->getField("SATUAN_KERJA_BKD_ID");

            $identity->STATUS_KHUSUS_DINAS = $user_login->getField("STATUS_KHUSUS_DINAS");
            $identity->AKSES_APP_ABSENSI_ID = $user_login->getField("AKSES_APP_ABSENSI_ID");
            $auth->getStorage()->write($identity);
            return true;

			// if($username == ""){
			// 	return false;
			// }
			// else
			// {
			// 	// $this->getLoginInformation($username);
			// 	return true;
			// }
		}
		else
			return false;
    }
	

    public function getInstance(){
        return Zend_Auth::getInstance();
    }
	
	function getLoginInformation($pegawaiId, $kodeGroup="")
	{
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $CI =& get_instance();
		$CI->load->model("Pegawai");
		$CI->load->model("UserGroup");
		$CI->load->model("AbsensiRekap");

		$pegawai = new Pegawai();
		$pegawai->selectByParamsInformasiLogin(array("A.PEGAWAI_ID" => $pegawaiId));
		if($pegawai->firstRow())
		{
			$identity = new stdClass();
			$identity->ID = $pegawai->getField("PEGAWAI_ID");
			$identity->NAMA = $pegawai->getField("NAMA");
			$identity->USERNAME = $pegawai->getField("PEGAWAI_ID");
			$identity->KODE_CABANG = $pegawai->getField("CABANG_ID");
			$identity->CABANG = $pegawai->getField("NAMA_CABANG");
			$identity->CABANG_LOKASI = $pegawai->getField("LOKASI");
			$identity->KODE_DEPARTEMEN = $pegawai->getField("DEPARTEMEN_ID");
			$identity->DEPARTEMEN = $pegawai->getField("NAMA_DEPARTEMEN");
			$identity->KODE_SUB_DEPARTEMEN = $pegawai->getField("SUB_DEPARTEMEN_ID");
			$identity->SUB_DEPARTEMEN = $pegawai->getField("NAMA_SUB_DEPARTEMEN");
			$identity->KODE_STAFF = $pegawai->getField("STAFF_ID");
			$identity->STAFF = $pegawai->getField("NAMA_STAFF");
			$identity->KODE_FUNGSI = $pegawai->getField("FUNGSI_ID");
			$identity->FUNGSI = $pegawai->getField("NAMA_FUNGSI");
			$identity->KODE_JABATAN = $pegawai->getField("JABATAN_ID");
			$identity->JABATAN = $pegawai->getField("JABATAN");
			$identity->STATUS_PEGAWAI = trim($pegawai->getField("STATUS_PEGAWAI"));
			$identity->CUTI_TAHUNAN_AKTIF = $pegawai->getField("CUTI_TAHUNAN_AKTIF");
			$identity->CUTI_BESAR_AKTIF = $pegawai->getField("CUTI_BESAR_AKTIF");
			$identity->TANGGAL_MASUK = $pegawai->getField("TANGGAL_MASUK");
			$identity->KELOMPOK = $pegawai->getField("KELOMPOK");	
			$identity->JENIS_KELAMIN = $pegawai->getField("JENIS_KELAMIN");	
			$identity->STATUS_NIKAH = $pegawai->getField("STATUS_NIKAH");	
			$identity->STATUS_ATASAN = $pegawai->getField("STATUS_ATASAN");	
			$identity->AGAMA = $pegawai->getField("AGAMA");	
			$identity->PEGAWAI_ID_BAWAHAN = str_replace("#","'",$pegawai->getField("PEGAWAI_ID_BAWAHAN"));
			
			
			/* AKSES */
			if($kodeGroup == "")
			{
				$identity->USER_GROUP_ID = $pegawai->getField("USER_GROUP_ID");
				$identity->AKSES_MASTER = $pegawai->getField("AKSES_MASTER");	
				$identity->AKSES_LAPORAN = $pegawai->getField("AKSES_LAPORAN");	
				$identity->AKSES_UNIT = $pegawai->getField("AKSES_UNIT");	
				$identity->AKSES_PROSES_REKAP = $pegawai->getField("AKSES_PROSES_REKAP");
				$identity->AKSES_PERMOHONAN = $pegawai->getField("AKSES_PERMOHONAN");
				
			}
			else
			{
				$user_group = new UserGroup();
				$user_group->selectByParams(array("A.USER_GROUP_ID" => $kodeGroup));
				$user_group->firstRow();
				
				$identity->USER_GROUP_ID = $user_group->getField("USER_GROUP_ID");	
				$identity->AKSES_MASTER = $user_group->getField("AKSES_MASTER");	
				$identity->AKSES_LAPORAN = $user_group->getField("AKSES_LAPORAN");	
				$identity->AKSES_UNIT = $user_group->getField("AKSES_UNIT");	
				$identity->AKSES_PROSES_REKAP = $user_group->getField("AKSES_PROSES_REKAP");
				$identity->AKSES_PERMOHONAN = $user_group->getField("AKSES_PERMOHONAN");

			}
						
			if($identity->KELOMPOK == "N")		
				$kelompok_keterangan = "Non-Shift";
			else
				$kelompok_keterangan = "Shift";
				
			$identity->KELOMPOK_KETERANGAN = $kelompok_keterangan;			
			$identity->ISLOGIN = 1;
			
			/* APPROVAL NEW*/
			if($identity->CABANG_LOKASI == "KP")
			{
				if(
				$identity->KODE_DEPARTEMEN != 'A' && // BUKAN DIRUT
				(
				$identity->KODE_STAFF == "01" || // DIRUT
				$identity->KODE_STAFF == "02" || // KASAT
				$identity->KODE_STAFF == "03"  // SEKPER
				)
				)
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Direktur Utama";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'01'";
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.JABATAN_ID" => "KP00000B");	
				} // EDIT
				
				
				elseif( // KASAT
				$identity->KODE_JABATAN == "KP00412B" || // KASAT RESIKO
				$identity->KODE_JABATAN == "KP00435B" || // SCM
				$identity->KODE_JABATAN == "KP00399B" || // KASAT SPI
				$identity->KODE_JABATAN == "KP00305B"	 // SEKPER
				)
				{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Utama";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.JABATAN_ID" => "KP00000B");					
				} // EDIT
				
				elseif( // BINAAN
				$identity->KODE_JABATAN == "KP00172B" ||
				$identity->KODE_JABATAN == "KP00173B" ||
				$identity->KODE_JABATAN == "KP00174B" ||
				$identity->KODE_JABATAN == "KP00175B" ||
				$identity->KODE_JABATAN == "KP00176B" ||
				$identity->KODE_JABATAN == "KP00177B" ||
				$identity->KODE_JABATAN == "KP00169B" ||
				$identity->KODE_JABATAN == "KP00170B"	 
				)
				{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);					
				} // EDIT
				
				elseif
				($identity->KODE_DEPARTEMEN == 'I')
				{
					if($identity->KODE_STAFF == "04")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Utama";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.JABATAN_ID" => "KP00000A");	
					}
					elseif( 
					$identity->KODE_STAFF == "06" || 
						((
						$identity->KODE_STAFF == "11" || 
						$identity->KODE_STAFF == "12" || 
						$identity->KODE_STAFF == "13" || 
						$identity->KODE_STAFF == "14") && $identity->KODE_SUB_DEPARTEMEN == "")
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);	
					}
					else
					{
						$identity->APPROVAL[0] = "Asisten Manager";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'06'";
						$identity->APPROVAL_ID[1] = "'04'";			
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);	
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);
					}
				}
				
				/*
				elseif($identity->KODE_DEPARTEMEN == 10) //MANAJEMEN OM
				{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'02'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.JABATAN_ID" => "KP00027A");					
				} // LONCATI KARENA MUNGKIN TIDAK DIPAKAI
				*/
				
				elseif(
				$identity->KODE_DEPARTEMEN == 'E' &&  // keuangan
				$identity->KODE_SUB_DEPARTEMEN == 'E2' &&  //akutansi
				$identity->KODE_STAFF == "04") // MANAGER
				{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);					
				} // EDIT
				
				elseif(
				$identity->KODE_DEPARTEMEN == 'E' &&  //KEUANGAN
				$identity->KODE_SUB_DEPARTEMEN == 'E2' //AKUTANSI
				) 
				{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);					
				}// EDIT
				
				
				
				else
				{
					
					/* JIKA JABATAN ASMAN */
					if($identity->KODE_STAFF == "06") //ASMAN
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);								
					} //EDIT
					
					
					/* JIKA JABATAN SEKRETARIS*/
					elseif(
					(
					$identity->KODE_STAFF == "11" || //analis
					$identity->KODE_STAFF == "12" || // off senior
					$identity->KODE_STAFF == "13" || // off junior 1
					$identity->KODE_STAFF == "14"   // off junior 2
					)&&( 
					$identity->KODE_JABATAN == "KP00330B" ||// KESEKERTARIATAN suraya, vina, vidya, vira
					$identity->KODE_JABATAN == "KP00331B" ||// 
					$identity->KODE_JABATAN == "KP00332B" ||// 
					$identity->KODE_JABATAN == "KP00333B" //
					)
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);	
													
					}//EDIT
					
					
					
					
					elseif(
					(
					$identity->KODE_STAFF == "11" || //analis
					$identity->KODE_STAFF == "12" || // off senior
					$identity->KODE_STAFF == "13" || // off junior 1
					$identity->KODE_STAFF == "14"   // off junior 2
					) 
					&& 
					$identity->KODE_DEPARTEMEN == 'G' // pengawasan internal
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Kepala Satuan";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'02'";		//kasat	
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
					}//EDIT
					
					elseif(
					$identity->KODE_STAFF == "14" && //rina rosiana
					$identity->KODE_DEPARTEMEN == 'H' //risk osm
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Kepala Satuan";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'02'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
					}//EDIT
					
					elseif(
					$identity->KODE_STAFF == "04" && //manager
					$identity->KODE_DEPARTEMEN == 'H' //risk osm
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Kepala Satuan";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'02'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
					}//EDIT
					
					
					elseif(
					$identity->KODE_STAFF == "04" && //manager 
					$identity->KODE_DEPARTEMEN == 'F' //sekper
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Sekper";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'03'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
					}//EDIT
					
					
					
					elseif(
					(
					$identity->KODE_STAFF == "11" || //analis
					$identity->KODE_STAFF == "12" || // off senior
					$identity->KODE_STAFF == "13" || // off junior 1
					$identity->KODE_STAFF == "14"   // off junior 2
					)
					&& //
					$identity->KODE_DEPARTEMEN == 'H'//risk osm
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																	 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);								
					}//EDIT
					
					
					elseif(
					$identity->KODE_DEPARTEMEN == 'H' && //risk osm 
					(
					$identity->KODE_SUB_DEPARTEMEN == 'H1' || // RISK & KEPATUHAN
					$identity->KODE_SUB_DEPARTEMEN == 'H2' // KIN OSM
					)
					)
					{
							$identity->APPROVAL[0] = "";
							$identity->APPROVAL[1] = "Manager";
							$identity->APPROVAL_ID[0] = "''";
							$identity->APPROVAL_ID[1] = "'04'";
							$identity->APPROVAL_STATEMENT[0] = array();
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																	 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);					
					}//EDIT
					
					
					
					elseif($identity->KODE_STAFF == "04") //MANAGER
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
					}//EDIT
					
					
					
					else
					{
						/* JIKA STAFF FUNGSIONAL AHLI - ADMIN DIREKTORAT DESY Z */
						if(
						(
						$identity->KODE_STAFF == "10" ||
						$identity->KODE_STAFF == "11" ||
						$identity->KODE_STAFF == "12" ||
						$identity->KODE_STAFF == "13" ||
						$identity->KODE_STAFF == "14" 
						)
						&& $identity->KODE_FUNGSI == "")
						{	
							$identity->APPROVAL[0] = "";
							$identity->APPROVAL[1] = "Direktur";
							$identity->APPROVAL_ID[0] = "''";
							$identity->APPROVAL_ID[1] = "'01'";			
							$identity->APPROVAL_STATEMENT[0] = array();
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
						}//EDIT
						
						else // KONDISI NORMAL
						{					
							$identity->APPROVAL[0] = "Asisten Manager";
							$identity->APPROVAL[1] = "Manager";
							$identity->APPROVAL_ID[0] = "'06'";
							$identity->APPROVAL_ID[1] = "'04'";			
							$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																	 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN, 
																	 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);	
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																	 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);			
						}//EDIT
					}
				}
			}
			elseif($identity->CABANG_LOKASI == "PR")
			{
				if ($identity->KODE_CABANG == "MK")
				{
					if($identity->KODE_STAFF == "08")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "UJ00080B");
					}
					else 
					{
						$identity->APPROVAL[0] = "Supervisior";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'08'";
						$identity->APPROVAL_ID[1] = "'04'";		
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);
						$identity->APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "UJ00080B");
					}
				} 
				else if($identity->KODE_STAFF == "24") // GM 
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Direktur Operasi";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'01'";		
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
															 "A.DEPARTEMEN_ID" => "C");	
				}
				else if
				(
					$identity->KODE_STAFF == "04" ||
					$identity->KODE_STAFF == "15" ||
					$identity->KODE_STAFF == "16" ||
					$identity->KODE_STAFF == "17" ||
					$identity->KODE_STAFF == "19" ||
					$identity->KODE_STAFF == "21" ||
					$identity->KODE_STAFF == "23"
				)
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "General Manager";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'24'";		
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);
				}
				else if
				(
					$identity->KODE_STAFF == "06" ||
					$identity->KODE_STAFF == "08" 
				)
				{
					$identity->APPROVAL[0] = "Manager";
					$identity->APPROVAL[1] = "General Manager";
					$identity->APPROVAL_ID[0] = "'04'";
					$identity->APPROVAL_ID[1] = "'24'";		
					$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
															 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);
				}
				else
				{
					$identity->APPROVAL[0] = "Supervisior / Asman";
					$identity->APPROVAL[1] = "Manager";
					$identity->APPROVAL_ID[0] = "'08', '06'";
					$identity->APPROVAL_ID[1] = "'04'";		
					$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
															 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
															 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
															 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);
				}
			}
			elseif($identity->CABANG_LOKASI == "UK")
			{
				if($identity->KODE_STAFF == "05")
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Direktur Operasi";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'01'";		
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
															 "A.DEPARTEMEN_ID" => "C");	
				}
				else
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Manager Unit";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'05'";			
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
				}
			}
			else
			{
				if($identity->CABANG_LOKASI == "LS")	
				{
					/* JIKA JABATAN */
					if(
					substr($identity->KODE_JABATAN, -6) == "00001B" ||
					substr($identity->KODE_JABATAN, -6) == "00002B" ||
					substr($identity->KODE_JABATAN, -6) == "00003B"
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Koordinator";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'25'";	 //MANAGER UNIT	
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);											
					} //EDIT
					
					/* JIKA JABATAN FOREMAN */
					else if(
					$identity->KODE_STAFF == "07"	// DEPUTY MANAGER
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'05', '04'";	 //MANAGER UNIT	
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);											
					} //EDIT
					
					 /* JIKA MANAGER UNIT */
					elseif($identity->KODE_STAFF == "05")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Operasi";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																 "A.DEPARTEMEN_ID" => "C");											
					} //EDIT
					
					
					/* JIKA TEKNIK  */
					elseif(
					$identity->KODE_STAFF == "18" || // TEKNIK
					$identity->KODE_STAFF == "19" || 
					$identity->KODE_STAFF == "20" || 
					$identity->KODE_STAFF == "21" ||
					$identity->KODE_STAFF == "22" || 
					$identity->KODE_STAFF == "23" ||
					$identity->KODE_STAFF == "12" || // OFFICER
					$identity->KODE_STAFF == "13" || 
					$identity->KODE_STAFF == "14" 
					)
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'08'";
						$identity->APPROVAL_ID[1] = "'04'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					} //EDIT
					
					
					/* JIKA NON TEKNIK  */
					elseif(
					$identity->KODE_STAFF == "12" || 
					$identity->KODE_STAFF == "13" || 
					$identity->KODE_STAFF == "14"
					)
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "'08'";
						$identity->APPROVAL_ID[1] = "'05'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					}//EDIT
					
					
					/* JIKA NON TEKNIK  */
					
					elseif(
					$identity->KODE_STAFF == "08" //SUPERVISOR
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'05','07','04'"; //MANAGER UNIT ATAU DEPUTY MANAGER	
						$identity->APPROVAL_STATEMENT[0] = array();		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					}//EDIT					
					
					else
					{					
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'08'";
						$identity->APPROVAL_ID[1] = "'05', '04'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);								
					}//EDIT
					
					
				}
				else if($identity->CABANG_LOKASI == "OM") // LUAR JAWA
				{
					if ($identity->KODE_CABANG == "DR") //duri
					{
						if($identity->KODE_STAFF == "05")
						{
							$identity->APPROVAL[0] = "";
							$identity->APPROVAL[1] = "Direktur Operasi";
							$identity->APPROVAL_ID[0] = "''";
							$identity->APPROVAL_ID[1] = "'01'";		
							$identity->APPROVAL_STATEMENT[0] = array();
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																	 "A.DEPARTEMEN_ID" => "C");											
						} 
						else if(
						$identity->KODE_JABATAN == "DR00001B" || 
						$identity->KODE_JABATAN == "DR00002B" || 
						$identity->KODE_JABATAN == "DR00003B" || 
						$identity->KODE_STAFF == "08")
						{
							$identity->APPROVAL[0] = "";
							$identity->APPROVAL[1] = "Manager Unit";
							$identity->APPROVAL_ID[0] = "''";
							$identity->APPROVAL_ID[1] = "'05'";	
							$identity->APPROVAL_STATEMENT[0] = array();		
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);	
						}
						else
						{
							$identity->APPROVAL[0] = "Supervisor";
							$identity->APPROVAL[1] = "Manager Unit";
							$identity->APPROVAL_ID[0] = "'08'";
							$identity->APPROVAL_ID[1] = "'05'";	
							$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																	 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);		
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);	
						}
						/*
						else if(
						$identity->KODE_STAFF == "18" ||
						$identity->KODE_STAFF == "20" ||
						$identity->KODE_STAFF == "22" ||
						$identity->KODE_STAFF == "12" ||
						$identity->KODE_STAFF == "13" ||
						$identity->KODE_STAFF == "14" ||
						$identity->KODE_STAFF == "19" ||
						$identity->KODE_STAFF == "21" ||
						$identity->KODE_STAFF == "23"
						)
						{
							$identity->APPROVAL[0] = "Supervisor";
							$identity->APPROVAL[1] = "Manager Unit";
							$identity->APPROVAL_ID[0] = "'08'";
							$identity->APPROVAL_ID[1] = "'05'";	
							$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																	 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);		
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);	
						}
						*/
					}
					
					/* JIKA JABATAN DEPUTY MANAGER */
					else if($identity->KODE_STAFF == "07")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'05'";	//
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					} //EDIT
					
					
					/* JIKA MANAGER UNIT */
					elseif($identity->KODE_STAFF == "05")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Operasi";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																 "A.DEPARTEMEN_ID" => "C");											
					} //EDIT
					
					
					 /* JIKA STAFF SELAIN SDM DAN ADM*/
					elseif(
					(
					$identity->KODE_STAFF == "18" ||
					$identity->KODE_STAFF == "19" || 
					$identity->KODE_STAFF == "20" || 
					$identity->KODE_STAFF == "21" ||
					$identity->KODE_STAFF == "22" || 
					$identity->KODE_STAFF == "23" ||
					$identity->KODE_STAFF == "12" ||
					$identity->KODE_STAFF == "13" || 
					$identity->KODE_STAFF == "14" || 
					$identity->KODE_STAFF == "26" 		// ELEMENTARY
					) && $identity->KODE_FUNGSI != ""
					)
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Deputy Manager";
						$identity->APPROVAL_ID[0] = "'08'";
						$identity->APPROVAL_ID[1] = "'07'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					}//EDIT
					
					
					
					/* JIKA NON TEKNIK DAN SUB DIREKTORAT ADMINISTRASI */
					elseif(
					(
					$identity->KODE_STAFF == "12" || 
					$identity->KODE_STAFF == "13" || 
					$identity->KODE_STAFF == "14" || 
					$identity->KODE_STAFF == "26"  // ELEMENTARY
					) 
					&& 
					(
					$identity->KODE_SUB_DEPARTEMEN == "J3" ||
					$identity->KODE_SUB_DEPARTEMEN == "K3" ||
					$identity->KODE_SUB_DEPARTEMEN == "K4" 
					) // SUPERVISOR SDM ADM & KEU
					)
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "'08'";
						$identity->APPROVAL_ID[1] = "'05'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					} //EDIT
					
					elseif(//ANGGIT ASAHAN
					(
					$identity->KODE_STAFF == "13" ||
					$identity->KODE_STAFF == "14" 
					)
					 && 
					(
					$identity->KODE_SUB_DEPARTEMEN == "J3" ||
					$identity->KODE_SUB_DEPARTEMEN == "K3" ||
					$identity->KODE_SUB_DEPARTEMEN == "K4" ||
					$identity->KODE_SUB_DEPARTEMEN == "X3" ||
					$identity->KODE_SUB_DEPARTEMEN == "X1" 
					) && // SUPERVISOR SDM ADM & KEU) 
					$identity->KODE_CABANG == "AS"
					)					
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'05'";
						$identity->APPROVAL_STATEMENT[0] = array();		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					} //ANGGIT ASAHAN
					
					elseif(
					$identity->KODE_STAFF == "08"  && 
					(
					$identity->KODE_SUB_DEPARTEMEN == "J3" ||
					$identity->KODE_SUB_DEPARTEMEN == "K3" ||
					$identity->KODE_SUB_DEPARTEMEN == "K4" 
					)
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'05'";	
						$identity->APPROVAL_STATEMENT[0] = array();		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					}	//EDIT	
					
					
					//SELAIN SUPERVISOR ADMINISTRASI & KEUANGAN UNIT					
					elseif($identity->KODE_STAFF == "08")
					{
						$identity->APPROVAL[0] = "Deputy Manager";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "'07'";
						$identity->APPROVAL_ID[1] = "'05'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					} //EDIT			
										
									
					else
					{					
						$identity->APPROVAL[0] = "Deputy Manager";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'07'";
						$identity->APPROVAL_ID[1] = "'05'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);								
					}											
				}				
			}
			
			
			if($identity->APPROVAL[0] == "")
			{
				$identity->APPROVAL1_DISPLAY = "  style=\"display:none\" ";
				$identity->APPROVAL1_REQUIRED = "";
				$identity->APPROVAL_MONITORING = array("NAMA_APPROVAL2", "STATUS_APPROVAL2");	
				$identity->APPROVAL_KOLOM_NULL = ",null,null";	
				$identity->APPROVAL_KOLOM1 = "<th colspan=\"2\" style=\"text-align:center\">Approval I</th>";	
				$identity->APPROVAL_KOLOM2 = "<th>Nama</th><th>Status</th>";	
			}
			else
			{
				$identity->APPROVAL1_DISPLAY = "";
				$identity->APPROVAL1_REQUIRED = " required ";		
				$identity->APPROVAL_MONITORING = array("NAMA_APPROVAL1", "STATUS_APPROVAL1", "NAMA_APPROVAL2", "STATUS_APPROVAL2");				
				$identity->APPROVAL_KOLOM_NULL = ",null,null,null,null";			
				$identity->APPROVAL_KOLOM1 = "<th colspan=\"2\" style=\"text-align:center\">Approval I</th><th colspan=\"2\" style=\"text-align:center\">Approval II</th>";	
				$identity->APPROVAL_KOLOM2 = "<th>Nama</th><th>Status</th><th>Nama</th><th>Status</th>";	
			}

			$CI->load->library("SettingApp"); $settingApp = new SettingApp();
			$identity->TAHUN_CUTI = $settingApp->getSetting("CUTI_TAHUNAN_TAHUN_AKTIF");
			
			$auth->getStorage()->write($identity);
		}
		
	}
		
		
}

?>
