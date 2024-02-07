<?php
/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author Raahul Seshadri
 *
 */
class approval_lembur
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
				
				echo "<input type=\"text\" id=\"reqApproval1\" name=\"reqApproval1\" calass=\"easyui-validatebox\" value=\"".$reqNamaPegawai." (".$arrApproval[$i].") ".$status_ph."\" style=\"width:30%\" readonly>";
				
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