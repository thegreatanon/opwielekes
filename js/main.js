$(document).ready(function () {

	compileHandlebarTemplates();
});

/* HANDLEBARS */

function compileHandlebarTemplates() {
	template_kidsrow = Handlebars.compile($('#kidsrow').html());
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
		main_nav.find('a[href$="#"]').parent().addClass('active');
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
