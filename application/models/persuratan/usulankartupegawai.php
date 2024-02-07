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
  include_once("functions/date.func.php");
  
  class UsulanKartuPegawai extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UsulanKartuPegawai()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USULAN_KARTU_PEGAWAI_ID", $this->getNextId("USULAN_KARTU_PEGAWAI_ID","PERSURATAN.USULAN_KARTU_PEGAWAI")); 

     	$str = "
			INSERT INTO PERSURATAN.USULAN_KARTU_PEGAWAI (
				USULAN_KARTU_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID
				, SURAT_MASUK_PEGAWAI_ID, JENIS_PERMOHONAN_ID, NO_SURAT_KEHILANGAN
				, TANGGAL_SURAT_KEHILANGAN, KETERANGAN, LAST_USER, LAST_DATE, LAST_LEVEL
				) 
			VALUES (
				 ".$this->getField("USULAN_KARTU_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_PERMOHONAN_ID").",
				 '".$this->getField("NO_SURAT_KEHILANGAN")."',
				 ".$this->getField("TANGGAL_SURAT_KEHILANGAN").",
				 '".$this->getField("KETERANGAN")."',
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL")."
			)
		"; 	
		$this->id = $this->getField("USULAN_KARTU_PEGAWAI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.USULAN_KARTU_PEGAWAI
				SET
					JENIS_ID= ".$this->getField("JENIS_ID").",
					SURAT_MASUK_BKD_ID= ".$this->getField("SURAT_MASUK_BKD_ID").",
					SURAT_MASUK_UPT_ID= ".$this->getField("SURAT_MASUK_UPT_ID").",
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					SURAT_MASUK_PEGAWAI_ID= ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
					JENIS_PERMOHONAN_ID= ".$this->getField("JENIS_PERMOHONAN_ID").",
					NO_SURAT_KEHILANGAN= '".$this->getField("NO_SURAT_KEHILANGAN")."',
					TANGGAL_SURAT_KEHILANGAN= ".$this->getField("TANGGAL_SURAT_KEHILANGAN")."
				WHERE USULAN_KARTU_PEGAWAI_ID = ".$this->getField("USULAN_KARTU_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM PERSURATAN.USULAN_KARTU_PEGAWAI
				WHERE USULAN_KARTU_PEGAWAI_ID = ".$this->getField("USULAN_KARTU_PEGAWAI_ID")."
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
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sTable= "", $sField="", $sJoin="")
	{
		$str = "SELECT 
					   ".$sField." NAMA
				FROM ".$sTable." A
				".$sJoin."
				WHERE 1=1"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USULAN_KARTU_PEGAWAI_ID ASC')
	{
		$str = "
				SELECT
					A.USULAN_KARTU_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID
					, A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_PERMOHONAN_ID, A.NO_SURAT_KEHILANGAN
					, A.TANGGAL_SURAT_KEHILANGAN, A.KETERANGAN
				FROM persuratan.USULAN_KARTU_PEGAWAI A
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
	
	function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function getCountByParamsMonitoringPegawai($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
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
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.USULAN_KARTU_PEGAWAI A
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