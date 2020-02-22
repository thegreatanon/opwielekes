$(document).ready(function () {

	$('#kid1birthdatepicker').datetimepicker({
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	$('#kid2birthdatepicker').datetimepicker({
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

});

function registerMember() {
		validInput = validateInput();
		if (!validInput) {
			toastr.error('Vul alle velden correct in.');
			return;
		}
		var parentid = "0";
		var kidsdata = [];
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
		var parentdata = {
				'ID': parentid,
				'Name': $('#parentfirstname').val(),
				'Surname': $('#parentlastname').val(),
				'Street': $('#parentstreet').val(),
				'StreetNumber': $('#parentstreetnr').val(),
				'Postal': $('#parentpostal').val(),
				'Town': $('#parenttown').val(),
				'Email': $('#parentemail').val(),
				'Phone': $('#parentphone').val(),
				'InitDate': convertDate(myGetDate()),
				'CautionAmount': "0",
				'MembershipID':  defaultMembershipID
			};
			console.log(JSON.stringify({
				'kidsdata': kidsdata,
				'parentdata': parentdata,
				'parentID': parentid
			}));
	    $.ajax({
			type: 'POST',
			url: '../api/members',
			data: JSON.stringify({
				'kidsdata': kidsdata,
				'parentdata': parentdata,
				'parentID': parentid
			}),
			contentType: "application/json",
			success: function () {
				toastr.success('Registratie opgeslagen');
				resetSignupForm();
			},
			error: function (data) {
				console.error(data);
			}
		});
}

function validateInput() {
	return true;
}


function resetSignupForm() {
	return true;
}

function convertDate(date) {
	console.log(date);
	return date.split("-").reverse().join("-");
}

function myGetDate() {
    return moment().format('DD-MM-YYYY');
}
