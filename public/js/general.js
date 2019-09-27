/**
 * [numberWithComma description]
 * @param  {[type]} x [description]
 * @return {[type]}   [description]
 */
function numberWithComma(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

start_price_format();

function start_price_format()
{
	$('.price_format').priceFormat({
	    prefix: '',
	    centsSeparator: '.',
	    thousandsSeparator: ',',
	    clearOnEmpty: true,
	    centsLimit : 0
	});
}