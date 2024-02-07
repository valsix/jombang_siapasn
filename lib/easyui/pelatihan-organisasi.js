function addOrganisasiRow(index)
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
	button.innerHTML = 'Organisasi '+infoData+' <a style="cursor:pointer; float:right;" onclick="deleteRowDrawTable(\'dataTableRowPelatihanOrganisasiDinamis\', this, 7)">Hapus Pengalaman <img src="../WEB-INF/images/hapus.png" width="15" height="15" border="0" /></a>'
	+'<input id="reqRowIdPelatihanOrganisasi'+rownum+'" type="hidden" name="reqRowIdPelatihanOrganisasi[]" />';
	cell.appendChild(button);
	row.appendChild(cell);
	tabBody.appendChild(row);
	//end tr
	
	//start tr
	row=document.createElement("TR");
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.innerHTML = 'Nama organisasi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqOrganisasi[]";
	element.id = "reqOrganisasi"+rownum;
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
	button.innerHTML = 'Lokasi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqLokasi[]";
	element.id = "reqLokasi"+rownum;
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
	button.innerHTML = 'Bidang organisasi&nbsp;<img src="../WEB-INF/images/star.png" width="10" height="10">';
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
	element.name = "reqBidangOrganisasiId[]";
	element.id = "reqBidangOrganisasiId"+rownum;
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
	element.name = "reqJabatan[]";
	element.id = "reqJabatan"+rownum;
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
	button.innerHTML = 'mulai ';
	cell.appendChild(button);
	
	var elementAwal= document.createElement("select");
	elementAwal.setAttribute("name", "reqPeriodeAwal[]");
	var option = document.createElement("option");
	$.getJSON("../json/set_tahun_combo_json.php",
	function(data){
		for(i=0;i<data.arrID.length; i++)
		{
			elementAwal.options[i] = new Option(data.arrNama[i], data.arrID[i]);
		}
	});
	elementAwal.setAttribute('id', "reqPeriodeAwal"+rownum);
	//elementAwal.style.width = "145px";
	cell.appendChild(elementAwal);
	
	var button = document.createElement('label');
	button.innerHTML = ' sampai ';
	cell.appendChild(button);
	
	var elementAkhir= document.createElement("select");
	elementAkhir.setAttribute("name", "reqPeriodeAkhir[]");
	var option = document.createElement("option");
	elementAkhir.options[0] = new Option("Belum Dipilih", "");
	/*$.getJSON("../json/set_tahun_combo_json.php",
	function(data){
		for(i=0;i<data.arrID.length; i++)
		{
			elementAkhir.options[i] = new Option(data.arrNama[i], data.arrID[i]);
		}
	});*/
	elementAkhir.setAttribute('id', "reqPeriodeAkhir"+rownum);
	//elementAkhir.style.width = "145px";
	cell.appendChild(elementAkhir);
	
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
	rowCount= rowCount-7;
	
	$('select[id^="reqPeriodeAwal"]').change(function() {
		var id= $(this).attr('id');
		id= id.replace("reqPeriodeAwal", "")
		//alert('--'+id);
		setPeriodeDinamis(id);
	});
		
	$('#reqLokasi'+rowCount+',#reqOrganisasi'+rowCount+',#reqBidangOrganisasiId'+rowCount+',#reqJabatan'+rowCount+',#reqPeriodeAwal'+rowCount+',#reqPeriodeAkhir'+rowCount).validatebox({
		required:true
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
}