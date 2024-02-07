<?
//Load file content

$reqLinkFile= $this->input->get("reqLinkFile");


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(file_exists($reqLinkFile))
{
	
	$namagenerate =generateRandomString().".pdf";
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . basename($namagenerate).'"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($reqLinkFile));
	ob_clean();
	flush();
	readfile($reqLinkFile);
	exit;          

}
else
{
	echo "File Tidak Ditemukan";
}


?>

