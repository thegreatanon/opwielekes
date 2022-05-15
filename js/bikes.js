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
					{
							extend: 'collection',
						 	text: 'Kolommen',
							 buttons: [
								 {
											 text: 'Toggle Ingebracht',
											 action: function ( e, dt, node, config ) {
													 dt.column( -5 ).visible( ! dt.column( -5 ).visible() );
											 }
									 },
									 {
											 text: 'Toggle Ontleend',
											 action: function ( e, dt, node, config ) {
													 dt.column( -4 ).visible( ! dt.column( -4 ).visible() );
											 }
									 },
									 {
											 text: 'Toggle Ontlener',
											 action: function ( e, dt, node, config ) {
													 dt.column( -3 ).visible( ! dt.column( -3 ).visible() );
											 }
									 },
									 {
											 text: 'Toggle Notities',
											 action: function ( e, dt, node, config ) {
													 dt.column( -2 ).visible( ! dt.column( -2 ).visible() );
											 }
									 }
							 ]
        	},
	        {
            extend: 'collection',
            text: 'Exporteer',
						buttons: [
							'copyHtml5',
							{
									extend: 'csv',
									filename: 'Opwielekes fietsjes',
									title: '',
									exportOptions: { columns: [ 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]}
							},
							// TODO fix export to excel
							{
									extend: 'excel',
									filename: 'Opwielekes fietsjes',
									title: '',
									exportOptions: { columns: [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]}
							},
							{
									extend: 'pdf',
									filename: 'Opwielekes fietsjes',
									title: '',
									exportOptions: { columns: [1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]},
									orientation: 'landscape'
							}
						]
        	}
    		],
				autoWidth: true,
		    columns: [
						{
							data: {Imagefile: 'ImageFile'},
							name: 'Image',
							render: function (data, type) {
								if (data.ImageFile !== null) {
									return '<img src="uploads/' + $('#act_bi').val() + '/thumb/'+ data.ImageFile + '" style="height:75px;">';
								} else {
									return '<img src="images/transparent.png" style="height:75px;">';
								}
							},
							sortable: false
						},
						{data: 'Number', name: 'Number'},
						{data: 'Name', name: 'Name'},
						{data: 'StatusName', name: 'StatusName'},
						{data: 'Brand', name: 'Brand'},
						{data: 'Gender', name: 'Gender'},
			      {data: 'Frame', name: 'Frame'},
						{data: 'Wheel', name: 'Wheel'},
						{data: 'Tyre', name: 'Tyre'},
						{data: 'Gears', name: 'Gears'},
						{data: 'Colour', name: 'Colour'},
						{data: 'Location', name: 'Location'},
						{
							data: 'InitDate',
							name: 'Initdate',
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
						{data: 'KidName', name: 'KidName', visible: false},
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
					columnDefs: [
						{
							targets: 14,
							render: $.fn.dataTable.render.ellipsis(75)
						}
					],
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
					setBikesTableColumns(bikestable);
					//bikestable.columns.adjust().draw(); //moved to setbikestablecolumns
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
	$('#bike_tyre').val(bike.Tyre);
	$('#bike_brand').val(bike.Brand);
	bikegender.val(bike.Gender).trigger('change');
	$('#bike_colour').val(bike.Colour);
	$('#bike_gears').val(bike.Gears);
	$('#bike_location').val(bike.Location);
	$('#bike_initdate').val(bike.InitDate);
	document.getElementById('bike_loandate').innerHTML = bike.LoanDate;
	bikequill.root.innerHTML = bike.Notes;
	setBikeImage(bike);
	// STATUS
	document.getElementById('bike_status_text').innerHTML = bike.StatusName;
	$('#bike_statusnr').val(bike.StatusNr);

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
	$('#bike_tyre').val('');
	$('#bike_brand').val('');
	bikegender.val('').trigger('change');
	$('#bike_colour').val('');
	$('#bike_gears').val('');
	$('#bike_location').val('');
	$('#bike_initdate').val(myGetDate());
	document.getElementById('bike_loandate').innerHTML = '';
	bikequill.setContents([]);
	resetBikeImage();
	// STATUS
	$('#bike_statusnr').val(defaultBikeAvailableID);
	bikestatus.empty();
	var initstatus = db_bikestatuses.filter(x => x.ID === defaultBikeAvailableID.toString());
	document.getElementById('bike_status_text').innerHTML = initstatus[0].Name;
	var newOption = new Option(initstatus[0].Name, initstatus[0].ID, false, false);
	bikestatus.append(newOption);
	bikestatus.val(defaultBikeAvailableID).trigger('change');
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
				'Tyre': $('#bike_tyre').val(),
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
				'Tyre': $('#bike_tyre').val(),
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


function setBikeFieldsDiv() {
	var bikeid = $('#bike_id').val();
	var bikefields = ['frame','wheel', 'tyre', 'brand','gender','colour','gears','location','initdate','loandate'];
	var descriptions = ['Frame', 'Wiel', 'Band', 'Merk', 'Gender', 'Kleur', 'Versnellingen','Locatie','Ingebracht','Ontleend'];
	var fieldtypes = ['text', 'text', 'text', 'text', 'dropdowng', 'text', 'text', 'text', 'date', 'p'];
	//var fieldnames = bikefields.map(field => '#bike_' + field + '_div');
	var bikeproperties = bikefields.map(field => 'bike_show_' + field);
	$('#bike_fields_div').empty();
	var myhtml = '';
	var openrow = false;
	var closerow = false;
	var fieldsshown = 0;
	var initdropdowng = false;

	$.each(bikefields, function (index, item) {
		prop = getProperty(bikeproperties[index]);
		if (prop.value == true) {
			fieldsshown = fieldsshown + 1;
			if (fieldsshown % 2 == 0) {
				closerow = true;
			} else {
				openrow = true;
			}
		}
		if (openrow == true) {
			myhtml += '<div class="form-group">'
			openrow = false;
		}
		//myhtml +=	'<div';
		if (prop.value == false) {
			divvisibility = ' hidden';
		} else {
			divvisibility = '';
		}
		//myhtml += '>';
		myhtml += '<label class="col-sm-2 control-label lb-sm"' +	divvisibility ;
		//if (descriptions[index]=="Wiel") {
		//	myhtml += ' title="Voeg hier de inchmaat toe van de fiets, dit zie je op de buitenband, of vul aan met loopfiets, step,.. Mogelijke kindermaten zijn 12 inch, 14, 16,...  tem 26 inch"';
		//}
		myhtml += '>' + descriptions[index] + '</label>';
		myhtml +=	'<div class="col-sm-4"' + divvisibility + '>';
		if (fieldtypes[index] == 'text') {
			myhtml +=	'<input type="text" class="form-control input-sm" id="bike_' + item + '" name="bike_' + item + '">';
		} else if (fieldtypes[index] == 'date'){
				myhtml +=	'<div class="input-group" id="bikedatepicker">';
				myhtml +=	'<input type="text" class="form-control input-sm" id="bike_' + item + '" name="bike_' + item + '">';
				myhtml +=	'<span class="input-group-addon">';
				myhtml +=	'<span class="glyphicon glyphicon-calendar"></span>';
				myhtml +=	'</span>';
				myhtml +=	'</div>';
		} else if (fieldtypes[index] == 'p') {
			 	myhtml += '<p class="form-control-static" id="bike_' + item + '" name="bike_' + item + '"> </p>';
		}
		else if (fieldtypes[index] == 'dropdowng') {
			myhtml += '<select style="width : 100%;" id="bike_' + item + '">';
			myhtml += '<option value=""></option>';
			myhtml += '<option value="Unisex">Unisex</option>';
			myhtml += '<option value="Jongen">Jongen</option>';
			myhtml += '<option value="Meisje">Meisje</option>';
			myhtml += '</select>';
			initdropdowng = true;
		}
		myhtml +=	'</div>';
		//myhtml +=	'</div>';
		if (closerow == true) {
			myhtml += '</div>';
			closerow = false;
		}
	});
	$('#bike_fields_div').append(myhtml);
	bikegender = $('#bike_gender').select2({
		tags: false,
	});

	if (typeof bikeid !== 'undefined') {
		if (bikeid != 0) {
			setBikeFormByID(bikeid);
		}
	}
}


function setBikesTableColumns(bikestable) {
	var bikeproperties = ['bike_show_frame','bike_show_wheel','bike_show_tyre','bike_show_brand','bike_show_gender','bike_show_colour','bike_show_gears','bike_show_location','bike_show_initdate','bike_show_loandate','bike_show_image'];
	var colnames = bikeproperties.map(sliceCaseBikeProperty);
	$.each(colnames, function (index, item) {
		prop = getProperty(bikeproperties[index]);
		bikestable.column(item + ':name').visible(prop.value);
	});
	bikestable.columns.adjust().draw();
}

function sliceCaseBikeProperty(item) {
  return item.slice(10).replace(/^\w/, (c) => c.toUpperCase());
}
