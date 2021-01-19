<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">

    <title>Op Wielekes</title>

    <!-- lib css files -->
	<link href="libs/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="libs/toastr/2.1.3/toastr.min.css" rel="stylesheet"/>

	<link href="libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="libs/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
  <link href="libs/select2/4.0.13/dist/css/select2.css" rel="stylesheet"/>
	<!-- <link href="libs/datatables/datatables.min.css" rel="stylesheet"/> -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/datatables.min.css"/>
	<link href="libs/daterangepicker/2.1.25/daterangepicker.css" rel="stylesheet"/>
	<link href="libs/quill/1.3.6/quill.snow.css" rel="stylesheet">

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

<!-- div to make footer work -->
  <div class="wrapper">
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
                  <?php if (isset($_SESSION["baseurl"])) {
                    echo '<a class="navbar-brand" href="' . $_SESSION["baseurl"] . '">';
                  } ?>
                      <span><img class="mb-4" src="images/opwielekes.jpg" alt="" width="20"></span>
                      <span>  Op wielekes</span>
                  </a>

              </div>
              <div id="navbar" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav" id="main-nav">
                      <li class="active"><a href="#transactions">Ontleningen</a></li>
                      <li><a href="#bikes">Fietsen</a></li>
                      <li><a href="#members">Leden</a></li>
                      <!--<li><a href="#transactionhistory">Transacties</a></li>-->
            					<li><a href="#finances">Financiën</a></li>
            					<!--<li><a href="#stats">Dashboard</a></li>-->
  					<li class="dropdown">
  						<a href="#settings" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Instellingen <span class="caret"></span></a>
  						  <ul class="dropdown-menu">
                <li><a href="#settings_bikes">Fietsen</a></li>
  							<li><a href="#settings_prices">Financiëel</a></li>
  							<li><a href="#settings_emails">Emails</a></li>
                <li><a href="#settings_memberships">Automatische emails</a></li>
  						  </ul>
  					</li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
  					<?php if (isset($_SESSION["account"])) {
  						echo '<p class="navbar-text text-black">' . ucfirst($_SESSION["account"]["AccountName"]) . '</p>';
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

  				<form id="action_form" class="form-horizontal" target="_blank">


  					<div class="form-group">
  						<label for="action_member" class="col-sm-2 control-label">Lid</label>
  						<div class="col-sm-5">
  							<select style="width : 100%;" class="form-control" id="action_member" name="action_member"></select>
  						</div>
              <div class="col-sm-2">
  							<a href="#members" onclick="newMember()">Nieuw Lid </a>
              </div>

  					</div>

  					<div class="form-group">
  						<label class="col-sm-2 control-label lb-sm">Actie</label>
  						<div class="col-sm-3">
  							<select style="width : 100%;" class="form-control" id="action_type" name="action_type">
  								<!--<option></option>
  								<option value="start">Start</option>
  								<option value="trade">Ruil</option>
  								<option value="end">Eind</option>
  								<option value="donate">Donatie</option>-->
  							</select>
  						</div>


  						<label for="actiondatepicker" class="col-sm-1 control-label lb-sm">Datum</label>
  						<div class='input-group col-sm-3' id='actiondatepicker'>
  								<input type='text' class="form-control input-sm" id="action_date" name="action_date" />
  								<span class="input-group-addon">
  									<span class="glyphicon glyphicon-calendar"></span>

  								</span>

  								<div class='col-sm-1' id='dateUnlocked'>
  									<a class="btn btn-default btn-sm" onclick="lockDate()">
  										<i class="fa fa-unlock" aria-hidden="true"></i>
  									</a>
  								</div>
  								<div class='col-sm-1' id='dateLocked' hidden>
  									<a class="btn btn-default btn-sm" onclick="unlockDate()">
  										<i class="fa fa-lock" aria-hidden="true"></i>
  									</a>
  								</div>
  						</div>

  					</div>

  					<div class="form-group">

  						<label class="col-sm-2 control-label lb-sm">Fiets IN</label>
  						<div id="action_allbikes" hidden>
  							<div class="col-sm-3">
  								<select style="width : 100%;" class="form-control input-sm" id="action_bike_all" name="action_bike_all"></select>
  							</div>
  						</div>
  						<div id="action_currentbike" hidden>
  							<p class="col-sm-3 form-control-static" id="action_currentbiketext"></p>
  						</div>
  						<div id="action_bikein_space">
  							<p class="col-sm-3 form-control-static"></p>
  						</div>
  						<label class="col-sm-1 control-label ">Fiets UIT</label>

  						<div id="action_availablebikes" hidden>
  							<div class="col-sm-3">
  								<select style="width : 100%;" class="form-control" id="action_bike_out" name="action_bike_out"></select>
  							</div>
  						</div>



  					</div>

            <div class="form-group">

  						<label class="col-sm-2 control-label lb-sm">Lidmaatschap</label>
              <div class="action_memberdiv" hidden>
                <div class="col-sm-2">
                  <select style="width : 100%;" class="form-control" id="action_membershipsel" name="action_membershipsel">
                  </select>
                </div>

                <label class="col-sm-1 control-label lb-sm">Vervaldag</label>
                <div class='input-group col-sm-2' id='actionexpirydatepicker'>
                    <input type='text' class="form-control input-sm" id="action_expirydate" name="action_expirydate" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>

              </div>
  					</div>

            <div class="form-group">

    				  <label class="col-sm-2 control-label lb-sm">Betaling</label>
              <div class="action_actiondiv" hidden>
                <div class='col-sm-2' id="action_membershipinfo" hidden>
    							<p class="form-control-static" id="action_membershipinfotext"></p>
    						</div>
    						<div class='col-sm-2' id="action_membershipinput">
    							<input style="width : 100%;" class="form-control input-sm" type="number" step="0.01" value="0" id="amount_membership">
    						</div>
                <input type="hidden" id="amount_membership_hidden" name="amount_membership_hidden" value="0">

                <div id="action_paymentmethodselector">
    							<div class="col-sm-2">
    								<select style="width : 100%;" class="form-control" id="action_paymentmethod" name="action_paymentmethod">
                    </select>
    							</div>
  						        </div>

                <div id="action_donationbikes" hidden>
                  <div class="col-sm-3">
                    <select style="width : 100%;" class="form-control" id="action_bike_donate" name="action_bike_donate"></select>
                  </div>
                </div>
    					</div>
            </div>

  					<div class="form-group">
              <label class="col-sm-2 control-label lb-sm">Waarborg</label>
              <div class="action_actiondiv" hidden>
    						<div class='col-sm-2' id="action_cautioninfo" hidden>
    							<p class="form-control-static" id="action_cautioninfotext"></p>
    						</div>
    						<div class='col-sm-2' id="action_cautioninput">
    							<input style="width : 100%;" class="form-control input-sm" type="number" step="0.01" value="0" id="amount_caution">
                </div>
                <input type="hidden" id="amount_caution_hidden" name="amount_caution_hidden" value="0">
                <div id="action_waarborgpaymentselector">
    							<div class="col-sm-2">
    								<select style="width : 100%;" class="form-control" id="action_waarborgpaymentmethod" name="action_waarborgpaymentmethod">
    								</select>
                  </div>
    						</div>
                <label class="col-sm-2 control-label lb-sm">Huidig saldo</label>
                <p class="col-sm-1 form-control-static" id="action_parentcaution">0</p>
              </div>
  					</div>


            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm">Totaal</label>
              <div class="action_actiondiv" hidden>
                <p class="col-sm-2 form-control-static" id="action_totalpayment"></p>
                <div class='col-sm-5' id="action_paymentnoteinput">
                  <input class="form-control input-sm" type="text" value="" placeholder="Boodschap bij transactie" id="amount_paymentnote">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm">Email</label>

              <div class='col-sm-2' >
                <div class="checkbox">
                      <label><input type="checkbox" class="test" id="action_sendemail" name="action_sendemail" value="send" disabled> Verstuur</label>
                  </div>
              </div>

              <div class="actbtns">
                <input type="hidden" id="action_bikeinid" name="bikeinid" value="0">
                <button type="button" onclick="cancelTransaction()" class="btn btn-default actbtn">Annuleren</button>
                <button type="submit" id="saveActionBtn" class="btn btn-primary actbtn" disabled>Opslaan</button>
              </div>
            </div>

  					<div id="action_emaildiv" hidden>


  						<div class="form-group">
  							<label class="col-sm-2 control-label lb-sm"></label>

  							<label class="col-sm-1 control-label lb-sm">Onderwerp</label>
  							<div class='col-sm-3'>
  								<input class="form-control input-sm" type="text" id="action_emailsubject" name="action_emailsubject" >
  							</div>

  							<label class="col-sm-1 control-label lb-sm">Adres</label>
  							<div class='col-sm-2'>
  								<input class="form-control input-sm" type="text" id="action_emailaddress" name="action_emailaddress">
  							</div>
  						</div>

  						<div class="form-group">
  							<label class="col-sm-2 control-label lb-sm"></label>
  							<div class="col-sm-8">
  									<div id="actionemail">
  									</div>
  								</div>


  						</div>
  					</div>

  					<hr class="formhr">

  					<div class="form-row">
  						<div class="form-group action_memberdiv form-control-static" hidden>
  							<label class="col-sm-1 control-label lb-sm">Ouder</label>
  							<p class="col-sm-2 form-control-static" id="action_parentname"></p>
  							<label class="col-sm-2 control-label lb-sm">Lidmaatschap</label>
  							<p class="col-sm-1 form-control-static" id="action_membership"></p>
  							<label class="col-sm-2 control-label lb-sm">Actieve kids</label>
  							<p class="col-sm-1 form-control-static" id="action_parentactivekids">0</p>
  							<label class="col-sm-1 control-label lb-sm" hidden>Waarborg</label>
  							<p class="col-sm-1 form-control-static" id="action_parentcaution" hidden>0</p>
                <input type="hidden" id="action_parentid" name="action_parentid">
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
              <th>Ontlener</th>
              <th>Notities</th>
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
              <th>Ontlener</th>
              <th>Notities</th>
  						<th></th>
  					</tr>
  				</tfoot>
  			</table>
  		</div>

  		<div id="tabBikesOne" style="display: none;" class="tabContent">

  			<h4 class="inlineh4">Detail Fiets</h4>

        <div class="container-fluid" width="100%">
  				<form id="form_bike" class="form-horizontal">
  					<div class="row">

  					       <div class="col-sm-9 div_rightline">
      								<div class="form-group">
      									<label class="col-sm-2 control-label lb-sm">Nummer</label>
      									<div class="col-sm-2">
      										<input type="number" class="form-control input-sm" id="bike_nr" name="bike_nr" value=1>
      									</div>
      									<label class="col-sm-1 control-label lb-sm">Naam</label>
      									<div class="col-sm-3">
      										<input type="text" class="form-control input-sm" id="bike_name" name="bike_name" placeholder="naam">
      									</div>
                        <label class="col-sm-2 control-label">Status</label>
                        <p class="col-sm-2 form-control-static" id="bike_status_text"> </p>
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
              					<label class="col-sm-2 control-label lb-sm">Notities</label>
              					<div class="col-sm-10">
              							<div id="bike_notes">
              							</div>
              						</div>
              				</div>

      								<div class="form-group">
      									<div class="col-sm-5">
      									</div>
      									<div class="input-group col-sm-6" id="bikebtns">
      										<input type="hidden" id="bike_id" name="bike_id" value="0">
                          <input type="hidden" id="bike_statusnr" name="bike_statusnr">
                            <button type="button" onclick="deleteBike()" class="btn btn-danger actbtn">Verwijderen</button>
      										<button type="button" onclick="cancelBike()" class="btn btn-default actbtn">Annuleren</button>
      										<button type="button" onclick="saveBike()" class="btn btn-primary actbtn">Opslaan</button>
      									</div>
                        <div class="col-sm-1">
                      	</div>
      								</div>

                    </div>


                  <div class="col-sm-3" id="bikestatusdiv">
                    <div class="form-row">
      								<div class="form-group">
                        <label class="col-md-3 control-label">Status</label>
                        <div class="col-md-6 bikeleft">
                          <div style="width : 100%;">
                          <select style="width : 100%;" class="form-control" id="bike_status" name="bike_status">
                          </select>
                          </div>
                        </div>
                        <div class="col-md-3" id="bsbdiv">
                          <button type="button" id="bikestatusbtn" onclick="saveBikeStatus()" class="btn btn-primary">Opslaan</button>
                        </div>

      								</div>
      							</div>

                    <div class="form-row">
      								<div class="form-group">
      									<label class="col-md-3 control-label">Datum</label>
      									<div class='input-group col-md-6 bikeleft' id='bikestatusdatepicker'>
      											<input type='text' class="form-control input-sm" id="bikestatusdate" name="bikestatusdate" />
      											<span class="input-group-addon">
      												<span class="glyphicon glyphicon-calendar"></span>
      											</span>
      									</div>
                        <div class='input-group col-md-3'>
      								  </div>
      							</div>

                    <div class="form-row">
      								<table id="table_bikestatushistory" class="table compact" width="100%">
      								</table>
      							</div>
  					     </div>

              </div>
	         </form>

  			</div>



  		</div>


      </section>

      <section id="content_members" class="content_section">
  		    <div id="tabMembersAll" class="tabContent">

  			    <h4 class="inlineh4">Overzicht Leden</h4>
            <button onclick="newMember()" class="btn btn-default bikebtns">Nieuw lid</button>
            <?php if (isset($_SESSION["baseurl"])) {
              echo '<a class="button btn btn-default bikebtns compact" href="' . $_SESSION["baseurl"] . '/signup" target="_blank">Zelf inschrijven</a>';
            } ?>
            <button onclick="loadMembers()" class="btn btn-default bikebtns compact" style="margin-bottom:20px;">Tabel herladen</button>

      			<table id="members_table" class="table table-striped" width="100%">
      				<thead>
      					<tr>
      						<th>Voornaam</th>
      						<th>Achternaam</th>
      						<th>Straat</th>
      						<th>Lidmaatschap</th>
      						<th>Actieve kids</th>
      						<th>Waarborg</th>
      						<th>Donaties</th>
                  <th>Notities</th>
                  <th>Email</th>
                  <th>Telefoon</th>
                  <th>Lid sinds</th>
      						<th></th>
      					</tr>
      				</thead>
      				<tfoot>
      					<tr>
      						<th>Voornaam</th>
      						<th>Achternaam</th>
      						<th>Straat</th>
      						<th>Lidmaatschap</th>
      						<th>Actieve kids</th>
      						<th>Waarborg</th>
      						<th>Donaties</th>
                  <th>Notities</th>
                  <th>Email</th>
                  <th>Telefoon</th>
                  <th>Lid sinds</th>
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
        								<input type="text" class="form-control input-md" id="parent_name" name="parent_name" placeholder="Voornaam">
        							</div>
        							<div class="col-sm-4">
        								<input type="text" class="form-control input-md" id="parent_surname" name="parent_surname" placeholder="Familienaam">
        							</div>
                      <label class="col-sm-1 control-label">Tel</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control input-md" id="parent_phone" name="parent_phone" placeholder="Tel">
                      </div>
        						</div>

        						<div class="form-group">
        							<label class="col-sm-2 control-label">Adres</label>
        							<div class="col-sm-4">
        								<input type="text" class="form-control input-md" id="parent_street" name="parent_street" placeholder="Straat">
        							</div>
        							<div class="col-sm-2">
        								<input type="text" class="form-control input-md" id="parent_streetnr" name="parent_streetnr" placeholder="Nr">
        							</div>
                      <label class="col-sm-1 control-label">Email</label>
        							<div class="col-sm-3">
        								<input type="text" class="form-control input-md" id="parent_email" name="parent_email" placeholder="email">
        							</div>
        						</div>

        						<div class="form-group">
        							<label class="col-sm-2 control-label"></label>
        							<div class="col-sm-2">
        								<input type="text" class="form-control input-md" id="parent_postal" name="parent_postal" placeholder="Postcode">
        							</div>
        							<div class="col-sm-3">
        								<input type="text" class="form-control input-md" id="parent_town" name="parent_town" placeholder="Stad">
        							</div>
                      <label class="col-sm-2 control-label">Rekeningnr</label>
                      <div class="col-sm-3">
        								<input type="text" class="form-control input-md" id="parent_iban" name="parent_iban" placeholder="IBAN">
        							</div>
        						</div>

                    <div class="form-group">
        							<label class="col-sm-2 control-label">Lidmaatschap</label>
        							<div class="col-sm-4">
                        <select style="width : 100%;" class="form-control" id="parent_membership" name="parent_membership">
                        </select>
                      </div>
                      <label class="col-sm-3 control-label lb-sm">Lid sinds</label>
                      <div class='col-sm-3'>
                        <div class='input-group' id='parentdatepicker'>
                          <input type='text' class="form-control input-md" id="parent_date" name="parent_date" >
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
        						</div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label lb-sm">Notities</label>
                      <div class="col-sm-10">
                          <div id="parent_notes">
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
          											<th>Achternaam</th>
                                <th>Voornaam</th>
          											<th>Geboortedatum</th>
                                <th>Fiets</th>
                                <th>Lid tot</th>
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
                        <button type="button" onclick="deleteMember()" class="btn btn-danger actbtn">Verwijderen</button>
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

      <section id="content_transactionhistory" class="content_section">
       <h4 class="inlineh4">Transacties</h4>

       <div class="container-fluid" width="100%">
         <table id="transactions_table" class="table table-striped" width="100%">
           <thead>
             <tr>
               <th>Datum</th>
               <th>Ouder</th>
               <th>Kind</th>
               <th>Actie</th>
               <th>Fiets IN</th>
               <th>Fiets UIT</th>
             </tr>
           </thead>
           <tfoot>
             <tr>
               <th>Datum</th>
               <th>Ouder</th>
               <th>Kind</th>
               <th>Actie</th>
               <th>Fiets IN</th>
               <th>Fiets UIT</th>
             </tr>
           </tfoot>
         </table>
       </div>

     </section>



      <section id="content_finances" class="content_section">
  		<h4 class="inlineh4">Financiële transacties</h4>

  		<div class="container-fluid" width="100%">

  			<table id="finances_table" class="table table-striped" width="100%">
  				<thead>
  					<tr>
              <th>Datum</th>
  						<th>Ouder</th>
              <th>Straat</th>
              <th>Straatnr</th>
              <th>Postcode</th>
              <th>Stad</th>
              <th>Email</th>
              <th>Tel</th>
  						<th>Kind</th>
              <th>Actie</th>
              <th>Waarborg</th>
              <th>Wb wijze</th>
              <th>Wb betaald</th>
              <th>Abonnement</th>
              <th>Ab wijze</th>
              <th>Ab betaald</th>
  						<th>Totaal</th>
  						<th>Notities</th>
  						<th></th>
  					</tr>
  				</thead>
  				<tfoot>
  					<tr>
  						<th>Datum</th>
  						<th>Ouder</th>
              <th>Straat</th>
              <th>Straatnr</th>
              <th>Postcode</th>
              <th>Stad</th>
              <th>Email</th>
              <th>Tel</th>
  						<th>Kind</th>
              <th>Actie</th>
              <th>Waarborg</th>
              <th>Wb wijze</th>
              <th>Wb betaald</th>
              <th>Abonnement</th>
              <th>Ab wijze</th>
              <th>Ab betaald</th>
  						<th>Totaal</th>
  						<th>Notities</th>
  						<th></th>
  					</tr>
  				</tfoot>
  			</table>

        <!-- Modal -->
        <div class="modal fade" id="editFinanceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Betaling wijzigen</h4>
                    </div>
                    <div class="modal-body">
                        <form id="editFinanceForm">
                            <div class="form-group">
                                <label for="fin_date" class="control-label">Datum:</label>
                                <div class="input-group" id='findatepicker'>
                  								<input type='text' class="form-control input-sm" id="fin_date" name="fin_date" />
                  								<span class="input-group-addon">
                  									 <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fin_membershipinfo" class="control-label">Lidmaatschap:</label>
                                <p class="form-control-static" id="fin_membershipinfo"> </p>
                            </div>

                            <div class="form-group">
                                <label for="fin_membershipstatus" class="control-label">Status lidmaatschap:</label>
                                <select style="width : 100%;" id="fin_membershipstatus">
                                    <option value="1">Voldaan</option>
                                    <option value="0">In afwachting</option>
                                  </select>
                            </div>

                            <div class="form-group">
                                <label for="fin_cautioninfo" class="control-label">Waarborg:</label>
                                <p class="form-control-static" id="fin_cautioninfo"> </p>
                            </div>

                            <div class="form-group">
                                <label for="fin_cautionstatus" class="control-label">Status waarborg:</label>
                                <select style="width : 100%;" id="fin_cautionstatus">
                                    <option value="1">Voldaan</option>
                                    <option value="0">In afwachting</option>
                                  </select>
                            </div>
                            <input type="hidden" id="fin_id" name="fin_id" value="0">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                        <button type="button" class="btn btn-primary" id="submitEditFinance">Opslaan</button>
                    </div>
                </div>
            </div>
        </div>

  			<button type="button" onclick="checkExpiryDates()" class="btn btn-primary" style="display: none;">Controleer lidmaatschap</button>

  			<table id="expiry_table" class="table table-striped" width="100%" style="display: none;">
  				<thead>
  					<tr>
  						<th>Ouder</th>
  						<th>Kind</th>
  						<th>Kind nr</th>
  						<th>Fiets</th>
  						<th>Waarborg</th>
  						<th>Vervaldag</th>
  						<th>Tegoed (EUR)</th>
  						<th></th>
  					</tr>
  				</thead>
  				<tfoot>
  					<tr>
  						<th>Ouder</th>
  						<th>Kind</th>
  						<th>Kind nr</th>
  						<th>Fiets</th>
  						<th>Waarborg</th>
  						<th>Vervaldag</th>
  						<th>Tegoed (EUR)</th>
  						<th></th>
  					</tr>
  				</tfoot>
  			</table>

  		</div>
      </section>

       <section id="content_stats" class="content_section">
  		<h4 class="inlineh4">Statistieken</h4>

  		<div class="container-fluid" width="100%">
  		</div>

      </section>

      <section id="content_settings_bikes" class="content_section">
        <h4 class="inlineh4">Instellingen: Fietsen status</h4>

        <div class="container-fluid" width="100%">
          <form id="settings_bikes_form">

            <div class="col-md-3">
            </div>

            <div class="col-md-6">
              <table id="settings_bikes_table" class="table" width="50%">
                <thead>
                  <tr>
                    <th scope='col'>Omschrijving</th>
                    <th scope='col'>Beschikbaar voor uitlening</th>
                    <th scope='col'>In gebruik</th>
                  </tr>
                </thead>
                <tbody id="settings_bikes_table_tbody">
                </tbody>
              </table>
            </div>

            <div class="col-md-3">
            </div>
            <!--
            <div class="form-group">
                <label class="col-sm-3 control-label lb-sm">Standaard status na terugbrengen fietsje</label>
                <div class="col-sm-3">
                  <select style="width : 100%;" class="form-control" id="default_bikestatus" name="default_bikestatus">
                  </select>
                </div>
            </div>
          -->


            <div class="input-group col-sm-9" id="actbtns">
              <button type="button" onclick="cancelBikeSettings()" class="btn btn-default actbtn">Annuleren</button>
              <button type="button" onclick="saveBikeSettings()" class="btn btn-primary actbtn">Opslaan</button>
            </div>
          </form>
        </div>

    </section>

  	<section id="content_settings_prices" class="content_section">
  		<h4 class="inlineh4">Instellingen: Financiëel</h4>

  		<div class="container-fluid" width="100%">


  			<form id="settings_prices_form">

          <div id="pricesformtarieven" class="container-fluid" width="100%">
            <h4 class="inlineh4">Tarieven</h4>
    				<table id="settings_prices_table" class="table" >
    					<thead>
                <tr>
                  <th colspan="1" scope='colgroup'></th>
                  <th colspan="3" scope='colgroup' class="outlined">Geldig</th>
                  <th colspan="4" scope='colgroup' class="outlined">Prijs lidmaatschap</th>
                  <th colspan="4" scope='colgroup' class="outlined">Prijs waarborg</th>
                </tr>
      					<tr>
      						<th scope='col'>Type</th>
                  <th scope='col'>Jaren</th>
                  <th scope='col'>Maanden</th>
      						<th scope='col'>Dagen</th>
      						<th scope='col'>Kind 1</th>
      						<th scope='col'>Kind 2</th>
      						<th scope='col'>Kind 3</th>
      						<th scope='col'>Kind 4+</th>
                  <th scope='col'>Kind 1</th>
                  <th scope='col'>Kind 2</th>
                  <th scope='col'>Kind 3</th>
                  <th scope='col'>Kind 4+</th>
      					</tr>
    					</thead>
    					<tbody id="settings_prices_table_tbody">
    					</tbody>
    				</table>


    				<div class="col-md-12 text-center">
    					<button type="button" onclick="cancelMembershipPrices()" class="btn btn-default">Annuleren</button>
    					<button type="button" onclick="saveMembershipPrices()" class="btn btn-primary">Opslaan</button>
    				</div>
          </div>

          <hr />



          <div id="pricesformstandaarbetaalinfo" class="container-fluid" width="100%">
              <h4 class="inlineh4">Betaalgegevens</h4>

              <div class="container">
                <div class="row">
                  <div class="col-md-2">
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="settings_defaultpaymentmethod" class="col-md-7 control-label">De voorkeursbetaalmethode in de ontleningen tab</label>
                      <div class="col-md-5">
                        <select style="width : 100%;" class="form-control" id="settings_defaultpaymentmethod" name="settings_defaultpaymentmethod"></select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                  </div>

                </div>

                <div class="row" style="padding-top:10px;">
                  <div class="col-md-2">
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="settings_defaultIBAN" class="col-md-7 control-label">IBAN nr van het depot te gebruiken in emails</label>
                      <div class="col-md-5">
                        <input style="width : 100%;" class="form-control" id="settings_defaultIBAN" name="settings_defaultIBAN">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                  </div>
                </div>

              </div>

              <div class="col-md-12 text-center" style="padding-top:20px;">
                <button type="button" onclick="cancelDefaultPaymentInfo()" class="btn btn-default">Annuleren</button>
                <button type="button" onclick="saveDefaultPaymentInfo()" class="btn btn-primary">Opslaan</button>
              </div>

          </div>


          <hr />

          <div id="pricesformbetaalmethodes" class="container-fluid" width="100%">
            <h4 class="inlineh4">Betaalmethodes</h4>

            <div>
              <div class="col-md-2">
              </div>

              <div class="col-md-8">
                <table id="settings_paymentmethods_table" class="table">
                  <thead>
                    <tr>
                      <th scope='col'>Omschrijving</th>
                      <th scope='col'>Actief</th>
                      <th scope='col'>Onmiddelijke inning *</th>
                      <th scope='col'>Lidmaatschap gratis °</th>
                      <th scope='col'>Fiets selecteren</th>
                    </tr>
                  </thead>
                  <tbody id="settings_paymentmethods_table_tbody">
                  </tbody>
                  <tfoot style="text-align:center">
                     <tr>
                         <td colspan="5" scope='colgroup' class="outlined">* Geen bevestiging nodig dat dit ontvangen is (in het financiën tabblad). <br/> ° De bijdrage voor het lidmaatschap wordt kwijtgescholden. <br/> Je kan de standaardbetaalmethode niet deactiveren.</td>
                     </tr>
                   </tfoot>
                </table>
              </div>

              <div class="col-md-2">
              </div>


            </div>

            <div class="col-md-12 text-center">
              <button type="button" onclick="cancelPaymentMethods()" class="btn btn-default">Annuleren</button>
              <button type="button" onclick="savePaymentMethods()" class="btn btn-primary">Opslaan</button>
            </div>

          </div>
  			</form>
  		</div>

      </section>

  	<section id="content_settings_emails" class="content_section">
    	<h4 class="inlineh4">Email Instellingen</h4>

      <div class="container-fluid" width="100%">
        <form id="settings_email_preferences" class="form-horizontal">


          <div class="form-group">
            <label class="col-sm-2 control-label lb-sm">Afzender naam</label>
            <div class="col-sm-4">
              <input style="width : 100%;" class="form-control" id="settings_email_sendername" name="settings_email_sendername">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label lb-sm">Reply to emailadres</label>
            <div class="col-sm-4">
              <input style="width : 100%;" class="form-control" id="settings_email_replytoemail" name="settings_email_replytoemail">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label lb-sm">Reply to naam</label>
            <div class="col-sm-4">
              <input style="width : 100%;" class="form-control" id="settings_email_replytoname" name="settings_email_replytoname">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label lb-sm">CC adres</label>
            <div class='col-sm-4'>
              <input class="form-control" type="text" value="" id="settings_email_cc" name="settings_email_cc">
            </div>
          </div>

          <div class="input-group col-sm-10 actbtns">
            <button type="button" onclick="cancelEmailPreferences()" class="btn btn-default actbtn">Annuleren</button>
            <button type="button" onclick="saveEmailPreferences()" class="btn btn-primary actbtn">Opslaan</button>
          </div>

        </form>
      </div>

      <hr class="formhr">

  		<h4 class="inlineh4">Email Templates</h4>

  		<div class="container-fluid" width="100%">
  			<form id="settings_email_form" class="form-horizontal">

  				<div class="form-group">
  					<label class="col-sm-2 control-label lb-sm">Template</label>
  					<div class="col-sm-4">
  						<select style="width : 100%;" class="form-control" id="settings_email_name" name="settings_email_name">
  						</select>
  					</div>
  				</div>



  				<div class="form-group">
  					<label class="col-sm-2 control-label lb-sm">Onderwerp</label>
  					<div class='col-sm-5'>
  						<input class="form-control input-sm" type="text" id="settings_email_subject" name="settings_email_subject">
  					</div>
  				</div>

  				<div class="form-group">
  					<label class="col-sm-2 control-label lb-sm">Bericht</label>
  					<div class="col-sm-8">
  							<div id="settings_email_message">
  							</div>
  						</div>
  				</div>

          <div class="form-group">
							<label for="emailmsg" class="col-md-2 control-label"></label>
							<div class="col-sm-8">
								<p>Volgende codes in het onderwerp en de tekst worden ingevuld door de echte waarden:<br>
								{{voornaam_ouder}},{{achternaam_ouder}},{{voornaam_kind}},{{achternaam_kind}},{{IBAN_ouder}},{{IBAN_depot}},
                {{bedrag_totaal}},{{bedrag_waarborg}},{{bedrag_lidmaatschap}}
								</p>
							</div>
					</div>

          <div class="input-group col-sm-10 actbtns">
            <button type="button" onclick="deleteEmail()" class="btn btn-danger actbtn">Verwijderen</button>
  					<button type="button" onclick="cancelEmail()" class="btn btn-default actbtn">Annuleren</button>
  					<button type="button" onclick="saveEmail()" class="btn btn-primary actbtn">Opslaan</button>
  				</div>

  			</form>
  		</div>
  			<hr class="formhr">

  		<h4 class="inlineh4">Emails koppelen aan acties</h4>

  		<div class="container-fluid" width="100%">
  			<form id="settings_action_form" class="form-horizontal">
  				<div class="form-group">
  					<label class="col-sm-2 control-label lb-sm">Actie</label>
  					<div class="col-sm-3">
  						<select style="width : 100%;" class="form-control" id="settings_email_action" name="settings_email_action">
  						</select>
  					</div>
  				</div>

  				<div class="form-group">
  					<label class="col-sm-2 control-label lb-sm"></label>
  					<div class='col-sm-8' >
  						<input class="styled" type="checkbox" id="settings_emaillink_actionsend" name="settings_emaillink_actionsend" value="settings_emaillink_actionsend">
                          <label for="settings_emaillink_actionsend">
                              Template versturen bij deze actie
                          </label>
  					</div>
  				</div>

  				<div class="form-group">
  					<label class="col-sm-2 control-label lb-sm">Template</label>
  					<div class="col-sm-3">
  						<select style="width : 100%;" class="form-control" id="settings_emaillink_template" name="settings_emaillink_template" disabled>
  						</select>
  					</div>
  				</div>

          <div class="input-group col-sm-5 actbtns">
            <button type="button" onclick="cancelEmailLink()" class="btn btn-default actbtn">Annuleren</button>
            <button type="button" onclick="saveEmailLink()" class="btn btn-primary actbtn">Opslaan</button>
          </div>

  			</form>


  		</div>

      </section>

      <section id="content_settings_memberships" class="content_section">


        <div class="container-fluid" width="100%">
          <form id="settings_email_signuppreferences" class="form-horizontal">
            <h4 class="inlineh4">Bevestiging van zelf in te schrijven</h4>
            <div class="form-group form-inline">
              <label class="col-sm-2 control-label lb-sm"></label>
              <div class="col-sm-10">
                <input type="checkbox" required name="settings_membership_signupsend" id="settings_membership_signupsend"> <label for="terms1" class="plabel"> Een automatische bevestiging sturen
                  dat het lidmaatschap aangemaakt werd.</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
                <label class="col-sm-1 control-label lb-sm">Onderwerp</label>
              <div class='col-sm-6'>
                <input class="form-control input-sm" type="text" value="" placeholder="Onderwerp" id="settings_membership_signupsubject" name="settings_membership_signupsubject">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
              <label class="col-sm-1 control-label lb-sm">Bericht</label>
              <div class="col-sm-6">
                  <div id="settings_membership_signuptext">
                  </div>
                </div>
            </div>


            <h4 class="inlineh4">Herinneringen bij vervallen van lidmaatschap</h4>

            <div class="form-group form-inline">
              <label class="col-sm-2 control-label lb-sm">Herinnering 1</label>
              <div class="col-sm-10">
              <input type="checkbox" required name="settings_membership_reminder1send" id="settings_membership_reminder1send"> <label for="terms1" class="plabel"> Een automatische herinnering sturen
                <select class="form-control" name="settings_membership_reminder1days" id="settings_membership_reminder1days">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                </select>
                  dagen voor de vervaldag. Het bedrag wordt toegvoegd aan de financiëntabel.</label>

              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
                <label class="col-sm-1 control-label lb-sm">Onderwerp</label>
              <div class='col-sm-6'>
                <input class="form-control input-sm" type="text" value="" placeholder="Onderwerp" id="settings_membership_reminder1subject" name="settings_membership_reminder1subject">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
              <label class="col-sm-1 control-label lb-sm">Bericht</label>
              <div class="col-sm-6">
                  <div id="settings_membership_reminder1text">
                  </div>
                </div>
            </div>

            <div class="form-group form-inline">
              <label class="col-sm-2 control-label lb-sm">Herinnering 2</label>
              <div class="col-sm-10">
              <input type="checkbox" required name="settings_membership_reminder2send" id="settings_membership_reminder2send"> <label for="terms1" class="plabel"> Een automatische herinnering sturen
                <select class="form-control" name="settings_membership_reminder2days" id="settings_membership_reminder2days">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                </select>
                 dag(en) voor de vervaldag, indien de betaling nog niet ontvangen is.</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
              <label class="col-sm-1 control-label lb-sm">Onderwerp</label>
              <div class='col-sm-6'>
                <input class="form-control input-sm" type="text" value="" placeholder="Onderwerp" id="settings_membership_reminder2subject" name="settings_membership_reminder2subject">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
              <label class="col-sm-1 control-label lb-sm">Bericht</label>
              <div class="col-sm-6">
                  <div id="settings_membership_reminder2text">
                  </div>
                </div>
            </div>

            <div class="form-group form-inline">
              <label class="col-sm-2 control-label lb-sm">Herinnering 3</label>
              <div class="col-sm-10">
              <input type="checkbox" required name="settings_membership_reminder3send" id="settings_membership_reminder3send"> <label for="terms1" class="plabel"> Een automatische herinnering sturen
                <select class="form-control" name="settings_membership_reminder3days" id="settings_membership_reminder3days">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                </select>
                 dag(en) voor de vervaldag, indien de betaling nog niet ontvangen is.</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
              <label class="col-sm-1 control-label lb-sm">Onderwerp</label>
              <div class='col-sm-6'>
                <input class="form-control input-sm" type="text" value="" placeholder="Onderwerp" id="settings_membership_reminder3subject" name="settings_membership_reminder3subject">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label lb-sm"></label>
              <label class="col-sm-1 control-label lb-sm">Bericht</label>
              <div class="col-sm-6">
                  <div id="settings_membership_reminder3text">
                  </div>
                </div>
            </div>

            <div class="input-group col-sm-9 actbtns">
              <button type="button" onclick="cancelEmailReminders()" class="btn btn-default actbtn">Annuleren</button>
              <button type="button" onclick="saveEmailReminders()" class="btn btn-primary actbtn">Opslaan</button>
            </div>

          </form>
        </div>


        </section>

  </div>

  <!-- div to make footer work -->
  <div class="push"></div>

</div> <!-- close wrapper div -->

<!--
<div class="footer">
      <span class="text-muted">Problemen of suggesties: <a href="mailto:webmaster@opwielekes.be?Subject=Opwielekes">webmaster</a>.</span>
</div>
-->

<!-- Row in kids table -->
<script id="kidsrow" type="text/x-handlebars-template">
    <tr data-id="{{ID}}" data-name="{{name}}" data-surname="{{surname}}" data-active="{{active}}" data-expirydate="{{expirydate}}">
        <td class="kids_surname">
            <input type="text" class="form-control kids_surname_input" value="{{surname}}">
        </td>
        <td class="kids_name">
            <input type="text" class="form-control kids_name_input" value="{{name}}">
        </td>
		     <td class="kids_birthdate">
            <input type="text" class="form-control kids_birthdate_input" value="{{birthdate}}">
        </td>
        <td class="kids_bike">
           <input disabled type="text" class="form-control kids_bike_input" value="{{bike}}">
       </td>
        <td class="kids_expirydate">
            <input disabled type="text" class="form-control kids_expirydate_input" value="{{expirydate}}">
        </td>
        <td class="col-sm-1">
            <button type="button" class="btn btn-default deletekidrow">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
        </td>
    </tr>
</script>

<!-- Row in action info table -->
<script id="kidsactionrow" type="text/x-handlebars-template">
    <tr data-id="{{ID}}">
		<td>{{fullname}}</td>
		<td>{{age}}</td>
		<td>{{active}}</td>
		<td>{{bikenr}}</td>
		<td>{{expirydate}}</td>
    </tr>
</script>


<!-- js libs -->
<script src="js/jquery.js"></script>
<!--<script src="libs/moment/2.16.0/moment.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script src="libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="libs/select2/4.0.13/dist/js/select2.min.js"></script>
<script src="libs/toastr/2.1.3/toastr.min.js"></script>
<script src="libs/handlebars/4.0.5/handlebars.js"></script>
<script src="libs/datetimepicker/nl-be.js"></script>
<script src="libs/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="libs/routie/0.3.2/routie.js"></script>
<script src="libs/daterangepicker/2.1.25/daterangepicker.js"></script>
<!-- <script src="libs/datatables/datatables.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/datatables.min.js"></script>
<script type="text/javascript" src="libs/datatables/ellipsis.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
<script src="libs/quill/1.3.6/quill.min.js"></script>

<!-- own js -->
<script src="js/globalvars.js"></script>
<script src="js/finances.js"></script>
<script src="js/settings.js"></script>
<script src="js/members.js"></script>
<script src="js/bikes.js"></script>
<script src="js/transactions.js"></script>
<script src="js/main.js"></script>


</body>
</html>
