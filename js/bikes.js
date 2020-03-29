$(document).ready(function () {

	// INIT BIKES TABLE
	bikestable = $('#bikes_table').DataTable({
        paging: true,
		pageLength: 25,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Alle"]],
		ordering: true,
		sortable: true,
		rowId: 'bikeID',
		dom: '<l<"filterbikes">fr>tip',
		"order": [[ 0, 'asc' ], [ 1, 'asc' ]],
		autoWidth: true,
    columns: [
			{data: 'Number', name: 'Number'},
			{data: 'Name', name: 'Name'},
			{data: 'Status', name: 'Status'},
      {data: 'Frame', name: 'Frame'},
			{data: 'Wheel', name: 'Wheel'},
			{data: 'InitDate', name: 'InitDate'},
			{data: 'Notes', name: 'Notes', 'visible': false},
			{
                data: {
										ID: 'ID'
                },
                render: function (data, type) {
                    return '<button type="button" class="btn btn-default editBike">Bewerk</button>';
                },
				sortable: false
            }
        ],
		"search": {
			"regex": true,
			"smart":false
		}
    });

	/* FILTER BIKES TABLE */
	$('.filterbikes').on('change', function() {
        bikestable.draw();
    });

	$("div.filterbikes").html('<input type="checkbox" id="bikesfilteravailable" checked> Beschikbaar <input type="checkbox" id="bikesfilterloans" checked> Ontleend');

	/* Custom filtering function for datatablesr items-table with lowstockcheckbox */
	$.fn.dataTable.ext.search.push(
		function( settings, data ) {
			/* for orders */
			if (settings.nTable.id == 'bikes_table') {
				var state = data[2];
				if (state == "Ontleend") {
					if ($('#bikesfilterloans').is(':checked')){
						return true;
					} else
						return false;
					}
				else if (state == "Beschikbaar") {
					if ($('#bikesfilteravailable').is(':checked')){
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

	$('#bikedatepicker').datetimepicker({
		//locale: 'nl',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	bikequill = new Quill('#bike_notes', {
			modules: {
				toolbar: quillToolbarOptions
			},
			theme: 'snow',
			background: 'white'
	});

	$(document).on('click', '.editBike', function () {
		rowdata = bikestable.row( $(this).closest('tr') ).data();
        setBikeForm(rowdata);
    });

		$(document).on('click', '.deletebike', function () {
						// determine which item to delete
						var row = $(this).closest('tr');
						var id = row.data('id');
						if (id == "0") {
							$(this).closest("tr").remove();
						} else {
							if (parseInt(row.data('active')) > 0) {
								alert('Dit kind heeft momenteel een fietsje en kan niet verwijderd worden.');
							} else {
								if (confirm('Ben je zeker dat je ' + row.data('name') +  ' ' + row.data('surname') + ' wilt verwijderen?')) {
									kidsToDelete.push(id);
									$(this).closest("tr").remove();

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
								}
							}
						}
				});

	loadBikes();

});


function loadBikes() {
    $.ajax({
        url: 'api/bikes',
        success: function (bikes) {
					bikestable.clear();
					bikestable.rows.add(bikes);
					bikestable.columns.adjust().draw();
					setActionBikes(bikes);
					db_bikes = bikes;
				}
    });
}

function newBike() {
	emptyBikeForm() ;
	setNewBikeNr();
	viewTab('Bikes','one');
}

function setBikeForm(rowdata) {
	$('#bike_id').val(rowdata.ID);
	$('#bike_nr').val(parseInt(rowdata.Number));
	$('#bike_name').val(rowdata.Name);
	document.getElementById('bike_status').innerHTML = rowdata.Status;
	$('#bike_frame').val(rowdata.Frame);
	$('#bike_wheel').val(rowdata.Wheel);
	$('#bike_date').val(rowdata.InitDate);
	bikequill.root.innerHTML = rowdata.Notes;
	viewTab('Bikes','one');
}

function emptyBikeForm() {
	$('#bike_id').val(0);
	$('#bike_nr').val(0);
	$('#bike_name').val('');
	document.getElementById('bike_status').innerHTML = "Beschikbaar";
	$('#bike_frame').val('');
	$('#bike_wheel').val('');
	$('#bike_date').val(myGetDate());
	bikequill.setContents([]);
}

function setNewBikeNr() {
	// compute max bike and add 1
	if (db_bikes.length>0) {
		highestnr = db_bikes.reduce((max, bike) => parseInt(bike.Number) > max ? parseInt(bike.Number) : max, db_bikes[0].Number);
	} else {
		highestnr = 0;
	}
	console.log('highest bike nr is ' + highestnr);
	$('#bike_nr').val(parseInt(highestnr)+parseInt(1));
}

function cancelBike() {
	emptyBikeForm();
	viewTab('Bikes','all');
}

function saveBike() {
	var bikeid = $('#bike_id').val();
	if (bikeid==0){
		var succesmsg = 'Fiets aangemaakt';
		bstatus = "Beschikbaar";
	} else {
		var succesmsg = 'Fiets aangepast';
		bstatus = document.getElementById('bike_status').innerHTML;
	}
    $.ajax({
		type: 'POST',
		url: 'api/bikes',
		data: JSON.stringify({
			'ID': bikeid,
			'Number': $('#bike_nr').val(),
			'Name': $('#bike_name').val(),
			'Status':  bstatus,
			'Frame': $('#bike_frame').val(),
			'Wheel': $('#bike_wheel').val(),
			'Source': 'Donatie lid',
			'InitDate': convertDate($('#bike_date').val()),
			'Notes': bikequill.root.innerHTML,
		}),
		contentType: "application/json",
		success: function () {
			toastr.success(succesmsg);
			loadBikes();
			viewTab('Bikes','all');
		},
		error: function (data) {
			console.error(data);
		}
	});
}

function deleteBike() {
	var bikeid = $('#bike_id').val();
	if (bikeid==0){
		viewTab('Members','all');
	} else {
		var kids = db_kids.filter(x => x.BikeID === bikeid);
		console.log(kids)
		console.log(kids.length)
		if (kids.length > 0) {
			alert('Dit fietsje is momenteel in gebruik en kan niet verwijderd worden.');
		} else {
			if (confirm('Ben je zeker dat je deze fiets wilt verwijderen?')) {
				$.ajax({
					type: 'POST',
					url: 'api/bikes/delete/' + bikeid,
					contentType: "application/json",
					success: function () {
						toastr.success('Fiets verwijderd');
						loadBikes();
						viewTab('Bikes','all');
					},
					error: function (data) {
						console.error(data);
					}
				});
			}
		}
	}
}
