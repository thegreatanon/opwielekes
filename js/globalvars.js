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
var defaultMembershipID = 1;
var replyto = 'maarten@bewustverbuiken.be';

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

function sendEmail(mailData) {
	$('#saveActionBtn').button('loading');
	console.log(' in email');
	console.log(mailData);
	console.log(mailData.message);
	$.ajax({
		type: 'POST',
		url: 'sendEmail.php',
		data: {
			'sendto': [mailData.sendto],
			'replyto': mailData.replyto,
			'replytoname': mailData.replytoname,
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
				$('#saveActionBtn');prop("disabled", true);
			}, 0);
		},
		error: function() {
			toastr.error('Kon email niet verzenden');
			$('#saveActionBtn').button('reset');
		}
	});
}
