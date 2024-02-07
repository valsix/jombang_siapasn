<?
function menufield()
{
	$arrField= array(
	  array("id"=>"01", "parent_id"=>"0", "nama"=>"Pegawai", "field"=>"", "child"=>"1")
	  , array("id"=>"0101", "parent_id"=>"01", "nama"=>"NIP Baru", "field"=>"NIP_BARU"
	  	, "kondisifield"=>"A.NIP_BARU", "child"=>"0")
	  , array("id"=>"0102", "parent_id"=>"01", "nama"=>"Nama", "field"=>"NAMA_LENGKAP"
	  	, "kondisifield"=>"A.NAMA_LENGKAP", "child"=>"0")
	  , array("id"=>"0103", "parent_id"=>"01", "nama"=>"Tempat Lahir", "field"=>"TEMPAT_LAHIR"
	  	, "kondisifield"=>"A.TEMPAT_LAHIR", "child"=>"0")
	  , array("id"=>"0104", "parent_id"=>"01", "nama"=>"Tanggal Lahir", "field"=>"TANGGAL_LAHIR"
	  	, "kondisifield"=>"A.TANGGAL_LAHIR", "child"=>"0")
	  , array("id"=>"0105", "parent_id"=>"01", "nama"=>"Umur", "field"=>"", "child"=>"0")
	  , array("id"=>"02", "parent_id"=>"0", "nama"=>"Pangkat", "field"=>"", "child"=>"1")
	  , array("id"=>"0201", "parent_id"=>"02", "nama"=>"Gol. Ruang", "field"=>"PANGKAT_RIWAYAT_KODE"
	  	, "kondisifield"=>"PANG_RIW.KODE", "child"=>"0")
	  , array("id"=>"0202", "parent_id"=>"02", "nama"=>"TMT Pangkat", "field"=>"PANGKAT_RIWAYAT_TMT"
	  	, "kondisifield"=>"PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT", "child"=>"0")
	  , array("id"=>"0203", "parent_id"=>"02", "nama"=>"Nomor SK", "field"=>"PANGKAT_RIWAYAT_NO_SK"
	  	, "kondisifield"=>"PANG_RIW.NO_SK PANGKAT_RIWAYAT_NO_SK", "child"=>"0")
	);
	return $arrField;
}

function kondisiJenisKelamin()
{
	$arrData= array('Laki', 'Perempuan');
	return $arrData;
}

function kondisiStatusNikah()
{
	$arrData= array('Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati');
	return $arrData;
}

function kondisiGolonganDarah()
{
	$arrData= array('A', 'B','AB','O');
	return $arrData;
}

function kondisiCuti()
{
	$arrData= array('Cuti Tahunan', 'Cuti Besar','Cuti Sakit','Cuti Bersalin','Cuti Bersalin','Cuti Bersama', 'CLTN');
	return $arrData;
}

function operatorField()
{
	$arrData= array(
		array("val"=>"1","kondisifield"=>"=", "nama"=>"=")
		, array("val"=>"2","kondisifield"=>"!=", "nama"=>"!=")
		, array("val"=>"3","kondisifield"=>"<", "nama"=>"<")
		, array("val"=>"4","kondisifield"=>"<=", "nama"=>"<=")
		, array("val"=>"5","kondisifield"=>">=", "nama"=>">")
		, array("val"=>"6","kondisifield"=>">=", "nama"=>">=")
		, array("val"=>"7","kondisifield"=>"LIKE", "nama"=>"LIKE")
		, array("val"=>"8","kondisifield"=>"IN", "nama"=>"IN")
		, array("val"=>"9","kondisifield"=>"BETWEEN", "nama"=>"BETWEEN")
		, array("val"=>"10","kondisifield"=>"NOT IN", "nama"=>"NOT IN")
		, array("val"=>"11","kondisifield"=>"NOT BETWEEN", "nama"=>"NOT BETWEEN")
	);
	return $arrData;
}

function optionField()
{
	$arrData= array(
		array("val"=>"1", "nama"=>"NIP Baru", "kondisifield"=>"A.NIP_BARU")
		, array("val"=>"2", "nama"=>"Nama", "kondisifield"=>"A.NAMA_LENGKAP")
		, array("val"=>"3", "nama"=>"Tempat Lahir")
		, array("val"=>"4", "nama"=>"Umur")
		, array("val"=>"5", "nama"=>"Tanggal Lahir", "kondisifield"=>"A.TANGGAL_LAHIR")
		, array("val"=>"6", "nama"=>"Agama", "kondisifield"=>"A.AGAMA_ID")
		, array("val"=>"7", "nama"=>"Tipe Pegawai")
		, array("val"=>"8", "nama"=>"Golongan Ruang")
	);
	return $arrData;

	/*<option value="NIP">NIP</option><option value="NIP Baru">NIP Baru</option><option value="Nama">Nama</option><option value="Gelar Depan">Gelar Depan</option><option value="Gelar Belakang">Gelar Belakang</option><option value="Tempat Lahir">Tempat Lahir</option><option value="Umur">Umur</option><option value="Tanggal Lahir">Tanggal Lahir</option><option value="Bulan Lahir">Bulan Lahir</option><option value="Tahun Lahir">Tahun Lahir</option><option value="Jenis Kelamin">Jenis Kelamin</option><option value="Agama">Agama</option><option value="Status Kawin">Status Kawin</option><option value="Suku Bangsa">Suku Bangsa</option><option value="Gol Darah">Gol Darah</option><option value="Alamat">Alamat</option><option value="Telepon">Telepon</option><option value="Kode Pos">Kode Pos</option><option value="Status Pegawai">Status Pegawai</option><option value="NIK">NIK</option><option value="Karpeg">Karpeg</option><option value="Askes">Askes</option><option value="Taspen">Taspen</option><option value="Tipe Pegawai">Tipe Pegawai</option><option value="Jenis Pegawai">Jenis Pegawai</option><option value="Kedudukan Pegawai">Kedudukan Pegawai</option><option value="TMT Pensiun">TMT Pensiun</option><option value="Golongan Ruang">Golongan Ruang</option><option value="TMT Pangkat">TMT Pangkat</option><option value="Masa Kerja">Masa Kerja</option><option value="Jabatan">Jabatan</option><option value="Eselon">Eselon</option><option value="TMT Eselon">TMT Eselon</option><option value="Tahun Eselon">Tahun Eselon</option><option value="TMT Jabatan">TMT Jabatan</option><option value="Nomor SK Jabatan">Nomor SK Jabatan</option><option value="Tanggal SK Jabatan">Tanggal SK Jabatan</option><option value="Satker">Satker</option><option value="Pendidikan">Pendidikan</option><option value="Jurusan">Jurusan</option><option value="Nama Sekolah">Nama Sekolah</option><option value="Tempat Sekolah">Tempat Sekolah</option><option value="Nomor STTB">Nomor STTB</option><option value="Tanggal STTB">Tanggal STTB</option><option value="Penghargaan">Penghargaan</option><option value="Nomor SK Penghargaan">Nomor SK Penghargaan</option><option value="Tanggal SK Penghargaan">Tanggal SK Penghargaan</option><option value="Tahun Penghargaan">Tahun Penghargaan</option><option value="Nama Diklat Fungsional">Nama Diklat Fungsional</option><option value="Tempat Diklat Fungsional">Tempat Diklat Fungsional</option><option value="Penyelenggara Diklat Fungsional">Penyelenggara Diklat Fungsional</option><option value="Angkatan Diklat Fungsional">Angkatan Diklat Fungsional</option><option value="Tahun Diklat Fungsional">Tahun Diklat Fungsional</option><option value="Nama Diklat Teknis">Nama Diklat Teknis</option><option value="Tempat Diklat Teknis">Tempat Diklat Teknis</option><option value="Penyelenggara Diklat Teknis">Penyelenggara Diklat Teknis</option><option value="Angkatan Diklat Teknis">Angkatan Diklat Teknis</option><option value="Tahun Diklat Teknis">Tahun Diklat Teknis</option><option value="Nama Diklat Teknis">Nama Diklat Teknis</option><option value="Tempat Diklat Teknis">Tempat Diklat Teknis</option><option value="Penyelenggara Diklat Teknis">Penyelenggara Diklat Teknis</option><option value="Angkatan Diklat Teknis">Angkatan Diklat Teknis</option><option value="Tahun Diklat Teknis">Tahun Diklat Teknis</option><option value="No SK Hukuman">No SK Hukuman</option><option value="Tgl SK Hukuman">Tgl SK Hukuman</option><option value="Pejabat Penetap Hukuman">Pejabat Penetap Hukuman</option><option value="TMT SK Hukuman">TMT SK Hukuman</option><option value="Tingkat Hukuman">Tingkat Hukuman</option><option value="Tahun Hukuman">Tahun Hukuman</option>  <option value="Jenis Cuti">Jenis Cuti</option><option value="Lama Cuti">Lama Cuti</option><option value="TMT CPNS">TMT CPNS</option><option value="Tgl. Tugas CPNS">Tgl. Tugas CPNS</option><option value="TMT PNS">TMT PNS</option><option value="Tahun Penilaian Dp3">Tahun Penilaian Dp3</option></select>'*/
}
?>