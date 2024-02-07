<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class PenilaianSkpDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PenilaianSkpDetil()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.PENILAIAN_SKP_DETIL"));
     	$str = "
					INSERT INTO validasi.PENILAIAN_SKP_DETIL (PENILAIAN_SKP_DETIL_ID, TAHUN, TRIWULAN, PEGAWAI_ID, PEGAWAI_UNOR_ID,        PEGAWAI_UNOR_NAMA, JENIS_JABATAN_DINILAI, PEGAWAI_PEJABAT_PENILAI_ID,        PEGAWAI_PEJABAT_PENILAI_NIP, PEGAWAI_PEJABAT_PENILAI_NAMA, PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA,        PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA, PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID,        PEGAWAI_ATASAN_PEJABAT_ID, PEGAWAI_ATASAN_PEJABAT_NIP, PEGAWAI_ATASAN_PEJABAT_NAMA,        PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA, PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA,        PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID, NILAI_HASIL_KERJA, NILAI_HASIL_PERILAKU,        NILAI_QUADRAN, NILAI_CAPAIAN_ORGANISASI, LAST_USER, LAST_DATE,        LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TANGGAL_VALIDASI,        TEMP_VALIDASI_ID)
					VALUES (
					".$this->getField("PENILAIAN_SKP_DETIL_ID").",
					".$this->getField("TAHUN").",
					".$this->getField("TRIWULAN").",
					".$this->getField("PEGAWAI_ID").",
					".$this->getField("PEGAWAI_UNOR_ID").",
					".$this->getField("PEGAWAI_UNOR_NAMA").",
					".$this->getField("JENIS_JABATAN_DINILAI").",
					".$this->getField("PEGAWAI_PEJABAT_PENILAI_ID").",
					".$this->getField("PEGAWAI_PEJABAT_PENILAI_NIP").",
					".$this->getField("PEGAWAI_PEJABAT_PENILAI_NAMA").",
					".$this->getField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA").",
					".$this->getField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA").",
					".$this->getField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID").",
					".$this->getField("PEGAWAI_ATASAN_PEJABAT_ID").",
					".$this->getField("PEGAWAI_ATASAN_PEJABAT_NIP").",
					".$this->getField("PEGAWAI_ATASAN_PEJABAT_NAMA").",
					".$this->getField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA").",
					".$this->getField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA").",
					".$this->getField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID").",
					".$this->getField("NILAI_HASIL_KERJA").",
					".$this->getField("NILAI_HASIL_PERILAKU").",
					".$this->getField("NILAI_QUADRAN").",
					".$this->getField("NILAI_CAPAIAN_ORGANISASI").",
					".$this->getField("LAST_USER").",
					".$this->getField("LAST_DATE").",
					".$this->getField("LAST_LEVEL").",
					".$this->getField("USER_LOGIN_ID").",
					".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					
								
					CURRENT_DATE,
					".$this->getField("TEMP_VALIDASI_ID")." 
			)
		"; 	
		$this->id = $this->getField("TEMP_VALIDASI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE validasi.PENILAIAN_SKP_DETIL
		SET    
		PENILAIAN_SKP_DETIL_ID =".$this->getField("PENILAIAN_SKP_DETIL_ID").",
		TAHUN =".$this->getField("TAHUN").",
		TRIWULAN =".$this->getField("TRIWULAN").",
		PEGAWAI_ID =".$this->getField("PEGAWAI_ID").",
		PEGAWAI_UNOR_ID =".$this->getField("PEGAWAI_UNOR_ID").",
		PEGAWAI_UNOR_NAMA =".$this->getField("PEGAWAI_UNOR_NAMA").",
		JENIS_JABATAN_DINILAI =".$this->getField("JENIS_JABATAN_DINILAI").",
		PEGAWAI_PEJABAT_PENILAI_ID =".$this->getField("PEGAWAI_PEJABAT_PENILAI_ID").",
		PEGAWAI_PEJABAT_PENILAI_NIP =".$this->getField("PEGAWAI_PEJABAT_PENILAI_NIP").",
		PEGAWAI_PEJABAT_PENILAI_NAMA =".$this->getField("PEGAWAI_PEJABAT_PENILAI_NAMA").",
		PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA =".$this->getField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA").",
		PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA =".$this->getField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA").",
		PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID =".$this->getField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID").",
		PEGAWAI_ATASAN_PEJABAT_ID =".$this->getField("PEGAWAI_ATASAN_PEJABAT_ID").",
		PEGAWAI_ATASAN_PEJABAT_NIP =".$this->getField("PEGAWAI_ATASAN_PEJABAT_NIP").",
		PEGAWAI_ATASAN_PEJABAT_NAMA =".$this->getField("PEGAWAI_ATASAN_PEJABAT_NAMA").",
		PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA =".$this->getField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA").",
		PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA =".$this->getField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA").",
		PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID =".$this->getField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID").",
		NILAI_HASIL_KERJA =".$this->getField("NILAI_HASIL_KERJA").",
		NILAI_HASIL_PERILAKU =".$this->getField("NILAI_HASIL_PERILAKU").",
		NILAI_QUADRAN =".$this->getField("NILAI_QUADRAN").",
		NILAI_CAPAIAN_ORGANISASI =".$this->getField("NILAI_CAPAIAN_ORGANISASI").",
		LAST_USER =".$this->getField("LAST_USER").",
		LAST_DATE =".$this->getField("LAST_DATE").",
		LAST_LEVEL =".$this->getField("LAST_LEVEL").",
		USER_LOGIN_ID =".$this->getField("USER_LOGIN_ID").",
		USER_LOGIN_PEGAWAI_ID =".$this->getField("USER_LOGIN_PEGAWAI_ID").",		
		TEMP_VALIDASI_ID =".$this->getField("TEMP_VALIDASI_ID")." 
		WHERE TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."";
			
		$this->query = $str;
		// echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.TAHUN DESC')
	{
		$str = "
		SELECT 	
		A.PENILAIAN_SKP_ID, A.STATUS, A.PEGAWAI_ID, A.TAHUN, A.PEGAWAI_PEJABAT_PENILAI_ID, A.PEGAWAI_ATASAN_PEJABAT_ID, A.SKP_NILAI, A.SKP_HASIL, 
		A.ORIENTASI_NILAI, A.INTEGRITAS_NILAI, A.KOMITMEN_NILAI, A.DISIPLIN_NILAI, A.KERJASAMA_NILAI, A.KEPEMIMPINAN_NILAI, A.PERILAKU_NILAI, 
		A.PERILAKU_HASIL, A.PRESTASI_HASIL, A.JUMLAH_NILAI, A.RATA_NILAI, A.KEBERATAN, A.KEBERATAN_TANGGAL, A.TANGGAPAN, A.TANGGAPAN_TANGGAL, 
		A.KEPUTUSAN, A.KEPUTUSAN_TANGGAL, A.REKOMENDASI, A.PEGAWAI_PEJABAT_PENILAI_NIP, A.PEGAWAI_PEJABAT_PENILAI_NAMA, A.PEGAWAI_ATASAN_PEJABAT_NIP, 
		A.PEGAWAI_ATASAN_PEJABAT_NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM PENILAIAN_SKP A 
		WHERE 1=1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

     function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.PENILAIAN_SKP_DETIL_ID DESC')
	{
		$str = "
		SELECT A.PENILAIAN_SKP_DETIL_ID,A.TAHUN,A.TRIWULAN,A.PEGAWAI_ID,A.PEGAWAI_UNOR_ID,A.PEGAWAI_UNOR_NAMA,A.JENIS_JABATAN_DINILAI,A.PEGAWAI_PEJABAT_PENILAI_ID,A.PEGAWAI_PEJABAT_PENILAI_NIP,A.PEGAWAI_PEJABAT_PENILAI_NAMA,A.PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA,A.PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA,A.PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID,A.PEGAWAI_ATASAN_PEJABAT_ID,A.PEGAWAI_ATASAN_PEJABAT_NIP,A.PEGAWAI_ATASAN_PEJABAT_NAMA,A.PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA,A.PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA,A.PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID,A.NILAI_HASIL_KERJA,A.NILAI_HASIL_PERILAKU,A.NILAI_QUADRAN,A.NILAI_CAPAIAN_ORGANISASI,A.STATUS,A.VALIDASI,A.VALIDATOR,A.PERUBAHAN_DATA,A.PERUBAHAN_VERIFIKATOR_DATA,A.TIPE_PERUBAHAN_DATA,A.TANGGAL_VALIDASI,A.TEMP_VALIDASI_ID,
			CASE 
		  	WHEN A.TRIWULAN='1' THEN 'I'
		  	WHEN A.TRIWULAN='2' THEN 'II'
		  	WHEN A.TRIWULAN='3' THEN 'III'
		  	WHEN A.TRIWULAN='4' THEN 'IV'
		  	WHEN A.TRIWULAN='99' THEN 'Final'
		  	ELSE ''
		  	END  NAMA_TRIWULAN
		FROM (select * from validasi.validasi_pegawai_penilaian_skp_detil('".$pegawaiid."', '".$id."', '".$rowid."')) A
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// ECHO $str;exit();
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PENILAIAN_SKP_ID) AS ROWCOUNT 
				FROM PENILAIAN_SKP A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>