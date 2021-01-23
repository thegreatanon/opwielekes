$(document).ready(function () {

	loadActions();

	compileHandlebarTemplates();
});

function loadActions() {
    $.ajax({
        url: 'api/settings/actions',
        success: function (actions) {
					db_actions = actions;
					setSettingsEmailActions(actions);
				}
    });
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
	'kids': function () {
					setPageActive('kids');
			},
	'transactionhistory': function () {
	        setPageActive('transactionhistory');
	    },
	'finances': function () {
        setPageActive('finances');
				financestable.columns.adjust().draw();
    },
	'stats': function () {
        setPageActive('stats');
    },
	'settings_bikes': function () {
				setPageActive('settings_bikes');
		},
	'settings_prices': function () {
        setPageActive('settings_prices');
    },
	'settings_emails': function () {
        setPageActive('settings_emails');
    },
	'settings_memberships': function () {
	        setPageActive('settings_memberships');
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
