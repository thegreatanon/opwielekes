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
            {data: 'StreetNumber', name: 'StreetNumber'},
			{data: 'Postal', name: 'Postal'},
			{data: 'Active', name: 'Active'},
			{
                data: {
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
				var state = data[5];
				if (state == 1) {
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
		format: 'YYYY-MM-DD'
	});
		
		
	$(document).on('click', '.editMember', function () {
		rowdata = memberstable.row( $(this).closest('tr') ).data();
        setMemberForm(rowdata);
    });	
	
	loadMembers();

});


function loadMembers() {
	loadParents();
	loadKids();
}

function loadParents() {
    $.ajax({
        url: 'api/parents',
        success: function (parents) {
			memberstable.clear();
			memberstable.rows.add(parents);
			memberstable.draw();
		}
    });
}

function loadKids() {
    $.ajax({
        url: 'api/kids',
        success: function (kids) {
			db_kids = kids;
		}
    });
}

function newMember() {
	emptyMemberForm();
	addNewKidRow();
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
	setKidForm(rowdata.ID);
	//viewTab('Bike','one');
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
	// kids
	$('#kids_table_tbody').empty();
	//viewTab('Bike','one');
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
			'BirthDate': $this.find(".kids_birthdate_input")[0].value,
			'Caution': "0",
			'ExpiryDate': "0000-00-00",
			'Active': "0"
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
			'InitDate': $('#parent_date').val(),
			'Active': "0"
		};
	console.log(kidsdata);
	console.log(parentdata);
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
		},
		error: function (data) {
			console.error(data);
		}
	});
}

/* KIDS */

function addNewKidRow() {
	$('#kids_table_tbody').append(template_kidsrow({ID: '0', name: '', surname: '', birthdate: '0000-00-00'}));
}

function addKidItem(data) {
    $('#kids_table_tbody').append(template_kidsrow({ID: data.ID, name: data.Name, surname: data.Surname, birthdate: data.BirthDate}));
}