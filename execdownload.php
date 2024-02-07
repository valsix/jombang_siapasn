<?php
$reqControllers = $argv[1]; // contoh audit_json
$reqFunction = $argv[2];   //  posting_kertas_kerja_email
$reqLogId = $argv[3];		   //  1
$reqUser = $argv[4];		   //  1

// $reqControllers= "surat/download_json";
// $reqFunction= "getdownload";
// $reqLogId= "asd";

$baseUrl = "http://localhost/jombang/siapasn/";
// $baseUrl = "http://192.168.88.100/jombang/siapasn/";
// $baseUrl = "https://siapasn.jombangkab.go.id/";

$link = $reqControllers."/".$reqFunction."/?token=ex3c&reqLogId=".$reqLogId."&reqUser=".$reqUser;
// $link = $reqControllers."/".$reqFunction."/?";

$data = array('reqLogId' => $reqLogId, 'reqUser' => $reqUser);
// print_r($data);exit;

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
    ,
    "ssl"=>array(
    	"verify_peer"=>false,
    	"verify_peer_name"=>false,
    )
);
$context  = stream_context_create($options);
// echo $baseUrl.$link;exit;
$result = file_get_contents($baseUrl.$link, false, $context);
echo $result;
?>