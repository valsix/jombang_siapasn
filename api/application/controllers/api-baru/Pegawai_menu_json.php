<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pegawai_menu_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/AksiMenu');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		// echo $user_login_log->query;exit();
        $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        // echo $reqPegawaiId;exit();

        $set= new AksiMenu();
        $set->selectbyparams($reqPegawaiId);
        $set->firstRow();

        $aColumns = array("STATUS_DATA_UTAMA", "ICON_DATA_UTAMA", "STATUS_SK_CPNS", "ICON_SK_CPNS", "STATUS_SK_PNS", "ICON_SK_PNS", "STATUS_SK_PPPK", "ICON_SK_PPPK", "STATUS_PANGKAT", "ICON_PANGKAT", "STATUS_GAJI", "ICON_GAJI", "STATUS_JABATAN", "ICON_JABATAN", "STATUS_TUGAS", "ICON_TUGAS", "STATUS_PENDIDIKAN", "ICON_PENDIDIKAN", "STATUS_DIKLAT_STRUKTURAL", "ICON_DIKLAT_STRUKTURAL", "STATUS_DIKLAT_KURSUS", "ICON_DIKLAT_KURSUS", "STATUS_CUTI", "ICON_CUTI", "STATUS_SKP_PPK", "ICON_SKP_PPK", "STATUS_PAK", "ICON_PAK", "STATUS_KOMPETENSI", "ICON_KOMPETENSI", "STATUS_PENGHARGAAN", "ICON_PENGHARGAAN", "STATUS_PENINJAUAN_MASA_KERJA", "ICON_PENINJAUAN_MASA_KERJA", "STATUS_SURAT_TANDA_LULUS", "ICON_SURAT_TANDA_LULUS", "STATUS_SUAMI_ISTRI", "ICON_SUAMI_ISTRI", "STATUS_ANAK", "ICON_ANAK", "STATUS_ORANG_TUA_ADD", "ICON_ORANG_TUA_ADD", "STATUS_SAUDARA", "ICON_SAUDARA", "STATUS_MERTUA_ADD", "ICON_MERTUA_ADD", "STATUS_BAHASA", "ICON_BAHASA");

        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
        }
        $result[] = $row;

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));

    }

	
    // insert new data to entitas
    function index_post() {
    }
    
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}