<?php
/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author Raahul Seshadri
 *
 */
class approval_shift
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
	public function __construct($pegawaiId = '', $cabangId = '')
	{
		$this->pegawai_id = $pegawaiId;
		$this->cabang_id = $cabangId;
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
		$reqCabangId = $this->cabang_id;
		
			$CI =& get_instance();
			$CI->load->model("pegawai");
			$CI->load->model("Staff");
			
			$pegawai_approval = new Pegawai();
			$reqApproval = $pegawai_approval->getAtasanTingkat($reqPegawaiId, '1');
			
			$arrApproval = explode(';', $reqApproval);
			
			$no =1;
			for($i=0; $i<count($arrApproval); $i++)
			{
				$pegawai = new Pegawai();
				$pegawai->selectByParams(array("A.PEGAWAI_ID"=>$arrApproval[$i]));
				$pegawai->firstRow();
				
				$reqNamaPegawai = $pegawai->getField("NAMA");
				
				echo "<tr id=\"trDataPermohonan".$i."\"><td>Approval ".$no."</td><td>:</td><td>".$reqNamaPegawai." (".$arrApproval[$i].")</td></tr>";
				
				$no++;
			}
			
			/*
			$staff = new Staff();
			$statement = " AND TRIM(NAMA) = 'MANAJER OPERASI' AND STAFF_ID LIKE '".$reqCabangId."%'";
			$staff->selectByParams(array(), -1, -1, $statement);
			$staff->firstRow();
			$reqStaffId = $staff->getField("STAFF_ID");
			
			$pegawai_approval_manajer = new Pegawai();
			$pegawai_approval_manajer->selectByParams(array("A.STAFF_ID" => $reqStaffId));
			
			$pegawai_approval_manajer->firstRow();
			
			$reqPegawaiIdManajer = $pegawai_approval_manajer->getField("PEGAWAI_ID");
			$reqNamaPegawaiManajer = $pegawai_approval_manajer->getField("NAMA");
			
			echo "<tr id=\"trDataPermohonan2\"><td>Approval 2</td><td>:</td><td>".$reqNamaPegawaiManajer." (".$reqPegawaiIdManajer.")</td></tr>";
			*/		
		
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