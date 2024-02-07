<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class bio_integrasi_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->username = $this->kauth->getInstance()->getIdentity()->USERNAME;   
	}	
	
	function departementadd()
	{
		$this->load->model('bio/PersonnelDepartment');
		$this->load->model('bio/LookupData');

		$set= new PersonnelDepartment();
		// $set->selectByParams(array(),-1,-1, " AND A.ID_BIO IS NULL");
		$set->selectByParams(array(), 25, 0, " AND A.ID_BIO IS NULL");
		while($set->nextRow())
		{
			$reqDeptCode= $set->getField("DEPT_CODE");
			$reqDeptName= $set->getField("DEPT_NAME");

			$fields = array
			(
				'dept_code'=> $reqDeptCode,
				'dept_name'=> $reqDeptName
			);

			$set_detil = new LookupData();
			$rs= $set_detil->departementadd($fields, '');
			// print_r($rs);exit;
			// echo $rs["id"]."<br/>";
			$reqIdBio= $rs["id"];

			if($reqIdBio == ""){}
			else
			{
				$set_detil = new PersonnelDepartment();
				$set_detil->setField("DEPT_CODE", $reqDeptCode);
				$set_detil->setField("ID_BIO", $reqIdBio);
				$set_detil->updateIdBio();
			}
		}

		$set= new PersonnelDepartment();
		$reqReturn= $set->getCountByParams(array(), " AND A.ID_BIO IS NULL");
		echo $reqReturn;

	}

	function integrasiawalarea()
	{
		$this->load->model('bio/PersonnelArea');
		$this->load->model('bio/LookupData');

		$set= new PersonnelArea();
		$set->insertAwalAreaIntegrasi();

		$set= new PersonnelArea();
		$set->selectByParams(array(),-1,-1, " AND A.ID_BIO IS NULL");

		while($set->nextRow())
		{
			$reqId= $set->getField("ID");
			$reqAreaCode= $set->getField("AREA_CODE");
			$reqDeptCode= $set->getField("DEPT_CODE");
			$reqAreaName= $set->getField("AREA_NAME");

			$fields = array
			(
				'area_code'=> $reqAreaCode,
				'area_name'=> $reqAreaName
			);

			$set_detil = new LookupData();
			$rs= $set_detil->areaadd($fields, '');
			// print_r($rs);exit;
			// echo $rs["id"]."<br/>";
			$reqIdBio= $rs["id"];

			if($reqIdBio == ""){}
			else
			{
				$set_detil = new PersonnelArea();
				$set_detil->setField("ID", $reqId);
				$set_detil->setField("ID_BIO", $reqIdBio);
				$set_detil->updateIdBio();
			}
		}

		$set= new PersonnelArea();
		$reqReturn= $set->getCountByParams(array(), " AND A.ID_BIO IS NULL");
		echo $reqReturn;

	}

	function areaadd()
	{
		echo "0";
	}

	function integrasiawalpegawai()
	{
		$this->load->model('bio/PersonnelEmployee');
		$this->load->model('bio/LookupData');

		// $set= new PersonnelEmployee();
		// $set->insertAwalPegawaiIntegrasi();

		$set= new PersonnelEmployee();
		$set->selectByParamsIntegrasi(array(),-1,-1, " AND A.ID_BIO IS NULL");
		// $set->selectByParamsIntegrasi(array(),1,0, " AND A.ID_BIO IS NULL");
		while($set->nextRow())
		{
			$reqEmpCode= $set->getField("EMP_CODE");
			$reqFirstName= $set->getField("FIRST_NAME");
			$reqArea= "";
			$reqArea[0]= $set->getField("AREA");
			$reqDepartment= $set->getField("DEPARTMENT");
			$reqDeptCode= $set->getField("DEPT_CODE");
			$reqAreaCode= $set->getField("AREA_CODE");
			// echo $reqArea;exit();

			$fields = array
			(
				'emp_code'=> $reqEmpCode,
				'first_name'=> $reqFirstName,
				'area'=> $reqArea,
				'department'=> $reqDepartment
			);

			$set_detil = new LookupData();
			$rs= $set_detil->employeesadd($fields, '');
			// print_r($rs);exit;
			// echo $rs["id"]."<br/>";
			$reqIdBio= $rs["id"];

			if($reqIdBio == ""){}
			else
			{
				$set_detil = new PersonnelEmployee();
				$set_detil->setField("EMP_CODE", $reqEmpCode);
				$set_detil->setField("ID_BIO", $reqIdBio);
				$set_detil->setField("DEPT_CODE", $reqDeptCode);
				$set_detil->setField("AREA_CODE", $reqAreaCode);
				$set_detil->updateIdBio();
			}
		}

		$set= new PersonnelEmployee();
		$reqReturn= $set->getCountByParams(array(), " AND A.ID_BIO IS NULL");
		echo $reqReturn;

	}

	function json() 
	{
		$this->load->model('bio/LookupData');

		$set = new LookupData();
		$set->selectByParamsTerminal('');
		while($set->nextRow())
		{
			echo $set->getField("id");
		}
		
	}

	function tesadd() 
	{
		$this->load->model('bio/PersonnelDepartment');
		$this->load->model('bio/LookupData');

		$set= new PersonnelDepartment();
		$set->selectByParams(array(),-1,-1, " AND A.ID_BIO IS NULL");
		// $set->selectByParams(array(),1,0, " AND A.ID_BIO IS NULL");
		while($set->nextRow())
		{
			$reqDeptCode= $set->getField("DEPT_CODE");
			$reqDeptName= $set->getField("DEPT_NAME");

			$fields = array
			(
				'dept_code'=> $reqDeptCode,
				'dept_name'=> $reqDeptName
			);

			$set_detil = new LookupData();
			$rs= $set_detil->departementadd($fields, '');
			// print_r($rs);exit;
			// echo $rs["id"]."<br/>";
			$reqIdBio= $rs["id"];

			if($reqIdBio == ""){}
			else
			{
				$set_detil = new PersonnelDepartment();
				$set_detil->setField("DEPT_CODE", $reqDeptCode);
				$set_detil->setField("ID_BIO", $reqIdBio);
				$set_detil->updateIdBio();
			}
			


			// echo $reqDeptCode;exit();
		}
		exit();
		
	}
	

	
}
?>
