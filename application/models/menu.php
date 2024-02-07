<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel website.agama.
  * 
  ***/
  // include_once("Entity.php");
  include_once(APPPATH.'/models/Entity.php');

  class Menu extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Menu()
	{
      $this->Entity(); 
    }
	
	function selectByParamsUsulanSurat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USULAN_SURAT_ID ASC')
	{
		$str = "
				SELECT
					A.USULAN_SURAT_ID, A.ID_SEMENTARA, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, A.TANGGAL_BATAS, 
					A.KEPADA, A.PERIHAL, COALESCE(A.SATUAN_KERJA_ASAL_NAMA, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID)) POSISI_TERAKHIR
					, A.SATUAN_KERJA_TUJUAN_NAMA, A.ID_SEMENTARA, A.STATUS_KIRIM
					, COALESCE(JUMLAH_USUL,0) TOTAL_USULAN
					, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
				FROM persuratan.USULAN_SURAT A
				LEFT JOIN
				(
					SELECT USULAN_SURAT_ID, COUNT(1) JUMLAH_USUL FROM persuratan.SURAT_MASUK_PEGAWAI GROUP BY USULAN_SURAT_ID
				) B ON A.USULAN_SURAT_ID = B.USULAN_SURAT_ID
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

	function selectByParamsBkd($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_ID ASC')
	{
		// LEFT JOIN ( 
		// 			SELECT SURAT_MASUK_BKD_DISPOSISI_ID, SATUAN_KERJA_ASAL_ID, POSISI_TEKNIS FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
		// 		) PD ON PD.SURAT_MASUK_BKD_DISPOSISI_ID = persuratan.AMBIL_SATKER_POSISI_ID_SURAT(A.SURAT_MASUK_BKD_ID, A.JENIS_ID)
		$str = "
				SELECT
					A.SURAT_MASUK_BKD_ID, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, A.TANGGAL_BATAS, 
					A.KEPADA, A.PERIHAL, A.SATUAN_KERJA_TUJUAN_ID, A.SATUAN_KERJA_ASAL_ID, B.NAMA SATUAN_KERJA_ASAL_NAMA, C.NAMA SATUAN_KERJA_TUJUAN_NAMA
					, COALESCE(TPU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI, COALESCE(TPPU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI_PROGRES, COALESCE(TPKU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI_KEMBALI
					, A.STATUS_KIRIM, A.STATUS_KIRIM, PBKD.INFO_PROSES POSISI_TERAKHIRBAK1
					,
					CASE WHEN PD.POSISI_TEKNIS = 1 THEN
						AMBIL_SATKER_NAMA(PD.SATUAN_KERJA_ASAL_ID)
					ELSE
						PBKD.INFO_PROSES
					END POSISI_TERAKHIR
					, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
				FROM persuratan.SURAT_MASUK_BKD A
				LEFT JOIN ( 
					SELECT *
					FROM
					(
						SELECT SURAT_MASUK_BKD_DISPOSISI_ID POSISI_ID_SURAT, SURAT_MASUK_BKD_ID, ID_POSISI_SURAT, ID_POSISI_SURAT_BACA
						FROM persuratan.SURAT_MASUK_BKD_DISPOSISI_POSISI A
					) A
					INNER JOIN 
					(
						SELECT SURAT_MASUK_BKD_ID SURAT_MASUK_BKD_ID_ALIAS, SURAT_MASUK_BKD_DISPOSISI_ID, SATUAN_KERJA_ASAL_ID, POSISI_TEKNIS
						FROM persuratan.SURAT_MASUK_BKD_DISPOSISI
					) B ON POSISI_ID_SURAT = SURAT_MASUK_BKD_DISPOSISI_ID
				) PD ON PD.SURAT_MASUK_BKD_ID = A.SURAT_MASUK_BKD_ID
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ASAL_ID = B.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_TUJUAN_ID = C.SATUAN_KERJA_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_BKD TPU ON A.SURAT_MASUK_BKD_ID = TPU.SURAT_MASUK_BKD_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_PROGRES_BKD TPPU ON A.SURAT_MASUK_BKD_ID = TPPU.SURAT_MASUK_BKD_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_KEMBALI_BKD TPKU ON A.SURAT_MASUK_BKD_ID = TPKU.SURAT_MASUK_BKD_ID
				LEFT JOIN 
				(
					SELECT
					A.JENIS_ID, A.SURAT_MASUK_BKD_ID
					, A.SATUAN_KERJA_ID, A.LAST_USER, A.LAST_DATE
					, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
					FROM persuratan.SURAT_MASUK_PEGAWAI_LOG A
					INNER JOIN
					(
					SELECT SURAT_MASUK_BKD_ID, JENIS_ID, MAX(LAST_DATE) LAST_DATE
					FROM persuratan.SURAT_MASUK_PEGAWAI_LOG
					WHERE SURAT_MASUK_BKD_ID IS NOT NULL
					GROUP BY SURAT_MASUK_BKD_ID, JENIS_ID
					) B ON A.JENIS_ID = B.JENIS_ID AND A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID AND A.LAST_DATE = B.LAST_DATE
					GROUP BY A.JENIS_ID, A.SURAT_MASUK_BKD_ID
					, A.SATUAN_KERJA_ID, A.LAST_USER, A.LAST_DATE
					, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID))
				) PBKD ON A.JENIS_ID = PBKD.JENIS_ID AND A.SURAT_MASUK_BKD_ID = PBKD.SURAT_MASUK_BKD_ID
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
    
	function selectByParamsUpt($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_UPT_ID ASC')
	{
		$str = "
				SELECT
					A.SURAT_MASUK_UPT_ID, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, A.TANGGAL_BATAS, 
					A.KEPADA, A.PERIHAL, A.SATUAN_KERJA_TUJUAN_ID, A.SATUAN_KERJA_ASAL_ID, B.NAMA SATUAN_KERJA_ASAL_NAMA, C.NAMA SATUAN_KERJA_TUJUAN_NAMA
					, COALESCE(TPU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI, COALESCE(TPPU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI_PROGRES, COALESCE(TPKU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI_KEMBALI
					, A.STATUS_KIRIM, A.TERBACA, CASE A.TERBACA WHEN 1 THEN C.NAMA ELSE '' END POSISI_TERAKHIR
					, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
				FROM persuratan.SURAT_MASUK_UPT A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ASAL_ID = B.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_TUJUAN_ID = C.SATUAN_KERJA_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_UPT TPU ON A.SURAT_MASUK_UPT_ID = TPU.SURAT_MASUK_UPT_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_PROGRES_UPT TPPU ON A.SURAT_MASUK_UPT_ID = TPPU.SURAT_MASUK_UPT_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_KEMBALI_UPT TPKU ON A.SURAT_MASUK_UPT_ID = TPKU.SURAT_MASUK_UPT_ID
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

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $akses_adm_intranet_id="", $table="", $order="ORDER BY A.MENU_ID ASC")
	{
		//, (SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END FROM MENU X WHERE 1=1 AND CASE WHEN LENGTH(A.MENU_ID) = 6 THEN SUBSTR(A.MENU_ID, 1, 6) WHEN LENGTH(A.MENU_ID) = 4 THEN SUBSTR(A.MENU_ID, 1, 4) ELSE SUBSTR(A.MENU_ID, 1, 2) END = X.MENU_PARENT_ID) JUMLAH_CHILD
		$str = "
                SELECT 
				A.MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, A.NAMA MENU, KETERANGAN, LINK_FILE, COALESCE(B.AKSES, 'A') AKSES, C.NAMA 
				, COALESCE(MN.JUMLAH,0) JUMLAH_CHILD, LENGTH(A.MENU_ID) PANJANG_MENU
				, CASE WHEN COALESCE(NULLIF(A.MENU_ICON_ID, ''), NULL) IS NULL THEN 'fa fa-files-o' ELSE A.MENU_ICON_ID END MENU_ICON_ID
				FROM MENU A 
                LEFT JOIN ".$table."_MENU B ON A.MENU_ID = B.MENU_ID AND B.".$table."_ID = '".$akses_adm_intranet_id."' 
                LEFT JOIN ".$table." C ON C.".$table."_ID = '".$akses_adm_intranet_id."'
				LEFT JOIN
				(
					SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH, MENU_PARENT_ID PARENT_ID
					FROM MENU A
					GROUP BY MENU_PARENT_ID
					ORDER BY MENU_PARENT_ID
				) MN ON A.MENU_ID = MN.PARENT_ID
                WHERE A.MENU_ID IS NOT NULL 
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
	
	function selectByParamsMenu($group_id, $akses_id, $table_prefix, $statement = "", $order="ORDER BY URUT, A.MENU_ID ASC")
	{
		//(SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END FROM MENU X WHERE 1=1 AND SUBSTR(A.MENU_ID, 1, 2) = X.MENU_PARENT_ID) JUMLAH_CHILDBAK,
		//(SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END FROM MENU X WHERE 1=1 AND CASE WHEN LENGTH(A.MENU_ID) = 4 THEN SUBSTR(A.MENU_ID, 1, 4) ELSE SUBSTR(A.MENU_ID, 1, 2) END = X.MENU_PARENT_ID) JUMLAH_CHILD,
		$str = "
		SELECT
			A.MENU_ID, A.MENU_PARENT_ID, NAMA, LINK_FILE, LINK_DETIL_FILE, AKSES, ICON,
			COALESCE(MN.JUMLAH,0) JUMLAH_CHILD,
			(SELECT COUNT(".$table_prefix."_ID) FROM ".$table_prefix."_MENU X WHERE SUBSTR(X.MENU_ID, 1, 2) = A.MENU_ID AND ".$table_prefix."_ID = ".$akses_id.") JUMLAH_MENU,
			(SELECT COUNT(".$table_prefix."_ID) FROM ".$table_prefix."_MENU X WHERE SUBSTR(X.MENU_ID, 1, 2) = A.MENU_ID AND ".$table_prefix."_ID = ".$akses_id." AND AKSES = 'D') JUMLAH_DISABLE
			, CASE WHEN COALESCE(NULLIF(A.MENU_ICON_ID, ''), NULL) IS NULL THEN 'fa fa-files-o' ELSE A.MENU_ICON_ID END MENU_ICON_ID
		FROM MENU  A
		LEFT JOIN ".$table_prefix."_MENU B ON A.MENU_ID = B.MENU_ID AND ".$table_prefix."_ID = ".$akses_id."
		LEFT JOIN
		(
			SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH, MENU_PARENT_ID PARENT_ID FROM MENU A GROUP BY MENU_PARENT_ID ORDER BY MENU_PARENT_ID
		) MN ON A.MENU_ID = MN.PARENT_ID
		WHERE MENU_GROUP_ID = ".$group_id."
	    "; 
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsAksesMenu($statement = "", $order="")
	{
		$str = "
		SELECT
			A.USER_GROUP_ID, A.AKSES_APP_SIMPEG_ID, C.MENU_ID, C.NAMA, B.AKSES
		FROM USER_GROUP A
		INNER JOIN AKSES_APP_SIMPEG_MENU B ON A.AKSES_APP_SIMPEG_ID = B.AKSES_APP_SIMPEG_ID
		INNER JOIN MENU C ON B.MENU_ID = C.MENU_ID
		WHERE 1=1
		"; 
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

    function deletemenubkngroup()
	{
		$str = "		
		DELETE FROM menu_sapk
		WHERE USER_GROUP_ID = ".$this->getField("USER_GROUP_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertmenubkngroup()
	{
		$str = "
		INSERT INTO menu_sapk
		(
			MENU_ID, MENU_GROUP_ID, USER_GROUP_ID, LIHAT, KIRIM, TARIK, SYNC
		) 
		VALUES 
		(
			'".$this->getField("MENU_ID")."'
			, ".$this->getField("MENU_GROUP_ID")."
			, ".$this->getField("USER_GROUP_ID")."
			, ".$this->getField("LIHAT")."
			, ".$this->getField("KIRIM")."
			, ".$this->getField("TARIK")."
			, ".$this->getField("SYNC")."
		)
		";

		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectparamsapk($statement = "", $order="ORDER BY A.MENU_ID")
	{
		$str = "
		SELECT
			A.*
		FROM menu_sapk A
		WHERE 1=1
		"; 
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectparam($statement = "", $order="ORDER BY A.MENU_ID, A.URUT")
	{
		$str = "
		SELECT
			A.*
		FROM menu A
		WHERE 1=1
		"; 
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }
	
  } 
?>