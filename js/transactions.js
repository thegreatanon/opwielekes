$(document).ready(function () {


	$('#actiondatepicker').datetimepicker({
		locale: 'nl-be',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	$('#actionexpirydatepicker').datetimepicker({
		locale: 'nl-be',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	actionbikeall = $('#action_bike_all').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setSaveDisabled(false);
	}).on('select2:unselect', function() {
		setSaveDisabled(true);
	});

	actionbikeout = $('#action_bike_out').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setSaveDisabled(false);
	}).on('select2:unselect', function() {
		setSaveDisabled(true);
	});

	actionbikedonate = $('#action_bike_donate').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	});

	actiontype = $('#action_type').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setActionInfo();
	}).on('select2:unselect', function() {
		resetActionInfo();
	});

	actionmember = $('#action_member').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		kidID = $(this).val();
		selection = $(this).find('option:selected');
		setActionMemberInfo(selection, kidID);
		setActionTypes(selection, kidID);
		resetActionInfo();
		visibilityActionMemberInfo(true);
	}).on('select2:unselect', function() {
		visibilityActionMemberInfo(false);
		resetActionMemberInfo();
		resetActionInfo();
		resetActionTypes();
	});

	actionpaymentmembership = $('#action_paymentmethod').select2({
		allowClear: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		membershipPaymentChanged($(this).val());
	});

	actionpaymentcaution = $('#action_waarborgpaymentmethod').select2({
		allowClear: false,
		dropdownAutoWidth: true
	}).on('select2:unselect', function() {
		computeTotalPayment();
	}).on('select2:select', function() {
		computeTotalPayment();
	});

	$('#action_cautioninput').on('change', 'input', function (e) {
		computeTotalPayment();
	});

	$('#action_membershipinput').on('change', 'input', function (e) {
		computeTotalPayment();
	});



/*
	$(document).on('change', '#action_sendemail', function() {
			console.log('checkbox changed');
	    if(this.checked) {
	      // checkbox is checked
	    }
	});
*/

	$('#action_form').on('submit',function(e){
		e.preventDefault();
		saveTransaction();
	})

	transactionstable = $('#transactions_table').DataTable({
  	paging: true,
		pageLength: 25,
		//"order": [[ 6, "asc" ],[ 0, "desc" ]],
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		//dom: 'lfrtip',
		//rowId: 'transID',
		autoWidth: true,
  	columns: [
					{data: 'Date'},
		      {
						data: {
								ParentID: 'ParentID',
								ParentName: 'ParentName',
                ParentSurname: 'ParentSurname'
            },
	          render: function (data, type) {
							//return data.ParentName + " " + data.ParentSurname;
							return data.ParentID;
	          }
					},
		      {
						data: {
								KidID: 'KidID',
                KidName: 'KidName',
                KidSurname: 'KidSurname'
            },
            render: function (data, type) {
								//return data.KidName + " " + data.KidSurname;
								return data.KidID;
            }
					},
		      {data: 'ActionID'},
          {data: 'BikeInID'},
					{data: 'BikeOutID'}
    ],
		"search": {
			"regex": true,
			"smart":false
		}
  });

	actionquill = new Quill('#actionemail', {
		modules: {
			toolbar: quillToolbarOptions
		},
		theme: 'snow',
		background: 'white'
	});

	loadTransactionHistory();

});

function setActionBikes(bikes) {
	actionbikeout.empty();
	actionbikeall.empty();
	actionbikedonate.empty();
	actionbikeout.append(new Option('', '', false, false));
	actionbikeall.append(new Option('', '', false, false));
	actionbikedonate.append(new Option('', '', false, false));
	for (var i = 0, len = bikes.length; i < len; i++) {
		var newOption = new Option( bikes[i].Number + " - " + bikes[i].Name, bikes[i].ID, false, false);
		actionbikeall.append(newOption);
		if (bikes[i].StatusAvailable == "1") {
			var bOption = new Option( bikes[i].Number + " - " + bikes[i].Name, bikes[i].ID, false, false);
			actionbikeout.append(bOption);
		}
		if (bikes[i].Donated == "0") {
			var dOption = new Option( bikes[i].Number + " - " + bikes[i].Name, bikes[i].ID, false, false);
			actionbikedonate.append(dOption);
		}
	}
	actionbikeout.trigger('change');
	actionbikeall.trigger('change');
	actionbikedonate.trigger('change');
}

function setActionMembers(members) {
	actionmember.empty();
	var newOption = new Option('', '', false, false);
	actionmember.append(newOption);
	for (var i = 0, len = members.length; i < len; i++) {
		var m = members[i];
		var p = db_parents.find(x => x.ID === m.ParentID.toString());
		var htmlOption = '<option value="' + m.KidID + '" data-parentid="' + m.ParentID +'" data-parentname="' + m.ParentName + ' ' + m.ParentSurname + '" data-parentdate="' + m.ParentInitDate + '" data-parentsurname="' + m.ParentSurname + '" data-parentfirstname="' + m.ParentName + '" data-kidfirstname="' + m.KidName + '" data-kidsurname="' + m.KidSurname + '" data-parentiban="' + p.IBAN + '" data-parentnrkids="' + p.NrKids + '" data-parentactivekids="' + p.ActiveKids + '" data-parentdonations="' + p.Donations + '" data-parentcautionamount="' + m.ParentCautionAmount + '" data-parentmembershipid="' + m.ParentMembershipID + '" data-parentmembershipname="' + m.ParentMembershipName + '" data-kidactive="' + m.KidActive + '" data-cautionamount="' + m.KidCautionAmount + '" data-expirydate="' + m.KidExpiryDate + '" data-bikeid="' + m.KidBikeID + '" data-kidnr="' + m.KidNr + '" data-email="' + p.Email + '">' + m.KidName + ' ' + m.KidSurname + ' - ' +  m.ParentName + ' ' + m.ParentSurname  + '</option>';
		actionmember.append(htmlOption);
	}
	actionmember.trigger('change');
}

function setActionTypes(selection, kidID)  {
	actiontype.empty();
	actiontype.append(new Option('', '', false, false));
	bikeID = parseInt(selection.data('bikeid'));
	var bikeLoan = parseInt(selection.data('bikeid'))>0 ? true : false;
	$(db_actions).each( function (index, item) {
		if (item.ValidAlways=="1" || (item.ValidNoBike=="1" && !bikeLoan) || (item.ValidOnBike=="1" && bikeLoan)) {
			var htmlOption = '<option value="' + item.ID + '" data-updatebike="' + item.UpdateBike + '" data-updatekid="' + item.UpdateKid + '" data-updatekidfin="' + item.UpdateKidFin + '" data-updatefin="' + item.UpdateFin + '" data-requirebikein="' + item.RequireBikeIn + '" data-requirebikeout ="' + item.RequireBikeOut + '" data-requirebikeall="' + item.RequireBikeAll + '" data-requiremembership="' + item.RequireMembership + '" data-requirecaution="' + item.RequireCaution + '" data-resultchangeactive="' + item.ResultChangeActive + '" data-resultkidactive="' + item.ResultKidActive + '" data-enablesave="' + item.EnableSave + '" data-checkMembership="' + item.CheckMembership + '" data-demandcaution="' + item.DemandCaution + '" data-returncaution="' + item.ReturnCaution + '" data-emailsend="' + item.EmailSend + '" data-emailid="' + item.EmailID +'">' + item.Name  + '</option>';
			actiontype.append(htmlOption);
		}
	});
}

function resetActionTypes(){
	actiontype.empty();
}

function setActionMemberInfo(selection, kidID) {
	// BIKE
	bikeID = selection.data('bikeid');
	if (bikeID == 0) {
		//console.log('resetting currentbike in memberinfo');
		document.getElementById('action_currentbiketext').innerHTML = '';
	} else {
		bike = db_bikes.find(x => x.ID === bikeID.toString());
		//console.log('setting current bike to ' + bike.Number);
		document.getElementById('action_currentbiketext').innerHTML = bike.Number + " - " + bike.Name;
	}
	// MEMBERSHIP
	actionmembershipsel.val(selection.data('parentmembershipid'));
	actionmembershipsel.trigger('change');
	$('#action_expirydate').val(selection.data('expirydate'));
	// PARENT
	parentID = selection.data('parentid').toString();
	document.getElementById('action_parentname').innerHTML = '<a href="#members" onclick="viewMember()">' + selection.data('parentname') + '</a>';
	document.getElementById('action_membership').innerHTML = selection.data('parentmembershipname');
	document.getElementById('action_parentcaution').innerHTML = selection.data('parentcautionamount');
	document.getElementById('action_parentactivekids').innerHTML = selection.data('parentactivekids');
	$('#action_parentid').val(selection.data('parentid'));
	$('#action_emailaddress').val(selection.data('email'));
	// KIDS
	var kids = db_kids.filter(x => x.ParentID === parentID);
	$('#action_kids_table_tbody').empty();
	for (var i = 0, len = kids.length; i < len; i++) {
		bikeID = kids[i].BikeID;
		if (bikeID == "0") {
			bikenr = "";
		} else {
			bike = db_bikes.find(x => x.ID === bikeID.toString());
			bikenr = bike.Number;
		}
		if (kids[i].BirthDate == "00-00-0000") {
			kidsage = "";
		} else {
			kidsage = moment().diff(convertDate(kids[i].BirthDate), 'years');
		}
		$('#action_kids_table_tbody').append(template_kidsactionrow({ID: kids[i].ID, fullname: kids[i].Name + ' ' + kids[i].Surname, kidnr: kids[i].KidNr, age: kidsage, expirydate: kids[i].ExpiryDate, active: kids[i].Active, bikenr: bikenr }));
	}
	// FINANCES

	/*
	if (selection.data('cautionpresent')=="1") {
		document.getElementById('action_cautioninfotext').innerHTML = 'OK';
	} else {
		document.getElementById('action_cautioninfotext').innerHTML = 'Te ontvangen';
	}

	*/
}

function resetActionMemberInfo(selection, kidID) {
	// BIKE
	document.getElementById('action_currentbiketext').innerHTML = '';
	// PARENTS
	document.getElementById('action_parentname').innerHTML = '';
	document.getElementById('action_membership').innerHTML = '';
	document.getElementById('action_parentcaution').innerHTML = '';
	document.getElementById('action_parentactivekids').innerHTML = '';
	$('#action_parentid').val(0);
	$('#action_emailaddress').val('');
	// KIDS
	$('#action_kids_table_tbody').empty();
	// FINANCES
	document.getElementById('action_cautioninfotext').innerHTML = '';
	document.getElementById('action_membershipinfotext').innerHTML = '';
}

function visibilityActionMemberInfo(visible) {
	if (visible) {
		$(".action_memberdiv").show();
	} else {
		$(".action_memberdiv").hide();
	}
}

function setActionInfo() {
	actionoption = actiontype.find('option:selected');
	// BIKES
	if (actionoption.data('requirebikeall')=="1") {
		$("#action_currentbike").hide();
		$("#action_allbikes").show();
		$("#action_bikein_space").hide();
	} else if (actionoption.data('requirebikein')=="1"){
		//console.log('showing current bike');
		$("#action_currentbike").show();
		$("#action_allbikes").hide();
		$("#action_bikein_space").hide();
	} else {
		$("#action_currentbike").hide();
		$("#action_allbikes").hide();
		$("#action_bikein_space").show();
	}
	if (actionoption.data('requirebikeout')=="1") {
		$("#action_availablebikes").show();
	} else {
		$("#action_availablebikes").hide();
	}
	// FINANCES
	if (actionoption.data('requirecaution')=="1") {
		$("#action_cautioninput").show();
		$("#action_cautioninfo").hide();
	} else {
		$("#action_cautioninput").hide();
		$("#action_cautioninfo").show();
	}
	if (actionoption.data('requiremembership')=="1") {
		$("#action_membershipinput").show();
		$("#action_membershipinfo").hide();
	} else {
		$("#action_membershipinput").hide();
		$("#action_membershipinfo").show();
	}
	if (actionoption.data('enablesave')=="1") {
		setSaveDisabled(false);
	} /*else {
		setSaveDisabled(true);
	}*/
	// CAUTION
	memberoption = actionmember.find('option:selected');
	cautionBalance = checkCaution(actionoption, memberoption);
	$("#amount_caution").val(cautionBalance);
	$("#amount_caution_hidden").val(cautionBalance);
	// MEMBERSHIP
	var memvals = checkMembership(actionoption, memberoption);
	var membershipBalance = memvals[0];
	var newexpirydate = memvals[1];
	$("#amount_membership").val(membershipBalance);
	$("#amount_membership_hidden").val(membershipBalance);
	$("#action_expirydate").val(newexpirydate);
	// general updates
	computeTotalPayment();

	// EMAIL
	if (actionoption.data('emailsend')=="1") {
		emailID = actionoption.data('emailid');
		var e = db_emails.find(x => x.ID === emailID.toString());
		$('#action_emailsubject').val(parseEmail(e.Subject));
		actionquill.root.innerHTML = parseEmail(e.Text);
		$("#action_emaildiv").show();
		$("#action_sendemail").prop('checked', true);
		$("#action_sendemail").prop('disabled', false);

	} else {
		$("#action_emaildiv").hide();
		$("#action_sendemail").prop('checked', false);
		$("#action_sendemail").prop('disabled', true);
	}
	// SHOW
	$(".action_actiondiv").show();
}

function checkMembership(actionoption, memberoption) {
	var today =  moment();
	var expirydate = memberoption.data('expirydate');
	//membershipid = memberoption.data('parentmembershipid');
	membershipid = 	actionmembershipsel.val();
	membership = db_memberships.find(x => x.ID === membershipid.toString());
	var balance = 0;
	if (actionoption.data('checkmembership')=='1') {
		if (!moment(expirydate, 'DD-MM-YYYY').isValid() || moment(expirydate, 'DD-MM-YYYY').isBefore(today)) {
			if (memberoption.data('kidnr') == "0") {
				currentKidNr = parseInt(memberoption.data('parentactivekids')) + 1;
			} else {
				currentKidNr = memberoption.data('kidnr');
			}
			balance = balance + parseFloat(membership['MembershipK'+currentKidNr]);
			expirydate = extendExpiryDate(expirydate);
		}
	}
	return [balance.toFixed(2), expirydate];
}

function checkCaution(actionoption, memberoption) {
	activeKids = memberoption.data('parentactivekids');
	cautionAmount = memberoption.data('parentcautionamount');
	//nrDonations = memberoption.data('parentdonations');
	if (actionoption.data('demandcaution')=="1"){
		activeKids = activeKids+1;
	}
	if (actionoption.data('returncaution')=="1"){
		activeKids = activeKids-1;
	}
	desiredCautionAmount = computeCaution(activeKids, actionmembershipsel.val());
	var balance = desiredCautionAmount - cautionAmount;
	return balance.toFixed(2);
}

function computeCaution(activeKids,membershipID) {
	var caution = 0;
	var thismembership = db_memberships.find(x => x.ID === membershipID.toString());
	for (var i = 1; i < activeKids+1; i++) {
		caution = caution + parseFloat(thismembership['CautionK'+i]);
	}
	return caution.toFixed(2);
}

function setActionMembershipType() {
	setActionInfo();
}


function loadActionPaymentMethods() {
	loadMembershipPaymentMethod();
	loadCautionPaymentMethod();
}

function loadMembershipPaymentMethod(){
	actionpaymentmembership.empty();
	$.each(db_paymentmethods, function (index, item) {
		if (item.PaymentMethodActive == 1) {
			var newOption = new Option(item.PaymentMethodName, item.PaymentMethodID, false, false);
			actionpaymentmembership.append(newOption).trigger('change');
		}
	});
	actionpaymentmembership.val(defaultPaymentMethod).trigger('change');
}

function loadCautionPaymentMethod(){
	actionpaymentcaution.empty();
	$.each(db_paymentmethods, function (index, item) {
		if (item.PaymentMethodActive == 1 && item.PaymentMethodDonation == 0) {
			var newOption = new Option(item.PaymentMethodName, item.PaymentMethodID, false, false);
			actionpaymentcaution.append(newOption).trigger('change');
		}
	});
	// if default is donation, then nothing is automatically selected here, not a problem
	actionpaymentcaution.val(defaultPaymentMethod).trigger('change');
}

function membershipPaymentChanged(paymentmethod){
	var method = db_paymentmethods.filter(x => x.PaymentMethodID === paymentmethod)[0];
	if (method.PaymentMethodDonation == "1") {
		showBikeDonations(true);
		$("#amount_membership").val(0);
		$("#amount_membership").prop("disabled", true);
	} else {
		showBikeDonations(false);
		$("#amount_membership").val($("#amount_membership_hidden").val());
		$("#amount_membership").prop("disabled", false);
	}
	computeTotalPayment();
}

function showBikeDonations(show) {
	if (show) {
		$("#action_donationbikes").show();
	} else {
		$("#action_donationbikes").hide();
	}
}

function computeTotalPayment() {
	totalsum = parseFloat($("#amount_caution").val()) + parseFloat($("#amount_membership").val());
	totalsum = parseFloat(totalsum).toFixed(2);
	if (totalsum<0) {
		sumstring = (-1*totalsum) + " terug te storten";
	} else {
		sumstring = (totalsum) + " te betalen";
	}
	document.getElementById('action_totalpayment').innerHTML = sumstring;
}

function resetActionInfo() {
	// BIKES
	$("#action_currentbike").hide();
	$("#action_allbikes").hide();
	$("#action_bikein_space").show();
	$("#action_availablebikes").hide();
	// FINANCES
	$('#amount_membership').val('0');
	$('#amount_membership').val('0');
	$("#action_cautioninput").hide();
	$("#action_cautioninfo").show();
	$("#action_membershipinput").hide();
	$("#action_membershipinfo").show();
	$("#saveActionBtn").prop("disabled", true);
	document.getElementById('action_totalpayment').innerHTML ="";
	// EMAIL
	$("#action_emaildiv").hide();
	$("#action_sendemail").prop('checked', false);
	$("#action_sendemail").prop('disabled', true);
	// CAUTION
	document.getElementById('action_cautioninfotext').innerHTML = "";
	cautionBalance = 0;
	// MEMBERSHIP
	document.getElementById('action_membershipinfotext').innerHTML = '';
	membershipBalance = 0;
	$("#amount_caution").val(0);
	actionpaymentcaution.val(defaultPaymentMethod).trigger('change');
	$("#amount_membership").val(0);
	actionpaymentmembership.val(defaultPaymentMethod).trigger('change');
	$("#amount_membership").prop("disabled", false);
	$("#action_donationbikes").hide();
	actionbikedonate.val('').trigger('change');
	$("#amount_paymentnote").val("");
	$(".action_actiondiv").hide();
}

function setSaveDisabled(disable) {
	$("#saveActionBtn").prop("disabled", disable);
}


function lockDate() {
	$("#dateUnlocked").hide();
	$("#dateLocked").show();
	$("#action_date").prop("disabled", true);
	actionDateIsLocked = 1;
}

function unlockDate() {
	$("#dateUnlocked").show();
	$("#dateLocked").hide();
	$("#action_date").prop("disabled", false);
	actionDateIsLocked = 0;
}

function resetTransaction() {
	resetActionTypes();
	resetActionMemberInfo();
	resetActionInfo();
	visibilityActionMemberInfo(false)
	if (actionDateIsLocked == "0") {
		$('#action_date').val(myGetDate());
	}
}

function cancelTransaction() {
	resetTransaction();
	actionmember.val('').trigger('change');
	actionbikeall.val('').trigger('change');
	actionbikeout.val('').trigger('change');
}

function saveTransaction() {
	if (document.getElementById("action_form").checkValidity()) {
		// READ INPUT
		memberoption = actionmember.find('option:selected');
		parentID = memberoption.data('parentid');
		kidID = actionmember.val();
		actionoption = actiontype.find('option:selected');
		action= actiontype.val();
		aDate = convertDate($('#action_date').val());
		// BIKE STATUS
		var newBikeID = "0";
		bikeStatus = [];
		bikeInID = memberoption.data('bikeid')
		updateBike = actionoption.data('updatebike');
		if (actionoption.data('requirebikein')=="1"){
			bikeStatus.push({
				'ID': bikeInID,
				'Status': defaultBikeAvailableID,
				'KidID' : 0,
				'Date': aDate
			});
		} else {
			bikeInID = "0";
		}
		if (actionoption.data('requirebikeall')=="1"){
			bikeInID = actionbikeall.val();
		}
		bikeOutID = "0";
		if (actionoption.data('requirebikeout')=="1"){
			bikeOutID = actionbikeout.val();
			bikeStatus.push({
				'ID': bikeOutID,
				'Status': defaultBikeOnLoanID,
				'KidID' : kidID,
				'Date': aDate
			});
		}
		// KID STATUS
		kidStatus = [];
		kidActive = actionoption.data('resultkidactive');
		updateKid = actionoption.data('updatekid');
		var kidNr = memberoption.data('kidnr');
		if (kidNr=="0" && kidActive=="1") {
			kidNr = parseInt(memberoption.data('parentactivekids'))+1;
		}
		if (kidActive=="0") {
			kidNr = 0;
		}
		var expirydate = convertDate($('#action_expirydate').val());
		if (updateKid == "1") {
			kidStatus = {
				'ID': kidID,
				'Active': kidActive,
				'KidNr': kidNr,
				'BikeID': bikeOutID
			};
		}
		expiryData = {
			'ID': kidID,
			'ExpiryDate': expirydate
		};
		// Donation?
		updateDonor = "0";
		donorData = [];
		finTransactions = [];
		updateFin = "0";
		amountcaution =  $('#amount_caution').val();
		amountmembership = $('#amount_membership').val();
 		methodmembership = db_paymentmethods.filter(x => x.PaymentMethodID === actionpaymentmembership.val())[0];
		methodcaution = db_paymentmethods.filter(x => x.PaymentMethodID === actionpaymentcaution.val())[0];
		if (methodmembership.PaymentMethodDonation == "1") {
			if (actionbikedonate.val()==''){
				toastr.error('Kies een fiets om te doneren');
				return;
			} else {
				updateDonor = "1";
				donorData = {
					'ID': actionbikedonate.val(),
					'Donated': "1",
					'Donor': kidID,
					'DonationDate': aDate
				};
			};
		};
		// membership and caution should be recorded regardless of donation
	 if (amountmembership == 0) {
				if (amountcaution != 0) {
					updateFin = "1";
					finTransactions.push({
						'TransactionDate': aDate,
						'ParentID': parentID,
						'KidID': kidID,
						'Amount': parseFloat(amountcaution),
						'Membership': 0,
						'Caution': parseFloat(amountcaution),
						'Received': methodcaution.PaymentMethodImmediate,
						'Method' : methodcaution.PaymentMethodID
					});
				}
		} else {
			if (methodmembership.PaymentMethodID == methodcaution.PaymentMethodID) {
				updateFin = "1";
				finTransactions.push({
					'TransactionDate': aDate,
					'ParentID': parentID,
					'KidID': kidID,
					'Amount': parseFloat(amountcaution)+parseFloat(amountmembership),
					'Membership': parseFloat(amountmembership),
					'Caution': parseFloat(amountcaution),
					'Received': methodcaution.PaymentMethodImmediate,
					'Method' : methodcaution.PaymentMethodID
				});
			} else {
				updateFin = "1";
				finTransactions.push({
					'TransactionDate': aDate,
					'ParentID': parentID,
					'KidID': kidID,
					'Amount': parseFloat(amountmembership),
					'Membership': parseFloat(amountmembership),
					'Caution': 0,
					'Received': methodmembership.PaymentMethodImmediate,
					'Method' : methodmembership.PaymentMethodID
				});
				if (amountcaution != 0) {
					finTransactions.push({
						'TransactionDate': aDate,
						'ParentID': parentID,
						'KidID': kidID,
						'Amount': parseFloat(amountcaution),
						'Membership': 0,
						'Caution': parseFloat(amountcaution),
						'Received': methodcaution.PaymentMethodImmediate,
						'Method' : methodcaution.PaymentMethodID
					});
				}
			}
		}


		// Caution
		var updateCaution = "0";
		var cautionData = [];
		if (amountcaution!=0) {
			updateCaution = "1";
			var prevcautionAmount = memberoption.data('parentcautionamount');
			cautionData = {
				'ID': parentID,
				'CautionAmount': parseFloat(prevcautionAmount) + parseFloat(amountcaution)
			};
		}
		// LOAN
		transactionData = {
			'KidID': kidID,
			'ParentID': parentID,
			'ActionID': actionoption.val(),
			'BikeInID': bikeInID,
			'BikeOutID': bikeOutID,
			'BikeDonatedID': (methodmembership.PaymentMethodDonation == "1") ? actionbikedonate.val() : "0",
			'MembershipID': actionmembershipsel.val(),
			'ExpiryDate': expirydate,
			'Note': $('#amount_paymentnote').val(),
			'Date': aDate
		};
		validInput = verifyActionInput(actionoption, kidID, bikeOutID, bikeInID, aDate);
		// Email
		sendmail = document.getElementById("action_sendemail").checked;
		mailData = [];
		if (sendmail) {
			mailData = {
				'sendto' : document.getElementById("action_emailaddress").value,
				'message' : actionquill.root.innerHTML,
				'subject': document.getElementById("action_emailsubject").value
			};
		}
		console.log('Transaction API data: ' + JSON.stringify({
					'transactionData': transactionData,
					'updateKid': updateKid,
					'kidStatus': kidStatus,
					'updateBike': updateBike,
					'bikeStatus': bikeStatus,
					'updateFin': updateFin,
					'finTransactions': finTransactions,
					'updateCaution': updateCaution,
					'cautionData': cautionData,
					'updateDonor': updateDonor,
					'donorData': donorData,
					'expiryData': expiryData
				}));
		if (!validInput){
			toastr.error('Vul alle velden in', 'Niet opgeslagen');
		} else {
			$.ajax({
				type: 'POST',
				url: 'api/transactions',
				data: JSON.stringify({
					'transactionData': transactionData,
					'updateKid': updateKid,
					'kidStatus': kidStatus,
					'updateBike': updateBike,
					'bikeStatus': bikeStatus,
					'updateFin': updateFin,
					'finTransactions': finTransactions,
					'updateCaution': updateCaution,
					'cautionData': cautionData,
					'updateDonor': updateDonor,
					'donorData': donorData,
					'expiryData': expiryData
				}),
				contentType: "application/json",
				success: function () {
					toastr.success('Transactie opgeslagen.');
					loadBikes();
					loadMembers();
					resetTransaction();
					loadFinances();
					loadTransactionHistory();
				},
				error: function (data) {
					toastr.error('Er is een fout opgetreden', 'Niet opgeslagen');
					console.error(data);
				},
				complete: function () {
					if (sendmail) {
						sendEmail(mailData);
					}

				}
			});
		}
	}
}


function loadTransactionHistory() {
	$.ajax({
			url: 'api/transactions',
			success: function (transactionhistory) {
				transactionstable.clear();
				transactionstable.rows.add(transactionhistory);
				transactionstable.columns.adjust().draw();
			}
	});
}

function isParentActive(parentID, kidID, kidActive){
	parentActive = 0;
	kids = db_kids.filter(x => x.ParentID === parentID.toString());
	for (var i = 0, len = kids.length; i < len; i++) {
		if (kids[i].ID == kidID) {
			thisKid = kidActive;
		} else {
			thisKid = kids[i].Active;
		}
		if (thisKid == 1) {
			parentActive = 1;
		}
	}
	return parentActive;
}


function verifyActionInput(actionoption, kidID, bikeOutID, bikeInID, aDate) {
	validInput = true;
	if (kidID<1) {
		validInput = false;
	}
	if (!moment(aDate, 'YYYY-MM-DD', true).isValid()) {
		validInput = false;
	}
	if ((actionoption.data('requirebikein')=="1" && bikeInID < 1 ) ||
		(actionoption.data('requirebikeall')=="1"  && bikeInID < 1 ) ||
		(actionoption.data('requirebikein')=="0" && actionoption.data('requirebikeall')=="0" && bikeInID!=0)) {
			validInput = false;
	}
	if ((actionoption.data('requirebikeout')=="1" && bikeOutID < 1) ||
		(actionoption.data('requirebikeout')=="0" && bikeOutID !=0)) {
			validInput = false;
	}
	return validInput;
}

function parseEmail(email) {
	memberoption = actionmember.find('option:selected');
	email = email.replace('{{voornaam_ouder}}', memberoption.data('parentfirstname'));
	email = email.replace('{{achternaam_ouder}}', memberoption.data('parentsurname'));
	email = email.replace('{{voornaam_kind}}', memberoption.data('kidfirstname'));
	email = email.replace('{{achternaam_kind}}', memberoption.data('kidsurname'));
	email = email.replace('{{IBAN_ouder}}', memberoption.data('parentiban'));
	email = email.replace('{{IBAN_depot}}', $('#settings_defaultIBAN').val());
	email = email.replace('{{bedrag_totaal}}', document.getElementById('action_totalpayment').innerHTML);
	email = email.replace('{{bedrag_waarborg}}', parseFloat($('#amount_caution').val()));
	email = email.replace('{{bedrag_lidmaatschap}}', parseFloat($('#amount_membership').val()));
	return email;
}
