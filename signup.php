<!DOCTYPE html>

<?php
// database access
require_once(__DIR__ . "/api/pdoconnect.php");
require_once(__DIR__ . "/api/Services/SettingsService.php");
$preferences = SettingsService::getPreferences()
?>

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


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="../libs/toastr/2.1.3/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link href="../libs/select2/4.0.3/dist/css/select2.css" rel="stylesheet" type="text/css"/>
    <link href="../libs/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>

    <link href="../css/signup.css" rel="stylesheet" type="text/css"/>
  </head>

  <body>
        <div class="container">

        <!-- inspiration: https://bootsnipp.com/snippets/8ANqZ -->

              <div class="col-md-12">
                  <form class="form-signup">
                    <?php
                      if (!isset($_SESSION["urlaccount"])) {
                        echo '<h1 class="h3 mb-3 font-weight-normal form-signup-heading">Geen geldige link.</h1>';
                        exit;
                      } else {
                        echo '<h1 class="h3 mb-3 font-weight-normal">Inschrijven Op Wielekes ' . $_SESSION["urlaccount"]["AccountName"] . '</h1>';
                      }
                    ?>
                      <div id="showinput">
                          <div class="form-group">
                                <label class="control-label lb-md">Ouder<span class="req"> * </span></label>
                                <div class="row">
                                  <div class="form-group col-md-6" id="parentfirstnamediv">
                                    <input class="form-control"  type="text" name="parentfirstname" id="parentfirstname" placeholder="Voornaam" />
                                  </div>
                                  <div class="form-group col-md-6" id="parentlastnamediv">
                                    <input class="form-control" type="text" name="parentlastname" id="parentlastname" placeholder="Familienaam" />
                                  </div>
                                </div>

                                <label class="control-label lb-md">Adres<span class="req"> * </span></label>

                                <div class="row">
                                  <div class="form-group col-md-8" id="parentstreetdiv">
                                    <input class="form-control" type="text" name="parentstreet" id="parentstreet" placeholder="Straat" />
                                  </div>
                                  <div class="form-group col-md-4" id="parentstreetnrdiv">
                                    <input class="form-control" type="text" name="parentstreetnr" id="parentstreetnr" placeholder="Nummer"/>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <input disabled class="form-control" type="text" name="parentpostal" id="parentpostal" />
                                  </div>
                                  <div class="col-md-8" id="parenttowndiv">
                                    <select style="width : 100%;" class="form-control" id="parenttown" name="parenttown">
                                    </select>
                                  </div>

                                </div>
                            </div>


                          <div class="form-group">
                              <div class="row">
                                <label for="email" class="col-md-6">E-mailadres<span class="req"> * </span></label>
                                <label for="phonenumber" class="col-md-6">Telefoonnr (bvb 0475123456)<span class="req"> * </span></label>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6" id="parentemaildiv">
                                    <input class="form-control" type="text" name="parentemail" id="parentemail" placeholder="naam@email.com" />
                                </div>
                                <div class="form-group col-md-6" id="parentphonediv">
                                    <input class="form-control" type="text" name="parentphone" id="parentphone" placeholder="0475123456"  />
                                </div>
                              </div>

                              <div class="row">
                                <label class="col-md-8">Kind 1</label>
                                <label class="col-md-4">Geboortedatum</label>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid1firstname" id="kid1firstname" placeholder="Voornaam"  />
                                </div>
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid1lastname" id="kid1lastname" placeholder="Familienaam" />
                                </div>
                                <div class="form-group col-md-3">
                                    <div class='input-group' id='kid1birthdatepicker'>
                                      <input class="form-control" type="text" name="kid1birthdate" id="kid1birthdate"  />
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="button" class="btn btn-default" onclick="showSignUpKid2()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                </div>
                              </div>

                              <div class="row kid2">
                                <label class="col-md-8">Kind 2</label>
                                <label class="col-md-4">Geboortedatum</label>
                              </div>
                              <div class="row kid2">
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid2firstname" id="kid2firstname" placeholder="Voornaam" />
                                </div>
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid2lastname" id="kid2lastname" placeholder="Familienaam"  />
                                </div>
                                <div class="form-group col-md-3">
                                    <div class='input-group' id='kid2birthdatepicker'>
                                      <input class="form-control" type="text" name="kid2birthdate" id="kid2birthdate" />
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="button" class="btn btn-default" onclick="showSignUpKid3()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                </div>
                              </div>

                              <div class="row kid3">
                                <label class="col-md-8">Kind 3</label>
                                <label class="col-md-4">Geboortedatum</label>
                              </div>
                              <div class="row kid3">
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid3firstname" id="kid3firstname" placeholder="Voornaam" />
                                </div>
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid3lastname" id="kid3lastname" placeholder="Familienaam"  />
                                </div>
                                <div class="form-group col-md-3">
                                    <div class='input-group' id='kid3birthdatepicker'>
                                      <input class="form-control" type="text" name="kid3birthdate" id="kid3birthdate" />
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="button" class="btn btn-default" onclick="showSignUpKid4()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                </div>
                              </div>

                              <div class="row kid4">
                                <label class="col-md-8">Kind 4</label>
                                <label class="col-md-4">Geboortedatum</label>
                              </div>
                              <div class="row kid4">
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid4firstname" id="kid4firstname" placeholder="Voornaam" />
                                </div>
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid4lastname" id="kid4lastname" placeholder="Familienaam"  />
                                </div>
                                <div class="form-group col-md-3">
                                    <div class='input-group' id='kid4birthdatepicker'>
                                      <input class="form-control" type="text" name="kid4birthdate" id="kid4birthdate" />
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                </div>
                              </div>

                              <div>
                              <?php
                              $date_entered = date('dd-mm-Y');
                              echo '<input type="hidden" value="<' . $date_entered . '" name="dateregistered">';
                              echo '<input type="hidden" id="emailsend" name="emailsend" value ="' . $preferences['SignupSend'] . '">';
                              echo '<input type="hidden" id="emailmessage" name="emailmessage" value ="' . htmlspecialchars($preferences['SignupMessage']) . '">';
                              echo '<input type="hidden" id="emailsubject" name="emailsubject" value="' . $preferences['SignupSubject'] . '">';
                              echo '<input type="hidden" id="emailcc" name="emailcc" value="' . $preferences['EmailCC'] . '">';
                              echo '<input type="hidden" id="emailreplyto" name="remailreplyto" value="' . $preferences['EmailReplyTo'] . '">';
                              echo '<input type="hidden" id="emailreplytoname" name="emailreplytoname" value="' . $preferences['EmailReplyToName'] . '">';
                              echo '<input type="hidden" id="emailsender" name="emailsender" value="' . $preferences['SenderName'] . '">';
                              echo '<input type="hidden" id="membershipid" name="membershipid" value="' . $preferences['DefaultMembership'] . '">';
                              echo '<div class="row">';
                              echo '<div class="col-md-12" form-group" id="signcontactdiv">';
                              echo '<input type="checkbox" name="signcontact" id="signcontact"> <label class="control-label" for="signcontact"> Ik teken dat ik Op Wielekes toestemming geef me te contacteren.<span class="req"> * </span></label>';
                              echo '</div>';
                              echo '</div>';
                              echo '<div class="row">';
                              echo '<div class="col-md-12 form-group" id="signrulesdiv">';
                              if ( $_SESSION["urlaccount"]["AccountCode"] == "has" ) {
                                  $termsfile = "https://admin.opwielekes.be/pdf/ReglementHasselt.pdf";
                              } elseif ( $_SESSION["urlaccount"]["AccountCode"] == "flo" ) {
                                  $termsfile = "https://admin.opwielekes.be/pdf/ReglementFlora.pdf";
                              } elseif ( $_SESSION["urlaccount"]["AccountCode"] == "zem" ) {
                                  $termsfile = "https://admin.opwielekes.be/pdf/ReglementZemst.pdf";
                              } else {
                                  $termsfile = "https://admin.opwielekes.be/pdf/ReglementOpwielekes.pdf";
                              }
                              echo '<input type="checkbox" name="signrules" id="signrules">  <label class="control-label" for="signrules"> Ik teken het <a data-fancybox data-type="iframe" href="' . $termsfile . '" title="Je kan het intern reglement lezen door op deze link te klikken">intern reglement</a>.<span class="req"> * </span></label>';
                              echo '</div>';
                              echo '</div>';
                              //echo '<input type="checkbox" required name="terms" onchange="this.setCustomValidity(validity.valueMissing ? 'Ik ga akkoord met het intern reglement' : '');" id="field_terms">   <label for="terms">Ik ga akkoord met het <a href="terms.php" title="Je kan de algemene voorwaarden lezen door op deze link te klikken">intern reglement</a>.<span class="req"> * </span></label>';
                              ?>
                              </div>
                          </div>

                          <div class="form-group">
                              <input class="btn btn-success actbtn" type="button" onclick="verifyMember()" value="Inschrijven">
                          </div>

                        </div>

                        <div id="showsummary" style="display: none;">
                          <p style="margin-top:30px;margin-bottom:20px;"> Je hebt volgende gegevens ingegeven:</p>

                          <div class="col-sm-12">
                            <label class="col-sm-2 control-label">Naam</label>
              							<div class="col-sm-10">
              								<p id="sumname"> </p>
              							</div>
                          </div>

                          <div class="col-sm-12">
                            <label class="col-sm-2 control-label">Adres</label>
                            <div class="col-sm-10">
                              <p id="sumaddress"> </p>
                            </div>
                          </div>

                          <div class="col-sm-12">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                              <p id="sumemail"> </p>
                            </div>
                          </div>

                          <div class="col-sm-12">
                            <label class="col-sm-2 control-label">Tel</label>
                            <div class="col-sm-10">
                              <p id="sumphone"> </p>
                            </div>
                          </div>

                          <div class="col-sm-12">
                            <label class="col-sm-2 control-label">Kinderen</label>
                            <div class="col-sm-10">
                              <p id="sumkids"> </p>
                            </div>
                          </div>

          								<div class="col-sm-12 checkbox">
                            <div style="padding-left:15px">
          										<label><input type="checkbox" checked disabled> Ik teken dat ik Op Wielekes toestemming geef me te contacteren.</label>
                            </div>
                          </div>

          								<div class="col-sm-12 checkbox">
          	                <div style="padding-left:15px">
          										<label><input type="checkbox" checked disabled> Ik teken het <a data-fancybox data-type="iframe" href="https://admin.opwielekes.be/pdf/ReglementOpwielekes.pdf" title="Je kan het intern reglement lezen door op deze link te klikken">intern reglement</a>.</label>
                            </div>
          								</div>

                          <div class="form-group" >
                            	<div class="col-sm-12" style="margin-top:40px;">
                              </div>
                          </div>

                          <div class="form-group">

                                <input class="btn btn-primary" type="button" onclick="editMember()" value="Wijzigen">
                                <input class="btn btn-success actbtn" type="button" onclick="registerMember()" value="Bevestigen">

                          </div>
                        </div>

                        <div id="showsuccess" style="display: none;">
                          <p style="margin-top:30px;margin-bottom:20px;"> Je bent ingeschreven.</p>
                          <!--<p> Er werd een bevestigingsemail gestuurd. </p>-->
                          <div class="form-group">
                              <input class="btn btn-success actbtn" type="button" onclick="resetSignupForm()" value="Nieuwe inschrijving">
                          </div>
                        </div>
                    </form>

                </div>

      </div>

    <script src="../libs/moment/2.16.0/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="../libs/toastr/2.1.3/toastr.min.js"></script>
    <script src="../libs/select2/4.0.3/dist/js/select2.min.js"></script>
    <script src="../libs/datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <script src="../js/globalfunctions.js"></script>
    <script src="../js/signup.js"></script>

  </body>
</html>
