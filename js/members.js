$(document).ready(function () {

	// INIT BIKES TABLE
	memberstable = $('#members_table').DataTable({
        paging: true,
				pageLength: 25,
				"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Alle"]],
				ordering: true,
				sortable: true,
				rowId: 'parentID',
				"language": {
					"url": "libs/datatables/lang/dutch.json",
					buttons: {
						 copyTitle: 'Kopiëer',
						 copyKeys: 'Druk op <i>ctrl</i> of <i>\u2318</i> + <i>C</i> om de rijen te kopiëren naar je klembord. <br><br>Om te annuleren, klik op deze boodscap of op ESC.',
						 copySuccess: {
								 _: '%d rijen gekopiëerd',
								 1: '1 rij gekopiëerd'
						 }
					 }
				},
				dom: '<l<"filtermembers">fr>t<iBp>',
				"order": [[ 0, 'asc' ], [ 1, 'asc' ]],
				buttons: [
						'copyHtml5',
						{
								extend: 'csv',
								filename: 'Opwielekes leden',
								title: '',
								exportOptions: { columns: [ 0, 1, 8, 9, 3, 10, 4, 5, 6, 7]}
						},
            {
                extend: 'excel',
								filename: 'Opwielekes leden',
								title: '',
								exportOptions: { columns: [ 0, 1, 8, 9, 3, 10, 4, 5, 6, 7]}
            },
            {
                extend: 'pdf',
								filename: 'Opwielekes leden',
								title: '',
								exportOptions: { columns: [ 0, 1, 8, 9, 3, 10, 4, 5, 6, 7]},
								orientation: 'landscape'
            }
        ],
				autoWidth: true,
	        columns: [
						{data: 'Name', name: 'Name'},
						{data: 'Surname', name: 'Surname'},
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
						{data: 'Notes', name: 'Notes'},
						{data: 'Email', name: 'Email', 'visible': false},
						{data: 'Phone', name: 'Phone', 'visible': false},
						{data: 'InitDate', name: 'InitDate', 'visible': false},
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
				columnDefs: [ {
							targets: 7,
							render: $.fn.dataTable.render.ellipsis(75)
				} ],
				"search": {
					"regex": true,
					"smart":false
				},
				"initComplete": function( settings, json ) {
					$("div.filtermembers").html('<input type="checkbox" name="membersfilteractive" id="membersfilteractive" checked > Actief <input type="checkbox" id="membersfilterinactive" checked> Inactief');
					/* FILTER MEMBERS TABLE */
					$('.filtermembers').on('change', function() {
								memberstable.draw();
					});
				}
    });

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
			if (settings.nTable.id == 'kidsexpiry_table') {
				var state = data[3];
				if (state >= 1) {
					if ($('#kidsfilteractive').is(':checked')){
					return true;
					} else
					return false;
					}
				else if (state == 0) {
					if ($('#kidsfilterinactive').is(':checked')){
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


	// INIT KIDS TABLE
	kidsexpirytable = $('#kidsexpiry_table').DataTable({
		paging: true,
		pageLength: 25,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Alle"]],
		ordering: true,
		sortable: true,
		rowId: 'KidID',
		"language": {
			"url": "libs/datatables/lang/dutch.json",
			buttons: {
				 copyTitle: 'Kopiëer',
				 copyKeys: 'Druk op <i>ctrl</i> of <i>\u2318</i> + <i>C</i> om de rijen te kopiëren naar je klembord. <br><br>Om te annuleren, klik op deze boodscap of op ESC.',
				 copySuccess: {
						 _: '%d rijen gekopiëerd',
						 1: '1 rij gekopiëerd'
				 }
			 }
		},
		dom: '<l<"filterkids">fr>t<iBp>',
		"order": [[ 0, 'asc' ], [ 1, 'asc' ]],
		buttons: [
				'copyHtml5',
				{
						extend: 'csv',
						filename: 'Opwielekes lidmaatschap',
						title: '',
						exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]}
				},
				{
						extend: 'excel',
						filename: 'Opwielekes lidmaatschap',
						title: '',
						exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]}
				},
				{
						extend: 'pdf',
						filename: 'Opwielekes lidmaatschap',
						title: '',
						exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]},
						orientation: 'landscape'
				}
		],
		autoWidth: true,
			columns: [
				{ data:
					{
						KidName: 'KidName',
						KidSurname: 'KidSurname'
					},
					render: function (data, type) {
						return data.KidName + " " + data.KidSurname;
					},
					sortable: true
				},
				{ data:
					{
					ParentName: 'ParentName',
					ParentSurname: 'ParentSurname'
					},
					render: function (data, type) {
						return data.ParentName + " " + data.ParentSurname;
					},
					sortable: true
				},
				{data: 'ParentEmail', name: 'ParentEmail'},
				{data: 'KidActive', name: 'KidActive'},
				{data: 'ParentActiveKids', name: 'ParentActiveKids', visible: false},
				{data: 'KidNr', name: 'KidNr'},
				{data:
					{
						KidExpiryDate: 'KidExpiryDate',
					},
					render: function (data, type) {
						if (data.KidExpiryDate == '00-00-0000') {
							return "";
						} else {
							return data.KidExpiryDate;
						}
					},
					sortable: true
				},
				{data:
					{
						KidActive: 'KidActive',
						KidExpiryDate: 'KidExpiryDate',
					},
					render: function (data, type) {
						if (data.KidActive == '1') {
							if (!moment(data.KidExpiryDate, 'DD-MM-YYYY').isValid() || moment(data.KidExpiryDate, 'DD-MM-YYYY').isBefore(today)) {
								return 1;
							} else {
								return 0;
							}
						} else {
							return 0;
						}
					},
					sortable: true
				},
				{data:
					{
						KidActive: 'KidActive',
						KidExpiryDate: 'KidExpiryDate',
						KidNr: 'KidNr',
						ParentMembershipID: 'ParentMembershipID'
					},
					render: function (data, type) {
						if (data.KidActive == '1') {
							return getMembershipFee(data.KidExpiryDate, data.KidNr, data.ParentMembershipID);
						} else {
							return (0).toFixed(2);
						}
					},
					sortable: true
				}
			],
			"search": {
				"regex": true,
				"smart":false
			},
			select: true,
			"initComplete": function( settings, json ) {
				$("div.filterkids").html('<input type="checkbox" name="kidsfilteractive" id="kidsfilteractive" checked > Actief <input type="checkbox" id="kidsfilterinactive" checked> Inactief');
				/* FILTER MEMBERS TABLE */
				$('.filterkids').on('change', function() {
							kidsexpirytable.draw();
				});
		}
  });



	$('#parentdatepicker').datetimepicker({
		//locale: 'nl',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	parentquill = new Quill('#parent_notes', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

	$(document).on('click', '.editMember', function () {
		rowdata = memberstable.row( $(this).closest('tr') ).data();
    setMemberForm(rowdata);
  });

	$(document).on('click', '.deletekidrow', function () {
	        // determine which item to delete
	        var row = $(this).closest('tr');
	        var id = row.data('id');
					if (id == "0") {
						$(this).closest("tr").remove();
					} else {
						if (parseInt(row.data('active')) > 0) {
							alert('Dit kind heeft momenteel een fietsje en kan niet verwijderd worden.');
						} else {
							kidsToDelete.push(id);
							$(this).closest("tr").remove();
						}
	        }
	});

	loadMembers();

});


function loadMembers() {
	kidsToDelete = [];
	loadKids();
	$.ajax({
        url: 'api/members/all',
        success: function (members) {
					kidsexpirytable.clear();
					kidsexpirytable.rows.add(members);
					kidsexpirytable.columns.adjust().draw();
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
	kidsToDelete = [];
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
	parentquill.root.innerHTML = rowdata.Notes;
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
	$('#parent_iban').val(p.IBAN);
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
	$('#parent_iban').val('');
	$('#parent_date').val(myGetDate());
	$('#parent_membership').val(defaultMembershipID);
	$('#parent_membership').trigger('change');
	parentquill.setContents([]);
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
			'IBAN': $('#parent_iban').val(),
			'InitDate': convertDate($('#parent_date').val()),
			'CautionAmount': "0",
			'MembershipID':  $('#parent_membership').val(),
			'Notes': parentquill.root.innerHTML,
	};
	//if (confirm('Ben je zeker dat je kind ' + row.data('name') +  ' ' + row.data('surname') + ' wilt verwijderen?')) {
  $.ajax({
		type: 'POST',
		url: 'api/members',
		data: JSON.stringify({
			'kidsdata': kidsdata,
			'parentdata': parentdata,
			'parentID': parentid,
			'deleteKids': kidsToDelete
		}),
		contentType: "application/json",
		success: function () {
			toastr.success(succesmsg);
			kidsToDelete = [];
			loadMembers();
			viewTab('Members','all');
		},
		error: function (data) {
			console.error(data);
		}
	});
}
/*
$.ajax({
		type: 'POST',
		url: 'api/members/deletekid/' + id,
		success: function () {
			loadMembers();
		},
		error: function () {
				console.error();
		}
});
*/
function deleteMember() {
	var parentid = $('#parent_id').val();
	if (parentid==0){
		viewTab('Members','all');
	} else {
		var kids = db_kids.filter(x => x.ParentID === parentid);
		var kidsid = [];
		var kidsactive = 0;
		$.each(kids, function (index, item) {
				kidsid.push({
					'ID': item.ID,
				});
				kidsactive += item.Active;
	  });
		if (kidsactive > 0) {
			alert('Dit lid heeft momenteel fietsjes en kan niet verwijderd worden.');
		} else {
			console.log(JSON.stringify({
				'parentid': parentid,
				'kidsid': kidsid
			}));

			if (confirm('Ben je zeker dat je dit lid en bijhorende kinderen wilt verwijderen?')) {
				$.ajax({
					type: 'POST',
					url: 'api/members/delete',
					data: JSON.stringify({
						'parentid': parentid,
						'kidsid': kidsid
					}),
					contentType: "application/json",
					success: function () {
						toastr.success('Lid verwijderd');
						loadMembers();
						viewTab('Members','all');
					},
					error: function (data) {
						console.error(data);
					}
				});
			}
		}
	}
}

/* KIDS */

function addNewKidRow() {
	$('#kids_table_tbody').append(template_kidsrow({ID: '0', name: '', surname: '', birthdate: '00-00-0000', active:'0', expirydate:"00-00-0000", bike:""}));
}

function addKidItem(data) {
		if (data.BikeID==0) {
			bikenr = "";
		} else {
			bikenr = data.BikeNr;
		}
    $('#kids_table_tbody').append(template_kidsrow({ID: data.ID, name: data.Name, surname: data.Surname, birthdate: data.BirthDate, active: data.Active, expirydate:data.ExpiryDate, bike:bikenr}));
}

function signup() {
	//var signuplink = $_SESSION["baseurl"] . "/signup";
	var signuplink =  "/signup";
  window.open(signuplink, "_blank");
}
