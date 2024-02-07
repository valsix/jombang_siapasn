<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pegawai_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   		
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->DEPARTEMEN;
		$this->SUB_DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->SUB_DEPARTEMEN;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;
	}	

	function proses()
	{
		$this->load->model('bio/PersonnelEmployee');
		$this->load->model('biotime/VPersonnelEmployee');
		
		/*$arrInfo="";
		$index_data= 0;
		$set= new PersonnelEmployee();
		$set->selectByParamsMonitoring();
		while($set->nextRow())
		{
			$arrInfo[$index_data]["ID_BIO"] = $set->getField("ID_BIO");
			$arrInfo[$index_data]["EMP_CODE"] = $set->getField("SATUAN_KERJA_ID");
			$arrInfo[$index_data]["AREA_NAME"] = $set->getField("NAMA");
			$index_data++;
		}
		$jumlahdata= $index_data;
		print_r($arrInfo);exit();*/

		$set= new PersonnelEmployee();
		$set->selectByParamsMonitoring();
		while($set->nextRow())
		{
			$reqId = $set->getField("ID");
			$reqIdBio = $set->getField("ID_BIO");
			$reqEmpCode = $set->getField("EMP_CODE");
			$reqFirstName = $set->getField("FIRST_NAME");

			$simpan= "";
			$setdetil= new VPersonnelEmployee();
			$setdetil->setField("ID", $reqIdBio);
			$setdetil->setField("EMP_CODE", $reqEmpCode);
			$setdetil->setField("FIRST_NAME", $reqFirstName);
			if($reqIdBio == "")
			{
				if($setdetil->insert())
				{
					$reqIdBio= $setdetil->id;
					$simpan= "1";
				}
			}
			else
			{
				if($setdetil->update())
				{
					$simpan= "1";
				}
			}

			if($simpan == "1")
			{
				$setup= new PersonnelEmployee();
				$setup->setField("ID", $reqId);
				$setup->setField("ID_BIO", $reqIdBio);
				$setup->setField("EMP_CODE", $reqEmpCode);
				$setup->setField("FIRST_NAME", $reqFirstName);
				if($reqId == "")
					$setup->insert();
				else
					$setup->update();
			}


		}
		echo "Data berhasil diproses.";
	}

	function adddetil()
	{
		$this->load->model('biotime/VSyncEmployee');
		$this->load->model('bio/PersonnelEmployee');
		$this->load->model('bio/PersonnelEmployeeArea');
		$this->load->model('bio/PersonnelArea');
		$this->load->model('siap/SatuanKerja');

		$reqMode= $this->input->post('reqMode');
		$reqId= $this->input->post('reqId');
		$reqRowId= $this->input->post('reqRowId');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiAktifAbsen= $this->input->post('reqPegawaiAktifAbsen');
		$reqPegawaiPin= $this->input->post('reqPegawaiPin');
		$reqPegawaiFirstName= $this->input->post('reqPegawaiFirstName');
		$reqPegawaiAreaCode= $this->input->post('reqPegawaiAreaCode');
		$reqPegawaiAreaNama= $this->input->post('reqPegawaiAreaNama');

		$reqStatusDataHapus= $this->input->post('reqStatusDataHapus');
		$reqPegawaiSebelumAreaCode= $this->input->post('reqPegawaiSebelumAreaCode');
		$reqAreaCode= $this->input->post('reqAreaCode');
		$updatebio= "";

		// kalau area dafault berubah, updatebio = 1
		// maka reset ulang area pegawai dari awal
		if($reqPegawaiSebelumAreaCode !== $reqPegawaiAreaCode && empty($updatebio))
		{
			$updatebio= "1";
		}
		// echo $updatebio."--".$reqPegawaiAreaNama;exit();

		if($reqPegawaiAktifAbsen == "1")
			$reqPegawaiAktifAbsen= "TRUE";
		else
			$reqPegawaiAktifAbsen= "FALSE";

		if($updatebio == "1")
		{
			$simpan= "";
			$set= new VSyncEmployee();
			$set->setField("ID", $reqPegawaiId);
			$set->setField("EMP_CODE", $reqPegawaiId);
			$set->setField("ACTIVE_STATUS", $reqPegawaiAktifAbsen);
			$set->setField("FIRST_NAME", $reqPegawaiFirstName);
			$set->setField("AREA_CODE", $reqPegawaiAreaCode);
			$set->setField("AREA_NAME", $reqPegawaiAreaNama);
			$set->setField("MULTI_AREA", "FALSE");

			if($reqMode == "insert")
			{
				if($set->insert())
				{
					$simpan= "1";
				}
			}
			else
			{
				if($set->update())
				{
					$simpan= "1";
				}
			}
			// $simpan= "1";
		}
		else
		{
			$simpan= "";
			$set= new VSyncEmployee();
			$set->setField("EMP_CODE", $reqPegawaiId);
			$set->setField("IS_ACTIVE", $reqPegawaiAktifAbsen);
			$set->updateactivestatus();
			$simpan= "1";
		}

		if($simpan == "1")
		{
			$arrvallama="";
			$indexlama= 0;
			// $statement= " AND A.PERSONNEL_EMPLOYEE_ID = ".$reqRowId;
			$statement= " AND VBIO.EMP_CODE = '".$reqPegawaiId."'";
			$set= new PersonnelEmployeeArea();
			$set->selectByParams(array(), -1,-1, $statement);
			while($set->nextRow())
			{
				$arrvallama[$indexlama]["ID"]= $set->getField("ID");
				$arrvallama[$indexlama]["EMPLOYEE_ID"]= $set->getField("EMPLOYEE_ID");
				$arrvallama[$indexlama]["AREA_ID"]= $set->getField("AREA_ID");
				$arrvallama[$indexlama]["STATUS_DEFAULT"]= $set->getField("STATUS_DEFAULT");
				$arrvallama[$indexlama]["PERSONNEL_EMPLOYEE_ID"]= $set->getField("PERSONNEL_EMPLOYEE_ID");
				$arrvallama[$indexlama]["AREA_CODE"]= $set->getField("AREA_CODE");
				$arrvallama[$indexlama]["AREA_NAME"]= $set->getField("AREA_NAME");
				$arrvallama[$indexlama]["EMP_CODE"]= $set->getField("EMP_CODE");
				$indexlama++;
			}

			// echo $reqStatusDataHapus."--".$indexlama."--".count($reqAreaCode)."--".$updatebio;
			// print_r($arrvallama);
			// print_r($reqAreaCode);
			// exit();

			// buat data area baru
			$arrvalbaru="";
			$indexbaru= 0;
			for($i=0; $i < count($reqAreaCode); $i++)
			{
				// echo $updatebio."<br/>";
				// echo $reqAreaCode[$i];
				if($indexlama > 0)
				{
					// kalau area code default
					if($updatebio == "1" && $arrvallama[$i]["STATUS_DEFAULT"] == 1)
					{
						$reqStatusDataHapus= "1";
						// ambil data area
						$statementdetil= " AND A.SATUAN_KERJA_ID = ".$reqPegawaiAreaCode;
						$setdetil= new SatuanKerja();
						$setdetil->selectByParamsMesin(array(), -1, -1, $statementdetil);
						$setdetil->firstRow();
						// echo $setdetil->query;exit();
						$valbaruareanama= $setdetil->getField("NAMA_SINGKAT_MESIN");
						$valbaruareaid= $setdetil->getField("ID_BIO");
						unset($setdetil);

						// ambil data employee
						$statementdetil= " AND A.EMP_CODE = '".$reqPegawaiId."'";
						$setdetil= new PersonnelEmployee();
						$setdetil->selectByParams(array(), -1, -1, $statementdetil);
						$setdetil->firstRow();
						$valbaruemployeeid= $setdetil->getField("ID_BIO");
						unset($setdetil);

						$arrvalbaru[$indexbaru]["ID"]= $arrvallama[$i]["ID"];
						$arrvalbaru[$indexbaru]["EMPLOYEE_ID"]= $valbaruemployeeid;
						$arrvalbaru[$indexbaru]["AREA_ID"]= $valbaruareaid;
						$arrvalbaru[$indexbaru]["STATUS_DEFAULT"]= $arrvallama[$i]["STATUS_DEFAULT"];
						$arrvalbaru[$indexbaru]["PERSONNEL_EMPLOYEE_ID"]= $arrvallama[$i]["PERSONNEL_EMPLOYEE_ID"];
						$arrvalbaru[$indexbaru]["AREA_CODE"]= $arrvallama[$i]["AREA_CODE"];
						$arrvalbaru[$indexbaru]["AREA_NAME"]= $valbaruareanama;
						$arrvalbaru[$indexbaru]["EMP_CODE"]= $arrvallama[$i]["EMP_CODE"];
						$arrvalbaru[$indexbaru]["UPDATEBIO"]= $reqStatusDataHapus;
						$indexbaru++;
					}
					// kalau sama updatebio set null, tujuan tidak di reset
					elseif($reqAreaCode[$i] == $arrvallama[$i]["AREA_CODE"])
					{
						$arrvalbaru[$indexbaru]["ID"]= $arrvallama[$i]["ID"];
						$arrvalbaru[$indexbaru]["EMPLOYEE_ID"]= $arrvallama[$i]["EMPLOYEE_ID"];
						$arrvalbaru[$indexbaru]["AREA_ID"]= $arrvallama[$i]["AREA_ID"];
						$arrvalbaru[$indexbaru]["STATUS_DEFAULT"]= $arrvallama[$i]["STATUS_DEFAULT"];
						$arrvalbaru[$indexbaru]["PERSONNEL_EMPLOYEE_ID"]= $arrvallama[$i]["PERSONNEL_EMPLOYEE_ID"];
						$arrvalbaru[$indexbaru]["AREA_CODE"]= $arrvallama[$i]["AREA_CODE"];
						$arrvalbaru[$indexbaru]["AREA_NAME"]= $arrvallama[$i]["AREA_NAME"];
						$arrvalbaru[$indexbaru]["EMP_CODE"]= $arrvallama[$i]["EMP_CODE"];
						$arrvalbaru[$indexbaru]["UPDATEBIO"]= $reqStatusDataHapus;
						$indexbaru++;
					}
					elseif($reqAreaCode[$i] !== $arrvallama[$i]["AREA_CODE"] && empty($updatebio))
					{
						$valbaruareacode= $reqAreaCode[$i];
						// tutuk kene lnjut mene

						// kalau ada . maka ambbil mesin area lain
						$checkcontaintitik= isStrContain($valbaruareacode, ".");

						if(empty($checkcontaintitik))
						{
							// ambil data area
							$statementdetil= " AND A.SATUAN_KERJA_ID = ".$valbaruareacode;
							$setdetil= new SatuanKerja();
							$setdetil->selectByParamsMesin(array(), -1, -1, $statementdetil);
							$setdetil->firstRow();
							// echo $setdetil->query;exit();
							$valbaruareanama= $setdetil->getField("NAMA_SINGKAT_MESIN");
							$valbaruareaid= $setdetil->getField("ID_BIO");
							unset($setdetil);
						}
						else
						{
							// ambil data area
							$statementdetil= " AND IPAR.AREA_CODE = '".$valbaruareacode."'";
							$setdetil= new SatuanKerja();
							$setdetil->selectByParamsMesinLain(array(), -1, -1, $statementdetil);
							$setdetil->firstRow();
							// echo $setdetil->query;exit();
							$valbaruareanama= $setdetil->getField("NAMA_SINGKAT_MESIN");
							$valbaruareaid= $setdetil->getField("ID_BIO");
							unset($setdetil);	
						}

						// ambil data employee
						$statementdetil= " AND A.EMP_CODE = '".$reqPegawaiId."'";
						$setdetil= new PersonnelEmployee();
						$setdetil->selectByParams(array(), -1, -1, $statementdetil);
						$setdetil->firstRow();
						$valbaruemployeeid= $setdetil->getField("ID_BIO");
						unset($setdetil);

						$arrvalbaru[$indexbaru]["ID"]= "";
						$arrvalbaru[$indexbaru]["EMPLOYEE_ID"]= $valbaruemployeeid;
						$arrvalbaru[$indexbaru]["AREA_ID"]= $valbaruareaid;
						$arrvalbaru[$indexbaru]["STATUS_DEFAULT"]= "";
						$arrvalbaru[$indexbaru]["PERSONNEL_EMPLOYEE_ID"]= $reqRowId;
						$arrvalbaru[$indexbaru]["AREA_CODE"]= $valbaruareacode;
						$arrvalbaru[$indexbaru]["AREA_NAME"]= $valbaruareanama;
						$arrvalbaru[$indexbaru]["EMP_CODE"]= $reqPegawaiId;
						$arrvalbaru[$indexbaru]["UPDATEBIO"]= "1";
						$indexbaru++;
					}

				}
				else
				{

				}
			}

			if($indexbaru == 0)
			{
				$reqStatusDataHapus= "1";
				// ambil data area
				$statementdetil= " AND A.SATUAN_KERJA_ID = ".$reqPegawaiAreaCode;
				$setdetil= new SatuanKerja();
				$setdetil->selectByParamsMesin(array(), -1, -1, $statementdetil);
				$setdetil->firstRow();
				// echo $setdetil->query;exit();
				$valbaruareanama= $setdetil->getField("NAMA_SINGKAT_MESIN");
				$valbaruareaid= $setdetil->getField("ID_BIO");
				unset($setdetil);

				// ambil data employee
				$statementdetil= " AND A.EMP_CODE = '".$reqPegawaiId."'";
				$setdetil= new PersonnelEmployee();
				$setdetil->selectByParams(array(), -1, -1, $statementdetil);
				$setdetil->firstRow();
				$valbaruemployeeid= $setdetil->getField("ID_BIO");
				unset($setdetil);

				$arrvalbaru[$indexbaru]["ID"]= "";
				$arrvalbaru[$indexbaru]["EMPLOYEE_ID"]= $valbaruemployeeid;
				$arrvalbaru[$indexbaru]["AREA_ID"]= $valbaruareaid;
				$arrvalbaru[$indexbaru]["STATUS_DEFAULT"]= "1";
				$arrvalbaru[$indexbaru]["PERSONNEL_EMPLOYEE_ID"]= $reqRowId;
				$arrvalbaru[$indexbaru]["AREA_CODE"]= $reqPegawaiAreaCode;
				$arrvalbaru[$indexbaru]["AREA_NAME"]= $valbaruareanama;
				$arrvalbaru[$indexbaru]["EMP_CODE"]= $reqPegawaiId;
				$arrvalbaru[$indexbaru]["UPDATEBIO"]= $reqStatusDataHapus;
				$indexbaru++;
			}

			// echo $arrvalbaru[0]["AREA_CODE"]."-".$arrvalbaru[0]["AREA_NAME"]."-".$indexbaru;
			// print_r($arrvalbaru);exit();

			$reqJumlahArea= $indexbaru;
			$set= new PersonnelEmployee();
			$set->setField("ID", $reqRowId);
			$set->setField("EMP_CODE", $reqPegawaiId);
			$set->setField("FIRST_NAME", $reqPegawaiFirstName);
			$set->setField("JUMLAH_AREA", $reqJumlahArea);
			$set->setField("ACTIVE_STATUS", $reqPegawaiAktifAbsen);
			$set->setField("AREA_CODE", $reqPegawaiAreaCode);
			$set->setField("AREA_NAME", $reqPegawaiAreaNama);
			$set->setField("DEVICE_PASSWORD", $reqPegawaiPin);

			if(empty($reqRowId))
			{
				$set->insert();
				$reqRowId= $set->id;
			}
			else
			{
				$set->update();
			}
			// echo $set->query;exit();
			unset($set);

			// reset data
			$set= new PersonnelEmployeeArea();
			$set->setField("PERSONNEL_EMPLOYEE_ID", $reqRowId);
			$set->delete();
			unset($set);
			// exit();

			for($i=0; $i < $indexbaru; $i++)
			{
				$areavalid= $arrvalbaru[$i]["ID"];
				$areavalemployeeid= $arrvalbaru[$i]["EMPLOYEE_ID"];
				$areavalareaid= $arrvalbaru[$i]["AREA_ID"];
				$areavalstatusdefault= $arrvalbaru[$i]["STATUS_DEFAULT"];
				$areavalpersonelemployeid= $arrvalbaru[$i]["PERSONNEL_EMPLOYEE_ID"];
				$areavalareacode= $arrvalbaru[$i]["AREA_CODE"];
				$areavalareaname= $arrvalbaru[$i]["AREA_NAME"];
				$areavalupdatebio= $arrvalbaru[$i]["UPDATEBIO"];
				$areavalempcode= $arrvalbaru[$i]["EMP_CODE"];

				// buat data ke area pegawai
				$set= new PersonnelEmployeeArea();
				$set->setField("EMPLOYEE_ID", ValToNullDB($areavalemployeeid));
				$set->setField("AREA_ID", ValToNullDB($areavalareaid));
				$set->setField("STATUS_DEFAULT", ValToNullDB($areavalstatusdefault));
				$set->setField("PERSONNEL_EMPLOYEE_ID", $areavalpersonelemployeid);
				$set->setField("AREA_CODE", $areavalareacode);
				$set->setField("AREA_NAME", $areavalareaname);
				$set->setField("EMP_CODE", $areavalempcode);
				$set->setField("UPDATEBIO", ValToNullDB($areavalupdatebio));
				$set->setField("ID", $areavalid);

				if(empty($areavalid))
				{
					$set->insert();
					// echo $set->query;exit();
				}
				else
				{
					$set->insertdatalama();
				}
				unset($set);
			}

			echo "-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";
	}

	function flagbelumsyncarea()
	{
		$this->load->model('biotime/VSyncEmployee');

		$reqCode= $this->input->get("reqCode");

		$statement= " AND A.FLAG = 0";
		if(!empty($reqCode))
		{
			$statement.= " AND A.EMP_CODE = '".$reqCode."'";
		}

		$ch= new VSyncEmployee();
		$jumlahdata= $ch->getCountByParams(array(), $statement);
		// echo $ch->query;exit();
		echo json_encode($jumlahdata);
	}

	function syncpersonal()
	{
		$this->load->model('biotime/VPersonnelEmployee');
		$this->load->model('bio/PersonnelEmployee');

		$reqCode= $this->input->get("reqCode");

		if(!empty($reqCode))
		{
			$statement.= " AND A.EMP_CODE = '".$reqCode."'";
		}

		$set= new VPersonnelEmployee();
		$set->selectByParams(array(), -1,-1, $statement);
		while($set->nextRow())
		{
			$valbioid= $set->getField("ID");
			$valempcode= $set->getField("EMP_CODE");

			$statement= " AND A.EMP_CODE = '".$valempcode."'";
			$ch= new PersonnelEmployee();
			$ch->selectByParams(array(), -1, -1, $statement);
			$ch->firstRow();
			$chid= $ch->getField("ID");
			$chidbio= $ch->getField("ID_BIO");
			$chdevicepassword= $ch->getField("DEVICE_PASSWORD");
			unset($ch);

			if(empty($chidbio))
			{
				$simpan= "";
				$sv= new PersonnelEmployee();
				$sv->setField("DEVICE_PASSWORD", $chdevicepassword);
				$sv->setField("ID", $chid);
				$sv->setField("ID_BIO", $valbioid);
				$sv->updateIdBio();
				// echo $sv->query;exit();
				unset($sv);
			}

			// update untuk pin
			$sv= new VPersonnelEmployee();
			$sv->setField("DEVICE_PASSWORD", $chdevicepassword);
			$sv->setField("ID", $valbioid);
			$sv->updatePin();
			// echo $sv->query;exit();
			unset($sv);
			

		}
		
		echo json_encode("1");
	}

	function flagbelumsyncpersonalarea()
	{
		$this->load->model('bio/PersonnelEmployeeArea');

		$reqCode= $this->input->get("reqCode");

		$statement= " AND COALESCE(A.UPDATEBIO,0) > 0";
		if(!empty($reqCode))
		{
			$statement.= " AND A.EMP_CODE = '".$reqCode."'";
		}

		$ch= new PersonnelEmployeeArea();
		$jumlahdata= $ch->getCountByParams(array(), $statement);
		// echo $ch->query;exit();
		echo json_encode($jumlahdata);
	}

	function syncpersonalarea()
	{
		$this->load->model('bio/PersonnelEmployeeArea');
		$this->load->model('biotime/VSyncEmployee');

		$reqCode= $this->input->get("reqCode");

		if(!empty($reqCode))
		{
			$statement.= " AND A.EMP_CODE = '".$reqCode."'";
		}

		$statement.= " AND COALESCE(A.UPDATEBIO,0) > 0";
		$set= new PersonnelEmployeeArea();
		$set->selectByParamsSimple(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$set->firstRow();
		$synid= $set->getField("ID");

		if(!empty($synid))
		{
			$synstatusdefault= $set->getField("STATUS_DEFAULT");
			$synareacode= $set->getField("AREA_CODE");
			$synareaname= $set->getField("AREA_NAME");
			$synempcode= $set->getField("EMP_CODE");

			if($synstatusdefault == "1")
				$synmultiarea= "FALSE";
			else
				$synmultiarea= "TRUE";

			// echo "synstatusdefault:".$synstatusdefault."<br/>";
			// echo "synareacode:".$synareacode."<br/>";
			// echo "synareaname:".$synareaname."<br/>";
			// echo "synmultiarea:".$synmultiarea."<br/>";

			$sv= new VSyncEmployee();
			$sv->setField("AREA_CODE", $synareacode);
			$sv->setField("AREA_NAME", $synareaname);
			$sv->setField("MULTI_AREA", $synmultiarea);
			$sv->setField("ID", $synempcode);
			if($sv->resetarea())
			{
				$svdetil= new PersonnelEmployeeArea();
				$svdetil->setField("ID", $synid);
				$svdetil->updatebio();
				unset($svdetil);
			}
			unset($sv);
		}
		echo json_encode("1");
	}
	
	function json() 
	{
		$this->load->model('siap/SatuanKerja');
		$this->load->model('bio/PersonnelEmployee');
		$set= new PersonnelEmployee;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatusIntegrasi= $this->input->get("reqStatusIntegrasi");
		$reqStatusAktif= $this->input->get("reqStatusAktif");
		$reqPencarian= $this->input->get("reqPencarian");

		$aColumns = array("EMP_CODE", "NIP_NAMA", "SATUAN_KERJA_DETIL", "JABATAN_RIWAYAT_NAMA", "IS_AKTIF_NAMA", "INFO_FP", "AREA_DEFAULT", "AREA_ABSENSI", "EMP_CODE");
		$aColumnsAlias = $aColumns;
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY EMP_CODE asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else

			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}

		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
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
			
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" && $reqSatuanKerjaTeknisId == ""){}
				else
				{
					$reqSatuanKerjaId= "";
					if($tempSatuanKerjaId == ""){}
					else
					{
						$reqSatuanKerjaId= $tempSatuanKerjaId;
						$skerja= new SatuanKerja();
						$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
						unset($skerja);
						//echo $reqSatuanKerjaId;exit;
						$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
					}
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		if(empty($reqStatusIntegrasi)){}
		else
		{
			if($reqStatusIntegrasi == "1")
			{
				$statement.= " AND A.ID_BIO IS NOT NULL";
			}
			elseif($reqStatusIntegrasi == "2")
			{
				$statement.= " AND A.ID_BIO IS NULL";
			}
		}

		if(empty($reqStatusAktif)){}
		else
		{
			if($reqStatusAktif == "1")
			{
				$statement.= " AND A.IS_AKTIF = 1";
			}
			elseif($reqStatusAktif == "2")
			{
				$statement.= " AND A.IS_AKTIF = 0";
			}
		}

		// echo "dsplyRange:".$dsplyRange.";dsplyStart:".$dsplyStart;exit();
		$searchJson = " AND (UPPER(A.FIRST_NAME) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(CAST(A.EMP_CODE AS TEXT)) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%')";

		// echo $searchJson;exit();

		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsMonitoring(array(), $searchJson.$statement);
		
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		  // echo $set->query;exit;
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($set->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{

				if($aColumns[$i] == "NIP_NAMA")
				{
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "IS_AKTIF_NAMA")
				{
					$valIsAktif= $set->getField("IS_AKTIF");
					if($valIsAktif == "1")
						$tempValue= "√";
					else
						$tempValue= "-";

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "SATUAN_KERJA_DETIL")
				{
					$setdetil= new PersonnelEmployee();
					$tempValue= $setdetil->getSatkerNamaDetil($set->getField("SATUAN_KERJA_ID"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "AREA_DEFAULT")
				{
					$setdetil= new PersonnelEmployee();
					$tempValue= $setdetil->getAmbilSatkerMesin($set->getField("SATUAN_KERJA_ID"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "AREA_ABSENSI")
				{
					$setdetil= new PersonnelEmployee();
					$tempValue= $setdetil->getPegawaiAreaAbsensi($set->getField("EMP_CODE"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "INFO_FP")
				{
					$row[] = $set->getField("JUMLAH_FP")." / ".$set->getField("JUMLAH_FF");
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function jsonrekon() 
	{
		$this->load->model('siap/SatuanKerja');
		$this->load->model('bio/PersonnelEmployee');
		$set= new PersonnelEmployee;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatusIntegrasi= $this->input->get("reqStatusIntegrasi");
		$reqPencarian= $this->input->get("reqPencarian");

		$aColumns = array("EMP_CODE", "NIP_NAMA", "SATUAN_KERJA_DETIL", "IS_AKTIF_NAMA", "AREA_DEFAULT", "AREA_ABSENSI", "EMP_CODE");
		$aColumnsAlias = $aColumns;
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY EMP_CODE asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else

			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			$dsplyRange = 2147483645;
		}

		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
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
			
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" && $reqSatuanKerjaTeknisId == ""){}
				else
				{
					$reqSatuanKerjaId= "";
					if($tempSatuanKerjaId == ""){}
					else
					{
						$reqSatuanKerjaId= $tempSatuanKerjaId;
						$skerja= new SatuanKerja();
						$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
						unset($skerja);
						//echo $reqSatuanKerjaId;exit;
						$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
					}
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$statement.= "AND EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT A.PEGAWAI_ID FROM presensi.P_PEGAWAI_NOT_SYNC() A
			) X WHERE A.EMP_CODE = X.PEGAWAI_ID
		)";

		// echo "dsplyRange:".$dsplyRange.";dsplyStart:".$dsplyStart;exit();
		$searchJson = " AND (UPPER(A.FIRST_NAME) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(CAST(A.EMP_CODE AS TEXT)) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%')";

		// echo $searchJson;exit();

		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsMonitoring(array(), $searchJson.$statement);
		
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		  // echo $set->query;exit;
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($set->nextRow())		
		{		
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{

				if($aColumns[$i] == "NIP_NAMA")
				{
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "IS_AKTIF_NAMA")
				{
					$valIsAktif= $set->getField("IS_AKTIF");
					if($valIsAktif == "1")
						$tempValue= "√";
					else
						$tempValue= "-";

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "SATUAN_KERJA_DETIL")
				{
					$setdetil= new PersonnelEmployee();
					$tempValue= $setdetil->getSatkerNamaDetil($set->getField("SATUAN_KERJA_ID"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "AREA_DEFAULT")
				{
					$setdetil= new PersonnelEmployee();
					$tempValue= $setdetil->getAmbilSatkerMesin($set->getField("SATUAN_KERJA_ID"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				elseif($aColumns[$i] == "AREA_ABSENSI")
				{
					$setdetil= new PersonnelEmployee();
					$tempValue= $setdetil->getPegawaiAreaAbsensi($set->getField("EMP_CODE"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
}