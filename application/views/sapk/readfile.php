<?
$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('base-api/DataCombo');
$id= $this->input->get("id");

$arrparam= ["id"=>$id, "vurl"=>"Data_file_json"];
$set= new DataCombo();
$urltes= $set->selectby("", "", $arrparam, "", "data");
// print_r($urltes);exit;

header('Cache-Control: public'); 
header('Content-type: application/pdf');
// header("Content-Disposition: inline; filename=xx");
header('Content-Length: '.strlen($urltes));
echo $urltes;
exit;
?>