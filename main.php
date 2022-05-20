<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/x-icon" href="https://admin.opwielekes.be/images/favicon.ico">
    <!-- <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.ico"> -->
    <link rel="manifest" href="images/site.webmanifest">

    <title>Op Wielekes</title>

    <!-- lib css files -->
	<link href="libs/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="libs/toastr/2.1.3/toastr.min.css" rel="stylesheet"/>

	<link href="libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link href="libs/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
  <link href="libs/select2/4.0.13/dist/css/select2.css" rel="stylesheet"/>
	<!-- <link href="libs/datatables/datatables.min.css" rel="stylesheet"/> -->
  <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/datatables.min.css"/>  -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/datatables.min.css"/>
	<link href="libs/daterangepicker/2.1.25/daterangepicker.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.css" integrity="sha512-+eoiXLTtw/fDauKv6qMjHuO5pCnqJLz83WWIEpCF+fCAoIkK4UNy04CtJbNZ73Oo/WeNom5FwKie4NVorKjomA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="libs/quill/1.3.6/quill.snow.css" rel="stylesheet">
  <link href="libs/quill-emoji/0.2.0/dist/quill-emoji.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <link href="css/opwielekes.css" rel="stylesheet"/>
    <style type='text/css'>
        .content_section:not(.active) {
            display: none;
        }
    </style>

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
                      <!--<li><a href="#kids">Lidmaatschappen</a></li>-->
                      <!--<li><a href="#transactionhistory">Transacties</a></li>-->
            					<li><a href="#finances">Financiën</a></li>
            					<!--<li><a href="#stats">Dashboard</a></li>-->
  					<li class="dropdown">
  						<a href="#settings" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Instellingen <span class="caret"></span></a>
  						  <ul class="dropdown-menu">
                <li><a href="#settings_bikes">Fietsen</a></li>
  							<li><a href="#settings_prices">Financiëel</a></li>
  							<li><a href="#settings_emails">Emails</a></li>
                <li><a href="#settings_auto_emails">Automatische emails</a></li>
                <li><a href="#settings_memberships">Lidmaatschappen</a></li>
                <li><a href="#settings_faq">FAQ</a></li>
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

            <input type="hidden" id="act_bi" name="act_bi" value="<?php echo $_SESSION["account"]["AccountCode"]?>">

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
  							<p class="col-sm-3 form-control-static preducedvertspace" id="action_currentbiketext"></p>
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
                  <div class="input-group col-sm-2" id="actionexpirydatepicker">
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
                <p class="col-sm-1 form-control-static preducedvertspace" id="action_parentcaution">0</p>
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
  							<label class="col-sm-2 control-label lb-sm style='float: left;'">Verantwoordelijke <i class="fa fa-info-circle" title="Ouder, grootouder, voogd, ..."></i></label>
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
  							<label class="col-sm-2 control-label lb-sm">Overzicht</label>
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
              <th>Foto</th>
  						<th>Nr</th>
  						<th>Naam</th>
  						<th>Status</th>
              <th>Merk</th>
              <th>Gender</th>
  						<th>Frame</th>
  						<th>Wiel</th>
              <th>Band</th>
              <th>Versnellingen</th>
  						<th>Kleur</th>
              <th>Locatie</th>
  						<th>Ingebracht</th>
              <th>Ontleend</th>
              <th>Ontlener</th>
              <th>Notities</th>
  						<th></th>
  					</tr>
  				</thead>
  				<tfoot>
  					<tr>
              <th>Foto</th>
              <th>Nr</th>
  						<th>Naam</th>
  						<th>Status</th>
              <th>Merk</th>
              <th>Gender</th>
  						<th>Frame</th>
  						<th>Wiel</th>
              <th>Band</th>
              <th>Versnellingen</th>
  						<th>Kleur</th>
              <th>Locatie</th>
  						<th>Ingebracht</th>
              <th>Ontleend</th>
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

                     <!-- will be populated dynamically depending on image-->
                     <div id="bike_basics_div" >
                     </div>

                     <!-- TODO delete after pdf upload complete -->

                        <!-- <div class="form-group">
                          <div class="col-sm-2" >
                          </div>
                          <div class="clsbox-1 col-sm-4" runat="server" style="max-width: 500px;" hidden>
                            <div class="dropzone clsbox" id="my-great-dropzone">
                            </div>
                          </div>
                          <div class="col-sm-6" >
                          </div>
                        </div> -->


                      <!-- will be populated dynamically depending on selected fields-->
                      <div id="bike_fields_div">
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
                          <button type="button" onclick="deleteBike()" class="btn btn-danger actbtn">Archiveren</button>
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
        							<label class="col-sm-2 control-label">Verantwoordelijke</label>
        							<div class="col-sm-2" >
        								<input type="text" tabindex="1" class="form-control input-md" id="parent_name" name="parent_name" placeholder="Voornaam">
        							</div>
        							<div class="col-sm-4">
        								<input type="text"  tabindex="2" class="form-control input-md" id="parent_surname" name="parent_surname" placeholder="Familienaam">
        							</div>
                      <label class="col-sm-1 control-label">Tel</label>
                      <div class="col-sm-3" >
                        <input type="text" tabindex="7" class="form-control input-md" id="parent_phone" name="parent_phone" placeholder="Tel">
                      </div>
        						</div>

        						<div class="form-group">
        							<label class="col-sm-2 control-label">Adres</label>
        							<div class="col-sm-4" >
        								<input type="text" tabindex="3" class="form-control input-md" id="parent_street" name="parent_street" placeholder="Straat">
        							</div>
        							<div class="col-sm-2" >
        								<input type="text" tabindex="4" class="form-control input-md" id="parent_streetnr" name="parent_streetnr" placeholder="Nr">
        							</div>
                      <label class="col-sm-1 control-label">Email</label>
        							<div class="col-sm-3" >
        								<input type="text" tabindex="8" class="form-control input-md" id="parent_email" name="parent_email" placeholder="email">
        							</div>
        						</div>

        						<div class="form-group">
        							<label class="col-sm-2 control-label"></label>
        							<div class="col-sm-2" >
        								<input type="text" tabindex="5" class="form-control input-md" id="parent_postal" name="parent_postal" placeholder="Postcode">
        							</div>
        							<div class="col-sm-3" >
        								<input type="text" tabindex="6" class="form-control input-md" id="parent_town" name="parent_town" placeholder="Stad">
        							</div>
                      <label class="col-sm-2 control-label">Rekeningnr <i class="fa fa-info-circle" title="Om de waarborg te kunnen terugstorten."></i></label>
                      <div class="col-sm-3" >
        								<input type="text" tabindex="9" class="form-control input-md" id="parent_iban" name="parent_iban" placeholder="IBAN" >
        							</div>
        						</div>

                    <div class="form-group">
        							<label class="col-sm-2 control-label">Lidmaatschap</label>
        							<div class="col-sm-4">
                        <select  tabindex="10" style="width : 100%;" class="form-control" id="parent_membership" name="parent_membership">
                        </select>
                      </div>
                      <label class="col-sm-3 control-label lb-sm">Lid sinds</label>
                      <div class='col-sm-3'>
                        <div class='input-group' id='parentdatepicker'>
                          <input type='text' tabindex="11" class="form-control input-md" id="parent_date" name="parent_date" >
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
        						</div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label lb-sm">Notities</label>
                      <div class="col-sm-10" >
                          <div id="parent_notes">
                          </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label lb-sm">Kinderen  <button type="button" class="btn btn-default btn-sm" onclick="addNewKidRow()"><span class="fa fa-plus" aria-hidden="true"></span></button></label>


                         <div class="col-sm-10">
                           <table class="table table-condensed" id="kids_table">
                             <thead>
                               <tr>
                                 <th>Achternaam</th>
                                 <th>Voornaam</th>
                                 <th>Geboortedatum</th>
                                 <th>Fiets <i class="fa fa-info-circle" title="Dit veld wordt later automatisch ingevuld via het tabblad ontleningen)."></i></th>
                                 <th>Lid tot <i class="fa fa-info-circle" title="Dit veld wordt later automatisch ingevuld via het tabblad ontleningen)."></i></th>
                                 <th>  </th>
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
                        <button type="button" onclick="deleteMember()" class="btn btn-danger actbtn">Archiveren</button>
        								<button type="button" onclick="cancelMember()" class="btn btn-default actbtn">Annuleren</button>
        								<button type="button" onclick="saveMember()" class="btn btn-primary actbtn">Opslaan</button>
        							</div>
        						</div>

                    <hr class="formhr">

   					       <div class="row" id="parenthistory">
   							       <div class="col-sm-2">
           								<div class="form-row">
           									<div class="form-group">
           										<label class="col-sm-2 control-label">Geschiedenis</label>
           									</div>
           								</div>
   							        </div>

           							<div class="col-sm-10">
           								<table class="table table-condensed" id="parenthistory_table" width="100%">
           									<thead>
                              <tr>
                                <th>Datum</th>
                                <th>Kind</th>
                                <th>Actie</th>
                                <th>Fiets IN</th>
                                <th>Fiets UIT</th>
                              </tr>
           									</thead>
           									<tbody id="parenthistory_table_tbody">
           									</tbody>
           								</table>
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
  		<h4 class="inlineh4">Financiële transacties <i class="fa fa-info-circle" title="Volg hier op welke acties er gebeurden per lid, en zie de openstaande betalingen die je nog moet ontvangen. Zet de transacties op voldaan als je ze hebt ontvangen of terugbetaald (bv waarborg)."></i></h4>

  		<div class="container-fluid" width="100%">

  			<table id="finances_table" class="table table-striped" width="100%">
  				<thead>
  					<tr>
              <th>Datum</th>
  						<th>Verantw.</th>
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
  						<th>Verantw.</th>
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

                            <div class="form-group finmodal_caution">
                                <label for="fin_cautioninfo" class="control-label">Waarborg:</label>
                                <p class="form-control-static" id="fin_cautioninfo"> </p>
                            </div>

                            <div class="form-group finmodal_caution">
                                <label for="fin_cautionstatus" class="control-label">Status waarborg:</label>
                                <select style="width : 100%;" id="fin_cautionstatus">
                                    <option value="1">Voldaan</option>
                                    <option value="0">In afwachting</option>
                                  </select>
                            </div>

                            <div class="form-group finmodal_renewal">
                                <label for="fin_renewalinfo" class="control-label">Hernieuwing</label>
                                <p class="form-control-static" id="fin_renewalinfo">Het lidmaatschap wordt met 1 jaar verlengd bij bevestiging van ontvangst van deze betaling.</p>
                            </div>

                            <input type="hidden" id="fin_id" name="fin_id" value="0">
                            <input type="hidden" id="fin_autorenewal" name="fin_autorenewal" value="0">
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
  						<th>Verantwoordelijke</th>
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
  						<th>Verantwoordelijke</th>
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
        <h4 class="inlineh4">Instellingen: Fietsen</h4>
        <div class="container-fluid" width="100%">
          <form id="settings_bikes_form">

            <h4 class="inlineh4">Status</h4>
            <div id="settings_bikes_status" class="container-fluid" width="100%">
              <div class="col-md-3">
              </div>
              <div class="col-md-6">
                <table id="settings_bikes_status_table" class="table" width="50%">
                  <thead>
                    <tr>
                      <th scope='col'>Omschrijving</th>
                      <th scope='col'>Beschikbaar voor uitlening</th>
                      <th scope='col'>In gebruik</th>
                    </tr>
                  </thead>
                  <tbody id="settings_bikes_status_table_tbody">
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
                <button type="button" onclick="cancelBikeStatusSettings()" class="btn btn-default actbtn">Annuleren</button>
                <button type="button" onclick="saveBikeStatusSettings()" class="btn btn-primary actbtn">Opslaan</button>
              </div>

            </div>

            <hr />

            <!-- TODO delete when pdf upload complete
            <h4 class="inlineh4">Afbeeldingen</h4>
            <div id="settings_bikes_image" class="container-fluid" width="100%">
              <div class="col-md-3">
              </div>
              <div class='col-sm-6' >
                <input class="styled" type="checkbox" id="settings_bike_show_image" name="settings_bike_show_image" value="settings_bike_show_image">
                            <label for="settings_bike_show_image">
                                Activeer afbeeldingen van fietsen
                            </label>
              </div>
              <div class="col-md-3">
              </div>

              <div class="input-group col-sm-9" id="actbtns">
                <button type="button" onclick="cancelBikeImageSettings()" class="btn btn-default actbtn">Annuleren</button>
                <button type="button" onclick="saveBikeImageSettings()" class="btn btn-primary actbtn">Opslaan</button>
              </div>

            </div>

            <hr /> -->

            <h4 class="inlineh4">Eigenschappen</h4>
            <div id="settings_bikes_properties" class="container-fluid" width="100%">

              <div class="col-md-3">
              </div>
              <div class="col-md-6">
                <p style="margin-top: 5px;">Zet de zichtbaarheid van eigenschappen die je niet gebruikt af om deze velden niet te zien en een eenvoudigere interface te hebben.</p>
                <table id="settings_bikes_properties_table" class="table" width="50%">
                  <thead>
                    <tr>
                      <th scope='col'>Eigenschap</th>
                      <th scope='col'>Zichtbaar</th>
                    </tr>
                  </thead>
                  <tbody id="settings_bikes_properties_table_tbody">
                  </tbody>
                </table>
              </div>
              <div class="col-md-3">
              </div>

              <div class="input-group col-sm-9" id="actbtns">
                <button type="button" onclick="cancelBikeProperties()" class="btn btn-default actbtn">Annuleren</button>
                <button type="button" onclick="saveBikeProperties()" class="btn btn-primary actbtn">Opslaan</button>
              </div>
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
                  <th colspan="1" scope='colgroup'><button type="button" class="btn btn-default" onclick="addEmptyPriceRow()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></th>
                  <th colspan="3" scope='colgroup' class="outlined">Geldig</th>
                  <th colspan="4" scope='colgroup' class="outlined">Prijs lidmaatschap</th>
                  <th colspan="4" scope='colgroup' class="outlined">Prijs waarborg</th>
                  <th colspan="1" scope='colgroup' class="outlined"></th>
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
                  <th scope='col'>In gebruik</th>
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

      <section id="content_settings_auto_emails" class="content_section">
        <div class="container-fluid" width="100%">
          <p class="bg-info" style="padding: 10px; margin-top: 5px;">De emails in dit tabblad worden automatisch naar je leden verzonden. Wees dus voorzichtig.<br>
          Als je een cc adres hebt ingevuld onder 'Instellingen > Emails' ontvangt het cc adres elke verzonden email in cc.</p>
        </div>

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

            <div class="container-fluid" width="100%">
              <p class="bg-danger" style="padding: 10px; margin-top: 5px;" id="SendRemindersOffMsg">Herinneringsemails zijn momenteel niet geactiveerd voor dit depot. Om deze te activeren contacteer webmaster@opwielekes.be.</p>

              <p class="bg-info" style="padding: 10px; margin-top: 5px;">Deze emails zijn bedoeld om de opvolging van lidmaatschappen te vergemakkelijken.
                  <a href="#managefieldsinfo" data-toggle="collapse">Meer info over de werking.</a></p>
              <div id="managefieldsinfo" class="collapse">
                <p>Eenmaal per dag wordt een email verzonden naar de leden wiens lidmaatschap vervalt binnen de aangegeven dagen, of al het aantal dagen vervallen is.
                  <br>In deze email kan je ze vragen het lidmaatschap opnieuw te betalen.
                  <br>Dit bedrag wordt toegevoegd aan de financiën tabel, zodat je weet dat je dit geld dient te ontvangen. Als je het als ontvangen aanduidt, wordt de vervaldatum verlengd met 1 jaar (of zoals aangegeven in het tariefplan).
                  <br>Volgende codes in het onderwerp en de tekst van de email worden ingevuld door de echte waarden:</p>
                  <ul>
                   <li>{{voornaam_ouder}}, {{achternaam_ouder}}</li>
                   <li>{{voornaam_kind}}, {{achternaam_kind}}</li>
                   <li>{{IBAN_depot}}</li>
                   <li>{{bedrag_lidmaatschap}}, berekend aan de hand van het tarief en het kind nr</li>
                  </ul>
              </div>
            </div>

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
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                </select>
                  dagen
                  <select class="form-control" name="settings_membership_reminder1afterexp" id="settings_membership_reminder1afterexp">
                      <option value="0">voor</option>
                      <option value="1">na</option>
                  </select> de vervaldag. Het bedrag wordt toegvoegd aan de financiëntabel.</label>

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
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                </select>
                 dag(en)
                 <select class="form-control" name="settings_membership_reminder2afterexp" id="settings_membership_reminder2afterexp">
                     <option value="0">voor</option>
                     <option value="1">na</option>
                 </select>
                  de vervaldag, indien de betaling nog niet ontvangen is.</label>
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
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                </select>
                 dag(en)
                 <select class="form-control" name="settings_membership_reminder3afterexp" id="settings_membership_reminder3afterexp">
                     <option value="0">voor</option>
                     <option value="1">na</option>
                 </select>
                  de vervaldag, indien de betaling nog niet ontvangen is.</label>
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
              <button type="button" onclick="testEmailReminders()" class="btn btn-default actbtn" id="settings_membership_testreminders" style="margin-right:300px;">Test <i class="fa fa-info-circle" title="Deze test stuurt alle emails die vandaag naar leden verzonden zouden worden naar het cc adres."></i></button>
            </div>

          </form>
        </div>


        </section>


        <section id="content_settings_memberships" class="content_section">
         <h4 class="inlineh4">Opvolging lidmaatschap</h4>

         <div class="container-fluid" width="100%">
           <table id="kidsexpiry_table" class="table table-striped" width="100%">
             <thead>
               <tr>
                 <th>Kind</th>
                 <th>Verantwoordelijke</th>
                 <th>Email</th>
                 <th>Actief</th>
                 <th>Actieve kids</th>
                 <th>Kind Nr</th>
                 <th>Vervaldatum</th>
                 <th>Vernieuwen</th>
                 <th>Te betalen</th>
                 <th>Straat</th>
                 <th>Straatnr</th>
                 <th>Postcode</th>
                 <th>Stad</th>
               </tr>
             </thead>
             <tfoot>
               <tr>
                 <th>Kind</th>
                 <th>Verantwoordelijke</th>
                 <th>Email</th>
                 <th>Actief</th>
                 <th>Actieve kids</th>
                 <th>Kind Nr</th>
                 <th>Vervaldatum</th>
                 <th>Vernieuwen</th>
                 <th>Te betalen</th>
                 <th>Straat</th>
                 <th>Straatnr</th>
                 <th>Postcode</th>
                 <th>Stad</th>
               </tr>
             </tfoot>
           </table>
         </div>

        </section>


        <section id="content_settings_faq" class="content_section">
         <h4 class="inlineh4">FAQ</h4>
         <p>Heb je een vraag? Blader even door de FAQ, mogelijk vind je het antwoord hier. Indien niet, stel je vraag aan beatrice@detransformisten.be en je krijgt binnen de week antwoord.<p>

           <h4 class="inlineh4">De werking</h4>
           <div class="panel-group" id="aaccordion">
             <!-- FAQ 1 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#aaccordion" href="#afaq1">
                     Is er een handleiding die uitlegt hoe dit platform te gebruiken?
                   </a>
                 </h4>
               </div>
               <div id="afaq1" class="panel-collapse collapse">   <!--add class 'in' to start with panel open -->
                 <div class="panel-body">
                   Er zijn twee filmpjes die tonen hoe je het platform gebruikt. De links worden hier weldra toegevoegd.
                 </div>
               </div>
             </div>
             <!-- FAQ 2 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#aaccordion" href="#afaq2">
                     Help, mijn tabellen zijn leeg!
                   </a>
                 </h4>
               </div>
               <div id="afaq2" class="panel-collapse collapse">
                 <div class="panel-body">
                   Er wordt een backup van de gegevens bijgehouden dus de gegevens zijn zeker niet kwijt. Mogelijk ligt het probleem aan je browser. Probeer het volgende eens:
                   <ul>
                     <li>Kijk na of het adres de s bevat na http, dus http<b>s</b>://..</li>
                     <li>Log uit en terug aan, of gebruik de F5 toets om te vernieuwen</li>
                     <li>Test eens een andere browser (chrome, safari, firefox, ...)</li>
                   </ul>
                 </div>
               </div>
             </div>
             <!-- FAQ 3 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#aaccordion" href="#afaq3">
                     Waarom zie ik niet alle invulvakken bij fietsen?
                   </a>
                 </h4>
               </div>
               <div id="afaq3" class="panel-collapse collapse">
                 <div class="panel-body">
                   Omdat verschillende gebruikers verschillende info willen bijhouden, zijn er een heel aantal velden voorzien voor elk fietsje. Voor zij die een bepaalde veld niet gebruiken is het niet zinvol om deze velden te zien.
                   Daarom kan je in het tabblad 'instellingen - fietsen' onderaan aangeven welke velden je wilt dat zichtbaar zijn in jouw tabblad fietsen. Vergeet niet op te slaan.
                   Je kan nu ook foto's van fietsjes uploaden als je foto's op deze plek zichtbaar maakt.
                 </div>
               </div>
             </div>
             <!-- FAQ 4 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#aaccordion" href="#afaq4">
                     Hoe pas ik de vervaldag aan van een abonnement?
                   </a>
                 </h4>
               </div>
               <div id="afaq4" class="panel-collapse collapse">
                 <div class="panel-body">
                   <ul>
                    <li>Als je de automatische emails gebruikt, dan staat de betaling in het tabblad financien. Als je daar aangeeft dat het ontvangen is wordt de vervaldatum automatisch verlengd.</li>
                    <li>Wil je het abonnement manueel aanpassen, ga dan naar het tabblad ontleningen, kies de naam van het kind en actie aanpassing, en pas de vervaldag aan. Vergeet niet op te slaan.</li>
                  </ul>
                 </div>
               </div>
             </div>
             <!-- FAQ 5 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#aaccordion" href="#afaq5">
                     Waarvoor dient het tabblad lidmaatschappen onder tab Instellingen? Wat is het verschil met tab Financien?
                   </a>
                 </h4>
               </div>
               <div id="afaq5" class="panel-collapse collapse">
                 <div class="panel-body">
                    Eén van de doelstellingen van het platform is om het gemakkelijk te maken om de lidmaatschappen op te volgen. In het tabblad 'leden' zie je alle leden, maar zonder vervaldatum want elk kind kan een ander vervaldatum hebben. De tabellidmaatschappen toont elk kind met zijn vervaldag.
                    De kinderen die geen fietsje meer hebben zijn inactief. Aangezien je hun lidmaatschap niet meer moet opvolgen kan je deze lijnen eenvoudig wegfilteren rechtsbovenaan.
                    Wanneer het kind actief is en voorbij de vervaldatum komt er in de kolom vernieuwen een 1 te staan. Zo kan je opvolgen we je moet contacteren.
                    Als je de automatische mails gebruikt komt er ook bij te staan wie al een mail ontvangen heeft.
                    Ook het kind nummer kan je zien in de tabel, om te weten welk bedrag aangerekend moet worden.

                    Het tabblad financiën houdt bij welke transacties er gebeurd zijn (bv start, einde, ..). De bedoeling is dat je hier kan zien wie wat moet betalen en kan aanduiden wanneer iets betaald is. Je kan bovenaan selecteren om alle transacties te zien waarvan de betaling nog in afwachting is. Zo kan je opvolgen wie je nog toegoed heeft.
                 </div>
               </div>
             </div>
             <!-- FAQ 6 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#aaccordion" href="#afaq6">
                     Wat te doen indien in 1 gezin verschillende tarieven van toepassing zijn ifv verhoogde tegemoedkoming?
                   </a>
                 </h4>
               </div>
               <div id="afaq6" class="panel-collapse collapse">
                 <div class="panel-body">
                    Pas dan best het bedrag manueel aan tijdens de actie start.
                 </div>
               </div>
             </div>
           </div>  <!-- close accordion -->


           <h4 class="inlineh4">Zelf inschrijven en reglement</h4>
           <div class="panel-group" id="baccordion">
             <!-- FAQ 1 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#baccordion" href="#bfaq1">
                     Zijn we in orde met de GDPR wetgeving?
                   </a>
                 </h4>
               </div>
               <div id="bfaq1" class="panel-collapse collapse">
                 <div class="panel-body">
                   Ja we zijn in orde met de GDPR wetgeving:
                   <ul>
                     <li>Enkel de persoonsgegevens noodzakelijk voor de werking worden opgevraagd, deze zijn enkel zichtbaar voor de beheerders van de depot en de webmaster, en ze worden voor geen andere doeleinden gebruikt.</li>
                     <li>Een lid kan gearchiveerd worden uit de database, in dat geval worden de persoongegevens verwijderd. De uitleenhistoriek wordt wel bewaard voor de statistieken.</li>
                     <li>Bij het zelf inschrijven moet het lid akkoord gaan met "Ik teken dat ik Op Wielekes toestemming geef me te contacteren. Meer info over ons privacybeleid in het intern reglement."</li>
                     <li>Het intern reglement dient de volgende zin (of gelijkaardig) te bevatten: "Je geeft Op Wielekes toestemming om de persoonsgegevens die je in dit contract invult, te gebruiken om je lidgeld te berekenen en om je te contacteren over Op Wielekes. We bewaren je gegevens op een veilige manier en geven ze niet door aan derde partijen. Heb je een vraag over ons privacybeleid, wil je je persoonlijke gegevens wijzigen of verwijderen, dan kun je contact opnemen met [verantwoordelijke depot]."</li>
                  </ul>
                 </div>
               </div>
             </div>
             <!-- FAQ 2 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#baccordion" href="#bfaq2">
                     Kan een ouder op latere basis via zelf inschrijven een 2e kind toevoegen?
                   </a>
                 </h4>
               </div>
               <div id="bfaq2" class="panel-collapse collapse">
                 <div class="panel-body">
                   Neen dit is niet mogelijk. Enkel op het moment dat de ouder het formulier invult kan ie zelf kinderen toevoegen. Je kan vragen om bij inschrijving alle kinderen al op te geven, ook al hebben ze nog geen fietsje nodig.
                   Als er later kinderen bij moeten ingeschreven worden, moet je dit zelf doen via het tabblad 'leden', bewerk, druk op het plus bij kinderen en vergeet niet op te slaan na het invullen van de gegevens.
                 </div>
               </div>
             </div>
             <!-- FAQ 3 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#baccordion" href="#bfaq3">
                     Kan ik ons eigen intern reglement laten zien?
                   </a>
                 </h4>
               </div>
               <div id="bfaq3" class="panel-collapse collapse">
                 <div class="panel-body">
                   Deze functionaliteit wordt eerstdaags toegevoegd. In afwachting, kan je je eigen intern reglement opsturen en zorgen wij dat het getoond wordt.
                 </div>
               </div>
             </div>
           </div> <!-- close accordion -->

           <h4 class="inlineh4">Emails</h4>
           <!-- FAQ 1 -->
           <div class="panel-group" id="caccordion">
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#caccordion" href="#cfaq1">
                     Hoe weet ik welke emails verzonden worden?
                   </a>
                 </h4>
               </div>
               <div id="cfaq1" class="panel-collapse collapse">
                 <div class="panel-body">
                   Als je een emailadres invult onder cc adres (tabblad 'instellingen - emails') krijgt dit adres een kopie van elke verzonden email. Er zijn drie types emails:
                   <ol>
                     <li>De emails die verzonden worden bij het uitvoeren van een transactie in de ontleningen tab. Dit is telkens 1 email die je kan uitvinken als je niet wil dat ie verzonden wordt.</li>
                     <li>De bevestiginsemail bij zelf inschrijving. Als je wil dat het lid deze bevestiging krijgt, kan je deze email aanvinken bovenaan in het tabblad 'instellingen - automatische emails'.</li>
                     <li>De automatische emails die verzonden worden zonder interactie, om mensen te verwittigen dat hun lidmaatschap vervalt. Deze staan standaard gedeactiveerd. Voor meer info, zie de vraag over automatische emails.</li>
                  </ol>
                 </div>
               </div>
             </div>
             <!-- FAQ 2 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#caccordion" href="#cfaq2">
                     Wat gebeurt er als een emailadres onjuist is?
                   </a>
                 </h4>
               </div>
               <div id="cfaq2" class="panel-collapse collapse">
                 <div class="panel-body">
                   De webmaster ontvangt dan een email dat het emailadres onjuist is. De webmaster zal je deze info bezorgen. Je zal dan wellicht manueel moeten opvolgen en het emailadres corrigeren.
                 </div>
               </div>
             </div>
             <!-- FAQ 3 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#caccordion" href="#cfaq3">
                    Wat zijn de automatische emails en hoe werken deze?
                   </a>
                 </h4>
               </div>
               <div id="cfaq3" class="panel-collapse collapse">
                 <div class="panel-body">
                   Eén van de doelstellingen van het platform is om het gemakkelijk te maken om de lidmaatschappen op te volgen. Hiervoor kan het platform automatisch emails uitsturen op gepaste momenten om mensen te vragen om te betalen of langs te komen. Bvb een maand voor de vervaldag, op de vervaldag, en een week na de vervaldag.
                   Elke dag kijkt het platform wie die dag een mail moet ontvangen en stuurt die uit. Tegelijk wordt een rij aangemaakt in financiën tabel waarbij aangegeven wordt dat het lid hetlidmaatschap nog moet betalen, zodat je duidelijk kan zien van wie je nog geld verwacht. Als je ingeeft dat dit bedrag ontvangen is, dan wordt de vervaldatum van het lidmaatschap van dat lid aangepast.
                   <br>
                   Om te vermijden dat deze automatische emails per ongeluk verzonden worden, staan deze uit, en moet je de webmaster contacteren om ze aan te zetten.
                   De momenten om de mails te versturen en de mails zelf kan je aanpassen in het tabblad 'instellingen - automatische emails'.  Daar staat ook de info over welke gegevens je automatisch kan laten invullen in je mail, bv {{voornaam_kind}} of het te betalen bedrag {{bedrag_lidmaatschap}}.
                   Als je een kopie van de verzonden emails wil ontvangen vul dan het cc adres in het tabblad 'instellingen - emails'.
                 </div>
               </div>
             </div>
             <!-- FAQ 4 -->
             <div class="panel panel-default">
               <div class="panel-heading">
                 <h4 class="panel-title">
                   <a data-toggle="collapse" data-parent="#caccordion" href="#cfaq4">
                     Kan ik vanuit het systeem een éénmalige mail aan alle leden sturen, bv wegens wijziging openingsuren?
                   </a>
                 </h4>
               </div>
               <div id="cfaq4" class="panel-collapse collapse">
                 <div class="panel-body">
                   Dit ondersteunt het systeem niet. Je kan in het tabblad 'leden' wel alle leden exporteren en de emailadressen selecteren, om op de gewone manier een email te verzenden.
                 </div>
               </div>
             </div>
           </div><!-- close accordion -->

         </section>


        <section id="content_transactionhistory" class="content_section">
         <h4 class="inlineh4">Transacties</h4>

         <div class="container-fluid" width="100%">
           <table id="transactions_table" class="table table-striped" width="100%">
             <thead>
               <tr>
                 <th>Datum</th>
                 <th>Verantwoordelijke</th>
                 <th>Kind</th>
                 <th>Actie</th>
                 <th>Fiets IN</th>
                 <th>Fiets UIT</th>
               </tr>
             </thead>
             <tfoot>
               <tr>
                 <th>Datum</th>
                 <th>Verantwoordelijke</th>
                 <th>Kind</th>
                 <th>Actie</th>
                 <th>Fiets IN</th>
                 <th>Fiets UIT</th>
               </tr>
             </tfoot>
           </table>
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
<!-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/datatables.min.js"></script> -->
<script type="text/javascript" src="  https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/datatables.min.js"></script>
<script type="text/javascript" src="libs/datatables/ellipsis.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js" integrity="sha512-BxJRFdTKV85fhFUw+olPr0B+UEzk8FTLxRB7dAdhoQ7SXmwMECj1I4BlSmZfeoSfy0OVA8xFLTDyObu3Nv1FoQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="libs/quill/1.3.6/quill.min.js"></script>
<script src="libs/quill-emoji/0.2.0/dist/quill-emoji.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://apis.google.com/js/api.js"></script>


<script>

</script>

<!-- own js -->
<script src="js/globalvars.js"></script>
<script src="js/globalfunctions.js"></script>
<script src="js/finances.js"></script>
<script src="js/settings.js"></script>
<script src="js/members.js"></script>
<script src="js/bikes.js"></script>
<script src="js/transactions.js"></script>
<script src="js/uploads.js"></script>
<script src="js/main.js"></script>


</body>
</html>
