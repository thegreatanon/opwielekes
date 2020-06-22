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

// selectboxes
var actiontype;
var actionmember;
var actionbikeout;
var actionbikeall;
var defaultmembership;
var parentmembership;

// finance variables
var cautionBalance;
var membershipBalance;

var actionDateIsLocked = 0;

// for database
var defaultMembershipID;
var defaultBikeAvailableID;
var defaultBikeOnLoanID;
var replytoemail;
var replytoname;
var ccemail;
var sendername;

var kidsToDelete = [];

// email variables
var newEmailTemplate;

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

function sendEmail(mailData, signup) {
	if (typeof signup !== 'undefined') {
    mailurl = '../sendEmail.php';

	} else {
		mailurl = 'sendEmail.php';
	}
	console.log('sending in global vars');
	console.log(mailData);
	$('#saveActionBtn').button('loading');
	$.ajax({
		type: 'POST',
		url: mailurl,
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
