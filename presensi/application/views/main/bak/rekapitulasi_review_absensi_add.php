<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('main/AbsensiRekap');
$this->load->model('main/AbsensiKoreksi');

$set = new AbsensiRekap();
$set_count = new AbsensiRekap();

$absensi_koreksi = new AbsensiKoreksi();

$reqPeriode = $this->input->get("reqPeriode");
$reqPegawaiId = $this->input->get("reqPegawaiId");

if($reqPeriode == "")
{
	$reqPeriode = date("mY");
	$reqBulan = date("m");
	$reqTahun = date("Y");
}
else
{
	$reqBulan = substr($reqPeriode, 0, 2);
	$reqTahun = substr($reqPeriode, 2, 4);
}

function setpartisi($reqPeriode)
{
	if(!empty($reqPeriode))
	{
		$tahun= getTahunPeriode($reqPeriode);
		for($i= 1; $i <= 12; $i++)
		{
			$reqPeriode= generateZeroDate($i,2).$tahun;
			// echo $reqPeriode."<br/>";

			$setdetil= new AbsensiRekap();
			$setdetil->setPartisiTablePeriode($reqPeriode);
			// echo $set->query;exit();
		}
		// exit();
	}
}
setpartisi($reqPeriode);

$set->selectByParamsReviewPresensi(array(), -1,-1, "", $reqPegawaiId, $reqPeriode);
// echo $set->query;exit;

$jumlah = $set_count->getCountByParamsInformasiRekapKehadiranTanggal(array(), "", $reqPegawaiId, $reqPeriode);
// echo $jumlah;exit;

$jumlah_hari =  cal_days_in_month(CAL_GREGORIAN,(int)$reqBulan,$reqTahun);
//echo $jumlah_hari;exit;

$hari_libur = $absensi_koreksi->getHariLibur($reqBulan, $reqTahun, $this->KODE_CABANG);
// echo $hari_libur;exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>">
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
<link href="css/admin.css" rel="stylesheet" type="text/css">

<style type="text/css" media="screen">
    @import "lib/media/css/site_jui.css";
    @import "lib/media/css/demo_table_jui.css";
    @import "lib/media/css/themes/base/jquery-ui.css";
</style>

<!-- FLEX MENU -->
<link rel="stylesheet" type="text/css" href="lib/Flex-Level-Drop-Down-Menu-V2/flexdropdown.css" />
<script type="text/javascript" src="lib/Flex-Level-Drop-Down-Menu-V2/jquery.min.js"></script>
<script type="text/javascript" src="lib/Flex-Level-Drop-Down-Menu-V2/flexdropdown.js">
/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>


<script type="text/javascript" src="lib/media/js/complete.js"></script>

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/demo.css">
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	

<script type="text/javascript" charset="utf-8">
	var oTable;
    $(document).ready( function () {
		<?							
    	if($reqPeriode == "")
		{
			?>
			document.location.href='app/loadUrl/main/rekapitulasi_review_absensi_add/?reqPegawaiId=<?=$reqPegawaiId?>&reqPeriode=<?=date("m").date("Y")?>&reqDepartemen=<?=$reqDepartemen?>';
			<?
		}
		?>
		
		$("#reqBulan").change(function() {
			var tahun = $("#reqTahun").val();
			
			document.location.href='app/loadUrl/main/rekapitulasi_review_absensi_add/?reqPegawaiId=<?=$reqPegawaiId?>&reqPeriode='+this.value+tahun+'&reqDepartemen=<?=$reqDepartemen?>';
		});
		
		$("#reqTahun").change(function() {
			var bulan = $("#reqBulan").val();
			
			document.location.href='app/loadUrl/main/rekapitulasi_review_absensi_add/?reqPegawaiId=<?=$reqPegawaiId?>&reqPeriode='+bulan+this.value+'&reqDepartemen=<?=$reqDepartemen?>';
		});
		
		$("#btnCetak").click(function() {
			window.open('app/loadUrl/main/rekapitulasi_review_absensi_add_excel/?reqPegawaiId=<?=$reqPegawaiId?>&reqPeriode='+$("#reqBulan").val()+$("#reqTahun").val()+'&reqDepartemen=<?=$reqDepartemen?>', '_blank', "");
			
			/*
			document.location.href='app/loadUrl/app/review_presensi/?reqPeriode='+$("#reqBulan").val()+$("#reqTahun").val();
			'_blank';
			*/
		});
			  
		});
</script>

    <!--RIGHT CLICK EVENT-->		
    <style>

	.vmenu{
		border:1px solid #aaa;
		position:absolute;

		background:#fff;
		display:none;font-size:0.75em;
	}
	.first_li{}
	.first_li span{
		width:100px;
		display:block;
		padding:5px 10px;
		cursor:pointer
	}
	.inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	.sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	.fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}
	</style>
    
    <link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> 
    <link href="css/begron.css" rel="stylesheet" type="text/css">  
    <link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/dropdowntabs.js"></script>
	
</head>
<body>
<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman"><span>Rekapitulasi Presensi Jam Kerja</span></div>
    <div id="parameter-tambahan" class="parameter-tabel">
        Bulan :
            <select name="reqBulan" id="reqBulan">
            <?
            for($i=1; $i<=12; $i++)
            {
                $tempNama=getNameMonth($i);
                $temp=generateZeroDate($i,2);
            ?>
                <option value="<?=$temp?>" <? if($temp == $reqBulan) echo 'selected'?>><?=$tempNama?></option>
            <?
            }
            ?>
            </select>
        
        Tahun
            <select name="reqTahun" id="reqTahun">
                <? 
                for($i=date("Y")-2; $i < date("Y")+2; $i++)
                {
                ?>
                <option value="<?=$i?>" <? if($i == $reqTahun) echo 'selected'?>><?=$i?></option>
                <?
                }
                ?>
            </select>
	        <!-- <label>
	        	<button style="height:23px" id="btnCetak"><label style="color:#333">Cetak Excel</label></button>
	        </label> -->
    </div>
    
    <div class="menu-aksi"><a data-flexmenu="flexmenu1"><img src="images/tambah.png"></a></div>
   
	<div class="review-presensi-wrapper">
        <table class="review-presensi">
            <thead>
                <tr>
                  <th rowspan="2" style="text-align:center">TGL</th>
                  <th rowspan="2" colspan="2" style="text-align:center">HARI</th>
                  <th rowspan="2" style="text-align:center">MASUK</th>
                  <th rowspan="2" style="text-align:center">TERLAMBAT</th>
                  <th rowspan="2" style="text-align:center">PULANG</th>
                  <th rowspan="2" style="text-align:center">PULANG AWAL</th>
                  <!-- <th rowspan="2" style="text-align:center">KERJA LEBIH</th>
                  <th colspan="2" style="text-align:center">LOKASI</th> -->
                  <th rowspan="2" style="text-align:center">KETERANGAN</th>
              </tr>
              <!-- <tr>
                  	<th>MASUK</th>
                  	<th>PULANG</th>
              </tr> -->
            </thead>
            
          <tbody>
          	<?
			$hariKerja = 0;
			$hariHadir = 0;
			$hariTidakHadir = 0;
			$hariIjin = 0;
			$hariCuti = 0;
			$hariSppd = 0;
			$hariDinasLuar = 0;
			$hariSakit = 0;
			$hariSakitSurat = 0;
			$hariLupaChecklog = 0;
			$hariPulangCepat = 0;
			$terlambatTidakDipotong = 0;
			$jumlahTerlambat = 0;
			
			$terlambat_non_shift = 0;
			$terlambat_shift = 0;
			
			if($jumlah == "0")
			{
				for($i=1; $i<=$jumlah_hari; $i++)
				{
					
					$date = generateZero($i,2).'-'.$reqBulan.'-'.$reqTahun;
					$nama_hari = date('l', strtotime($date));
					
					if($nama_hari == "Saturday")
						$classHari = "sabtu";
					elseif($nama_hari == "Sunday")
						$classHari = "minggu";
					else
					{
						$arrHari = explode(',', $hari_libur);
					
						for($j=1; $j<=count($arrHari); $j++)
						{
							if($i == $arrHari[$j])
							{
								$classHari = "libur";
								break;
							}
							else
								$classHari = "";
						}
					}
					
			?>
            	<tr>
                	<th><?=$i?></th>
                	<td><span class="<?=$classHari?>"><?=getNamaHariIndo($nama_hari)?></span></td>
                	<td style="border-left:hidden"></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                	<td></td>
                </tr>
			<?
				}
			}
			else
			{
				while($set->nextRow())
				{
					$masuk = explode(" ", $set->getField("MASUK"));
					
					if(trim($set->getField("NAMA_HARI"), " ") == "SATURDAY")
						$classHari = "sabtu";
					elseif(trim($set->getField("NAMA_HARI"), " ") == "SUNDAY")
						$classHari = "minggu";
					elseif($set->getField("MASUK") == "libur")
						$classHari = "libur";
					else
						$classHari = $masuk[0];
					
					if(trim($set->getField("NAMA_HARI"), " ") == "SATURDAY" || trim($set->getField("NAMA_HARI"), " ") == "SUNDAY" || $set->getField("MASUK") == "libur" )
						$classLibur = "libur";
					else
						$classLibur = "";
						
					if($set->getField("KETERANGAN") == "Alpha" || trim($set->getField("MASUK"), " ") == "cuti")
						$classMerah = "merah";
					else
						$classMerah = "";
						
					$absensiMasuk = $set->getField("MASUK");
					$absensiKeterangan = $set->getField("KETERANGAN");
					
					if($absensiMasuk == "libur" || $absensiMasuk == "" || $set->getField("JAM_SHIFT") == "L")
					{}
					else
						$hariKerja++;
						
					if(stristr($absensiMasuk, "efektif"))
						$hariHadir++;
					
					if($absensiMasuk == "alpha")
						$hariTidakHadir++;
						
					if($absensiMasuk == "ijin")
					{
						if(stristr(strtoupper($absensiKeterangan), "SAKIT"))
						{
							if(stristr(strtoupper($absensiKeterangan), "SAKIT (SURAT)"))
								$hariSakitSurat++;
							else
								$hariSakit++;
						}
						else

							$hariIjin++;	
					}
					
					if($absensiMasuk == "cuti")
						$hariCuti++;
						
					if($absensiMasuk == "dinas")
					{
						if(stristr(strtoupper($absensiKeterangan), "DINAS LUAR KOTA"))
							$hariDinasLuar++;
						else
							$hariSppd++;	
					}
					
					if(stristr($absensiMasuk, "efektif-"))
						$hariLupaChecklog++;
					
					if($set->getField("PULANG_CEPAT") == "" || $set->getField("PULANG_CEPAT") == "0")
					{}
					else
						$hariPulangCepat++;
					
					if(strtoupper($absensiMasuk) == "DINAS" OR strtoupper($absensiKeterangan) == "LUPA CHECK LOG")
					{}
					else
					{					
						if($set->getField("TERLAMBAT") == "" || $set->getField("TERLAMBAT") == "0")
							$terlambatTidakDipotong  += $set->getField("TIDAK_DIPOTONG");	
					}
					
					if(($set->getField("JAM_MASUK") == "" && $set->getField("JAM_PULANG") == "") || strtoupper($absensiMasuk) == "DINAS")
					{}
					else
					{
						$jumlahTerlambat += $set->getField("JUMLAH_TERLAMBAT");	
					}
					
				?>
					<tr class="<?=$classLibur?>">
						<th><?=$set->getField("HARI")?></th>
						<td><span class="<?=$classHari?>"><?=getNamaHariIndo(ucwords(strtolower($set->getField("NAMA_HARI"))))?></span></td>
						<td style="border-left:hidden"><?=$set->getField("JAM_SHIFT")?></td>
						<td><span class="<?=$set->getField("MASUK")?>"><?=$set->getField("JAM_MASUK")?></span></td>
						<!--<td><span class="tdk-dipotong"><?=$set->getField("TIDAK_DIPOTONG")?></span></td>-->
						<td>
                        <?
						if($set->getField("MASUK") == "efektif-LA-in-out" || $set->getField("MASUK") == "efektif-LA-in" || $set->getField("MASUK") == "efektif-LA-out")
						{}
						else
						{
                        ?>
                        	<span <? if($set->getField("TERLAMBAT")== "" || $set->getField("TERLAMBAT") == "0") {} else { ?> class="dipotong" <? } ?> >
								<? 
								// $terlambat_non_shift = $set->getField("TIDAK_DIPOTONG") + $set->getField("TERLAMBAT");
								$terlambat_non_shift = $set->getField("TERLAMBAT");
								$terlambat_shift = $set->getField("TERLAMBAT");
								
								if(floor($terlambat_non_shift/60) > 10)
									$telat_non_shift_jam = sprintf("%02d jam", floor($terlambat_non_shift/60));
								else
									$telat_non_shift_jam = sprintf("%01d jam", floor($terlambat_non_shift/60));
									
								if(floor($terlambat_shift/60) > 10)	
									$telat_shift_jam = sprintf("%02d jam", floor($terlambat_shift/60));
								else
									$telat_shift_jam = sprintf("%01d jam", floor($terlambat_shift/60));
								
								if($terlambat_non_shift%60 > 10)
									$telat_non_shift_menit = sprintf("%02d mnt", $terlambat_non_shift%60);
								else
									$telat_non_shift_menit = sprintf("%01d mnt", $terlambat_non_shift%60);
									
								if($terlambat_shift%60 > 10)
									$telat_shift_menit = sprintf("%02d mnt", $terlambat_shift%60);
								else
									$telat_shift_menit = sprintf("%01d mnt", $terlambat_shift%60);

								$telat_shift_menit = sprintf("%01d Detik", $terlambat_shift);
		
								
									if($this->KELOMPOK == "N")
									{
										if($set->getField("JAM_MASUK") == "" && $set->getField("JAM_PULANG") != "")
										{
											?>
											<span class="dipotong">
											<? echo "8 Jam";?>
											</span>
											<?
										}
										else
										{
											if ($set->getField("TERLAMBAT") == "") 
											{
												/* COMMENT NVN KARENA TIDAK ADA TOLERANSI TERLAMBAT 15 MENIT */
												/*	
												if($set->getField("TIDAK_DIPOTONG") == '0 mnt')
												{}
												else
													echo $set->getField("TIDAK_DIPOTONG");
												*/
											}
											else
											{
												if(floor($terlambat_non_shift/60) == 0 && $terlambat_non_shift%60 == 0)
												{}
												else
													if(floor($terlambat_non_shift/60) == 0)
														echo $telat_non_shift_menit;
													else
														echo $telat_non_shift_jam.' '.$telat_non_shift_menit;
												//echo $set->getField("TIDAK_DIPOTONG") + $set->getField("TERLAMBAT");
											}
										}
									} 
									else 
									{ 
										if(floor($terlambat_shift/60) == 0 && $terlambat_shift%60 == 0)
										{}
										else
										{
											if(floor($terlambat_shift/60) == 0)
												echo $telat_shift_menit;
											else
												echo $telat_shift_jam.' '.$telat_shift_menit;
										}
										// echo $telat_shift_menit;

										// echo "Asd";
										//echo $set->getField("TERLAMBAT"); 
									} 
								?>  
								<? 
								/*
								if($set->getField("TERLAMBAT") == "") {} else { echo " mnt"; } 
								*/
								?> 
							</span>
                        <?
						}
                        ?>
						</td>
						<td><span class="tepat"><?=$set->getField("JAM_PULANG")?> <?=$set->getField("AUTH_PULANG")?></span></td>
						<td>
							<?
							if($set->getField("MASUK") == "efektif-LA-in-out" || $set->getField("MASUK") == "efektif-LA-in" || $set->getField("MASUK") == "efektif-LA-out")
							{}
							else
							{
								if($set->getField("JAM_MASUK") != "" && $set->getField("JAM_PULANG") == "" && stristr($set->getField("MASUK"), "efektif"))
								{
									if($this->KELOMPOK == "N")
									{
										$pulang_cepat_non_shift = 480-$terlambat_non_shift;
										?>
										<span class="dipotong">
										<?
										echo sprintf("%02d jam", floor($pulang_cepat_non_shift/60)).' '.sprintf("%02d mnt", $pulang_cepat_non_shift%60);
										?>
										</span>
										<?
									}
									else
									{
										
									}
								}
								else
								{
								?>
									<?=$set->getField("PULANG_CEPAT")?> <? if($set->getField("PULANG_CEPAT") == "") {} else { echo " mnt"; } ?>
								<?
								}
							}
							?>
						</td>
						<!-- <td>
							<?
							if($set->getField("JAM_MASUK_LEMBUR") == "")
							{}
							else
							{
							?>
							<?=$set->getField("JAM_MASUK_LEMBUR")?> - <?=$set->getField("JAM_PULANG_LEMBUR")?>
							<?
							}
							?>
						</td>
						<td><?=strtolower($set->getField("LOKASI_AUTH_MASUK"))?></td>
						<td><?=strtolower($set->getField("LOKASI_AUTH_PULANG"))?></td> -->
						<td>
							<span class="<?=$classMerah?>">
								<? if($set->getField("KETERANGAN") == "OFF") {} else { ?><?=$set->getField("KETERANGAN")?> <? } ?> <? if ($set->getField("JAM_MASUK_LEMBUR") != "" || $set->getField("JAM_PULANG_LEMBUR") != "") { echo $set->getField("KETERANGAN_LEMBUR"); }?>
							</span>
						</td>
					</tr>
					<?
				}

			if(floor($terlambatTidakDipotong/60) > 10)
				$telat_tidak_dipotong_jam = sprintf("%02d jam", floor($terlambatTidakDipotong/60));
			else
				$telat_tidak_dipotong_jam = sprintf("%01d jam", floor($terlambatTidakDipotong/60));
				
			if($terlambat_non_shift%60 > 10)
				$telat_tidak_dipotong_menit = sprintf("%02d mnt", $terlambatTidakDipotong%60);
			else
				$telat_tidak_dipotong_menit = sprintf("%01d mnt", $terlambatTidakDipotong%60);
			
			}
            ?>
          </tbody>
            <tfoot>
                <tr>
                  <th colspan="13">
					<?
					/*
					?>
					<table class="keterangan-footer">
                        <tr>
                            <td><span>Hari Kerja</span><span>: <?=$hariKerja?> hari</span></td>
                            <td><span>Ijin</span><span>: <?=$hariIjin?> hari</span></td>
                            <td><span>Dinas Luar</span><span>: <?=$hariDinasLuar?> hari</span></td>
                            <td><span>Tidak Check Lock</span><span>: <?=$hariLupaChecklog?> hari</span></td>
                            <td><span>Pulang Awal</span><span>: (<?=$hariPulangCepat?> hari)</span></td>
                        </tr>
                        <tr>
                            <td><span>Hadir</span><span>: <?=$hariHadir?> hari</span></td>
                            <td><span>Cuti</span><span>: <?=$hariCuti?> hari</span></td>
                            <td><span>Sakit</span><span>: <?=$hariSakit?> hari</span></td>
                            <td><span>Terlambat Tdk dipotong</span><span>: 
								<? 
								if(floor($terlambatTidakDipotong/60) == 0 && $terlambatTidakDipotong%60 == 0)
								{
									echo '-';
								}
								else
								{
									if(floor($terlambatTidakDipotong/60) == 0)
										echo $telat_tidak_dipotong_menit;
									else
										echo $telat_tidak_dipotong_jam.' '.$telat_tidak_dipotong_menit;
								}
								?> <!--3 jam 24 mnt (15 hari)--></span></td>
                          	<td><span>Lembur H. Kerja</span><span>: 0 jam / 0 hari</span></td>
                        </tr>
                        <tr>
                            <td><span>Tidak Hadir</span><span>: <?=$hariTidakHadir?> hari</span></td>
                            <td><span>SPPD</span><span>: <?=$hariSppd?> hari</span></td>
                            <td><span>Sakit (Surat)</span><span>: <?=$hariSakitSurat?> hari</span></td>
                            <td><span>Terlambat dipotong</span><span>: <?=$jumlahTerlambat?> Jam</span></td>
                            <td><span>Lembur H. Libur</span><span>: 0 jam / 0</span></td>
                        </tr> 
                    </table>
					<?
					*/
					?>
                  </th>
                </tr>
            </tfoot>
        </table>
        
        

        <form id="ff" method="post" novalidate enctype="multipart/form-data" style="display:none;">
        <table class="table">
        <thead>
        	<tr>
                <th colspan="3">Jenis Permohonan Cuti</th>
            </tr>
            <tr>
            	<td>Jenis Cuti</td>
                <td>:</td>
                <td>
                	<input class="easyui-combobox" name="reqIjinKoreksiId" id="reqIjinKoreksiId" style="width:450px;" data-options="
                          url: 'ijin_koreksi_json/combobox',
                          method: 'get',
                          valueField:'id', 
                          textField:'nama',
                          onSelect: function(rec){
                              $('#reqMaksimalHari').val(rec.maksimal);
                              $('#reqMinimalHari').val(rec.minimal);
                              $('#reqTanggalAwal').datebox('setValue', '');					
                              $('#reqTanggalAkhir').datebox('setValue', '');					
                              $('#reqJumlahHari').val('');
                              $('input[type=checkbox]').attr('checked',false);
                              if(rec.kode == 'CT')
                              {                      
                                $('#theadKeteranganCutiTahunan').show();            
                                $('#theadKeteranganCutiBesar').hide();
                                cekCuti(0);
                              }
                              else if(rec.kode == 'CB')
                              {
                                $('#theadKeteranganCutiTahunan').hide();            
                                $('#theadKeteranganCutiBesar').show();
                                cekCuti(1);                            
                              }
                              else
                              {
                                $('#theadKeteranganCutiTahunan').hide();            
                                $('#theadKeteranganCutiBesar').hide();                                    
                              }
                          },
                        editable:false
                      " value="<?=$reqIjinKoreksiId?>">
                </td>
            </tr>
        </thead>
        </table>
        <table class="table">
        <thead id="theadKeteranganCutiTahunan" style="display:none">
            <tr>
                <th colspan="3">Informasi Cuti <?=$keterangan_cuti?> <?=$reqTahun?></th>
            </tr>
            <tr>
                <td>Jatah Cuti <?=$keterangan_cuti?></td>
                <td>:</td>
                <td><span id="reqJatahCutiCT"></span>  hari</td>
            </tr>
            <tr>
                <td>Cuti Diambil</td>
                <td>:</td>
                <td><span id="reqTotalCutiCT"></span>  hari</td>
            </tr>
            <tr>
                <td>Sisa Cuti</td>
                <td>:</td>
                <td><span id="reqSisaCutiCT"></span> hari</td>
            </tr>
            <!--<tr>
                <td>Nomor</td>
                <td>:</td>
                <td>
                    <input type="text" name="reqNomor" class="easyui-validatebox" style="width:250px;" value="<?=$reqNomor?>" />
                </td>
            </tr>-->
        </thead>
        <thead id="theadKeteranganCutiBesar" style="display:none">
            <tr>
                <th colspan="3">Informasi Cuti Besar</th>
            </tr>
            <tr>
                <td>Jatah Cuti Besar</td>
                <td>:</td>
                <td><span id="reqJatahCutiCB"></span>  hari</td>
            </tr>
            <tr>
                <td>Cuti Diambil</td>
                <td>:</td>
                <td><span id="reqTotalCutiCB"></span>  hari</td>
            </tr>
            <tr>
                <td>Sisa Cuti</td>
                <td>:</td>
                <td><span id="reqSisaCutiCB"></span> hari</td>
            </tr>
            <tr>
                <td>Masa Berlaku</td>
                <td>:</td>
                <td><?=$masa_berlaku?></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3">Permohonan Cuti</th>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>
                  <input class="easyui-datebox" name="reqTanggal" value="<?=$reqTanggal?>">
                </td>
            </tr>      
            <tr>
                <td>Tanggal Awal</td>
                <td>:</td>
                <td>
                    <input class="easyui-datebox" id="reqTanggalAwal" name="reqTanggalAwal" value="<?=$reqTanggalAwal?>" required>
                </td>
            </tr>  
            <tr>
                <td>Tanggal Akhir</td>
                <td>:</td>
                <td>
                    <input class="easyui-datebox" id="reqTanggalAkhir" name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" required>
                </td>
            </tr>
            <tr>
                <td>Lama Cuti</td>
                <td>:</td>
                <td>
                   <input type="text" id="reqJumlahHari" name="reqJumlahHari" class="easyui-validatebox" style="width:50px;" readonly value="<?=$reqJumlahHari?>" />
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>
                   <input type="text" id="reqKeterangan" name="reqKeterangan" class="easyui-validatebox" style="width:350px;" value="<?=$reqKeterangan?>" />
                </td>
            </tr>
            <tr>
                <td>Alamat Cuti</td>
                <td>:</td>
                <td>
                    <textarea name="reqAlamat" style="height:70px; width:250px;"><?=$reqAlamat?></textarea>
                </td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>:</td>
                <td>
                   <input type="text" id="reqTelepon" name="reqTelepon" class="easyui-validatebox" style="width:250px;" value="<?=$reqTelepon?>" />
                </td>
            </tr>
            <tr <?=$this->APPROVAL1_DISPLAY?>>
                <td>Approval <?=$this->APPROVAL[0]?></td>
                <td>:</td>
                <td>
                    <input class="easyui-combobox" name="reqPegawaiIdApproval1" id="reqPegawaiIdApproval1" style="width:400px;" data-options="
                        url: 'pegawai_json/combo_asman/',
                        method: 'get',
                        valueField:'id', 
                        textField:'nama',
                        editable:false
                    " value="<?=$reqPegawaiIdApproval1?>" <?=$this->APPROVAL1_REQUIRED?>>
                    <a id="clueTipBox1" class="clueTipBox" title="Panduan|Apabila data <?=$this->APPROVAL[0]?> tidak muncul, hubungi Administrator."><img src="images/help.png"></a>
                </td>
            </tr>        
            <tr>
                <td>Approval <?=$this->APPROVAL[1]?></td>
                <td>:</td>
                <td>
                   <input class="easyui-combobox" name="reqPegawaiIdApproval2" id="reqPegawaiIdApproval2" style="width:400px;" data-options="
                        url: 'pegawai_json/combo_manajer/',
                        method: 'get',
                        valueField:'id', 
                        textField:'nama',
                        editable:false
                    " value="<?=$reqPegawaiIdApproval2?>" required>
                    <a id="clueTipBox2" class="clueTipBox" title="Panduan|Apabila data <?=$this->APPROVAL[1]?> tidak muncul, hubungi Administrator."><img src="images/help.png"></a>
                </td>
            </tr>            
        </tbody>
        </table>
        <input type="hidden" id="reqMaksimalHari" name="reqMaksimalHari" value="" />
        <input type="hidden" id="reqMinimalHari" name="reqMinimalHari" value="" />
        <input type="hidden" id="reqSisaCuti" name="reqSisaCuti" value="" />
        <input type="hidden" name="reqId" value="<?=$reqId?>" />
        <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
        <input type="hidden" name="reqTahun" value="<?=$reqTahun?>" />
        <input type="hidden" id="reqPosting" name="reqPosting" value="D" />
        <input type="hidden" id="reqTanggalPosting" name="reqTanggalPosting" value="<?=date("d-m-Y")?>" />
        <button id="btnDraft" name="btnDraft" class="btn btn-primary">Draft</button>
        <button id="btnSubmit" name="btnSubmit" class="btn btn-primary">Submit</button>
        <input type="submit" id="reqSubmit" name="reqSubmit" class="btn btn-primary" value="Submit" style="display:none" />
        <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
        
      </form>

		</div>
	</div>
</body>
</html>