<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");
include_once("functions/browser.func.php");

class globalteken
{
	function getsessionuser()
	{
		$CI = &get_instance();

		$sessionloginlevel= $CI->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$sessionloginuser= $CI->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$sessionloginid= $CI->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$sessionloginpegawaiid= $CI->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;

		$sessionsatuankerja= $CI->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$sessionusergroup= $CI->kauth->getInstance()->getIdentity()->USER_GROUP;
		$sessionstatussatuankerjabkd= $CI->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
		$sessionaksesappsimpegid= $CI->kauth->getInstance()->getIdentity()->AKSES_APP_SIMPEG_ID;

		$arrreturn= [];
		$arrreturn["sessionloginlevel"]= $sessionloginlevel;
		$arrreturn["sessionloginuser"]= $sessionloginuser;
		$arrreturn["sessionloginid"]= $sessionloginid;
		$arrreturn["sessionloginpegawaiid"]= $sessionloginpegawaiid;
		$arrreturn["sessionsatuankerja"]= $sessionsatuankerja;
		$arrreturn["sessionusergroup"]= $sessionusergroup;
		$arrreturn["sessionstatussatuankerjabkd"]= $sessionstatussatuankerjabkd;
		$arrreturn["sessionaksesappsimpegid"]= $sessionaksesappsimpegid;
		$arrreturn["sessioninfosepeta"]= $sessioninfosepeta;
		// print_r($arrreturn);exit;
		return $arrreturn;
	}

	function settoken($arrparam)
	{
		// print_r($arrparam);exit;
		$reqJenis= $arrparam["reqJenis"];
		$reqPassphrase= $arrparam["reqPassphrase"];

		$infologdata= $infosimpan= "";
		if($reqPassphrase == "gagal")
		{
			$infologdata= "1";
		}
		// echo $infologdata;exit;

		if($infologdata == "1")
		{
			$arrgetsessionuser= $this->getsessionuser();
			$sessionloginlevel= $arrgetsessionuser["sessionloginlevel"];
			$sessionloginuser= $arrgetsessionuser["sessionloginuser"];
			$sessionloginid= $arrgetsessionuser["sessionloginid"];
			$sessionloginpegawaiid= $arrgetsessionuser["sessionloginpegawaiid"];

			$reqIp= getClientIpEnv();
			$ua=getBrowser();
			$reqUserAgent= $ua['name'] . " " . $ua['version'] . " pada OS ( " .$ua['platform'] . ")";

			$CI = &get_instance();
			$CI->load->model("TekenLog");
			$reqLogKeterangan= "coba log gagal";

			$set_detil= new TekenLog();
			$set_detil->setField("JENIS", $reqJenis);
			$set_detil->setField("IP_ADDRESS", $reqIp);
			$set_detil->setField("USER_AGENT", $reqUserAgent);
			$set_detil->setField("KETERANGAN", $reqLogKeterangan);
			$set_detil->setField("LAST_LEVEL", $sessionloginlevel);
			$set_detil->setField("LAST_USER", $sessionloginuser);
			$set_detil->setField("USER_LOGIN_ID", $sessionloginid);
			$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($sessionloginpegawaiid));
			$set_detil->setField("LAST_DATE", "NOW()");

			if($set_detil->insert())
			{
				// $infosimpan= "1";
			}
		}
		else
		{
			$infosimpan= "1";
		}

		return $infosimpan;
	}
}