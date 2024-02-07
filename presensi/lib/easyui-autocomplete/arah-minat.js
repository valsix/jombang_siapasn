$(function(){
	setValue();
	$('#reqAlasanMelamar1').keyup(function(){
		setInfoMaxKarakter('reqAlasanMelamar1', 'charsRemaining1');
	});
	
	$('#reqAlasanMelamar2').keyup(function(){
		setInfoMaxKarakter('reqAlasanMelamar2', 'charsRemaining2');
	});
	
	$('#reqAlasanMelamar3').keyup(function(){
		setInfoMaxKarakter('reqAlasanMelamar3', 'charsRemaining3');
	});
	
	$('#reqKompensasi').keyup(function(){
		setInfoMaxKarakter('reqKompensasi', 'charsRemainingKompensasi');
	});

});

function setValue()
{
	setIsLamaranKpk();
	setInfoMaxKarakter('reqAlasanMelamar1', 'charsRemaining1');
	setInfoMaxKarakter('reqAlasanMelamar2', 'charsRemaining2');
	setInfoMaxKarakter('reqAlasanMelamar3', 'charsRemaining3');
	setInfoMaxKarakter('reqKompensasi', 'charsRemainingKompensasi');
}

function setInfoMaxKarakter(id, idInfo)
{
	var max = parseInt($("#"+id).attr('maxlength'));
	if($("#"+id).val().length > max){
		$("#"+id).val($("#"+id).val().substr(0, $("#"+id).attr('maxlength')));
	}

	$("#"+id).parent().find('.'+idInfo).html('Sisa karakter anda : ' + (max - $("#"+id).val().length));
}