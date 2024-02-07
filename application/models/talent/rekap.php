<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  include_once("functions/talent.func.php");

  class Rekap extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Rekap()
	{
      $this->Entity(); 
    }
    
    function selectByParamsRekapJumlahKuadran($reqTahun, $statement="")
    {
    	$reqTahunSebelum= $reqTahun - 1;
    	$kondisi= kondisifield();
    	// AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'

    	$str = "
    	SELECT T.GROUP_AREA, COALESCE(A.JUMLAH_DATA,0) JUMLAH_DATA
		FROM
		(
			SELECT N GROUP_AREA
			FROM
			(
			WITH RECURSIVE T(N) AS (
			VALUES (1)
			UNION ALL
			SELECT N+1 FROM T WHERE N < 9
			)
			SELECT N FROM T
			) T
		) T
		LEFT JOIN
		(
	    	SELECT GROUP_AREA, COUNT(1) JUMLAH_DATA
	    	FROM
	    	(
		    	SELECT A.PEGAWAI_ID, A.NILAI_KINERJA, A.NILAI_KOMPETENSI
				, COALESCE(CASE ";
				for($i=0; $i < count($kondisi); $i++)
				{
					$str .= "
					WHEN A.NILAI_KINERJA ".$kondisi[$i]["minimaltipekinerja"]." ".$kondisi[$i]["minimalkinerja"]." AND A.NILAI_KINERJA ".$kondisi[$i]["maksimaltipekinerja"]." ".$kondisi[$i]["maksimalkinerja"]." AND A.NILAI_KOMPETENSI ".$kondisi[$i]["minimaltipekompetensi"]." ".$kondisi[$i]["minimalkompetensi"]." AND A.NILAI_KOMPETENSI ".$kondisi[$i]["maksimaltipekompetensi"]." ".$kondisi[$i]["maksimalkompetensi"]." THEN ".$kondisi[$i]["kuadran"];
				}
				
				$str .= " END,1) GROUP_AREA
				FROM
				(
					SELECT A.PEGAWAI_ID, COALESCE(B.NILAI_KINERJA,0) NILAI_KINERJA, A.NILAI_KOMPETENSI
					FROM
					(
						SELECT A.PEGAWAI_ID, COALESCE(JPM,0) * 100 NILAI_KOMPETENSI
						FROM talent.penilaian A
						WHERE 1=1
						AND EXISTS
						(
							SELECT 1
							FROM
							(
								SELECT A.PEGAWAI_ID, MAX(CAST(TO_CHAR(A.TANGGAL_TES, 'YYYY') AS INTEGER)) TAHUN
								FROM talent.penilaian A
								WHERE 1=1
								GROUP BY A.PEGAWAI_ID
							) X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND CAST(X.TAHUN AS TEXT) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
						)
					) A
					LEFT JOIN
					(
						SELECT 
						PEGAWAI_ID, PRESTASI_HASIL NILAI_KINERJA
						FROM PENILAIAN_SKP A
						WHERE 1=1 AND A.TAHUN = ".$reqTahunSebelum."
					) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
					INNER JOIN
					(
						SELECT
						A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA
						, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP, ESELON_GROUP_ID, A.TIPE_PEGAWAI_ID
						FROM PEGAWAI A
						LEFT JOIN
						(
							SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA
							, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
							, SUBSTRING(CAST(A.ESELON_ID AS TEXT), 1, 1) ESELON_GROUP_ID
							FROM JABATAN_RIWAYAT A
							LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
						) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
						WHERE 1 = 1 ".$statement."
					) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
					WHERE 1=1
				) A
				WHERE 1=1
			) A
			WHERE 1=1
		 	GROUP BY GROUP_AREA
		) A ON A.GROUP_AREA = T.GROUP_AREA
		ORDER BY T.GROUP_AREA
    	"; 
    	$this->query = $str;
    	// echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsInfoDetil($paramsArray=array(),$limit=-1,$from=-1, $reqTahun, $statement="")
    {
    	$reqTahunSebelum= $reqTahun- 1;
    	$kondisi= kondisifield();

    	$str = "
    	SELECT *
    	FROM
    	(
    	SELECT
    	A.PEGAWAI_ID, A.NILAI_KINERJA, A.NILAI_KOMPETENSI
    	, A.NIP_LAMA, A.NIP_BARU, A.NAMA_LENGKAP, A.CATATAN_STRENGTH, A.KESIMPULAN
    	, A.JABATAN_NAMA, A.SATUAN_KERJA_ID, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK, ESELON_GROUP_ID, TIPE_PEGAWAI_ID
		, COALESCE(CASE ";
		for($i=0; $i < count($kondisi); $i++)
		{
			$str .= "
			WHEN A.NILAI_KINERJA ".$kondisi[$i]["minimaltipekinerja"]." ".$kondisi[$i]["minimalkinerja"]." AND A.NILAI_KINERJA ".$kondisi[$i]["maksimaltipekinerja"]." ".$kondisi[$i]["maksimalkinerja"]." AND A.NILAI_KOMPETENSI ".$kondisi[$i]["minimaltipekompetensi"]." ".$kondisi[$i]["minimalkompetensi"]." AND A.NILAI_KOMPETENSI ".$kondisi[$i]["maksimaltipekompetensi"]." ".$kondisi[$i]["maksimalkompetensi"]." THEN ".$kondisi[$i]["kuadran"];
		}
		// AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
		
		$str .= " END,1) GROUP_AREA
		FROM
		(
			SELECT
			A.PEGAWAI_ID, COALESCE(B.NILAI_KINERJA,0) NILAI_KINERJA, A.NILAI_KOMPETENSI
			, A.CATATAN_STRENGTH, A.KESIMPULAN
			, P.NIP_LAMA, P.NIP_BARU, P.NAMA_LENGKAP
			, P.JABATAN_NAMA, P.SATUAN_KERJA_ID, P.ESELON_GROUP_ID, TIPE_PEGAWAI_ID
			FROM
			(
				SELECT A.PEGAWAI_ID, COALESCE(JPM,0) * 100 NILAI_KOMPETENSI
				, CATATAN_STRENGTH, KESIMPULAN
				FROM talent.penilaian A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT A.PEGAWAI_ID, MAX(CAST(TO_CHAR(A.TANGGAL_TES, 'YYYY') AS INTEGER)) TAHUN
						FROM talent.penilaian A
						WHERE 1=1
						GROUP BY A.PEGAWAI_ID
					) X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND CAST(X.TAHUN AS TEXT) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
				)
			) A
			LEFT JOIN
			(
				SELECT 
				PEGAWAI_ID, PRESTASI_HASIL NILAI_KINERJA
				FROM PENILAIAN_SKP A
				WHERE 1=1 AND A.TAHUN = ".$reqTahunSebelum."
			) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
			INNER JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, JAB_RIW.JABATAN_NAMA, A.SATUAN_KERJA_ID, ESELON_GROUP_ID, A.TIPE_PEGAWAI_ID
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					, SUBSTRING(CAST(A.ESELON_ID AS TEXT), 1, 1) ESELON_GROUP_ID
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				WHERE 1 = 1
			) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
    	) A WHERE 1=1 ".$statement." ORDER BY A.NILAI_KOMPETENSI DESC, A.NILAI_KINERJA DESC, A.NAMA_LENGKAP"; 

    	foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
    	$this->query = $str;
    	// echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsInfoDetil($paramsArray=array(), $reqTahun, $statement="")
	{
		$reqTahunSebelum= $reqTahun- 1;
    	$kondisi= kondisifield();

		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(";
    	$str .= "
    	SELECT *
    	FROM
    	(
    	SELECT
    	A.PEGAWAI_ID, A.NILAI_KINERJA, A.NILAI_KOMPETENSI
    	, A.NIP_LAMA, A.NIP_BARU, A.NAMA_LENGKAP, A.CATATAN_STRENGTH, A.KESIMPULAN, ESELON_GROUP_ID, TIPE_PEGAWAI_ID, SATUAN_KERJA_ID
		, COALESCE(CASE ";
		for($i=0; $i < count($kondisi); $i++)
		{
			$str .= "
			WHEN A.NILAI_KINERJA ".$kondisi[$i]["minimaltipekinerja"]." ".$kondisi[$i]["minimalkinerja"]." AND A.NILAI_KINERJA ".$kondisi[$i]["maksimaltipekinerja"]." ".$kondisi[$i]["maksimalkinerja"]." AND A.NILAI_KOMPETENSI ".$kondisi[$i]["minimaltipekompetensi"]." ".$kondisi[$i]["minimalkompetensi"]." AND A.NILAI_KOMPETENSI ".$kondisi[$i]["maksimaltipekompetensi"]." ".$kondisi[$i]["maksimalkompetensi"]." THEN ".$kondisi[$i]["kuadran"];
		}
		// AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
		
		$str .= " END,1) GROUP_AREA
		FROM
		(
			SELECT
			A.PEGAWAI_ID, COALESCE(B.NILAI_KINERJA,0) NILAI_KINERJA, A.NILAI_KOMPETENSI
			, A.CATATAN_STRENGTH, A.KESIMPULAN
			, P.NIP_LAMA, P.NIP_BARU, P.NAMA_LENGKAP, ESELON_GROUP_ID, TIPE_PEGAWAI_ID, SATUAN_KERJA_ID
			FROM
			(
				SELECT A.PEGAWAI_ID, COALESCE(JPM,0) * 100 NILAI_KOMPETENSI
				, CATATAN_STRENGTH, KESIMPULAN
				FROM talent.penilaian A
				WHERE 1=1 
				AND EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT A.PEGAWAI_ID, MAX(CAST(TO_CHAR(A.TANGGAL_TES, 'YYYY') AS INTEGER)) TAHUN
						FROM talent.penilaian A
						WHERE 1=1
						GROUP BY A.PEGAWAI_ID
					) X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND CAST(X.TAHUN AS TEXT) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
				)
			) A
			LEFT JOIN
			(
				SELECT 
				PEGAWAI_ID, PRESTASI_HASIL NILAI_KINERJA
				FROM PENILAIAN_SKP A
				WHERE 1=1 AND A.TAHUN = ".$reqTahunSebelum."
			) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
			INNER JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, ESELON_GROUP_ID, A.TIPE_PEGAWAI_ID, A.SATUAN_KERJA_ID
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					, SUBSTRING(CAST(A.ESELON_ID AS TEXT), 1, 1) ESELON_GROUP_ID
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				WHERE 1 = 1
			) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1
    	) A WHERE 1=1 ".$statement."
		) A
    	WHERE 1 = 1 "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
    	$this->query = $str;
    	// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsTahun()
    {
    	$str = "
    	SELECT
    	TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
    	FROM talent.penilaian A
    	WHERE 1=1
    	GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
    	ORDER BY TO_CHAR(A.TANGGAL_TES, 'YYYY') DESC"; 

    	$this->query = $str;
    	// echo $str;exit;
    	return $this->selectLimit($str,-1,-1); 
    }
	
  } 
?>