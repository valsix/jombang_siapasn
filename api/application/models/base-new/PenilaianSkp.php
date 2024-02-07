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
  
  class PenilaianSkp extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PenilaianSkp()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.PENILAIAN_SKP"));
     	$str = "
			INSERT INTO validasi.PENILAIAN_SKP (
				PENILAIAN_SKP_ID, PEGAWAI_ID, TAHUN, PEGAWAI_PEJABAT_PENILAI_ID, PEGAWAI_ATASAN_PEJABAT_ID, SKP_NILAI, SKP_HASIL, 
				ORIENTASI_NILAI, INTEGRITAS_NILAI, KOMITMEN_NILAI, DISIPLIN_NILAI, KERJASAMA_NILAI, KEPEMIMPINAN_NILAI, PERILAKU_NILAI, 
				PERILAKU_HASIL, PRESTASI_HASIL, JUMLAH_NILAI, RATA_NILAI, KEBERATAN, KEBERATAN_TANGGAL, TANGGAPAN, TANGGAPAN_TANGGAL, 
				KEPUTUSAN, KEPUTUSAN_TANGGAL, REKOMENDASI, PEGAWAI_PEJABAT_PENILAI_NIP, PEGAWAI_PEJABAT_PENILAI_NAMA, PEGAWAI_ATASAN_PEJABAT_NIP, 
				PEGAWAI_ATASAN_PEJABAT_NAMA, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID,
				JENIS_JABATAN_DINILAI,PEGAWAI_UNOR_ID,PEGAWAI_UNOR_NAMA,PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA,PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA,PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA
				,PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA,PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID,NILAI_HASIL_KERJA,NILAI_HASIL_PERILAKU,PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID
			) 
			VALUES (
				  ".$this->getField("PENILAIAN_SKP_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("PEGAWAI_PEJABAT_PENILAI_ID").",
				  ".$this->getField("PEGAWAI_ATASAN_PEJABAT_ID").",
				  ".$this->getField("SKP_NILAI").",
				  ".$this->getField("SKP_HASIL").",
				  ".$this->getField("ORIENTASI_NILAI").",
				  ".$this->getField("INTEGRITAS_NILAI").",
				  ".$this->getField("KOMITMEN_NILAI").",
				  ".$this->getField("DISIPLIN_NILAI").",
				  ".$this->getField("KERJASAMA_NILAI").",
				  ".$this->getField("KEPEMIMPINAN_NILAI").",
				  ".$this->getField("PERILAKU_NILAI").",
				  ".$this->getField("PERILAKU_HASIL").",
				  ".$this->getField("PRESTASI_HASIL").",
				  ".$this->getField("JUMLAH_NILAI").",
				  ".$this->getField("RATA_NILAI").",
				  '".$this->getField("KEBERATAN")."',
				  ".$this->getField("KEBERATAN_TANGGAL").",
				  '".$this->getField("TANGGAPAN")."',
				  ".$this->getField("TANGGAPAN_TANGGAL").",
				  '".$this->getField("KEPUTUSAN")."',
				  ".$this->getField("KEPUTUSAN_TANGGAL").",
				  '".$this->getField("REKOMENDASI")."',
				  '".$this->getField("PEGAWAI_PEJABAT_PENILAI_NIP")."',
				  '".$this->getField("PEGAWAI_PEJABAT_PENILAI_NAMA")."',
				  '".$this->getField("PEGAWAI_ATASAN_PEJABAT_NIP")."',
				  '".$this->getField("PEGAWAI_ATASAN_PEJABAT_NAMA")."',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  ".$this->getField("TEMP_VALIDASI_ID").",
				  ".$this->getField("JENIS_JABATAN_DINILAI").",
				  ".$this->getField("PEGAWAI_UNOR_ID").",
				  ".$this->getField("PEGAWAI_UNOR_NAMA").",
				  ".$this->getField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA").",
				  ".$this->getField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA").",
				  ".$this->getField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA").",
				  ".$this->getField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA").",
				  ".$this->getField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID").",
				  ".$this->getField("NILAI_HASIL_KERJA").",
				   ".$this->getField("NILAI_HASIL_PERILAKU").",
				    ".$this->getField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID")."

			)
		"; 	
		$this->id = $this->getField("PENILAIAN_SKP_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE validasi.PENILAIAN_SKP
				SET  
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	TAHUN= ".$this->getField("TAHUN").",
				  	PEGAWAI_PEJABAT_PENILAI_ID= ".$this->getField("PEGAWAI_PEJABAT_PENILAI_ID").",
				  	PEGAWAI_ATASAN_PEJABAT_ID= ".$this->getField("PEGAWAI_ATASAN_PEJABAT_ID").",
				  	SKP_NILAI= ".$this->getField("SKP_NILAI").",
				  	SKP_HASIL= ".$this->getField("SKP_HASIL").",
				  	ORIENTASI_NILAI= ".$this->getField("ORIENTASI_NILAI").",
				  	INTEGRITAS_NILAI= ".$this->getField("INTEGRITAS_NILAI").",
				  	KOMITMEN_NILAI= ".$this->getField("KOMITMEN_NILAI").",
				  	DISIPLIN_NILAI= ".$this->getField("DISIPLIN_NILAI").",
				  	KERJASAMA_NILAI= ".$this->getField("KERJASAMA_NILAI").",
				  	KEPEMIMPINAN_NILAI= ".$this->getField("KEPEMIMPINAN_NILAI").",
				  	PERILAKU_NILAI= ".$this->getField("PERILAKU_NILAI").",
				  	PERILAKU_HASIL= ".$this->getField("PERILAKU_HASIL").",
				  	PRESTASI_HASIL= ".$this->getField("PRESTASI_HASIL").",
				  	JUMLAH_NILAI= ".$this->getField("JUMLAH_NILAI").",
				  	RATA_NILAI= ".$this->getField("RATA_NILAI").",
				  	KEBERATAN= '".$this->getField("KEBERATAN")."',
				  	KEBERATAN_TANGGAL= ".$this->getField("KEBERATAN_TANGGAL").",
				  	TANGGAPAN= '".$this->getField("TANGGAPAN")."',
				  	TANGGAPAN_TANGGAL= ".$this->getField("TANGGAPAN_TANGGAL").",
				  	KEPUTUSAN= '".$this->getField("KEPUTUSAN")."',
				  	KEPUTUSAN_TANGGAL= ".$this->getField("KEPUTUSAN_TANGGAL").",
				  	REKOMENDASI= '".$this->getField("REKOMENDASI")."',
				  	PEGAWAI_PEJABAT_PENILAI_NIP= '".$this->getField("PEGAWAI_PEJABAT_PENILAI_NIP")."',
				  	PEGAWAI_PEJABAT_PENILAI_NAMA= '".$this->getField("PEGAWAI_PEJABAT_PENILAI_NAMA")."',
				  	PEGAWAI_ATASAN_PEJABAT_NIP= '".$this->getField("PEGAWAI_ATASAN_PEJABAT_NIP")."',
				  	PEGAWAI_ATASAN_PEJABAT_NAMA= '".$this->getField("PEGAWAI_ATASAN_PEJABAT_NAMA")."',
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				  	JENIS_JABATAN_DINILAI= ".$this->getField("JENIS_JABATAN_DINILAI").",
				  	PEGAWAI_UNOR_ID= ".$this->getField("PEGAWAI_UNOR_ID").",
				  	PEGAWAI_UNOR_NAMA= ".$this->getField("PEGAWAI_UNOR_NAMA").",
				  	PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA= ".$this->getField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA").",
				  	PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA= ".$this->getField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA").",
				  	PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA= ".$this->getField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA").",
				  	PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA= ".$this->getField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA").",
				  	PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID= ".$this->getField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID").",
				  	NILAI_HASIL_KERJA= ".$this->getField("NILAI_HASIL_KERJA").",
				  	NILAI_HASIL_PERILAKU= ".$this->getField("NILAI_HASIL_PERILAKU").",
				  	PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID= ".$this->getField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID")."

				WHERE  PENILAIAN_SKP_ID = ".$this->getField("PENILAIAN_SKP_ID")."
				"; 
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

     function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TAHUN DESC')
	{
		$str = "
		SELECT 	
		A.PENILAIAN_SKP_ID, A.PEGAWAI_ID, A.TAHUN, A.PEGAWAI_PEJABAT_PENILAI_ID, A.PEGAWAI_ATASAN_PEJABAT_ID, A.SKP_NILAI, A.SKP_HASIL, 
		A.ORIENTASI_NILAI, A.INTEGRITAS_NILAI, A.KOMITMEN_NILAI, A.DISIPLIN_NILAI, A.KERJASAMA_NILAI, A.KEPEMIMPINAN_NILAI, A.PERILAKU_NILAI, 
		A.PERILAKU_HASIL, A.PRESTASI_HASIL, A.JUMLAH_NILAI, A.RATA_NILAI, A.KEBERATAN, A.KEBERATAN_TANGGAL, A.TANGGAPAN, A.TANGGAPAN_TANGGAL, 
		A.KEPUTUSAN, A.KEPUTUSAN_TANGGAL, A.REKOMENDASI, A.PEGAWAI_PEJABAT_PENILAI_NIP, A.PEGAWAI_PEJABAT_PENILAI_NAMA, A.PEGAWAI_ATASAN_PEJABAT_NIP, 
		A.PEGAWAI_ATASAN_PEJABAT_NAMA, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI,JENIS_JABATAN_DINILAI,PEGAWAI_UNOR_ID,PEGAWAI_UNOR_NAMA,PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA,PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA,PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA,PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA,PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID,NILAI_HASIL_KERJA,NILAI_HASIL_PERILAKU,PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID
		FROM (select * from validasi.validasi_pegawai_penilaian_skp('".$pegawaiid."', '".$id."', '".$rowid."')) A
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
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