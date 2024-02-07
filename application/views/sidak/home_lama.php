<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$tinggi = 156;
// $tinggi = 580;
$reqSatuanKerjaNama= "Semua Satuan Kerja"
?>
<html>
<head>
</html>
<base href="<?=base_url()?>" />

<div class="app-page-title">
  <div class="container fiori-container">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div> Data Table <div class="page-title-subheading">Contoh Data Table</div>
        </div>
      </div>
      <div class="page-title-actions">
        <div class="d-inline-block dropdown">          
        </div>
      </div>
    </div>
  </div>
</div>
<div class="app-inner-layout app-inner-layout-page">
  <div class="app-inner-layout__wrapper">
    <div class="app-inner-layout__content">
      <div class="tab-content">
        <div class="container fiori-container">
          <div class="main-card mb-3 card">
            <div class="d-block clearfix card-footer">
              <div class="float-left">
                <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                <input type="text" id="reqCariFilter" class="form-control" placeholder="Pencarian"/>
              </div>
              <div class="float-right">
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id='btnAdd'>Add</button>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id='btnEdit'>Edit</button>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id='btnDelete'>Delete</button>
              </div>
            </div>
            <div class="d-block clearfix card-footer">
              <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="clicktoggle" >Show Filter Tree</button>
              
              <br>
              <br>
              <div id="settoggle">
                <select name="select" id="reqStatusPegawaiId" class="form-control">
                    <option value="">Semua</option>
                    <option value="126" selected>CPNS/PNS/PPPK</option>
                    <option value="12">CPNS/PNS</option>
                    <option value="1">CPNS</option>
                    <option value="2">PNS</option>
                    <option value="3">Pensiun</option>
                    <option value="6">PPPK</option>
                    <option value="spk21">Pensiun BUP</option>
                    <option value="spk24">Pensiun Wafat</option>
                    <option value="spk25">Pensiun Tewas</option>
                    <option value="spk27">Pemberhentian Dengan Tidak Hormat</option>
                    <option value="spk28">Mutasi Keluar/Pindah Atas Permintaan Sendiri</option>
                    <option value="hk">Hukuman</option>
                    <option value="pk">Pernah Kena Hukuman</option>
                </select>

                  <table id="tt" class="easyui-treegrid" style="width:100%; height:250px">
                  <thead>
                    <tr>
                      <th field="NAMA" width="90%">Nama</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="table-responsive">
              <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="example" >
                <thead style="background-color: #6770d2;color: white;">
                  <tr>
                    <th class="text-center">foto</th>
                    <th class="text-center">nip</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">gol</th>
                    <th class="text-center">jabatan</th>
                    <th class="text-center">unit</th>
                    <th class="text-center">induk</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function showTree(argument) {
     $("#settoggle").show();
     $("#buttonHideTree").show();
     $("#tree").show();
     $("#clicktoggle").hide();
  }
  function hideTree(argument) {
     $("#settoggle").hide();
     $("#buttonHideTree").hide();
     $("#tree").hide();
     $("#clicktoggle").show();
  }

</script>
  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

  <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
    
    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
  <script type="text/javascript" charset="utf-8">
    var oTable;
    $(document).ready( function () 
    {
      oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 10,
        /* UNTUK MENGHIDE KOLOM ID */
        "aoColumns": [ 
        {sWidth: '60px', className: 'all'},// null,
        {sWidth: '100px', className: 'all'},// null,
        {sWidth: '100px', className: 'all'},
        null,
        null,
        null,
        null
        ],
        "lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
        "bSort":true,
        "bFilter": false,
        "bLengthChange": false,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "pegawai_json/json?reqStatusPegawaiId=126",
        "sScrollX": "100%",                 
        "sScrollXInner": "100%",
        "sPaginationType": "full_numbers",
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        var valueStyle= loopIndex= "";
        valueStyle= nRow % 2;
        loopIndex= 6;       
        if( aData[7] == '1')
        {
          $($(nRow).children()).attr('class', 'hukumanstyle');
        }
        else if( aData[7] == '2')
        {
          $($(nRow).children()).attr('class', 'hukumanpernahstyle');
        }
      }
    });
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
          $(event.target.parentNode.parentNode).addClass('row_selected');

          var anSelected = fnGetSelected(oTable);                         
          anSelectedData = String(oTable.fnGetData(anSelected[0]));
          var element = anSelectedData.split(','); 
          anSelectedId = element[element.length-1];
          // alert(anSelectedData);return false;
        });
    
    $('#example tbody').on( 'dblclick', 'tr', function () {
      $("#btnEdit").click();  
    });
      
    var tempindextab=0;
        $('#btnAdd').on('click', function () {
          location.href ="sidak/index/form";
        });

        $('#btnEdit').on('click', function () {
          if(anSelectedData == "")
            return false;       
          newWindow = window.open("app/loadUrl/app/pegawai_add?reqId="+anSelectedId, 'Cetak'+tempindextab);
          newWindow.focus();
          tempindextab= parseInt(tempindextab) + 1;
          //window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add/?reqId="+anSelectedId);

          // tutup flex dropdown => untuk versi mobile
          $('div.flexmenumobile').hide()
          $('div.flexoverlay').css('display', 'none')
        });

        $('#btnDataPNS').on('click', function () {
          if(anSelectedData == "")
            return false;       
          newWindow = window.open("app/loadUrl/datapns/biodata_detil?reqId="+anSelectedId, 'Cetak'+tempindextab);
          newWindow.focus();
      tempindextab= parseInt(tempindextab) + 1;
          //window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add/?reqId="+anSelectedId);

          // tutup flex dropdown => untuk versi mobile
          $('div.flexmenumobile').hide()
          $('div.flexoverlay').css('display', 'none')
        });

    $("#btnCari").on("click", function () {
      var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
      reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
      reqCariFilter= $("#reqCariFilter").val();
      // reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
      oTable.fnReloadAjax("pegawai_json/json?reqSatuanKerjaId=&reqStatusPegawaiId=126&sSearch="+reqCariFilter);
    });
    
    $("#reqCariFilter").keyup(function(e) {
        setCariInfo();
    });
    
    $("#reqStatusPegawaiId").change(function() { 
      setCariInfo();
    });

    $('#btnDelete').on('click', function () {
          if(anSelectedData == "")
            return false; 
          $.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
            if (r){
              $.getJSON("pegawai_json/delete/?reqId="+anSelectedId,
                function(data){
                  $.messager.alert('Info', data.PESAN, 'info');
                  oTable.fnReloadAjax("pegawai_json/json");
                });

            }
          }); 
        });
    });

    function setCariInfo()
    {
      $(document).ready( function () {
        $("#btnCari").click();      
      });
    }
      
</script>

</head>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

   <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
      $('select').material_select();
    });

    $('.materialize-textarea').trigger('autoresize');

    $(function(){
      var tt = $('#tt').treegrid({
        url: 'satuan_kerja_json/treepilih',
        rownumbers: false,
        pagination: false,
        idField: 'ID',
        treeField: 'NAMA',
        onBeforeLoad: function(row,param){
          if (!row) { // load top level rows
          param.id = 0; // set id=0, indicate to load new page rows
          }
        }
      });
    });

    var outer = document.getElementById('settoggle');
    document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
    if (outer.style.maxHeight){
        //alert('a');
        outer.style.maxHeight = null;
        outer.classList.add('settoggle-closed');
      } 
      else {
        //alert('b');
        outer.style.maxHeight = outer.scrollHeight + 'px';
        outer.classList.remove('settoggle-closed');  
      }
    });

    outer.style.maxHeight = outer.scrollHeight + 'px';
    $('#clicktoggle').trigger('click');
  </script>