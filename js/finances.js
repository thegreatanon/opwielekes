$(document).ready(function () {

	// INIT FINANCE TABLE
	financestable = $('#finances_table').DataTable({
		paging: true,
		pageLength: 25,
		"order": [[ 0, "desc" ]],
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
		dom: '<l<"filterfinances">fr>t<iBp>',
		buttons: [
				'copyHtml5',
				{
						extend: 'csv',
						filename: 'Opwielekes financien',
						title: ''
				},
				{
						extend: 'excel',
						filename: 'Opwielekes financien',
						title: ''
				},
				{
						extend: 'pdf',
						filename: 'Opwielekes financien',
						title: ''
				}
		],
		rowId: 'finID',
		sortable: true,
		autoWidth: true,
    columns: [
			{data: 'TransactionDate'},
      {
				data: {
            ParentName: 'ParentName',
            ParentSurname: 'ParentSurname'
        },
        render: function (data, type) {
						//if (type === 'display'){
	           		return '<a href="#members" class="memberlink">' + data.ParentName + ' ' +  data.ParentSurname + '</a>';
//	          } else {
								//return data.ParentName + " " + data.ParentSurname;
						//}
        }
			},
      {
				data: {
            KidName: 'KidName',
            KidSurname: 'KidSurname'
        },
        render: function (data, type) {
					return data.KidName + " " + data.KidSurname;
        }
			},
      {data: 'Caution'},
      {data: 'Membership'},
			{data: 'Amount'},
			{data: 'ReceivedFull'},
			{
        data: {
					ID: 'ID',
					Received : 'Received',
					Amount: 'Amount'
        },
				width: '80px',
        render: function (data, type) {
					if (data.Received == "0") {
						btnok = '<button type="button" class="btn btn-default editFin"><span class="glyphicon glyphicon-ok" style="color:gray" aria-hidden="true"></span></button>';
						btndel = '<button type="button" class="btn btn-default delete_openfin"><span class="glyphicon glyphicon-remove" style="color:red" aria-hidden="true"></span></button>';
						return btnok + btndel;
					} else {
						return '<button type="button" class="btn btn-default editFin"><span class="glyphicon glyphicon-ok" style="color:green" aria-hidden="true"></span></button>';
					}
        },
				sortable: false
      }
    ],
		"search": {
			"regex": true,
			"smart":false
		},
		"initComplete": function( settings, json ) {
				$("div.filterfinances").html('<div id="financerange" class="pull-right" style="background: #fff;cursor: pointer;padding: 2px 10px;border: 1px solid #ccc;"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b></div> <div id="finfilter" class="pull-left"><input type="checkbox" id="finfilteropen" checked> In afwachting <input type="checkbox" id="finfilterreceived" checked> Voldaan</div>');
				// initiliase finance data range picker
								//var initstart = moment().subtract(29, 'days');
								var initstart = moment().startOf('year');
								var initend = moment();
								$('#financerange').daterangepicker({
									locale: {format: findateformat},
									minDate: moment("01-01-2016", findateformat),
									startDate: initstart,
									endDate: initend,
									alwaysShowCalendars: true,
									ranges: {
							           'Vandaag': [moment(), moment()],
							           'Voorbije week': [moment().subtract(6, 'days'), moment()],
							           'Deze maand': [moment().startOf('month'), moment().endOf('month')],
									   		 'Vorige maand': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
												 'Dit jaar': [moment().startOf('year'), moment().endOf('year')],
									   		 'Altijd': [moment("01-01-2015", findateformat), moment()]
							        }
								}, finrangechanged);
								finrangechanged(initstart, initend);


								/* FILTER BIKES TABLE */
								$('#finfilter').on('change', function() {
											financestable.draw();
								});

								// Custom filtering function
								$.fn.dataTable.ext.search.push(
									function(settings, searchData, index, rowData, counter ) {
										if (settings.nTable.id == 'finances_table') {
											var datetime = moment(rowData.TransactionDate, findateformat);
											var startdate = moment($('#financerange').data('daterangepicker').startDate,findateformat);
											var enddate = moment($('#financerange').data('daterangepicker').endDate,findateformat);
											var validDate = (moment(datetime).isSameOrAfter(startdate) && moment(datetime).isSameOrBefore(enddate));
											var validfilters = false;
											if (rowData.Received == "1") {
													if ($('#finfilterreceived').is(':checked')){
														validfilters = true;
													}
											} else {
													if ($('#finfilteropen').is(':checked')){
														validfilters = true;
													}
											}
											return (validDate && validfilters);
										}
										return true;
									}
								);

		}

	});


	$(document).on('click', '.memberlink', function () {
		rowdata= financestable.row( $(this).closest('tr') ).data();
		setMemberFormByID(rowdata.ParentID);
	});


	function finrangechanged(start, end) {
		$('#financerange').find('span').html(start.format('D MMM, YYYY') + ' - ' + end.format('D MMM, YYYY'));
		financestable.columns.adjust().draw();
	}






	// TABLE WITH EXPIRED MEMBERSHIPS
	/*
	expirytable = $('#expiry_table').DataTable({
        paging: true,
		pageLength: 25,
		"order": [[ 0, "desc" ]],
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		rowId: 'ID',
		autoWidth: true,
		select: {
            style: 'multi'
        },
        columns: [
			{data: 'ParentID'},
			{data: 'Fullname'},
			{data: 'ID'},
			{data: 'BikeID'},
            {data: 'CautionPresent'},
			{data: 'ExpiryDate'},
            {data: 'Active'},
			{data: 'Surname'}
        ],
		"search": {
			"regex": true,
			"smart":false
		}
    });
	*/


	$('#findatepicker').datetimepicker({
		//locale: 'nl',
		defaultDate: new Date(),
		format: 'DD-MM-YYYY'
	});

	$('#fin_status').select2({
		tags: false
	});

	$(document).on('click', '.editFin', function () {
		rowdata= financestable.row( $(this).closest('tr') ).data();
		console.log('row: ' + rowdata.ID)
		$('#fin_date').val(rowdata.TransactionDate);
		$('#fin_id').val(rowdata.ID);
		$('#fin_status').val(rowdata.Received).trigger('change');;
		$('#editFinanceModal').modal('show');
        //processPayment(rowdata);
  });

	$(document).on('click', '.delete_openfin', function () {
		rowdata = financestable.row( $(this).closest('tr') ).data();
        deletePayment(rowdata);
    });

	$("#submitEditFinance").click(function () {
      $("#editFinanceForm").submit();
  });

	$("#editFinanceForm").submit(function (e) {
			$.ajax({
					type: 'POST',
					url: 'api/finances/update/' + $('#fin_id').val(),
					data: JSON.stringify({
							'TransactionDate': convertDate($('#fin_date').val()),
							'Received': parseInt($('#fin_status').val())
					}),
					contentType: "application/json",
					success: function () {
							$('#editFinanceModal').modal('hide');
							loadFinances();
					},
					error: function (data) {
							console.error(data);
					}
			});
			e.preventDefault();
	});

	loadFinances();

});


function loadFinances() {
    $.ajax({
        url: 'api/finances',
        success: function (finances) {
					jQuery.each(finances, function(index, item) {
						if (item.Received=="1") {
							item["ReceivedFull"] = "Voldaan";
						} else {
							item["ReceivedFull"] = "In afwachting";
						}
					});
					financestable.clear();
					financestable.rows.add(finances);
					financestable.columns.adjust().draw();
				}
    });
}

function checkExpiryDates() {
	expiredKids = [];
	var today = myGetDate();
	jQuery.each(db_kids, function(index, item) {
		console.log(item);
		if (item.Active && moment(item.ExpiryDate).isBefore(today)) {
			expiredKids.push({
				'ID': item.ID,
				'BikeID': item.BikeID,
				'ParentID': item.ParentID,
				'Name': item.Name,
				'Surname': item.Surname,
				'Active': item.Active,
				'CautionPresent': item.CautionPresent,
				'ExpiryDate': item.ExpiryDate,
				'Fullname': item.Name + " " + item.Surname
			});
		}
	});
	console.log(expiredKids);
	expirytable.clear();
	expirytable.rows.add(expiredKids);
	expirytable.columns.adjust().draw();
}

function processPayment(row) {
	// add caution payment to current caution value
	var updateCaution = "0";
	var cautionData = [];
	if (row.Caution != "0") {
		updateCaution = "1";
		var parentID = row.ParentID;
		var p = db_parents.find(x => x.ID === parentID.toString());
		var newAmount = parseFloat(p.CautionAmount) + parseFloat(row.Caution);
		cautionData = {
			'ID': parentID,
			'CautionAmount': newAmount
		};
	}
	// if membership paid, extend expiry date
	var updateMembership = "0";
	var membershipData = [];
	if (parseFloat(row.Membership) > 0) {
		updateMembership = "1";
		var kidID = row.KidID;
		var k = db_kids.find(x => x.ID === kidID.toString());
		var expirydate = k.ExpiryDate;
		expirydate = extendExpiryDate(expirydate);
		membershipData = {
			'ID': kidID,
			'ExpiryDate': expirydate,
		};
	}
	// POST IT
	$.ajax({
		type: 'POST',
		url: 'api/members/payments',
		data: JSON.stringify({
			'updateCaution': updateCaution,
			'cautionData': cautionData,
			'updateMembership': updateMembership,
			'membershipData': membershipData,
			'finTransID': row.ID
		}),
		contentType: "application/json",
		success: function () {
			loadMembers();
			loadFinances();
		},
		error: function () {
			console.error();
		}
	});

}

function deletePayment(row) {
	if (confirm("Ben je zeker dat je deze financiële transactie wil verwijderen?")) {
		$.ajax({
			type: 'POST',
			url: 'api/finances/delete/' + row.ID,
			success: function () {
				loadFinances();
			},
			error: function () {
				console.error();
			}
		});
	}
}
