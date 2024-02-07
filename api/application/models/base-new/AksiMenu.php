<? 
include_once(APPPATH.'/models/Entity.php');

class AksiMenu extends Entity{ 

	var $query;
    function AksiMenu()
	{
      $this->Entity(); 
    }

    function selectbyparams($pegawaiid="", $statement= "", $order='ORDER BY A.MENU_AKSI_ID DESC')
	{
		$str = "
		SELECT *
		FROM
		(
			SELECT * FROM validasi.aksi_menu WHERE MENU_AKSI_ID = -1
			UNION ALL
			SELECT * FROM validasi.aksi_menu WHERE MENU_AKSI_ID = ".$pegawaiid."
		) A
		WHERE 1=1
		";

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

} 
?>