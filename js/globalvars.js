// tables
var bikestable;
var expirytable;
var financestable;
var memberstable;
var transactionstable;

// handlebars
var template_kidsrow;
var template_kidsactionrow;

// sql data
var db_actions;
var db_bikes;
var db_emails;
var db_kids;
var db_parents;
var db_memberships;
var db_postalcodes;
var db_bikestatuses;
var db_preferences;
var db_paymentmethods;

// selectboxes
var actiontype;
var actionmember;
var actionbikeout;
var actionbikeall;
var defaultmembership;
var parentmembership;
var actionmembershipsel;

// finance variables
var cautionBalance;
var membershipBalance;
var findateformat = 'DD-MM-YYYY';
var actionDateIsLocked = 0;

// for database
var defaultMembershipID;
var defaultBikeAvailableID;
var defaultBikeOnLoanID;
var defaultPaymentMethod;
var replytoemail;
var replytoname;
var ccemail;
var sendername;

var kidsToDelete = [];


// email variables
var newEmailTemplate;

$(document).ready(function () {
	$.fn.dataTable.moment('DD-MM-YYYY');
});

/* organise tabs */
function viewTab(section, amount) {
	if (amount == 'all') {
		document.getElementById('tab' + section + 'One').style.display = 'none';
		document.getElementById('tab' + section + 'All').style.display = 'block';
		emptyForm(section);
	} else {
		document.getElementById('tab' + section + 'All').style.display = 'none';
		document.getElementById('tab' + section + 'One').style.display = 'block';
	}
}

var quillToolbarOptions = [
	[{ size: [ 'small', false, 'large', 'huge' ]}],
	['bold', 'italic', 'underline'],        // toggled buttons
	[{ 'list': 'ordered'}, { 'list': 'bullet' }],
	[ 'link', 'image' ],
	[{ 'indent': '-1'}, { 'indent': '+1' }],
	['clean']                   // remove formatting button
];

function sendEmail(mailData) {
	$('#saveActionBtn').button('loading');
	$.ajax({
		type: 'POST',
		url: 'sendEmail.php',
		data: {
			'sendto': [mailData.sendto],
			'sendcc': ccemail,
			'replyto' : replytoemail,
			'replytoname' : replytoname,
			'sendername': sendername,
			'subject': mailData.subject,
			'message': mailData.message
		},
		success: function (result) {
			toastr.success('Email verzonden');
			//data = JSON.parse(result);
			//console.log( typeof(data) );
			//console.log( data.success );
			$('#saveActionBtn').button('reset');
			// button doesn't disable after reset
			// https://stackoverflow.com/questions/10707229/jquery-disable-enable-button-not-working-after-reset
			// workaround:
			setTimeout(function() {
				$('#saveActionBtn').prop("disabled", true);
			},0);
		},
		error: function() {
			toastr.error('Kon email niet verzenden');
			$('#saveActionBtn').button('reset');
		}
	});
}

// Quill setting to avoid double newlines in emailSend
// see https://github.com/quilljs/quill/issues/1074
var Block = Quill.import('blots/block');
Block.tagName = 'DIV';
Quill.register(Block, true);

// HELP FUNCTIONS

$.fn.dataTable.moment = function ( format, locale ) {
	var types = $.fn.dataTable.ext.type;

	// Add type detection
	types.detect.unshift( function ( d ) {
			return moment( d, format, locale, true ).isValid() ?
					'moment-'+format :
					null;
	} );

	// Add sorting method - use an integer for the sorting
	types.order[ 'moment-'+format+'-pre' ] = function ( d ) {
			return moment( d, format, locale, true ).unix();
	};
};


function isEmptyString(mystring) {
	return !mystring.trim().length;
}

function myGetDate() {
    return moment().format('DD-MM-YYYY');
}

function convertDate(date) {
	return date.split("-").reverse().join("-");
}

function extendExpiryDate(expirydate) {
	if (!moment(expirydate, 'DD-MM-YYYY', true).isValid()) {
		actiondate = $('#action_date').val();
		expirydate = moment(actiondate, 'DD-MM-YYYY').subtract(1, 'day').format('DD-MM-YYYY');
	}
  return moment(expirydate, 'DD-MM-YYYY').add(1, 'year').format('DD-MM-YYYY');
}
