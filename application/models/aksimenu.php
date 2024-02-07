<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');
  
  class AksiMenu extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function AksiMenu()
	{
      $this->Entity(); 
    }

	function insert()
	{
		$str = "
		INSERT INTO validasi.aksi_menu 
		(
			MENU_AKSI_ID, STATUS_DATA_UTAMA, ICON_DATA_UTAMA, STATUS_SK_CPNS, ICON_SK_CPNS, STATUS_SK_PNS, ICON_SK_PNS
			, STATUS_SK_PPPK, ICON_SK_PPPK, STATUS_PANGKAT, ICON_PANGKAT, STATUS_GAJI, ICON_GAJI, STATUS_JABATAN
			, ICON_JABATAN, STATUS_TUGAS, ICON_TUGAS, STATUS_PENDIDIKAN, ICON_PENDIDIKAN, STATUS_DIKLAT_STRUKTURAL
			, ICON_DIKLAT_STRUKTURAL, STATUS_DIKLAT_KURSUS, ICON_DIKLAT_KURSUS, STATUS_CUTI, ICON_CUTI, STATUS_SKP_PPK
			, ICON_SKP_PPK, STATUS_PAK, ICON_PAK, STATUS_KOMPETENSI, ICON_KOMPETENSI, STATUS_PENGHARGAAN
			, ICON_PENGHARGAAN, STATUS_PENINJAUAN_MASA_KERJA, ICON_PENINJAUAN_MASA_KERJA, STATUS_SURAT_TANDA_LULUS
			, ICON_SURAT_TANDA_LULUS, STATUS_SUAMI_ISTRI, ICON_SUAMI_ISTRI, STATUS_ANAK, ICON_ANAK
			, STATUS_ORANG_TUA_ADD, ICON_ORANG_TUA_ADD, STATUS_SAUDARA, ICON_SAUDARA, STATUS_MERTUA_ADD
			, ICON_MERTUA_ADD, STATUS_BAHASA, ICON_BAHASA
		) 
		VALUES 
		(
			".$this->getField("MENU_AKSI_ID")."
			, '".$this->getField("STATUS_DATA_UTAMA")."'
			, '".$this->getField("ICON_DATA_UTAMA")."'
			, '".$this->getField("STATUS_SK_CPNS")."'
			, '".$this->getField("ICON_SK_CPNS")."'
			, '".$this->getField("STATUS_SK_PNS")."'
			, '".$this->getField("ICON_SK_PNS")."'
			, '".$this->getField("STATUS_SK_PPPK")."'
			, '".$this->getField("ICON_SK_PPPK")."'
			, '".$this->getField("STATUS_PANGKAT")."'
			, '".$this->getField("ICON_PANGKAT")."'
			, '".$this->getField("STATUS_GAJI")."'
			, '".$this->getField("ICON_GAJI")."'
			, '".$this->getField("STATUS_JABATAN")."'
			, '".$this->getField("ICON_JABATAN")."'
			, '".$this->getField("STATUS_TUGAS")."'
			, '".$this->getField("ICON_TUGAS")."'
			, '".$this->getField("STATUS_PENDIDIKAN")."'
			, '".$this->getField("ICON_PENDIDIKAN")."'
			, '".$this->getField("STATUS_DIKLAT_STRUKTURAL")."'
			, '".$this->getField("ICON_DIKLAT_STRUKTURAL")."'
			, '".$this->getField("STATUS_DIKLAT_KURSUS")."'
			, '".$this->getField("ICON_DIKLAT_KURSUS")."'
			, '".$this->getField("STATUS_CUTI")."'
			, '".$this->getField("ICON_CUTI")."'
			, '".$this->getField("STATUS_SKP_PPK")."'
			, '".$this->getField("ICON_SKP_PPK")."'
			, '".$this->getField("STATUS_PAK")."'
			, '".$this->getField("ICON_PAK")."'
			, '".$this->getField("STATUS_KOMPETENSI")."'
			, '".$this->getField("ICON_KOMPETENSI")."'
			, '".$this->getField("STATUS_PENGHARGAAN")."'
			, '".$this->getField("ICON_PENGHARGAAN")."'
			, '".$this->getField("STATUS_PENINJAUAN_MASA_KERJA")."'
			, '".$this->getField("ICON_PENINJAUAN_MASA_KERJA")."'
			, '".$this->getField("STATUS_SURAT_TANDA_LULUS")."'
			, '".$this->getField("ICON_SURAT_TANDA_LULUS")."'
			, '".$this->getField("STATUS_SUAMI_ISTRI")."'
			, '".$this->getField("ICON_SUAMI_ISTRI")."'
			, '".$this->getField("STATUS_ANAK")."'
			, '".$this->getField("ICON_ANAK")."'
			, '".$this->getField("STATUS_ORANG_TUA_ADD")."'
			, '".$this->getField("ICON_ORANG_TUA_ADD")."'
			, '".$this->getField("STATUS_SAUDARA")."'
			, '".$this->getField("ICON_SAUDARA")."'
			, '".$this->getField("STATUS_MERTUA_ADD")."'
			, '".$this->getField("ICON_MERTUA_ADD")."'
			, '".$this->getField("STATUS_BAHASA")."'
			, '".$this->getField("ICON_BAHASA")."'
		)
		";

		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE validasi.aksi_menu
		SET
		STATUS_DATA_UTAMA= '".$this->getField("STATUS_DATA_UTAMA")."'
		, ICON_DATA_UTAMA= '".$this->getField("ICON_DATA_UTAMA")."'
		, STATUS_SK_CPNS= '".$this->getField("STATUS_SK_CPNS")."'
		, ICON_SK_CPNS= '".$this->getField("ICON_SK_CPNS")."'
		, STATUS_SK_PNS= '".$this->getField("STATUS_SK_PNS")."'
		, ICON_SK_PNS= '".$this->getField("ICON_SK_PNS")."'
		, STATUS_SK_PPPK= '".$this->getField("STATUS_SK_PPPK")."'
		, ICON_SK_PPPK= '".$this->getField("ICON_SK_PPPK")."'
		, STATUS_PANGKAT= '".$this->getField("STATUS_PANGKAT")."'
		, ICON_PANGKAT= '".$this->getField("ICON_PANGKAT")."'
		, STATUS_GAJI= '".$this->getField("STATUS_GAJI")."'
		, ICON_GAJI= '".$this->getField("ICON_GAJI")."'
		, STATUS_JABATAN= '".$this->getField("STATUS_JABATAN")."'
		, ICON_JABATAN= '".$this->getField("ICON_JABATAN")."'
		, STATUS_TUGAS= '".$this->getField("STATUS_TUGAS")."'
		, ICON_TUGAS= '".$this->getField("ICON_TUGAS")."'
		, STATUS_PENDIDIKAN= '".$this->getField("STATUS_PENDIDIKAN")."'
		, ICON_PENDIDIKAN= '".$this->getField("ICON_PENDIDIKAN")."'
		, STATUS_DIKLAT_STRUKTURAL= '".$this->getField("STATUS_DIKLAT_STRUKTURAL")."'
		, ICON_DIKLAT_STRUKTURAL= '".$this->getField("ICON_DIKLAT_STRUKTURAL")."'
		, STATUS_DIKLAT_KURSUS= '".$this->getField("STATUS_DIKLAT_KURSUS")."'
		, ICON_DIKLAT_KURSUS= '".$this->getField("ICON_DIKLAT_KURSUS")."'
		, STATUS_CUTI= '".$this->getField("STATUS_CUTI")."'
		, ICON_CUTI= '".$this->getField("ICON_CUTI")."'
		, STATUS_SKP_PPK= '".$this->getField("STATUS_SKP_PPK")."'
		, ICON_SKP_PPK= '".$this->getField("ICON_SKP_PPK")."'
		, STATUS_PAK= '".$this->getField("STATUS_PAK")."'
		, ICON_PAK= '".$this->getField("ICON_PAK")."'
		, STATUS_KOMPETENSI= '".$this->getField("STATUS_KOMPETENSI")."'
		, ICON_KOMPETENSI= '".$this->getField("ICON_KOMPETENSI")."'
		, STATUS_PENGHARGAAN= '".$this->getField("STATUS_PENGHARGAAN")."'
		, ICON_PENGHARGAAN= '".$this->getField("ICON_PENGHARGAAN")."'
		, STATUS_PENINJAUAN_MASA_KERJA= '".$this->getField("STATUS_PENINJAUAN_MASA_KERJA")."'
		, ICON_PENINJAUAN_MASA_KERJA= '".$this->getField("ICON_PENINJAUAN_MASA_KERJA")."'
		, STATUS_SURAT_TANDA_LULUS= '".$this->getField("STATUS_SURAT_TANDA_LULUS")."'
		, ICON_SURAT_TANDA_LULUS= '".$this->getField("ICON_SURAT_TANDA_LULUS")."'
		, STATUS_SUAMI_ISTRI= '".$this->getField("STATUS_SUAMI_ISTRI")."'
		, ICON_SUAMI_ISTRI= '".$this->getField("ICON_SUAMI_ISTRI")."'
		, STATUS_ANAK= '".$this->getField("STATUS_ANAK")."'
		, ICON_ANAK= '".$this->getField("ICON_ANAK")."'
		, STATUS_ORANG_TUA_ADD= '".$this->getField("STATUS_ORANG_TUA_ADD")."'
		, ICON_ORANG_TUA_ADD= '".$this->getField("ICON_ORANG_TUA_ADD")."'
		, STATUS_SAUDARA= '".$this->getField("STATUS_SAUDARA")."'
		, ICON_SAUDARA= '".$this->getField("ICON_SAUDARA")."'
		, STATUS_MERTUA_ADD= '".$this->getField("STATUS_MERTUA_ADD")."'
		, ICON_MERTUA_ADD= '".$this->getField("ICON_MERTUA_ADD")."'
		, STATUS_BAHASA= '".$this->getField("STATUS_BAHASA")."'
		, ICON_BAHASA= '".$this->getField("ICON_BAHASA")."'
		WHERE MENU_AKSI_ID = ".$this->getField("MENU_AKSI_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectbyparams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
				SELECT
				A.*
				FROM validasi.aksi_menu A
				WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

  } 
?>