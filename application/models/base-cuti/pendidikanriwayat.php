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
  
  class PendidikanRiwayat extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PendidikanRiwayat()
	{
      $this->Entity(); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		 // AND (COALESCE(NULLIF(PR.STATUS, ''), NULL) IS NULL OR PR.STATUS = '2')
		 // AND (COALESCE(NULLIF(JR.STATUS, ''), NULL) IS NULL OR JR.STATUS = '2')
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
			, A.SATUAN_KERJA_ID
			, A.INFO_SATUAN_KERJA_DETIL SATUAN_KERJA_NAMA
			--, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, A.JABATAN_RIWAYAT_ID, JR.NAMA JABATAN_NAMA
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PR.PANGKAT_KODE, A.KARTU_PEGAWAI, CPNS_RIW.TMT_CPNS, PNS_RIW.TMT_PNS, PR.MASA_KERJA_TAHUN, PR.MASA_KERJA_BULAN
		FROM PEGAWAI A
		INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = A.PANGKAT_RIWAYAT_ID
		INNER JOIN JABATAN_RIWAYAT JR ON JR.JABATAN_RIWAYAT_ID = A.JABATAN_RIWAYAT_ID
		LEFT JOIN 
		(
			SELECT
				A.PEGAWAI_ID, B.KODE, A.TMT_CPNS, A.PANGKAT_ID, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN 
			FROM SK_CPNS A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) CPNS_RIW ON A.PEGAWAI_ID = CPNS_RIW.PEGAWAI_ID
		LEFT JOIN 
		(
			SELECT
				A.PEGAWAI_ID, B.KODE, A.TMT_PNS, A.PANGKAT_ID, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN 
			FROM SK_PNS A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID 
		) PNS_RIW ON A.PEGAWAI_ID = PNS_RIW.PEGAWAI_ID 
		WHERE 1 = 1
		AND A.STATUS_PEGAWAI_ID IN (1,2,6)
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
   
  } 
?>