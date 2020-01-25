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

var defaultMembershipID = 1;

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
