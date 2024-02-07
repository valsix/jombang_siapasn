function addPengalamanSuksesRow(index)
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(index);
	
	var rownum= tabBody.rows.length;
	var infoData="";
	infoData= (parseInt(rownum) / 6) + 1;
	var maxTotalInfo= 3;
	if(infoData <= maxTotalInfo){}
	else
	{
		alert("Anda hanya boleh memasukkan "+maxTotalInfo);
		return false;
	}
	//alert(rownum);
	//alert(infoData);
    
	infoData= "";
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	cell.className="judul-grup";
	var button = document.createElement('label');
	button.innerHTML = 'Pengalaman Sukses Anda '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowPengalamanSuksesDinamis\', this, 6)">Hapus Pengalaman <img src="../WEB-INF/images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowIdPengalamanSukses'+rownum+'" type="hidden" name="reqRowIdPengalamanSukses[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	cell.colSpan = 3;
	var button = document.createElement('label');
	button.innerHTML = 'Ceritakan secara singkat dan jelas salah satu pengalaman yang Anda anggap paling sukses selama Anda bekerja';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Situasi yang terjadi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqPengalamanSuksesInstansi[]";
	element.id = "reqPengalamanSuksesInstansi"+rownum;
	element.style.width = "250px";
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
	button.innerHTML = 'Tindakan yang anda lakukan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqPengalamanSuksesTindakan[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqPengalamanSuksesTindakan"+rownum;
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
	button.innerHTML = 'Hasil tindakan Anda&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqPengalamanSuksesHasil[]";
	element.cols="35";
	element.rows="5";
	element.id = "reqPengalamanSuksesHasil"+rownum;
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
	//alert(rowCount);
	$("#reqArrData").val(rowCount);
	rowCount= rowCount-6;

	$('#reqPengalamanSuksesInstansi'+rowCount+',#reqPengalamanSuksesTindakan'+rowCount+',#reqPengalamanSuksesHasil'+rowCount).validatebox({
		required:true
	});
}