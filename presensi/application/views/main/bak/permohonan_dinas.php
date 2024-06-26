<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('main/SatuanKerja');

$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	// $reqBulan= "11";
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

$tinggi = 190;

//$reqSatuanKerjaId = $this->KODE_CABANG;

$statement= " AND A.SATUAN_KERJA_PARENT_ID = 0
AND EXISTS(
SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
)";

$satuankerja = new SatuanKerja();
$satuankerja->selectByParams(array(), -1, -1, $statement);
// echo $satuankerja->query;exit();

$date=$reqTahun."-".$reqBulan;
$totalhari =  getDay(date("Y-m-t",strtotime($date)));
// echo $date."--".$totalhari;exit();

$arrWidth=array("150", "320", "110", "110", "110", "", "120");
$arrData=array("NIP / NAMA", "Satuan Kerja", "Tanggal", "Tanggal Awal", "Tanggal Akhir", "Keterangan", "Status");
// print_r($arrData);exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>">
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
<link href="css/admin.css" rel="stylesheet" type="text/css">

<style type="text/css" media="screen">
    @import "lib/media/css/site_jui.css";
    @import "lib/media/css/demo_table_jui.css";
    @import "lib/media/css/themes/base/jquery-ui.css";
</style>

	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/demo.css">
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.rowGrouping.js"></script>

<script type="text/javascript" charset="utf-8">
	var oTable;
    $(document).ready( function () {
										
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 50,
        	  // "pageLength": 50,
			  /* UNTUK MENGHIDE KOLOM ID */
			  "aoColumns": [
			  <?
			  for($col=0; $col<count($arrData); $col++)
			  {
			  	if($col == 0){}
		  		else
		  			echo ",";
			  ?>
			  		null
			  <?
			  }
			  ?>
			  ],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,
			  "ajax": {	
	            "url": "main/permohonan_json/json_dinas/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
        	  	"type": "POST"
        	  },
			  // columnDefs: [{ className: 'never', targets: [  0 ] }], 
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "scrollX": true,
			  "responsive": false,
			  "sScrollX": "100%",								  
			  "sScrollXInner": "100%",
			  "sPaginationType": "full_numbers"
			});
			// }).rowGrouping({bExpandableGrouping: true});
			/* Click event handler */

			  /* RIGHT CLICK EVENT */
			  var anSelectedData = '';
			  var anSelectedId = '';
			  var anSelectedDownload = '';
			  var anSelectedPosition = '';	
			  			  
			  function fnGetSelected( oTableLocal )
			  {
				  var aReturn = new Array();
				  var aTrs = oTableLocal.fnGetNodes();
				  for ( var i=0 ; i<aTrs.length ; i++ )
				  {
					  if ( $(aTrs[i]).hasClass('row_selected') )
					  {
						  aReturn.push( aTrs[i] );
						  anSelectedPosition = i;
					  }
				  }
				  return aReturn;
			  }
		  
			  $("#example tbody").click(function(event) {
					  $(oTable.fnSettings().aoData).each(function (){
						  $(this.nTr).removeClass('row_selected');
					  });
					  $(event.target.parentNode).addClass('row_selected');
					  //
					  var anSelected = fnGetSelected(oTable);													
					  anSelectedData = String(oTable.fnGetData(anSelected[0]));
					  var element = anSelectedData.split(','); 
					  // anSelectedId = element[0];
					  anSelectedId = element[element.length-1];
			  });

			  $("#btnAdd,#btnEdit").on("click", function () {
			  	var tempId= $(this).attr('id');
			  	var tempLihat= "";
			  	if(tempId == "btnAdd")
			  	{
			  		anSelectedId= "";
			  		tempLihat= 1;
			  	}
			  	else if(tempId == "btnEdit")
			  	{
		  			if(anSelectedId == ""){}
		  			else
		  			{
		  				tempLihat= 1;
		  			}
		  			// console.log(anSelectedId);return false;
		  		}

		  		if(tempLihat == 1)
			  	document.location.href = "app/loadUrl/main/permohonan_dinas_add?reqId="+anSelectedId;

			  	$('div.flexmenumobile').hide()
			  	$('div.flexoverlay').css('display', 'none')

			  });
			  
			  $("#btnCari").on("click", function () {
			  	var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
			  	reqCariFilter= $("#reqCariFilter").val();
			  	reqBulan= $("#reqBulan").val();
			  	reqTahun= $("#reqTahun").val();
			  	// reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
			  	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			  	oTable.fnReloadAjax("main/permohonan_json/json_dinas/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&sSearch="+reqCariFilter);
			  });

			  $("#reqCariFilter").keyup(function(e) {
			  	var code = e.which;
			  	if(code==13)
			  	{
			  		setCariInfo();
			  	}
			  });

			  $("#reqSatuanKerjaId, #reqBulan, #reqTahun").change(function() { 
			  	// var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
			  	// reqCariFilter= $("#reqCariFilter").val();
			  	// reqBulan= $("#reqBulan").val();
			  	// reqTahun= $("#reqTahun").val();
			  	// // reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
			  	// reqSatuanKerjaId= $("#reqSatuanKerjaId").val();

			  	// document.location.href= "app/loadUrl/main/rekapitulasi_absensi/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;
			  	setCariInfo();
			  });

			  $("#reqPermohonan").change(function() { 

			  	var reqPermohonan= $("#reqPermohonan").val();
                if(reqPermohonan !== "")
                {
                	document.location.href = "app/loadUrl/main/permohonan_"+reqPermohonan;
                }
                else
                	document.location.href = "app/loadUrl/main/permohonan";

			  });

			  

               // $(".ui-corner-tl").hide();
			  
		});
		
		function setCariInfo()
		{
			$(document).ready( function () {
			  $("#btnCari").click();      
			});
		}
</script>

    <!--RIGHT CLICK EVENT-->		
    <style>

	.vmenu{
		border:1px solid #aaa;
		position:absolute;
		background:#fff;
		display:none;font-size:0.75em;
	}
	.first_li{}
	.first_li span{
		width:100px;
		display:block;
		padding:5px 10px;
		cursor:pointer
	}
	.inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	.sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	.fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}
	</style>
    
    <link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> 
    <link href="css/begron.css" rel="stylesheet" type="text/css">  
    <link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/dropdowntabs.js"></script>
      

</head>
<body style="overflow:hidden;">
<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman"><span>Permohonan Dinas Luar Kantor</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
				<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>

				<span><a id="btnAdd" title="Tambah" class="btn-tambah"> Tambah</a></span>
				<span><a id="btnEdit" title="Approval" > <img src="images/icon-verifikasi.png" /> Approval</a></span>
				<!-- <span><a id="btnLihat" title="Lihat" class="btn-ubah"> Lihat</a></span>
				<span><a id="btnPrint" title="Cetak" class="btn-hapus"><i class="fa fa-print cetak" aria-hidden="true"></i> Cetak</a></span>
				<span><a id="btnPdf" title="PDF" class="btn-hapus"><i class="fa fa-file-pdf-o pdf" aria-hidden="true"></i> PDF</a></span> -->

            	<!--<span><a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a></span>
                <span><a id="btnEditData" title="Ubah Data"><img src="images/icon-edit.png" /> Ubah Data</a> </span>-->
                <!-- <span><a id="btnSinkronisasi" title="Sinkronisasi"><img src="images/icon-edit.png" /> Sinkronisasi</a> </span> -->
            </li>
        </ul>
    </div>
   <div id="parameter-tambahan" style="width: 80%">
   		Bulan :
        <select name="reqBulan" id="reqBulan">
        <?
        for($i=1; $i<=12; $i++)
        {
            $tempNama=getNameMonth($i);
            $temp=generateZeroDate($i,2);
        ?>
            <option value="<?=$temp?>" <? if($temp == $reqBulan) echo 'selected'?>><?=$tempNama?></option>
        <?
        }
        ?>
        </select>
        Tahun
        <select name="reqTahun" id="reqTahun">
            <? 
            for($i=date("Y")-2; $i < date("Y")+2; $i++)
            {
            ?>
            <option value="<?=$i?>" <? if($i == $reqTahun) echo 'selected'?>><?=$i?></option>
            <?
            }
            ?>
        </select>

   		Satuan Kerja : 
   		<select name="reqSatuanKerjaId" id="reqSatuanKerjaId" style="width: 100px">
   			<option value="">Semua</option>
   			<?
   			while($satuankerja->nextRow())
   			{
   				$id= $satuankerja->getField("SATUAN_KERJA_ID");
   				$nama= $satuankerja->getField("NAMA");
   			?>
   			<option value="<?=$id?>" <? if($reqSatuanKerjaId == $id) echo "selected";?>><?=$nama?></option>
   			<?
   			}
   			?>
   		</select>

   		Permohonan : 
        <select id="reqPermohonan">
          <option value="">Semua</option>         
          <option value="lupa_absen">Lupa Absen</option>
          <option value="dinas" selected>Dinas Luar Kantor</option>
          <!-- <option value="cuti">Cuti</option>      
          <option value="revisi_cuti">Revisi Cuti</option>      
          <option value="ijin_khusus">Ijin Khusus</option>      
          <option value="revisi_ijin_khusus">Revisi Ijin Khusus</option>      
          <?
          if($this->STATUS_ATASAN != 1)
          {}
          else
          {
          ?>
          <option value="lembur">Lembur</option>      
          <?
          }
          ?>
          <option value="on_call">On Call</option>      
          <option value="ganti_shfit">Ganti Shift</option>
          <option value="outstanding_shfit">Outstanding Shift</option>
          <option value="ganti_proyek">Ganti Proyek</option> -->
        </select>

    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display dt-responsive" id="example">
    <thead>
    	<tr>
    		<?
	        for($col=0; $col<count($arrData); $col++)
	        {
	        ?>
	        <th class="th_like" style="<?=$style?>; width: <?=$wd?>px;"><?=$arrData[$col]?></th>
            <?
        	}
            ?>
        </tr>
    </thead>
    </table> 
</div>
</body>
</html>