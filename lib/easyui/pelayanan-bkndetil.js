$(document).ready( function () {
	$('[id^="buttondatasapk"]').click(function(){
		infoid= $(this).attr('id');
		infoid= infoid.replace("buttondatasapk", "");
		buttonframesapk(infoid);
	});


  $('[id^="buttonbtn"]').click(function(){
   
    kirim_data_ke_bkn();
  });


});

function buttonframesapk(infoid) {
    $('[id^="buttonframesapk"]').hide();
   
    var element = document.getElementById("main");
    if ($("#divframepdf").css("visibility") == "hidden" || $('#divframepdf').is(':hidden')) 
    {
      element.classList.remove("m12");
      element.classList.add("m6");
      $('#divframepdf').show();
      // $("#vnewframe").val(infoid);
      labelframesapk= $("#labelvsapk"+infoid).val();
      $("#labelframesapk"+infoid).text("Tutup " + labelframesapk);
      $("#buttonframesapk"+infoid).show();
      $("#infonewimage, #infonewframe").hide();

      vinfoid= infoid.split('___'); 
      var reqNip =$("#"+vinfoid[0]).val();
      // console.log(vinfoid);return false;

      $("#infonewframe").show();
      var infonewframe= $('#infonewframe');
      infourl= vbase_url+'app/loadUrl/sapk/'+vinfoid[1]+'?reqId='+vinfoid[0]+"&reqNip="+reqNip;
      // console.log(infourl);

      vnewframe= $("#vnewframe").val();
      infonewframe.attr("src", infourl);
      if(vnewframe == ""){}
      else
      {
        infonewframe.contentWindow.location.reload();
      }
    }
    else
    {
      labelframesapk= $("#labelvsapk"+infoid).val();
      $("#labelframesapk"+infoid).text(labelframesapk);

      element.classList.remove("m6");
      element.classList.add("m12");
      $('#divframepdf').hide();
      $("#vnewframe").val("");
    }
  }

function kirim_data_ke_bkn(){
var   info= "Apakah Anda Yakin, update data terpilih SIAPASN ke BKN ?";
var   vUrl= $("#reqUrlBkn").val();
var   vinfoid= $("#reqIdField").val();
 var   vinfoidbkn= $("#reqIdBkn").val();
  mbox.custom({
          message: info,
          options: {close_speed: 100},
          buttons: [
          {
            label: 'Ya',
            color: 'green darken-2',
            callback: function() {

              var s_url='bkn/'+vUrl+'/siapasn_bkn?reqRiwayatId='+vinfoid+"&reqBknId="+vinfoidbkn;
              $.ajax({'url': s_url, type: "get",'success': function(data){
                data= JSON.parse(data);
                // console.log(data.code);return false;
                if(data.code == 400)
                {
                  mbox.alert(data.PESAN);
                }
                else
                {
                  mbox.alert('Proses Data', {open_speed: 500}, interval = window.setInterval(function() 
                  {
                    clearInterval(interval);
                    window.location.reload();
                  }, 1000));
                  $(".mbox > .right-align").css({"display": "none"});
                }
                
              }});
              mbox.close();
            }
          },
          {
            label: 'Tidak',
            color: 'grey darken-2',
            callback: function() {
              mbox.close();
            }
          }
          ]
        });

}