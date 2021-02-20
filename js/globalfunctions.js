function myGetDate() {
    return moment().format('DD-MM-YYYY');
}

function convertDate(date) {
	return date.split("-").reverse().join("-");
}

function isEmptyString(mystring) {
	return !mystring.trim().length;
}

function extendExpiryDate(expirydate) {
	if (!moment(expirydate, 'DD-MM-YYYY', true).isValid()) {
		actiondate = $('#action_date').val();
		expirydate = moment(actiondate, 'DD-MM-YYYY').subtract(1, 'day').format('DD-MM-YYYY');
	}
  return moment(expirydate, 'DD-MM-YYYY').add(1, 'year').format('DD-MM-YYYY');
}

function sendEmail(mailData) {
	$('#saveActionBtn').button('loading');
	$.ajax({
		type: 'POST',
		url: 'sendEmail.php',
		data: {
			'sendto': [mailData.sendto],
			'sendcc': ccemail,
			'replyto' : replytoemail,
			'replytoname' : replytoname,
			'sendername': sendername,
			'subject': mailData.subject,
			'message': mailData.message
		},
		success: function (result) {
			toastr.success('Email verzonden');
			//data = JSON.parse(result);
			//console.log( typeof(data) );
			//console.log( data.success );
			$('#saveActionBtn').button('reset');
			// button doesn't disable after reset
			// https://stackoverflow.com/questions/10707229/jquery-disable-enable-button-not-working-after-reset
			// workaround:
			setTimeout(function() {
				$('#saveActionBtn').prop("disabled", true);
			},0);
		},
		error: function() {
			toastr.error('Kon email niet verzenden');
			$('#saveActionBtn').button('reset');
		}
	});
}
