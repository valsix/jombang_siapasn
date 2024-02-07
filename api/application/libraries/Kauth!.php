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
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("presensi4kemasti"));
    }




    public function localAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

		$CI =& get_instance();

		/* USER AUTH  */

		// Define $username and $password
		// using ldap bind
        $ldaprdn  = 'ho' . "\\" .$username;     // ldap rdn or dn
        $ldappass = $credential;  // associated password

		$ldapconn = ldap_connect("172.16.30.106")
        or die("Could not connect to LDAP server.");

		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

		// binding to ldap server
		$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
		// verify binding

		if ($ldapbind)
		{}
		else
			return true;

		?>
		<script language="javascript">
            alert('<?=$ldapbind?>');
            document.location.href = 'index';
        </script>
        <?


	   $filter="(sAMAccountName=$username)";
	   $result = ldap_search($ldapconn,"dc=ho,dc=pjbservices,dc=com",$filter);
	   ldap_sort($ldapconn,$result,"sn");
	   $info = ldap_get_entries($ldapconn, $result);

	    $username = $info[0]["samaccountname"][0];

		if($username == ""){
			return false;
		}
		else
		{
			$this->getLoginInformation($username);
			return true;
		}
    }



	public function localAuthenticate2($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

		$CI =& get_instance();
		$CI->load->model("users");

		$users = new Users();
		$users->login_anggit($username);
		if($users->firstRow())
		{
            $identity = new stdClass();
            //$identity->ID = $users->getField("USER_LOGIN_ID");
            $identity->PEGAWAI_ID = $users->getField("PEGAWAI_ID");
            /*
			$identity->DEPARTEMEN_ID = $users->getField("DEPARTEMEN_ID");
            $identity->USERNAME = $users->getField("USER_LOGIN");
            $identity->NRP = $users->getField("NRP");
            $identity->NAMA = $users->getField("NAMA");
            $identity->JABATAN = $users->getField("JABATAN");
            $identity->AKSES_APP_HELPDESK_ID = $users->getField("AKSES_APP_HELPDESK_ID");
            $identity->HAK_AKSES = $users->getField("HAK_AKSES");
            $identity->HAK_AKSES_DESC = $users->getField("HAK_AKSES_DESC");
			*/
            $auth->getStorage()->write($identity);

			if($username == ""){
				return false;
			}
			else
			{
				//$this->getLoginInformation($username);
				return true;
			}
		}
		else
			return false;
    }


    public function getInstance(){
        return Zend_Auth::getInstance();
    }


    public function autoAuthenticate($username,$credential)
    {
		//
		ini_set ('soap.wsdl_cache_enabled', 0);
        kloader::load('Zend_Soap_Client');
        $wsdl = 'http://portal.pjbservices.com/index.php/portal_login?wsdl';
        $CI =& get_instance();

        $cl = new SoapClient($wsdl);
        $rv = $cl->loginToken(11, $username, $credential);
        if($rv->RESPONSE == "1")
        {
            $this->getLoginInformation($rv->NID, $rv->KODE_GROUP);
			return $rv;
		}
		else
			return $rv;

    }

    public function autoGroupAuthenticate($username,$credential, $groupId)
    {
		//
		ini_set ('soap.wsdl_cache_enabled', 0);
        kloader::load('Zend_Soap_Client');
        $wsdl = 'http://portal.pjbservices.com/index.php/portal_login?wsdl';
        $CI =& get_instance();

        $cl = new SoapClient($wsdl);
        $rv = $cl->loginGroup(11, $username, $credential, $groupId);
        if($rv->RESPONSE == "1")
        {
            $this->getLoginInformation($rv->NID, $rv->KODE_GROUP);
			return $rv;
		}
		else
			return $rv;

    }


    public function portalAuthenticate($username,$credential)
    {

    	ini_set ('soap.wsdl_cache_enabled', 0);
        kloader::load('Zend_Soap_Client');
        $wsdl = 'http://192.168.88.101/pjbs-ess/portal_login?wsdl';
        $CI =& get_instance();
		
        $cl = new SoapClient($wsdl);
		//$rv = $cl->__soapCall("loginAplikasi", array('aplikasiId'=>1,'userLogin'=>"xxx",'userPassword'=>$credential));
        /*BACKUP NVN*/
        $rv = $cl->loginAplikasi(1, $username, $credential);
		//print_r($rv); exit;
        // $rv->RESPONSE = "PAGE";
        // $rv->NID = $username;
        // $rv->APLIKASI_ID = '1';
        // $rv->RESPONSE_LINK = "http://essportal.centos.ptpjbs.com/login/autologin";
        if($rv->RESPONSE == "1")
        {
            $this->getLoginInformation($rv, $credential);
            return $rv;
        }
        else
        {
            return $rv;
        }

		// ini_set ('soap.wsdl_cache_enabled', 0);
  //       kloader::load('Zend_Soap_Client');
  //       $wsdl = 'http://portal.pjbservices.com/index.php/portal_login?wsdl';

  //       $CI =& get_instance();

  //       $cl = new SoapClient($wsdl);

  //       $rv = $cl->loginAplikasi(11, $username, $credential);

  //       if($rv->RESPONSE == "1")
  //       {
  //           $this->getLoginInformation($rv->NID, $rv->KODE_GROUP);
		// 	return $rv;
		// }
		// else
		// 	return $rv;



    }

	function getLoginInformation($pegawaiId, $kodeGroup="")
	{
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $CI =& get_instance();
		$CI->load->model("Pegawai");
		$CI->load->model("UserGroup");

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
			$identity->PM_PROYEK_AKTIF = $pegawai->getField("PM_PROYEK_AKTIF");
			$identity->TANGGAL_MASUK = $pegawai->getField("TANGGAL_MASUK");
			$identity->KELOMPOK = $pegawai->getField("KELOMPOK");
			$identity->JENIS_KELAMIN = $pegawai->getField("JENIS_KELAMIN");

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
//kantor pusat
			if($identity->CABANG_LOKASI == "KP")
			{
				//app dirut
				if(
				$identity->KODE_DEPARTEMEN == 'A' && //  DIRUT
				
				$identity->KODE_STAFF == "01" &&// DIRUT
				
				$identity->KODE_JABATAN == "KP00000C"//DIRUT
				)
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Dewan Komisaris Utama";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'00'";
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.JABATAN_ID" => "KPDK0000");	
					
				}
				
				elseif(
				$identity->KODE_DEPARTEMEN == 'A' && //  DIRUT
				
				$identity->KODE_STAFF == "01" &&// DIRUT
				
				$identity->KODE_JABATAN == "KP00000C"//DIRUT
				)
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Dewan Komisaris Utama";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'00'";
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																	 "A.JABATAN_ID" => "KPDK0000");	
					
				}
				elseif(
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
																	 "A.JABATAN_ID" => "KP00000C");
				} // EDIT


				elseif( // KASAT
				$identity->KODE_JABATAN == "KP00354C" || // KASAT RESIKO
				$identity->KODE_JABATAN == "KP00372C" || // SCM
				$identity->KODE_JABATAN == "KP00343C" || // KASAT SPI
				$identity->KODE_JABATAN == "KP00290C"	 // SEKPER
				)
				{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Utama";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.JABATAN_ID" => "KP00000C");
				} // EDIT

				elseif( // BINAAN TKARYA MAGANG
				$identity->KODE_JABATAN == "KP00214C" ||
				$identity->KODE_JABATAN == "KP00215C" ||
				$identity->KODE_JABATAN == "KP00216C" ||
				$identity->KODE_JABATAN == "KP00217C" ||
				$identity->KODE_JABATAN == "KP00218C" ||
				$identity->KODE_JABATAN == "KP00219C" ||
				
				$identity->KODE_JABATAN == "KP00220C" || // TKARYA
				$identity->KODE_JABATAN == "KP00221C" || // TKARYA
				$identity->KODE_JABATAN == "KP00222C" || // TKARYA
				$identity->KODE_JABATAN == "KP00223C" || // TKARYA
				$identity->KODE_JABATAN == "KP00224C" || // TKARYA
				$identity->KODE_JABATAN == "KP00225C" || // TKARYA
				$identity->KODE_JABATAN == "KP00226C" || // TKARYA
				$identity->KODE_JABATAN == "KP00227C" || // TKARYA
				$identity->KODE_JABATAN == "KP00228C" || // TKARYA
				$identity->KODE_JABATAN == "KP00229C" || // TKARYA
				
				$identity->KODE_JABATAN == "KP00245C" || // MAGANG
				$identity->KODE_JABATAN == "KP00246C" || // MAGANG
				$identity->KODE_JABATAN == "KP00247C" || // MAGANG
				$identity->KODE_JABATAN == "KP00248C" || // MAGANG
				$identity->KODE_JABATAN == "KP00249C" || // MAGANG
				$identity->KODE_JABATAN == "KP00250C" || // MAGANG
				$identity->KODE_JABATAN == "KP00251C" || // MAGANG
				$identity->KODE_JABATAN == "KP00252C"    // MAGANG
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

				elseif //SCM
				($identity->KODE_DEPARTEMEN == 'AD')
				{
					if($identity->KODE_STAFF == "04")//MANAGER KP SCM
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Utama";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.JABATAN_ID" => "KP00000C");
					}
					elseif(
					$identity->KODE_STAFF == "06" || //ASMAN SCM
						((
						$identity->KODE_STAFF == "42" ||//
						$identity->KODE_STAFF == "43" 
						) && $identity->KODE_SUB_DEPARTEMEN == "")
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
				// DIREKTORAT keuangan
				$identity->KODE_DEPARTEMEN == 'E' &&  
				$identity->KODE_SUB_DEPARTEMEN == 'E2' &&  //SUBDIT akutansi
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


/* JIKA JABATAN SEKRETARIS - DINON AKTIFKAN SEJAK KODE C DI EDIT PER 09/06/2017*/ 
					elseif(
					(
					/*$identity->KODE_STAFF == "11" || //analis
					$identity->KODE_STAFF == "12" || // off senior
					$identity->KODE_STAFF == "13" || // off junior 1
					$identity->KODE_STAFF == "14"   // off junior 2*/
					$identity->KODE_STAFF == "43" || // JUN OFFICER
					$identity->KODE_STAFF == "42" || // AS OFFICER
					$identity->KODE_STAFF == "38" || // JUN ANALIS
					$identity->KODE_STAFF == "37" || // AS ANALIS
					$identity->KODE_STAFF == "35" || // SEN SPESIALIST 2
					$identity->KODE_STAFF == "11"  // ANALIS
					)&&(
					/*$identity->KODE_JABATAN == "KP00330B" ||// KESEKERTARIATAN suraya, vina, vidya, vira
					$identity->KODE_JABATAN == "KP00331B" ||//
					$identity->KODE_JABATAN == "KP00332B" ||//
					$identity->KODE_JABATAN == "KP00333B" //*/
					$identity->KODE_JABATAN == "KP00335C" //SEKERTARIS [VINA]
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

					}
					//EDIT



					elseif(
					(
					$identity->KODE_STAFF == "43" || // JUN OFFICER
					$identity->KODE_STAFF == "42" || // AS OFFICER
					$identity->KODE_STAFF == "38" || // JUN ANALIS
					$identity->KODE_STAFF == "37" || // AS ANALIS
					$identity->KODE_STAFF == "35" || // SEN SPESIALIST 2
					$identity->KODE_STAFF == "11"  // ANALIS
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
					
					/*elseif // O&M
					(
					$identity->KODE_FUNGSI == 'C106' // O&M RENDAL HAR 1
					)
					{
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[1] = "'04'";		// MANAGER
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);
					}*/

					elseif(
					(
					$identity->KODE_STAFF == "42" || 
					$identity->KODE_STAFF == "43") && //rina rosiana
					$identity->KODE_DEPARTEMEN == 'AC' //risk osm
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
					$identity->KODE_DEPARTEMEN == 'AC' //risk osm
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
					$identity->KODE_STAFF == "11" || // ANALIS
					$identity->KODE_STAFF == "37" || // AS ANALIS
					$identity->KODE_STAFF == "38" // JUN ANALIS
					)
					&& //
					$identity->KODE_DEPARTEMEN == 'AC'//risk osm
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
					$identity->KODE_DEPARTEMEN == 'AC' && //risk osm
					(
					$identity->KODE_SUB_DEPARTEMEN == 'H1' || // MAN RESK KEPATUKAN
					$identity->KODE_SUB_DEPARTEMEN == 'H3' || // MUTU
					$identity->KODE_SUB_DEPARTEMEN == 'H4' // K3
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

					/*elseif(
					($identity->KODE_STAFF == "12" ||
					$identity->KODE_STAFF == "13"||
					$identity->KODE_STAFF == "14")&&
					$identity->KODE_DEPARTEMEN == 'C' &&  
					$identity->KODE_SUB_DEPARTEMEN == 'C2') 
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);
					}//EDIT*/

					else
					{
						/* JIKA STAFF FUNGSIONAL AHLI - ADMIN DIREKTORAT DESY Z */
						if(
						(
						$identity->KODE_STAFF == "42" ||
						$identity->KODE_STAFF == "43" ||
						$identity->KODE_STAFF == "40" ||
						$identity->KODE_STAFF == "35" ||
						$identity->KODE_STAFF == "30" ||
						$identity->KODE_STAFF == "16" 
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
			
			
// proyek
			elseif($identity->CABANG_LOKASI == "PR")
			{
				if ($identity->KODE_CABANG == "BE") //BELAWAN
				{
					if($identity->KODE_JABATAN == "KP00142C")
					{
						$identity->APPROVAL[0] = "Asman SAR dan KOM Proyek";
						$identity->APPROVAL[1] = "Manager Niaga Proyek";
						$identity->APPROVAL_ID[0] = "'06'";
						$identity->APPROVAL_ID[1] = "'04'";
						$identity->APPROVAL_STATEMENT[0] = array("A.JABATAN_ID" => "KP00133C");
						$identity->APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "KP00132C");
					}
				}
				elseif ($identity->KODE_CABANG == "MK") // belum
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
					$identity->APPROVAL[1] = "Direktur Proyek";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'01'";
					$identity->APPROVAL_STATEMENT[0] = array();
					//$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", "A.DEPARTEMEN_ID" => "C");
					$identity->APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "KP000125C");
				}
				else if
				(
					$identity->KODE_STAFF == "04" ||
					($identity->KODE_STAFF == "16" && $identity->KODE_JABATAN == "UJ00001C") ||
					($identity->KODE_STAFF == "17" && $identity->KODE_JABATAN == "UJ00002C") ||
					($identity->KODE_STAFF == "30" && $identity->KODE_JABATAN == "UJ00003C") ||
					($identity->KODE_STAFF == "31" && $identity->KODE_JABATAN == "UJ00004C") ||
					//$identity->KODE_STAFF == "21" || NVN
					($identity->KODE_STAFF == "43" && $identity->KODE_JABATAN == "UJ00005C") ||
					($identity->KODE_STAFF == "37" && $identity->KODE_JABATAN == "UJ00007C") ||
					($identity->KODE_STAFF == "11" && $identity->KODE_JABATAN == "UJ00006C") 
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
					$identity->KODE_STAFF == "07" || // DM
					$identity->KODE_STAFF == "16" ||
					($identity->KODE_STAFF == "17" && $identity->KODE_JABATAN == "UJ00010C") ||
					($identity->KODE_STAFF == "27" && $identity->KODE_JABATAN == "UJ00011C")
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
				else if
				($identity->KODE_STAFF == "28" && $identity->KODE_JABATAN == "UJ00049C") //nambah dewanggga
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Manager";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'04'";
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "UJ00008C");
				}
				else
				{
					$identity->APPROVAL[0] = "DM";
					$identity->APPROVAL[1] = "Manager";
					//$identity->APPROVAL_ID[0] = "'08', '06'";
					$identity->APPROVAL_ID[0] = "'07'";
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
			
			
// unit kecil
			elseif($identity->CABANG_LOKASI == "UK")
			{
				if($identity->KODE_STAFF == "05") // manager unit bawean
				{
					//$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Direktur O&M";
					//$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'01'";
					//$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
															 "A.DEPARTEMEN_ID" => "Z");
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
// om & ls
			else
			{
				
// unit luar jawa labor supplay

				if($identity->CABANG_LOKASI == "LS")
				{
					if ($identity->KODE_CABANG == "CNG") //CNG
					{
						if($identity->KODE_STAFF == "33")
						{
							$identity->APPROVAL[0] = "Supervisior";
							$identity->APPROVAL[1] = "Manager Unit";
							$identity->APPROVAL_ID[0] = "'08'";
							$identity->APPROVAL_ID[1] = "'04'";
							$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);
						}
					}
					
					/* JIKA JABATAN */
					elseif( //admin unit
					substr($identity->KODE_JABATAN, -6) == "00001C" ||
					substr($identity->KODE_JABATAN, -6) == "00002C" 
					//substr($identity->KODE_JABATAN, -6) == "00003B"
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Koordinator";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'25'";	 //koordinator UNIT
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
						$identity->APPROVAL[1] = "Manager"; //unit
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
						$identity->APPROVAL[1] = "Direktur O&M";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
																 "A.DEPARTEMEN_ID" => "Z");
					} //EDIT


					/* JIKA TEKNIK  */
					elseif( // belum
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
					$identity->KODE_STAFF == "42" ||
					$identity->KODE_STAFF == "43" ||
					$identity->KODE_STAFF == "37" ||
					$identity->KODE_STAFF == "38"
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
				
//unit luar jawa
				else if($identity->CABANG_LOKASI == "OM") // LUAR JAWA
				{
					if ($identity->KODE_CABANG == "DR") //duri
					{
						if($identity->KODE_STAFF == "05")//manager unit
						{
							$identity->APPROVAL[0] = "";
							$identity->APPROVAL[1] = "Direktur O&M";
							$identity->APPROVAL_ID[0] = "''";
							$identity->APPROVAL_ID[1] = "'01'";
							$identity->APPROVAL_STATEMENT[0] = array();
							$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
																	 "A.DEPARTEMEN_ID" => "Z");
						}
						else if(
						$identity->KODE_JABATAN == "DR00001C" ||
						$identity->KODE_JABATAN == "DR00002C" ||
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
						else// staff biasa
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
// bukan DURI
/* JIKA JABATAN DEPUTY MANAGER */
					else if(
					$identity->KODE_STAFF == "07" 
					//||
					//$identity->KODE_STAFF == "08" // tambahan
					)
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'05'";	//manager unit
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);
					} //EDIT


/* JIKA MANAGER UNIT */
					elseif($identity->KODE_STAFF == "05")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur O&M";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
																 "A.DEPARTEMEN_ID" => "Z");
					} //EDIT


					



					/* JIKA NON TEKNIK DAN ADMINISTRASI */
					elseif(
					(
					$identity->KODE_STAFF == "42" ||
					$identity->KODE_STAFF == "43" ||
					$identity->KODE_STAFF == "37" || 
					$identity->KODE_STAFF == "38"
					)
					&&
					(
					$identity->KODE_SUB_DEPARTEMEN == "J3" || //
					$identity->KODE_SUB_DEPARTEMEN == "K3" || //
					$identity->KODE_SUB_DEPARTEMEN == "K4" || //
					$identity->KODE_SUB_DEPARTEMEN == "Q4" ||
					$identity->KODE_SUB_DEPARTEMEN == "T3" ||
					$identity->KODE_SUB_DEPARTEMEN == "X3" 
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
					
					 /* JIKA STAFF SELAIN SDM DAN ADM*/
					elseif(
					(
					$identity->KODE_STAFF == "27" || //ass enginer
					$identity->KODE_STAFF == "28" || //jun enginer
					$identity->KODE_STAFF == "37" || //ass analis
					$identity->KODE_STAFF == "38" || //jun analis
					$identity->KODE_STAFF == "32" || //ass opr 
					$identity->KODE_STAFF == "33"  //jun opr
				
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
					
					

//asahan
					elseif(//ANGGIT ASAHAN
					(
					//$identity->KODE_STAFF == "37" ||
					$identity->KODE_STAFF == "38" ||
					//$identity->KODE_STAFF == "42" ||
					$identity->KODE_STAFF == "43" 
					)
					 &&
					//(
					//$identity->KODE_SUB_DEPARTEMEN == "J3" ||
					//$identity->KODE_SUB_DEPARTEMEN == "K3" ||
					//$identity->KODE_SUB_DEPARTEMEN == "K4" ||
					//$identity->KODE_SUB_DEPARTEMEN == "X3"
					//) && // SUPERVISOR SDM ADM & KEU)
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
					} //ANGGIT MODIF ASAHAN


					elseif(
					$identity->KODE_STAFF == "08"  &&// SUPERVISOR
					(
					$identity->KODE_SUB_DEPARTEMEN == "J3" || //
					$identity->KODE_SUB_DEPARTEMEN == "K3" || //
					$identity->KODE_SUB_DEPARTEMEN == "K4" || //
					$identity->KODE_SUB_DEPARTEMEN == "Q4" ||
					$identity->KODE_SUB_DEPARTEMEN == "T3" ||
					$identity->KODE_SUB_DEPARTEMEN == "X3" 
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
