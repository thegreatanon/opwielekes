$(document).ready(function () {

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

	loadSettings();
});


/* SETTINGS */

function loadSettings() {
	loadPrices();
	loadEmails(0);
}

// PRICES

function loadPrices() {
    $.ajax({
        url: 'api/settings/prices',
        success: function (prices) {
			setPriceTable(prices);
			db_prices = prices;
		}
    });
}

function setPriceTable(prices) {
	$('#settings_prices_table_tbody').empty();
	var myhtml = '';
	$.each(prices, function (index, item) {
		myhtml = getPriceRowHTML(item);
		$('#settings_prices_table_tbody').append(myhtml);
	});
}

function cancelPrices() {
	setPriceTable(db_prices)
}

function savePrices() {
	if (document.getElementById("settings_prices_form").checkValidity()) {
		var updatePriceData = [];
		$('#settings_prices_table_tbody').find('tr').each(function () {
			row = $(this);
			updatePriceData.push({
				'ID': parseFloat(row.data('priceid')),
				'Kid1': row.find('.price_kid1 input')[0].value,
				'Kid2': row.find('.price_kid2 input')[0].value,
				'Kid3': row.find('.price_kid3 input')[0].value,
				'Kid4': row.find('.price_kid4 input')[0].value
			});
		});

		// check if prices are if (!$.isNumeric(price)){ ??
		$.ajax({
			type: 'POST',
			url: 'api/settings/prices',
			dataType: 'json',
			data: JSON.stringify({
				'updateData': updatePriceData
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
	myhtml += '<td>' + item.Rate + '</td>';
	myhtml += '<td>' + item.Type + '</td>';
	if (item.Rate != "Normaal") {
		myhtml += '<td class="price_kid1"><input type="number" value="' + item.Kid1 + '" step=".01" min="0" disabled></td>';
		myhtml += '<td class="price_kid2"><input type="number" value="' + item.Kid2 + '" step=".01" min="0"  disabled></td>';
		myhtml += '<td class="price_kid3"><input type="number" value="' + item.Kid3 + '" step=".01" min="0" disabled></td>';
		myhtml += '<td class="price_kid4"><input type="number" value="' + item.Kid4 + '" step=".01" min="0" disabled></td>';
	} else {
		myhtml += '<td class="price_kid1"><input type="number" value="' + item.Kid1 + '" step=".01" min="0"></td>';
		myhtml += '<td class="price_kid2"><input type="number" value="' + item.Kid2 + '" step=".01" min="0"></td>';
		myhtml += '<td class="price_kid3"><input type="number" value="' + item.Kid3 + '" step=".01" min="0"></td>';
		myhtml += '<td class="price_kid4"><input type="number" value="' + item.Kid4 + '" step=".01" min="0"></td>';
	}
	myhtml += '</tr>';
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
	var newOption = new Option('', '', false, false);
	settingsemailname.append(newOption);
	settingsemaillinktemplate.append(newOption);
	$(emails).each( function (index, item) {
		var htmlOption = '<option value="' + item.ID + '" data-name="' + item.Name + '" data-subject="' + item.Subject + '" data-text="' + item.Text + '" data-cc="' + item.CC+ '">' + item.Name  + '</option>';
		settingsemailname.append(htmlOption);
		settingsemaillinktemplate.append(htmlOption);
	});
	newEmailTemplate = false;
	if (lastid != 0) {
		settingsemailname.val(lastid).trigger('change');
	}
}

function setSettingsEmail(){
	emailoption = settingsemailname.find(':selected');
	if (emailoption.data('select2-tag')==null) {
		$('#settings_email_cc').val(emailoption.data('cc'));
		$('#settings_email_subject').val(emailoption.data('subject'));
		settingsemailquill.root.innerHTML = emailoption.data('text');
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
	$('#settings_email_cc').val("");
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
			'Text':  settingsemailquill.root.innerHTML,
			'CC': $('#settings_email_cc').val()
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
		var htmlOption = '<option value="' + item.ID + '" data-updatebike="' + item.UpdateBike + '" data-updatekid="' + item.UpdateKid + '" data-updatekidfin="' + item.UpdateKidFin + '" data-updatefin="' + item.UpdateFin + '" data-requirebikein="' + item.RequireBikeIn + '" data-requirebikeout ="' + item.RequireBikeOut + '" data-requirebikeall="' + item.RequireBikeAll + '" data-requiremembership="' + item.RequireMembership + '" data-requirecaution="' + item.RequireCaution + '" data-resultchangeactive="' + item.ResultChangeActive + '" data-resultkidactive="' + item.ResultKidActive + '" data-enablesave="' + item.EnableSave + '" data-donationreceived="' + item.DonationReceived + '" data-demandcaution="' + item.DemandCaution + '" data-returncaution="' + item.ReturnCaution + '" data-emailsend="' + item.EmailSend + '" data-emailid="' + item.EmailID + '">' + item.Name  + '</option>';
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
