function addPengalamanKonfirmasiRow(index)
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(index);
	
	var rownum= tabBody.rows.length;
	infoData= (parseInt(rownum) / 10) + 1;
	var maxTotalInfo= 5;
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
	button.innerHTML = 'Pihak yang bisa dihubungi '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowPengalamanKonfirmasiDinamis\', this, 10)">Hapus Pihak yang bisa dihubungi <img src="../WEB-INF/images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowIdPengalamanKonfirmasi'+rownum+'" type="hidden" name="reqRowIdPengalamanKonfirmasi[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Nama&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqArrPihakNama[]";
	element.id = "reqArrPihakNama"+rownum;
	element.style.width = "250px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
			
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Institusi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqArrPerusahaan[]";
	element.id = "reqArrPerusahaan"+rownum;
	element.style.width = "250px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr


	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Alamat&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);

	cell = document.createElement("TD");
	var element = document.createElement("textarea");
	element.type = "text";
	element.name = "reqArrAlamat[]";
	element.id = "reqArrAlamat"+rownum;
	element.cols= 30;
	element.rows= 2;
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
				
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Jabatan&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqArrJabatan[]";
	element.id = "reqArrJabatan"+rownum;
	element.style.width = "250px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
		
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Hubungan dengan Anda&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqArrPihakHubungan[]";
	element.id = "reqArrPihakHubungan"+rownum;
	//element.style.width = "250px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Nomor telpon / Hp yang bisa dihubungi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = ':';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '1. <input name="reqArrPihakTelp[]" class="easyui-validatebox" type="text" id="reqArrPihakTelp'+rownum+'" style="width:120px" />';
	cell.appendChild(button);
	
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '';
	cell.appendChild(button);
	row.appendChild(cell);
	
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = '2. <input name="reqArrPihakHp[]" class="easyui-validatebox" type="text" id="reqArrPihakHp'+rownum+'" style="width:120px" />';
	cell.appendChild(button);
	
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	/*cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqArrPihakTelp[]";
	element.id = "reqArrPihakTelp"+rownum;
	element.style.width = "120px";
	element.className='easyui-validatebox';
	cell.appendChild(element);
	row.appendChild(cell);
	tabBody.appendChild(row);*/
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Email';
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
	element.name = "reqArrEmail[]";
	element.id = "reqArrEmail"+rownum;
	element.style.width = "272px";
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
	rowCount= rowCount-10;
	
	$('#reqArrPihakNama'+rowCount+',#reqArrPerusahaan'+rowCount+',#reqArrAlamat'+rowCount+',#reqArrJabatan'+rowCount+',#reqArrPihakHubungan'+rowCount+',#reqArrPihakTelp'+rowCount).validatebox({
		required:true
	});
	
	$('#reqArrEmail'+rowCount).validatebox({
		validType:['email']
	});
	
	$('#reqArrPihakHp'+rowCount+', #reqArrPihakTelp'+rowCount).keypress(function(e) {
		if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});

}