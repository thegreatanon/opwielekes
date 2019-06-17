$(document).ready(function () {

	$('#actiondatepicker').datetimepicker({
		//locale: 'nl',
		defaultDate: new Date(),
		format: 'YYYY-MM-DD'
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
	
	actiontype = $('#action_type').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setActionInfo();
	}).on('select2:unselect', function() {
		resetActionInfo();
	});;
	
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
	
	$('#action_cautioninput').on('change', 'input', function (e) {
        setSaveDisabled(false);
	});
	
	$('#action_membershipinput').on('change', 'input', function (e) {
        setSaveDisabled(false);
	});

	$('#action_form').on('submit',function(e){
		e.preventDefault();
		saveTransaction();
	})
	
	actionquill = new Quill('#actionemail', {
		modules: {
			toolbar: quillToolbarOptions
		},
		theme: 'snow',
		background: 'white'
	});

});

function setActionBikes(bikes) {
	actionbikeout.empty();
	actionbikeall.empty();
	actionbikeout.append(new Option('', '', false, false));
	actionbikeall.append(new Option('', '', false, false));
	for (var i = 0, len = bikes.length; i < len; i++) {
		var newOption = new Option( bikes[i].Number + " - " + bikes[i].Name, bikes[i].ID, false, false);
		actionbikeall.append(newOption);
		if (bikes[i].Status == "Beschikbaar") {
			var bOption = new Option( bikes[i].Number + " - " + bikes[i].Name, bikes[i].ID, false, false);
			actionbikeout.append(bOption);
		} 
	}
	actionbikeout.trigger('change');
	actionbikeall.trigger('change');
}

function setActionMembers(members) {
	actionmember.empty();
	var newOption = new Option('', '', false, false);
	actionmember.append(newOption);
	for (var i = 0, len = members.length; i < len; i++) {
		var m = members[i];
		var p = db_parents.find(x => x.ID === m.ParentID.toString());
		var htmlOption = '<option value="' + m.KidID + '" data-parentid="' + m.ParentID +'" data-parentname="' + m.ParentName + ' ' + m.ParentSurname + '" data-parentdate="' + m.ParentInitDate + '" data-parentnrkids="' + p.NrKids + '" data-parentactivekids="' + p.ActiveKids + '" data-parentdonations="' + p.Donations + '" data-parentcautionamount="' + m.ParentCautionAmount + '" data-kidactive="' + m.KidActive + '" data-cautionamount="' + m.KidCautionAmount + '" data-expirydate="' + m.KidExpiryDate + '" data-bikeid="' + m.KidBikeID + '" data-kidnr="' + m.KidNr + '">' + m.KidName + ' ' + m.KidSurname + ' - ' +  m.ParentName + ' ' + m.ParentSurname  + '</option>';
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
			var htmlOption = '<option value="' + item.ID + '" data-updatebike="' + item.UpdateBike + '" data-updatekid="' + item.UpdateKid + '" data-updatekidfin="' + item.UpdateKidFin + '" data-updatefin="' + item.UpdateFin + '" data-requirebikein="' + item.RequireBikeIn + '" data-requirebikeout ="' + item.RequireBikeOut + '" data-requirebikeall="' + item.RequireBikeAll + '" data-requiremembership="' + item.RequireMembership + '" data-requirecaution="' + item.RequireCaution + '" data-resultchangeactive="' + item.ResultChangeActive + '" data-resultkidactive="' + item.ResultKidActive + '" data-enablesave="' + item.EnableSave + '" data-donationreceived="' + item.DonationReceived + '" data-demandcaution="' + item.DemandCaution + '" data-returncaution="' + item.ReturnCaution + '" data-emailsend="' + item.EmailSend + '" data-emailid="' + item.EmailID +'">' + item.Name  + '</option>';
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
		document.getElementById('action_currentbiketext').innerHTML = '';
	} else {
		bike = db_bikes.find(x => x.ID === bikeID.toString());
		document.getElementById('action_currentbiketext').innerHTML = bike.Number + " - " + bike.Name;
	}
	// PARENT
	parentID = selection.data('parentid').toString();
	document.getElementById('action_parentname').innerHTML = selection.data('parentname');
	document.getElementById('action_parentsince').innerHTML = selection.data('parentdate');
	document.getElementById('action_parentcaution').innerHTML = selection.data('parentcautionamount');
	document.getElementById('action_parentactivekids').innerHTML = selection.data('parentactivekids');
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
		$('#action_kids_table_tbody').append(template_kidsactionrow({ID: kids[i].ID, fullname: kids[i].Name + ' ' + kids[i].Surname, kidnr: kids[i].KidNr, expirydate: kids[i].ExpiryDate, active: kids[i].Active, bikenr: bikenr }));
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
	//document.getElementById('action_currentbiketext').innerHTML = '';
	// PARENTS
	document.getElementById('action_parentname').innerHTML = '';
	document.getElementById('action_parentsince').innerHTML = '';
	document.getElementById('action_parentcaution').innerHTML = '';
	document.getElementById('action_parentactivekids').innerHTML = '';
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
	} else {
		setSaveDisabled(true);
	}
	// EMAIL
	if (actionoption.data('emailsend')=="1") {
		$("#action_emaildiv").show();
	} else {
		$("#action_emaildiv").hide();
	}
	// CAUTION
	memberoption = actionmember.find('option:selected');
	cautionBalance = checkCaution(actionoption, memberoption);
	var cautionstring = "";
	if (cautionBalance==0) {
		cautionstring = "OK";
	} else if (cautionBalance<0) {
		cautionstring = (-1*cautionBalance) + " terug te storten";	
	} else {
		cautionstring = (cautionBalance) + " te betalen";	
	}
	document.getElementById('action_cautioninfotext').innerHTML = cautionstring;
	// MEMBERSHIP
	membershipBalance = checkMembership(actionoption, memberoption);
	if (membershipBalance==0) {
		document.getElementById('action_membershipinfotext').innerHTML = 'OK';
	} else {
		document.getElementById('action_membershipinfotext').innerHTML = (membershipBalance) + ' te betalen';
	}
}

function checkMembership(actionoption, memberoption) {
	var today =  moment();
	var expirydate = memberoption.data('expirydate');
	console.log('valid: ' + moment(expirydate).isValid());
	var balance = 0;
	if (!moment(expirydate).isValid() || moment(expirydate).isBefore(today)) {
		if (memberoption.data('kidnr') == "0") {
			currentKidNr = parseInt(memberoption.data('parentactivekids')) + 1;
		} else {
			currentKidNr = memberoption.data('kidnr');
		}
		prices = db_prices[0];
		balance = parseFloat(prices['Kid'+currentKidNr]);
	}		
	console.log('membershipbalance: ' + balance);
	return balance;
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
	console.log('activekids: ' + activeKids);
	//activeKids = activeKids - nrDonations;
	desiredCautionAmount = computeCaution(activeKids);
	var balance = desiredCautionAmount - cautionAmount;
	console.log('cautionbalance: ' + balance);
	return balance;
}

function computeCaution(activeKids) {
	var caution = 0;
	prices = db_prices[1];
	for (var i = 1; i < activeKids+1; i++) {
		caution = caution + parseFloat(prices['Kid'+i]);
	}
	return caution;
}


function resetActionInfo() {
	// BIKES
	document.getElementById('action_currentbiketext').innerHTML = '';
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
	// EMAIL
	$("#action_emaildivt").hide();
	// CAUTION
	document.getElementById('action_cautioninfotext').innerHTML = "";
	cautionBalance = 0;
	// MEMBERSHIP
	document.getElementById('action_membershipinfotext').innerHTML = '';
	membershipBalance = 0;
}

function setSaveDisabled(disable) {
	$("#saveActionBtn").prop("disabled", disable);
}

function resetTransaction() {
	resetActionTypes();
	resetActionMemberInfo();
	resetActionInfo();
	visibilityActionMemberInfo(false)
	$('#action_date').val(myGetDate());
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
		aDate = $('#action_date').val();
		// BIKE STATUS
		var newBikeID = "0";
		bikeStatus = [];
		bikeInID = memberoption.data('bikeid')
		updateBike = actionoption.data('updatebike');
		if (actionoption.data('requirebikein')=="1"){
			bikeStatus.push({
				'ID': bikeInID,
				'Status': 'Beschikbaar'
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
				'Status': 'Ontleend'
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
		var expirydate = memberoption.data('expirydate');
		if (updateKid == "1") {
			if (actionoption.data('donationreceived')=="1") {
				expirydate = extendExpiryDate(expirydate);
			}
			kidStatus = {
				'ID': kidID,
				'Active': kidActive,
				'KidNr': kidNr,
				'ExpiryDate': expirydate, 
				'BikeID': bikeOutID
			};
		}
		
		var cautionval = "0";
		var membershipval = "0";
		// FINANCE STATUS 
		/*
		kidFinances = [];
		updateKidFin = actionoption.data('updatekidfin');
		var cautionval = "0";
		var cautionpresent = memberoption.data('cautionpresent');
		var cautionamount = memberoption.data('cautionamount');
		var membershipval = "0";
		
		if (updateKidFin == "1") {
			// caution
			if (actionoption.data('requirecaution')=="1") {
				cautionval = $('#amount_caution').val();
				if (cautionval > "0") {
					cautionpresent = "1";
				}
				
			}
			// a donation was made, set CautionPresent to 1
			
			// membership expiry
			if (actionoption.data('requiremembership')=="1") {
				membershipval = $('#amount_membership').val();
				if (membershipval > "0") {
					expirydate = moment().add(1, 'year');
				}
			}
			
			kidFinances = {
				'ID': kidID,
				'CautionPresent': cautionpresent,
				'CautionAmount': cautionval,
				'ExpiryDate': expirydate
			};
		}
		*/
		// FINANCES
		finTransactions = [];
		updateFin = "0";
		if (cautionBalance!=0 || membershipBalance!=0) {
			updateFin = "1";
		}
		//updateFin = actionoption.data('updatefin');
		if (updateFin == "1") {
			finTransactions.push({
				'TransactionDate': aDate,
				'ParentID': parentID,
				'KidID': kidID,
				'Amount': parseFloat(cautionBalance)+parseFloat(membershipBalance),
				'Membership': membershipBalance,
				'Caution': cautionBalance,
				'Received': "0"
			}); 
		}
		// Caution
		/*
		returnCaution = actionoption.data('returncaution');
		var cautionamount = parseFloat(memberoption.data('cautionamount'));
		if (returnCaution == "1" && cautionamount>0) {
			var credit = -1*cautionamount;
			updateFin = "1";
			finTransactions.push({
				'TransactionDate': "0000-00-00",
				'ParentID': parentID,
				'KidID': kidID,
				'Amount': credit,
				'Membership': "0",
				'Caution': credit,
				'Received': "0"
			}); 
		}			
		*/
		// LOAN
		transactionData = {
			'KidID': kidID,
			'ParentID': parentID,
			'Action': actionoption.text(),
			'BikeInID': bikeInID,
			'BikeOutID': bikeOutID,
			'Caution': cautionval,
			'Membership': membershipval,
			'Date': aDate
		};
		validInput = verifyActionInput(actionoption, kidID, bikeOutID, bikeInID, aDate);
		
		console.log('API data: ' + JSON.stringify({
					'transactionData': transactionData,
					'updateKid': updateKid,
					'kidStatus': kidStatus,
					'updateBike': updateBike,
					'bikeStatus': bikeStatus,
					'updateFin': updateFin,
					'finTransactions': finTransactions
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
					'finTransactions': finTransactions
				}),
				contentType: "application/json",
				success: function () {
					toastr.success('Transactie opgeslagen.');
					loadBikes();
					loadMembers();
					resetTransaction();
					loadFinances();
				},
				error: function (data) {
					toastr.error('Er is een fout opgetreden', 'Niet opgeslagen');
					console.error(data);
				}
			});
		}
	}
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