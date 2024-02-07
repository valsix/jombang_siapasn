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

class Klarifikasi extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function Klarifikasi()
	{
	  $this->Entity(); 
	}

	function insert()
	{
		$this->setField("KLARIFIKASI_ID", $this->getNextId("KLARIFIKASI_ID","presensi.KLARIFIKASI"));

		$str= "
		INSERT INTO presensi.KLARIFIKASI
		(
			KLARIFIKASI_ID, JENIS_KLARIFIKASI, TANGGAL_MULAI, TANGGAL_SELESAI
			, NOMOR_SURAT, TANGGAL_SURAT, KETERANGAN
			, UBAH_STATUS, STATUS, ALASAN_TOLAK
			, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_UPDATE
		) 
		VALUES 
		( 
			'".$this->getField("KLARIFIKASI_ID")."'
			, '".$this->getField("JENIS_KLARIFIKASI")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, '".$this->getField("NOMOR_SURAT")."'
			, ".$this->getField("TANGGAL_SURAT")."
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("UBAH_STATUS")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("ALASAN_TOLAK")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, ".$this->getField("LAST_CREATE_DATE")."
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_UPDATE")."
		)
		"; 
		$this->id= $this->getField("KLARIFIKASI_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str= "
		UPDATE presensi.KLARIFIKASI
		SET
		TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
		, TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI")."
		, NOMOR_SURAT= '".$this->getField("NOMOR_SURAT")."'
		, TANGGAL_SURAT= ".$this->getField("TANGGAL_SURAT")."
		, KETERANGAN= '".$this->getField("KETERANGAN")."'
		, UBAH_STATUS= '".$this->getField("UBAH_STATUS")."'
		, STATUS= '".$this->getField("STATUS")."'
		, ALASAN_TOLAK= '".$this->getField("ALASAN_TOLAK")."'
		, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_UPDATE= ".$this->getField("LAST_UPDATE")."
		WHERE KLARIFIKASI_ID = '".$this->getField("KLARIFIKASI_ID")."'
		";
		
		$this->query= $str;
		// echo "xxx-".$str;exit();
		return $this->execQuery($str);
	}

	function insertDetil()
	{
		$this->setField("KLARIFIKASI_DETIL_ID", $this->getNextId("KLARIFIKASI_DETIL_ID","presensi.KLARIFIKASI_DETIL"));

		$str= "
		INSERT INTO presensi.KLARIFIKASI_DETIL
		(
			KLARIFIKASI_DETIL_ID, KLARIFIKASI_ID, JENIS_KLARIFIKASI, TANGGAL_MULAI, TANGGAL_SELESAI
			, NOMOR_SURAT, TANGGAL_SURAT, KETERANGAN
			, UBAH_STATUS, STATUS, ALASAN_TOLAK, KODE
			, PEGAWAI_ID, JABATAN_TAMBAHAN_ID, PANGKAT_RIWAYAT_ID
		) 
		VALUES 
		( 
			'".$this->getField("KLARIFIKASI_DETIL_ID")."'
			, '".$this->getField("KLARIFIKASI_ID")."'
			, '".$this->getField("JENIS_KLARIFIKASI")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, '".$this->getField("NOMOR_SURAT")."'
			, ".$this->getField("TANGGAL_SURAT")."
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("UBAH_STATUS")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("ALASAN_TOLAK")."'
			, '".$this->getField("KODE")."'
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("JABATAN_TAMBAHAN_ID")."
			, ".$this->getField("PANGKAT_RIWAYAT_ID")."
		)
		"; 
		$this->id= $this->getField("KLARIFIKASI_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertgantistatus()
	{
		$this->setField("KLARIFIKASI_ID", $this->getNextId("KLARIFIKASI_ID","presensi.KLARIFIKASI"));

		$str= "
		INSERT INTO presensi.KLARIFIKASI
		(
			KLARIFIKASI_ID, JENIS_KLARIFIKASI, TANGGAL_MULAI, TANGGAL_SELESAI
			, NOMOR_SURAT, TANGGAL_SURAT, KETERANGAN
			, UBAH_STATUS, STATUS, ALASAN_TOLAK
			, JAM, TIPE_ABSEN_AWAL, TIPE_ABSEN_REVISI
			, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_UPDATE
		) 
		VALUES 
		( 
			'".$this->getField("KLARIFIKASI_ID")."'
			, '".$this->getField("JENIS_KLARIFIKASI")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, '".$this->getField("NOMOR_SURAT")."'
			, ".$this->getField("TANGGAL_SURAT")."
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("UBAH_STATUS")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("ALASAN_TOLAK")."'
			, TO_TIMESTAMP('".$this->getField("JAM")."', 'YYYY-MM-DD HH24:MI:SS')
			, '".$this->getField("TIPE_ABSEN_AWAL")."'
			, '".$this->getField("TIPE_ABSEN_REVISI")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, ".$this->getField("LAST_CREATE_DATE")."
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_UPDATE")."
		)
		"; 
		$this->id= $this->getField("KLARIFIKASI_ID");
		$this->query= $str;
		// echo "xxx-".$str;exit();
		return $this->execQuery($str);
	}

	function updategantistatus()
	{
		$str= "
		UPDATE presensi.KLARIFIKASI
		SET
		TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
		, TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI")."
		, NOMOR_SURAT= '".$this->getField("NOMOR_SURAT")."'
		, TANGGAL_SURAT= ".$this->getField("TANGGAL_SURAT")."
		, KETERANGAN= '".$this->getField("KETERANGAN")."'
		, UBAH_STATUS= '".$this->getField("UBAH_STATUS")."'
		, STATUS= '".$this->getField("STATUS")."'
		, ALASAN_TOLAK= '".$this->getField("ALASAN_TOLAK")."'
		, JAM= TO_TIMESTAMP('".$this->getField("JAM")."', 'YYYY-MM-DD HH24:MI:SS')
		, TIPE_ABSEN_AWAL= '".$this->getField("TIPE_ABSEN_AWAL")."'
		, TIPE_ABSEN_REVISI= '".$this->getField("TIPE_ABSEN_REVISI")."'
		, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_UPDATE= ".$this->getField("LAST_UPDATE")."
		WHERE KLARIFIKASI_ID = '".$this->getField("KLARIFIKASI_ID")."'
		";
		
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertdetilgantistatus()
	{
		$this->setField("KLARIFIKASI_DETIL_ID", $this->getNextId("KLARIFIKASI_DETIL_ID","presensi.KLARIFIKASI_DETIL"));

		$str= "
		INSERT INTO presensi.KLARIFIKASI_DETIL
		(
			KLARIFIKASI_DETIL_ID, KLARIFIKASI_ID, JENIS_KLARIFIKASI, TANGGAL_MULAI, TANGGAL_SELESAI
			, NOMOR_SURAT, TANGGAL_SURAT, KETERANGAN
			, UBAH_STATUS, STATUS, ALASAN_TOLAK, KODE
			, PEGAWAI_ID, JABATAN_TAMBAHAN_ID, PANGKAT_RIWAYAT_ID
			, JAM, TIPE_ABSEN_AWAL, TIPE_ABSEN_REVISI
		) 
		VALUES 
		( 
			'".$this->getField("KLARIFIKASI_DETIL_ID")."'
			, '".$this->getField("KLARIFIKASI_ID")."'
			, '".$this->getField("JENIS_KLARIFIKASI")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, '".$this->getField("NOMOR_SURAT")."'
			, ".$this->getField("TANGGAL_SURAT")."
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("UBAH_STATUS")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("ALASAN_TOLAK")."'
			, '".$this->getField("KODE")."'
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("JABATAN_TAMBAHAN_ID")."
			, ".$this->getField("PANGKAT_RIWAYAT_ID")."
			, TO_TIMESTAMP('".$this->getField("JAM")."', 'YYYY-MM-DD HH24:MI:SS')
			, '".$this->getField("TIPE_ABSEN_AWAL")."'
			, '".$this->getField("TIPE_ABSEN_REVISI")."'
		)
		"; 
		$this->id= $this->getField("KLARIFIKASI_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertsatuankerja()
	{
		$this->setField("KLARIFIKASI_ID", $this->getNextId("KLARIFIKASI_ID","presensi.KLARIFIKASI"));

		$str= "
		INSERT INTO presensi.KLARIFIKASI
		(
			KLARIFIKASI_ID, JENIS_KLARIFIKASI, TANGGAL_MULAI, TANGGAL_SELESAI
			, NOMOR_SURAT, TANGGAL_SURAT, KETERANGAN
			, UBAH_STATUS, STATUS, ALASAN_TOLAK
			, SATUAN_KERJA_ID, SATUAN_KERJA_STATUS, JENIS_FM
			, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_UPDATE
		) 
		VALUES 
		( 
			'".$this->getField("KLARIFIKASI_ID")."'
			, '".$this->getField("JENIS_KLARIFIKASI")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, '".$this->getField("NOMOR_SURAT")."'
			, ".$this->getField("TANGGAL_SURAT")."
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("UBAH_STATUS")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("ALASAN_TOLAK")."'
			, ".$this->getField("SATUAN_KERJA_ID")."
			, '".$this->getField("SATUAN_KERJA_STATUS")."'
			, '".$this->getField("JENIS_FM")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, ".$this->getField("LAST_CREATE_DATE")."
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_UPDATE")."
		)
		"; 
		$this->id= $this->getField("KLARIFIKASI_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatesatuankerja()
	{
		$str= "
		UPDATE presensi.KLARIFIKASI
		SET
		TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
		, TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI")."
		, NOMOR_SURAT= '".$this->getField("NOMOR_SURAT")."'
		, TANGGAL_SURAT= ".$this->getField("TANGGAL_SURAT")."
		, KETERANGAN= '".$this->getField("KETERANGAN")."'
		, UBAH_STATUS= '".$this->getField("UBAH_STATUS")."'
		, STATUS= '".$this->getField("STATUS")."'
		, ALASAN_TOLAK= '".$this->getField("ALASAN_TOLAK")."'
		, SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID")."
		, SATUAN_KERJA_STATUS= '".$this->getField("SATUAN_KERJA_STATUS")."'
		, JENIS_FM= '".$this->getField("JENIS_FM")."'
		, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_UPDATE= ".$this->getField("LAST_UPDATE")."
		WHERE KLARIFIKASI_ID = '".$this->getField("KLARIFIKASI_ID")."'
		";
		
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatedetildata()
	{
		$str= "
		UPDATE presensi.KLARIFIKASI_DETIL
		SET
		STATUS= '".$this->getField("STATUS")."'
		WHERE KLARIFIKASI_ID = '".$this->getField("KLARIFIKASI_ID")."'
		";
		
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatedetilkode()
	{
		$str= "
		UPDATE presensi.KLARIFIKASI_DETIL
		SET
		KODE= '".$this->getField("KODE")."'
		WHERE KLARIFIKASI_DETIL_ID = '".$this->getField("KLARIFIKASI_DETIL_ID")."'
		";
		
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatesimpanulang()
	{
		$str0= "
		UPDATE presensi.KLARIFIKASI
		SET
		LAST_UPDATE_USER= '".$this->getField("LAST_USER")."'
		, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE")."
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_UPDATE= ".$this->getField("LAST_UPDATE")."
		WHERE KLARIFIKASI_ID= '".$this->getField("KLARIFIKASI_ID")."'
		";
		
		$this->query= $str0;
		// echo $str0;exit();
		$this->execQuery($str0);

		$str= "
		UPDATE presensi.KLARIFIKASI_DETIL
		SET
		KLARIFIKASI_ID= '".$this->getField("KLARIFIKASI_ID")."'
		WHERE KLARIFIKASI_ID= '".$this->getField("KLARIFIKASI_ID")."'
		";
		
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertdetilsatuankerja()
	{
		$this->setField("KLARIFIKASI_DETIL_ID", $this->getNextId("KLARIFIKASI_DETIL_ID","presensi.KLARIFIKASI_DETIL"));

		$str= "
		INSERT INTO presensi.KLARIFIKASI_DETIL
		(
			KLARIFIKASI_DETIL_ID, KLARIFIKASI_ID, JENIS_KLARIFIKASI, TANGGAL_MULAI, TANGGAL_SELESAI
			, NOMOR_SURAT, TANGGAL_SURAT, KETERANGAN
			, UBAH_STATUS, STATUS, ALASAN_TOLAK, KODE
			, PEGAWAI_ID, JABATAN_TAMBAHAN_ID, PANGKAT_RIWAYAT_ID
			, SATUAN_KERJA_ID, SATUAN_KERJA_STATUS, JENIS_FM
		) 
		VALUES 
		( 
			'".$this->getField("KLARIFIKASI_DETIL_ID")."'
			, '".$this->getField("KLARIFIKASI_ID")."'
			, '".$this->getField("JENIS_KLARIFIKASI")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_SELESAI")."
			, '".$this->getField("NOMOR_SURAT")."'
			, ".$this->getField("TANGGAL_SURAT")."
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("UBAH_STATUS")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("ALASAN_TOLAK")."'
			, '".$this->getField("KODE")."'
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("JABATAN_TAMBAHAN_ID")."
			, ".$this->getField("PANGKAT_RIWAYAT_ID")."
			, ".$this->getField("SATUAN_KERJA_ID")."
			, '".$this->getField("SATUAN_KERJA_STATUS")."'
			, '".$this->getField("JENIS_FM")."'
		)
		"; 
		$this->id= $this->getField("KLARIFIKASI_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function delete()
    {
        $str = "DELETE FROM presensi.KLARIFIKASI
                WHERE 
                  KLARIFIKASI_ID = ".$this->getField("KLARIFIKASI_ID").""; 
                  
        $this->query = $str;
        return $this->execQuery($str);
    }

    function deleteDetil()
    {
        $str = "DELETE FROM presensi.KLARIFIKASI_DETIL
                WHERE 
                  KLARIFIKASI_ID = ".$this->getField("KLARIFIKASI_ID").""; 
                  
        $this->query = $str;
        // echo $str;exit;
        return $this->execQuery($str);
    }

	function selectByParamsDinasLuar($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_dinas_luar') BUKTI_PENDUKUNG
		, TO_DATE(A.TANGGAL_SELESAI, 'YYYY-MM-DD') - TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD') + 1 LAMA
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1=1
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

	function getCountByParamsDinasLuar($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsDiklat($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_diklat') BUKTI_PENDUKUNG
		, TO_DATE(A.TANGGAL_SELESAI, 'YYYY-MM-DD') - TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD') LAMA
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1=1
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

	function getCountByParamsDiklat($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsMasukPulang($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_masuk_pulang') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, P.NIP_BARU, P.NAMA_LENGKAP
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsMasukPulang($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*, P.*
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsIjinSakit($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_ijin_sakit') BUKTI_PENDUKUNG
		, TO_DATE(A.TANGGAL_SELESAI, 'YYYY-MM-DD') - TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD') + 1 LAMA
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, P.NIP_BARU, P.NAMA_LENGKAP
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsIjinSakit($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*, P.*
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsLupa($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_lupa') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, P.NIP_BARU, P.NAMA_LENGKAP
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsLupa($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*, P.*
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsTugasBelajar($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_tugas_belajar') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, P.NIP_BARU, P.NAMA_LENGKAP
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsTugasBelajar($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*, P.*
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsGantiStatus($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_ganti_status') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		, CASE A.TIPE_ABSEN_AWAL WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Pertengahan' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END || ' (' || A.TIPE_ABSEN_AWAL || ')' TIPE_ABSEN_AWAL_INFO
		, CASE A.TIPE_ABSEN_REVISI WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Pertengahan' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END || ' (' || A.TIPE_ABSEN_REVISI || ')' TIPE_ABSEN_REVISI_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') JAM, TIPE_ABSEN_AWAL, TIPE_ABSEN_REVISI
			, P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsGantiStatus($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*, P.*
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsAsk($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_ask') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		, CASE A.TIPE_ABSEN_AWAL WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Pertengahan' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END || ' (' || A.TIPE_ABSEN_AWAL || ')' TIPE_ABSEN_AWAL_INFO
		, CASE A.TIPE_ABSEN_REVISI WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Pertengahan' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END || ' (' || A.TIPE_ABSEN_REVISI || ')' TIPE_ABSEN_REVISI_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_SELESAI
			, TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') JAM, TIPE_ABSEN_AWAL, TIPE_ABSEN_REVISI
			, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ID) SATUAN_KERJA_INFO, A.SATUAN_KERJA_STATUS, A.JENIS_FM
			FROM presensi.KLARIFIKASI_DETIL A
			WHERE 1=1
			".$satuankerjakondisi."
		) A
		WHERE 1=1
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

	function getCountByParamsAsk($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*
			FROM presensi.KLARIFIKASI_DETIL A
			WHERE 1=1
			".$satuankerjakondisi."
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsForceMajerUnitKerja($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_masuk_pulang_satuan_kerja') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		, CASE A.JENIS_FM WHEN '1' THEN 'Masuk' WHEN '2' THEN 'Pulang' WHEN '3' THEN 'A/S/K' WHEN '4' THEN 'Masuk-A/S/K' WHEN '5' THEN 'A/S/K-Pulang' WHEN '6' THEN 'Masuk-A/S/K-Pulang' END JENIS_FM_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_SELESAI
			, TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') JAM, TIPE_ABSEN_AWAL, TIPE_ABSEN_REVISI
			, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ID) SATUAN_KERJA_INFO, A.SATUAN_KERJA_STATUS, A.JENIS_FM
			FROM presensi.KLARIFIKASI_DETIL A
			WHERE 1=1
			".$satuankerjakondisi."
		) A
		WHERE 1=1
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

	function getCountByParamsForceMajerUnitKerja($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*
			FROM presensi.KLARIFIKASI_DETIL A
			WHERE 1=1
			".$satuankerjakondisi."
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsForceMajerIndividu($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_masuk_pulang_individu') BUKTI_PENDUKUNG
		, TO_DATE(A.TANGGAL_SELESAI, 'YYYY-MM-DD') - TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD') LAMA
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		, CASE A.JENIS_FM WHEN '1' THEN 'Masuk' WHEN '2' THEN 'Pulang' WHEN '3' THEN 'A/S/K' WHEN '4' THEN 'Masuk-A/S/K' WHEN '5' THEN 'A/S/K-Pulang' WHEN '6' THEN 'Masuk-A/S/K-Pulang' END JENIS_FM_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK, A.JENIS_FM
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD HH24:MI:SS'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD HH24:MI:SS')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK, A.JENIS_FM
		) A
		WHERE 1=1
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

	function getCountByParamsForceMajerIndividu($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK, A.JENIS_FM
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD HH24:MI:SS'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD HH24:MI:SS')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK, A.JENIS_FM
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsCuti($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order='')
	{
		$str = "
		SELECT
		A.*
		FROM
		(
			SELECT
			A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT
			, P.NIP_BARU, P.NAMA_LENGKAP
			, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, TO_CHAR(A.TANGGAL_PERMOHONAN, 'YYYY-MM-DD') TANGGAL_PERMOHONAN
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI
			, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.LAMA, A.KETERANGAN, A.CUTI_KETERANGAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			, CASE A.JENIS_CUTI 
			WHEN 1 THEN 'Cuti Tahunan'
			WHEN 2 THEN 'Cuti Besar'
			WHEN 3 THEN 'Cuti Sakit'
			WHEN 4 THEN 'Cuti Bersalin'
			WHEN 5 THEN 'Cuti Alasan Penting'
			WHEN 6 THEN 'Cuti Bersama'
			WHEN 7 THEN 'CLTN'
			ELSE '-' END JENIS_CUTI_NAMA
			FROM CUTI A
			INNER JOIN
			(
				SELECT XXX.*
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsCuti($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT
			, TO_CHAR(A.TANGGAL_PERMOHONAN, 'YYYY-MM-DD') TANGGAL_PERMOHONAN
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI
			, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.LAMA, A.KETERANGAN, A.CUTI_KETERANGAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			, CASE A.JENIS_CUTI 
			WHEN 1 THEN 'Cuti Tahunan'
			WHEN 2 THEN 'Cuti Besar'
			WHEN 3 THEN 'Cuti Sakit'
			WHEN 4 THEN 'Cuti Bersalin'
			WHEN 5 THEN 'Cuti Alasan Penting'
			WHEN 6 THEN 'Cuti Bersama'
			WHEN 7 THEN 'CLTN'
			ELSE '-' END JENIS_CUTI_NAMA
			FROM CUTI A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsKalkulasiUlang($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_kalkulasi_ulang') BUKTI_PENDUKUNG
		, TO_DATE(A.TANGGAL_SELESAI, 'YYYY-MM-DD') - TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD') LAMA
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1=1
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

	function getCountByParamsKalkulasiUlang($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsPaktaIntegritas($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT
		A.*
		, presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID) NIP_NAMA
		, presensi.info_bukti_file(A.KLARIFIKASI_ID, 'klarifikasi_pakta_integritas') BUKTI_PENDUKUNG
		, CASE WHEN A.STATUS = 'Y' THEN 'Disetujui' WHEN A.STATUS = 'T' THEN 'Ditolak' ELSE '-' END STATUS_INFO
		, CASE WHEN A.UBAH_STATUS = 'Y' THEN 'Ya' ELSE 'Tidak' END UBAH_STATUS_INFO
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT, A.KETERANGAN
			, A.ALASAN_TOLAK, A.KODE, A.STATUS, A.UBAH_STATUS
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, P.NIP_BARU, P.NAMA_LENGKAP
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
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

	function getCountByParamsPaktaIntegritas($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.*, P.*
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID, XXX.NIP_BARU, XXX.NAMA_LENGKAP
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function selectByParamsKlarifikasi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=' ORDER BY A.KLARIFIKASI_ID')
	{
		$str = "
		SELECT
		A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
		, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
		, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
		, TO_CHAR(A.TANGGAL_MULAI, 'HH24:MI:SS') TANGGAL_MULAI_DETIK, TO_CHAR(A.TANGGAL_SELESAI, 'HH24:MI:SS') TANGGAL_SELESAI_DETIK
		, A.KETERANGAN, A.ALASAN_TOLAK, A.STATUS
		, A.UBAH_STATUS, A.LAST_USER
		, TO_CHAR(A.LAST_UPDATE, 'YYYY-MM-DD HH24:MI:SS') LAST_UPDATE, TO_CHAR(A.LAST_CREATE_DATE, 'YYYY-MM-DD HH24:MI:SS') LAST_CREATE_DATE
		, B.KODE
		, P.PEGAWAI_ID, P.PANGKAT_RIWAYAT_ID, P.JABATAN_RIWAYAT_ID, P.NIP_BARU, P.NAMA_LENGKAP
		, P.JABATAN_RIWAYAT_NAMA
		, TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') JAM
		, A.TIPE_ABSEN_AWAL
		, CASE A.TIPE_ABSEN_AWAL WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Pertengahan' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END || ' (' || A.TIPE_ABSEN_AWAL || ')' TIPE_ABSEN_AWAL_INFO
		, A.TIPE_ABSEN_REVISI
		, CASE A.TIPE_ABSEN_REVISI WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Pertengahan' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END || ' (' || A.TIPE_ABSEN_REVISI || ')' TIPE_ABSEN_REVISI_INFO
		, B.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA(B.SATUAN_KERJA_ID) SATUAN_KERJA_INFO, B.SATUAN_KERJA_STATUS, B.JENIS_FM
		, CASE B.JENIS_FM WHEN '1' THEN 'Masuk' WHEN '2' THEN 'Pulang' WHEN '3' THEN 'A/S/K' WHEN '4' THEN 'Masuk-A/S/K' WHEN '5' THEN 'A/S/K-Pulang' WHEN '6' THEN 'Masuk-A/S/K-Pulang' END JENIS_FM_INFO
		FROM presensi.KLARIFIKASI A
		LEFT JOIN presensi.KLARIFIKASI_DETIL B ON A.KLARIFIKASI_ID = B.KLARIFIKASI_ID
		LEFT JOIN
		(
			SELECT
			XXX.PEGAWAI_ID, XXX.PANGKAT_RIWAYAT_ID, XXX.JABATAN_RIWAYAT_ID
			, XXX.NIP_BARU, XXX.NAMA_LENGKAP, XXX.JABATAN_RIWAYAT_NAMA
			FROM PINFOAKHIR() XXX
			WHERE 1=1
		) P ON P.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE 1=1
		".$statement; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByParamsJenisKlarifikasi($paramsArray=array(), $limit=-1, $from=-1, $statement='', $order=' ORDER BY A.IJIN_KOREKSI_ID')
	{
		$str = "
		SELECT
		A.*
		FROM presensi.IJIN_KOREKSI A
		WHERE 1=1
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

	function selectByParamsValidasiFile($paramsArray=array(), $limit=-1, $from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		A.*
		FROM presensi.KLARIFIKASI_VALIDASI_FILE A
		WHERE 1=1
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

	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
		, A.JABATAN_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
		FROM 
		(
			SELECT XXX.*
			FROM PINFOAKHIR() XXX
		) A 
		WHERE 1=1
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

	function selectByParamsAbsensi($paramsArray=array(), $limit=-1, $from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		A.*
		, TO_CHAR(A.JAM, 'YYYY-MM-DD') TANGGAL, TO_CHAR(A.JAM, 'HH24:MI:SS') WAKTU
		, CASE A.TIPE_ABSEN WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Siang' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END TIPE_ABSEN_INFO
		FROM presensi.ABSENSI A
		WHERE 1=1
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

	function selectByParamsAbsensiKoreksi($periode, $statement='')
	{
		$str = "
		SELECT
		A.*
		FROM partisi.absensi_koreksi_".$periode." A
		WHERE 1=1
		";
		
		$str .= $statement;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,-1,-1);
	}

	function selectByParamsKlarifikasDetil($statement='')
	{
		$str = "
		SELECT
		A.*
		, TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') JAM_INFO
		FROM presensi.KLARIFIKASI_DETIL A
		WHERE 1=1
		";
		
		$str .= $statement;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,-1,-1);
	}

} 
?>