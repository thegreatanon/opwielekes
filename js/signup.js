$(document).ready(function () {

	$('#kid1birthdatepicker').datetimepicker({
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	$('#kid2birthdatepicker').datetimepicker({
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	$("a.iframe").fancybox({
		'type': 'iframe',
		iframe: {
            preload: false // fixes issue with iframe and IE
    }
	});

	selcity = $('#parenttown').select2({
		placeholder: "Plaatsnaam",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setPostalcode();
	}).on('select2:unselect', function() {
		resetPostalcode();
	});

	loadPostalcodes();
});


function verifyMember() {
	validInput = validateInput();
	//validInput = true;
	console.log('input valid is ' + validInput)
	if (!validInput) {
		toastr.error('Vul alle vereiste velden in.');
		return;
	}
	document.getElementById("sumname").innerHTML = $('#parentfirstname').val() + ' ' + $('#parentlastname').val();
	document.getElementById("sumaddress").innerHTML = $('#parentstreet').val() + ' ' + $('#parentstreetnr').val() + '<br>' + $('#parentpostal').val() +  ' ' + selcity.find(':selected').data('city');
	document.getElementById("sumemail").innerHTML =  $('#parentemail').val();
	document.getElementById("sumphone").innerHTML =  $('#parentphone').val();
	kidstxt = '';
	if ($('#kid1firstname').val().length>0 && $('#kid1lastname').val().length>0){
		kidstxt += $('#kid1firstname').val() + ' ' + $('#kid1lastname').val() + ', ° ' + $('#kid1birthdate').val();
	}
	if ($('#kid2firstname').val().length>0 && $('#kid2lastname').val().length>0){
		kidstxt += '<br>'+ $('#kid2firstname').val() + ' ' + $('#kid2lastname').val() + ', ° ' + $('#kid2birthdate').val();
	}
	document.getElementById("sumkids").innerHTML =  kidstxt;
	document.getElementById('showinput').style.display = 'none';
	document.getElementById('showsummary').style.display = 'block';
	document.getElementById('showsuccess').style.display = 'none';
}

function editMember() {
	document.getElementById('showsummary').style.display = 'none';
	document.getElementById('showinput').style.display = 'block';
	document.getElementById('showsuccess').style.display = 'none';
}

function registerMember() {
		var parentid = "0";
		var kidsdata = [];
		if ($('#kid1firstname').val().length>0 && $('#kid1lastname').val().length>0){
			console.log('adding kid 1');
			kidsdata.push({
				'ID': "0",
				'Name': $('#kid1firstname').val(),
				'Surname': $('#kid1lastname').val(),
				'BirthDate': convertDate($('#kid1birthdate').val()),
				'Caution': "0",
				'ExpiryDate': "0000-00-00",
				'Active': "0",
				'BikeID': "0",
				'KidNr': "0"
			});
		}
		if ($('#kid2firstname').val().length>0 && $('#kid2lastname').val().length>0){
			console.log('adding kid 2');
			kidsdata.push({
				'ID': "0",
				'Name': $('#kid2firstname').val(),
				'Surname': $('#kid2lastname').val(),
				'BirthDate': convertDate($('#kid2birthdate').val()),
				'Caution': "0",
				'ExpiryDate': "0000-00-00",
				'Active': "0",
				'BikeID': "0",
				'KidNr': "0"
			});
		}
		var parentdata = {
				'ID': parentid,
				'Name': $('#parentfirstname').val(),
				'Surname': $('#parentlastname').val(),
				'Street': $('#parentstreet').val(),
				'StreetNumber': $('#parentstreetnr').val(),
				'Postal': $('#parentpostal').val(),
				'Town': selcity.find(':selected').data('city'),
				'Email': $('#parentemail').val(),
				'Phone': $('#parentphone').val(),
				'InitDate': convertDate(myGetDate()),
				'CautionAmount': "0",
				'MembershipID': defaultMembershipID.toString(),
				'Notes': "",
			};
			var logdata = {
				'Datetime' : moment().utcOffset(60).format('YYYY-MM-DD HH:mm:ss'),
				'SignPhrase':document.getElementById("signcontact").checked ? 1 : 0,
				'Phrase': 'Ik teken dat ik op wielekes toestemming geef me te contacteren.',
				'SignRules': document.getElementById("signrules").checked ? 1 : 0,
				'RulesDoc': 'ReglementOpwielekes.pdf'
			}
			console.log(JSON.stringify({
				'kidsdata': kidsdata,
				'parentdata': parentdata,
				'logdata' : logdata
			}));
	    $.ajax({
			type: 'POST',
			url: '../api/members/register',
			data: JSON.stringify({
				'kidsdata': kidsdata,
				'parentdata': parentdata,
				'logdata' : logdata
			}),
			contentType: "application/json",
			success: function () {
				toastr.success('Registratie opgeslagen');

			},
			error: function (data) {
				console.error(data);
			},
			complete: function () {
				//mailRegistration();
				document.getElementById('showinput').style.display = 'none';
				document.getElementById('showsummary').style.display = 'none';
				document.getElementById('showsuccess').style.display = 'block';
			}
		});
}

function mailRegistration() {
	mailData = {
		'sendto' : $('#parentemail').val(),
		'replyto' : replyto,
		'replytoname' : 'Opwielekes',
		'message' : 'Beste,<br><br>Je bent geregistreerd voor opwielekes',
		'subject': 'Op wielekes registratie'
	};
	sendEmail(mailData);
}

function validateInput() {
	var validinput = true;
	signrules = document.getElementById("signrules").checked;
	if ( !signrules ) {
		$('#signrulesdiv').addClass('has-error');
		document.getElementById("signrules").focus();
		validinput = false;
	} else {
		$('#signrulesdiv').removeClass('has-error');
	}
	signcontact = document.getElementById("signcontact").checked;
	if ( !signcontact ) {
		$('#signcontactdiv').addClass('has-error');
		document.getElementById("signcontact").focus();
		validinput = false;
	} else {
		$('#signcontactdiv').removeClass('has-error');
	}
	var email  = $('#parentemail').val();
	if (email == "" || !isValidEmailAddress(email)) {
		$('#parentemaildiv').addClass('has-error');
		document.getElementById("parentemail").focus();
		validinput = false;
	} else {
		$('#parentemalidiv').removeClass('has-error');
	}
	var phone = $('#parentphone').val();
	if ( !$.isNumeric(phone) || phone.length<8) {
		$('#parentphonediv').addClass('has-error');
		document.getElementById("parentphone").focus();
		validinput = false;
	} else {
		$('#parentphonediv').removeClass('has-error');
	}
	if (!$('#parenttown').val()) {
		$('#parenttowndiv').addClass('has-error');
		document.getElementById("parenttown").focus();
		validinput = false;
	} else {
		$('#parenttowndiv').removeClass('has-error');
	}
	if ($('#parentstreetnr').val() == "") {
		$('#parentstreetnrdiv').addClass('has-error');
		document.getElementById("parentstreetnr").focus();
		validinput = false;
	} else {
		$('#parentstreetnrdiv').removeClass('has-error');
	}
	if ($('#parentstreet').val() == "") {
		$('#parentstreetdiv').addClass('has-error');
		$('#parentstreet').addClass('has-error');
			document.getElementById("parentstreet").focus();
		validinput = false;
	} else {
		$('#parentstreetdiv').removeClass('has-error');
	}
	if ($('#parentlastname').val() == "") {
		$('#parentlastnamediv').addClass('has-error');
			document.getElementById("parentlastname").focus();
		validinput = false;
	} else {
		$('#parentlastnamediv').removeClass('has-error');
	}
	if ($('#parentfirstname').val() == "") {
		$('#parentfirstnamediv').addClass('has-error');
			document.getElementById("parentfirstname").focus();
		validinput = false;
	} else {
		$('#parentfirstnamediv').removeClass('has-error');
	}
	return validinput;
}


function loadPostalcodes() {
	$.ajax({
			url: '../api/settings/postalcodes',
			success: function (postalcodes) {
				db_postalcodes = postalcodes;
				setCities(postalcodes);
			}
	});
}

function setCities(postalcodes) {
	selcity.empty();
	var newOption = new Option('', '', false, false);
	selcity.append(newOption);
	$(postalcodes).each( function (index, item) {
		var htmlOption = '<option value="' + item.ID + '" data-postalcode="' + item.Postalcode + '" data-city="' + item.City +  '">' + item.City  + '</option>';
		selcity.append(htmlOption);
	});
}

function setPostalcode() {
	city = selcity.find(':selected');
	$('#parentpostal').val(city.data('postalcode'));
}

function resetPostalcode() {
	$('#parentpostal').val('');
}

function resetSignupForm() {
	$('#parentfirstname').val('');
	$('#parentlastname').val('');
	$('#parentstreet').val('');
	$('#parentstreetnr').val('');
	$('#parentpostal').val('');
	selcity.val(null).trigger("change");
	$('#parentemail').val('');
	$('#parentphone').val('');
	$('#kid1firstname').val('');
	$('#kid1lastname').val('');
	$('#kid1birthdatepicker').data("DateTimePicker").date(moment().format('DD-MM-YYYY'));
	$('#kid2firstname').val('');
	$('#kid2lastname').val('');
	$('#kid2birthdatepicker').data("DateTimePicker").date(moment().format('DD-MM-YYYY'));
	$('#signcontact').prop("checked", false);
	$('#signrules').prop("checked", false);
	document.getElementById('showinput').style.display = 'block';
	document.getElementById('showsummary').style.display = 'none';
	document.getElementById('showsuccess').style.display = 'none';
}

function convertDate(date) {
	console.log(date);
	return date.split("-").reverse().join("-");
}

function myGetDate() {
    return moment().format('DD-MM-YYYY');
}

function isValidEmailAddress(emailAddress) {
		var pattern = /\S+@\S+\.\S+/;
    //var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		console.log('email ' + emailAddress + ' is valid: ' + pattern.test(emailAddress));
		return pattern.test(emailAddress);
}
