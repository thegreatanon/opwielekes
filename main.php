<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.png">

    <title>Op Wielekes</title>

    <!-- lib css files -->
	<link href="libs/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="libs/toastr/2.1.3/toastr.min.css" rel="stylesheet"/>
	<link href="libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="libs/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="libs/select2/4.0.3/dist/css/select2.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/rg-1.0.2/datatables.min.css"/>

	
    <link href="css/opwielekes.css" rel="stylesheet"/>
    <style type='text/css'>
        .content_section:not(.active) {
            display: none;
        }
    </style>

	
	<?php
		// database access
		// require_once(__DIR__ . "/api/pdoconnect.php");
	?>
</head>

<body>

<div class="container">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    Op wielekes
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav" id="main-nav">
                    <li class="active"><a href="#transactions">Ontleningen</a></li>
                    <li><a href="#bikes">Fietsen</a></li>
                    <li><a href="#members">Leden</a></li>
					<!--<li><a href="#finances">Financiën</a></li>
					<li><a href="#stats">Statistieken</a></li>-->
                </ul>
                <ul class="nav navbar-nav navbar-right">
					<?php if (isset($_SESSION["login"])) {
						echo '<p class="navbar-text text-black">' . ucfirst($_SESSION["login"]) . '</p>';
					 } ?>
                    <li><a href="logout.php">Afmelden</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="content_transactions" class="content_section">
		<h4 class="inlineh4">Flow</h4> 
		<div class="container-fluid" width="100%">
		
			<div class="col-sm-12">
		
				<form id="form_action" class="form-horizontal">
				
							
					<div class="form-group">
						<label for="action_member" class="col-sm-1 control-label">Lid</label>
						<div class="col-sm-5">
							<select style="width : 100%;" class="form-control" id="action_member" name="action_member"></select>
						</div>				
					</div>

					<div class="form-group" disable>
						<label class="col-sm-1 control-label lb-sm">Actie</label>
						<div class="col-sm-2">
							<select style="width : 100%;" class="form-control" id="action_type" name="action_type">
								<!--<option></option>
								<option value="start">Start</option>
								<option value="trade">Ruil</option>
								<option value="end">Eind</option>
								<option value="donate">Donatie</option>-->
							</select>
						</div>
						
					
						<label for="actiondatepicker" class="col-sm-1 control-label"></label>
						<div class='input-group col-sm-2' id='actiondatepicker'>			
								<input type='text' class="form-control input-sm" id="action_date" name="action_date" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
						</div>
					</div>	
					
					<div class="form-group">
					
						<label class="col-sm-1 control-label lb-sm">Fiets</label>
						<div id="action_allbikes" hidden>
							<div class="col-sm-3">
								<select style="width : 100%;" class="form-control input-sm" id="action_bike_all" name="action_bike_all"></select>
							</div>
						</div>
						
						<div id="action_availablebikes" hidden>
							<div class="col-sm-3">
								<select style="width : 100%;" class="form-control" id="action_bike_out" name="action_bike_out"></select>
							</div>
						</div>
						
						<div id="action_tradebikes" hidden>
							<label class="col-sm-2 control-label ">Huidige fiets</label>

						</div>
						
						<div id="action_returnbikes" hidden>
							<p class="col-sm-3 form-control-static" id="action_returnbike"></p>	
						</div>
						
						<div class="actbtns">
							<input type="hidden" id="action_bikeinid" name="bikeinid" value="0">
							<button type="button" onclick="cancelTransaction()" class="btn btn-default actbtn">Annuleren</button>
							<button type="button" id="saveActionBtn" onclick="saveTransaction()" class="btn btn-primary actbtn" disabled>Opslaan</button>
						</div>
					</div>	
					
					<hr class="formhr">
					
					<div class="form-row">
						<div class="form-group action_memberdiv form-control-static" hidden>
							<label class="col-sm-1 control-label lb-sm">Ouder</label>
							<p class="col-sm-3 form-control-static" id="action_parentname"></p>
							<p class="col-sm-3 form-control-static" id="action_parentsince">Lid sinds 2015-09-04</p>
							<p class="col-sm-3 form-control-static" id="action_parentdonations" hidden>Fietsdonaties: 0</p>
							<!-- hidden temp -->	
						</div>	
						<div class="form-group action_memberdiv" hidden>
							<label class="col-sm-1 control-label lb-sm">Overzicht</label>
							<div class="col-sm-10">
								<table class="table table-condensed" id="action_kids_table">
									<thead>
										<tr>
											<th>Kind</th>
											<th>Leeftijd</th>
											<th>Actief</th>
											<th>Fiets</th>
											<th>Waarborg</th>
											<th>Lid tot</th>
										</tr>					
									</thead>
									<tbody id="action_kids_table_tbody">
									</tbody>
								</table>
								<label class="col-sm-1 control-label plabel"></label>
							</div>
						</div>
					</div>
	
				</form>
			</div>
		</div>	

    </section>

    <section id="content_bikes" class="content_section">
		<div id="tabBikesAll" class="tabContent">
	
			<h4 class="inlineh4">Overzicht Fietsen</h4> 
			<button onclick="newBike()" class="btn btn-default bikebtns">Nieuwe fiets</button>
		
			<table id="bikes_table" class="table table-striped compact" width="100%">
				<thead>
					<tr>
						<th>Nummer</th>
						<th>Naam</th>
						<th>Status</th>
						<th>Frame</th>
						<th>Wiel</th>
						<th>Ingebracht</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nummer</th>
						<th>Naam</th>
						<th>Status</th>
						<th>Frame</th>
						<th>Wiel</th>
						<th>Ingebracht</th>
						<th></th>
					</tr>
				</tfoot>
			</table>	
		</div>
		
		<div id="tabBikesOne" style="display: none;" class="tabContent">
	
			<h4 class="inlineh4">Detail Fiets</h4>
			
			<div class="container-fluid" width="100%">
				<div class="row">
					<div class="col-sm-8">
						<form id="form_bike" class="form-horizontal">
							
								<div class="form-group">
									<label class="col-sm-2 control-label lb-sm">Nummer</label>
									<div class="col-sm-2">
										<input type="number" class="form-control input-sm" id="bike_nr" name="bike_nr" value=1>
									</div>
									<label class="col-sm-1 control-label lb-sm">Naam</label>
									<div class="col-sm-3">
										<input type="text" class="form-control input-sm" id="bike_name" name="bike_name" placeholder="naam">
									</div>
									<label for="orderhistnote" class="col-md-2 control-label">Status</label>
									<p class="col-sm-2 form-control-static" id="bike_status"> </p>
								</div>	
							
							
								<div class="form-group">
									<label class="col-sm-2 control-label lb-sm">Frame</label>
									<div class="col-sm-4">
										<input type="text" class="form-control input-sm" id="bike_frame" name="bike_frame" placeholder="frame">
									</div>
									<label class="col-sm-2 control-label lb-sm">Wiel</label>
									<div class="col-sm-4">
										<input type="text" class="form-control input-sm" id="bike_wheel" name="bike_wheel" placeholder="wiel">
									</div>
								</div>	
						
								<div class="form-group">
									<label class="col-sm-2 control-label lb-sm">Ingebracht</label>
									
									<div class='col-sm-4'>	
										<div class='input-group' id='bikedatepicker'>	
											<input type='text' class="form-control input-sm" id="bike_date" name="bike_date" >
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</div>
								
									<div class="col-sm-6" hidden>
										<input type="text" class="form-control input-sm" id="bike_donator" name="bike_donator" placeholder="lid" >
									</div>
								</div>	
							
							
								<div class="form-group">
									<div class="col-sm-6">
									</div>
									<div class="input-group col-sm-6" id="actbtns">
										<input type="hidden" id="bike_id" name="bike_id" value="0">
										<button type="button" onclick="cancelBike()" class="btn btn-default actbtn">Annuleren</button>
										<button type="button" onclick="saveBike()" class="btn btn-primary actbtn">Opslaan</button>
									</div>
								</div>

													
						</form>
					</div>
				
					
					
					<div class="col-sm-4">	
						<label class="col-sm-2 control-label lb-sm">Historiek</label>
						<table id="table_orderstatushistory" class="table compact" width="100%">
						</table>
					</div>
				</div>
			</div>	


	
		</div>
	

    </section>

    <section id="content_members" class="content_section">
		<div id="tabMembersAll" class="tabContent">
	
			<h4 class="inlineh4">Overzicht Leden</h4> 
			<button onclick="newMember()" class="btn btn-default bikebtns compact">Nieuw lid</button>
		
			<table id="members_table" class="table table-striped" width="100%">
				<thead>
					<tr>
						<th>Voornaam</th>
						<th>Achternaam</th>
						<th>Kind 1</th>
						<th>Kind 2</th>
						<th>Kind 3</th>
						<th>Actief</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Voornaam</th>
						<th>Achternaam</th>
						<th>Straat</th>
						<th>Straatnr</th>
						<th>Postcode</th>
						<th>Actief</th>
						<th></th>
					</tr>
				</tfoot>
			</table>	
		</div>
		
		<div id="tabMembersOne" style="display: none;" class="tabContent">
	
			<h4 class="inlineh4">Detail gebruiker</h4>
			<div class="container-fluid" width="100%">
				<div class="col-sm-10">
					<form id="klant_form" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label">Ouder</label>
							<div class="col-sm-2">
								<input type="text" class="form-control input-sm" id="parent_name" name="parent_name" placeholder="Voornaam">
							</div>
							<div class="col-sm-4">
								<input type="text" class="form-control input-sm" id="parent_surname" name="parent_surname" placeholder="Familienaam">
							</div>
							<label class="col-sm-1 control-label">Email</label>
							<div class="col-sm-3">
								<input type="text" class="form-control input-sm" id="parent_email" name="parent_email" placeholder="email">
							</div>
						</div>	
						
						<div class="form-group">
							<label class="col-sm-2 control-label">Adres</label>
							<div class="col-sm-4">
								<input type="text" class="form-control input-sm" id="parent_street" name="parent_street" placeholder="Straat">
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control input-sm" id="parent_streetnr" name="parent_streetnr" placeholder="Nr">
							</div>
							<label class="col-sm-1 control-label">Tel</label>
							<div class="col-sm-3">
								<input type="text" class="form-control input-sm" id="parent_phone" name="parent_phone" placeholder="Tel">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-2">
								<input type="text" class="form-control input-sm" id="parent_postal" name="parent_postal" placeholder="Postcode">
							</div>
							<div class="col-sm-3">
								<input type="text" class="form-control input-sm" id="parent_town" name="parent_town" placeholder="Stad">
							</div>
							<label class="col-sm-2 control-label lb-sm">Lid sinds</label>
							<div class='col-sm-3'>	
								<div class='input-group' id='parentdatepicker'>	
									<input type='text' class="form-control input-sm" id="parent_date" name="parent_date" >
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						
						<hr class="formhr">
						
					    <div class="row">
							<div class="col-sm-2">

								<div class="form-row">
									<div class="form-group">
										<label class="col-sm-2 control-label">Kinderen</label>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group">
										<button type="button" class="btn btn-default" onclick="addNewKidRow()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

									</div>
								</div>
							</div>
							
							<div class="col-sm-10">
								<table class="table table-condensed" id="kids_table">
									<thead>
										<tr>
											<th>Voornaam</th>
											<th>Achternaam</th>
											<th>Geboortedatum</th>
											<th></th>
										</tr>					
									</thead>
									<tbody id="kids_table_tbody">
									</tbody>
								</table>
							</div>
						</div>					
						
						<div class="form-group">
							<div class="col-sm-6">
							</div>
							<div class="input-group col-sm-6" id="actbtns">
								<input type="hidden" id="parent_id" name="parent_id" value="0">
								<button type="button" onclick="cancelMember()" class="btn btn-default actbtn">Annuleren</button>
								<button type="button" onclick="saveMember()" class="btn btn-primary actbtn">Opslaan</button>
							</div>
						</div>

					</form>
				</div>
				
				<div class="col-sm-2">
					<!--
					<label class="col-sm-2 control-label lb-sm">Historiek</label>
					<table id="table_orderstatushistory" class="table compact" width="100%">
					</table>
					-->
				</div>
			</div>
	

		</div>
		
    </section>

    <section id="content_finances" class="content_section">
		<h4 class="inlineh4">Financiën</h4> 
		
		<div class="container-fluid" width="100%">
		</div>	

    </section>
 
 
     <section id="content_stats" class="content_section">
		<h4 class="inlineh4">Statistieken</h4> 
		
		<div class="container-fluid" width="100%">
		</div>	

    </section>
</div>



<!-- Row in kids table -->
<script id="kidsrow" type="text/x-handlebars-template">
    <tr data-id="{{ID}}">
		<td class="kids_name">
            <input type="text" class="form-control kids_name_input" value="{{name}}">
        </td>
		<td class="kids_surname">
            <input type="text" class="form-control kids_surname_input" value="{{surname}}">
        </td>
		<td class="kids_birthdate">
            <input type="text" class="form-control kids_birthdate_input" value="{{birthdate}}">
        </td>
        <td class="col-sm-1">
			<!--
            <button type="button" class="btn btn-default kids_deleterow">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
			-->
        </td>
    </tr>
</script>

<!-- Row in action info table -->
<script id="kidsactionrow" type="text/x-handlebars-template">
    <tr data-id="{{ID}}">
		<td>{{fullname}}</td>
		<td></td>
		<td>{{active}}</td>
		<td>{{bikeid}}</td>
		<td>{{caution}}</td>
		<td>{{expirydate}}</td>
    </tr>
</script>


<!-- js libs -->
<script src="js/jquery.js"></script>
<script src="libs/moment/2.16.0/moment.min.js"></script>
<script src="libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="libs/select2/4.0.3/dist/js/select2.min.js"></script>
<script src="libs/toastr/2.1.3/toastr.min.js"></script>
<script src="libs/handlebars/4.0.5/handlebars.js"></script>
<script src="libs/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="libs/routie/0.3.2/routie.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/rg-1.0.2/datatables.min.js"></script>

<!-- own js -->
<script src="js/globalvars.js"></script>
<script src="js/main.js"></script>
<script src="js/members.js"></script>
<script src="js/bikes.js"></script>
<script src="js/transactions.js"></script>

</body>
</html>
