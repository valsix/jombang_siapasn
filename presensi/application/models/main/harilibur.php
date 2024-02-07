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
  include_once(APPPATH.'/models/Entity.php');

  class HariLibur extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function HariLibur()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("HARI_LIBUR_ID", $this->getNextId("HARI_LIBUR_ID","HARI_LIBUR")); 
		if($this->getField("TANGGAL_FIX") == "NULL")
		{
			// $this->setField("JUMLAH_LIBUR_BULAN_INI", "(SELECT AMBIL_SELISIH_TANPA_WEEKEND(".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", 'BULAN_INI'))::INT");
			// $this->setField("JUMLAH_LIBUR_BULAN_DEPAN", "(SELECT AMBIL_SELISIH_TANPA_WEEKEND(".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", 'BULAN_DEPAN'))::INT");
			$this->setField("JUMLAH_LIBUR_BULAN_INI", 1);
			$this->setField("JUMLAH_LIBUR_BULAN_DEPAN", 0);						
		}
		else
		{
			$this->setField("JUMLAH_LIBUR_BULAN_INI", 1);
			$this->setField("JUMLAH_LIBUR_BULAN_DEPAN", 0);						
		}
		$str = "
				INSERT INTO HARI_LIBUR (
				   HARI_LIBUR_ID, NAMA, KETERANGAN, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, TANGGAL_FIX, JUMLAH_LIBUR_BULAN_INI, JUMLAH_LIBUR_BULAN_DEPAN, STATUS_CUTI_BERSAMA
				   --, STATUS, LAST_USER, LAST_DATE, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, CABANG_ID
				   --, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				   )
				VALUES(
					  ".$this->getField("HARI_LIBUR_ID").",
					  '".$this->getField("NAMA")."',
					  '".$this->getField("KETERANGAN")."',
					  ".$this->getField("TANGGAL_AWAL").",
					  ".$this->getField("TANGGAL_AKHIR").",
					  ".$this->getField("TANGGAL_FIX").",
					  ".$this->getField("JUMLAH_LIBUR_BULAN_INI").",
					  ".$this->getField("JUMLAH_LIBUR_BULAN_DEPAN").",
					  '".$this->getField("STATUS_CUTI_BERSAMA")."'
					  --, ".$this->getField("STATUS").",
					  --".$this->getField("LAST_USER").",
					  --".$this->getField("LAST_DATE").",
					  --".$this->getField("LAST_CREATE_USER").",
					  --".$this->getField("LAST_CREATE_DATE").",
					  --".$this->getField("LAST_UPDATE_USER").",
					  --".$this->getField("LAST_UPDATE_DATE").",
					  --'".$this->getField("USER_LOGIN_ID")."',
					  --'".$this->getField("USER_LOGIN_PEGAWAI_ID")."'
				)"; 
		$this->id = $this->getField("HARI_LIBUR_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		if($this->getField("TANGGAL_FIX") == "NULL")
		{
			// $this->setField("JUMLAH_LIBUR_BULAN_INI", "(SELECT AMBIL_SELISIH_TANPA_WEEKEND(".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", 'BULAN_INI'))::INT");
			// $this->setField("JUMLAH_LIBUR_BULAN_DEPAN", "(SELECT AMBIL_SELISIH_TANPA_WEEKEND(".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", 'BULAN_DEPAN'))::INT");
			$this->setField("JUMLAH_LIBUR_BULAN_INI", 1);
			$this->setField("JUMLAH_LIBUR_BULAN_DEPAN", 0);						
		}
		else
		{
			$this->setField("JUMLAH_LIBUR_BULAN_INI", 1);
			$this->setField("JUMLAH_LIBUR_BULAN_DEPAN", 0);						
		}		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE  HARI_LIBUR
			   SET NAMA         	= '".$this->getField("NAMA")."',
				   KETERANGAN		= '".$this->getField("KETERANGAN")."',
				   TANGGAL_AWAL		= ".$this->getField("TANGGAL_AWAL").",
				   TANGGAL_AKHIR	= ".$this->getField("TANGGAL_AKHIR").",
				   TANGGAL_FIX= ".$this->getField("TANGGAL_FIX").",
				   JUMLAH_LIBUR_BULAN_INI = ".$this->getField("JUMLAH_LIBUR_BULAN_INI").",
				   JUMLAH_LIBUR_BULAN_DEPAN = ".$this->getField("JUMLAH_LIBUR_BULAN_DEPAN").",
				   STATUS_CUTI_BERSAMA = '".$this->getField("STATUS_CUTI_BERSAMA")."',
				   CABANG_ID = '".$this->getField("CABANG_ID")."',
				   --USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."',
				   --USER_LOGIN_PEGAWAI_ID = '".$this->getField("USER_LOGIN_PEGAWAI_ID")."'
			 WHERE HARI_LIBUR_ID = ".$this->getField("HARI_LIBUR_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM HARI_LIBUR
                WHERE 
                  HARI_LIBUR_ID = ".$this->getField("HARI_LIBUR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
				SELECT 
				HARI_LIBUR_ID, A.NAMA, A.KETERANGAN, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, TANGGAL_FIX, STATUS_CUTI_BERSAMA, A.CABANG_ID,
				   '' NAMA_CABANG,
				   CASE 
					WHEN COALESCE(A.CABANG_ID, 'X') = 'X' THEN 'Libur Nasional'
					ELSE 'Libur Unit ' || ''
				   END KETERANGAN_LIBUR
				FROM HARI_LIBUR A
				WHERE 1 = 1
			"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsHariLibur($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TANGGAL ASC ")
	{
		$str = "
				SELECT KODE_HARI, HARI, BULAN, TAHUN, A.NAMA, STATUS_CUTI_BERSAMA, TOTAL_LIBUR, TANGGAL, STATUS, A.CABANG_ID,
				'' NAMA_CABANG
  				FROM HARI_LIBUR_TANGGAL A
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
	
    function selectByParamsCheckHariAktif($awal, $penambahan, $cabangId)
	{
		$str = "
				SELECT (TO_DATE('".$awal."', 'DD-MM-YYYY') + INTERVAL '".$penambahan."' DAY)::DATE TANGGAL, AMBIL_HARI_AKTIF(TO_DATE('".$awal."', 'DD-MM-YYYY') + INTERVAL '".$penambahan."' DAY, TO_DATE('".$awal."', 'DD-MM-YYYY') + INTERVAL '".$penambahan."' DAY, COALESCE('".$cabangId."', 'NULL')) AKTIF 
			"; 
		
		$this->query = $str;
		return $this->select($str); 
    }
	
	function selectByParamsCheckHari($awal, $penambahan)
	{
		$str = "
				SELECT (TO_DATE('".$awal."', 'DD-MM-YYYY') + INTERVAL '".$penambahan."' DAY)::DATE TANGGAL 
			"; 
		
		$this->query = $str;
		return $this->select($str); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    
				SELECT 
				HARI_LIBUR_ID, NAMA, KETERANGAN, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, TANGGAL_FIX
				FROM HARI_LIBUR
				WHERE 1 = 1
			"; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY HARI_LIBUR_ID DESC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(HARI_LIBUR_ID) AS ROWCOUNT FROM HARI_LIBUR WHERE 1 = 1 ".$statement; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

   function getLibur($tanggal_fix, $tanggal_penuh, $cabangId)
	{
		$str = " SELECT 1 ROWCOUNT FROM HARI_LIBUR WHERE COALESCE(CABANG_ID, '#') = '#' AND (TANGGAL_FIX = '".$tanggal_fix."' OR TO_DATE('".$tanggal_penuh."', 'DDMMYYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR) 
				 UNION ALL
				 SELECT 1 ROWCOUNT FROM HARI_LIBUR WHERE CABANG_ID = '".$cabangId."' AND (TANGGAL_FIX = '".$tanggal_fix."' OR TO_DATE('".$tanggal_penuh."', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR)
				"; 
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

	function getHariLibur($tanggal_fix, $tanggal_penuh, $cabangId)
	{
		$str = " 
				SELECT 1 ROWCOUNT FROM HARI_LIBUR WHERE COALESCE(CABANG_ID, '#') = '#' AND (TANGGAL_FIX = '".$tanggal_fix."' OR TO_DATE('".$tanggal_penuh."', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR)
				UNION ALL
				SELECT 1 ROWCOUNT FROM HARI_LIBUR WHERE CABANG_ID = '".$cabangId."' AND (TANGGAL_FIX = '".$tanggal_fix."' OR TO_DATE('".$tanggal_penuh."', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR)
				"; 
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
   function getAktif($awal, $akhir, $cabangId)
	{
		$str = " SELECT AMBIL_HARI_AKTIF(TO_DATE('".$awal."', 'DD-MM-YYYY'), TO_DATE('".$akhir."', 'DD-MM-YYYY'), COALESCE('".$cabangId."', 'NULL')) ROWCOUNT  "; 
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

   function getVerifikasiPermohonan($pegawaiId, $awal, $akhir)
	{
		$str = " SELECT PESAN FROM PERMOHONAN_VERIFIKASI
					WHERE TANGGAL_AWAL IS NOT NULL AND TANGGAL_AKHIR IS NOT NULL AND
						   (
						   (TO_DATE('".$awal."', 'DD-MM-YYYY'), TO_DATE('".$akhir."', 'DD-MM-YYYY')) OVERLAPS(TANGGAL_AWAL, TANGGAL_AKHIR) OR 
						    TO_DATE('".$awal."', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR OR
						    TO_DATE('".$akhir."', 'DD-MM-YYYY') BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR
						   ) AND PEGAWAI_ID = '".$pegawaiId."' AND APPROVAL = 1
	   "; 
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("PESAN"); 
		else 
			return ""; 
    }

   function getVerifikasiPermohonanCutiHaid($pegawaiId, $awal)
	{
		$str = " SELECT 'Terdapat permohonan ' || B.NAMA || ' pada tanggal ' || TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY') || ', anda hanya mendapat 1 kesempatan di bulan yang sama.' PESAN 
					FROM PERMOHONAN_IJIN_KHUSUS A INNER JOIN IJIN_KOREKSI B ON A.IJIN_KOREKSI_ID = B.IJIN_KOREKSI_ID
					WHERE B.KODE = 'CKW' AND COALESCE(NULLIF(APPROVAL1, ''), 'X') IN ('X', 'Y') AND COALESCE(NULLIF(APPROVAL2, ''), 'X') IN ('X', 'Y') 
					AND PEGAWAI_ID = '".$pegawaiId."'
					AND TO_CHAR(TO_DATE('".$awal."', 'DD-MM-YYYY'), 'MMYYYY') = TO_CHAR(TANGGAL_AWAL, 'MMYYYY')
	   "; 
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("PESAN"); 
		else 
			return ""; 
    }
				
	function getSelisihWeekEnd($tanggal_awal, $tanggal_akhir, $mode="BULAN_INI")
	{
		$str = " SELECT AMBIL_SELISIH_TANPA_WEEKEND(".$tanggal_awal.", ".$tanggal_akhir.", '".$mode."') ROWCOUNT ";
		$this->select($str);
		$this->query = $str;
		if($this->firstRow()) 
		{
			return $this->getField("ROWCOUNT"); 
		}
		else 
			return 0; 
    }

	function getCountHariAktifShift($pegawaiId, $tanggal_awal, $tanggal_akhir)
	{
		$str = "
				SELECT COUNT(1) JUMLAH_HARI FROM JADWAL_SHIFT_PEGAWAI WHERE PEGAWAI_ID = '".$pegawaiId."' AND
				TO_DATE(LPAD(HARI::varchar, 2, '0') || PERIODE, 'DDMMYYYY') BETWEEN TO_DATE('".$tanggal_awal."', 'DDMMYYYY') AND TO_DATE('".$tanggal_akhir."', 'DDMMYYYY') AND NOT JAM_MASUK_AWAL IS NULL
				";
		$this->select($str);
		$this->query = $str;
		if($this->firstRow()) 
		{
			return $this->getField("JUMLAH_HARI"); 
		}
		else 
			return 0; 
    }
	
	function getHariAktif($tanggal_awal, $tanggal_akhir)
	{
		$str = " SELECT AMBIL_HARI_AKTIF(".$tanggal_awal.", ".$tanggal_akhir.") JUMLAH_HARI";
		$this->select($str);
		$this->query = $str;
		if($this->firstRow()) 
		{
			return $this->getField("JUMLAH_HARI"); 
		}
		else 
			return 0; 
    }
	
    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(HARI_LIBUR_ID) AS ROWCOUNT FROM HARI_LIBUR WHERE 1 = 1 "; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>