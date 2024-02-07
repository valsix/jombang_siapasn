$(document).ready( function () {
	$("#vnewframe").val("");
	$('#divframepdf').hide();
	$('[id^="buttonframepdf"]').click(function(){
		infoid= $(this).attr('id');
		infoid= infoid.replace("buttonframepdf", "");
		buttonframepdf(infoid);
	});

	if(typeof vselectmaterial == "undefined")
	{
		vselectmaterial= "";
	}

	$("#reqCheckAllAtas, #reqCheckAllBawah").change(function() {
		if($(this).prop('checked')) {
			$("#reqCheckAllAtas, #reqCheckAllBawah").prop('checked', true);
			$('input[id^="reqStatusCheckBoxFix"]').each(function(){
				var id= $(this).attr('id');
				id= id.replace("reqStatusCheckBoxFix", "")
				var reqInfoDownload= "";

				$(this).prop('checked', true);
				$(this).removeClass('validatebox-invalid');
				$("#reqInfoChecked"+id).val("1");
			});
		}
		else {
			$('input[id^="reqStatusCheckBoxFix"]').each(function(){
				var id= $(this).attr('id');
				id= id.replace("reqStatusCheckBoxFix", "")
				var reqInfoDownload= "";
				$("#reqCheckAllAtas, #reqCheckAllBawah").prop('checked', false);

				$(this).prop('checked', false);
				$(this).validatebox({required: true});
				$("#reqInfoChecked"+id).val("");
			});
		}

	});
      
	$('input[id^="reqStatusCheckBoxFix"]').click(function () {
		var id= $(this).attr('id');
		id= id.replace("reqStatusCheckBoxFix", "")
		// console.log(id);
		$("#reqInfoChecked"+id).val("");
		if($(this).prop('checked')) 
		{
			$("#reqInfoChecked"+id).val("1");
		}
	});

	function isgambar(vext)
	{
		vreturn= "";
		if(vext == "jpg")
        {
        	vreturn= "1";
        }

        return vreturn;
	}

	// validasi jquery batas file
	$("input[type='file']").on("change", function () {
		// console.log("asd"+this.files[0].size);
		if(this.files[0].size > 2000000) {
			mbox.alert("check file upload harus di bawah 2 MB", {open_speed: 0});
			$(this).val('');
		}

		if (window.parent && window.parent.document)
		{
			if (typeof window.parent.iframeLoaded === 'function')
			{
				parent.iframeLoaded();
			}
		}
	});

	// set foto default
	if(typeof getarrlistpilihfilefield == "undefined"){}
	else
	{
		arrdefaultfoto= getarrlistpilihfilefield["foto"];
		if(Array.isArray(arrdefaultfoto) && arrdefaultfoto.length)
		{
			varrdefaultfoto= arrdefaultfoto.filter(item => item.selected === "selected");
			// console.log(varrdefaultfoto);

			if(Array.isArray(varrdefaultfoto) && varrdefaultfoto.length)
			{
				vurl= varrdefaultfoto[0]["vurl"];
				vext= varrdefaultfoto[0]["ext"];
				if(isgambar(vext) == "1")
		        {
		        	$("#infoimage").attr("src", vurl);
		        }
		    }
		}
	}

	function buttonframepdf(infoid) {
		$('[id^="buttonframepdf"]').hide();
	    // $('[id^="buttonframepdf"]').each(function(){
	    //   vinfoid= $(this).attr('id');
	    //   vinfoid= vinfoid.replace("buttonframepdf", "");

	    //   labelvpdf= $("#labelvpdf"+vinfoid).val();
	    //   $("#labelframepdf"+vinfoid).text(labelvpdf);
	    // });

	    var element = document.getElementById("main");
	    if ($("#divframepdf").css("visibility") == "hidden" || $('#divframepdf').is(':hidden')) {

			reqDokumenIndexId= $("#reqDokumenIndexId"+infoid+" option:selected").val();
			if(typeof getarrlistpilihfilefield == "undefined"){}
			else
			{
				getarrlistpilihfilepegawai= getarrlistpilihfilefield[infoid];
				// console.log(getarrlistpilihfilepegawai);

				if(Array.isArray(getarrlistpilihfilepegawai) && getarrlistpilihfilepegawai.length)
				{
					varrlistpilihfilepegawai= getarrlistpilihfilepegawai.filter(item => item.index === reqDokumenIndexId);
					// console.log(varrlistpilihfilepegawai);

			        vurl= varrlistpilihfilepegawai[0]["vurl"];
			        vext= varrlistpilihfilepegawai[0]["ext"];
			        // vurl= "uploads/8158/AKTA_ANAK_1_2011_198607142005011002.pdf";

			        element.classList.remove("m12");
			        element.classList.add("m6");
			        $('#divframepdf').show();
			        $("#vnewframe").val(infoid);

			        labelvpdf= $("#labelvpdf"+infoid).val();
			        $("#labelframepdf"+infoid).text("Tutup " + labelvpdf);
			        $("#buttonframepdf"+infoid).show();

			        $("#infonewimage, #infonewframe").hide();
			        if(isgambar(vext) == "1")
			        {
			        	$("#infonewimage").show();
			        	$("#infonewimage").attr("src", vurl);
			        	// console.log(varrlistpilihfilepegawai);
			        }
			        else
			        {
			        	$("#infonewframe").show();
			        	var infonewframe= $('#infonewframe');
				        // infourl= '<?=base_url()?>/lib/pdfjs/web/viewer.html?file=../../../'+vurl;
				        infourl= vbase_url+'/lib/pdfjs/web/viewer.html?file=../../../'+vurl;
				        // console.log(infourl);

				        vnewframe= $("#vnewframe").val();
				        infonewframe.attr("src", infourl);
				        if(vnewframe == ""){}
				        else
				        {
				          infonewframe.contentWindow.location.reload();
				        }
			        }
		      	}
		    }
	    }
	    else
	    {
	      labelvpdf= $("#labelvpdf"+infoid).val();
	      $("#labelframepdf"+infoid).text(labelvpdf);

	      // $('[id^="buttonframepdf"]').show();
	      $('[id^="buttonframepdf"]').each(function(){
	        vinfoid= $(this).attr('id');
	        vinfoid= vinfoid.replace("buttonframepdf", "");

	        setdokumenpilih(vinfoid, "");
	      });

	      element.classList.remove("m6");
	      element.classList.add("m12");
	      $('#divframepdf').hide();
	      $("#vnewframe").val("");
	      // $("#labelframepdf"+infoid).text("Lihat");
	    }

	    // khusus foto saja
	    // $("#buttonframepdffoto").hide();

	}

	$('[id^="buttonframepdf"]').each(function(){
		vinfoid= $(this).attr('id');
		vinfoid= vinfoid.replace("buttonframepdf", "");

		setdokumenpilih(vinfoid, "");
	});
	  
	$('[id^="reqDokumenPilih"]').change(function(){
		vinfoid= $(this).attr('id');
		vinfoid= vinfoid.replace("reqDokumenPilih", "");
		setdokumenpilih(vinfoid, "data");
	});

	$('[id^="reqDokumenIndexId"]').change(function(){
		vinfoid= $(this).attr('id');
		vinfoid= vinfoid.replace("reqDokumenIndexId", "");
		setinfonewframe(vinfoid, "data");
	});

	function setdokumenpilih(vinfoid, infomode)
	{
		reqDokumenPilih= $("#reqDokumenPilih"+vinfoid).val();

	    if(infomode == ""){}
	    else
	    {
	    	$("#reqDokumenFileKualitasId"+vinfoid).val("");

	    	if(vselectmaterial == "1")
	    	{
	    		$("#reqDokumenFileKualitasId"+vinfoid).material_select();
	    	}
	    }

	    $("#buttonframepdf"+vinfoid+", #labeldokumenfileupload"+vinfoid+", #labeldokumendarifileupload"+vinfoid).hide();
	    if(reqDokumenPilih == "1")
	    {
	      $("#reqDokumenFileId"+vinfoid).val("");
	      $("#labeldokumenfileupload"+vinfoid).show();

	      var element = document.getElementById("main");
	      element.classList.remove("m6");
	      element.classList.add("m12");
	      $('#divframepdf').hide();
	      $("#vnewframe").val("");

	    }
	    else if(reqDokumenPilih == "2")
	    {
	      $("#labeldokumendarifileupload"+vinfoid).show();

	      // if(vinfoid == "foto"){}
	      // else
	      $("#buttonframepdf"+vinfoid).show();

	      setinfonewframe(vinfoid, infomode);
	    }

	}

	function setinfonewframe(vinfoid, infomode)
	{
	    reqDokumenIndexId= $("#reqDokumenIndexId"+vinfoid).val();

	    infoid= reqDokumenIndexId;
	    // console.log(infoid+"-"+vinfoid);
	    if(typeof getarrlistpilihfilefield == "undefined"){}
		else
		{
		    getarrlistpilihfilepegawai= getarrlistpilihfilefield[vinfoid];
		    // console.log(getarrlistpilihfilepegawai);

		    if(Array.isArray(getarrlistpilihfilepegawai) && getarrlistpilihfilepegawai.length)
		    {

		      	varrlistpilihfilepegawai= getarrlistpilihfilepegawai.filter(item => item.index === infoid);
		      	// console.log(varrlistpilihfilepegawai);

		      	if(Array.isArray(varrlistpilihfilepegawai) && varrlistpilihfilepegawai.length)
		      	{
			        vurl= varrlistpilihfilepegawai[0]["vurl"];
			        vext= varrlistpilihfilepegawai[0]["ext"];
			        reqDokumenFileId= varrlistpilihfilepegawai[0]["id"];
			        reqDokumenFileKualitasId= varrlistpilihfilepegawai[0]["filekualitasid"];
			        // reqDokumenFileRiwayatId= varrlistpilihfilepegawai[0]["inforiwayatid"];

			        // console.log(reqDokumenFileId);
			        if(vurl == ""){}
			        else
			        {
			        	// console.log(varrlistpilihfilepegawai);
						$("#reqDokumenFileId"+vinfoid).val(reqDokumenFileId);
						$("#reqDokumenPath"+vinfoid).val(vurl);
						$("#reqDokumenFileKualitasId"+vinfoid).val(reqDokumenFileKualitasId);
						// $("#reqDokumenFileRiwayatId"+vinfoid).val(reqDokumenFileRiwayatId);

						if(vselectmaterial == "1")
						{
							$("#reqDokumenFileKualitasId"+vinfoid).material_select();
						}

						// console.log(vurl);

						// if(vext == "jpg")
						// {
						// 	$("#infoimage").attr("src", vurl);
						// }
						// else
						// {
							if(infomode == ""){}
							else
							{
								$("#infonewimage, #infonewframe").hide();
						        if(isgambar(vext) == "1")
						        {
						        	$("#infonewimage").show();
						        	$("#infonewimage").attr("src", vurl);
						        	// console.log(varrlistpilihfilepegawai);
						        }
						        else
						        {
						        	$("#infonewframe").show();
						        	var infonewframe= $('#infonewframe');
									// infourl= '<?=base_url()?>/lib/pdfjs/web/viewer.html?file=../../../'+vurl;
									infourl= vbase_url+'/lib/pdfjs/web/viewer.html?file=../../../'+vurl;
									// console.log(infourl);

									vnewframe= $("#vnewframe").val();
									// khusus mode terpilih
									if(vinfoid == vnewframe)
									{
										infonewframe.attr("src", infourl);
										if(vnewframe == ""){}
										else
										{
											infonewframe.contentWindow.location.reload();
										}
									}
						        }

							}
						// }

		        	}
				}

		  	}
		}
	}

	$(".reqfilesimpan").click(function() { 
		if($("#ff").form('validate') == false){
			return false;
		}
		
		// gotombox();

	    mbox.custom({
	      message: "Pastikan sudah melengkapi file dokumen ?",
	      options: {close_speed: 100},
	      buttons: [
	      {
	        label: 'Ya',
	        color: 'green darken-2',
	        callback: function() {
	          $("#reqSubmit").click();
	          mbox.close();
	        }
	      },
	      {
	        label: 'Tidak',
	        color: 'grey darken-2',
	        callback: function() {
	          //console.log('do action for no answer');
	          mbox.close();
	        }
	      }
		]
		});

		gotombox();
				
	});

});

function copytoclipboard(element) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
}

function gotombox()
{
	var $container = $("html,body");
	var $scrollTo = $('.mbox');

	$container.animate({scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop(), scrollLeft: 0},300);
}