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
  
  class Pelayanan_karis_karsu extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pelayanan_karis_karsu()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
			SELECT A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.NIP_BARU
				, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID
				, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
				, AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_NAMA_UPT
				, CASE WHEN SMP.SURAT_MASUK_UPT_ID IS NOT NULL THEN AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) ELSE AMBIL_SATKER_NAMA(SMB.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_NAMA_BKD
				, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_LENGKAP
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, A.NAMA NAMA_SAJA
				, SMB.NOMOR NOMOR_USUL_BKDPP, L.LAST_DATE PROSES_TANGGAL, L.INFO_PROSES PROSES_STATUS
				, SMP.STATUS_KEMBALI, SMP.STATUS_PERNAH_TURUN
				, SKR.NOMOR NOMOR_SURAT_KELUAR, SMP.STATUS_SURAT_KELUAR
				, SMP.USULAN_SURAT_URUT
			FROM PEGAWAI A
			INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
			LEFT JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
			LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
			LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
			LEFT JOIN
			(
				SELECT A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID
				, A.SATUAN_KERJA_ID, A.INFO_LOG, A.LAST_USER, A.LAST_DATE
				, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
				FROM persuratan.SURAT_MASUK_PEGAWAI_LOG A
				INNER JOIN
				(
				SELECT SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID, MAX(LAST_DATE) LAST_DATE
				FROM persuratan.SURAT_MASUK_PEGAWAI_LOG
				GROUP BY SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID
				) B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID AND A.JENIS_ID = B.JENIS_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID AND A.LAST_DATE = B.LAST_DATE
			) L ON SMP.SURAT_MASUK_PEGAWAI_ID = L.SURAT_MASUK_PEGAWAI_ID AND SMP.JENIS_ID = L.JENIS_ID AND SMP.PEGAWAI_ID = L.PEGAWAI_ID
			LEFT JOIN persuratan.SURAT_KELUAR_BKD SKR ON SMP.USULAN_SURAT_ID = SKR.USULAN_SURAT_ID
			WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement." ".$order;
		$this->query = $str;

		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.BAHASA_ID) AS ROWCOUNT 
				FROM BAHASA A
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