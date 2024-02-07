<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");


class globalrekappegawai
{
	function getsessionuser()
	{
		$CI = &get_instance();

		$sessionloginlevel= $CI->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$sessionsatuankerja= $CI->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$sessionusergroup= $CI->kauth->getInstance()->getIdentity()->USER_GROUP;
		$sessionstatussatuankerjabkd= $CI->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
		$sessionaksesappsimpegid= $CI->kauth->getInstance()->getIdentity()->AKSES_APP_SIMPEG_ID;

		$arrreturn= [];
		$arrreturn["sessionloginlevel"]= $sessionloginlevel;
		$arrreturn["sessionsatuankerja"]= $sessionsatuankerja;
		$arrreturn["sessionusergroup"]= $sessionusergroup;
		$arrreturn["sessionstatussatuankerjabkd"]= $sessionstatussatuankerjabkd;
		$arrreturn["sessionaksesappsimpegid"]= $sessionaksesappsimpegid;
		// print_r($arrreturn);exit;
		return $arrreturn;
	}

	function getsatuankerjanama($arrparam)
	{
		// print_r($arrparam);exit;
		$satuankerjaid= $arrparam["satuankerjaid"];

		$CI = &get_instance();
		$CI->load->model("SatuanKerja");

		$statement= " AND A.SATUAN_KERJA_ID = ".$satuankerjaid;
		$set= new SatuanKerja();
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit;
		$set->firstRow();
		return $set->getField("SATUAN_KERJA_NAMA_DETIL");
	}

	function getparampegawai($arrparam)
	{
		// print_r($arrparam);exit;
		$satuankerjaid= $arrparam["satuankerjaid"];
		$tipepegawaiid= $arrparam["tipepegawaiid"];
		$statuspegawaiid= $arrparam["statuspegawaiid"];
		$eselongroupid= $arrparam["eselongroupid"];
		$mode= $arrparam["mode"];

		$CI = &get_instance();
		$CI->load->model("talent/RekapTalent");
		$CI->load->model("SatuanKerja");

		$vsatuankerjaid= "";
		$statement= "";
		if(empty($satuankerjaid))
		{
			// $statement= " AND A.SATUAN_KERJA_PARENT_ID = 0";

			$arrgetsessionuser= $this->getsessionuser();
			$sessionsatuankerja= $arrgetsessionuser["sessionsatuankerja"];
			$sessionusergroup= $arrgetsessionuser["sessionusergroup"];
			$sessionstatussatuankerjabkd= $arrgetsessionuser["sessionstatussatuankerjabkd"];

			$tempSatuanKerjaId= $reqSatuanKerjaId= $sessionsatuankerja;
			$statementAktif= "";
			if($reqSatuanKerjaId == "")
			{
				if(isStrContain(strtoupper($sessionusergroup), "TEKNIS") == "1" || $sessionstatussatuankerjabkd == 1)
				{
					$statementAktif= " AND EXISTS(
					SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
					AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
					)";
				}
			}
			else
			{
				$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
				)";
				
				if(isStrContain(strtoupper($sessionusergroup), "TEKNIS") == "1" || $sessionstatussatuankerjabkd == 1)
				{
					if(isStrContain(strtoupper($sessionusergroup), "TEKNIS") == "1" && $reqSatuanKerjaTeknisId == ""){}
					else
					{
						$vsatuankerjaid= "";
						if($tempSatuanKerjaId == ""){}
						else
						{
							$vsatuankerjaid= $tempSatuanKerjaId;
						}
					}
				}
				else
				{
					$skerja= new SatuanKerja();
					$vsatuankerjaid= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
				}
			}

		}
		else
		{
			$statementdetil= " AND A.SATUAN_KERJA_ID = ".$satuankerjaid;

			// $vsatuankerja= [];
			$set= new RekapTalent();
			$set->selectsatkerid(array(), -1, -1, $statementdetil);
			while($set->nextRow())
			{
				$infosatuankerjaid= $set->getField("SATUAN_KERJA_ID");
				$setdetil= new RekapTalent();
				$vgetsatuankerja= $setdetil->getsatuankerja($infosatuankerjaid);

				$vsatuankerjaid= getconcatseparator($vsatuankerjaid, $vgetsatuankerja);

				// array_push($vsatuankerja, $set->getField("SATUAN_KERJA_ID"));
			}
		}
		// echo $vsatuankerjaid;exit;
		
		if(!empty($vsatuankerjaid))
		{
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$vsatuankerjaid.")";
		}

		if(!empty($tipepegawaiid))
		{
			$statement.= " AND A.TIPE_PEGAWAI_ID LIKE '".$tipepegawaiid."%'";
		}

		if(!empty($eselongroupid))
		{
  			$statement.= " AND A.ESELON_GROUP_ID = '".$eselongroupid."'";
		}

		if(empty($mode))
		{
			if(empty($statuspegawaiid) || $statuspegawaiid == "126")
			{
				$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2,6)";
			}
			else
			{
				$statement.= " AND A.STATUS_PEGAWAI_ID IN (".$statuspegawaiid.")";
			}
		}

		// echo $statement;exit;
		return $statement;
	}

	function getperumpunan()
	{
		$vreturn= $this->getglobalperumpunan("perumpunan");
		return $vreturn;
	}

	function getglobalperumpunan($infomode)
	{
		$CI = &get_instance();
		$CI->load->model("base/RumpunNilai");

		$vreturn= [];
		$statement= " AND A.INFOMODE = '".$infomode."'";
		$sorder= "ORDER BY A.INFOID";
		$set= new RumpunNilai();
		$set->selectparams(array(), -1, -1, $statement, $sorder);
		while($set->nextRow())
		{
			$vnilai= $set->getField("NILAI");
			$arrdata= [];
			$arrdata["rumpunnilaiid"]= $set->getField("INFOID");
			$arrdata["rumpunnilai"]= $vnilai;
			$arrdata["rumpunpersentase"]= $vnilai/100;
			$arrdata["rumpunketerangan"]= $set->getField("KETERANGAN_NILAI");

			array_push($vreturn, $arrdata);
		}
		return $vreturn;
	}

	function pilihstatus($vmode="")
	{
		$arrField= array(
			array("info"=>"Semua", "value"=>"")
			, array("info"=>"CPNS/PNS/PPPK", "value"=>"126")
			, array("info"=>"CPNS", "value"=>"1")
			, array("info"=>"PNS", "value"=>"2")
			, array("info"=>"PPPK", "value"=>"6")
		);
		return $arrField;
	}

	function kotakpetatalentaketerangan($vmode="")
	{
		$arrField= array(
			array("id"=>"1", "nama"=>"Kinerja dibawah ekspetasi dan potensial rendah")
			, array("id"=>"2", "nama"=>"Kinerja sesuai ekspetasi dan potensial rendah")
			, array("id"=>"3", "nama"=>"Kinerja dibawah ekspetasi dan potensial menengah")
			, array("id"=>"4", "nama"=>"Kinerja diatas ekspetasi dan potensial rendah")
			, array("id"=>"5", "nama"=>"Kinerja sesuai ekspetasi dan potensial menengah")
			, array("id"=>"6", "nama"=>"Kinerja dibawah ekspetasi dan potensial tinggi")
			, array("id"=>"7", "nama"=>"Kinerja diatas ekspetasi dan potensial menengah")
			, array("id"=>"8", "nama"=>"Kinerja sesuai ekspetasi dan potensial tinggi")
			, array("id"=>"9", "nama"=>"kinerja diatas ekspetasi dan potensial tinggi")
		);
		return $arrField;
	}

	function pilihtipepegawai($vmode="")
	{
		$arrField= array(
			array("info"=>"Semua", "value"=>"")
			, array("info"=>"Struktural", "value"=>"11")
			, array("info"=>"Pelaksana", "value"=>"12")
			, array("info"=>"JFT", "value"=>"2")
		);
		return $arrField;
	}

	function piliheselon($vmode="")
	{
		$arrField= array(
			array("info"=>"Semua", "value"=>"")
			, array("info"=>"II", "value"=>"2")
			, array("info"=>"III", "value"=>"3")
			, array("info"=>"IV", "value"=>"4")
		);
		return $arrField;
	}

	function inforumpun($statement="")
	{
		$CI = &get_instance();
		$CI->load->model("base/Rumpun");

		$set= new Rumpun();
		$set->selectByParams(array());
		// echo $set->query;exit;
		$arrrumpun=[];
		while($set->nextRow())
		{
		  $arrdata= [];
		  $arrdata["id"]= $set->getField("RUMPUN_ID");
		  $arrdata["kode"]= $set->getField("KODE");
		  $arrdata["nama"]= $set->getField("NAMA");
		  $arrdata["keterangan"]= $set->getField("KETERANGAN");
		  array_push($arrrumpun, $arrdata);
		};
		return $arrrumpun;
	}

	function infofaktorkoreksi()
	{
		$arrrumpun= array(
			array("id"=>"1", "kode"=>"INDISIPLIN", "nama"=>"INDISIPLIN", "keterangan"=>"INDISIPLIN")
			, array("id"=>"2", "kode"=>"PBJ", "nama"=>"PBJ", "keterangan"=>"PBJ")
			, array("id"=>"3", "kode"=>"PRESTASI", "nama"=>"PRESTASI", "keterangan"=>"PRESTASI")
			, array("id"=>"4", "kode"=>"NILAI", "nama"=>"NILAI", "keterangan"=>"NILAI")
		);
		return $arrrumpun;
	}

	function inforumpunnilai($statement="")
	{
		$CI = &get_instance();
		$CI->load->model("base/RumpunNilai");

		$set= new RumpunNilai();
		$set->selectparams(array(), -1,-1, $statement);
		// echo $set->query;exit;
		$arrrumpun=[];
		while($set->nextRow())
		{
		  $arrdata= [];
		  $arrdata["id"]= $set->getField("RUMPUN_ID");
		  $arrdata["kode"]= $set->getField("KODE");
		  $arrdata["nama"]= $set->getField("NAMA");
		  $arrdata["keterangan"]= $set->getField("KETERANGAN");
		  array_push($arrrumpun, $arrdata);
		};
		return $arrrumpun;
	}

	function infodetil($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("talent/RekapTalent");

		$reqMode= $arrparam["reqMode"];
		$reqId= $arrparam["reqId"];

		$arrdetilriwayat=[];
		if($reqMode == "jabatanriwayat")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$reqId;
			$setdetil= new RekapTalent();
			$setdetil->selectjabatanriwayat(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
			  $arrdata["nama"]= $setdetil->getField("NAMA");
			  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK_HITUNG");
			  $arrdata["mode"]= $reqMode;
			  $arrdata["jenisjabatanid"]= $setdetil->getField("JENIS_JABATAN_ID");
			  $arrdata["vreqid"]= $setdetil->getField("PEGAWAI_ID");
			  $arrdata["vreqrowid"]= $setdetil->getField("ROW_ID");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($reqMode == "pendidikanriwayat")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$reqId;
			$setdetil= new RekapTalent();
			$setdetil->selectpendidikanriwayat(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
			  $arrdata["nama"]= $setdetil->getField("NAMA");
			  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK");
			  $arrdata["mode"]= $reqMode;
			  $arrdata["vreqid"]= $setdetil->getField("PEGAWAI_ID");
			  $arrdata["vreqrowid"]= $setdetil->getField("ROW_ID");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($reqMode == "diklatriwayat")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$reqId;
			$setdetil= new RekapTalent();
			$setdetil->selectdiklatriwayat(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
			  $arrdata["nama"]= $setdetil->getField("DIKLAT_NAMA");
			  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK");
			  $arrdata["mode"]= $reqMode;
			  $arrdata["infourut"]= $setdetil->getField("INFO_URUT");
			  $arrdata["vreqid"]= $setdetil->getField("PEGAWAI_ID");
			  $arrdata["vreqrowid"]= $setdetil->getField("ROW_ID");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}

		return $arrdetilriwayat;
	}

	function inforiwayatdetil($vparam)
	{
		// print_r($vparam);exit;
		$infomode= $vparam->mode;

		$vriwayat= "";
		if($infomode == "jabatanriwayat")
		{
			$jenisjabatanid= $vparam->jenisjabatanid;

			if($jenisjabatanid == "1")
				$vriwayat= "pegawai_add_jabatan_struktural_data";
			else if($jenisjabatanid == "2")
				$vriwayat= "pegawai_add_jabatan_fungsional_data";
			else if($jenisjabatanid == "3")
				$vriwayat= "pegawai_add_jabatan_tertentu_data";
		}
		else if($infomode == "pendidikanriwayat")
		{
			$vriwayat= "pegawai_add_pendidikan_data";
		}
		else if($infomode == "diklatriwayat")
		{
			$infourut= $vparam->infourut;
			if($infourut == "1")
				$vriwayat= "pegawai_add_diklat_struktural_data";
			else
				$vriwayat= "pegawai_add_diklat_kursus_data";
			
		}
		

		return $vriwayat;
	}

	function ambildetilinfo($infolabel, $param)
	{
		$inforumpun= $this->inforumpun();
		// print_r($inforumpun);exit;

		$jumlahrumpun= count($inforumpun) + 1;

		$valreturn= '<table class="bordered highlight md-text table_list tabel-responsif responsive-table">
		<thead>
			<tr>
				<th rowspan="2">'.$infolabel.'</th>
				<th style="text-align:center" colspan="'.$jumlahrumpun.'">NILAI PER RUMPUN</th>
			</tr>
			<tr>
		';

		foreach ($inforumpun as $key => $value)
		{
			$valreturn.='
				<th style="text-align:center">'.$value["nama"].'</th>
			';
		}

		$valreturn.='
				<th rowspan="2">Aksi</th>
			</tr>
		</thead>
		<tbody>';

		foreach ($param as $k => $v)
		{
			$infonama= $v["nama"];
			$inforumpunid= $v["id"];
			$infomode= $v["mode"];
			$vreqid= $v["vreqid"];
			$vreqrowid= $v["vreqrowid"];

			$infodetilmode= $infomode."-".$vreqid."-".$vreqrowid;
			
			$arrrumpunid= [];
			if(!empty($inforumpunid))
			{
				$arrrumpunid= explode(",", $inforumpunid);
			}
			$inforumpunvalue= $v["nilai"];

			$valreturn.='
			<tr id="tableinfodetil-'.$infodetilmode.'">
				<td>'.$infonama.'</td>
			';

			foreach ($inforumpun as $key => $value)
			{
				$vrumpunid= $value["id"];
				$vnilai= 0;

				if(in_array($vrumpunid, $arrrumpunid))
				{
					$vnilai= coalesce($inforumpunvalue,"0");
				}

				$valreturn.='
					<td style="text-align:center">'.$vnilai.'</td>
				';
			}
			
			$vparsedata= base64_encode(json_encode($v));
			$valreturn.='
				<td style="text-align:center">
					<a href="javascript:void(0)"
					onclick=\'lihatriwayat("'.$vparsedata.'")\'
					title="Lihat Detil"><img src="images/icon-edit.png" />
					</a>
				</td>
			</tr>
			';	
		}

		$valreturn.='
		</tbody>
		</table>';

		// echo $valreturn;exit;

		// print_r($param);exit;

		/*$valreturn= '<div class="rTable">
			<div class="rTableRow">
				<div class="rTableHead">RIWAYAT JABATAN/ESELON</div>
				<div class="rTableHead">NILAI PER RUMPUN</div>
			</div>
			<div class="rTableRow">
				<div class="rTableHead"></div>
				<div class="rTableHead">ADM</div>
				<div class="rTableHead">PEM</div>
			</div>
		</div>';*/

		// print_r($param);exit;
		/*$valreturn= '<div class="rTable">
			<div class="rTableRow">
				<div class="rTableHead">Status</div>
				<div class="rTableHead">Jam</div>
				<div class="rTableHead">Ket</div>
			</div>
			<div class="rTableRow">
				<div class="rTableCell">In</div>
				<div class="rTableCell">'.$in1.'</div>
				<div class="rTableCell">
					<div class="infotooltip">'.$in2.'
						<span class="infotooltiptext">'.$ininfo2.'</span>
					</div>
				</div>
			</div>
			<div class="rTableRow">
				<div class="rTableCell">Out</div>
				<div class="rTableCell">'.$out1.'</div>
				<div class="rTableCell">
					<div class="infotooltip">'.$out2.'
						<span class="infotooltiptext">'.$outinfo2.'</span>
					</div>
				</div>
			</div>
			<div class="rTableRow">
				<div class="rTableCell">A/S/K</div>
				<div class="rTableCell">'.$ask1.'</div>
				<div class="rTableCell">
					<div class="infotooltip">'.$ask2.'
						<span class="infotooltiptext">'.$askinfo2.'</span>
					</div>
				</div>
			</div>
		</div>';*/

		return $valreturn;
	}

	function getparamperumpunan($arrparam)
	{
		// print_r($arrparam);exit;
		$pegawaiid= $arrparam["pegawaiid"];
		$infomode= $arrparam["infomode"];
		// statuspegawaiid

		// $satuankerjaid
		$CI = &get_instance();
		$CI->load->model("talent/RekapTalent");

		$arrdetilriwayat=[];
		if($infomode == "jabatanriwayat")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$pegawaiid;
			$setdetil= new RekapTalent();
			$setdetil->selectrumpunjabatanriwayat(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
			  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK_HITUNG");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($infomode == "pendidikanriwayat")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$pegawaiid;
			$setdetil= new RekapTalent();
			$setdetil->selectrumpunpendidikanriwayat(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
			  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($infomode == "diklatriwayat")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$pegawaiid;
			$setdetil= new RekapTalent();
			$setdetil->selectrumpundiklatriwayat(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
			  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($infomode == "kompetensi")
		{
			$setdetil= new RekapTalent();
			$setdetil->selectakhirkompetensi($pegawaiid);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["nilai"]= $setdetil->getField("NILAI");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($infomode == "kinerja")
		{
			$setdetil= new RekapTalent();
			$setdetil->selectakhirkinerja($pegawaiid);
			// echo $setdetil->query;exit;
			while($setdetil->nextRow())
			{
			  $arrdata= [];
			  $arrdata["nilai"]= $setdetil->getField("NILAI");
			  array_push($arrdetilriwayat, $arrdata);
			}
		}
		else if($infomode == "faktorkoreksi")
		{
			$statementdetil= " AND A.PEGAWAI_ID = ".$pegawaiid;
			$setdetil= new RekapTalent();
			$setdetil->selectnilaiakhirdetil(array(), -1,-1, $statementdetil);
			// echo $setdetil->query;exit;
			$setdetil->firstRow();

			$arrdata= [];
			$arrdata["id"]= "1";
			$arrdata["nilai"]= $setdetil->getField("NILAI_INDISIPLINER");
			array_push($arrdetilriwayat, $arrdata);

			$arrdata= [];
			$arrdata["id"]= "2";
			$arrdata["nilai"]= $setdetil->getField("NILAI_PBJ");
			array_push($arrdetilriwayat, $arrdata);

			$arrdata= [];
			$arrdata["id"]= "3";
			$arrdata["nilai"]= $setdetil->getField("NILAI_PRESTASI");
			array_push($arrdetilriwayat, $arrdata);

			$arrdata= [];
			$arrdata["id"]= "4";
			$arrdata["nilai"]= $setdetil->getField("NILAI_FAKTOR_KOREKSI");
			array_push($arrdetilriwayat, $arrdata);
		}
		else if($infomode == "nilaiakhir")
		{
			$inforumpun= $this->inforumpun();

			foreach ($inforumpun as $key => $value)
			{
				$vrumpunid= $value["id"];
				$statementdetil= "";
				$setdetil= new RekapTalent();
				$setdetil->selectrumpunpegawainilaiakhir(array(), -1,-1, $vrumpunid, $pegawaiid, $statementdetil);
				// echo $setdetil->query;exit;
				$setdetil->firstRow();

				$arrdata= [];
				$arrdata["id"]= $vrumpunid;
				$arrdata["nilai"]= $setdetil->getField("NILAI");
				array_push($arrdetilriwayat, $arrdata);
			}
		}

		return $arrdetilriwayat;
	}
	

	function ambildetilkodeinfo($infomode, $param)
	{
		if($infomode == "faktorkoreksi")
			$inforumpun= $this->infofaktorkoreksi();
		else
			$inforumpun= $this->inforumpun();
		
		// print_r($inforumpun);exit;

		$valreturn= '<div class="rTable">
			<div class="rTableRow">
		';

		foreach ($inforumpun as $key => $value)
		{
			if($infomode == "nilairumpun" || $infomode == "nilaiakhir")
			{
				$valreturn.='
					<div style="cursor:pointer;" id="rumpunklikid'.$value["id"].'" class="rTableHead rumpunklik">'.$value["kode"].'</div>
				';
			}
			else
			{
				$valreturn.='
					<div class="rTableHead">'.$value["kode"].'</div>
				';	
			}
		}

		$valreturn.='
			</div>
		';

		if($infomode == "nilairumpun")
		{
			$nilairumpun= $this->getperumpunan();
			// print_r($nilairumpun);exit;
			$rekamjejak= $param["jabatanriwayat"];
			$kualifikasi= $param["pendidikanriwayat"];
			$kompetensi= $param["diklatriwayat"];
			// print_r($rekamjejak);exit;
			// print_r($param);exit;

			$valreturn.='
			<div class="rTableRow">
			';

			foreach ($inforumpun as $key => $value)
			{
				$vrumpunid= $value["id"];
				$vnilai= 0;

				foreach ($nilairumpun as $keyrumpun => $vrumpun)
				{
					$vrumpunnilaiid= $vrumpun["rumpunnilaiid"];
					$vpersentase= $vrumpun["rumpunpersentase"];

					$infocari= $vrumpunid;
					$infoparam= [];

					if($vrumpunnilaiid == "1")
					{
						$infoparam= $rekamjejak;
						$arraycari= in_array_column($infocari, "id", $infoparam);
					}
					else if($vrumpunnilaiid == "2")
					{
						$infoparam= $kualifikasi;
						$arraycari= in_array_column($infocari, "id", $infoparam);
					}
					else if($vrumpunnilaiid == "3")
					{
						$infoparam= $kompetensi;
						$arraycari= in_array_column($infocari, "id", $infoparam);
					}

					// print_r($arraycari);exit;
					if(!empty($arraycari))
					{
						$inforumpunvalue= $infoparam[$arraycari[0]]["nilai"];
						$inforumpunvalue= $vpersentase*coalesce($inforumpunvalue,"0");

						$vnilai+= $inforumpunvalue;
					}
				}

				$valreturn.='
					<div class="rTableCell">'.$vnilai.'</div>
				';
			}

			$valreturn.='
			</div>';
		}
		else
		{
			// print_r($param);exit;
			$valreturn.='
			<div class="rTableRow">
			';

			foreach ($inforumpun as $key => $value)
			{
				$vrumpunid= $value["id"];
				$vnilai= 0;

				$infocari= $vrumpunid;
				$arraycari= in_array_column($infocari, "id", $param);
				// print_r($arraycari);exit;
				if(!empty($arraycari))
				{
					$inforumpunvalue= $param[$arraycari[0]]["nilai"];
					$vnilai= coalesce($inforumpunvalue,"0");
				}

				$valreturn.='
					<div class="rTableCell">'.$vnilai.'</div>
				';
			}

			$valreturn.='
			</div>';
		}

		$valreturn.='
		</div>';

		// print_r($param);exit;

		// echo $valreturn;exit;

		// print_r($param);exit;

		/*$valreturn= '<div class="rTable">
			<div class="rTableRow">
				<div class="rTableHead">RIWAYAT JABATAN/ESELON</div>
				<div class="rTableHead">NILAI PER RUMPUN</div>
			</div>
			<div class="rTableRow">
				<div class="rTableHead"></div>
				<div class="rTableHead">ADM</div>
				<div class="rTableHead">PEM</div>
			</div>
		</div>';*/

		// print_r($param);exit;
		/*$valreturn= '<div class="rTable">
			<div class="rTableRow">
				<div class="rTableHead">Status</div>
				<div class="rTableHead">Jam</div>
				<div class="rTableHead">Ket</div>
			</div>
			<div class="rTableRow">
				<div class="rTableCell">In</div>
				<div class="rTableCell">'.$in1.'</div>
				<div class="rTableCell">
					<div class="infotooltip">'.$in2.'
						<span class="infotooltiptext">'.$ininfo2.'</span>
					</div>
				</div>
			</div>
			<div class="rTableRow">
				<div class="rTableCell">Out</div>
				<div class="rTableCell">'.$out1.'</div>
				<div class="rTableCell">
					<div class="infotooltip">'.$out2.'
						<span class="infotooltiptext">'.$outinfo2.'</span>
					</div>
				</div>
			</div>
			<div class="rTableRow">
				<div class="rTableCell">A/S/K</div>
				<div class="rTableCell">'.$ask1.'</div>
				<div class="rTableCell">
					<div class="infotooltip">'.$ask2.'
						<span class="infotooltiptext">'.$askinfo2.'</span>
					</div>
				</div>
			</div>
		</div>';*/

		return $valreturn;
	}
}