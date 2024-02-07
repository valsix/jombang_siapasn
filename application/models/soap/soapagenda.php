<? 

include_once(APPPATH.'/models/SoapData.php');

class SoapAgenda extends SoapData{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function SoapAgenda()
    {
    	$this->SoapData(); 
    }

    function selectCount()
    {
    	return $this->jumlahRow();
    }

    function selectByParamsInfo($bidang="1")
    {
    	$this->selectLimit("service", "mMnLpZaV&key=mMnLpZaVtJyfyqicn6uYn1exwpdeWILVo5qY0Ms%3D&bidang=".$bidang, "agenda");
    }

    function selectByParamsDetilInfo($detil)
    {
    	$this->selectLimit("service2", "m8famJugxZ6X05qU&key=m8famJugxZ6X05qUgZyknKnPz6mRoFixl5plgq6g0sST08k%3D", "detailagenda", "&agenda=".$detil);
    }

} 
?>