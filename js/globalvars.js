// tables
var bikestable;
var memberstable;

// handlebars
var template_kidsrow;
var template_kidsactionrow;

// sql data
var db_kids;
var db_bikes;

// selectboxes
var actiontype;
var actionmember;
var actionbikeout;
var actionbikeall;


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