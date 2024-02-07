<? 

include_once(APPPATH.'/models/CurlData.php');

class LookupData extends CurlData{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function LookupData()
    {
    	$this->CurlData(); 
    }

    function selectByParamsTerminal($token)
    {
      $token= "Token d20af8cc6ab7dce0b2404c33efb935b644268f21";
    	$this->selectLimit("iclock/api/terminals/", $token);
    }

    function departementadd($fields, $token)
    {
      $token= "Token d20af8cc6ab7dce0b2404c33efb935b644268f21";
      return $this->execQuery("departments/", $fields, $token);
    }

    function areaadd($fields, $token)
    {
      $token= "Token d20af8cc6ab7dce0b2404c33efb935b644268f21";
      return $this->execQuery("areas/", $fields, $token);
    }

    function employeesadd($fields, $token)
    {
      $token= "Token d20af8cc6ab7dce0b2404c33efb935b644268f21";
      return $this->execQuery("employees/", $fields, $token);
    }

} 
?>