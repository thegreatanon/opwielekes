function registerMember() {
		var parentid = 0;
		var kidsdata = [];
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
				'InitDate': convertDate($('#dateregistered').val()),
				'CautionAmount': "0",
				'MembershipID':  defaultMembershipID
			};
	    $.ajax({
			type: 'POST',
			url: 'api/members',
			data: JSON.stringify({
				'kidsdata': kidsdata,
				'parentdata': parentdata,
				'parentID': parentid
			}),
			contentType: "application/json",
			success: function () {
				toastr.success('Registratie opgeslagen');
			},
			error: function (data) {
				console.error(data);
			}
		});
}
