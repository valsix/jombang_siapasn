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
  
  class Combo extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Combo()
	{
      $this->Entity(); 
    }

    function inserthapusdata()
	{
		$this->setField("HAPUS_DATA_ID", $this->getNextId("HAPUS_DATA_ID","validasi.HAPUS_DATA")); 
        $str = "
        INSERT INTO validasi.HAPUS_DATA
        (
	        HAPUS_DATA_ID, PEGAWAI_ID, TEMP_VALIDASI_ID, HAPUS_NAMA, 
	        LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_DATE
        )
        VALUES 
        (
	        ".$this->getField("HAPUS_DATA_ID").",
	        ".$this->getField("PEGAWAI_ID").",
	        ".$this->getField("TEMP_VALIDASI_ID").",
	        '".$this->getField("HAPUS_NAMA")."',
	        '".$this->getField("LAST_CREATE_USER")."',
	        NOW(),
	        '".$this->getField("LAST_CREATE_USER")."',
	        NOW()
	    )";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletehapusdata()
	{
        $str = "
        DELETE FROM validasi.HAPUS_DATA
        WHERE 
        TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."
        AND HAPUS_NAMA= '".$this->getField("HAPUS_NAMA")."'
        AND VALIDASI IS NULL
        ";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function hapusdata()
	{
		$str = "
		DELETE FROM validasi.".$this->getField("TABLE")."
		WHERE TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."
		";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function selectByParamsInfoUpdateData($paramsArray=array(), $limit=-1,$from=-1, $pegawaiid, $statement='', $order='')
	{
		$str = "
		SELECT 	
		A.*
		FROM validasi.validasi_pegawai_perubahandatavalidasi('".$pegawaiid."') A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from);
		
    }
	
    function selectByParamsPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.PANGKAT_ID ASC')
	{
		$str = "
		SELECT 	
		A.*
		FROM PANGKAT A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function getCountByParamsGajiBerlaku($periode, $masakerja, $pangkatid)
	{
		$str= "SELECT AMBIL_GAJI_BERLAKU_TGL('".substr($periode,4,4)."' || '-' || '".substr($periode,2,2)."' || '-' || '".substr($periode,0,2)."', ".$masakerja.", ".$pangkatid.") AS ROWCOUNT "; 
		$this->select($str);
		//echo $str;exit;
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsFormasiCpns($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.FORMASI_CPNS_ID ASC')
	{
		$str = "
		SELECT 	
		A.*
		FROM FORMASI_CPNS A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsJabatanFu($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY LENGTH(A.NAMA), A.NAMA')
	{
		$str = "
		SELECT 	
		A.*
		FROM JABATAN_FU A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from);
		
    }

    function selectByParamsJabatanFt($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY LENGTH(A.NAMA), A.NAMA')
	{
		$str = "
		SELECT 	
		A.*
		FROM JABATAN_FT A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from);
		
    }

    function selectByParamsPejabatPenetap($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY LENGTH(A.NAMA), A.NAMA')
	{
		$str = "
		SELECT 	
		A.*
		FROM PEJABAT_PENETAP A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from);
		
    }

    function selectByParamsPendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENDIDIKAN_ID ASC')
	{
		$str = "
				SELECT A.PENDIDIKAN_ID, A.NAMA
				FROM PENDIDIKAN A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

     function selectByParamsSuamiIstri($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		$str = "
		SELECT 	
			A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.TANGGAL_KAWIN, A.KARTU, 
			A.STATUS_PNS, A.NIP_PNS, A.PEKERJAAN, A.STATUS_TUNJANGAN, A.STATUS_BAYAR, A.BULAN_BAYAR, A.STATUS, A.STATUS_S_I, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL,
			CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai Hidup' WHEN '3' THEN 'Cerai Mati' ELSE 'Belum di set' END STATUS_S_I_NAMA
			, A.SURAT_NIKAH, A.NIK, A.CERAI_SURAT, A.CERAI_TANGGAL, A.CERAI_TMT, A.KEMATIAN_SURAT, A.KEMATIAN_TANGGAL, A.KEMATIAN_TMT
		FROM SUAMI_ISTRI A
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsJurusan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENDIDIKAN_JURUSAN_ID ASC')
	{
		$str = "
				SELECT A.PENDIDIKAN_JURUSAN_ID, A.PENDIDIKAN_ID, A.NAMA, A.LAST_USER, A.LAST_DATE
				, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM PENDIDIKAN_JURUSAN A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsDiklatStruktural($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.DIKLAT_ID ASC')
	{
		$str = "
				SELECT A.DIKLAT_ID, A.NAMA, A.KETERANGAN, A.LAST_USER, A.LAST_DATE
				FROM DIKLAT A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsCariPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT A.*, PF.PATH
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
		, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
		FROM
		(
		SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			, CASE
			WHEN CURRENT_DATE <= G.TANGGAL_AKHIR AND CURRENT_DATE >= G.TANGGAL_MULAI
			THEN 1
			WHEN G.PEGAWAI_ID IS NOT NULL THEN 2
			ELSE 0
			END STATUS_BERLAKU
		FROM PEGAWAI A
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
		LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." 
		) A
		LEFT JOIN (SELECT PEGAWAI_ID, PATH FROM P_PEGAWAI_FILE_DATA('')) PF ON PF.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }


    function selectByParamsSatuanKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SATUAN_KERJA_ID ASC')
    {
    	$str = "
    	SELECT A.SATUAN_KERJA_ID, A.SATUAN_KERJA_PARENT_ID, A.NAMA, A.NAMA_SINGKAT, A.TIPE_ID, A.NAMA_JABATAN, A.TIPE_JABATAN_ID, 
    	A.ESELON_ID, ES.NAMA ESELON_NAMA, A.SATUAN_KERJA_INDUK, A.SATUAN_KERJA_URUTAN_SURAT, A.MASA_BERLAKU_AWAL, 
    	A.MASA_BERLAKU_AKHIR, A.KONVERSI, A.ID_SAPK
    	, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
    	, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_INDUK
    	FROM SATUAN_KERJA A
    	LEFT JOIN ESELON ES ON A.ESELON_ID = ES.ESELON_ID
    	WHERE 1 = 1
    	"; 
    	
    	while(list($key,$val) = each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	
    	$str .= $statement." ".$order;
    	$this->query = $str;
    	return $this->selectLimit($str,$limit,$from); 
    	
    }

    function selectByParamsTugasDetilTambahan($paramsArray=array(),$limit=-1,$from=-1, $tipepegawaiid="", $statement='', $statementsatuankerja='',$order=' ')
	{
		$str = "
		SELECT
		NAMA, TUGAS_TAMBAHAN_ID, SATKER_NAMA, SATKER_ID
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATKER_ID) SATUAN_KERJA_NAMA_DETIL
		FROM
		(
			SELECT
				NAMA, TUGAS_JABATAN_ID TUGAS_TAMBAHAN_ID, NULL SATKER_NAMA, NULL SATKER_ID
			FROM TUGAS_JABATAN
			WHERE TIPE_PEGAWAI_ID IS NOT NULL AND TIPE_PEGAWAI_ID = '".$tipepegawaiid."'
			UNION ALL
			SELECT
				NAMA_JABATAN NAMA, NULL TUGAS_TAMBAHAN_ID, NAMA SATKER_NAMA, SATUAN_KERJA_ID SATKER_ID
			FROM SATUAN_KERJA WHERE TIPE_JABATAN_ID = 2 AND JENIS_JABATAN_ID = ".substr($tipepegawaiid,1,1).$statementsatuankerja."
		) A
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsPlh($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
			NAMA_JABATAN NAMA, NULL TUGAS_TAMBAHAN_ID, NAMA SATKER_NAMA, SATUAN_KERJA_ID SATKER_ID
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
		FROM SATUAN_KERJA A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsPlt($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
			NAMA_JABATAN NAMA, NULL TUGAS_TAMBAHAN_ID, NAMA SATKER_NAMA, SATUAN_KERJA_ID SATKER_ID
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
		FROM SATUAN_KERJA A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsPropinsi($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT PROPINSI_ID, NAMA
				FROM PROPINSI WHERE PROPINSI_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsKabupaten($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT KABUPATEN_ID, NAMA
				FROM KABUPATEN WHERE KABUPATEN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsKecamatan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT KECAMATAN_ID, NAMA
				FROM KECAMATAN WHERE KECAMATAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsKelurahan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT KELURAHAN_ID, NAMA
				FROM KELURAHAN WHERE KELURAHAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsEselon($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ESELON_ID ASC ')
	{
		$str = "
				SELECT A.ESELON_ID, A.NAMA, A.PANGKAT_MINIMAL, A.PANGKAT_MAKSIMAL, 
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM ESELON A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsPendidikanRiwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENDIDIKAN_RIWAYAT_ID ASC')
	{
		$str = "
			SELECT 	
			A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.PENDIDIKAN_JURUSAN_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA
			, A.NAMA, A.TEMPAT, A.KEPALA
			, A.NO_STTB, A.TANGGAL_STTB, A.JURUSAN, A.NO_SURAT_IJIN, A.TANGGAL_SURAT_IJIN, A.STATUS_PENDIDIKAN, A.GELAR_TIPE
			, A.GELAR_DEPAN, A.GELAR_NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS, C.NAMA PENDIDIKAN_NAMA
			, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
			, CASE A.STATUS_PENDIDIKAN
			WHEN '1' THEN 'Pendidikan CPNS'
			WHEN '2' THEN 'Diakui'
			WHEN '3' THEN 'Belum Diakui'
			WHEN '4' THEN 'Riwayat'
			WHEN '5' THEN 'Ijin belajar'
			WHEN '6' THEN 'Tugas Belajar'
			ELSE '-' END STATUS_PENDIDIKAN_NAMA
			, A.STATUS_TUGAS_IJIN_BELAJAR, A.PPPK_STATUS
			, CASE A.STATUS_TUGAS_IJIN_BELAJAR WHEN 1 THEN 'Ijin Belajar' WHEN 2 THEN 'Tugas Belajar' END STATUS_TUGAS_IJIN_BELAJAR_NAMA, A.STATUS_VALIDASI_TUGAS_IJIN_BELAJAR
			FROM PENDIDIKAN_RIWAYAT A
			LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
			LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
			WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

     function selectByParamsGolonganPppk($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.GOLONGAN_PPPK_ID ASC')
	{
		$str = "
			SELECT A.GOLONGAN_PPPK_ID, A.KODE, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM GOLONGAN_PPPK A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsAgama($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.AGAMA_ID ASC')
	{
		$str = "
				SELECT A.AGAMA_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'AKtif' END STATUS_NAMA
				FROM AGAMA A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsJenisPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.JENIS_PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.JENIS_PEGAWAI_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM JENIS_PEGAWAI A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsBank($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.BANK_ID ASC')
	{
		$str = "
				SELECT A.BANK_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'AKtif' END STATUS_NAMA
				FROM BANK A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

  } 
?>