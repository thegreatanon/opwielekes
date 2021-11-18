// tables
var bikestable;
var kidsexpirytable;
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
var db_properties;

// selectboxes
var actiontype;
var actionmember;
var actionbikeout;
var actionbikeall;
var defaultmembership;
var parentmembership;
var actionmembershipsel;
var bikegender;

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

var today =  moment();

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
	[ 'link'],
	[{ 'indent': '-1'}, { 'indent': '+1' }],
	['clean']                   // remove formatting button
];

var quillToolbarOptions2 = {
        container: [
					[{ size: [ 'small', false, 'large', 'huge' ]}],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'list': 'ordered' }, { 'list': 'bullet' }],
					[ 'link'],
          [{ 'indent': '-1' }, { 'indent': '+1' }],
					['image'],
          ['clean'],
          ['emoji']
        ],
        handlers: {
          'emoji': function () {}
        }
};


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
