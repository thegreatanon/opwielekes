// tables
var bikestable;
var expirytable;
var financetable;
var memberstable;

// handlebars
var template_kidsrow;
var template_kidsactionrow;

// sql data
var db_actions;
var db_bikes;
var db_emails;
var db_kids;
var db_parents;
var db_prices;

// selectboxes
var actiontype;
var actionmember;
var actionbikeout;
var actionbikeall;

// finance variables
var cautionBalance;
var membershipBalance;

var actionDateIsLocked = 0;



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