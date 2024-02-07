<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN */
$CI =& get_instance();
$CI->checkUserLogin();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>	
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui-autocomplete/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui-autocomplete/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
	<!-- <div class="area-form"> -->
		<div class="judul-monitoring"><span>Form Jadwal Jam Kerja</span></div>

		

    	<div class="area-form-inner">
		<div class="area-form-konten">

			<div class="area-pegawai-profil-form">
			<div class="foto">
				<img src="app/loadUrl/simpeg/image_script/?reqPegawaiId=6425&reqMode=pegawai" width="60" height="77">

			</div>

			<div class="keterangan"> 
				<div class="nama"><strong>RAHMAT YOYOK PRASETIO</strong></div>

				<div class="nrp">0193022742</div>
				<div class="jabatan">Periode : Dec&nbsp;2020</div>

				<div class="item">Hari Kerja : Belum Dientri</div>
			</div>
		</div>

        <form id="ff" method="post" novalidate enctype="multipart/form-data">
			<div class="heading2">&nbsp;</div>
                <div class="container_calendar">
                    <div>
                        <div class="day" style="border-top-left-radius:18px;">Minggu</div>
                        <div class="day">Senin</div>
                        <div class="day">Selasa</div>
                        <div class="day">Rabu</div>
                        <div class="day">Kamis</div>
                        <div class="day">Jum'at</div>
                        <div class="day" style="border-top-right-radius:18px;">Sabtu</div>
                        <div class="clear"></div>
                    </div>
        
                    <div>
<div class="date">
</div>
<div class="date">
</div>
                                    <div id="2020-12-01" class="date"  style=" background-color:#FFC">
                                    <div class="top">
                                    <div class="date_topright"><strong>1</strong><br>
                                                                        <table>
                                    <tr>
                                      <td>M : </td>
                                      <td>
                                      <select name="reqJamMasuk[]" id="reqJamMasuk1" style="width:75px;">
                                      <option></option>
                                                                            <option value="OFF" >OFF</option>
                                                                            </select>
                                      </td>
                                     </tr>
                                      </table>
                                                                        </div>
                                    </div>
                                    <div class="bottom">
                                    <div class="part"></div>
                                    <div class="part"></div>
                                    <div class="part"></div>
                                    </div>
                                    </div>							
                                                                            <div id="2020-12-02" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>2</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk2" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-03" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>3</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk3" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-04" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>4</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk4" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-05" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>5</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk5" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                        </div>
<div>
                                        <div id="2020-12-06" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>6</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk6" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-07" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>7</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk7" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-08" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>8</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk8" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-09" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>9</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk9" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-10" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>10</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk10" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-11" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>11</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk11" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-12" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>12</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk12" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                        </div>
<div>
                                        <div id="2020-12-13" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>13</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk13" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-14" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>14</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk14" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-15" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>15</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk15" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-16" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>16</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk16" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-17" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>17</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk17" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-18" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>18</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk18" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-19" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>19</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk19" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                        </div>
<div>
                                        <div id="2020-12-20" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>20</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk20" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-21" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>21</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk21" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-22" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>22</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk22" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-23" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>23</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk23" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-24" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>24</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk24" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-25" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>25</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk25" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-26" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>26</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk26" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                        </div>
<div>
                                        <div id="2020-12-27" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>27</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk27" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-28" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>28</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk28" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-29" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>29</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk29" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-30" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>30</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk30" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                                                                <div id="2020-12-31" class="date"   style=" background-color:#FFC">
                                        <div class="top">
                                                                                <div class="date_topright"><strong>31</strong><br>
                                                                                      <table>
                                            <tr>
                                              <td>M :</td>
                                              <td>
                                              <select name="reqJamMasuk[]" id="reqJamMasuk31" style="width:75px;">
                                              <option></option>
                                                                                            <option value="OFF" >OFF</option>
                                                                                            
                                                                                            </select>
                                              </td>
                                             </tr>
                                             
                                              </table>                    
                                                                                  </div>
                                        </div>
                                        <div class="bottom">
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        <div class="part"></div>
                                        </div>
                                        </div>							
                                        <div class="date">
</div>
<div class="date">
</div>
</div>
<div>
<div class="date"style="border-bottom-left-radius:18px;">
</div>
<div class="date">
</div>
<div class="date">
</div>
<div class="date">
</div>
<div class="date">
</div>
<div class="date">
</div>
<div class="date"style="border-bottom-right-radius:18px;">
</div>
</div>
                    
                    <br>&nbsp;<br>
                                            <!-- <p>*Mohon maaf tidak bisa entri shift lewat bulan</p> -->
                      
                    <div class="area-tombol-bawah">
                        <input type="hidden" name="reqId" value="6425">
                        <input type="hidden" name="reqPeriode" value="122020">
                        <input type="submit" value="Submit">
                    </div>                   
                    
                </div>
		</form>
		</div>
		</div>

	</div>
    
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
</body>
</html>