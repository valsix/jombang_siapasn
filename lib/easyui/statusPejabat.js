function setPejabat(val)
{
	if((parseFloat(val) == parseInt(val)) && !isNaN(val))
	{
		$('#reqStatusPejabat').val("");
	} 
	else 
	{
		$("#reqStatusPejabat").val("BARU");
	}
}


function setPejabatSumpah(val)
{
	if((parseFloat(val) == parseInt(val)) && !isNaN(val))
	{
		$('#reqStatusPejabatSumpah').val("");
	} 
	else 
	{
		$("#reqStatusPejabatSumpah").val("BARU");
	}
}