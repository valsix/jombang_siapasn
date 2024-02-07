<?
function kondisifield()
{
	$arrField= array(
	  array(
	  	"kuadran"=>1
	  	, "minimalkinerja"=>0, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>72, "maksimaltipekinerja"=>"<"
	  	, "minimalkompetensi"=>0, "minimaltipekompetensi"=>">="
	  	, "maksimalkompetensi"=>70, "maksimaltipekompetensi"=>"<"
	  	, "keterangan"=> "Pegawai bermasalah (kinerja dibawah ekspektasi, kemungkinan salah penempatan)"
	  )
	  ,
	  array(
	  	"kuadran"=>2
	  	, "minimalkinerja"=>72, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>80, "maksimaltipekinerja"=>"<"
	  	, "minimalkompetensi"=>0, "minimaltipekompetensi"=>">="
	  	, "maksimalkompetensi"=>70, "maksimaltipekompetensi"=>"<"
	  	, "keterangan"=> "Pekerja Keras Masa Depan (kinerja konsisten, kurang berpotensi posisi lebih tinggi, pekerjaan yang berbeda)"
	  )
	  ,
	  array(
	  	"kuadran"=>3
	  	, "minimalkinerja"=>0, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>72, "maksimaltipekinerja"=>"<"
	  	, "minimalkompetensi"=>70, "minimaltipekompetensi"=>">="
	  	, "maksimalkompetensi"=>90, "maksimaltipekompetensi"=>"<="
	  	, "keterangan"=> "Pegawai Kurang Konsisten (kinerja dibawah ekspektasi, menguasai pekerjaan, kemungkinan bisa beradaptasi di situasi yang berbeda)"
	  )
	  ,
	  array(
	  	"kuadran"=>4
	  	, "minimalkinerja"=>80, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>100, "maksimaltipekinerja"=>"<="
	  	, "minimalkompetensi"=>0, "minimaltipekompetensi"=>">="
	  	, "maksimalkompetensi"=>70, "maksimaltipekompetensi"=>"<"
	  	, "keterangan"=> "Pekerja Keras (kinerja diatas rata-rara, kurang berpotensi posisi lebih tinggi dan pekerjaan yang berbeda)"
	  )
	  ,
	  array(
	  	"kuadran"=>5
	  	, "minimalkinerja"=>72, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>80, "maksimaltipekinerja"=>"<"
	  	, "minimalkompetensi"=>70, "minimaltipekompetensi"=>">="
	  	, "maksimalkompetensi"=>90, "maksimaltipekompetensi"=>"<="
	  	, "keterangan"=> "Pegawai Berpengalaman (kinerja konsisten, menguasai pekerjaan, dapat beradaptasi dengan perkerjaan yang berbeda)"
	  )
	  ,
	  array(
	  	"kuadran"=>6
	  	, "minimalkinerja"=>0, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>72, "maksimaltipekinerja"=>"<"
	  	, "minimalkompetensi"=>90, "minimaltipekompetensi"=>">"
	  	, "maksimalkompetensi"=>100, "maksimaltipekompetensi"=>"<="
	  	, "keterangan"=> "Pegawai Berpotensi, Kinerja Tidak Konsisten (Menguasai pekerjaan sangat baik, hasil kinerja tidak konsisten)"
	  )
	  ,
	  array(
	  	"kuadran"=>7
	  	, "minimalkinerja"=>80, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>100, "maksimaltipekinerja"=>"<="
	  	, "minimalkompetensi"=>70, "minimaltipekompetensi"=>">="
	  	, "maksimalkompetensi"=>90, "maksimaltipekompetensi"=>"<="
	  	, "keterangan"=> "Pegawai Profesional (Kinerja diatas rata-rata, menguasai pekerjaan dengan sangat baik, berpotensi untuk pekerjaan yang berbeda)"
	  )
	  ,
	  array(
	  	"kuadran"=>8
	  	, "minimalkinerja"=>72, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>80, "maksimaltipekinerja"=>"<"
	  	, "minimalkompetensi"=>90, "minimaltipekompetensi"=>">"
	  	, "maksimalkompetensi"=>100, "maksimaltipekompetensi"=>"<="
	  	, "keterangan"=> "Pimpinan Masa Depan (Kompetensi diatas standar, menguasai pekerjaan, berpotensi ditempatkan di pekerjaan lain dengan kompetensi sama)"
	  )
	  ,
	  array(
	  	"kuadran"=>9
	  	, "minimalkinerja"=>80, "minimaltipekinerja"=>">="
	  	, "maksimalkinerja"=>100, "maksimaltipekinerja"=>"<="
	  	, "minimalkompetensi"=>90, "minimaltipekompetensi"=>">"
	  	, "maksimalkompetensi"=>100, "maksimaltipekompetensi"=>"<="
	  	, "keterangan"=> "Pimpinan Tinggi Masa Depan (Berpotensi untuk dipromosikan ke level manajemen puncak)"
	  )
	);
	// print_r($arrField);exit;
	return $arrField;
}
?>