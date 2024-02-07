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
  
  class Cetak extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Cetak()
	{
      $this->Entity(); 
    }
		
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
		A.PERIODE, SK.NOMOR, JENIS_KENAIKAN, COALESCE(HUKUMAN_RIWAYAT_ID, 0) HUKUMAN_RIWAYAT_ID 
		, TMT_BERIKUT_GAJI, TANGGAL_SK_HUKUMAN, NO_SK_HUKUMAN 
		, CONCAT(SPLIT_PART(SK.NOMOR, '/', 1), '/', SPLIT_PART(SK.NOMOR, '/', 2), '.', A.NOMOR_URUT, REPLACE(SK.NOMOR, CONCAT(SPLIT_PART(SK.NOMOR, '/', 1), '/', SPLIT_PART(SK.NOMOR, '/', 2)), '')) SK_NOMOR_BARU
		, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE ' ' || P.GELAR_BELAKANG END) NAMA_LENGKAP
		, P.NIP_BARU, CONCAT(PANGKAT_NAMA, ' / (', PR1.PANGKAT_KODE, ')') PANGKAT_NAMA
		, AMBIL_SATKER_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
		, PR1.PEJABAT_PENETAP_NAMA PEJABAT_PENETAP_NAMA_LAMA, PR1.TANGGAL_SK TANGGAL_SK_LAMA, PR1.NO_SK NO_SK_LAMA, PR1.GAJI_POKOK GAJI_POKOK_LAMA
		, CONCAT(PR1.MASA_KERJA_TAHUN, ' Tahun ', COALESCE(PR1.MASA_KERJA_BULAN,0), ' Bulan') MASA_KERJA_LAMA, PR1.TMT_SK TMT_SK_LAMA
		, A.GAJI_BARU, CONCAT(A.MASA_KERJA_TAHUN_BARU, ' Tahun ', COALESCE(A.MASA_KERJA_BULAN_BARU,0), ' Bulan') MASA_KERJA_BARU, A.TMT_BARU TMT_SK_BARU
		, A.SURAT_KELUAR_BKD_ID, SK.TANGGAL
		, TTD1.NAMA_PEJABAT NAMA_PEJABAT_TTD
		, replace(TTD1.PEJABAT_PENETAP_LENGKAP, '\n', '\n') PEJABAT_PENETAP_LENGKAP_TTD
		, TTD1.NIP NIP_TTD
		, TTD1.PANGKAT PANGKAT_TTD
		, AMBIL_SATKER_JABATAN_INDUK(a.SATUAN_KERJA_ID) SATKER_INDUK, A.QR_CODE
		, (SELECT X.NAMA FROM KGB_PERATURAN X WHERE A.TMT_BARU BETWEEN MULAI_BERLAKU AND COALESCE(AKHIR_BERLAKU,A.TMT_BARU)) NAMA_PERATURAN
		FROM KENAIKAN_GAJI_BERKALA A
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN GAJI_RIWAYAT_DATA PR1 ON A.GAJI_RIWAYAT_LAMA_ID = PR1.GAJI_RIWAYAT_ID
		LEFT JOIN persuratan.SURAT_KELUAR_BKD SK ON A.SURAT_KELUAR_BKD_ID = SK.SURAT_KELUAR_BKD_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.HUKUMAN_ID, A.JENIS_HUKUMAN_ID, TMT_BERIKUT_GAJI, TANGGAL_SK TANGGAL_SK_HUKUMAN, NO_SK NO_SK_HUKUMAN  
			FROM HUKUMAN A
		) HK ON HK.HUKUMAN_ID = A.HUKUMAN_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT 
			TANDA_TANGAN_BKD_ID, MULAI_BERLAKU, AKHIR_BERLAKU, NO_NOMENKLATUR_KAB, 
			NO_NOMENKLATUR_BKD, NAMA, PLT_JABATAN, NAMA_PEJABAT, PANGKAT_ID, 
			KODE_PANGKAT, PANGKAT, NIP, PEJABAT_PENETAP
			, CASE WHEN PLT_JABATAN = 'PLT' THEN 'Plt. ' || PEJABAT_PENETAP_LENGKAP ELSE PEJABAT_PENETAP_LENGKAP END PEJABAT_PENETAP_LENGKAP
			FROM TANDA_TANGAN_BKD A
			WHERE 1 = 1
		) TTD1 ON TTD1.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(COALESCE(SK.TANGGAL, CURRENT_DATE)))
		WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM KENAIKAN_GAJI_BERKALA A
				INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
				INNER JOIN GAJI_RIWAYAT_DATA PR1 ON A.GAJI_RIWAYAT_LAMA_ID = PR1.GAJI_RIWAYAT_ID
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