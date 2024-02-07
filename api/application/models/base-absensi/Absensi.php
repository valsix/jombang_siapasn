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
  
  class Absensi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Absensi()
	{
      $this->Entity(); 
    }
	
	function selectByDataPeriode($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, r.jam_masuk_".$i.", r.jam_pulang_".$i."
			, r.ex_jam_masuk_".$i.", r.ex_jam_pulang_".$i."
			, k.masuk_".$i.", k.pulang_".$i."
			, k.ex_masuk_".$i.", k.ex_pulang_".$i."
			, case when coalesce(nullif(terlambat_".$i.", ''), null) is not null then case when cast(terlambat_".$i." as integer) > 0 then presensi.fromseconds(cast(terlambat_".$i." as integer)) end end terlambat_".$i."
			, case when coalesce(nullif(pulang_cepat_".$i.", ''), null) is not null then case when cast(pulang_cepat_".$i." as integer) > 0 then presensi.fromseconds(cast(pulang_cepat_".$i." as integer)) end end pulang_cepat_".$i."
			";
		}

		$str .= "
		FROM pinfoakhir() A
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON A.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON A.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByDataPeriodeDetil($paramsArray=array(),$limit=-1,$from=-1, $periode, $infotanggaldetil, $statement='', $order='')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, r.jam_masuk_".$i.", r.jam_pulang_".$i."
			, r.ex_jam_masuk_".$i.", r.ex_jam_pulang_".$i."
			, k.masuk_".$i.", k.pulang_".$i."
			, k.ex_masuk_".$i.", k.ex_pulang_".$i."
			, r.terlambat_".$i.", r.pulang_cepat_".$i."
			";
		}

		$str .= "
		FROM pinfoberjalan('".$infotanggaldetil."') A
		LEFT JOIN pinfoperiodekoreksi('".$periode."') K ON A.PEGAWAI_ID = K.PEGAWAI_ID
		LEFT JOIN pinfoperioderekap('".$periode."') R ON A.PEGAWAI_ID = R.PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByDataPegawai($paramsArray=array(),$limit=-1,$from=-1, $infotanggaldetil, $statement='', $order='')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP
		FROM pinfoberjalan('".$infotanggaldetil."') A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByDataKoreksiPegawai($paramsArray=array(),$limit=-1,$from=-1, $periode, $pegawaiid, $statement='', $order='')
	{
		$str = "
		SELECT
		K.PEGAWAI_ID
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, k.masuk_".$i.", k.pulang_".$i."
			, k.ex_masuk_".$i.", k.ex_pulang_".$i."
			";
		}

		$str .= "
		FROM pinfopegawaiperiodekoreksi('".$periode."', ".$pegawaiid.") K
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByDataKoreksi($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		K.PEGAWAI_ID
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, k.masuk_".$i.", k.pulang_".$i."
			, k.ex_masuk_".$i.", k.ex_pulang_".$i."
			";
		}

		$str .= "
		FROM pinfoperiodekoreksi('".$periode."') K
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByDataRekapPegawai($paramsArray=array(),$limit=-1,$from=-1, $periode, $pegawaiid, $statement='', $order='')
	{
		$str = "
		SELECT
		R.PEGAWAI_ID
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, r.jam_masuk_".$i.", r.jam_pulang_".$i."
			, r.ex_jam_masuk_".$i.", r.ex_jam_pulang_".$i."
			, r.terlambat_".$i.", r.pulang_cepat_".$i."
			";
		}

		$str .= "
		FROM pinfopegawaiperioderekap('".$periode."', ".$pegawaiid.") R
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByDataRekap($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		R.PEGAWAI_ID
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, r.jam_masuk_".$i.", r.jam_pulang_".$i."
			, r.ex_jam_masuk_".$i.", r.ex_jam_pulang_".$i."
			, r.terlambat_".$i.", r.pulang_cepat_".$i."
			";
		}

		$str .= "
		FROM pinfoperioderekap('".$periode."') R
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

  } 
?>