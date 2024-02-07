var ajaxserverselectsingle = function() {
    var cekreturn = function(checkvaldata) {
        valreturn= true;
        if(checkvaldata == "1")
            valreturn= false;

        return valreturn;
    };

    var initdynamistable = function(valtableid, valjsonurl, valarrdata, valgroup) {

        if(typeof ajaxrowdetil == "undefined")
        {
            ajaxrowdetil= "";
        }
        // console.log(ajaxrowdetil);

        if(typeof vurlrowdetil == "undefined")
        {
            vurlrowdetil= "";
        }

        if(typeof datadefaultorder == "undefined")
        {
            datadefaultorder= 2;
        }
        infodefaultorder= datadefaultorder;
        // console.log(infodefaultorder);

        if(typeof datastateduration == "undefined")
        {
            // Set state duration to 1 day
            datastateduration= 60 * 60 * 24;
        }
        infostateduration= datastateduration;

        infocolumnsdef= [];
        infocolumns= [];
        infotargets= [];
        valarrdata.forEach(function (item, index) {
            infofield= item["field"];
            infodisplay= item["display"];

            infocolumnsdef.push(infofield);

            setdisplay= true;
            if(infodisplay == "1")
            {
                infotargets.push(index);
                setdisplay= false;
            }

            var infodetil= {};
            infodetil.data= infofield;
            infodetil.visible= setdisplay;

            if(index == 0 && ajaxrowdetil == "1")
            {
                infodetil= {};
                infodetil.className= 'dt-control';
                infodetil.orderable= false;
                infodetil.data= null;
                infodetil.visible= setdisplay;
                infodetil.defaultContent= '';
            }

            infocolumns.push(infodetil);

        });
        infogroupfield= valarrdata[0]["field"];
        // console.log(valarrdata[0]["field"]);
        // console.log(infocolumns);
        // console.log(infotargets);
        // console.log(infocolumnsdef);

        var valorderdefault= valarrdata.length - infodefaultorder;
        var table; var groupColumn = valorderdefault;
        var collapsedGroups = {};
        datanewtable= $('#'+valtableid);

        if(typeof datainforesponsive == "undefined")
        {
            datainforesponsive= "";
        }
        inforesponsive= cekreturn(datainforesponsive);

        if(typeof datainfostatesave == "undefined")
        {
            // datainfostatesave= "1";
            datainfostatesave= "";
        }
        infostatesave= cekreturn(datainfostatesave);

        if(typeof carijenis == "undefined" || carijenis == "")
        {
            carijenis= "1";
        }

        if(typeof datainfoscrollx == "undefined")
        {
            datainfoscrollx= "";
        }
        infoscrollx= cekreturn(datainfoscrollx);

        if(typeof datapagelength == "undefined")
        {
            pagelength= 25;
        }
        else
        {
            pagelength= datapagelength;
        }
        pagelength= parseFloat(pagelength);

        if(typeof datalengthmenu == "undefined")
        {
            lengthmenu= [[25, 50, -1],[25, 50, 'All'],];
        }
        else
        {
            lengthmenu= datalengthmenu;
        }

        // pagelength= parseInt(pagelength);
        // console.log(pagelength);

        if(typeof datainfofilter == "undefined")
        {
            datainfofilter= "";
        }
        infofilter= cekreturn(datainfofilter);

        if(typeof datainfosort == "undefined")
        {
            datainfosort= "";
        }
        infosort= cekreturn(datainfosort);

        if(typeof datainfolengthchange == "undefined")
        {
            datainfolengthchange= "";
        }
        infolengthchange= cekreturn(datainfolengthchange);

        if(valgroup == "1")
        {
            // console.log(infogroupfield);
            table= datanewtable.DataTable({
                bLengthChange : false
                // , bInfo : false 
                , pageLength: pagelength
                , responsive: inforesponsive
                , "scrollY": infoscrolly+"vh"
                , "scrollX": infoscrollx
                , "stateSave": infostatesave
                , "stateDuration": infostateduration
                // , responsive: true
                // , searchDelay: 500
                , "pageLength": 25
                , processing: true
                , serverSide: true
                , rowGroup: {
                    className: 'table-group',
                    dataSrc: infogroupfield,
                    startRender: function ( rows, group ) {
                        var collapsed = !!collapsedGroups[group];
                        rows.nodes().each(function (r) {
                            r.style.display = collapsed ? 'none' : '';
                        });
                        return $('<tr/>')
                            .append('<td colspan="'+valarrdata.length+'">' + group + '</td>')
                            // .append('<td colspan="'+valarrdata.length+'">' + group + ' (' + rows.count() + ')</td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                      },
                }
                , order: [[ valorderdefault, "desc" ]]
                , columnDefs: [
                    { className: 'never', targets: infotargets }
                ]

                , ajax: 
                {
                    url: valjsonurl
                    , type: 'POST'
                    , data: {columnsDef: infocolumnsdef},
                }
                , columns: infocolumns
                , "fnDrawCallback": function( oSettings ) {
                    $('#'+infotableid+'_filter input').unbind();
                    $('#'+infotableid+'_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            // carijenis= "1";
                            calltriggercari();
                        }
                    });

                    reloadglobalklikcheck();
                }
                , "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    // console.log(aData);
                    /*var valueStyle= loopIndex= "";
                    valueStyle= nRow % 2;
                    loopIndex= 6;
                    
                    if( aData[7] == '1')
                    {
                        $($(nRow).children()).attr('class', 'hukumanstyle');
                    }
                    else if( aData[7] == '2')
                    {
                        $($(nRow).children()).attr('class', 'hukumanpernahstyle');
                    }*/
                    
                    // $($(nRow).children()).attr('class', 'warnatandamerah');
                }

            });

            $('#'+valtableid+' tbody').on('click', 'tr.dtrg-start', function() {
                var name = $(this).data('name');
                collapsedGroups[name] = !collapsedGroups[name];
                table.draw(false);
            });
        }
        else if(ajaxrowdetil == "1")
        {
            // console.log(infocolumns);
            // console.log(infocolumnsdef);
            // console.log(infotargets);

            /*,
            columnDefs: [
                {
                    targets: 0,
                    data: null,
                    defaultContent: '<button>Click!</button>',
                },
            ],*/

            table= datanewtable.DataTable({
                bLengthChange : false
                , bFilter: infofilter
                , bLengthChange: infolengthchange
                , pageLength: pagelength
                , "scrollY": infoscrolly+"vh"
                , "scrollX": infoscrollx
                , "stateSave": infostatesave
                , "stateDuration": infostateduration
                , responsive: inforesponsive
                , processing: true
                , serverSide: true
                , "pageLength": 25
                , order: []
                , columnDefs: [
                    { className: 'never', targets: infotargets }
                ]
                , ajax: 
                {
                    url: valjsonurl
                    , type: 'POST'
                    , data: {columnsDef: infocolumnsdef},
                }
                , columns: infocolumns
                , "fnDrawCallback": function( oSettings ) {
                    $('#'+infotableid+'_filter input').unbind();
                    $('#'+infotableid+'_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            // carijenis= "1";
                            calltriggercari();
                        }
                    });

                    reloadglobalklikcheck();
                }
                , "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    // console.log(aData);
                    // console.log(valarrdata[infobold]["field"]);
                    color= bold= "";
                    if(typeof infobold == "undefined"){}
                    else
                    {
                        vbold= aData[valarrdata[infobold]["field"]];
                        bold= "bold";
                        if(vbold == "1")
                        {
                            bold= "";
                        }
                    }

                    if(typeof infocolor == "undefined"){}
                    else
                    {
                        vcolor= aData[valarrdata[infocolor]["field"]];
                        if( vcolor == 'Rahasia')
                        {
                            color= "fdd6d6";
                        }
                        else if( vcolor == 'Sangat Segera')
                        {
                            color= "ffeeba";
                        }
                        else if( vcolor == 'Segera')
                        {
                            color= "b4ebff";
                        }
                    }
                    // console.log(bold+"-"+color);

                    $($(nRow).children()).attr('style', 'font-weight:'+bold+'; background-color:#'+color);
                }
            });

            $('#example tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
         
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else
                {
                    // row.child(format(row.data())).show();

                    var vinfodata= row.data();
                    vfieldinfoid= arrdata[indexfieldid]["field"];
                    vidselected= vinfodata[vfieldinfoid];

                    tr.addClass('shown');

                    if(vurlrowdetil == ""){}
                    else
                    {
                        jsonurl= vurlrowdetil+"&reqId="+vidselected;

                        var request = $.get(jsonurl);
                        request.done(function(datajson)
                        {
                            // console.log(datajson);
                            row.child(datajson).show();
                        });

                    }
                }
            });

            function format(d) {
                return (
                    '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                    '<tr>' +
                    '<td>Full name:</td>' +
                    '<td>' +
                    'd.name' +
                    '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Extension number:</td>' +
                    '<td>' +
                    'd.extn' +
                    '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Extra info:</td>' +
                    '<td>And any further details here (images etc)...</td>' +
                    '</tr>' +
                    '</table>'
                );
            }
         
            /*$('#example tbody').on('click', 'button', function () {
                var data = table.row($(this).parents('tr')).data();
                alert(data[0] + "'s salary is: " + data[5]);
            });*/
        }
        else
        {
            // console.log(lengthmenu);
            table= datanewtable.DataTable({
                // bLengthChange : false

                bFilter: infofilter
                , bSort: infosort
                , bLengthChange: infolengthchange

                , lengthMenu: lengthmenu
                // , bInfo : false 
                , pageLength: pagelength
                // , "pageLength": 25
                // , sScrollX: 100,
                // , sScrollXInner: 100
                , "scrollY": infoscrolly+"vh"
                , "scrollX": infoscrollx
                , "stateSave": infostatesave
                , "stateDuration": infostateduration
                , responsive: inforesponsive
                , "pageLength": 25
                // , searchDelay: 500
                , processing: true
                , serverSide: true
                , order: []
                // , order: [[ valorderdefault, "desc" ]]
                , columnDefs: [
                    { className: 'never', targets: infotargets }
                ]
                , ajax: 
                {
                    url: valjsonurl
                    , type: 'POST'
                    , data: {columnsDef: infocolumnsdef},
                }
                , columns: infocolumns
                /*, "initComplete": function (settings) {
                    $('#clicktoggle').trigger('click');
                    console.log("Xxx");
                }*/
                , "fnDrawCallback": function( oSettings ) {
                    $('#'+infotableid+'_filter input').unbind();
                    $('#'+infotableid+'_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            // carijenis= "1";
                            calltriggercari();
                        }
                    });

                    reloadglobalklikcheck();
                }
                , "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    // console.log(aData);
                    // console.log(valarrdata[infobold]["field"]);
                    color= bold= "";
                    if(typeof infobold == "undefined"){}
                    else
                    {
                        vbold= aData[valarrdata[infobold]["field"]];
                        bold= "bold";
                        if(vbold == "1")
                        {
                            bold= "";
                        }
                    }

                    if(typeof infocolor == "undefined"){}
                    else
                    {
                        vcolor= aData[valarrdata[infocolor]["field"]];
                        if( vcolor == 'Rahasia')
                        {
                            color= "fdd6d6";
                        }
                        else if( vcolor == 'Sangat Segera')
                        {
                            color= "ffeeba";
                        }
                        else if( vcolor == 'Segera')
                        {
                            color= "b4ebff";
                        }
                    }
                    // console.log(bold+"-"+color);

                    $($(nRow).children()).attr('style', 'font-weight:'+bold+'; background-color:#'+color);
                }

            });
        }
    };

    return {
        init: function(valtableid, valjsonurl, valarrdata, valgroup) {
            if(typeof valgroup==='undefined' || valgroup===null || valgroup == "") 
            {
                valgroup= "";
            }

            initdynamistable(valtableid, valjsonurl, valarrdata, valgroup);
        },
    };

}();

function reloadglobalklikcheck()
{
    if(typeof infoglobalarrid == "undefined")
    {
        return false;
    }
    
    reqinfoglobalid= String($("#reqGlobalValidasiCheck").val());
    // console.log(reqinfoglobalid);
    arrinfoglobalid= reqinfoglobalid.split(',');

    var i= "";
    if(reqinfoglobalid == ""){}
    else
    {
        infoglobalarrid= arrinfoglobalid;

        for(var i=0; i<infoglobalarrid.length; i++) 
        {
            $("#reqPilihCheck"+infoglobalarrid[i]).prop('checked', true);
            // console.log("#reqPilihCheck"+infoglobalarrid[i]);
        }
    }   
}

function setglobalklikcheck()
{
    if(typeof infoglobalarrid == "undefined")
    {
        return false;
    }

    reqinfoglobalid= String($("#reqGlobalValidasiCheck").val());
    // console.log(reqinfoglobalid);
    arrinfoglobalid= reqinfoglobalid.split(',');

    var i= "";
    if(reqinfoglobalid == ""){}
    else
    {
        infoglobalarrid= arrinfoglobalid;
        i= infoglobalarrid.length - 1;
        i= infoglobalarrid.length;
    }

    reqPilihCheck= reqpilihcheckval= reqNominalBantuan= reqNominalBantuanVal= reqCatatan= reqCatatanVal= "";
    $('input[id^="reqPilihCheck"]:checkbox:checked').each(function(i){
        reqPilihCheck= $(this).val();
        var id= $(this).attr('id');
        id= id.replace("reqPilihCheck", "");

        if(reqpilihcheckval == "")
        {
            reqpilihcheckval= reqPilihCheck;
            // reqNominalBantuanVal= reqNominalBantuan;
            // reqCatatanVal= reqCatatan;
        }
        else
        {
            reqpilihcheckval= reqpilihcheckval+","+reqPilihCheck;
            // reqNominalBantuanVal= reqNominalBantuanVal+","+reqNominalBantuan;
            // reqCatatanVal= reqCatatanVal+"||"+reqCatatan;
        }

        var elementRow= infoglobalarrid.indexOf(reqPilihCheck);
        if(elementRow == -1)
        {
            i= infoglobalarrid.length;

            infoglobalarrid[i]= reqPilihCheck;
        }

    });

    $('input[id^="reqPilihCheck"]:checkbox:not(:checked)').each(function(i){
        reqPilihCheck= $(this).val();
        var id= $(this).attr('id');
        id= id.replace("reqPilihCheck", "");

        var elementRow= infoglobalarrid.indexOf(reqPilihCheck);
        if(parseInt(elementRow) >= 0)
        {
            infoglobalarrid.splice(elementRow, 1);
        }
    });

    reqPilihCheck= reqpilihcheckval= reqNominalBantuan= reqNominalBantuanVal= reqCatatan= reqCatatanVal= "";
    reqTotalNominal= reqTotalOrang= 0;

    for(var i=0; i<infoglobalarrid.length; i++) 
    {
        if(reqpilihcheckval == "")
        {
            reqpilihcheckval= infoglobalarrid[i];
        }
        else
        {
            reqpilihcheckval= reqpilihcheckval+","+infoglobalarrid[i];
        }
    }
    // console.log(reqpilihcheckval);

    $("#reqGlobalValidasiCheck").val(reqpilihcheckval);
    // $("#reqValidasiForm").val(reqPilihCheckForm);
}