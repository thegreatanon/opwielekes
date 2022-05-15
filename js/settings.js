$(document).ready(function () {


	// this one is in the members section but is loaded earlier
	parentmembership = $('#parent_membership').select2({
		tags: false
	});

		// this one is in the action section but is loaded earlier
	actionmembershipsel = $('#action_membershipsel').select2({
		allowClear: false,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setActionMembershipType();
	});

	settingsdefaultpaymentselect = $('#settings_defaultpaymentmethod').select2({
		allowClear: false,
		tags: false,
		dropdownAutoWidth: true
	});

	settingsemailaction = $('#settings_email_action').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setSettingsEmailLinks();
	}).on('select2:unselect', function() {
		resetSettingsEmailLinks();
	});

	settingsemailname = $('#settings_email_name').select2({
		placeholder: "Kies of typ een naam voor een nieuwe template ",
		allowClear: true,
		tags: true,
		dropdownAutoWidth: true,
		createTag: function (params) {
    	return {
	      id: params.term,
	      text: params.term,
	      newOption: true
	    }
  	},
		templateResult: function (data) {
			//console.log(data)
	    var $result = $("<span></span>");
	    $result.text(data.text);
	    if (data.newOption) {
	      $result.append(" <em>(new)</em>");
	    }
	    return $result;
  	}
	}).on('select2:select', function(e) {
		setSettingsEmail();
	}).on('select2:unselect', function() {
		resetSettingsEmail(false);
	});

	settingsemaillinktemplate = $('#settings_emaillink_template').select2({
		placeholder: '',
		allowClear: true,
		dropdownAutoWidth: true
	});

	settingsemailquill = new Quill('#settings_email_message', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

 $('#settings_action_form').on('change', '#settings_emaillink_actionsend', function() {
		if ($(this).is(':checked')) {
            $('#settings_emaillink_template').prop('disabled', false);
    } else {
        	settingsemaillinktemplate.val('').change();
					$('#settings_emaillink_template').prop('disabled', true);

    }
	});

	settingssignupquill = new Quill('#settings_membership_signuptext', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

	settingsreminder1quill = new Quill('#settings_membership_reminder1text', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

	settingsreminder2quill = new Quill('#settings_membership_reminder2text', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

	settingsreminder3quill = new Quill('#settings_membership_reminder3text', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

	$(document).on('click', '.addStatusRowBtn', function () {
		var myhtml = getBikestatusHtmlRow();
		$(myhtml).insertAfter($(this).closest('tr'));
  });

	loadSettings();
});


/* SETTINGS */

function loadSettings() {
	loadBikeStatuses();
	loadPrices();
	loadEmails(0);
	loadPreferences();
	loadProperties();
	// loadFAQ();
	// loadSettingsPaymentMethods() called in loadPreferences;
	//loadDefaultPaymentInfo() called in loadPreferences;
}

function loadFAQ() {
	console.log('loading FAQ');
	// 1. Load the JavaScript client library.
//	gapi.load('client', start);
	// 2. Initialize the JavaScript client library.
	// gapi.client.init({
	// 	'apiKey': 'YOUR_API_KEY',
	// 	// clientId and scope are optional if auth is not required.
	// 	'clientId': 'YOUR_WEB_CLIENT_ID.apps.googleusercontent.com',
	// 	'scope': 'profile',
	// }).then(function() {
	// 	// 3. Initialize and make the API request.
	// 	return gapi.client.request({
	// 		'path': 'https://people.googleapis.com/v1/people/me?requestMask.includeField=person.names',
	// 	})
	// }).then(function(response) {
	// 	console.log(response.result);
	// }, function(reason) {
	// 	console.log('Error: ' + reason.result.error.message);
	// });
}

// PRICES
function loadPrices() {
    $.ajax({
        url: 'api/settings/memberships',
        success: function (memberships) {
					setPriceTable(memberships);
					setMembershipTypes(memberships, actionmembershipsel);
					setMembershipTypes(memberships, parentmembership);

					//setDefaultMembership(memberships, defaultmembership);
					db_memberships = memberships;
				}
    });
}

function setPriceTable(memberships) {
	$('#settings_prices_table_tbody').empty();
	var myhtml = '';
	$.each(memberships, function (index, item) {
		myhtml = getPriceRowHTML(item);
		$('#settings_prices_table_tbody').append(myhtml);
	});
}

function addEmptyPriceRow() {
	myhtml = getEmptyPriceRowHTML();
	$('#settings_prices_table_tbody').append(myhtml);
}

function setMembershipTypes(memberships, selectbox) {
	selectbox.empty();
	for (var i = 0, len = memberships.length; i < len; i++) {
		if (memberships[i].Active == 1) {
			var htmlOption = '<option value="' + memberships[i].ID + '">' + memberships[i].MembershipName  + '</option>';
		} else {
			var htmlOption = '<option value="' + memberships[i].ID + '" disabled>' + memberships[i].MembershipName + ' (niet in gebruik)</option>';
		}
		selectbox.append(htmlOption);
	}
	selectbox.trigger('change');
}

function cancelMembershipPrices() {
	setPriceTable(db_memberships)
}

function saveMembershipPrices() {
	if (document.getElementById("settings_prices_form").checkValidity()) {
		var updateMembershipData = [];
		$('#settings_prices_table_tbody').find('tr').each(function () {
			row = $(this);
			updateMembershipData.push({
				'ID': parseFloat(row.data('priceid')),
				'MembershipName': row.find('.price_name input')[0].value,
				'YearsValid': row.find('.price_years input')[0].value,
				'MonthsValid': row.find('.price_months input')[0].value,
				'DaysValid': row.find('.price_days input')[0].value,
				'MembershipK1': row.find('.price_mkid1 input')[0].value,
				'MembershipK2': row.find('.price_mkid2 input')[0].value,
				'MembershipK3': row.find('.price_mkid3 input')[0].value,
				'MembershipK4': row.find('.price_mkid4 input')[0].value,
				'CautionK1': row.find('.price_ckid1 input')[0].value,
				'CautionK2': row.find('.price_ckid2 input')[0].value,
				'CautionK3': row.find('.price_ckid3 input')[0].value,
				'CautionK4': row.find('.price_ckid4 input')[0].value,
				'Active': row.find('.price_active').is(":checked")
			});
		});
		// check if prices are if (!$.isNumeric(price)){ ??
		$.ajax({
			type: 'POST',
			url: 'api/settings/memberships',
			dataType: 'json',
			data: JSON.stringify({
				'updateData': updateMembershipData
			}),
			success: function (result) {
				toastr.success('Prijzen opgeslagen');
				loadPrices();
			},
			error: function() {
				toastr.error('Prijzen niet opgeslagen','Er liep iets fout');
			}
		});
	}
}

function getPriceRowHTML(item) {
	var myhtml = '<tr data-priceid="' + item.ID + '" style="height:100% ">';
	myhtml += '<td class="price_name"><input type="text" value="' + item.MembershipName + '"></td>';
	myhtml += '<td class="price_years"><input type="number" value="' + item.YearsValid + '" step="1" min="0"></td>';
	myhtml += '<td class="price_months"><input type="number" value="' + item.MonthsValid + '" step="1" min="0"></td>';
	myhtml += '<td class="price_days"><input type="number" value="' + item.DaysValid + '" step="1" min="0"></td>';
	myhtml += '<td class="price_mkid1"><input type="number" value="' + item.MembershipK1 + '" step="0.01" min="0"</td>';
	myhtml += '<td class="price_mkid2"><input type="number" value="' + item.MembershipK2 + '" step="0.01" min="0"</td>';
	myhtml += '<td class="price_mkid3"><input type="number" value="' + item.MembershipK3 + '" step="0.01" min="0"</td>';
	myhtml += '<td class="price_mkid4"><input type="number" value="' + item.MembershipK4 + '" step="0.01" min="0"</td>';
	myhtml += '<td class="price_ckid1"><input type="number" value="' + item.CautionK1 + '" step="0.01" min="0"></td>';
	myhtml += '<td class="price_ckid2"><input type="number" value="' + item.CautionK2 + '" step="0.01" min="0"></td>';
	myhtml += '<td class="price_ckid3"><input type="number" value="' + item.CautionK3 + '" step="0.01" min="0"></td>';
	myhtml += '<td class="price_ckid4"><input type="number" value="' + item.CautionK4 + '" step="0.01" min="0"></td>';
	myhtml += '<td><input type="checkbox" class="price_active"';
	if  (item.Active == 1) {
		myhtml += ' checked></td>';
	} else {
		myhtml += '></td>';
	}
	myhtml += '</tr>';
	return myhtml;
}

function getEmptyPriceRowHTML() {
	emptyitem = {
		"ID" : 0,
		"MembershipName" : "Nieuw Tarief",
		"YearsValid" : 1,
		"MonthsValid" : 0,
		"DaysValid" : 0,
		"MembershipK1" : "20.00",
		"MembershipK2" : "20.00",
		"MembershipK3" : "20.00",
		"MembershipK4" : "20.00",
		"CautionK1" : "10.00",
		"CautionK2" : "10.00",
		"CautionK3" : "10.00",
		"CautionK4" : "10.00",
		"Active" : 1
	}
	myhtml = getPriceRowHTML(emptyitem);
	return myhtml;
}

// EMAILS
function loadEmails(lastid) {
    $.ajax({
        url: 'api/settings/emails',
        success: function (emails) {
					//setPriceTable(prices);
					db_emails = emails;
					setSettingsEmailNames(emails, lastid);
				}
    });
}

function setSettingsEmailNames(emails,lastid){
	settingsemailname.empty();
	settingsemaillinktemplate.empty();
	settingsemailname.append(new Option('', '', false, false));
	settingsemaillinktemplate.append(new Option('', '', false, false));
	$(emails).each( function (index, item) {
//var htmlOption = '<option value="' + item.ID + '" data-name="' + item.Name + '" data-subject="' + item.Subject + '" data-text="' + item.Text + '" data-cc="' + item.CC+ '">' + item.Name  + '</option>';
		var htmlOption = '<option value="' + item.ID + '">' + item.Name  + '</option>';
		settingsemailname.append(htmlOption);
		settingsemaillinktemplate.append(htmlOption);
	});
	newEmailTemplate = false;
	if (lastid != 0) {
		settingsemailname.val(lastid).trigger('change');
	} else {
			settingsemailname.trigger('change');
	}
	settingsemaillinktemplate.trigger('change');
}

function setSettingsEmail(){
	emailoption = settingsemailname.find(':selected');
	if (emailoption.data('select2-tag')==null) {
		emailid = emailoption.val();
		var emailinfo = db_emails.filter(x => x.ID === emailid)[0];
		//console.log(emailinfo);
		$('#settings_email_subject').val(emailinfo.Subject);
		settingsemailquill.root.innerHTML = emailinfo.Text;
		newEmailTemplate = false;
	} else {
		resetSettingsEmailFields(true)
	}
}

function resetSettingsEmail(isNewEmail){
	resetSettingsEmailFields(isNewEmail)
	settingsemailname.val('').change();
}

function resetSettingsEmailFields(isNewEmail){
	$('#settings_email_subject').val("");
	settingsemailquill.setContents([]);
	newEmailTemplate = isNewEmail;
}

function deleteEmail(){
	emailoption = settingsemailname.find(':selected');
	if (confirm("Ben je zeker dat je deze email template wil verwijderen?")) {
		if (emailoption.data('select2-tag')==null) {
			$.ajax({
				type: 'POST',
				url: 'api/settings/deleteemail/' + emailoption.val(),
				success: function () {
					loadEmails(0);
					resetSettingsEmailFields(false);
					toastr.success('Emailtemplate verwijderd.');
				},
				error: function () {
					console.error();
				}
			});
		} else {
			resetSettingsEmail(false)
		}
	}
}

function cancelEmail(){
	resetSettingsEmail(false);
}

function saveEmail(){
	emailoption = settingsemailname.find(':selected');
	if (emailoption.val() == "") {
		toastr.error('Geen template geselecteerd.')
		return;
	}
	if (emailoption.data('select2-tag')==true) {
		emailid = 0;
		emailname = emailoption.text();
	} else {
		//console.log('updating existing email template');
		emailid = emailoption.val();
		emailname = emailoption.text();
	}
  $.ajax({
		type: 'POST',
		url: 'api/settings/email',
		data: JSON.stringify({
			'ID': emailid,
			'Name': emailname,
			'Subject': $('#settings_email_subject').val(),
			'Text':  settingsemailquill.root.innerHTML
		}),
		contentType: "application/json",
		success: function (lastid) {
			toastr.success('Emailtemplate opgeslagen.');
			loadEmails(lastid);
		},
		error: function (data) {
			console.error(data);
		}
	});
}

// LINK EMAILS TO ACTIONS

function setSettingsEmailActions(actions){
	settingsemailaction.empty();
	var newOption = new Option('', '', false, false);
	settingsemailaction.append(newOption);
	$(actions).each( function (index, item) {
		var htmlOption = '<option value="' + item.ID + '" data-updatebike="' + item.UpdateBike + '" data-updatekid="' + item.UpdateKid + '" data-updatekidfin="' + item.UpdateKidFin + '" data-updatefin="' + item.UpdateFin + '" data-requirebikein="' + item.RequireBikeIn + '" data-requirebikeout ="' + item.RequireBikeOut + '" data-requirebikeall="' + item.RequireBikeAll + '" data-requiremembership="' + item.RequireMembership + '" data-requirecaution="' + item.RequireCaution + '" data-resultchangeactive="' + item.ResultChangeActive + '" data-resultkidactive="' + item.ResultKidActive + '" data-enablesave="' + item.EnableSave + '" data-checkmembership="' + item.CheckMembership + '" data-demandcaution="' + item.DemandCaution + '" data-returncaution="' + item.ReturnCaution + '" data-emailsend="' + item.EmailSend + '" data-emailid="' + item.EmailID + '">' + item.Name  + '</option>';
		settingsemailaction.append(htmlOption);
	});
}

function setSettingsEmailLinks(){
	actiontype = settingsemailaction.find(':selected');
	emailSend = actiontype.data('emailsend');
	emailID = actiontype.data('emailid');
	if (emailSend == 1) {
		$('#settings_emaillink_actionsend').prop('checked', true);
				$('#settings_emaillink_template').prop('disabled', false);
		if (emailID != 0) {
			settingsemaillinktemplate.val(emailID).trigger('change');
		} else {
			settingsemaillinktemplate.val('').change();
		}
	} else {
		$('#settings_emaillink_template').prop('disabled', true);
		$('#settings_emaillink_actionsend').prop('checked', false);
		settingsemaillinktemplate.val('').change();

	}
}

function resetSettingsEmailLinks(){
	$('#settings_emaillink_actionsend').prop('checked', false);
	settingsemaillinktemplate.val('').change();
	$('#settings_emaillink_template').prop('disabled', true);
}

function cancelEmailLink(){
		settingsemailaction.val('').change();
		resetSettingsEmailLinks();
}

function saveEmailLink() {
	action = settingsemailaction.find(':selected').val();
	send = $('#settings_emaillink_actionsend').is(':checked');
	sendint = send ? 1 : 0;
	template =	settingsemaillinktemplate.val();
	if (sendint == 0) {
		template=0;
	}
	console.log('action ' + action + ' send ' + sendint + ' templ ' + template)
	$.ajax({
		type: 'POST',
		url: 'api/settings/emailsettings',
		data: JSON.stringify({
			'Action': action,
			'Send': sendint,
			'Template': template
		}),
		contentType: "application/json",
		success: function () {
			toastr.success('Email koppeling opgeslagen.');
			loadActions();
			resetSettingsEmailLinks();
		},
		error: function (data) {
			console.error(data);
		}
	});
}

// BIKE Status
function loadBikeStatuses() {
    $.ajax({
        url: 'api/settings/bikestatuses',
        success: function (bikestatuses) {
					db_bikestatuses = bikestatuses;
					setBikeStatusTable(bikestatuses);
					var loanstatus = bikestatuses.filter(x => x.OnLoan === '1');
					defaultBikeOnLoanID = loanstatus[0].ID;
					//setDefaultBikestatus();
				}
    });
}

function setBikeStatusTable(bikestatuses) {
	$('#settings_bikes_status_table_tbody').empty();
	var myhtml = '';
	$.each(bikestatuses, function (index, item) {
		if (item.OnLoan == 1) {
			disa = ' disabled';
		} else {
			disa = '';
		}
		myhtml = '<tr data-statusid="' + item.ID + '" data-onloan="' + item.OnLoan + '" style="height:100%">';
		myhtml += '<td class="status_name"><input type="text" value="' + item.Name + '"' + disa + '></td>';
		myhtml += '<td><input type="checkbox" class="status_available"' + disa;
		if  (item.Available == 1) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		myhtml += '<td><input type="checkbox" class="status_active"'+ disa;
		if  (item.Active == 1) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		//myhtml += '<td><button type="button" class="btn btn-default addStatusRowBtn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></td>';
		myhtml += '</tr>';
		$('#settings_bikes_status_table_tbody').append(myhtml);
	});
}

function getBikestatusHtmlRow(){

}


function cancelBikeStatusSettings(){
	setBikeStatusTable(db_bikestatuses)
}

function saveBikeStatusSettings(){
	var updateStatusData = [];
	var row;
	$('#settings_bikes_status_table_tbody').find('tr').each(function () {
		row = $(this);
		updateStatusData.push({
			'ID': parseFloat(row.data('statusid')),
			'Name': row.find('.status_name input')[0].value,
			'Available': row.find('.status_available').is(":checked"),
			'Active': row.find('.status_active').is(":checked")
		});
	});
	$.ajax({
		type: 'POST',
		url: 'api/settings/bikestatuses',
		dataType: 'json',
		data: JSON.stringify({
			'statusData': updateStatusData
		}),
		success: function (result) {
			toastr.success('Statussen opgeslagen');
			loadBikeStatuses();
			loadBikes();
		},
		error: function() {
			toastr.error('Statussen niet opgeslagen','Er liep iets fout');
		}
	});
}

// TODO delete when pdf upload complete
// BIKE imagesfunction cancelBikeStatusSettings(){
// function setBikeImageSettings(){
// 	showimages = getProperty('bike_show_image');
// 	$('#settings_bike_show_image').prop('checked', showimages.value);
// }
//
// function cancelBikeImageSettings(db_bikestatuses){
// 	setBikeImageSettings();
// }
//
// function saveBikeImageSettings(){
// 	var updatePropertiesData = [];
// 	updatePropertiesData.push({
// 		'ID': parseFloat(13),
// 		'Value': $('#settings_bike_show_image').is(":checked").toString()
// 	});
// 	$.ajax({
// 		type: 'POST',
// 		url: 'api/settings/properties',
// 		dataType: 'json',
// 		data: JSON.stringify({
// 			'updateData': updatePropertiesData
// 		}),
// 		success: function (result) {
// 			toastr.success('Instellingen ivm afbeeldingen opgeslagen');
// 			loadProperties();
// 		},
// 		error: function() {
// 			toastr.error('Instellingen ivm afbeeldingen niet opgeslagen','Er liep iets fout');
// 		}
// 	});
// }



// PAYMENT METHODS

function loadPaymentMethods() {
    $.ajax({
        url: 'api/settings/paymentmethods',
        success: function (paymentmethods) {
					db_paymentmethods = paymentmethods;
					loadSettingsPayments();
					loadActionPaymentMethods();
				}
    });
}

function loadSettingsPayments() {
	setSettingsPaymentMethodsTable();
	setSettingsdDefaultPaymentInfo();
}


function setSettingsdDefaultPaymentInfo(){
	settingsdefaultpaymentselect.empty();
	$.each(db_paymentmethods, function (index, item) {
		if (item.PaymentMethodActive == 1) {
			var newOption = new Option(item.PaymentMethodName, item.PaymentMethodID, false, false);
			settingsdefaultpaymentselect.append(newOption).trigger('change');
		}
	});
	settingsdefaultpaymentselect.val(defaultPaymentMethod).trigger('change');
	$('#settings_defaultIBAN').val(db_preferences.DefaultIBAN);
}

function cancelDefaultPaymentInfo(){
	setSettingsdDefaultPaymentInfo();
}

function saveDefaultPaymentInfo(){
	$.ajax({
		type: 'POST',
		url: 'api/settings/preferences/defaultpayment',
		dataType: 'json',
		data: JSON.stringify({
			'DefaultPaymentMethod': settingsdefaultpaymentselect.val(),
			'DefaultIBAN':	$('#settings_defaultIBAN').val()
		}),
		success: function (result) {
			toastr.success('Standaard betaalgegevens opgeslagen');
			loadPreferences();
		},
		error: function() {
			toastr.error('Standaard betaalgegevens niet opgeslagen','Er liep iets fout');
		}
	});
}


function setSettingsPaymentMethodsTable() {
	$('#settings_paymentmethods_table_tbody').empty();
	var myhtml = '';
	$.each(db_paymentmethods, function (index, item) {
		//if it is the default, you can not deactivate it
		if (item.PaymentMethodID == defaultPaymentMethod) {
			acttext = ' disabled';
		} else {
			acttext = '';
		}
		myhtml = '<tr data-paymentmethodid="' + item.PaymentMethodID + '" style="height:100%">';
		myhtml += '<td class="paymentmethod_name"><input type="text" value="' + item.PaymentMethodName + '"' + '></td>';
		myhtml += '<td><input type="checkbox" class="paymentmethod_active"' + acttext;
		if  (item.PaymentMethodActive == 1) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		myhtml += '<td><input type="checkbox" class="paymentmethod_immediate"';
		if (item.PaymentMethodImmediate == 1) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		myhtml += '<td><input type="checkbox" class="paymentmethod_waivemembership"';
		if  (item.PaymentMethodWaiveMembership == 1) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		myhtml += '<td><input type="checkbox" class="paymentmethod_donation"';
		if  (item.PaymentMethodDonation == 1) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		//myhtml += '<td><button type="button" class="btn btn-default addStatusRowBtn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></td>';
		myhtml += '</tr>';
		$('#settings_paymentmethods_table_tbody').append(myhtml);
	});
}


function cancelPaymentMethods(){
	setPaymentMethodsTable(db_paymentmethods)
}

function savePaymentMethods(){
	var updatePaymentMethodsData = [];
	var row;
	$('#settings_paymentmethods_table_tbody').find('tr').each(function () {
		row = $(this);
		updatePaymentMethodsData.push({
			'PaymentMethodID': parseFloat(row.data('paymentmethodid')),
			'PaymentMethodName': row.find('.paymentmethod_name input')[0].value,
			'PaymentMethodActive': row.find('.paymentmethod_active').is(":checked"),
			'PaymentMethodImmediate': row.find('.paymentmethod_immediate').is(":checked"),
			'PaymentMethodWaiveMembership': row.find('.paymentmethod_waivemembership').is(":checked"),
			'PaymentMethodDonation': row.find('.paymentmethod_donation').is(":checked")
		});
	});
	$.ajax({
		type: 'POST',
		url: 'api/settings/paymentmethods',
		dataType: 'json',
		data: JSON.stringify({
			'updateData': updatePaymentMethodsData
		}),
		success: function (result) {
			toastr.success('Betaalmethoden opgeslagen');
			loadPreferences();
		},
		error: function() {
			toastr.error('Betaalmethoden niet opgeslagen','Er liep iets fout');
		}
	});
}

	// PREFERENCES

function loadPreferences() {
    $.ajax({
        url: 'api/settings/preferences',
        success: function (preferences) {
					setEmailGlobalVars(preferences);
					setSettingsEmailPreferences(preferences);
					setSettingsEmailReminders(preferences);
					db_preferences = preferences;
					defaultPaymentMethod = preferences.DefaultPaymentMethod;
					loadPaymentMethods();
				}
    });
}

function setEmailGlobalVars(preferences) {
		defaultMembershipID = preferences.DefaultMembership;
		defaultBikeAvailableID = preferences.DefaultBikestatusOnReturn;
		defaultBikeOnLoanID = preferences.DefaultBikestatusOnLoan;
		ccemail = preferences.EmailCC;
		replytoemail = preferences.EmailReplyTo;
		replytoname = preferences.EmailReplyToName;
		sendername = preferences.SenderName;
}

function setSettingsEmailPreferences(preferences) {
		$('#settings_email_replytoname').val(preferences.EmailReplyToName);
		$('#settings_email_replytoemail').val(preferences.EmailReplyTo);
		$('#settings_email_cc').val(preferences.EmailCC);
		$('#settings_email_sendername').val(preferences.SenderName);
	}

function cancelEmailPreferences(){
	setSettingsEmailPreferences(db_preferences);
}

function saveEmailPreferences(){
	$.ajax({
		type: 'POST',
		url: 'api/settings/preferences/emails',
		dataType: 'json',
		data: JSON.stringify({
			'replytoemail': $('#settings_email_replytoemail').val(),
			'replytoname': $('#settings_email_replytoname').val(),
			'ccemail': $('#settings_email_cc').val(),
			'sendername': $('#settings_email_sendername').val()
		}),
		success: function (result) {
			toastr.success('Email instellingen opgeslagen');
			loadPreferences();
		},
		error: function() {
			toastr.error('Email instellingen niet opgeslagen','Er liep iets fout');
		}
	});
}

function setSettingsEmailReminders(preferences) {		// Membership reminders
		$('#settings_membership_signupsend').prop('checked', (preferences.SignupSend=="1") ? true : false);
		$('#settings_membership_signupsubject').val(preferences.SignupSubject);
		settingssignupquill.root.innerHTML = preferences.SignupMessage;
		$('#settings_membership_reminder1send').prop('checked', (preferences.Reminder1Send=="1") ? true : false);
		$('#settings_membership_reminder1days').val(preferences.Reminder1Days).trigger('change');
		$('#settings_membership_reminder1afterexp').val(preferences.Reminder1AfterExp).trigger('change');
		$('#settings_membership_reminder1subject').val(preferences.Reminder1Subject);
		settingsreminder1quill.root.innerHTML = preferences.Reminder1Message;
		$('#settings_membership_reminder2send').prop('checked', (preferences.Reminder2Send=="1") ? true : false);
		$('#settings_membership_reminder2days').val(preferences.Reminder2Days).trigger('change');
		$('#settings_membership_reminder2afterexp').val(preferences.Reminder2AfterExp).trigger('change');
		$('#settings_membership_reminder2subject').val(preferences.Reminder2Subject);
		settingsreminder2quill.root.innerHTML = preferences.Reminder2Message;
		$('#settings_membership_reminder3send').prop('checked', (preferences.Reminder3Send=="1") ? true : false);
		$('#settings_membership_reminder3days').val(preferences.Reminder3Days).trigger('change');
		$('#settings_membership_reminder3afterexp').val(preferences.Reminder3AfterExp).trigger('change');
		$('#settings_membership_reminder3subject').val(preferences.Reminder3Subject);
		settingsreminder3quill.root.innerHTML = preferences.Reminder3Message;
		if (preferences.SendReminders=="1") {
			document.getElementById('SendRemindersOffMsg').style.display = 'none';
			$('#settings_membership_reminder1send').prop('disabled', false)
			$('#settings_membership_reminder2send').prop('disabled', false)
			$('#settings_membership_reminder3send').prop('disabled', false)
			document.getElementById("settings_membership_testreminders").disabled = false;
		} else {
			document.getElementById('SendRemindersOffMsg').style.display = 'block';
			$('#settings_membership_reminder1send').prop('disabled', true)
			$('#settings_membership_reminder2send').prop('disabled', true)
			$('#settings_membership_reminder3send').prop('disabled', true)
			document.getElementById("settings_membership_testreminders").disabled = true;
		}

}

function cancelEmailReminders(){
	setSettingsEmailReminders(db_preferences)
}

function testEmailReminders(){
	$.ajax({
		type: 'POST',
		url: 'runcron.php',
		data: {
			'mode': 'test'
			//,
			//'emailto': 'mdepypere@gmail.com',
			//'testdate': '2021-03-02'
		},
		success: function (result) {
			toastr.success('Test verzonden');
		},
		error: function() {
			toastr.error('Test niet verzonden','Er liep iets fout');
		}
	});
}

function saveEmailReminders(){
	$.ajax({
		type: 'POST',
		url: 'api/settings/preferences/reminders',
		dataType: 'json',
		data: JSON.stringify({
			'signupsend': $('#settings_membership_signupsend').is(':checked'),
			'signupsubject' : $('#settings_membership_signupsubject').val(),
			'signupmessage' : settingssignupquill.root.innerHTML,
			'reminder1send': $('#settings_membership_reminder1send').is(':checked'),
			'reminder1days': $('#settings_membership_reminder1days').val(),
			'reminder1afterexp': $('#settings_membership_reminder1afterexp').val(),
			'reminder1subject' : $('#settings_membership_reminder1subject').val(),
			'reminder1message' : settingsreminder1quill.root.innerHTML,
			'reminder2send': $('#settings_membership_reminder2send').is(':checked'),
			'reminder2days': $('#settings_membership_reminder2days').val(),
			'reminder2afterexp': $('#settings_membership_reminder2afterexp').val(),
			'reminder2subject' : $('#settings_membership_reminder2subject').val(),
			'reminder2message' : settingsreminder2quill.root.innerHTML,
			'reminder3send': $('#settings_membership_reminder3send').is(':checked'),
			'reminder3days': $('#settings_membership_reminder3days').val(),
			'reminder3afterexp': $('#settings_membership_reminder3afterexp').val(),
			'reminder3subject' : $('#settings_membership_reminder3subject').val(),
			'reminder3message' : settingsreminder3quill.root.innerHTML
		}),
		success: function (result) {
			toastr.success('Automatische emails opgeslagen');
			loadPreferences();
		},
		error: function() {
			toastr.error('Automatische emails niet opgeslagen','Er liep iets fout');
		}
	});
}

function loadProperties() {
    $.ajax({
        url: 'api/settings/properties',
        success: function (properties) {
					db_properties = properties;
					// set settings tab
					setBikePropertiesTable();
					// set bikes tab
					setBikeBasicsDiv();
					setBikeFieldsDiv();
					setBikesTableColumns(bikestable);
				}
    });
}

function setBikePropertiesTable() {
	var descriptions = ['Foto','Frame', 'Wiel', 'Band', 'Merk', 'Gender', 'Kleur', 'Versnellingen','Locatie','Datum ingebracht ','Datum laatste ontlening'];
	var bikeproperties = ['bike_show_image','bike_show_frame','bike_show_wheel','bike_show_tyre','bike_show_brand','bike_show_gender','bike_show_colour','bike_show_gears','bike_show_location','bike_show_initdate','bike_show_loandate'];
	$('#settings_bikes_properties_table_tbody').empty();
	var myhtml = '';
	$.each(descriptions, function (index, item) {
		prop = getProperty(bikeproperties[index]);
		myhtml = '<tr data-propid="' + prop.ID + '" style="height:100%">';
		myhtml += '<td>' + item + '</td>';
		myhtml += '<td><input type="checkbox" class="property_visible"';
		if (prop.value == true) {
			myhtml += ' checked></td>';
		} else {
			myhtml += '></td>';
		}
		myhtml += '</tr>';
		$('#settings_bikes_properties_table_tbody').append(myhtml);
	});
}

function cancelBikeProperties(){
	setBikePropertiesTable();
}

function saveBikeProperties(){
	var bikeID = $('#bike_id').val();
	var updatePropertiesData = [];
	var row;
	$('#settings_bikes_properties_table_tbody').find('tr').each(function () {
		row = $(this);
		updatePropertiesData.push({
			'ID': parseFloat(row.data('propid')),
			'Value': row.find('.property_visible').is(":checked").toString()
		});
	});
	$.ajax({
		type: 'POST',
		url: 'api/settings/properties',
		dataType: 'json',
		data: JSON.stringify({
			'updateData': updatePropertiesData
		}),
		success: function (result) {
			toastr.success('Eigenschappen opgeslagen');
			loadProperties();
			if (bikeID == 0 || bikeID == 'undefined') {
				loadBikes();
			} else {
				loadBikes(bikeID);
			}
			loadBikes();
		},
		error: function() {
			toastr.error('Eigenschappen niet opgeslagen','Er liep iets fout');
		}
	});

}

function getProperty(key) {
	var prop = db_properties.find(x => x.Setting === key.toString());
	if (prop.Type == 'boolean')  {
		propval = (prop.Value === 'true')
	} else if (prop.Type == 'int') {
		propval = parseInt(prop.Value);
	} else {
		propval = prop.Value;
	}
    return {
        value: propval,
        ID: prop.ID
    };
}
