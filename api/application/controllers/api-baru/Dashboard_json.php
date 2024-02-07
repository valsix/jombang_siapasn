<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Dashboard_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/UlasanAsn');
        $this->load->model('base-new/Absensi');

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
            $infoperiode= date('mY');
            $n= ltrim(date("d"),'0');

            $setrekap= new Absensi();
            $setrekap->selectByDataRekapPegawai(array(),-1,-1, $infoperiode, $reqPegawaiId);
            // echo $setrekap->query; exit;
            $setrekap->firstRow();
            $LOG_MASUK =  $setrekap->getField(trim('JAM_MASUK'."_".$n));
            $LOG_KELUAR = $setrekap->getField(trim('JAM_PULANG'."_".$n));

            $result['LOG_MASUK'] = coalesce($LOG_MASUK, "--:--");
            $result['LOG_PULANG'] = coalesce($LOG_KELUAR, "--:--");




            $ulasan_asn = new UlasanAsn;
            $aColumns = array("ULASAN_ASN_ID","PEGAWAI_ID", "JUDUL", "KETERANGAN", "TANGGAL");

            $statement= " AND A.PEGAWAI_ID = '$reqPegawaiId' ";
            
            $ulasan_asn->selectByParams(array(), 3, -1, $statement);
            // echo $ulasan_asn->query;exit();

            $arrUlasanAsn = array();
            while($ulasan_asn->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TANGGAL")
                        $row[trim($aColumns[$i])] = getFormattedDate($ulasan_asn->getField(trim($aColumns[$i])));
                    else
                        $row[trim($aColumns[$i])] = $ulasan_asn->getField(trim($aColumns[$i]));
                }

                $arrUlasanAsn[] = $row;
            }

            $result['ULASAN_ASN'] = $arrUlasanAsn;

            $sql = " SELECT * FROM PENGUMUMAN_BERITA A WHERE 1=1 ORDER BY A.TANGGAL DESC LIMIT 6 ";
            
            $arrPengumanBerita = $this->db->query($sql);
            
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

                $idx++;
            }

            $result['BERITA'] = $arrBerita;
            



            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
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