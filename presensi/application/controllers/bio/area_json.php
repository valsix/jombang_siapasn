<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class area_json extends CI_Controller {

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

	function syncarea()
	{
		$this->load->model('siap/SatuanKerja');
		$this->load->model('biotime/VSyncArea');

		// untuk integrasi ke syncarea dari satuan kerja
		$set= new SatuanKerja();
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";
		$statementAktif.= " AND A.STATUS_MESIN_POSISI = 1 AND SYNC.SATUAN_KERJA_ID IS NOT NULL";

		$set->selectByParamsSatuanKerjaTree(array(), -1, -1, $statementAktif);
		while($set->nextRow())
		{
			$valnamasingkat= $set->getField("NAMA_SINGKAT");
			$valareacode= $set->getField("SATUAN_KERJA_ID");
			$valareanama= $set->getField("AREA_NAMA");
			$valstatusintegrasi= $set->getField("STATUS_INTEGRASI");
			// echo $valnamasingkat."--".$valareanama."--".$valstatusintegrasi."<br/>";

			if(!empty($valnamasingkat))
			{
				if($valstatusintegrasi == 0 || $valnamasingkat !== $valareanama)
				{
					$statement= " AND A.AREA_CODE = '".$valareacode."'";
					$ch= new VSyncArea();
					$ch->selectByParams(array(), -1, -1, $statement);
					$ch->firstRow();
					$chareacode= $ch->getField("AREA_CODE");
					$reqmode= "insert";
					if(!empty($chareacode))
						$reqmode= "update";
					unset($ch);


					$simpan= "";
					$sv= new VSyncArea();
					$sv->setField("ID", $valareacode);
					$sv->setField("AREA_CODE", $valareacode);
					$sv->setField("AREA_NAME", $valnamasingkat);
					if($reqmode == "insert")
					{
						if($sv->insert())
							$simpan= 1;
					}
					else
					{
						if($sv->update())
							$simpan= 1;
					}
					unset($sv);
					// echo $valnamasingkat."--".$valareanama."--".$valstatusintegrasi."--".$reqmode."<br/>";
				}
			}
		}
		echo json_encode("1");
	}

	function flagbelumsyncarea()
	{
		$this->load->model('biotime/VSyncArea');

		$reqCode= $this->input->get("reqCode");

		$statement= " AND A.FLAG = 0";
		if(!empty($reqCode))
		{
			$statement.= " AND A.AREA_CODE = '".$reqCode."'";
		}

		$ch= new VSyncArea();
		$jumlahdata= $ch->getCountByParams(array(), $statement);
		// echo $ch->query;exit();
		echo json_encode($jumlahdata);
	}

	function syncpersonalarea()
	{
		$this->load->model('bio/PersonnelArea');
		$this->load->model('biotime/VPersonnelArea');

		$reqCode= $this->input->get("reqCode");

		$statement= " AND A.ID > 1";
		if(!empty($reqCode))
		{
			$statement.= " AND A.AREA_CODE = '".$reqCode."'";
		}

		$set= new VPersonnelArea();
		$set->selectByParams(array(), -1,-1, $statement);
		while($set->nextRow())
		{
			$valbioid= $set->getField("ID");
			$valareacode= $set->getField("AREA_CODE");
			$valareanama= $set->getField("AREA_NAME");
			$arrareacode= explode(".", $valareacode);
			$jumlahdetil= count($arrareacode);

			$areaparentcode= "0";
			if($jumlahdetil > 1)
			{
				$areaparentcode= "xxx";
			}

			$statement= " AND A.AREA_CODE = '".$valareacode."'";
			$ch= new PersonnelArea();
			$ch->selectByParams(array(), -1, -1, $statement);
			$ch->firstRow();
			$chid= $ch->getField("ID");

			$reqmode= "insert";
			if(!empty($chid))
				$reqmode= "update";
			unset($ch);

			$simpan= "";
			$sv= new PersonnelArea();
			$sv->setField("ID", $chid);
			$sv->setField("AREA_CODE", $valareacode);
			$sv->setField("AREA_PARENT_CODE", $areaparentcode);
			$sv->setField("AREA_NAME", $valareanama);
			$sv->setField("STATUS_INTEGRASI", "1");
			$sv->setField("ID_BIO", $valbioid);
			if($reqmode == "insert")
			{
				if($sv->insert())
					$simpan= 1;
			}
			else
			{
				if($sv->update())
					$simpan= 1;
			}
			unset($sv);
		}
		
		echo json_encode("1");
	}

	function proses()
	{
		$this->load->model('bio/PersonnelArea');
		$this->load->model('biotime/VPersonnelArea');
		
		/*$arrInfo="";
		$index_data= 0;
		$set= new PersonnelArea();
		$set->selectByParamsMonitoring();
		while($set->nextRow())
		{
			$arrInfo[$index_data]["ID_BIO"] = $set->getField("ID_BIO");
			$arrInfo[$index_data]["AREA_CODE"] = $set->getField("SATUAN_KERJA_ID");
			$arrInfo[$index_data]["AREA_NAME"] = $set->getField("NAMA_SINGKAT");
			$index_data++;
		}
		$jumlahdata= $index_data;
		print_r($arrInfo);exit();*/

		$set= new PersonnelArea();
		$set->selectByParamsMonitoring();
		while($set->nextRow())
		{
			$reqId = $set->getField("ID");
			$reqIdBio = $set->getField("ID_BIO");
			$reqAreaCode = $set->getField("SATUAN_KERJA_ID");
			$reqAreaName = $set->getField("NAMA_SINGKAT");

			$simpan= "";
			$setdetil= new VPersonnelArea();
			$setdetil->setField("ID", $reqIdBio);
			$setdetil->setField("AREA_CODE", $reqAreaCode);
			$setdetil->setField("AREA_NAME", $reqAreaName);
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
				$setup= new PersonnelArea();
				$setup->setField("ID", $reqId);
				$setup->setField("ID_BIO", $reqIdBio);
				$setup->setField("AREA_CODE", $reqAreaCode);
				$setup->setField("AREA_NAME", $reqAreaName);
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
		$this->load->model('biotime/VSyncArea');
		$this->load->model('bio/PersonnelArea');

		$reqMode= $this->input->post('reqMode');
		$reqId= $this->input->post('reqId');
		$reqSatuanKerjaId= $this->input->post('reqSatuanKerjaId');
		$reqAreaName= $this->input->post('reqAreaName');

		$simpan= "";
		$set= new VSyncArea();
		if($reqMode == "insert")
		{
			$setdetil= new PersonnelArea();

			if($reqSatuanKerjaId > 0)
			{
				$infodetil= $setdetil->getCountByParamsNextDetil($reqSatuanKerjaId);
				$set->setField("ID", "999".$reqSatuanKerjaId.$infodetil);
			}
			else
			{
				$infodetil= $setdetil->getCountByParamsNextDetil("xxx");
				$set->setField("ID", "777".$infodetil);
			}
			// echo $infodetil;exit();
			
			$set->setField("AREA_CODE", $reqSatuanKerjaId.".".$infodetil);
			$set->setField("AREA_NAME", $reqAreaName);
			if($set->insertDetil())
			{
				$simpan= "1";
			}
		}
		else
		{
			$set= new VSyncArea();
			$set->setField("ID", $reqId);
			$set->setField("AREA_NAME", $reqAreaName);
			if($set->updateDetil())
			{
				$simpan= "1";
			}
		}

		if($simpan == "1")
		{
			echo "-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";
	}
	
	function json() 
	{
		$this->load->model('bio/PersonnelArea');
		$set= new PersonnelArea;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatusIntegrasi= $this->input->get("reqStatusIntegrasi");

		$aColumns = array("NAMA_SINGKAT", "STATUS_INTEGRASI_NAMA", "ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY DEPT_NAME asc, DEPT_NAME asc") || trim(strtoupper($sOrder)) == strtoupper("ORDER BY DEPT_NAME asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY B.DEPT_NAME, A.AREA_NAME";
				 
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
		
		// if (trim($reqSatkerId) !== '')
		// 	$statement .= " AND A.DEPT_CODE = '".$reqSatkerId."'";

		$searchJson = " AND (UPPER(A.NAMA_SINGKAT) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
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
				$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
}

