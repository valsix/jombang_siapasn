<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class informasi_data_json extends CI_Controller {

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
		$this->USER_LOGIN_ID= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		
	}	
	
	function json() 
	{
		$this->load->model('Informasi');

		$set = new Informasi();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("NAMA", "KETERANGAN", "TANGGAL_AWAL", "TANGGAL_AKHIR", "LINK_URL_INFO", "INFORMASI_ID");
		$aColumnsAlias = array("NAMA", "KETERANGAN", "TANGGAL_AWAL", "TANGGAL_AKHIR", "LINK_URL_INFO", "INFORMASI_ID");
		
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc ") == 0)
					{
						$sOrder .=" asc , ";
					}else
					{
						$sOrder .=" desc , ";
					}
				}
			}
			

			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY NAMA desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY NAMA ASC ";

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
		
		$searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array());
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $searchJson);
		
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $searchJson, $sOrder);     		
		
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
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 5)."...";
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function monitoring_berita()
	{
		$reqId = $this->input->get("reqId");
		$reqInformasiKategori = $this->input->get("reqInformasiKategori");
		$reqTanggalMulai = $this->input->get("reqTanggalMulai");
		$reqTanggalAkhir = $this->input->get("reqTanggalAkhir");
		
		$this->load->model('Informasi');
		$this->load->library("Pagination");
		
		$reqPage = $this->input->post("page");
		$reqShow = $this->input->post("show");
		$reqContent = $this->input->post("content");
				
		$informasi = new Informasi();
		if(isset($reqPage)){
			
			$dsplyStart = !empty($reqPage)?$reqPage:0;
			$dsplyRange = 3;
			
			$statement = "AND STATUS IS NULL ";
			
			
			if($reqTanggalMulai == "" && $reqTanggalAkhir=="")
			{
				$statement .= " AND 1=1";
			}
			else if($reqTanggalMulai=="" && $reqTanggalAkhir!=="")
			{
				$statement .= " AND TANGGAL_AWAL = TO_DATE('".$reqTanggalMulai."', 'DD-MM-YYYY')";
			}
			else if($reqTanggalMulai!=="" && $reqTanggalAkhir=="")
			{
				$statement .= " AND TANGGAL_AKHIR = TO_DATE('".$reqTanggalMulai."', 'DD-MM-YYYY')";
			}
			else if($reqTanggalMulai!=="" && $reqTanggalAkhir!=="")
			{
				$statement .= " AND TANGGAL_AWAL >= TO_DATE('".$reqTanggalMulai."', 'DD-MM-YYYY') AND TANGGAL_AKHIR <= TO_DATE('".$reqTanggalAkhir."', 'DD-MM-YYYY')";
			}
			
			if($reqInformasiKategori==""){}
			else
			{
				$statement .= " AND INFORMASI_KATEGORI_ID = ".$reqInformasiKategori;
			}
			
			//echo $statement;exit;
			
			$sOrder= "ORDER BY A.TANGGAL_AWAL DESC";
			//get rows
			$rowCount = $informasi->getCountByParams(array(), $statement);
			//echo $informasi->query;exit;
			//echo $rowCount;exit;
			$informasi->selectByParams(array(), $dsplyRange, $dsplyStart, $statement, $sOrder);
			//initialize pagination class
			$pagConfig = array('baseURL'=>'informasi_data_json/monitoring_berita/?reqInformasiKategori='.$reqInformasiKategori.'&reqTanggalMulai='.$reqTanggalMulai.'&reqTanggalAkhir='.$reqTanggalAkhir, 'showRecord' => $reqShow, 'totalRows'=>$rowCount, 'currentPage'=>$dsplyStart, 'perPage'=>$dsplyRange, 'contentDiv'=>$reqContent);
			$pagination =  new Pagination($pagConfig);
?>
			<div class="row">
			<?
            while($informasi->nextRow())
            {
            ?>
            <div class="col s12 m12">
                <div class="card white">
                    <div class="card-content black-text">
                        <span class="card-title"><?=$informasi->getField("NAMA")?></span> 
                        <p class="tgl"><?=getFormattedDate($informasi->getField("TANGGAL_AWAL"))?></p>
                        <?=truncate($informasi->getField("KETERANGAN"), 50)?>
                    </div>
                    <div class="card-action">
                        <a href="app/loadUrl/app/informasi_detil?reqId=<?=$informasi->getField("INFORMASI_ID")?>">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?
            }
            ?> 
            </div>

            <div class="row">
                <div class="col s12 m12">
                    <?=$pagination->createLinks()?>
                </div>
            </div>
<?
		}	
	}
	
	function add()
	{
		$this->load->model('Informasi');
		
		$set = new Informasi();
		
		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		$reqNama= $this->input->post("reqNama");
		$reqKeterangan= $_POST["reqKeterangan"]; 
		$reqTanggalAwal= $this->input->post("reqTanggalAwal");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqInformasiKategori= $this->input->post("reqInformasiKategori");
		
		$reqLinkFile = $_FILES['reqLinkFile'];
		$reqLinkFileTemp = $this->input->post('reqLinkFileTemp');
				
		$set->setField('NAMA', $reqNama);
		$set->setField('KETERANGAN', $reqKeterangan);
		$set->setField('INFORMASI_KATEGORI_ID', ValToNullDB($reqInformasiKategori));
		$set->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
		
		$tempSimpan = "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			if($set->insert()){
				$reqId= $set->id;
				$tempSimpan = 1;
			} else {
				$tempSimpan = 0;
			}
		}
		else
		{
			$set->setField('INFORMASI_ID', $reqId); 
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			if($set->update()){
				$tempSimpan=1;
			} else {
				$tempSimpan=0;
			}
		}
		//echo $set->query;exit;
		
		if($tempSimpan==1)
		{
			$FILE_DIR= "uploads/informasi/".$reqId."/";
			if(file_exists($FILE_DIR)){}
			else
			{
				makedirs($FILE_DIR);
			}
			
			if($reqLinkFile['name'] == "")
				$insertLinkFile = $reqLinkFileTemp;
			else			
			{
				$renameFile= substr(md5(date("dmYHis").$reqLinkFile['name']),0,5);
				// $renameFile= $reqLinkFile['name'].substr($renameFile, 0, 5).".".getExtension($reqLinkFile['name']);
				$renameFile= array_shift(explode(".", basename($reqLinkFile['name'])))."_".$renameFile.".".getExtension($reqLinkFile['name']);

				if (move_uploaded_file($reqLinkFile['tmp_name'], $FILE_DIR.$renameFile))
				{
					$insertLinkFile = $FILE_DIR.$renameFile;
					if(file_exists($reqLinkFileTemp))
					{
						unlink($reqLinkFileTemp);
					}
				}
				else
					$insertLinkFile = $reqLinkFileTemp;
			}
			// echo $insertLinkFile;exit();
			$set_upload = new Informasi();
			$set_upload->setField("LINK_FILE", $insertLinkFile);
			$set_upload->setField("LAST_USER", $this->LOGIN_USER);
			$set_upload->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set_upload->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set_upload->setField("LAST_DATE", "NOW()");
			$set_upload->setField('INFORMASI_ID', $reqId);
			$set_upload->uploadFile();	
			// echo $set_upload->query;exit();

			echo $reqId."-Data berhasil disimpan.";
			
		}
		else
		{
				echo "xxx-Data gagal disimpan.";
		}

	}

	function delete()
	{
		$this->load->model('Informasi');
		$set = new Informasi();

		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("INFORMASI_ID", $reqId);

		if($reqMode == "informasi_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		elseif($reqMode == "informasi_1")
		{
			$set->setField("STATUS", ValToNullDB($req));
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}	

		echo json_encode($arrJson);
	}

	function combo() 
	{
		$this->load->model("Informasi");
		$set = new Informasi();

		$set->selectByParams();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("BANK_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA");	
			$i++;
		}
		echo json_encode($arr_json);		

	}	

	function log() 
	{	
		$this->load->model('InformasiLog');

		$set = new InformasiLog();

		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);


		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "INFORMASI_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "INFORMASI_ID");


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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc ") == 0)
					{
						$sOrder .=" asc , ";
					}else
					{
						$sOrder .=" desc , ";
					}
				}
			}
			

			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY INFO_LOG desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY LAST_DATE ASC ";

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
		
		$sOrder= " ORDER BY A.LAST_DATE DESC ";
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array());
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $searchJson, $sOrder);     		
		
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
				if($aColumns[$i] == "LAST_DATE")
					$row[] = getFormattedDateTime($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 5)."...";
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function set_info_total()
	{
		$this->load->model('Informasi');
		$set = new Informasi();
		$reqMode= $this->input->get("reqMode");
		
		// $statement= " AND A.PEGAWAI_ID = ".$reqId;
		$tempJumlah= $set->getCountByParamsUserInformasi($this->USER_LOGIN_ID);
		if($reqMode == "1")
			$tempInfo= $tempJumlah;
		else
		{
			if($tempJumlah > 0)
			$tempInfo= "Anda Punya ".$tempJumlah." Informasi Baru";
		}

		echo $tempInfo;exit;
	}

	function set_info_detil()
	{
		$this->load->model('Informasi');
		$set = new Informasi();

		$tempJumlah= $set->getCountByParamsUserInformasi($this->USER_LOGIN_ID);

		$i=0;
		$statement= " AND B.INFORMASI_ID IS NULL ";
		$infodata="<li class='header'>Anda Punya ".$tempJumlah." Informasi Baru</li><li><ul class='menu'>";
		$set->selectByParamsUserInformasi($this->USER_LOGIN_ID, $statement);
		while($set->nextRow())
		{
			$infodata.="
			<li>
				<a href='app/loadUrl/app/informasi_detil?reqId=".$set->getField("INFORMASI_ID")."' target='mainFrame'>
				<h4 style='font-size:12px'>
					".$set->getField("NAMA")." (belum terbaca)
				</h4>
				<p>Klik Menu Informasi -> Preview</p>
				</a>
			</li>
			";
			$i++;
		}
		$infodata.="</ul></li>";

		if($i > 0)
		echo $infodata;

	}

	function set_konsultasi_total()
	{
		$this->load->model('Mailbox');
		$this->load->model('SatuanKerja');

		$reqMode= $this->input->get("reqMode");

		$set = new Mailbox();
		$set->selectByParamsTerakhirDetil(array(), -1,-1, "", "ORDER BY B.MAILBOX_DETIL_ID ASC");
		$tempJumlah= 0;
		while($set->nextRow())
		{
			$satuanKerjaJawabId= $set->getField("SATUAN_KERJA_ID");
			$satuanKerjaAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");
			$satuanKerjaTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
			$tipeJawabId= $set->getField("TIPE");

			$skerja= new SatuanKerja();
			$reqSatuanKerjaInfoId= $skerja->getSatuanKerja($satuanKerjaJawabId);
			$reqSatuanKerjaInfoId= explode(",", $reqSatuanKerjaInfoId);
			// print_r($reqSatuanKerjaInfoId);exit();
			unset($skerja);

			if (in_array($this->SATUAN_KERJA_ID, $reqSatuanKerjaInfoId) || ($this->SATUAN_KERJA_ID == "" && $tipeJawabId == "2"))
			{
				$tempJumlah++;
			}
			
		}

		if($reqMode == "1")
			$tempInfo= $tempJumlah;
		else
		{
			if($tempJumlah > 0)
			$tempInfo= "Anda Punya ".$tempJumlah." Informasi Baru";
		}

		echo $tempInfo;exit;
	}

	function set_konsultasi_detil()
	{
		$this->load->model('Mailbox');
		$this->load->model('SatuanKerja');

		$reqMode= $this->input->get("reqMode");

		$set = new Mailbox();
		$set->selectByParamsTerakhirDetil(array(), -1,-1, "", "ORDER BY B.MAILBOX_DETIL_ID ASC");
		$tempJumlah= $i= 0;
		$infodatadetil= "";
		while($set->nextRow())
		{
			$satuanKerjaJawabId= $set->getField("SATUAN_KERJA_ID");
			$satuanKerjaAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");
			$satuanKerjaTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
			$tipeJawabId= $set->getField("TIPE");

			$skerja= new SatuanKerja();
			$reqSatuanKerjaInfoId= $skerja->getSatuanKerja($satuanKerjaJawabId);
			$reqSatuanKerjaInfoId= explode(",", $reqSatuanKerjaInfoId);
			// print_r($reqSatuanKerjaInfoId);exit();
			unset($skerja);

			if (in_array($this->SATUAN_KERJA_ID, $reqSatuanKerjaInfoId) || ($this->SATUAN_KERJA_ID == "" && $tipeJawabId == "2"))
			{
				$tempJumlah++;
				$infodatadetil.="
				<li>
					<a href='app/loadUrl/app/konsultasi_detil?reqId=".$set->getField("MAILBOX_ID")."' target='mainFrame'>
					<h4>
						".$set->getField("SUBYEK")."
					</h4>
					<p>Klik Menu Konsultasi</p>
					</a>
				</li>
				";
				$i++;
			}
			 // (".$set->getField("STATUS_NAMA").")
			
		}


		if($i > 0)
		{
			$infodata="<li class='header'>Anda Punya ".$tempJumlah." Pertanyaan Baru</li><li><ul class='menu'>".$infodatadetil;
			$infodata.="</ul></li>";
			echo $infodata;
		}

	}

}
?>