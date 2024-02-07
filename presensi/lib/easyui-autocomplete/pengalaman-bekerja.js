function addPengalamanRow(index)
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
	
	button.innerHTML = 'Pengalaman '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowPengalamanDinamis\', this, 13)">Hapus Pengalaman<img src="../WEB-INF/images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowIdPengalamanBekerja'+rownum+'" type="hidden" name="reqRowIdPengalamanBekerja[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Nama Perusahaan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqPengalamanPerusahaan[]";
	element.id = "reqPengalamanPerusahaan"+rownum;
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
	button.innerHTML = 'Jenis perusahaan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var element = document.createElement("select");
	element.className='easyui-validatebox';
	element.setAttribute("name", "reqJenisInstansi[]");
	var option = document.createElement("option");
	element.options[0] = new Option("Belum Dipilih", "");
	element.options[1] = new Option("Swasta Nasional", "Swasta Nasional");
	element.options[2] = new Option("Swasta Asing", "Swasta Asing");
	element.options[3] = new Option("BUMN", "BUMN");
	element.options[4] = new Option("Lain-Lain", "Lain-Lain");
	element.setAttribute('id', "reqJenisInstansi"+rownum);
	//element.style.width = "145px";
	cell.appendChild(element);
	
	var button = document.createElement('label');
	button.innerHTML = '&nbsp;<label><input type="hidden" name="reqJenisInstansiLain[]" id="reqJenisInstansiLain'+rownum+'" style="width:150px" /></label>';
	cell.appendChild(button);
	
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Jumlah Karyawan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var element = document.createElement("select");
	element.className='easyui-validatebox';
	element.setAttribute("name", "reqJumlahKaryawan[]");
	var option = document.createElement("option");
	element.options[0] = new Option("Belum Dipilih", "");
	element.options[1] = new Option("1-10", "1");
	element.options[2] = new Option("10-100", "2");
	element.options[3] = new Option("10-200", "3");
	element.options[4] = new Option(">200", "4");
	element.setAttribute('id', "reqJumlahKaryawan"+rownum);
	//element.style.width = "145px";
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Periode bekerja&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'mulai : '
	+'<input id="reqTahunAwalKerja'+rownum+'" name="reqTahunAwalKerja[]" class="easyui-datebox" style="width:100px" />'
	+'sampai : '
	+'<input id="reqTahunAkhirKerja'+rownum+'" name="reqTahunAkhirKerja[]" class="easyui-datebox" style="width:100px" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Jabatan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10"><br/>Bidang Kerja';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '<input name="reqJabatanAwal[]" class="easyui-validatebox" type="text" id="reqJabatanAwal'+rownum+'" required style="width:250px" />'
    +'<input type="hidden" id="reqJabatanAwalBidang'+rownum+'Id" name="reqJabatanAwalBidangId[]" />'
    +'<select id="reqJabatanAwalBidang'+rownum+'" multiple="multiple"></select>';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Gaji bersih perbulan (sudah termasuk seluruh tunjangan yang diterima)&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">'
	+'<br/><span class="spanItalicKpk">Langsung tuliskan angka tanpa Rp, tanda titik (.) atau koma (,)</span>';
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
	element.name = "reqGajiBersih[]";
	element.id = "reqGajiBersih"+rownum;
	element.className='easyui-validatebox';
	element.setAttribute('style', 'text-align: right;');
	//element.style.width = "10%";
	element.onfocus = function() {  
		FormatAngka("reqGajiBersih"+rownum);
	};
	element.onkeyup = function() {  
		FormatUang("reqGajiBersih"+rownum);
	};
	element.onblur = function() {  
		FormatUang("reqGajiBersih"+rownum);
	};
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = 'Tuliskan maksimal 5 fungsi dan tanggung jawab utama Anda';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	
	var button = document.createElement('label');
	button.innerHTML = '<label>1.</label>';
	cell.appendChild(button);
	
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqTanggungJawab1[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqTanggungJawab1-"+rownum;
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
		
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	
	var button = document.createElement('label');
	button.innerHTML = '<label>2.</label>';
	cell.appendChild(button);
	
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqTanggungJawab2[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqTanggungJawab2-"+rownum;
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	
	var button = document.createElement('label');
	button.innerHTML = '<label>3.</label>';
	cell.appendChild(button);
	
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqTanggungJawab3[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqTanggungJawab3-"+rownum;
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	
	var button = document.createElement('label');
	button.innerHTML = '<label>4.</label>';
	cell.appendChild(button);
	
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqTanggungJawab4[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqTanggungJawab4-"+rownum;
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	cell.vAlign="top";
	
	var button = document.createElement('label');
	button.innerHTML = '<label>5.</label>';
	cell.appendChild(button);
	
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqTanggungJawab5[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqTanggungJawab5-"+rownum;
	element.className='easyui-validatebox';
	cell.appendChild(element);
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
	rowCount= rowCount-13;
	
	$('#reqPengalamanPerusahaan'+rowCount+',#reqJenisInstansi'+rowCount+',#reqJumlahKaryawan'+rowCount+',#reqJabatanAwal'+rowCount+',#reqJabatanAkhir'+rowCount+',#reqGajiBersih'+rowCount).validatebox({
		required:true
	});
	
	function GetTagFillCombo(valTempId) {
		 jQuery.ajax({
			 type: "GET",
			 url: '../json/bidang_organisasi_multi_select.php',
			 data: '',
			 contentType: "application/json; charset=utf-8",
			 dataType: "json",
			 success: function(data){
				FillComboOnSuccess(data, valTempId)
			 },
			 failure: function (response1) {
			 alert(response.d);
			 jQuery("#imgSearchLoading").hide();
		 }
		 });
	}
	
	function FillComboOnSuccess(data, idTemp)
	{
	 var h1 = "";
	 var $select = $("#"+idTemp)
	 
	 for (j = 0; j < data.arrID.length; j++) 
	 {
		 var valId= data.arrID[j];
		 var valNama= data.arrNama[j];
					
		 $opt = $("<option />", {
			 value: valId,
			 text: valNama
		 });
		 $select.append($opt).multipleSelect("refresh");
	 }
	 
	}
	 
	GetTagFillCombo('reqJabatanAwalBidang'+rowCount);
	
	$('select[id^="reqJabatanAwalBidang"]').multipleSelect({
		width: 405,
		multiple: true,
		multipleWidth: 145
	});
	
	$('select[id^="reqJabatanAwalBidang"]').change(function() {
		var tempId= $(this).attr('id');
		var tempValueId= $('#'+tempId).multipleSelect("getSelects")
		$('#'+tempId+"Id").val(tempValueId);
	});

	/*$('#reqJabatanAwalBidangId'+rowCount).combobox({
		required:true,
		filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
		valueField: 'id', textField: 'text',
		url: '../json/bidang_organisasi_combo_json.php',
		onSelect:function(rec){
		},
		validType:['exists[\'#reqJabatanAwalBidangId'+rowCount+'\']']
	});*/
	
	/*$('#reqJabatanAkhirBidangId'+rowCount).combobox({
		required:true,
		filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
		valueField: 'id', textField: 'text',
		url: '../json/bidang_organisasi_combo_json.php',
		onSelect:function(rec){
		},
		validType:['exists[\'#reqJabatanAkhirBidangId'+rowCount+'\']']
	});*/
	
	$('#reqTahunAwalKerja'+rowCount).datebox({
		validType:['date'],
		required:true
	});
	
	$('#reqTahunAkhirKerja'+rowCount).datebox({
		validType:['date',"validPeriodeTanggal['"+rowCount+"']"],
		required:true
	});
	
	$('select[id^="reqJenisInstansi"]').change(function() {
		var idValue= idNama= idIndex= idLain= "";
		idValue= this.value;
		idNama= String(this.id);
		idIndex= idNama.replace("reqJenisInstansi", "");
		idLain= "reqJenisInstansiLain"+idIndex;
		
		if(idValue == "Lain-Lain") 
		{
		   $('#'+idLain).val("");
		   $('#'+idLain).prop('type','text');
		}
		else 
		{
		   $('#'+idLain).prop('type','hidden');
		}
	});
}

function deleteRowDrawTable(tableID, id, maxDataHapus) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount= table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	//alert(tableID+", "+id+", "+maxDataHapus+", "+rowCount);
	//alert(rowCount);
	for(var i=0; i<=rowCount; i++) {
		//alert(id+'-s-'+1);
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