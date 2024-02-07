function checkNan(value)
{
	if(typeof value == "undefined" || isNaN(value) || value == "")
	return 0;
	else
	return value;
}

function round(value, ndec){
    var n = 10;
    for(var i = 1; i < ndec; i++){
        n *=10;
    }

    if(!ndec || ndec <= 0)
        return Math.round(value);
    else
        return Math.round(value * n) / n;
}