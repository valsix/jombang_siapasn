<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");
include_once("functions/encrypt.func.php");

class globallinkpegawai
{
	function inforiwayatdetil($vparam)
	{
		// print_r($vparam);exit;
		$infomode= $vparam["mode"];

		$vriwayat= "";
		if($infomode == "jabatanriwayat")
		{
			$jenisjabatanid= $vparam["reqJenisJabatanId"];

			if($jenisjabatanid == "1")
				$vriwayat= "pegawai_add_jabatan_struktural_data";
			else if($jenisjabatanid == "2")
				$vriwayat= "pegawai_add_jabatan_fungsional_data";
			else if($jenisjabatanid == "3")
				$vriwayat= "pegawai_add_jabatan_tertentu_data";
		}
		else if($infomode == "kinerja")
		{
			$tahun= $vparam["reqTahun"];

			if($tahun == "2022")
				$vriwayat= "pegawai_add_skp22_data";
			else if($tahun == "2023")
				$vriwayat= "pegawai_add_skp23_data";
		}
		else if($infomode == "pendidikanriwayat")
		{
			$vriwayat= "pegawai_add_pendidikan_data";
		}
		else if($infomode == "diklatkursus")
		{
			$vriwayat= "pegawai_add_diklat_kursus_data";
		}
		else if($infomode == "diklatstruktural")
		{
			$vriwayat= "pegawai_add_diklat_struktural_data";
		}
		else if($infomode == "hukuman")
		{
			$vriwayat= "pegawai_add_hukuman_data";
		}

		return $vriwayat;
	}
}