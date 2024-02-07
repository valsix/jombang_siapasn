<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Berita_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/UlasanAsn');

        $user_login_log= new UserLoginLog;
        
        $reqToken= $this->input->get("reqToken");
        $reqId= $this->input->get("reqId");
        $reqRowId= $this->input->get("reqRowId");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $result = array();

                
            $sql = " SELECT * FROM PENGUMUMAN_BERITA A WHERE 1=1 ORDER BY A.TANGGAL DESC LIMIT 100 ";
            
            $arrPengumanBerita = $this->db->query($sql);
            
            $arrBerita = array();
            $idx = 0;
            foreach ($arrPengumanBerita->result_array() as $row) 
            {
                $sql = " SELECT * FROM PENGUMUMAN_BERITA_FILE A WHERE 1=1 AND A.FIELD_ID = '".$row['field_id']."' AND A.JENIS = '".$row['jenis']."' ";

                $data_file = $this->db->query($sql)->row();

                $arrBerita[$idx]['JUDUL']       = $row['nama'];
                $arrBerita[$idx]['JENIS']       = $row['jenis'];

                if ($data_file->link_file != "") {
                    // $arrBerita[$idx]['FOTO']    = 'https://bkpsdm.jombangkab.go.id/'.$data_file->link_file;
                    $arrBerita[$idx]['FOTO']    = 'https://www.valsix.xyz/jombang/web-code/'.$data_file->link_file;
                    // $arrBerita[$idx]['FOTO']    = $this->config->config["base_web"].$data_file->link_file;
                } else {
                    $arrBerita[$idx]['FOTO']    = "";
                }
                
                $arrBerita[$idx]['TANGGAL']     = getFormattedDate($row['tanggal']);


                $page_detil = 'pengumuman_detil';
                if ($row['jenis'] == 'BERITA') 
                {
                    $page_detil = 'berita_detil';
                }
                elseif ($row['jenis'] == 'AGENDA') 
                {
                    $page_detil = 'agenda_detil';
                }

                // $arrBerita[$idx]['LINK_URL']    = 'https://bkpsdm.jombangkab.go.id/portal/index/'.$page_detil.'?reqId='.$row['field_id'];
                $arrBerita[$idx]['LINK_URL']    = 'https://www.valsix.xyz/jombang/web-code/portal/index/'.$page_detil.'?reqId='.$row['field_id'];
                // $arrBerita[$idx]['LINK_URL']    = $arrBerita[$idx]['FOTO']    = $this->config->config["base_web"].'portal/index/'.$page_detil.'?reqId='.$row['field_id'];

                $idx++;
            }
            
            $result = $arrBerita;



            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200,'result' => $result));
        }
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