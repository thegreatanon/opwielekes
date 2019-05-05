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
		action = $(this).val();
		setBikeDivs(action);
	}).on('select2:unselect', function() {
		resetBikeDivs();
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
		visibilityActionMemberInfo(true);
	}).on('select2:unselect', function() {
		visibilityActionMemberInfo(false);
		resetActionTypes();
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
		var htmlOption = '<option value="' + m.KidID + '" data-parentid="' + m.ParentID +'" data-parentname="' + m.ParentName + ' ' + m.ParentSurname + '" data-parentdate="' + m.ParentInitDate + '" data-kidactive="' + m.KidActive + '" data-caution="' + m.KidCaution + '" data-expirydate="' + m.KidExpiryDate + '" data-bikeid="' + m.KidBikeID + '">' + m.KidName + ' ' + m.KidSurname + ' - ' +  m.ParentName + ' ' + m.ParentSurname  + '</option>';
		actionmember.append(htmlOption);
	}
	actionmember.trigger('change');
}

function setActionTypes(selection, kidID)  {
	actiontype.empty();
	actiontype.append(new Option('', '', false, false));
	bikeID = parseInt(selection.data('bikeid'));
	if (bikeID == 0){
		actiontype.append(new Option('Start', 'start', false, false));
	} else {
		actiontype.append(new Option('Ruil', 'trade', false, false));
		actiontype.append(new Option('Eind', 'end', false, false));
	}
	actiontype.append(new Option('Donatie', 'donate', false, false));
}

function resetActionTypes(){
	actiontype.empty();
}

function setActionMemberInfo(selection, kidID) {
	// parent info
	parentID = selection.data('parentid').toString();
	document.getElementById('action_parentname').innerHTML = selection.data('parentname');
	document.getElementById('action_parentsince').innerHTML = "Lid sinds " + selection.data('parentdate');
	document.getElementById('action_parentdonations').innerHTML = "Fietsdonaties: " + "x";
	// bike info
	console.log(db_bikes);
	console.log(selection.data('bikeid'));
	bikeID = selection.data('bikeid');
	if (bikeID == 0) {
		document.getElementById('action_returnbike').innerHTML = '';
	} else {
		bike = db_bikes.find(x => x.ID === bikeID.toString());
		document.getElementById('action_returnbike').innerHTML = bike.Number + " - " + bike.Name;
	}
	// kids info
	var kids = db_kids.filter(x => x.ParentID === parentID);
	$('#action_kids_table_tbody').empty();
	for (var i = 0, len = kids.length; i < len; i++) {
		$('#action_kids_table_tbody').append(template_kidsactionrow({ID: kids[i].ID, fullname: kids[i].Name + ' ' + kids[i].Surname, caution: kids[i].Caution, expirydate: kids[i].ExpiryDate, active: kids[i].Active, bikeid: kids[i].BikeID }));
	}
}

function visibilityActionMemberInfo(visible) {
	if (visible) {
		$(".action_memberdiv").show();
	} else {
		$(".action_memberdiv").hide();
	}
}

function setBikeDivs(action) {
	if (action == 'end') {
		$("#action_allbikes").hide();
		$("#action_availablebikes").hide();
		$("#action_returnbikes").show();
		$("#action_tradebikes").hide();
		$("#saveActionBtn").prop("disabled", false);
	} else if (action == 'donate') {
		$("#action_allbikes").show();
		$("#action_availablebikes").hide();
		$("#action_returnbikes").hide();
		$("#action_tradebikes").hide();
		$("#saveActionBtn").prop("disabled", true);
	}  else if (action == 'trade') {
		$("#action_allbikes").hide();
		$("#action_availablebikes").show();
		$("#action_returnbikes").show();
		$("#action_tradebikes").show();
		$("#saveActionBtn").prop("disabled", true);
	}  else if (action == 'start') {
		$("#action_allbikes").hide();
		$("#action_availablebikes").show();
		$("#action_returnbikes").hide();
		$("#action_tradebikes").hide();
		$("#saveActionBtn").prop("disabled", true);
	}
}

function setSaveDisabled(disable) {
	$("#saveActionBtn").prop("disabled", disable);
}

function resetBikeDivs() {
	$("#action_allbikes").hide();
	$("#action_availablebikes").hide();
	$("#action_returnbikes").hide();
	$("#action_tradebikes").hide();
	$("#saveActionBtn").prop("disabled", true);
}

function resetTransaction() {
	actiontype.val('').trigger('change');
	visibilityActionMemberInfo(false)
	$('#action_date').val(myGetDate());
}

function cancelTransaction() {
	resetTransaction();
	actionmember.val('').trigger('change');
	actionbikein.val('').trigger('change');
	actionbikeout.val('').trigger('change');
}

function saveTransaction() {
	memberoption = actionmember.find('option:selected');
	parentID = memberoption.data('parentid');
	oldBikeID = memberoption.data('bikeid');
	action= actiontype.val();
	kidID = actionmember.val();
	aDate = $('#action_date').val();
	bikeStatus = [];
	updateBike = 0;
	kidStatus = [];
	updateKid = 0;
	parentStatus = [];
	updateParent = 0;
	if (action == 'start') {
		bikeInID = 0;
		bikeOutID = actionbikeout.val();
		kidActive = 1;
		bikeStatus.push({
			'ID': bikeOutID,
			'Status': 'Ontleend'
		});
		updateBike = 1;
	} else if (action == "trade") {
		bikeInID = oldBikeID;
		bikeOutID = actionbikeout.val();
		kidActive = 1;
		bikeStatus.push({
			'ID': bikeOutID,
			'Status': 'Ontleend'
		});
		bikeStatus.push({
			'ID': bikeInID,
			'Status': 'Beschikbaar'
		});
		updateBike = 1;
	} else if (action == "end") {
		bikeInID = oldBikeID;
		bikeOutID = 0;
		kidActive = 0;	
		bikeStatus.push({
			'ID': bikeInID,
			'Status': 'Beschikbaar'
		});	
		updateBike = 1;		
	} else if (action == "donate") {
		bikeInID = actionbikeall.val();
		bikeOutID = 0;
		kidActive = memberoption.data('kidactive');
		updateBike = 0;			
	}
	
	validInput = verifyActionInput(action, kidID, bikeOutID, bikeInID, aDate);
	transactionData = {
		'KidID': kidID,
		'Action': action,
		'BikeInID': bikeInID,
		'BikeOutID': bikeOutID,
		'Date': aDate
	};
	if (action != "donate") {
		updateParent = 1;
		updateKid = 1;
		kidStatus = {
			'ID': kidID,
			'Active': kidActive,
			'Caution': memberoption.data('caution'),
			'ExpiryDate': memberoption.data('expirydate'), 
			'BikeID': bikeOutID
		};
		parentActive = isParentActive(parentID, kidID, kidActive);
		parentStatus = {
			'ID': parentID,
			'Active': parentActive
		};
	}

	console.log('Saving ' + JSON.stringify({
				'transactionData': transactionData,
				'updateKid': updateKid,
				'kidStatus': kidStatus,
				'updateParent': updateParent,
				'parentStatus': parentStatus,
				'updateBike': updateBike,
				'bikeStatus': bikeStatus
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
				'updateParent': updateParent,
				'parentStatus': parentStatus,
				'updateBike': updateBike,
				'bikeStatus': bikeStatus
			}),
			contentType: "application/json",
			success: function () {
				toastr.success('Transactie opgeslagen.');
				loadBikes();
				loadMembers();
				resetTransaction();
				//loadTransactions();
			},
			error: function (data) {
				toastr.error('Er is een fout opgetreden', 'Niet opgeslagen');
				console.error(data);
			}
		});
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
	

function verifyActionInput(action, kidID, bikeOutID, bikeInID, aDate) {
	validInput = true;
	if (kidID<1) {
		validInput = false;
	}
	if (!moment(aDate, 'YYYY-MM-DD', true).isValid()) {
		validInput = false;
	}
	if (action == 'start') {
		if (bikeOutID<1 || bikeInID != 0) {
			validInput = false;
		}
	} else if (action == 'trade') {
		if (bikeOutID<1 || bikeInID <1) {
			validInput = false;
		}
	} else if (action == 'end' || action == 'donate') {
		if (bikeOutID!=0 || bikeInID <1) {
			validInput = false;
		}
	} else {
		validInput = false;
	}
	return validInput;
}