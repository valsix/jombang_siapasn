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
  
  class RekapTalent2023 extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RekapTalent2023()
	{
      $this->Entity(); 
    }

    function ppetapegawai()
    {
        $str = "
        SELECT talent.ppetapegawai2023()
        "; 
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function selectbyparamsformulapenilaiannineboxstandart($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
    {
        $str = "
        SELECT
        A.*
        FROM talent.formula_tp A
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectbypegawai2023($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC")
    {
        $str = "
        SELECT
        A.*
        FROM talent.pegawai_2023 A
        INNER JOIN (SELECT SATUAN_KERJA_ID SID FROM satuan_kerja WHERE MASA_BERLAKU_AKHIR IS NULL) A1 ON SATUAN_KERJA_ID = SID
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectbypegawaijabatan2023($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
    {
        $str = "
        SELECT
        A.*
        FROM talent.pegawai_jabatan_2023 A
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectbypegawaidiklatkursus2023($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
    {
        $str = "
        SELECT
        A.*
        FROM talent.pegawai_diklat_kursus_2023 A
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectbypegawaipendidikan2023($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
    {
        $str = "
        SELECT
        A.*
        FROM talent.pegawai_pendidikan_riwayat_2023 A
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectbykuadranpegawai2023($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="")
    {
        $str = "
        SELECT
        KODE_KUADRAN, ORDER_KUADRAN
        , A.*
        FROM
        (
            SELECT KUADRAN_PEGAWAI, COUNT(1) JUMLAH
            FROM talent.pegawai_2023 A
            INNER JOIN (SELECT SATUAN_KERJA_ID SID FROM satuan_kerja WHERE MASA_BERLAKU_AKHIR IS NULL) A1 ON SATUAN_KERJA_ID = SID
            WHERE 1=1
            ".$statementdetil."
            GROUP BY KUADRAN_PEGAWAI
        ) A
        INNER JOIN
        (
            SELECT 11 ID_KUADRAN, 'I' KODE_KUADRAN, 1 ORDER_KUADRAN UNION ALL
            SELECT 12 ID_KUADRAN, 'II' KODE_KUADRAN, 2 ORDER_KUADRAN UNION ALL
            SELECT 21 ID_KUADRAN, 'III' KODE_KUADRAN, 3 ORDER_KUADRAN UNION ALL
            SELECT 13 ID_KUADRAN, 'IV' KODE_KUADRAN, 4 ORDER_KUADRAN UNION ALL
            SELECT 22 ID_KUADRAN, 'V' KODE_KUADRAN, 5 ORDER_KUADRAN UNION ALL
            SELECT 31 ID_KUADRAN, 'VI' KODE_KUADRAN, 6 ORDER_KUADRAN UNION ALL
            SELECT 23 ID_KUADRAN, 'VII' KODE_KUADRAN, 7 ORDER_KUADRAN UNION ALL
            SELECT 32 ID_KUADRAN, 'VIII' KODE_KUADRAN, 8 ORDER_KUADRAN UNION ALL
            SELECT 33 ID_KUADRAN, 'IX' KODE_KUADRAN, 9 ORDER_KUADRAN
        ) A1 ON ID_KUADRAN = KUADRAN_PEGAWAI
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
        // echo $str;exit;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectbykuadranpegawai2023Popup($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="")
    {
        $str = "
        SELECT
        A.*
        FROM
        (
            SELECT *
            FROM talent.pegawai_2023 A
            INNER JOIN (SELECT SATUAN_KERJA_ID SID FROM satuan_kerja WHERE MASA_BERLAKU_AKHIR IS NULL) A1 ON SATUAN_KERJA_ID = SID
            WHERE 1=1
            ".$statementdetil."
        ) A
        INNER JOIN
        (
            SELECT 11 ID_KUADRAN, 'I' KODE_KUADRAN, 1 ORDER_KUADRAN UNION ALL
            SELECT 12 ID_KUADRAN, 'II' KODE_KUADRAN, 2 ORDER_KUADRAN UNION ALL
            SELECT 21 ID_KUADRAN, 'III' KODE_KUADRAN, 3 ORDER_KUADRAN UNION ALL
            SELECT 13 ID_KUADRAN, 'IV' KODE_KUADRAN, 4 ORDER_KUADRAN UNION ALL
            SELECT 22 ID_KUADRAN, 'V' KODE_KUADRAN, 5 ORDER_KUADRAN UNION ALL
            SELECT 31 ID_KUADRAN, 'VI' KODE_KUADRAN, 6 ORDER_KUADRAN UNION ALL
            SELECT 23 ID_KUADRAN, 'VII' KODE_KUADRAN, 7 ORDER_KUADRAN UNION ALL
            SELECT 32 ID_KUADRAN, 'VIII' KODE_KUADRAN, 8 ORDER_KUADRAN UNION ALL
            SELECT 33 ID_KUADRAN, 'IX' KODE_KUADRAN, 9 ORDER_KUADRAN
        ) A1 ON ID_KUADRAN = KUADRAN_PEGAWAI
        WHERE 1=1
        "; 
        
        while(list($key,$val) = each($paramsArray))
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$sOrder;
        $this->query = $str;
        // echo $str;exit;
                
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectsatkerid($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SATUAN_KERJA_ID')
    {
    	$str = "
        SELECT
            A.SATUAN_KERJA_ID
        FROM satuan_kerja A
        WHERE 1=1
        AND COALESCE(TO_DATE(TO_CHAR(A.MASA_BERLAKU_AWAL, 'YYYY-MM-DD'), 'YYYY-MM-DD'),CURRENT_DATE) <= CURRENT_DATE
        AND COALESCE(TO_DATE(TO_CHAR(A.MASA_BERLAKU_AKHIR, 'YYYY-MM-DD'), 'YYYY-MM-DD'),CURRENT_DATE) >= CURRENT_DATE
    	";
        // AND A.SATUAN_KERJA_PARENT_ID = 0
    	
    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	
    	$str .= $statement." ".$order;
    	$this->query = $str;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function getsatuankerja($id='')
    {
        $str = "SELECT REPLACE(REPLACE(CAST(ambil_id_satuan_kerja_tree_array(".$id.") AS TEXT), '{',''), '}','') ROWCOUNT";
        $this->query = $str;
        // echo $str;exit();
        $this->select($str); 
        if($this->firstRow())
        {
            if($this->getField("ROWCOUNT") == "")
            return $id;
            else
            return $id.",".$this->getField("ROWCOUNT"); 
        }
        else 
            return $id;  
    }

    function selectpegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC')
    {
        $str = "
        SELECT A.*
        FROM
        (
            SELECT
            A.PEGAWAI_ID, A.NIP_BARU, A.SATUAN_KERJA_ID, A.TIPE_PEGAWAI_ID, A.STATUS_PEGAWAI_ID
            , SUBSTRING(COALESCE(JAB_RIW.ESELON_ID,99)::text,1,1) ESELON_GROUP_ID
            , COALESCE(JAB_RIW.ESELON_ID,99) ESELON_ID, PANG_RIW.PANGKAT_ID
            , (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
            , PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
            , JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
            FROM pegawai A
            LEFT JOIN
            (
                SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
                FROM pangkat_riwayat A
                LEFT JOIN pangkat B ON A.PANGKAT_ID = B.PANGKAT_ID
            ) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
            LEFT JOIN
            (
                SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
                FROM jabatan_riwayat A
                LEFT JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
            ) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
            WHERE 1=1
        ) A
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo "xxxx".$str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectrumpunpegawai($paramsArray=array(),$limit=-1,$from=-1, $rumpumid, $statement='',$order=' ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC')
    {
        $str = "
        SELECT A.*
        FROM
        (
            SELECT
            A.PEGAWAI_ID, A.NIP_BARU, A.SATUAN_KERJA_ID, A.TIPE_PEGAWAI_ID, A.STATUS_PEGAWAI_ID
            , SUBSTRING(COALESCE(JAB_RIW.ESELON_ID,99)::text,1,1) ESELON_GROUP_ID
            , COALESCE(JAB_RIW.ESELON_ID,99) ESELON_ID
            , PANG_RIW.PANGKAT_ID
            , (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
            , PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
            , JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
            FROM pegawai A
            LEFT JOIN
            (
                SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
                FROM pangkat_riwayat A
                LEFT JOIN pangkat B ON A.PANGKAT_ID = B.PANGKAT_ID
            ) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
            LEFT JOIN
            (
                SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
                FROM jabatan_riwayat A
                LEFT JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
            ) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
            WHERE 1=1
        ) A
        LEFT JOIN (select * from talent.p_rumpun_rekamjejak(".$rumpumid.")) R1 ON R1.PEGAWAI_ID = A.PEGAWAI_ID
        LEFT JOIN (select * from talent.p_rumpun_kualifikasi(".$rumpumid.")) R2 ON R2.PEGAWAI_ID = A.PEGAWAI_ID
        LEFT JOIN (select * from talent.p_rumpun_kompetensi(".$rumpumid.")) R3 ON R3.PEGAWAI_ID = A.PEGAWAI_ID
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectjabatanriwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
    {
        $str = "
        SELECT
            A.PEGAWAI_ID, A.JABATAN_RIWAYAT_ID ROW_ID, A.NAMA, A.RUMPUN_ID, A.TMT_JABATAN, A.JENIS_JABATAN_ID
            , CASE WHEN LAMA_JABATAN_HITUNG >= 5 THEN 100
            WHEN LAMA_JABATAN_HITUNG >= 4 THEN 80
            WHEN LAMA_JABATAN_HITUNG >= 3 THEN 60
            WHEN LAMA_JABATAN_HITUNG >= 2 THEN 40
            WHEN LAMA_JABATAN_HITUNG >= 1 THEN 20
            ELSE 0 END NILAI_REKAM_JEJAK_HITUNG
        FROM
        (
            SELECT A.*
            , CASE WHEN A.TMT_SELESAI_JABATAN IS NULL THEN
                CASE WHEN
                (datediff('month', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD')) / 12) > 2
                THEN 1 ELSE 0
                END
                +
                datediff('year', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD'))
            ELSE A.LAMA_JABATAN
            END LAMA_JABATAN_HITUNG
            FROM jabatan_riwayat A
        ) A
        WHERE 1=1
        AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectrumpunjabatanriwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
    {
        $str = "
        SELECT
            A.PEGAWAI_ID, A.RUMPUN_ID
            ,
            SUM
            (
                CASE WHEN LAMA_JABATAN_HITUNG >= 5 THEN 100
                WHEN LAMA_JABATAN_HITUNG >= 4 THEN 80
                WHEN LAMA_JABATAN_HITUNG >= 3 THEN 60
                WHEN LAMA_JABATAN_HITUNG >= 2 THEN 40
                WHEN LAMA_JABATAN_HITUNG >= 1 THEN 20
                ELSE 0 END
            ) NILAI_REKAM_JEJAK_HITUNG
        FROM
        (
            SELECT A.*
            , CASE WHEN A.TMT_SELESAI_JABATAN IS NULL THEN
                CASE WHEN
                (datediff('month', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD')) / 12) > 2
                THEN 1 ELSE 0
                END
                +
                datediff('year', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD'))
            ELSE A.LAMA_JABATAN
            END LAMA_JABATAN_HITUNG
            FROM jabatan_riwayat A
        ) A
        WHERE 1=1
        AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
        AND A.RUMPUN_ID IS NOT NULL
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." GROUP BY A.PEGAWAI_ID, A.RUMPUN_ID ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectpendidikanriwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANGGAL_STTB DESC')
    {
        $str = "
        SELECT
            A.PEGAWAI_ID, A.PENDIDIKAN_RIWAYAT_ID ROW_ID, A.JURUSAN NAMA, A.RUMPUN_ID, A.TANGGAL_STTB, A.STATUS_PENDIDIKAN, A.NILAI_REKAM_JEJAK
        FROM pendidikan_riwayat A
        WHERE 1=1
        AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
        AND A.STATUS_PENDIDIKAN IN ('1', '2')
        AND A.PENDIDIKAN_ID > 8
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectrumpunpendidikanriwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
    {
        $str = "
        SELECT
        A.PEGAWAI_ID, A.RUMPUN_ID
        , SUM(A.NILAI_REKAM_JEJAK) NILAI_REKAM_JEJAK
        FROM
        (
            SELECT
                A.PEGAWAI_ID
                , UNNEST(STRING_TO_ARRAY(A.RUMPUN_ID, ','))::numeric RUMPUN_ID
                , A.NILAI_REKAM_JEJAK
            FROM pendidikan_riwayat A
            WHERE 1=1
            AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
            AND A.STATUS_PENDIDIKAN IN ('1', '2')
            AND A.PENDIDIKAN_ID > 8
            AND A.RUMPUN_ID IS NOT NULL
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement."
        ) A
        WHERE A.RUMPUN_ID IS NOT NULL
        GROUP BY A.PEGAWAI_ID, A.RUMPUN_ID ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectdiklatriwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANGGAL_MULAI DESC, A.INFO_URUT')
    {
        $str = "
        SELECT A.*
        FROM
        (
            SELECT
            A.PEGAWAI_ID, A.DIKLAT_STRUKTURAL_ID ROW_ID, 1 INFO_URUT, B.NAMA DIKLAT_NAMA, A.RUMPUN_ID, A.TANGGAL_MULAI, A.NILAI_REKAM_JEJAK
            FROM diklat_struktural A
            INNER JOIN diklat B ON A.DIKLAT_ID = B.DIKLAT_ID
            WHERE 1=1
            AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
            UNION ALL
            SELECT
            A.PEGAWAI_ID, A.DIKLAT_KURSUS_ID ROW_ID, 2 INFO_URUT, A.NAMA DIKLAT_NAMA, A.RUMPUN_ID, A.TANGGAL_MULAI, A.NILAI_REKAM_JEJAK
            FROM diklat_kursus A
            WHERE 1=1
            AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
        ) A
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectrumpundiklatriwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
    {
        $str = "
        SELECT
            A.PEGAWAI_ID, A.RUMPUN_ID
            , SUM(A.NILAI_REKAM_JEJAK) NILAI_REKAM_JEJAK
        FROM
        (
            SELECT
            A.PEGAWAI_ID, 1 INFO_URUT, B.NAMA DIKLAT_NAMA, A.RUMPUN_ID, A.TANGGAL_MULAI, A.NILAI_REKAM_JEJAK
            FROM diklat_struktural A
            INNER JOIN diklat B ON A.DIKLAT_ID = B.DIKLAT_ID
            WHERE 1=1
            AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
            UNION ALL
            SELECT
            A.PEGAWAI_ID, 2 INFO_URUT, A.NAMA DIKLAT_NAMA, A.RUMPUN_ID, A.TANGGAL_MULAI, A.NILAI_REKAM_JEJAK
            FROM diklat_kursus A
            WHERE 1=1
            AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
        ) A
        WHERE A.RUMPUN_ID IS NOT NULL
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." GROUP BY A.PEGAWAI_ID, A.RUMPUN_ID ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectrumpunnilaiakhir($paramsArray=array(),$limit=-1,$from=-1, $rumpumid, $statement='',$order=' ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC')
    {
        $str = "
        SELECT A.*
        FROM
        (
            SELECT
            A.PEGAWAI_ID, A.NIP_BARU, A.SATUAN_KERJA_ID, A.TIPE_PEGAWAI_ID, A.STATUS_PEGAWAI_ID
            , SUBSTRING(COALESCE(JAB_RIW.ESELON_ID,99)::text,1,1) ESELON_GROUP_ID
            , COALESCE(JAB_RIW.ESELON_ID,99) ESELON_ID
            , PANG_RIW.PANGKAT_ID
            , (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
            , PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
            , JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
            FROM pegawai A
            LEFT JOIN
            (
                SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
                FROM pangkat_riwayat A
                LEFT JOIN pangkat B ON A.PANGKAT_ID = B.PANGKAT_ID
            ) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
            LEFT JOIN
            (
                SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
                FROM jabatan_riwayat A
                LEFT JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
            ) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
            WHERE 1=1
        ) A
        LEFT JOIN (select * from talent.p_rumpun_nilai_akhir(".$rumpumid.", 0)) R ON R.PEGAWAI_ID = A.PEGAWAI_ID
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectrumpunpegawainilaiakhir($paramsArray=array(),$limit=-1,$from=-1, $rumpumid, $pegawaiid, $statement='',$order=' ')
    {
        $str = "
        SELECT
        A.*
        FROM talent.p_rumpun_nilai_akhir(".$rumpumid.", ".$pegawaiid.") A
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectnilaiakhirdetil($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
    {
        $str = "
        SELECT
        A.PEGAWAI_ID
        , COALESCE(A.NILAI_INDISIPLINER,0) NILAI_INDISIPLINER
        , COALESCE(A.NILAI_PBJ,0) NILAI_PBJ
        , COALESCE(A.NILAI_PRESTASI,0) NILAI_PRESTASI
        , COALESCE(A.NILAI_FAKTOR_KOREKSI,0) NILAI_FAKTOR_KOREKSI
        FROM
        (
            SELECT A.PEGAWAI_ID, 0 NILAI_KOMPETENSI, 0 NILAI_KINERJA, A.NILAI_RUMPUN
            , COALESCE(A.NILAI_INDISIPLINER,0) NILAI_INDISIPLINER
            , COALESCE(A.NILAI_PBJ,0) NILAI_PBJ
            , COALESCE(A.NILAI_PRESTASI,0) NILAI_PRESTASI
            , COALESCE(A.NILAI_INDISIPLINER,0) + COALESCE(A.NILAI_PBJ,0) + COALESCE(A.NILAI_PRESTASI,0) NILAI_FAKTOR_KOREKSI
            FROM
            (
                SELECT
                P.PEGAWAI_ID, NULL::integer RUMPUN_ID
                , (COALESCE(R1.NILAI,0) + COALESCE(R2.NILAI,0) + COALESCE(R3.NILAI,0)) NILAI_RUMPUN
                , CASE WHEN COALESCE(HK.JUMLAH_HK_BERAT,0) > 0 THEN 25 WHEN COALESCE(HK.JUMLAH_HK_SEDANG,0) > 0 THEN 50 WHEN COALESCE(HK.JUMLAH_HK_RINGAN,0) > 0 THEN 75 ELSE 100 END NILAI_INDISIPLINER
                , CASE WHEN COALESCE(DK.NILAI,0) > 0 THEN 100 ELSE 0 END NILAI_PBJ
                , CASE WHEN COALESCE(PH.NILAI,0) > 0 THEN 100 ELSE 0 END NILAI_PRESTASI
                FROM pegawai P
                LEFT JOIN
                (
                    SELECT A.PEGAWAI_ID
                    , SUM(CASE WHEN A.TINGKAT_HUKUMAN_ID = 3 THEN 1 ELSE 0 END) JUMLAH_HK_BERAT
                    , SUM(CASE WHEN A.TINGKAT_HUKUMAN_ID = 2 THEN 1 ELSE 0 END) JUMLAH_HK_SEDANG
                    , SUM(CASE WHEN A.TINGKAT_HUKUMAN_ID = 1 THEN 1 ELSE 0 END) JUMLAH_HK_RINGAN
                    FROM hukuman A
                    WHERE 1=1
                    AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
                    GROUP BY A.PEGAWAI_ID
                ) HK ON HK.PEGAWAI_ID = P.PEGAWAI_ID
                LEFT JOIN
                (
                    SELECT A.PEGAWAI_ID, COUNT(1) NILAI
                    FROM diklat_kursus A
                    WHERE 1=1 AND A.STATUS_LULUS = '1'
                    AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
                    AND A.TIPE_KURSUS_ID IN (1,2)
                    AND A.REF_JENIS_KURSUS_ID IN (162, 274)
                    GROUP BY A.PEGAWAI_ID
                ) DK ON DK.PEGAWAI_ID = P.PEGAWAI_ID
                LEFT JOIN
                (
                    SELECT A.PEGAWAI_ID, COUNT(1) NILAI
                    FROM penghargaan A
                    WHERE REF_PENGHARGAAN_ID IN
                    (
                        SELECT 
                        REF_PENGHARGAAN_ID
                        FROM sapk.ref_penghargaan A
                        WHERE 1 = 1
                        AND A.INFO_DETIL = '1'
                    )
                    AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
                    GROUP BY A.PEGAWAI_ID
                ) PH ON PH.PEGAWAI_ID = P.PEGAWAI_ID
                LEFT JOIN (SELECT PEGAWAI_ID, NILAI FROM talent.p_rumpun_rekamjejak(1)) R1 ON R1.PEGAWAI_ID = P.PEGAWAI_ID
                LEFT JOIN (SELECT PEGAWAI_ID, NILAI FROM talent.p_rumpun_kualifikasi(1)) R2 ON R2.PEGAWAI_ID = P.PEGAWAI_ID
                LEFT JOIN (SELECT PEGAWAI_ID, NILAI FROM talent.p_rumpun_kompetensi(1)) R3 ON R3.PEGAWAI_ID = P.PEGAWAI_ID
                WHERE 1=1
            ) A
        ) A
        , 
        (
            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 1
        ) RN1
        , 
        (
            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 2
        ) RN2
        , 
        (
            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 3
        ) RN3
        , 
        (
            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 4
        ) RN4
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectakhirkompetensi($pegawaiid)
    {
        $str = "
        SELECT SKOR_KOMPETENSI NILAI
        FROM penilaian_kompetensi
        WHERE PEGAWAI_ID = ".$pegawaiid."
        AND TANGGAL_KOMPETENSI IN
        (
            SELECT MAX(TANGGAL_KOMPETENSI) TANGGAL_KOMPETENSI
            FROM penilaian_kompetensi
            WHERE PEGAWAI_ID = ".$pegawaiid."
        )
        ";
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,-1,-1); 
    }

    function selectakhirkinerja($pegawaiid)
    {
        $str = "
        SELECT PRESTASI_HASIL NILAI
        FROM penilaian_skp
        WHERE PEGAWAI_ID = ".$pegawaiid."
        AND TAHUN IN (2022)
        ";
        /*AND TAHUN IN
        (
            SELECT MAX(TAHUN) TAHUN
            FROM penilaian_skp
            WHERE PEGAWAI_ID = ".$pegawaiid."
        )*/
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,-1,-1); 
    }

    function selectpetapegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
    {
        $str = "
        SELECT
            A.*
        FROM talent.peta_pegawai A
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectpetatalent($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY NILAI_AKHIR DESC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC')
    {
        $str = "
        SELECT
        KOTAK_RUMPUN, R.RUMPUN_ID, R.NILAI_AKHIR, R1.KODE RUMPUN_KODE, R1.NAMA RUMPUN_NAMA, R1.KETERANGAN RUMPUN_KETERANGAN
        , A.*
        FROM
        (
            SELECT
            A.PEGAWAI_ID, A.NIP_BARU, A.SATUAN_KERJA_ID, A.TIPE_PEGAWAI_ID, A.STATUS_PEGAWAI_ID
            , SUBSTRING(COALESCE(JAB_RIW.ESELON_ID,99)::text,1,1) ESELON_GROUP_ID
            , COALESCE(JAB_RIW.ESELON_ID,99) ESELON_ID
            , PANG_RIW.PANGKAT_ID
            , (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
            , PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
            , JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
            FROM pegawai A
            LEFT JOIN
            (
                SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
                FROM pangkat_riwayat A
                LEFT JOIN pangkat B ON A.PANGKAT_ID = B.PANGKAT_ID
            ) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
            LEFT JOIN
            (
                SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
                FROM jabatan_riwayat A
                LEFT JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
            ) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
            WHERE 1=1
        ) A
        INNER JOIN
        (
            SELECT
            CASE
            WHEN A.NILAI_AKHIR >= 90 THEN 9
            WHEN A.NILAI_AKHIR >= 80 AND A.NILAI_AKHIR < 90 THEN 8
            WHEN A.NILAI_AKHIR >= 70 AND A.NILAI_AKHIR < 80 THEN 7
            WHEN A.NILAI_AKHIR >= 60 AND A.NILAI_AKHIR < 70 THEN 6
            WHEN A.NILAI_AKHIR >= 50 AND A.NILAI_AKHIR < 60 THEN 5
            WHEN A.NILAI_AKHIR >= 40 AND A.NILAI_AKHIR < 50 THEN 4
            WHEN A.NILAI_AKHIR >= 30 AND A.NILAI_AKHIR < 40 THEN 3
            WHEN A.NILAI_AKHIR >= 20 AND A.NILAI_AKHIR < 30 THEN 2
            ELSE 1
            END KOTAK_RUMPUN
            , A.*
            FROM
            (
                SELECT
                ROW_NUMBER () OVER (PARTITION BY A.PEGAWAI_ID ORDER BY NILAI_AKHIR DESC) NOMOR
                , A.*
                FROM
                (
                    SELECT A.PEGAWAI_ID, A.RUMPUN_ID
                    , PERSENTASE_KOMPETENSI + PERSENTASE_KINERJA + PERSENTASE_NILAI_RUMPUN + PERSENTASE_NILAI_FAKTOR_KOREKSI NILAI_AKHIR
                    FROM
                    (
                        SELECT
                        A.PEGAWAI_ID, A.RUMPUN_ID
                        , persentase(A.NILAI_KOMPETENSI, RN1.NILAI) PERSENTASE_KOMPETENSI
                        , persentase(A.NILAI_KINERJA, RN2.NILAI) PERSENTASE_KINERJA
                        , persentase(A.NILAI_RUMPUN, RN3.NILAI) PERSENTASE_NILAI_RUMPUN
                        , persentase(A.NILAI_FAKTOR_KOREKSI, RN4.NILAI) PERSENTASE_NILAI_FAKTOR_KOREKSI
                        from talent.peta_pegawai a 
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 1
                        ) RN1
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 2
                        ) RN2
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 3
                        ) RN3
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 4
                        ) RN4
                    ) A
                ) A
            ) A
            WHERE 1=1 AND NOMOR = 1 --AND A.PEGAWAI_ID = 14134
            ORDER BY NILAI_AKHIR DESC
        ) R ON R.PEGAWAI_ID = A.PEGAWAI_ID
        INNER JOIN talent.rumpun R1 ON R.RUMPUN_ID = R1.RUMPUN_ID
        WHERE 1=1
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        $str .= $statement." ".$order;
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }

    function selectpetatalentrumpun($paramsArray=array(),$limit=-1,$from=-1, $statement, $statementdetil='',$order='')
    {
        $str = "
        SELECT
        KOTAK_RUMPUN, COUNT(1) JUMLAH
        FROM
        (
            SELECT
            CASE
            WHEN A.NILAI_AKHIR >= 90 THEN 9
            WHEN A.NILAI_AKHIR >= 80 AND A.NILAI_AKHIR < 90 THEN 8
            WHEN A.NILAI_AKHIR >= 70 AND A.NILAI_AKHIR < 80 THEN 7
            WHEN A.NILAI_AKHIR >= 60 AND A.NILAI_AKHIR < 70 THEN 6
            WHEN A.NILAI_AKHIR >= 50 AND A.NILAI_AKHIR < 60 THEN 5
            WHEN A.NILAI_AKHIR >= 40 AND A.NILAI_AKHIR < 50 THEN 4
            WHEN A.NILAI_AKHIR >= 30 AND A.NILAI_AKHIR < 40 THEN 3
            WHEN A.NILAI_AKHIR >= 20 AND A.NILAI_AKHIR < 30 THEN 2
            ELSE 1
            END KOTAK_RUMPUN
            FROM
            (
                SELECT
                ROW_NUMBER () OVER (PARTITION BY A.PEGAWAI_ID ORDER BY NILAI_AKHIR DESC) NOMOR
                , A.*
                FROM
                (
                    SELECT A.PEGAWAI_ID, A.RUMPUN_ID
                    , PERSENTASE_KOMPETENSI + PERSENTASE_KINERJA + PERSENTASE_NILAI_RUMPUN + PERSENTASE_NILAI_FAKTOR_KOREKSI NILAI_AKHIR
                    FROM
                    (
                        SELECT
                        A.PEGAWAI_ID, A.RUMPUN_ID
                        , persentase(A.NILAI_KOMPETENSI, RN1.NILAI) PERSENTASE_KOMPETENSI
                        , persentase(A.NILAI_KINERJA, RN2.NILAI) PERSENTASE_KINERJA
                        , persentase(A.NILAI_RUMPUN, RN3.NILAI) PERSENTASE_NILAI_RUMPUN
                        , persentase(A.NILAI_FAKTOR_KOREKSI, RN4.NILAI) PERSENTASE_NILAI_FAKTOR_KOREKSI
                        from talent.peta_pegawai a 
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 1
                        ) RN1
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 2
                        ) RN2
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 3
                        ) RN3
                        , 
                        (
                            SELECT NILAI FROM talent.rumpun_nilai WHERE INFOMODE = 'nilai_akhir' AND INFOID = 4
                        ) RN4
                    ) A
                ) A
            ) A
            WHERE 1=1 AND NOMOR = 1
            AND A.PEGAWAI_ID IN
            (
                SELECT A.PEGAWAI_ID
                FROM
                (
                    SELECT
                    A.PEGAWAI_ID, A.NIP_BARU, A.SATUAN_KERJA_ID, A.TIPE_PEGAWAI_ID, A.STATUS_PEGAWAI_ID
                    , SUBSTRING(COALESCE(JAB_RIW.ESELON_ID,99)::text,1,1) ESELON_GROUP_ID
                    , COALESCE(JAB_RIW.ESELON_ID,99) ESELON_ID
                    , PANG_RIW.PANGKAT_ID
                    , (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
                    , PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
                    , JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
                    FROM pegawai A
                    LEFT JOIN
                    (
                        SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
                        FROM pangkat_riwayat A
                        LEFT JOIN pangkat B ON A.PANGKAT_ID = B.PANGKAT_ID
                    ) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
                    LEFT JOIN
                    (
                        SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
                        FROM jabatan_riwayat A
                        LEFT JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
                    ) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
                    WHERE 1=1
                ) A
                WHERE 1=1 ".$statement."
            )
        ) R
        WHERE 1=1
        GROUP BY KOTAK_RUMPUN
        ";
        
        foreach ($paramsArray as $key => $val)
        {
            $str .= " AND $key = '$val' ";
        }
        
        // echo $str;exit;
        $this->query = $str;
        return $this->selectLimit($str,$limit,$from); 
    }
    
  } 
?>