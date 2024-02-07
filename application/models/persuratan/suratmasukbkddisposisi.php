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
  
  class SuratMasukBkdDisposisi extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratMasukBkdDisposisi()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_BKD_DISPOSISI_ID", $this->getNextId("SURAT_MASUK_BKD_DISPOSISI_ID","PERSURATAN.SURAT_MASUK_BKD_DISPOSISI")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_BKD_DISPOSISI (
				SURAT_MASUK_BKD_DISPOSISI_ID, JENIS_ID, NOMOR, NO_AGENDA, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID				) 

			VALUES (
				 ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID").",
				 ".$this->getField("JENIS_ID").",
				 '".$this->getField("NOMOR")."',
				 '".$this->getField("NO_AGENDA")."',
				 ".$this->getField("TANGGAL").",
				 '".$this->getField("KEPADA")."',
				 '".$this->getField("PERIHAL")."',
				 ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 ".$this->getField("SATUAN_KERJA_ASAL_ID")."
				
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateAgendaBaca()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					TERBACA= ".$this->getField("TERBACA").",
				 	TANGGAL= ".$this->getField("TANGGAL").",
					SURAT_AWAL= ".$this->getField("SURAT_AWAL")."
				WHERE  SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		$this->execQuery($str);
		
		$str1= "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD
				SET
					NO_AGENDA= '".$this->getField("NO_AGENDA")."'
				WHERE  SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		return $this->execQuery($str1);
    }
	
	function updateDisposisi()
	{
		$str1= "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					SATUAN_KERJA_ASAL_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID")."
				WHERE  SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
				"; 
		$this->query = $str1;
		//echo $str;exit;
		$this->execQuery($str1);
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					TANGGAL_DISPOSISI= ".$this->getField("TANGGAL_DISPOSISI").",
				 	SATUAN_KERJA_TUJUAN_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
					TERDISPOSISI = 1,
					ISI= '".$this->getField("ISI")."'
				WHERE  SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusPosisiSurat()
	{
		$str1= "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					STATUS_POSISI_SURAT= NULL
				WHERE SURAT_MASUK_BKD_ID= ".$this->getField("SURAT_MASUK_BKD_ID")."
				"; 
		$this->query = $str1;
		//echo $str;exit;
		$this->execQuery($str1);
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					STATUS_POSISI_SURAT= 1,
					POSISI_TEKNIS= ".$this->getField("POSISI_TEKNIS")."
				WHERE  SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					TERBACA= ".$this->getField("TERBACA").",
				 	TANGGAL= ".$this->getField("TANGGAL").",
				 	SATUAN_KERJA_TUJUAN_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
					ISI= '".$this->getField("ISI")."'
				WHERE  SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		$this->execQuery($str);
		
		$str1= "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD
				SET
					NO_AGENDA= '".$this->getField("NO_AGENDA")."'
				WHERE  SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		return $this->execQuery($str1);
    }
	
	function updateKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI
				SET
					TERDISPOSISI= ".$this->getField("TERDISPOSISI")."
				WHERE SURAT_MASUK_BKD_DISPOSISI_ID= ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PERSURATAN.URAT_MASUK SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
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
	
	function selectByParamsHistoriDisposisi($statement="", $statementnode="", $order='')
	{
		$str = "	
		WITH RECURSIVE nodes
		(
			SURAT_MASUK_BKD_DISPOSISI_ID, SURAT_MASUK_BKD_DISPOSISI_PARENT_ID, 
			SURAT_MASUK_BKD_ID, JENIS_ID, SATUAN_KERJA_ASAL_ID, SATUAN_KERJA_TUJUAN_ID, 
			TANGGAL, TANGGAL_DISPOSISI, TERBACA, TERBALAS, TERDISPOSISI, 
			TERPARAF, ISI
		) AS (
			SELECT 
				SURAT_MASUK_BKD_DISPOSISI_ID, SURAT_MASUK_BKD_DISPOSISI_PARENT_ID, 
				SURAT_MASUK_BKD_ID, JENIS_ID, SATUAN_KERJA_ASAL_ID, SATUAN_KERJA_TUJUAN_ID, 
				TANGGAL, TANGGAL_DISPOSISI, TERBACA, TERBALAS, TERDISPOSISI, 
				TERPARAF, ISI
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI A
			WHERE SURAT_AWAL = 1 AND TERDISPOSISI IS NOT NULL
			".$statement."
			UNION
			SELECT 
				A.SURAT_MASUK_BKD_DISPOSISI_ID, A.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID, 
				A.SURAT_MASUK_BKD_ID, A.JENIS_ID, A.SATUAN_KERJA_ASAL_ID, A.SATUAN_KERJA_TUJUAN_ID, 
				A.TANGGAL, A.TANGGAL_DISPOSISI, A.TERBACA, A.TERBALAS, A.TERDISPOSISI, 
				A.TERPARAF, A.ISI
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI A, nodes B
			WHERE A.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = B.SURAT_MASUK_BKD_DISPOSISI_ID
			AND A.TERDISPOSISI IS NOT NULL
			".$statement."
		)
		SELECT
		SURAT_MASUK_BKD_DISPOSISI_ID, SURAT_MASUK_BKD_DISPOSISI_PARENT_ID, 
		SURAT_MASUK_BKD_ID, JENIS_ID, SATUAN_KERJA_ASAL_ID, SATUAN_KERJA_TUJUAN_ID, 
		TANGGAL, TANGGAL_DISPOSISI, TERBACA, TERBALAS, TERDISPOSISI, 
		TERPARAF, ISI
		, AMBIL_SATKER_JABATAN(SATUAN_KERJA_ASAL_ID) JABATAN_ASAL
		, AMBIL_SATKER_JABATAN(SATUAN_KERJA_TUJUAN_ID) JABATAN_TUJUAN
		FROM nodes
		WHERE 1=1
		".$statementnode;
		
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsNoAgenda($statement='')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "	
		SELECT
		GENERATEZERO(CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT),3) NO_AGENDA_BARUbak
		, CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT) NO_AGENDA_BARU
		, TO_CHAR(TANGGAL, 'YYYY') TAHUN_AGENDA
		FROM persuratan.SURAT_MASUK_BKD A WHERE 1=1
		".$statement."
		GROUP BY TO_CHAR(TANGGAL, 'YYYY')
		"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

	function selectByParamsDataSuratBak($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $statementdisposisi="", $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_ID ASC')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "	
		SELECT 
			A.SURAT_MASUK_BKD_ID, A.JENIS_ID
			, CASE WHEN A.SATUAN_KERJA_ASAL_ID IS NULL THEN A.SATUAN_KERJA_ASAL_NAMA ELSE AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_ASAL_NAMA
			, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_ASAL_NAMABAK, A.TANGGAL, A.NOMOR, A.PERIHAL
			, A.NO_AGENDA VAL_NO_AGENDA
			, CASE WHEN COALESCE(NULLIF(A.NO_AGENDA,'') , NULL ) IS NULL THEN NB.NO_AGENDA_BARU ELSE A.NO_AGENDA END NO_AGENDA
			, COALESCE(DS.TANGGAL, NOW()) TANGGAL_TERIMA, DS.TANGGAL_DISPOSISI
			, CASE WHEN DS.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 THEN DS.SATUAN_KERJA_TUJUAN_ID ELSE DS.SATUAN_KERJA_ASAL_ID END BATAS_SATUAN_KERJA_CARI_ID
			, AMBIL_SATKER_NAMA(DS.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_DITERUSKAN_NAMA
			, AMBIL_SATKER_JABATAN(DS.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA
			, DS.SATUAN_KERJA_TUJUAN_ID SATUAN_KERJA_DITERUSKAN_ID, DS.SURAT_MASUK_BKD_DISPOSISI_ID, DS.TERBACA, DS.TERDISPOSISI, DS.ISI
			, DS.SURAT_AWAL, DS.POSISI_TEKNIS
			, persuratan.AMBIL_SATKER_POSISI_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID) POSISI_SURAT
			, persuratan.AMBIL_SATKER_POSISI_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID) POSISI_ID_SURAT
			, persuratan.AMBIL_SATKER_POSISI_LOGIN_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID, ".$satuankerjaid.") POSISI_ID_LOGIN_SURAT
			, S.STATUS_KELOMPOK_PEGAWAI_USUL
			, DSB.TERBACA TERBACA_DISPOSISI
			, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
			, AMBIL_SATKER_JABATAN(SATUAN_KERJA_BKDPP_ID) KEPALA_SATUAN_KERJA_BKDPP_NAMA
		FROM persuratan.SURAT_MASUK_BKD A
		INNER JOIN
		(
			SELECT SURAT_MASUK_BKD_ID
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
			WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
			GROUP BY SURAT_MASUK_BKD_ID
		) B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
		LEFT JOIN persuratan.SURAT_MASUK_BKD_DISPOSISI DS ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = (persuratan.AMBIL_SATKER_POSISI_LOGIN_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID, ".$satuankerjaid."))
		LEFT JOIN persuratan.SURAT_MASUK_BKD_DISPOSISI DSB ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = DSB.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID
		LEFT JOIN SATUAN_KERJA S ON DS.SATUAN_KERJA_ASAL_ID = S.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT
			GENERATEZERO(CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT),3) NO_AGENDA_BARUbak
			, CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT) NO_AGENDA_BARU
			, TO_CHAR(TANGGAL, 'YYYY') TAHUN_AGENDA
			FROM persuratan.SURAT_MASUK_BKD A WHERE 1=1
			GROUP BY TO_CHAR(TANGGAL, 'YYYY')
		) NB ON NB.TAHUN_AGENDA = TO_CHAR(A.TANGGAL, 'YYYY')
		,
		(
			SELECT SATUAN_KERJA_ID SATUAN_KERJA_BKDPP_ID
			FROM SATUAN_KERJA
			WHERE STATUS_SATUAN_KERJA_BKPP = 1 LIMIT 1
		) XX
		WHERE 1=1
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

    function selectByParamsDataSuratLookup($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $reqid="", $statementdisposisi="", $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_ID ASC')
	{
		$str = "	
		SELECT 
			A.SURAT_MASUK_BKD_ID, A.JENIS_ID
			, CASE WHEN A.SATUAN_KERJA_ASAL_ID IS NULL THEN A.SATUAN_KERJA_ASAL_NAMA ELSE AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_ASAL_NAMA
			, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_ASAL_NAMABAK, A.TANGGAL, A.NOMOR, A.PERIHAL
			, A.NO_AGENDA VAL_NO_AGENDA
			, CASE WHEN COALESCE(NULLIF(A.NO_AGENDA,'') , NULL ) IS NULL THEN NB.NO_AGENDA_BARU ELSE A.NO_AGENDA END NO_AGENDA
			, COALESCE(DS.TANGGAL, NOW()) TANGGAL_TERIMA, DS.TANGGAL_DISPOSISI
			, CASE WHEN DS.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 THEN DS.SATUAN_KERJA_TUJUAN_ID ELSE DS.SATUAN_KERJA_ASAL_ID END BATAS_SATUAN_KERJA_CARI_ID
			, AMBIL_SATKER_NAMA(DS.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_DITERUSKAN_NAMA
			, AMBIL_SATKER_JABATAN(DS.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA
			, DS.SATUAN_KERJA_TUJUAN_ID SATUAN_KERJA_DITERUSKAN_ID, DS.SURAT_MASUK_BKD_DISPOSISI_ID, DS.TERBACA, DS.TERDISPOSISI, DS.ISI
			, DS.SURAT_AWAL, DS.POSISI_TEKNIS
			, ID_POSISI_SURAT POSISI_SURAT
			, POSISI_ID_SURAT
			, persuratan.AMBIL_SATKER_POSISI_LOGIN_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID, ".$satuankerjaid.") POSISI_ID_LOGIN_SURAT
			, S.STATUS_KELOMPOK_PEGAWAI_USUL
			, DSB.TERBACA TERBACA_DISPOSISI
			, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
			, AMBIL_SATKER_JABATAN(SATUAN_KERJA_BKDPP_ID) KEPALA_SATUAN_KERJA_BKDPP_NAMA
		FROM persuratan.SURAT_MASUK_BKD A
		INNER JOIN
		(
			SELECT SURAT_MASUK_BKD_ID
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
			WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
			GROUP BY SURAT_MASUK_BKD_ID
		) B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
		LEFT JOIN ( 
			SELECT *
			FROM
			(
			SELECT SURAT_MASUK_BKD_DISPOSISI_ID POSISI_ID_SURAT, SURAT_MASUK_BKD_ID, ID_POSISI_SURAT, ID_POSISI_SURAT_BACA
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI_POSISI A
			) A
			INNER JOIN 
			(
			SELECT SURAT_MASUK_BKD_ID SURAT_MASUK_BKD_ID_ALIAS, SURAT_MASUK_BKD_DISPOSISI_ID, SATUAN_KERJA_ASAL_ID, POSISI_TEKNIS FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
			) B ON POSISI_ID_SURAT = SURAT_MASUK_BKD_DISPOSISI_ID
		) PD ON PD.SURAT_MASUK_BKD_ID = A.SURAT_MASUK_BKD_ID
		LEFT JOIN
		(
		SELECT * FROM persuratan.SURAT_MASUK_BKD_DISPOSISI A
		WHERE 1=1 AND A.SURAT_MASUK_BKD_ID = ".$reqid."
		) DS ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = (persuratan.AMBIL_SATKER_POSISI_LOGIN_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID, ".$satuankerjaid."))
		LEFT JOIN persuratan.SURAT_MASUK_BKD_DISPOSISI DSB ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = DSB.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID
		LEFT JOIN SATUAN_KERJA S ON DS.SATUAN_KERJA_ASAL_ID = S.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT
			GENERATEZERO(CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT),3) NO_AGENDA_BARUbak
			, CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT) NO_AGENDA_BARU
			, TO_CHAR(TANGGAL, 'YYYY') TAHUN_AGENDA
			FROM persuratan.SURAT_MASUK_BKD A WHERE 1=1
			GROUP BY TO_CHAR(TANGGAL, 'YYYY')
		) NB ON NB.TAHUN_AGENDA = TO_CHAR(A.TANGGAL, 'YYYY')
		,
		(
			SELECT SATUAN_KERJA_ID SATUAN_KERJA_BKDPP_ID
			FROM SATUAN_KERJA
			WHERE STATUS_SATUAN_KERJA_BKPP = 1 LIMIT 1
		) XX
		WHERE 1=1
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

    function selectByParamsDataSurat($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $statementdisposisi="", $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_ID ASC')
	{
		$str = "	
		SELECT 
			A.SURAT_MASUK_BKD_ID, A.JENIS_ID
			, CASE WHEN A.SATUAN_KERJA_ASAL_ID IS NULL THEN A.SATUAN_KERJA_ASAL_NAMA ELSE AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_ASAL_NAMA
			, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_ASAL_NAMABAK, A.TANGGAL, A.NOMOR, A.PERIHAL
			, A.NO_AGENDA VAL_NO_AGENDA
			, CASE WHEN COALESCE(NULLIF(A.NO_AGENDA,'') , NULL ) IS NULL THEN NB.NO_AGENDA_BARU ELSE A.NO_AGENDA END NO_AGENDA
			, COALESCE(DS.TANGGAL, NOW()) TANGGAL_TERIMA, DS.TANGGAL_DISPOSISI
			, CASE WHEN DS.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 THEN DS.SATUAN_KERJA_TUJUAN_ID ELSE DS.SATUAN_KERJA_ASAL_ID END BATAS_SATUAN_KERJA_CARI_ID
			, AMBIL_SATKER_NAMA(DS.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_DITERUSKAN_NAMA
			, AMBIL_SATKER_JABATAN(DS.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA
			, DS.SATUAN_KERJA_TUJUAN_ID SATUAN_KERJA_DITERUSKAN_ID, DS.SURAT_MASUK_BKD_DISPOSISI_ID, DS.TERBACA, DS.TERDISPOSISI, DS.ISI
			, DS.SURAT_AWAL, DS.POSISI_TEKNIS
			, ID_POSISI_SURAT POSISI_SURAT
			, POSISI_ID_SURAT
			, persuratan.AMBIL_SATKER_POSISI_LOGIN_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID, ".$satuankerjaid.") POSISI_ID_LOGIN_SURAT
			, S.STATUS_KELOMPOK_PEGAWAI_USUL
			, DSB.TERBACA TERBACA_DISPOSISI
			, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
			, AMBIL_SATKER_JABATAN(SATUAN_KERJA_BKDPP_ID) KEPALA_SATUAN_KERJA_BKDPP_NAMA
		FROM persuratan.SURAT_MASUK_BKD A
		INNER JOIN
		(
			SELECT SURAT_MASUK_BKD_ID
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
			WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
			GROUP BY SURAT_MASUK_BKD_ID
		) B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
		LEFT JOIN ( 
			SELECT *
			FROM
			(
			SELECT SURAT_MASUK_BKD_DISPOSISI_ID POSISI_ID_SURAT, SURAT_MASUK_BKD_ID, ID_POSISI_SURAT, ID_POSISI_SURAT_BACA
			FROM persuratan.SURAT_MASUK_BKD_DISPOSISI_POSISI A
			) A
			INNER JOIN 
			(
			SELECT SURAT_MASUK_BKD_ID SURAT_MASUK_BKD_ID_ALIAS, SURAT_MASUK_BKD_DISPOSISI_ID, SATUAN_KERJA_ASAL_ID, POSISI_TEKNIS FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
			) B ON POSISI_ID_SURAT = SURAT_MASUK_BKD_DISPOSISI_ID
		) PD ON PD.SURAT_MASUK_BKD_ID = A.SURAT_MASUK_BKD_ID
		LEFT JOIN persuratan.SURAT_MASUK_BKD_DISPOSISI DS ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = (persuratan.AMBIL_SATKER_POSISI_LOGIN_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID, ".$satuankerjaid."))
		LEFT JOIN persuratan.SURAT_MASUK_BKD_DISPOSISI DSB ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = DSB.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID
		LEFT JOIN SATUAN_KERJA S ON DS.SATUAN_KERJA_ASAL_ID = S.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT
			GENERATEZERO(CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT),3) NO_AGENDA_BARUbak
			, CAST(MAX(COALESCE(CAST(COALESCE(NULLIF(A.NO_AGENDA,'')) AS NUMERIC),0)) + 1 AS TEXT) NO_AGENDA_BARU
			, TO_CHAR(TANGGAL, 'YYYY') TAHUN_AGENDA
			FROM persuratan.SURAT_MASUK_BKD A WHERE 1=1
			GROUP BY TO_CHAR(TANGGAL, 'YYYY')
		) NB ON NB.TAHUN_AGENDA = TO_CHAR(A.TANGGAL, 'YYYY')
		,
		(
			SELECT SATUAN_KERJA_ID SATUAN_KERJA_BKDPP_ID
			FROM SATUAN_KERJA
			WHERE STATUS_SATUAN_KERJA_BKPP = 1 LIMIT 1
		) XX
		WHERE 1=1
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

    function selectByParamsSuratBerikutnya($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_ID ASC')
	{
		$str = "
		SELECT 
			A.SURAT_MASUK_BKD_ID, A.JENIS_ID, DSB.TERBACA TERBACA_DISPOSISI, DS.TERBACA, DS.TERDISPOSISI
		FROM persuratan.SURAT_MASUK_BKD A
		INNER JOIN
		(
		SELECT * FROM persuratan.SURAT_MASUK_BKD_DISPOSISI A
		WHERE 1=1
			AND EXISTS
			(
				SELECT 1 FROM persuratan.SURAT_MASUK_BKD_DISPOSISI X WHERE 1=1 AND (X.TERBACA IS NULL OR X.TERDISPOSISI IS NULL) AND X.SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
				AND X.SURAT_MASUK_BKD_ID = A.SURAT_MASUK_BKD_ID
				GROUP BY SURAT_MASUK_BKD_ID
			)
			AND (A.TERBACA IS NULL OR TERDISPOSISI IS NULL)
		) DS ON DS.SURAT_MASUK_BKD_ID = A.SURAT_MASUK_BKD_ID
		LEFT JOIN persuratan.SURAT_MASUK_BKD_DISPOSISI DSB ON DS.SURAT_MASUK_BKD_DISPOSISI_ID = DSB.SURAT_MASUK_BKD_DISPOSISI_PARENT_ID
		WHERE 1=1
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

    function getCountByParamsSurat($paramsArray=array(), $satuankerjaid="", $jenisid="", $statement='')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_MASUK_BKD_DISPOSISI A
				INNER JOIN
				(
					SELECT SURAT_MASUK_BKD_DISPOSISI_ID
					FROM persuratan.SURAT_MASUK_BKD_DISPOSISI_DISPOSISI
					WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
					".$statementdisposisi."
					GROUP BY SURAT_MASUK_BKD_DISPOSISI_ID
				) B ON A.SURAT_MASUK_BKD_DISPOSISI_ID = B.SURAT_MASUK_BKD_DISPOSISI_ID
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
				FROM persuratan.SURAT_MASUK_BKD_DISPOSISI A
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

    function selectjenispelayanantujuan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.URUT ASC')
	{
		$str = "
		SELECT 
			A.*
		FROM persuratan.jenis_pelayanan_tujuan A
		WHERE 1=1
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

    function updatenoagenda()
	{
		$str= "		
		UPDATE PERSURATAN.SURAT_MASUK_BKD
		SET
			NO_AGENDA= '".$this->getField("NO_AGENDA")."'
		WHERE  SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatebypass()
	{
		$str1= "
		UPDATE persuratan.surat_masuk_bkd_disposisi
		SET SATUAN_KERJA_TUJUAN_ID = ".$this->getField("SATUAN_KERJA_TUJUAN_ID")."
		, TANGGAL = CURRENT_DATE
		, TANGGAL_DISPOSISI = CURRENT_DATE
		, TERBACA = 1, TERBALAS = NULL, TERDISPOSISI = 1, SURAT_AWAL = 1, STATUS_POSISI_SURAT = 1
		, ISI = 'BY PASS SYSTEM'
		WHERE SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")."
		";
		$this->query = $str1;
		// echo $str;str1;
		$this->execQuery($str1);

		$str2= "
		UPDATE persuratan.surat_masuk_bkd
		SET NO_AGENDA = '".$this->getField("NO_AGENDA")."'
		WHERE SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
		";
		$this->query = $str2;
		// echo $str;str2;
		$this->execQuery($str2);

		// $str3= "
		// UPDATE persuratan.surat_masuk_bkd_disposisi
		// SET TERBACA = 1
		// WHERE SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
		// AND SATUAN_KERJA_ASAL_ID = ".$this->getField("SATUAN_KERJA_TUJUAN_ID")."
		// ";
		// $this->query = $str3;
		// // echo $str;str3;
		// return $this->execQuery($str3);

		$str4= "
		UPDATE persuratan.surat_masuk_pegawai
		SET STATUS_BERKAS = 9
		WHERE SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
		";
		$this->query = $str4;
		// echo $str;str4;
		return $this->execQuery($str4);

		// $str5= "
		// UPDATE persuratan.surat_masuk_pegawai
		// SET STATUS_BERKAS = 10
		// WHERE SURAT_MASUK_BKD_ID = ".$this->getField("SURAT_MASUK_BKD_ID")."
		// ";
		// $this->query = $str5;
		// // echo $str;str5;
		// return $this->execQuery($str5);
    }

// ;
// ;
// ;
	
  } 
?>