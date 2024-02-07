$(document).ready( function () {
	$(".reloadpelayananriwayat").click(function() {
		pelayanankembali= $("#pelayanankembali").val();
		infoid= $("#pelayananid").val();
		infojenis= $("#pelayananjenis").val();
		inforowid= $("#pelayananrowid").val();
		document.location.href = "app/loadUrl/persuratan/"+pelayanankembali+"?reqId="+infoid+"&reqJenis="+infojenis+"&reqRowId="+inforowid;
	});
});

function reloadpelayananriwayat(infolink, infokembali)
{
	infopegawaiid= $("#reqPegawaiId").val();
	infoid= $("#reqId").val();
	infojenis= $("#reqJenis").val();
	inforowid= $("#reqRowId").val();
	infolinkurl= "app/loadUrl/app/"+infolink+"?reqId="+infopegawaiid+"&pelayananid="+infoid+"&pelayananjenis="+infojenis+"&pelayananrowid="+inforowid+"&pelayanankembali="+infokembali;
	document.location.href = infolinkurl;
}