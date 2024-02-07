$(function(){
	setValue();
	/*$('#reqAlasanMelamar1').keyup(function(){
		setInfoMaxKarakter('reqAlasanMelamar1', 'charsRemaining1');
	});
	
	$('#reqAlasanMelamar2').keyup(function(){
		setInfoMaxKarakter('reqAlasanMelamar2', 'charsRemaining2');
	});
	
	$('#reqAlasanMelamar3').keyup(function(){
		setInfoMaxKarakter('reqAlasanMelamar3', 'charsRemaining3');
	});
	
	$('#reqKompensasi').keyup(function(){
		setInfoMaxKarakter('reqKompensasi', 'charsRemainingKompensasi');
	});*/

});


function setPeriodeDinamis(id)
{
	var html=reqPeriodeAwal= reqPeriodeAkhir= valId= valNama= tempValAkhir= "";
	reqPeriodeAwal= $("#reqPeriodeAwal"+id).val();
	
	if(reqPeriodeAwal == "")
		reqPeriodeAwal= 'xx';
	
	$("#reqPeriodeAkhir"+id+" :selected").remove(); 
	$("#reqPeriodeAkhir"+id+" option").remove();
	
	$.getJSON("../json/set_tahun_combo_json.php?reqBatasTahun="+reqPeriodeAwal,function(data){
	  tempValAkhir= $("#reqPeriodeAkhirValue"+id).val();
	  reqPeriodeAkhir= tempValAkhir;
	  
	  for(i=0;i<data.arrID.length; i++)
	  {
		valId= data.arrID[i]; valNama= data.arrNama[i];
		
		if(valId == reqPeriodeAkhir)
			$("<option value='" + valId + "' selected='selected'>" + valNama + "</option>").appendTo("#reqPeriodeAkhir"+id);
		else
			$("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#reqPeriodeAkhir"+id);
	  }
	  
	  //alert(reqPeriodeAkhir+'--'+id+':temp');
	  //$("#reqPeriodeAkhir"+id).val(reqPeriodeAkhir);
	});
}
function setValue()
{
	/*setInfoMaxKarakter('reqAlasanMelamar1', 'charsRemaining1');
	setInfoMaxKarakter('reqAlasanMelamar2', 'charsRemaining2');
	setInfoMaxKarakter('reqAlasanMelamar3', 'charsRemaining3');
	setInfoMaxKarakter('reqKompensasi', 'charsRemainingKompensasi');*/
}

function setInfoMaxKarakter(id, idInfo)
{
	var max = parseInt($("#"+id).attr('maxlength'));
	if($("#"+id).val().length > max){
		$("#"+id).val($("#"+id).val().substr(0, $("#"+id).attr('maxlength')));
	}

	$("#"+id).parent().find('.'+idInfo).html('Sisa karakter anda : ' + (max - $("#"+id).val().length));
}

function addPelatihanRow(index)
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(index);
	
	var rownum= tabBody.rows.length;
	var infoData="";
	//infoData= (parseInt(rownum) / 3) + 1;
	//alert(rownum);
	//alert(infoData);

	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	cell.className="judul-grup";
	var button = document.createElement('label');
	button.innerHTML = 'Pelatihan / Sertifikasi '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowPelatihanDinamis\', this, 6)">Hapus Pelatihan <img src="../WEB-INF/images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowIdPelatihan'+rownum+'" type="hidden" name="reqRowIdPelatihan[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Nama pelatihan/sertifikasi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqPelatihan[]";
	element.id = "reqPelatihan"+rownum;
	element.style.width = "200px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
			
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Bidang pelatihan/sertifikasi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqBidang[]";
	element.id = "reqBidang"+rownum;
	element.style.width = "300px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Lembaga penyelenggaraan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqLembaga[]";
	element.id = "reqLembaga"+rownum;
	element.style.width = "350px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr

	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Tanggal sertifikat&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input id="reqTanggalSertifikat'+rownum+'" name="reqTanggalSertifikat[]" class="easyui-datebox" style="width:100px" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	var rowCount = tabBody.rows.length;
	$("#reqArrData").val(rowCount);
	rowCount= rowCount-6;
	
	$('#reqTanggalSertifikat'+rowCount).datebox({
		required:true,
		validType:['date']
	});
	
	$('#reqPelatihan'+rowCount+',#reqBidang'+rowCount+',#reqLembaga'+rowCount).validatebox({
		required:true
	});
}

function deleteRowDrawTable(tableID, id, maxDataHapus) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount= table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	//alert(rowCount);
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			for(var i_index=0; i_index < maxDataHapus; i_index++)
				table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowDrawTablePhp(tableID, id, rowId, reqMode, maxDataHapus) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) 
		{
			var valRowId= $("#"+rowId).val();
			$.getJSON('../json/delete.php?reqMode='+reqMode+'&id='+valRowId, function (data) 
			{
			});
			
			for(var i_index=0; i_index < maxDataHapus; i_index++)
				table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}