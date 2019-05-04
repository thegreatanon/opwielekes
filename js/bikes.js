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
		format: 'YYYY-MM-DD'
	});
		
		
	$(document).on('click', '.editBike', function () {
		rowdata = bikestable.row( $(this).closest('tr') ).data();
        setBikeForm(rowdata);
    });	
	
	loadBikes();

});


function loadBikes() {
    $.ajax({
        url: 'api/bikes',
        success: function (bikes) {
			bikestable.clear();
			bikestable.rows.add(bikes);
			bikestable.draw();
		}
    });
}

function newBike() {
	emptyBikeForm() ;
	setNewBikeNr();
}

function setBikeForm(rowdata) {
	$('#bike_id').val(rowdata.ID);
	$('#bike_nr').val(parseInt(rowdata.Number));
	$('#bike_name').val(rowdata.Name);
	$('#bike_status').val(rowdata.Status);
	$('#bike_frame').val(rowdata.Frame);
	$('#bike_wheel').val(rowdata.Wheel);
	$('#bike_date').val(rowdata.InitDate);
	//viewTab('Bike','one');
}

function emptyBikeForm() {
	$('#bike_id').val(0);
	$('#bike_nr').val(0);
	$('#bike_name').val('');
	$('#bike_status').val('');
	$('#bike_frame').val('');
	$('#bike_wheel').val('');
	$('#bike_date').val(myGetDate());
	//viewTab('Bike','one');
}

function setNewBikeNr() {
	// compute max bike and add 1
	$('#bike_nr').val(parseInt(100));
}



function saveBike() {
	var bikeid = $('#bike_id').val();
	if (bikeid==0){
		var succesmsg = 'Fiets aangemaakt';
	} else {
		var succesmsg = 'Fiets aangepast';
	}
	console.log({
			'ID': bikeid,
			'Number': $('#bike_nr').val(),
			'Name': $('#bike_name').val(),
			'Status': $('#bike_status').val(),
			'Frame': $('#bike_frame').val(),
			'Wheel': $('#bike_wheel').val(),
			'Source': 'Donatie lid',
			'InitDate': $('#bike_date').val()
	});
	
    $.ajax({
		type: 'POST',
		url: 'api/bikes',
		data: JSON.stringify({
			'ID': bikeid,
			'Number': $('#bike_nr').val(),
			'Name': $('#bike_name').val(),
			'Status': $('#bike_status').val(),
			'Frame': $('#bike_frame').val(),
			'Wheel': $('#bike_wheel').val(),
			'Source': 'Donatie lid',
			'InitDate': $('#bike_date').val()
		}),
		contentType: "application/json",
		success: function () {
			toastr.success(succesmsg);
			loadBikes();
		},
		error: function (data) {
			console.error(data);
		}
	});
}