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
  
  class PenilaianKompetensi extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function PenilaianKompetensi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENILAIAN_KOMPETENSI_ID", $this->getNextId("PENILAIAN_KOMPETENSI_ID","penilaian_kompetensi")); 

		$str = "
		INSERT INTO penilaian_kompetensi
		(
			PENILAIAN_KOMPETENSI_ID, PEGAWAI_ID, JABATAN_RIWAYAT_ID, TANGGAL_KOMPETENSI
			, TANGGAL_MULAI, TANGGAL_SELESAI, TAHUN, ASESOR
			, INTEGRITAS_NILAI, KERJASAMA_NILAI, KOMUNIKASI_NILAI, ORIENTASI_NILAI, PELAYANAN_PUBLIK_NILAI
			, PENGEMBANGAN_DIRI_NILAI, KELOLA_PERUBAHAN_NILAI, AMBIL_KEPUTUSAN_NILAI
			, PEREKAT_BANGSA_NILAI, SKOR_KOMPETENSI, NILAI_INDEKS_KOMPETENSI
			, KESIMPULAN, PENYELENGGARA, DESKRIPSI
            , USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_USER, LAST_CREATE_DATE
		) 
		VALUES 
		(
			".$this->getField("PENILAIAN_KOMPETENSI_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("JABATAN_RIWAYAT_ID")."
			, ".$this->getField("TANGGAL_KOMPETENSI")."
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, ".$this->getField("TAHUN")."
			, '".$this->getField("ASESOR")."'
			, ".$this->getField("INTEGRITAS_NILAI")."
			, ".$this->getField("KERJASAMA_NILAI")."
			, ".$this->getField("KOMUNIKASI_NILAI")."
			, ".$this->getField("ORIENTASI_NILAI")."
			, ".$this->getField("PELAYANAN_PUBLIK_NILAI")."
			, ".$this->getField("PENGEMBANGAN_DIRI_NILAI")."
			, ".$this->getField("KELOLA_PERUBAHAN_NILAI")."
			, ".$this->getField("AMBIL_KEPUTUSAN_NILAI")."
			, ".$this->getField("PEREKAT_BANGSA_NILAI")."
			, ".$this->getField("SKOR_KOMPETENSI")."
			, '".$this->getField("NILAI_INDEKS_KOMPETENSI")."'
			, '".$this->getField("KESIMPULAN")."'
			, '".$this->getField("PENYELENGGARA")."'
			, '".$this->getField("DESKRIPSI")."'
			, ".$this->getField("USER_LOGIN_ID")."
			, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			, ".$this->getField("LAST_CREATE_USER")."
			, NOW()
		)
		"; 	
		// echo "xxx-".$str;exit;

		$this->id = $this->getField("PENILAIAN_KOMPETENSI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE penilaian_kompetensi
		SET
			JABATAN_RIWAYAT_ID= ".$this->getField("JABATAN_RIWAYAT_ID")."
			, TANGGAL_KOMPETENSI= ".$this->getField("TANGGAL_KOMPETENSI")."
			, TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
			, TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI")."
			, TAHUN= ".$this->getField("TAHUN")."
			, ASESOR= '".$this->getField("ASESOR")."'
			, INTEGRITAS_NILAI= ".$this->getField("INTEGRITAS_NILAI")."
			, KERJASAMA_NILAI= ".$this->getField("KERJASAMA_NILAI")."
			, KOMUNIKASI_NILAI= ".$this->getField("KOMUNIKASI_NILAI")."
			, ORIENTASI_NILAI= ".$this->getField("ORIENTASI_NILAI")."
			, PELAYANAN_PUBLIK_NILAI= ".$this->getField("PELAYANAN_PUBLIK_NILAI")."
			, PENGEMBANGAN_DIRI_NILAI= ".$this->getField("PENGEMBANGAN_DIRI_NILAI")."
			, KELOLA_PERUBAHAN_NILAI= ".$this->getField("KELOLA_PERUBAHAN_NILAI")."
			, AMBIL_KEPUTUSAN_NILAI= ".$this->getField("AMBIL_KEPUTUSAN_NILAI")."
			, PEREKAT_BANGSA_NILAI= ".$this->getField("PEREKAT_BANGSA_NILAI")."
			, SKOR_KOMPETENSI= ".$this->getField("SKOR_KOMPETENSI")."
			, NILAI_INDEKS_KOMPETENSI= '".$this->getField("NILAI_INDEKS_KOMPETENSI")."'
			, KESIMPULAN= '".$this->getField("KESIMPULAN")."'
			, PENYELENGGARA= '".$this->getField("PENYELENGGARA")."'
			, DESKRIPSI= '".$this->getField("DESKRIPSI")."'
		 	, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
		 	, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		 	, LAST_USER= '".$this->getField("LAST_USER")."'
		 	, LAST_DATE= NOW()
		WHERE  PENILAIAN_KOMPETENSI_ID = ".$this->getField("PENILAIAN_KOMPETENSI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		$str = "		
		UPDATE penilaian_kompetensi
		SET    
			STATUS= ".$this->getField("STATUS")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE  PENILAIAN_KOMPETENSI_ID    	= ".$this->getField("PENILAIAN_KOMPETENSI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        UPDATE penilaian_kompetensi SET
	        STATUS = '1',
	        LAST_USER= '".$this->getField("LAST_USER")."',
	        LAST_DATE= ".$this->getField("LAST_DATE").",
	        USER_LOGIN_ID  = ".$this->getField("USER_LOGIN_ID").",
	        USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
        WHERE PENILAIAN_KOMPETENSI_ID = ".$this->getField("PENILAIAN_KOMPETENSI_ID")."
        ";
		$this->query = $str;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANGGAL_KOMPETENSI DESC')
	{
		$str = "
		SELECT
			A.*
		FROM penilaian_kompetensi A
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
		
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PENILAIAN_KOMPETENSI_ID) AS ROWCOUNT 
				FROM penilaian_kompetensi A
				WHERE 1 = 1 ".$statement; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }

  } 
?>