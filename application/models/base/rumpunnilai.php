<? 
include_once(APPPATH.'/models/Entity.php');

class RumpunNilai extends Entity{ 

	var $query;
	var $id;
    function RumpunNilai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO talent.rumpun_nilai
		(
			RUMPUN_ID, PERMEN_ID, INFOMODE, INFOID, NILAI
		) 
		VALUES
		(
			".$this->getField("RUMPUN_ID")."
			, ".$this->getField("PERMEN_ID")."
			, '".$this->getField("INFOMODE")."'
			, ".$this->getField("INFOID")."
			, ".$this->getField("NILAI")."
		)
		"; 	
		$this->id = $this->getField("AGAMA_ID");
		$this->query = $str;
		// echo $str;;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE talent.rumpun_nilai
		SET    
		NILAI= ".$this->getField("NILAI")."
		WHERE INFOMODE= '".$this->getField("INFOMODE")."'
        AND RUMPUN_ID= ".$this->getField("RUMPUN_ID")."
        AND INFOID= ".$this->getField("INFOID")."
        AND PERMEN_ID= ".$this->getField("PERMEN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatependidikanjurusan()
	{
		$str = "
		UPDATE talent.rumpun_nilai AS U
		SET
		NILAI= ".$this->getField("NILAI")."
		FROM
		(
			SELECT *
			FROM talent.rumpun_nilai A
			WHERE INFOMODE = 'pendidikan_jurusan'
			AND INFOID IN
			(
				SELECT PENDIDIKAN_JURUSAN_ID
				FROM PENDIDIKAN_JURUSAN A
				WHERE A.PENDIDIKAN_ID = ".$this->getField("PENDIDIKAN_ID")."
			)
		) AS X
		WHERE U.RUMPUN_ID = X.RUMPUN_ID
		AND U.PERMEN_ID = X.PERMEN_ID
		AND U.INFOMODE = X.INFOMODE
		AND U.INFOID = X.INFOID
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM talent.rumpun_nilai
        WHERE INFOMODE= '".$this->getField("INFOMODE")."'
        AND RUMPUN_ID= ".$this->getField("RUMPUN_ID")."
        AND INFOID= ".$this->getField("INFOID")."
        AND PERMEN_ID= ".$this->getField("PERMEN_ID")."
        ";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function selectparams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT A.*
		FROM talent.rumpun_nilai A
		WHERE 1 = 1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectrumpunpendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='ORDER BY A.PENDIDIKAN_ID')
	{
		$str = "
		SELECT
			A.*
		FROM pendidikan A
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

    function selectrumpunkualifikasi($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='ORDER BY A.PENDIDIKAN_ID DESC, B.NAMA')
	{
		$str = "
		SELECT
			A.PENDIDIKAN_ID, A.NAMA PENDIDIKAN_NAMA, B.PENDIDIKAN_JURUSAN_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA
		FROM pendidikan A
		INNER JOIN pendidikan_jurusan B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
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

    function selecttipekursus($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TIPE_KURSUS_ID ASC')
	{
		$str = "
		SELECT 
			A.*
		FROM tipe_kursus A
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

    function selectparamsrumpunnilai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT
		B.KETERANGAN
		, A.*
		FROM talent.rumpun_nilai A
		INNER JOIN talent.rumpun B ON A.RUMPUN_ID = B.RUMPUN_ID AND A.PERMEN_ID = B.PERMEN_ID
		WHERE 1 = 1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

} 
?>