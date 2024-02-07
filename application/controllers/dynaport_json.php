<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/dynaport.func.php");

class dynaport_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function cari()
	{
		$reqFieldName= $this->input->post("reqFieldName");
		$reqUrutan= $this->input->post("reqUrutan");
		$reqKondisiField= $this->input->post("reqKondisiField");
		if($reqKondisiField == "") $reqKondisiField= "";
		$reqKondisiOperasi= $this->input->post("reqKondisiOperasi");
		if($reqKondisiOperasi == "") $reqKondisiOperasi= "";
		$reqKondisiValue= $this->input->post("reqKondisiValue");
		if($reqKondisiValue == "") $reqKondisiValue= "";

		$output["reqFieldName"]= $reqFieldName;
		$output["reqUrutan"]= $reqUrutan;
		$output["reqKondisiField"]= $reqKondisiField;
		$output["reqKondisiOperasi"]= $reqKondisiOperasi;
		$output["reqKondisiValue"]= $reqKondisiValue;

		echo json_encode( $output );
		exit();
	}

	function json()
	{
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$this->load->model('Dynaport');
		$this->load->model('SatuanKerja');
		
		$reqInfo= $this->input->get("reqInfo");
		$reqKondisiJson= $this->input->get("reqKondisiJson");
		$reqKondisiJson= json_decode($reqKondisiJson);
		// print_r($reqKondisiJson);exit();
		// print_r($reqKondisiJson->reqFieldName);exit();

		/*

		$reqFieldName= $this->input->post("reqFieldName");
		// print_r($reqFieldName);exit();
		*/

		/*$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");*/
		
		if($reqInfo == "1")
		{
			$aColumns= array("");
			$aColumnsAlias = $aColumns;

			$output = array(
				"sEcho" => 0,
				"iTotalRecords" => 0,
				"iTotalDisplayRecords" => 0,
				"aaData" => array()
			);
			echo json_encode( $output );
			exit();
		}
		else
		{
			$arrField= menufield();
			$arrKondisiField= optionField();
			$arrOperatorKondisiField= operatorField();
			// print_r($arrField);exit();

			// untuk membuat field sesuai kolom yang dipilih
			$jumlah_field= 0;
			for($i= 0; $i < count($reqKondisiJson->reqFieldName); $i++)
			{
				$idField= $reqKondisiJson->reqFieldName[$i];

				$arrayKey= '';
				$arrayKey= in_array_column($idField, "id", $arrField);
				// print_r($arrayKey);exit();
				if($arrayKey == ''){}
	        	else
	        	{
	        		$index_row= $arrayKey[0];
	        		$aColumns[$jumlah_field]= $arrField[$index_row]["field"];
	        		$aColumnsView[$jumlah_field]= $arrField[$index_row]["kondisifield"];
	        		$jumlah_field++;
	        	}
			}
			$aColumnsAlias = $aColumns;
			// print_r($aColumnsAlias);exit();

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
			
			if($reqSatuanKerjaId == "")
			{
				$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
			}
			// echo $reqSatuanKerjaId;exit();

			// "Dinas Pendidikan"
			// $reqSatuanKerjaId=83;

			// "Badan Kepegawaian Daerah, Pendidikan dan Pelatihan"
			// $reqSatuanKerjaId=66;

			// "DINAS PENDIDIKAN"
			// $reqSatuanKerjaId=16;
			// $statement.= " AND A.PEGAWAI_ID = 6317";
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
				else
				{
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					// echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			// echo $statement;exit();

			if($reqStatusPegawaiId == ""){}
			else if($reqStatusPegawaiId == "12")
			{
				$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";
			}
			else if($reqStatusPegawaiId == "hk")
			{
				$statement.= " AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE CURRENT_DATE <= G.TANGGAL_AKHIR AND CURRENT_DATE >= G.TANGGAL_MULAI)";
			}
			else if($reqStatusPegawaiId == "pk")
			{
				$statement.= " AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE CURRENT_DATE >= G.TANGGAL_AKHIR)";
			}
			else if(isStrContain($reqStatusPegawaiId, "spk"))
			{
				$reqStatusPegawaiId= str_replace("spk", "", $reqStatusPegawaiId);
				$statement.= " AND A.PEGAWAI_ID IN (SELECT B.PEGAWAI_ID FROM STATUS_PEGAWAI_KEDUDUKAN A INNER JOIN PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID WHERE A.STATUS_PEGAWAI_KEDUDUKAN_ID = ".$reqStatusPegawaiId.")";
			}
			else
			{
				$statement.= " AND A.STATUS_PEGAWAI_ID = ".$reqStatusPegawaiId;
			}

			// mengkondisikan where sesuai pilihan array
			if($reqKondisiJson->reqKondisiField !== "")
			{
				for($i= 0; $i < count($reqKondisiJson->reqKondisiField); $i++)
				{
					$idField= $reqKondisiJson->reqKondisiField[$i];
					$operatorId= $reqKondisiJson->reqKondisiOperasi[$i];
					$whereField= $reqKondisiJson->reqKondisiValue[$i];
					// echo $idField."-".$operatorField."-".$whereField;

					$fieldKondisi= "";
					$arrayKey= '';
					$arrayKey= in_array_column($idField, "val", $arrKondisiField);
					// print_r($arrayKey);exit();
					if($arrayKey == ''){}
					else
					{
						$index_row= $arrayKey[0];
						$fieldKondisi= $arrKondisiField[$index_row]["kondisifield"];
					}

					$fieldOperator= "";
					$arrayKey= '';
					$arrayKey= in_array_column($operatorId, "val", $arrOperatorKondisiField);
					// print_r($arrayKey);exit();
					if($arrayKey == ''){}
					else
					{
						$index_row= $arrayKey[0];
						$fieldOperator= $arrOperatorKondisiField[$index_row]["kondisifield"];
					}

					// set kondisi where statement
					if($operatorId == "xxx"){}
					elseif($idField == "6")
					{
						$statement.= " AND ".$fieldKondisi." ".$fieldOperator." (SELECT X.AGAMA_ID FROM agama X WHERE X.NAMA = '".$whereField."')";
					}
					elseif($idField == "5")
					{
						$statement.= " AND ".$fieldKondisi." ".$fieldOperator." TO_DATE('".dateToPageCheck($whereField)."','YYYY/MM/DD')";
					}
					elseif($operatorId == "7")
						$statement.= " AND UPPER(".$fieldKondisi.") ".$fieldOperator." '%".strtoupper($whereField)."%'";
					else
						$statement.= " AND ".$fieldKondisi." ".$fieldOperator." '".$whereField."'";

					/*if($reqTanggalPemesananAwal != "" && $reqTanggalPemesananAkhir != "")
						$statement .= " AND A.TANGGAL_PESAN BETWEEN TO_DATE('".dateToPageCheck($reqTanggalPemesananAwal)."','YYYY/MM/DD') AND TO_DATE('".dateToPageCheck($reqTanggalPemesananAkhir)."','YYYY/MM/DD')";
					elseif($reqTanggalPemesananAwal != "")
						$statement .= " AND A.TANGGAL_PESAN = TO_DATE('".dateToPageCheck($reqTanggalPemesananAwal)."','YYYY/MM/DD')";
					elseif($reqTanggalPemesananAkhir != "")
						$statement .= " AND A.TANGGAL_PESAN = TO_DATE('".dateToPageCheck($reqTanggalPemesananAkhir)."','YYYY/MM/DD')";*/
				}
			}
			// echo $statement;exit();
			
			$set = new Dynaport();
			// $_GET['sSearch']= "196402061993071001";
			//echo $statement;exit;
			// $searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_LAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";
			$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
			// echo $allRecord;

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoring(array(), $statement.$searchJson);
			//echo $set->query;exit;
			$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder, $aColumnsView);
			//echo $set->query;exit;
			/* Output */
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
					if($aColumns[$i] == "PANGKAT_RIWAYAT_TMT" || $aColumns[$i] == "TANGGAL_LAHIR")
						$row[] = getFormattedDate($set->getField($aColumns[$i]));
					/*else if($aColumns[$i] == "PEGAWAI_INFO")
					{
						$tempPath= $set->getField("PATH");
						if($tempPath == "")
						$tempPath= "images/foto-profile.jpg";

						$row[] = '<img src="'.$tempPath.'" style="width:60px;height:90px" />';
					}
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");*/
					else
						$row[] = $set->getField($aColumns[$i]);
				}
				
				$output['aaData'][] = $row;
			}
			
			echo json_encode( $output );

		}

	}

}
?>