<?php
$this->load->model('biotime/VPersonnelArea');
$set= new VPersonnelArea();
$set->selectByParams();

while($set->nextRow())
{
	echo $set->getField("AREA_NAME")."<br/>";
}
?>