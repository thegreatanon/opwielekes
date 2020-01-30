$(document).ready(function () {

	// INIT BIKES TABLE
	memberstable = $('#members_table').DataTable({
        paging: true,
				pageLength: 25,
				"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Alle"]],
				ordering: true,
				sortable: true,
				rowId: 'parentID',
				dom: '<l<"filtermembers">fr>tip',
				"order": [[ 0, 'asc' ], [ 1, 'asc' ]],
				autoWidth: true,
	        columns: [
						{data: 'Name', name: 'Number'},
						{data: 'Surname', name: 'Name'},
						{data: 'Street', name: 'Street'},
						{ data: {
								MembershipID: 'MembershipID',
								MembershipName: 'MembershipName'
			         },
	              render: function (data, type) {
	                  return data.MembershipName;
	              },
								sortable: true
			      },
						{data: 'ActiveKids', name: 'ActiveKids'},
						{data: 'CautionAmount', name: 'CautionAmount'},
						{data: 'Donations', name: 'Donations'},
						{ data: {
								ID: 'ID',
								InitDate: 'InitDate'
			         },
	              render: function (data, type) {
	                  return '<button type="button" class="btn btn-default editMember">Bewerk</button>';
	              },
								sortable: false
			      }
	        ],
				"search": {
					"regex": true,
					"smart":false
				}
    });

	/* FILTER MEMBERS TABLE */
	$('.filtermembers').on('change', function() {
        memberstable.draw();
    });

	$("div.filtermembers").html('<input type="checkbox" id="membersfilteractive" checked > Actief <input type="checkbox" id="membersfilterinactive" checked> Inactief');

	/* Custom filtering function for datatablesr items-table with lowstockcheckbox */
	$.fn.dataTable.ext.search.push(
		function( settings, data ) {
			/* for orders */
			if (settings.nTable.id == 'members_table') {
				var state = data[4];
				if (state >= 1) {
					if ($('#membersfilteractive').is(':checked')){
						return true;
					} else
						return false;
					}
				else if (state == 0) {
					if ($('#membersfilterinactive').is(':checked')){
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			}
			return true;
		}
	);


	$('#parentdatepicker').datetimepicker({
		//locale: 'nl',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	$(document).on('click', '.editMember', function () {
		rowdata = memberstable.row( $(this).closest('tr') ).data();
    setMemberForm(rowdata);
  });

	loadMembers();

});


function loadMembers() {
	loadKids();
	$.ajax({
        url: 'api/members/all',
        success: function (members) {
					loadParents(members);
					//setActionMembers(members);
				}
    });
}

function loadParents(members) {
    $.ajax({
        url: 'api/parents',
        success: function (parents) {
						memberstable.clear();
						memberstable.rows.add(parents);
						memberstable.columns.adjust().draw();
						db_parents = parents;
						setActionMembers(members);
					}
    });
}

function loadKids() {
    $.ajax({
        url: 'api/kids',
        success: function (kids) {
			db_kids = kids;
			//console.log('kids:');
			//console.log(kids);
		}
    });
}

function newMember() {
	emptyMemberForm();
	addNewKidRow();
	viewTab('Members','one');
}

function setMemberForm(rowdata) {
	$('#parent_id').val(rowdata.ID);
	$('#parent_name').val(rowdata.Name);
	$('#parent_surname').val(rowdata.Surname);
	$('#parent_street').val(rowdata.Street);
	$('#parent_streetnr').val(rowdata.StreetNumber);
	$('#parent_postal').val(rowdata.Postal);
	$('#parent_town').val(rowdata.Town);
	$('#parent_email').val(rowdata.Email);
	$('#parent_phone').val(rowdata.Phone);
	$('#parent_date').val(rowdata.InitDate);
	$('#parent_membership').val(rowdata.MembershipID);
	$('#parent_membership').trigger('change');
	setKidForm(rowdata.ID);
	viewTab('Members','one');
}

function setMemberFormByID(parentID){
	$('#parent_id').val(parentID);
	var p = db_parents.find(x => x.ID === parentID.toString());
	$('#parent_name').val(p.Name);
	$('#parent_surname').val(p.Surname);
	$('#parent_street').val(p.Street);
	$('#parent_streetnr').val(p.StreetNumber);
	$('#parent_postal').val(p.Postal);
	$('#parent_town').val(p.Town);
	$('#parent_email').val(p.Email);
	$('#parent_phone').val(p.Phone);
	$('#parent_date').val(p.InitDate);
	$('#parent_membership').val(p.MembershipID);
	$('#parent_membership').trigger('change');
	setKidForm(parentID);
	viewTab('Members','one');
}

function viewMember() {
	parentID = $('#action_parentid').val();
	console.log('action_parentid ' + parentID);
	setMemberFormByID(parentID);
}

function setKidForm(parentID) {
	// find kids
	var kids = db_kids.filter(x => x.ParentID === parentID);
	// edit form
	$('#kids_table_tbody').empty();
	for (var i = 0, len = kids.length; i < len; i++) {
		addKidItem(kids[i]);
	}
}

function emptyMemberForm() {
	// parent
	$('#parent_id').val(0);
	$('#parent_name').val('');
	$('#parent_surname').val('');
	$('#parent_street').val('');
	$('#parent_streetnr').val('');
	$('#parent_postal').val('');
	$('#parent_town').val('');
	$('#parent_email').val('');
	$('#parent_phone').val('');
	$('#parent_date').val(myGetDate());
	$('#parent_membership').val(defaultMembershipID);
	$('#parent_membership').trigger('change');
	// kids
	$('#kids_table_tbody').empty();

}

function cancelMember() {
	emptyMemberForm();
	viewTab('Members','all');
}

function saveMember() {
	var parentid = $('#parent_id').val();
	if (parentid==0){
		var succesmsg = 'Ouder aangemaakt';
	} else {
		var succesmsg = 'Ouder aangepast';
	}
	var kidsdata = [];
	$("#kids_table_tbody").find('tr').each(function () {
      var $this = $(this);
			kidsdata.push({
				'ID': $this.data('id').toString(),
				'Name': $this.find(".kids_name_input")[0].value,
				'Surname': $this.find(".kids_surname_input")[0].value,
				'BirthDate': convertDate($this.find(".kids_birthdate_input")[0].value),
				'Caution': "0",
				'ExpiryDate': "0000-00-00",
				'Active': "0",
				'BikeID': "0",
				'KidNr': "0"
			});
  });
	var parentdata = {
			'ID': parentid,
			'Name': $('#parent_name').val(),
			'Surname': $('#parent_surname').val(),
			'Street': $('#parent_street').val(),
			'StreetNumber': $('#parent_streetnr').val(),
			'Postal': $('#parent_postal').val(),
			'Town': $('#parent_town').val(),
			'Email': $('#parent_email').val(),
			'Phone': $('#parent_phone').val(),
			'InitDate': convertDate($('#parent_date').val()),
			'CautionAmount': "0",
			'MembershipID':  $('#parent_membership').val()
		};
    $.ajax({
		type: 'POST',
		url: 'api/members',
		data: JSON.stringify({
			'kidsdata': kidsdata,
			'parentdata': parentdata,
			'parentID': parentid
		}),
		contentType: "application/json",
		success: function () {
			toastr.success(succesmsg);
			loadMembers();
			viewTab('Members','all');
		},
		error: function (data) {
			console.error(data);
		}
	});
}

/* KIDS */

function addNewKidRow() {
	$('#kids_table_tbody').append(template_kidsrow({ID: '0', name: '', surname: '', birthdate: '00-00-0000'}));
}

function addKidItem(data) {
    $('#kids_table_tbody').append(template_kidsrow({ID: data.ID, name: data.Name, surname: data.Surname, birthdate: data.BirthDate}));
}
