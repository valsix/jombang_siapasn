<?php

require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class pegawai_sppd_permohonan_add_json extends REST_Controller {

    function __construct() {
        parent::__construct();
    }
 
	function index_get() {
		$err_code = 0;
		$err_message = "";
		$err_status = "success";

		$headers = array(
			'Content-Type: application/json',
			'Cache-Control: no-cache',
			// 'Authorization: Basic YWRtaW46YWRtaW4='
		);
		//http://172.16.30.244/voservices/sppdrest/pegawai?filter=
		// $reqNama='ERICK';
		$reqNid = $this->input->get("reqNid");
		// echo $reqFilter;exit;

		$json = file_get_contents("http://172.16.30.244/voservices/sppdrest/prmhn/new/".$reqNid);
		$data=array();
		$data = json_decode($json, true);
		// echo $data[0];exit;
		// print_r($data);exit;

		$dataArr=array();
		if ($data) {

			$dataArr = array(
								"id" 				=>   $data['id'],
								"tanggal" 			=>   $data['tanggal'],
								"pemohon" 			=>   $data['pemohon'],
								"pemohonNama" 		=>   $data['pemohonNama'],
								"pemohonJabatan" 	=>   $data['pemohonJabatan'],
								"jenisPerjln" 		=>   $data['jenisPerjln'],
								"mtugasKode" 		=>   $data['mtugasKode'],
								"mtugasKet" 		=>   $data['mtugasKet'],
								"maksud" 			=>   $data['maksud'],
								"asalKota" 			=>   $data['asalKota'],
								"tujuanKota" 		=>   $data['tujuanKota'],
								"tambahanSebelum" 	=>   $data['tambahanSebelum'],
								"mulaiAcara" 		=>   $data['mulaiAcara'],
								"sampaiAcara" 		=>   $data['sampaiAcara'],
								"tambahanSesudah" 	=>   $data['tambahanSesudah'],
								"tambahanHari" 		=>   $data['tambahanHari'],
								"mulaiTanggal" 		=>   $data['mulaiTanggal'],
								"sampaiTanggal" 	=>   $data['sampaiTanggal'],
								"status" 			=>   $data['status'],
								"ketStatus" 		=>   $data['ketStatus'],
								"kodeSppd" 			=>   $data['kodeSppd'],
								"namaFile" 			=>   $data['namaFile'],
								"tipeFile" 			=>   $data['tipeFile'],
								"issubmit"			=>	 $data['issubmit'],
			);

		}else {
			$err_code++;
			$err_message= "Data Kosong";
		}

		if ($err_code) {
			$err_status = "FAIL";
		}

		$this->response(array('status' => $err_status, 'message' => $err_message, 'code' => $err_code,'result' => $dataArr)
		);
	}	

	function index_post() {
		$err_code = 0;
		$err_message = "";
		$err_status = "success";

		// $bank = new Bank();

		// $bank->selectByParams(array(), -1, -1, " AND STATUS_SINKRONISASI = '0'");
		// echo $bank->query; 
		// $total = 0;
		// while ($bank->nextRow()) {

		// var_dump($_FILES['reqUploadFile']); exit;
		/*
			$fields = array
			(
				'id'				=> $this->input->post("reqId"),
				'tanggal'			=> $this->input->post("reqTanggal"),
				'pemohon'			=> $this->input->post("reqPemohon"),
				'pemohonNama'		=> $this->input->post("reqPemohonNama"),
				'pemohonJabatan'	=> $this->input->post("reqPemohonJabatan"),
		        'jenisPerjln'		=> $this->input->post("reqJenisPerjln"),
		        'mtugasKode'		=> $this->input->post("reqMtugasKode"),
		        'mtugasKet'			=> $this->input->post("reqMtugasKet"),
		        'maksud'			=> $this->input->post("reqMaksud"),
		        'asalKota'			=> $this->input->post("reqAsalKota"),
		        'tujuanKota'		=> $this->input->post("reqTujuanKota"),
		        'tambahanSebelum'	=> $this->input->post("reqTambahanSebelum"),
		        'mulaiAcara'		=> $this->input->post("reqMulaiAcara"),
		        'sampaiAcara'		=> $this->input->post("reqSampaiAcara"),
		        'tambahanSesudah'	=> $this->input->post("reqTambahanSesudah"),
		        'tambahanHari'		=> $this->input->post("reqTambahanHari"),
		        'mulaiTanggal'		=> $this->input->post("reqMulaiTanggal"),
		        'sampaiTanggal'		=> $this->input->post("reqSampaiTanggal"),
		        'status'			=> $this->input->post("reqStatus"),
		        'ketStatus'			=> $this->input->post("reqKetStatus"),
		        'kodeSppd'			=> $this->input->post("reqKodeSppd"),
		        'namaFile'			=> $this->input->post("reqNamaFile"),
		        'tipeFile'			=> $this->input->post("reqTipeFile"),
		        'issubmit'			=> $this->input->post("issubmit"),
			);
			
			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			
			#Send Reponse To FireBase Server	
			
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'http://172.16.30.244/voservices/sppdrest/prmhns/' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch);
			// var_dump($result);
			// echo $result; 
			$res = json_decode($result);
			$result = json_decode(json_encode($res), true);
			$reqId = $result['createdobj']['id'];

			curl_close( $ch );

			if($reqId == "")
			{}
			else
			{
				*/
				$reqId = '89';
				// $url = "http://172.16.30.244/voservices/sppdrest/prmhns/".$reqId."/upload";
				// $filename = $_FILES['reqUploadFile']['name'];
				// $filedata = $_FILES['reqUploadFile']['tmp_name'];
				// $filesize = $_FILES['reqUploadFile']['size'];
				// $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
			 //    $postfields = array("filedata" => "@$filedata", "filename" => $filename);
			 //    $ch = curl_init();
			 //    $options = array(
			 //        CURLOPT_URL => $url,
			 //        CURLOPT_HEADER => true,
			 //        CURLOPT_POST => 1,
			 //        CURLOPT_HTTPHEADER => $headers,
			 //        CURLOPT_POSTFIELDS => $postfields,
			 //        CURLOPT_INFILESIZE => $filesize,
			 //        CURLOPT_RETURNTRANSFER => true
			 //    ); // cURL options
			 //    curl_setopt_array($ch, $options);
			 //    $res_file = curl_exec($ch);
			 //    curl_close($ch);


				$url = "http://172.16.30.244/voservices/sppdrest/prmhns/".$reqId."/upload";

				$filename  = $_FILES['reqUploadFile']['tmp_name'];
				$headers = array("Content-Type:multipart/form-data"); 
				$params = array('uploadFile' => "@".realpath($filename));

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);

				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				curl_setopt($ch, CURLOPT_POST, true);

				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

				$res_file = curl_exec($ch);

				curl_close($ch);



				// $filename  = $_FILES['reqUploadFile']['tmp_name'];
				// $handle    = fopen($filename, "r");
				// $data      = fread($handle, filesize($filename));
				// $POST_DATA = array(
				// 'uploadFile' => $data
				// );
				// $curl = curl_init();
				// curl_setopt($curl, CURLOPT_URL, "http://172.16.30.244/voservices/sppdrest/prmhns/".$reqId."/upload");
				// curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				// curl_setopt($curl, CURLOPT_POST, 1);
				// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($curl, CURLOPT_POSTFIELDS, $POST_DATA);
				// $res_file = curl_exec($curl);
				// curl_close ($curl);

			    echo $res_file; exit;
			/*
			}
			*/
			// access title of $book object
			// if ($res->code == '200')
			// {
			// 	$bank->setField("FIELD", 'STATUS_SINKRONISASI');
			// 	$bank->setField("FIELD_VALUE", '1');
			// 	$bank->setField("BANK_ID", $bank->getField("BANK_ID"));

			// 	$bank->updateByField();
			// 	$total++;
			// }
			// else
			// {
			// 	echo $result;
			// }


			# code...
		// }

		$this->response(array(
				'status' => $err_status, 
				'message' => $err_message, 
				'code' => $err_code,
				'result' => $res_file
			)
		);
	}

   // update data entitas
    function index_put() {
    	
    }
 
    // delete entitas
    function index_delete() {
		
		$reqId = $this->input->get("reqId");
    	$url = "http://172.16.30.244/voservices/sppdrest/prmhns/".$reqId;

	    $ch = curl_init();
	    
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $result = curl_exec($ch);
	    // var_dump($result);
	    $result = json_decode($result);
	    curl_close($ch);

	    $this->response(array('status' => 'success', 'message' => $err_message, 'code' => $err_code, 'result' => $result)
		);
	}
	
	function sendRequest($option) {
		$username = 'admin';
		$password = 'admin';
		$version = "1.0";

		$ch = curl_init();
		$url = 'http://172.16.33.162/web/webservices/rest.php?version=' . $version . '&json_data=' . urlencode($option);
		// echo $option;
		curl_setopt_array($ch, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_TIMEOUT => 500,
		  CURLOPT_USERPWD => "$username:$password",
		));

		$response = curl_exec($ch);
		$res = json_decode($response);
		curl_close($ch);
		
		return $res;
	}

}