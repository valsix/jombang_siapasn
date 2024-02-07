<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PangkatRiwayat');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	$statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	$set= new PangkatRiwayat();
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();
	$tempPANGKAT_RIWAYAT_ID = $set->getField('PANGKAT_RIWAYAT_ID');
	$tempGolRuang= $set->getField('PANGKAT_ID');
	$tempTglSTLUD= dateToPageCheck($set->getField('TANGGAL_STLUD'));
	$tempSTLUD= $set->getField('STLUD');
	$tempNoSTLUD			= $set->getField('NO_STLUD');
	$tempNoNota 			= $set->getField('NO_NOTA');
	$tempNoSK 				= $set->getField('NO_SK');
	$tempTh					= $set->getField('MASA_KERJA_TAHUN');
	$tempBl					= $set->getField('MASA_KERJA_BULAN');
	$tempKredit				= $set->getField('KREDIT');
	$tempJenisKP			= $set->getField('JENIS_KP');
	$tempJenisKPNama= $set->getField('NMJENIS');
	$tempKeterangan			= $set->getField('KETERANGAN');
	$tempGajiPokok			= $set->getField('GAJI_POKOK');
	$tempTglNota			= dateToPageCheck($set->getField('TANGGAL_NOTA'));
	$tempTglSK 				= dateToPageCheck($set->getField('TANGGAL_SK'));
	$tempTMTGol 			= dateToPageCheck($set->getField('TMT_PANGKAT'));
	$tempPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$tempPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');
	$tempLastProsesUser= $set->getField('LAST_PROSES_USER');
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="lib/easyui/jquery-2.2.4.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery-2.2.4.min.js"></script>
    
	<?php /*?><script type="text/javascript" src="lib/easyui/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script><?php */?>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
    
    <!-- AUTO KOMPLIT -->
    <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
    <script src="lib/autokomplit/jquery-ui.js"></script>
    
	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'pegawai_json/add',
				onSubmit:function(){
					return false;
					return $(this).form('validate');
				},
				success:function(data){
					$.messager.alert('Info', data, 'info');

					<?
					if($reqMode == "update")
					{
						?>
						// document.location.reload();
						<?	
					}
					else
					{
						?>
						$('#rst_form').click();
						<?
					}
					?>
					top.frames['mainFrame'].location.reload();
				}
			});
			
			$('input[id^="reqPejabatPenetap"]').autocomplete({
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";
								
						if (id.indexOf('reqPejabatPenetap') !== -1)
						{
							var element= id.split('reqPejabatPenetap');
							var indexId= "reqPejabatPenetapId"+element[1];
							urlAjax= "pejabat_penetap_json/combo";
						}
						
						$.ajax({
							url: urlAjax,
							type: "GET",
							dataType: "json",
							data: { term: request.term },
							success: function(responseData){
								if(responseData == null)
								{
									response(null);
								}
								else
								{
									var array = responseData.map(function(element) {
										return {desc: element['desc'], id: element['id'], label: element['label'], statusht: element['statusht']};
									});
									response(array);
								}
							}
						})
					},
					focus: function (event, ui) 
					{ 
						var id= $(this).attr('id');
						if (id.indexOf('reqPejabatPenetap') !== -1)
						{
							var element= id.split('reqPejabatPenetap');
							var indexId= "reqPejabatPenetapId"+element[1];
						}
						
						var statusht= "";
						//statusht= ui.item.statusht;
						$("#"+indexId).val(ui.item.id).trigger('change');
					},
					//minLength:3,
					autoFocus: true
				}).autocomplete( "instance" )._renderItem = function( ul, item ) {
					return $( "<li>" )
				  .append( "<a>" + item.desc + "</a>" )
				  .appendTo( ul );
			};
		});
	</script>
	
    <link rel="stylesheet" type="text/css" href="css/bluetabs.css">
    
    <!-- BOOTSTRAP CORE -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	
</head>

<body class="bg-kanan-full">
	<div id="konten">
		<div id="popup-tabel2">
        	<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    		<ul>
            	<? if($reqMode == ''){
					$read = 'readonly'; $disabled = 'disabled';
				?>
					<li><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqMode=tambah&reqId=<?=$reqId?>"><img src="images/icon-tambah.png" width="15" height="15"/> TAMBAH</a></li>
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-edit.png" width="15" height="15"/> EDIT</a></li>
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-hapus.png" width="15" height="15"/> HAPUS</a></li>        
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-verifikasi.png" width="15" height="15"/> SIMPAN</a></li>
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-x.png" width="15" height="15"/> BATAL</a></li>
				<? }
				elseif($reqMode == 'cancel' && $reqRowId == ''){
					$read = 'readonly'; $disabled = 'disabled';
				?>
					<li><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqMode=tambah&reqId=<?=$reqId?>"><img src="images/icon-tambah.png" width="15" height="15"/> TAMBAH</a></li>
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-edit.png" width="15" height="15"/> EDIT</a></li>
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-hapus.png" width="15" height="15"/> HAPUS</a></li>        
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-verifikasi.png" width="15" height="15"/> SIMPAN</a></li>
					<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-x.png" width="15" height="15"/> BATAL</a></li>
				<? }
				elseif($reqMode == 'view' || $reqMode == 'cancel'){
					$read = 'readonly'; $disabled = 'disabled';
				?>
				<li><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqMode=tambah&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>"><img src="images/icon-tambah.png" width="15" height="15"/> TAMBAH</a></li>
				<li><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqMode=edit&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>"><img src="images/icon-edit.png" width="15" height="15"/> EDIT</a></li>
				<li><a href="javascript:confirmAction('?reqMode=delete&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>', '1')"><img src="images/icon-hapus.png" width="15" height="15"/> HAPUS</a></li>
				<li><a href="javascript:void(0)" class="tidaknyalabutton" ><img src="images/icon-verifikasi.png" width="15" height="15"/> SIMPAN</a></li>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-x.png" width="15" height="15"/> BATAL</a></li>        
				<? }
				elseif($reqMode == 'tambah' || $reqMode == 'edit'){
					$read = ''; $disabled = '';
				?>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-tambah.png" width="15" height="15"/> TAMBAH</a></li>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-edit.png" width="15" height="15"/> EDIT</a></li>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-hapus.png" width="15" height="15"/> HAPUS</a></li>        
				<li><a href="#" onclick="$('#ff').submit();"><img src="images/icon-verifikasi.png" width="15" height="15"/> SIMPAN</a></li>
					<? if($reqMode == 'edit'){?>
						<input type="hidden" name="reqMode" value="update">
					<? }else{?>
						<input type="hidden" name="reqMode" value="insert">
					<? }?>
				<li><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqMode=cancel&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>"><img src="images/icon-x.png" width="15" height="15"/> BATAL</a></li>
				<? }
				elseif($reqMode == 'update' || $reqMode == 'insert'){
					$read = ''; $disabled = '';
				?>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-tambah.png" width="15" height="15"/> TAMBAH</a></li>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-edit.png" width="15" height="15"/> EDIT</a></li>
				<li><a href="javascript:void(0)" class="tidaknyalabutton"><img src="images/icon-hapus.png" width="15" height="15"/> HAPUS</a></li>        
				<li><a href="javascript:void(0)" onclick="$('#ff').submit();"><img src="images/icon-verifikasi.png" width="15" height="15"/> SIMPAN</a></li>
				<input type="hidden" name="reqMode" value="<?=$reqMode?>">
				<li><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqMode=cancel&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>"><img src="images/icon-x.png" width="15" height="15"/> BATAL</a></li>
				<? }
				?>
            </ul>
            </div>
            
            <input type="hidden" name="reqId" value="<?=$reqId?>" />
            <div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
            <table class="table_list" cellspacing="1" width="100%">
                <tr>
                     <td>STLUD</td><td>:</td>
                     <td>
                        <select <?=$disabled?> name="reqSTLUD">
                            <option></option>
                            <option value="1" <? if($tempSTLUD == 1) echo 'selected'?>>Tingkat I</option>
                            <option value="2" <? if($tempSTLUD == 2) echo 'selected'?>>Tingkat II</option>
                            <option value="3" <? if($tempSTLUD == 3) echo 'selected'?>>Tingkat III</option>
                        </select>
                    </td>
                    <td>No. STLUD</td><td>:</td>
                    <td><input type="text" style="width:100px" name="reqNoSTLUD" <?=$read?> value="<?=$tempNoSTLUD?>" /></td>
                    <td>Tgl. STLUD</td><td>:</td><td>
                    <input type="text" style="width:100px" id="reqTglSTLUD" name="reqTglSTLUD" class="dateIna" maxlength="10" onkeydown="return format_date(event,'reqTglSTLUD');" <?=$read?> value="<?=$tempTglSTLUD?>" />
                    </td>
                </tr>
                <tr>           
                    <td width="5%">Gol/Ruang</td><td>:</td>
                    <td>
                        <?php /*?><?
                        $pangkat->selectByParams(array());
                        if($tempJenisKP == "7" || $tempJenisKP == "6")
                        {
                            if($tempAppGroupLevelId == 1)
                            {
                        ?>
                            <select name="reqGolRuang" <?=$disabled?> id="reqGolRuang">
                                <? 
                                while($pangkat->nextRow())
                                {
                                ?>
                                    <option value="<?=$pangkat->getField('PANGKAT_ID')?>" <? if($tempGolRuang == $pangkat->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat->getField('KODE')?></option>
                                <? 
                                }
                                ?>
                            </select>
                        <?
                            }
                            else
                            {
                        ?>
                        <input type="hidden" name="reqGolRuangTemp" value="<?=$tempGolRuang?>" />
                        <input type="hidden" name="reqGolRuang" value="<?=$tempGolRuang?>" />
                        <?=$tempGolRuangNama?>
                        <?
                            }
                        }
                        else
                        {
                        ?>
                        <input type="hidden" name="reqGolRuangTemp" value="<?=$tempGolRuang?>" />
                        <select name="reqGolRuang" <?=$disabled?> id="reqGolRuang">
                            <? 
                            while($pangkat->nextRow())
                            {
                            ?>
                                <option value="<?=$pangkat->getField('PANGKAT_ID')?>" <? if($tempGolRuang == $pangkat->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat->getField('KODE')?></option>
                            <? 
                            }
                            ?>
                        </select>
                        <?
                        }
                        ?><?php */?>
                    </td>
                    <td width="5%">TMT Gol</td><td>:</td>
                    <td>
                        <input type="hidden" id="reqTMTGolLama" name="reqTMTGolLama" value="<?=$tempTMTGol?>" />
                        <input type="text" style="width:80px" id="reqTMTGol" name="reqTMTGol" maxlength="10" class="required dateIna" title="Tmt Gol harus diisi" onkeydown="return format_date(event,'reqTMTGol');" <?=$read?> value="<?=$tempTMTGol?>" />
                    </td>
                </tr>
                <tr>           
                    <td width="5%">No Nota</td><td>:</td>
                    <td>
                        <input type="text" style="width:150px" name="reqNoNota" <?=$read?> value="<?=$tempNoNota?>" class="easyui-validatebox" required />
                    </td>
                    <td width="5%">Tgl Nota</td><td>:</td>
                    <td>
                        <input type="text" style="width:80px" id="reqTglNota" name="reqTglNota" class="dateIna" maxlength="10" onkeydown="return format_date(event,'reqTglNota');" <?=$read?> value="<?=$tempTglNota?>" />
                    </td>			
                </tr>
                <tr>           
                    <td width="5%">No SK</td><td>:</td>
                    <td>
                        <input type="text" style="width:150px" name="reqNoSK" <?=$read?> value="<?=$tempNoSK?>" title="No SK harus diisi" class="required" />
                    </td>
                    <td width="5%">Tgl SK</td><td>:</td>
                    <td>
                        <input type="hidden" id="reqTglSKLama" name="reqTglSKLama" value="<?=$tempTglSK?>" />
                        <input type="text" style="width:80px" id="reqTglSK" name="reqTglSK" <?php /*?>title="Tanggal SK harus diisi"<?php */?> class="required dateIna" maxlength="10" onkeydown="return format_date(event,'reqTglSK');" <?=$read?> value="<?=$tempTglSK?>" />
                    </td>			
                </tr>
                <tr>
                    <td>Pj Penetap</td><td>:</td>
                    <td>
                        <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$tempPejabatPenetapId?>" /> 
                        <input type="text" required style="width:350px" name="reqPejabatPenetap" <?=$read?> id="reqPejabatPenetap" value="<?=$tempPejabatPenetap?>" title="Pejabat Penetap harus diisi" class="autocompletevalidator" />
                    </td>
                    <td>Jenis KP</td><td>:</td>
                    <td>
                        <?
                        if($tempJenisKP == "7" || $tempJenisKP == "6")
                        {
                            if($tempAppGroupLevelId == 1)
                            {
                        ?>
                            <select <?=$disabled?> name="reqJenisKP">
                                <option value="1" <? if($tempJenisKP == 1) echo 'selected'?>>Reguler</option>
                                <option value="2" <? if($tempJenisKP == 2) echo 'selected'?>>Pilihan</option>
                                <option value="3" <? if($tempJenisKP == 3) echo 'selected'?>>Anumerta</option>
                                <option value="4" <? if($tempJenisKP == 4) echo 'selected'?>>Pengabdian</option>
                                <option value="5" <? if($tempJenisKP == 5) echo 'selected'?>>SK lain-lain</option>
                                <option value="6" <? if($tempJenisKP == 6) echo 'selected'?>>CPNS</option>
                                <option value="7" <? if($tempJenisKP == 7) echo 'selected'?>>PNS</option>
                            </select>
                        <?
                            }
                            else
                            {
                        ?>
                        <input type="hidden" name="reqJenisTemp" value="<?=$tempJenisKP?>" />
                        <input type="hidden" name="reqJenisKP" value="<?=$tempJenisKP?>" />
                        <?=$tempJenisKPNama?>
                        <?
                            }
                        }
                        else
                        {
                        ?>
                        <input type="hidden" name="reqJenisTemp" value="<?=$tempJenisKP?>" />
                        <select <?=$disabled?> name="reqJenisKP">
                            <option value="1" <? if($tempJenisKP == 1) echo 'selected'?>>Reguler</option>
                            <option value="2" <? if($tempJenisKP == 2) echo 'selected'?>>Pilihan</option>
                            <option value="3" <? if($tempJenisKP == 3) echo 'selected'?>>Anumerta</option>
                            <option value="4" <? if($tempJenisKP == 4) echo 'selected'?>>Pengabdian</option>
                            <option value="5" <? if($tempJenisKP == 5) echo 'selected'?>>SK lain-lain</option>
                            <?
                            if($reqMode == "tambah"){}
                            else
                            {
                            ?>
                            <option value="6" <? if($tempJenisKP == 6) echo 'selected'?>>CPNS</option>
                            <option value="7" <? if($tempJenisKP == 7) echo 'selected'?>>PNS</option>
                            <?
                            }
                            ?>
                        </select>
                        <?
                        }
                        ?>
                    </td>
                </tr>
                <tr>           
                    <td>Kredit</td><td>:</td>
                    <td><input type="text" style="width:100px" id="reqKredit" name="reqKredit" <?=$read?> value="<?=$tempKredit?>"/></td>
                    <td colspan="2">Masa Kerja</td>
                    <td>
                    	<input type="text" style="width:20px" name="reqTh" <?=$read?> value="<?=$tempTh?>" id="reqTh" title="Masa kerja tahun harus diisi" class="required"/>
                        Th
                        <input type="text" style="width:20px" name="reqBl" <?=$read?> value="<?=$tempBl?>" id="reqBl" title="Masa kerja bulan diisi" class="required"/>
                        Bl
                    </td>
                    <td>Gaji Pokok:</td></td><td>:</td>
                    <td>
                        <input type="text" style="width:150px" name="reqGajiPokok" <?=$read?> value="<?=$tempGajiPokok?>" required id="reqGajiPokok"/>
                    </td>
                </tr>
                <tr>           
                    <td>Keterangan</td><td>:</td>
                    <td>
                    <textarea <?=$disabled?> name="reqKeterangan" cols="30"> <?=$tempKeterangan?></textarea>
                    </td>
                </tr>    
            </table>
        </div>
        </form>
        
		</div>
	</div>
</body>
</html>