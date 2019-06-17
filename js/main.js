$(document).ready(function () {

	loadSettings();
	
	compileHandlebarTemplates();
});

/* SETTINGS */

function loadSettings() {
    loadActions();
	loadPrices();
}

function loadActions() {
    $.ajax({
        url: 'api/settings/actions',
        success: function (actions) {
			db_actions = actions;
		}
    });
}

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

/* HANDLEBARS */

function compileHandlebarTemplates() {
	template_kidsrow = Handlebars.compile($('#kidsrow').html());
	template_kidsactionrow = Handlebars.compile($('#kidsactionrow').html());
}

/* organise tabs */
function viewTab(section, amount) {
	if (amount == 'all') {
		document.getElementById('tab' + section + 'One').style.display = 'none';
		document.getElementById('tab' + section + 'All').style.display = 'block';
	} else {
		document.getElementById('tab' + section + 'All').style.display = 'none';
		document.getElementById('tab' + section + 'One').style.display = 'block';
	}
}

/* ROUTING */ 
routie({
    '': function () {
        setPageActive('transactions');
    },
	'transactions': function () {
        setPageActive('transactions');
    },
    'bikes': function () {
        setPageActive('bikes');
    },
	'members': function () {
        setPageActive('members');
    },
	'finances': function () {
        setPageActive('finances');
    },
	'stats': function () {
        setPageActive('stats');
    },
	'settings_prices': function () {
        setPageActive('settings_prices');
    },
	'settings_emails': function () {
        setPageActive('settings_emails');
    },
    'logout': function () {
        setPageActive('logout');
    }
});

/* Bij het navigeren naar een andere pagina, pas de navigatiebalk aan en zet de pagina op actief 
	Als een pagina actief is wordt enkel die getoond, dit staat in de header van main.php */
	
function setPageActive(page) {
    var main_nav = $('#main-nav');
    main_nav.find('li.active').removeClass('active');
	if(page.indexOf('_') != -1) {
		mainpage = page.substr(0, page.indexOf('_'));
		main_nav.find('a[href$="#' + mainpage + '"]').parent().addClass('active');
	} else {
		main_nav.find('a[href$="' + page + '"]').parent().addClass('active');
	}
    $('.content_section.active').removeClass('active');
    $('#content_' + page).addClass('active');
}

// HELP FUNCTIONS

function isEmptyString(mystring) {
	return !mystring.trim().length;
}

function myGetDate() {
    return moment().format('YYYY-MM-DD');
}
