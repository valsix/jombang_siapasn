<? 
/* *******************************************************************************************************

***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');
  
  class StrukturOrg extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function StrukturOrg()
	{
      $this->Entity(); 
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParamsSatkerId($statement="", $statementnode="", $order='')
	{
		$str = "	
		SELECT
			satuan_kerja.satuan_kerja_id AS satker_id,
			satuan_kerja.nama AS nama_satker,
			satuan_kerja.nama_jabatan AS nama_jabatan, 
			satuan_kerja.nama_singkat AS nama_singkat_satker,
			p.nip_baru AS nip, 
			p.nama AS nama, 
			p.tempat_lahir AS lahir, 
			TO_CHAR(p.tanggal_lahir, 'dd-mm-yyyy') AS tl, 
			getpangkatkode(p.pangkat_id) AS pang, 
			TO_CHAR(p.tmt_pangkat, 'dd-mm-yyyy')  AS tmtpang, 
			TO_CHAR(p.tmt_jabatan, 'dd-mm-yyyy')  AS tmtjab
		FROM
			satuan_kerja
			LEFT JOIN
					(SELECT
					pegawai.nip_baru,
					(CASE WHEN COALESCE(NULLIF(pegawai.gelar_depan,'') , NULL ) IS NULL THEN '' ELSE pegawai.gelar_depan || ' ' END) || pegawai.nama || 
					(CASE WHEN COALESCE(NULLIF(pegawai.gelar_belakang,'') , NULL ) IS NULL THEN '' ELSE '' || pegawai.gelar_belakang END) nama,
					pegawai.tempat_lahir, 
					pegawai.tanggal_lahir, 
					pangkat_riwayat.pangkat_id, 
					pangkat_riwayat.tmt_pangkat, 
					jabatan_riwayat.tmt_jabatan, 
					jabatan_riwayat.satker_id
				FROM
					pegawai
					LEFT JOIN
					pangkat_riwayat
					ON 
						pegawai.pangkat_riwayat_id = pangkat_riwayat.pangkat_riwayat_id
					LEFT JOIN
					jabatan_riwayat
					ON 
						pegawai.jabatan_riwayat_id = jabatan_riwayat.jabatan_riwayat_id
				WHERE
					pegawai.status_pegawai_id = 2 AND
					pegawai.tipe_pegawai_id = '11') AS P
			ON 
				satuan_kerja.satuan_kerja_id = P.satker_id
		WHERE
			satuan_kerja.masa_berlaku_akhir IS NULL AND
			satuan_kerja.satuan_kerja_induk_upt = ".$statement."
		ORDER BY satuan_kerja.eselon_id ASC
		";
		
		//$str .= " ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }
	


 
// ;
// ;
// ;
	
  } 
?>