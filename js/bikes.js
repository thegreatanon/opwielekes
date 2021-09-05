$(document).ready(function () {

	// INIT BIKES TABLE
	bikestable = $('#bikes_table').DataTable({
        paging: true,
				pageLength: 25,
				"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Alle"]],
				ordering: true,
				sortable: true,
				rowId: 'bikeID',
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
				dom: '<l<"filterbikes">fr>t<iBp>',
				"order": [[ 0, 'asc' ], [ 1, 'asc' ]],
				buttons: [
						'copyHtml5',
						{
								extend: 'csv',
								filename: 'Opwielekes fietsjes',
								title: '',
								exportOptions: { columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13]}
						},
						{
								extend: 'excel',
								filename: 'Opwielekes fietsjes',
								title: '',
								exportOptions: { columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13]}
						},
						{
								extend: 'pdf',
								filename: 'Opwielekes fietsjes',
								title: '',
								exportOptions: { columns: [ 0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13]},
								orientation: 'landscape'
						}
				],
				autoWidth: true,
		    columns: [
						{data: 'Number', name: 'Number'},
						{data: 'Name', name: 'Name'},
						{data: 'StatusName', name: 'StatusName'},
						{data: 'Brand', name: 'Brand'},
						{data: 'Gender', name: 'Gender'},
			      {data: 'Frame', name: 'Frame'},
						{data: 'Wheel', name: 'Wheel'},
						{data: 'Gears', name: 'Gears'},
						{data: 'Colour', name: 'Colour'},
						{data: 'Location', name: 'Location'},
						{
							data: {InitDate: 'InitDate'},
							name: 'Initdate',
						  render: function (data, type) {
							 		return data.InitDate;
							},
							sortable: true
						},
						{
							data: {LoanDate: 'LoanDate'},
							name: 'Loandate',
							render: function (data, type) {
								if ( type === 'sort') {
									if (data.LoanDate == '') {
										return 0;
									} else {
										return moment( data.LoanDate, findateformat).unix();
									}
								}	else {
									return data.LoanDate;
								}
							}
						},
						{data: 'KidName', name: 'KidName'},
						{data: 'Notes', name: 'Notes'},
						{
                data: {
										ID: 'ID',
										StatusOnLoan: 'StatusOnLoan',
										StatusNr: 'StatusNr',
										StatusAvailable: 'StatusAvailable'
                },
                render: function (data, type) {
                    return '<button type="button" class="btn btn-default editBike">Bewerk</button>';
                },
								sortable: false
			        }
	        ],
					columnDefs: [ {
			        targets: 13,
			        render: $.fn.dataTable.render.ellipsis(75)
			    } ],
					"search": {
						"regex": true,
						"smart":false
					},
					"initComplete": function( settings, json ) {
							$("div.filterbikes").html('<input type="checkbox" id="bikesfilternotavailable" checked> Niet beschikbaar <input type="checkbox" id="bikesfilteravailable" checked> Beschikbaar <input type="checkbox" id="bikesfilterloans" checked> Ontleend');
							/* FILTER BIKES TABLE */
							$('.filterbikes').on('change', function() {
						        bikestable.draw();
						   });
					}
    });


	/* Custom filtering function for datatables */
	$.fn.dataTable.ext.search.push(
		function(settings, searchData, index, rowData, counter) {
			/* for orders */
			if (settings.nTable.id == 'bikes_table') {
				if (rowData.StatusOnLoan == "1") {
					if ($('#bikesfilterloans').is(':checked')){
						return true;
					} else
						return false;
					}
				else if (rowData.StatusAvailable == "1") {
					if ($('#bikesfilteravailable').is(':checked')){
						return true;
					} else {
						return false;
					}
				} else {
					if ($('#bikesfilternotavailable').is(':checked')){
						return true;
					} else {
						return false;
					}
				}
			}
			return true;
		}
	);

	bikestatustable = $('#table_bikestatushistory').DataTable({
    paging: false,
		pageLength: 25,
		ordering: false,
		sortable: false,
		dom: 't',
      columns: [
				{data: 'Date', name: 'datum'},
				{
						data: {
								'StatusName': 'status',
								'ParentID': 'ParentID'
						},
						render: function (data, type) {
							if (data.ParentID !== null) {
								return '<a href="#members" onclick="setMemberFormByID(' + data.ParentID + ')">' + data.StatusName + '</a>';
							} else {
								return data.StatusName;
							}
						},
						sortable: false
					}
      ],
			"search": {
				"regex": true,
				"smart":false
			}
    });

	bikestatus = $('#bike_status').select2({
		tags: false,
	});

	$('#bikedatepicker').datetimepicker({
		locale: 'nl-be',
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

	$('#bikestatusdatepicker').datetimepicker({
		locale: 'nl-be',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
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


function loadBikes(bikeid) {
    $.ajax({
        url: 'api/bikes',
        success: function (bikes) {
					bikestable.clear();
					bikestable.rows.add(bikes);
					setBikesTableColumns(bikestable)
					bikestable.columns.adjust().draw();
					setActionBikes(bikes);
					db_bikes = bikes;
					if (bikeid !== undefined) {
						setBikeFormByID(bikeid);
					}
				}
    });
}

function newBike() {
	emptyBikeForm() ;
	setNewBikeNr();
	viewTab('Bikes','one');
}

function setBikeForm(rowdata) {
	setBikeFormByID(rowdata.ID)
}

function setBikeFormByID(bikeID) {
	var bikes = db_bikes.filter(x => x.ID === bikeID);
	bike = bikes[0];
	$('#bike_id').val(bike.ID);
	$('#bike_nr').val(parseInt(bike.Number));
	$('#bike_name').val(bike.Name);
	$('#bike_frame').val(bike.Frame);
	$('#bike_wheel').val(bike.Wheel);
	$('#bike_brand').val(bike.Brand);
	bikegender.val(bike.Gender).trigger('change');
	$('#bike_colour').val(bike.Colour);
	$('#bike_gears').val(bike.Gears);
	$('#bike_location').val(bike.Location);
	$('#bike_initdate').val(bike.InitDate);
	document.getElementById('bike_loandate').innerHTML = bike.LoanDate;
	document.getElementById('bike_status_text').innerHTML = bike.StatusName;
	$('#bike_statusnr').val(bike.StatusNr);
	bikequill.root.innerHTML = bike.Notes;
	bikestatus.empty();
	if (bike.StatusOnLoan == 1) {
		var newOption = new Option(bike.StatusName, bike.StatusNr, false, false);
		bikestatus.append(newOption);
		bikestatus.trigger('change');
	} else {
		var activestatuses = db_bikestatuses.filter(x => x.Active === '1');
		var statuses = activestatuses.filter(x => x.OnLoan === '0');
		$.each(statuses, function (index, item) {
			var newOption = new Option(item.Name, item.ID, false, false);
			bikestatus.append(newOption);
			bikestatus.val(bike.StatusNr).trigger('change');
		});
	}
	visibilityBikeStatus(true);
	loadStatusHistory(bikeID)
	viewTab('Bikes','one');
}

function emptyBikeForm() {
	$('#bike_id').val(0);
	$('#bike_nr').val(0);
	$('#bike_name').val('');
	$('#bike_frame').val('');
	$('#bike_wheel').val('');
	$('#bike_brand').val('');
	bikegender.val('').trigger('change');
	$('#bike_colour').val('');
	$('#bike_gears').val('');
	$('#bike_location').val('');
	$('#bike_initdate').val(myGetDate());
	document.getElementById('bike_loandate').innerHTML = '';
	$('#bike_statusnr').val(defaultBikeAvailableID);
	bikestatus.empty();
	var initstatus = db_bikestatuses.filter(x => x.ID === defaultBikeAvailableID.toString());
	document.getElementById('bike_status_text').innerHTML = initstatus[0].Name;
	var newOption = new Option(initstatus[0].Name, initstatus[0].ID, false, false);
	bikestatus.append(newOption);
	bikestatus.val(defaultBikeAvailableID).trigger('change');
	bikequill.setContents([]);
	visibilityBikeStatus(false);
}


function visibilityBikeStatus(visible) {
	if (visible) {
		$("#bikestatusdiv").show();
	} else {
		$("#bikestatusdiv").hide();
	}
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
		var newBike;
		var bikeData;
		if (bikeid==0){
			var succesmsg = 'Fiets aangemaakt';
			newBike = 1;
			bikeData = {
				'Number': $('#bike_nr').val(),
				'Name': $('#bike_name').val(),
				'Status': defaultBikeAvailableID,
				'Frame': $('#bike_frame').val(),
				'Wheel': $('#bike_wheel').val(),
				'Brand': $('#bike_brand').val(),
				'Gender': $('#bike_gender').val(),
				'Colour': $('#bike_colour').val(),
				'Gears': $('#bike_gears').val(),
				'Location': $('#bike_location').val(),
				'Source': 'Donatie lid',
				'Date': convertDate($('#bike_initdate').val()),
				'Notes': bikequill.root.innerHTML,
				'KidID': 0
			};
		} else {
			var succesmsg = 'Fiets aangepast';
			newBike = 0;
			bikeData = {
				'ID': bikeid,
				'Number': $('#bike_nr').val(),
				'Name': $('#bike_name').val(),
				'Frame': $('#bike_frame').val(),
				'Wheel': $('#bike_wheel').val(),
				'Brand': $('#bike_brand').val(),
				'Gender': $('#bike_gender').val(),
				'Colour': $('#bike_colour').val(),
				'Gears': $('#bike_gears').val(),
				'Location': $('#bike_location').val(),
				'Source': 'Donatie lid',
				'Date': convertDate($('#bike_initdate').val()),
				'Notes': bikequill.root.innerHTML
			}
		}
    $.ajax({
			type: 'POST',
			url: 'api/bikes',
			data: JSON.stringify({
				'newBike': newBike,
				'bikeData': bikeData
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


function saveBikeStatus() {
	var bikeid = $('#bike_id').val();
	var oldstatusnr = $('#bike_statusnr').val();
	var newstatusnr = $('#bike_status').val();

	if (bikeid!=0 && oldstatusnr!=newstatusnr) {
		console.log('updating status')
		$.ajax({
			type: 'POST',
			url: 'api/bikes/bikestatus',
			data: JSON.stringify({
				'ID': bikeid,
				'KidID' : 0,
				'Status': newstatusnr,
				'Date': convertDate($('#bikestatusdate').val())
			}),
			contentType: "application/json",
			success: function () {
				loadBikes(bikeid);
			},
			error: function (data) {
				console.error(data);
			}
		});
	}
}

//loads the history actions of an order
function loadStatusHistory(bikeid) {
	$.ajax({
		url: 'api/bikes/statuslogs/' + bikeid,
		success: function (results) {
			var dt = $('#table_bikestatushistory').dataTable().api();
			dt.clear();
			for (var i = 0, len = results.length; i < len; i++) {
				dt.row.add(results[i]);
			}
			dt.columns.adjust().draw();
		},
		error: function (data) {
			//console.error(data);
		}
	});
}
