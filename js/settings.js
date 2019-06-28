$(document).ready(function () {

	loadSettings();

	settingsemailtype = $('#settings_email_type').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setSettingsEmailInfo();
	}).on('select2:unselect', function() {
		resetSettingsEmailInfo();
	});
	
	settingsemailname = $('#settings_email_name').select2({
		placeholder: "Kies",
		allowClear: true,
		tags: false,
		dropdownAutoWidth: true
	}).on('select2:select', function() {
		setSettingsEmail();
	}).on('select2:unselect', function() {
		resetSettingsEmail();
	});
	
	settingsemailquill = new Quill('#settings_email_message', {
		modules: {
			toolbar: quillToolbarOptions
		},
		theme: 'snow',
		background: 'white'
	});
});


/* SETTINGS */

function loadSettings() {
	loadPrices();
	loadEmails();
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

function loadEmails() {
    $.ajax({
        url: 'api/settings/emails',
        success: function (emails) {
			//setPriceTable(prices);
			db_emails = emails;
			setSettingsEmailNames(emails);
		}
    });
}

function setSettingsEmailTypes(actions){
	settingsemailtype.empty();
	var newOption = new Option('', '', false, false);
	settingsemailtype.append(newOption);
	$(actions).each( function (index, item) {
		var htmlOption = '<option value="' + item.ID + '" data-updatebike="' + item.UpdateBike + '" data-updatekid="' + item.UpdateKid + '" data-updatekidfin="' + item.UpdateKidFin + '" data-updatefin="' + item.UpdateFin + '" data-requirebikein="' + item.RequireBikeIn + '" data-requirebikeout ="' + item.RequireBikeOut + '" data-requirebikeall="' + item.RequireBikeAll + '" data-requiremembership="' + item.RequireMembership + '" data-requirecaution="' + item.RequireCaution + '" data-resultchangeactive="' + item.ResultChangeActive + '" data-resultkidactive="' + item.ResultKidActive + '" data-enablesave="' + item.EnableSave + '" data-donationreceived="' + item.DonationReceived + '" data-demandcaution="' + item.DemandCaution + '" data-returncaution="' + item.ReturnCaution + '" data-emailsend="' + item.EmailSend + '" data-emailid="' + item.EmailID + '">' + item.Name  + '</option>';
		settingsemailtype.append(htmlOption);
	});
}

function setSettingsEmailNames(emails){
	settingsemailname.empty();
	var newOption = new Option('', '', false, false);
	settingsemailname.append(newOption);
	$(emails).each( function (index, item) {
		var htmlOption = '<option value="' + item.ID + '" data-name="' + item.Name + '" data-subject="' + item.Subject + '" data-text="' + item.Text + '" data-cc="' + item.CC+ '">' + item.Name  + '</option>';
		settingsemailname.append(htmlOption);
	});
}

function setSettingsEmailInfo(){
	settingsemailtype = actiontype.find('option:selected');
	emailSend = settingsemailtype.data('emailsend');
	emailID = settingsemailtype.data('emailid');
	var e = db_emails.find(x => x.ID === emailID.toString());
	
}

function resetSettingsEmailInfo(){
	
}

function setSettingsEmail(){
	emailoption = settingsemailname.find('option:selected');
	$('#settings_email_cc').val(emailoption.data('cc'));
	$('#settings_email_subject').val(emailoption.data('subject'));
	settingsemailquill.root.innerHTML = emailoption.data('text');
}

function resetSettingsEmail(){
	$('#settings_email_cc').val("");
	$('#settings_email_subject').val("");
	settingsemailquill.setContents([]);
}