<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");
include_once("functions/encrypt.func.php");

class Gapi
{
	function generatetokenauth()
	{
		$ci=& get_instance();
		$sapkuserlogin= $ci->config->config["sapkuserlogin"];
		$sapkuserpass= $ci->config->config["sapkuserpass"];
		$sapkurl= $ci->config->config["sapkurl"];
		$sapkfield= $ci->config->config["sapkfield"];

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sapkurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("cache-control: no-cache",'Content-Type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sapkfield);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        $result= json_decode($response, 1);
        // print_r($result);exit;
        curl_close($ch);

        return $result['access_token'];
	}

	public function generatetokenauthichation(){
		$ci=& get_instance();
		$login= $ci->config->config["apimwsuserlogin"];
		$password= $ci->config->config["apimwsuserpass"];
		$apimwsurl= $ci->config->config["apimwsurl"];
		$apimwsfield= $ci->config->config["apimwsfield"];

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apimwsurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("cache-control: no-cache",'Content-Type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $apimwsfield);
		curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // var_dump($response);
        $result= json_decode($response, 1);
        // print_r($result);exit;
        curl_close($ch);

        return $result['access_token'];
	}

	function getdata($arrparam)
	{
		$ci=& get_instance();
		$urlpns= $ci->config->config["urlpns"]."".$arrparam["vjenis"]."/".$arrparam["nip"];
		// echo $urlpns;exit;

		$generatetokenauth= $this->generatetokenauth();
		$generatetokenauthichation= $this->generatetokenauthichation();

		$headers= [
		  	// 'User-Agent: NoBrowser v0.1 beta',
     //        'accept:application/json',
            'Auth:bearer ' . $generatetokenauth,
            'Authorization:Bearer ' . $generatetokenauthichation
            // , 'Content-Type:none'
        ];
        // print_r($headers);exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlpns);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        // print_r($response);exit;
        $rs= json_decode($response);
        curl_close($ch);

        if($arrparam["lihatdata"] == "1")
        {
        	print_r($rs->data);exit;
        }
        return $rs->data;

        /*$arrreturn= [];
		if ($rs) {
			foreach ($rs->data as $row)
			{
				$arrdata= [];
				foreach($row as $key=>$val)
				{
					$arrdata[$key] = $val;
				}
				array_push($arrreturn, $arrdata);
			}
			// print_r($arrreturn);exit;
		}
		return $arrreturn;*/
	}

	function postdata($arrparam,$jsonPost)
	{
		$ci=& get_instance();
		$urlpns= $ci->config->config["urlsapi"]."".$arrparam["ctrl"];
		// echo $urlpns;exit;

		$generatetokenauth= $this->generatetokenauth();
		$generatetokenauthichation= $this->generatetokenauthichation();

		$headers= [
		  	// 'User-Agent: NoBrowser v0.1 beta',
     	  'accept:application/json',
            'Auth:bearer ' . $generatetokenauth,
            'Authorization:Bearer ' . $generatetokenauthichation
        , 'Content-Type:application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlpns);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPost);

        $response= curl_exec($ch);
        $rs= json_decode($response);

        curl_close($ch);
         // print_r($rs);exit;
        // var_dump( $rs);exit;
        return $rs;

       
	}

	function getdataParam($arrparam)
	{
		$ci=& get_instance();
		$urlpns= $ci->config->config["urlsapi"]."".$arrparam["ctrl"]."/".$arrparam["value"];
		// echo $urlpns;exit;

		$generatetokenauth= $this->generatetokenauth();
		$generatetokenauthichation= $this->generatetokenauthichation();

		$headers= [
		  	'User-Agent: NoBrowser v0.1 beta',
            'accept:application/json',
            'Auth:bearer ' . $generatetokenauth,
            'Authorization:Bearer ' . $generatetokenauthichation,
            'Content-Type:none'
        ];
        // print_r($headers);exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlpns);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        $rs= json_decode($response);
        curl_close($ch);

        if($arrparam["lihatdata"] == "1")
        {
        	print_r($rs->data);exit;
        }
        return $rs->data;
        
        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlpns);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        // print_r($response);exit;
        $rs= json_decode($response);
         curl_close($ch);
        // print_r($result);exit;
      
        if($arrparam["lihatdata"] == "1")
        {
        	print_r($rs->data);exit;
        }
        return $rs->data;*/

	}

	function getdataRequest($arrparam,$reqParam='')
	{
		$ci=& get_instance();
		$urlpns= $ci->config->config["urlsapi"]."".$arrparam["ctrl"].$reqParam;
		// echo $urlpns;exit;

		$generatetokenauth= $this->generatetokenauth();
		$generatetokenauthichation= $this->generatetokenauthichation();

		$headers= [
		  	'User-Agent: NoBrowser v0.1 beta',
            'accept:application/json',
            'Auth:bearer ' . $generatetokenauth,
            'Authorization:Bearer ' . $generatetokenauthichation,
            'Content-Type:none'
        ];
        // print_r($headers);exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlpns);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        // print_r($response);exit;
        $result= json_decode($response, 1);
        // print_r($result);exit;
        return $result;
	}

    function getdatadownload($arrparam)
    {
        $ci=& get_instance();
        $urlpns= $ci->config->config["urlsapi"]."".$arrparam["detil"];
        // echo $urlpns;exit;

        $generatetokenauth= $this->generatetokenauth();
        $generatetokenauthichation= $this->generatetokenauthichation();

        $headers= [
            // 'User-Agent: NoBrowser v0.1 beta',
            // 'accept:application/json',
            'Auth:bearer ' . $generatetokenauth,
            'Authorization:Bearer ' . $generatetokenauthichation,
            // 'Content-Type:application/pdf'
        ];
        // print_r($headers);exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlpns);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response= curl_exec($ch);
        
        /*$buffer = curl_exec($ch);
        header('Cache-Control: public'); 
        header('Content-type: application/pdf');
        // header('Content-Disposition: attachment; filename="new.pdf"');
        header('Content-Length: '.strlen($buffer));
        echo $buffer;*/
        // print_r($response);exit;
        // $result= json_decode($response, 1);
        // print_r($result);exit;

        return urlencode($response);
    }

	function enkripdekripkunci()
	{
		return "KuNc1";
	}

	function enkripdata($arrparam)
	{
		$reqdata= urldecode($arrparam["reqdata"]);
		$reqkunci= urldecode($arrparam["reqkunci"]);

		return mencrypt($reqdata, $reqkunci);
	}

	function dekripdata($arrparam)
	{
		$reqdata= urldecode($arrparam["reqdata"]);
		$reqkunci= urldecode($arrparam["reqkunci"]);

		return mdecrypt($reqdata, $reqkunci);
	}

}