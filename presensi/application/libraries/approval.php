<?php
/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author Raahul Seshadri
 *
 */
class approval
{
	/**
	 * The namespace for the session variable and form inputs
	 * @var string
	 */
	private $namespace;
	
	/**
	 * Initializes the session variable name, starts the session if not already so,
	 * and initializes the token
	 * 
	 * @param string $namespace
	 */
	public function __construct($pegawaiId = '', $ijinKoreksiId = '')
	{
		$this->pegawai_id = $pegawaiId;
		$this->ijin_koreksi_id = $ijinKoreksiId;
	}
	
	/**
	 * Return the token from persistent storage
	 * 
	 * @return string
	 */
	public function getToken()
	{
		return $this->readTokenFromStorage();
	}
	
	/**
	 * Verify if supplied token matches the stored token
	 * 
	 * @param string $userToken
	 * @return boolean
	 */
	public function isTokenValid($userToken)
	{
		return ($userToken === $this->readTokenFromStorage());
	}
	
	/**
	 * Echoes the HTML input field with the token, and namespace as the
	 * name of the field
	 */
	
	public function echoInputField()
	{	
	
		$reqPegawaiId = $this->pegawai_id;
		$reqIjinKoreksiId = $this->ijin_koreksi_id;
		
		if($reqIjinKoreksiId == "")
		{}
		else
		{
			$CI =& get_instance();
			$CI->load->model("pegawai");
			$CI->load->model("StaffKosongIntegrasi");
			
			$pegawai_approval = new Pegawai();
			$reqApproval = $pegawai_approval->getAtasanTingkat($reqPegawaiId, $reqIjinKoreksiId);
			
			$arrApproval = explode(';', $reqApproval);
			
			$no =1;
			for($i=0; $i<count($arrApproval); $i++)
			{
				$pegawai = new Pegawai();
				$pegawai->selectByParams(array("A.PEGAWAI_ID"=>$arrApproval[$i]));
				$pegawai->firstRow();
				
				$staff_kosong_integrasi = new StaffKosongIntegrasi();
				
				//$statement = " AND CURRENT_DATE BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR";
				$statement = " AND STATUS_AKTIF = '1'";
				$jumlah_ph = $staff_kosong_integrasi->getCountByParams(array("A.PEGAWAI_ID"=>$arrApproval[$i]), $statement);
				
				if($jumlah_ph > 0)
					$status_ph = " - PH";
				else
					$status_ph = "";
				
				$reqNamaPegawai = $pegawai->getField("NAMA");
				
				echo "<tr id=\"trDataPermohonan".$i."\"><td>Approval ".$no."</td><td>:</td><td><input id=\"reqPegawaiApproval".$i."\" type=\"hidden\" value=\"".$arrApproval[$i]."\">".$reqNamaPegawai." (".$arrApproval[$i].") ".$status_ph."</td></tr>";
				
				$no++;
			}
		}
		
		//echo "<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
	}

	public function getAtasan()
	{	
	
		$reqPegawaiId = $this->pegawai_id;
		// $reqIjinKoreksiId = $this->ijin_koreksi_id;
		$reqIjinKoreksiId = '23';
		
		if($reqIjinKoreksiId == "")
		{}
		else
		{
			$CI =& get_instance();
			$CI->load->model("pegawai");
			$CI->load->model("StaffKosongIntegrasi");
			
			$pegawai_approval = new Pegawai();
			$reqApproval = $pegawai_approval->getAtasanTingkat($reqPegawaiId, $reqIjinKoreksiId);
			
			$arrApproval = explode(';', $reqApproval);
			
			$no =1;
			for($i=0; $i<count($arrApproval); $i++)
			{
				$pegawai = new Pegawai();
				$pegawai->selectByParams(array("A.PEGAWAI_ID"=>$arrApproval[$i]));
				$pegawai->firstRow();
				
				$staff_kosong_integrasi = new StaffKosongIntegrasi();
				
				//$statement = " AND CURRENT_DATE BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR";
				$statement = " AND STATUS_AKTIF = '1'";
				$jumlah_ph = $staff_kosong_integrasi->getCountByParams(array("A.PEGAWAI_ID"=>$arrApproval[$i]), $statement);
				
				if($jumlah_ph > 0)
					$status_ph = " - PH";
				else
					$status_ph = "";
				
				$reqNamaPegawai = $pegawai->getField("NAMA");
				
				$this->atasan = $reqNamaPegawai." (".$arrApproval[$i].") ".$status_ph;
				$this->approvalId = $arrApproval[$i];
				$this->approvalNama = $reqNamaPegawai;
				// echo "<tr id=\"trDataPermohonan".$i."\"><td>Approval ".$no."</td><td>:</td><td><input id=\"reqPegawaiApproval".$i."\" type=\"hidden\" value=\"".$arrApproval[$i]."\">".$reqNamaPegawai." (".$arrApproval[$i].") ".$status_ph."</td></tr>";
				
				$no++;
			}
		}
		
		//echo "<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
	} 
	 
	public function echoInputFieldBak()
	{	
	
		$reqPegawaiId = $this->pegawai_id;
		$reqIjinKoreksiId = $this->ijin_koreksi_id;
		
		if($reqIjinKoreksiId == "")
		{}
		else
		{
			$CI =& get_instance();
			$CI->load->model("pegawai");
			$CI->load->model("PelaksanaHarian");
			
			$pegawai_approval = new Pegawai();
			$reqApproval = $pegawai_approval->getAtasanTingkat($reqPegawaiId, $reqIjinKoreksiId);
			
			$arrApproval = explode(';', $reqApproval);
			
			$no =1;
			for($i=0; $i<count($arrApproval); $i++)
			{
				$pegawai = new Pegawai();
				$pegawai->selectByParams(array("A.PEGAWAI_ID"=>$arrApproval[$i]));
				$pegawai->firstRow();
				
				$pelaksana_harian = new PelaksanaHarian();
				
				$statement = " AND CURRENT_DATE BETWEEN TANGGAL_AWAL AND TANGGAL_AKHIR";
				$jumlah_ph = $pelaksana_harian->getCountByParams(array("A.PEGAWAI_PH_ID"=>$arrApproval[$i]), $statement);
				
				if($jumlah_ph > 0)
					$status_ph = " - PH";
				else
					$status_ph = "";
				
				$reqNamaPegawai = $pegawai->getField("NAMA");
				
				echo "<tr id=\"trDataPermohonan".$i."\"><td>Approval ".$no."</td><td>:</td><td><input id=\"reqPegawaiApproval".$i."\" type=\"hidden\" value=\"".$arrApproval[$i]."\">".$reqNamaPegawai." (".$arrApproval[$i].") ".$status_ph."</td></tr>";
				
				$no++;
			}
		}
		
		//echo "<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
	}
	
	/**
	 * Verifies whether the post token was set, else dies with error
	 */
	public function verifyRequest()
	{
		if (!isTokenValid($_POST[$this->namespace]))
		{
			die("CSRF validation failed.");
		}
	}
	
	/**
	 * Generates a new token value and stores it in persisent storage, or else
	 * does nothing if one already exists in persisent storage
	 */
	private function setToken()
	{
		$storedToken = $this->readTokenFromStorage();
		
		if ($storedToken === '')
		{
			$token = md5(uniqid(rand(), TRUE));
			$this->writeTokenToStorage($token);
		}
	}
	
	/**
	 * Reads token from persistent sotrage
	 * @return string
	 */
	private function readTokenFromStorage()
	{
		if (isset($_SESSION[$this->namespace]))
		{
			return $_SESSION[$this->namespace];
		}
		else
		{
			return '';
		}
	}
	
	/**
	 * Writes token to persistent storage
	 */
	private function writeTokenToStorage($token)
	{
		$_SESSION[$this->namespace] = $token;
	}
}