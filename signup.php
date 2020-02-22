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


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="../libs/toastr/2.1.3/toastr.min.css" rel="stylesheet"/>
    <link href="../libs/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/signup.css" rel="stylesheet">
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
                          <div class="form-group">
                                <label class="control-label lb-md">Ouder<span class="req"> * </span></label>
                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <input class="form-control"  type="text" name="parentfirstname" id="parentfirstname" placeholder="Voornaam" required />
                                  </div>
                                  <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="parentlastname" id="parentlastname" placeholder="Familienaam"  required />
                                  </div>
                                </div>

                                <label class="control-label lb-md">Adres<span class="req"> * </span></label>

                                <div class="row">
                                  <div class="form-group col-md-8">
                                    <input class="form-control" type="text" name="parentstreet" id="parentstreet" placeholder="Straat" required />
                                  </div>
                                  <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="parentstreetnr" id="parentstreetnr" placeholder="Nummer" required />
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <input class="form-control col-md-5" type="text" name="parentpostal" id="parentpostal" placeholder="Postcode" required />
                                  </div>
                                  <div class="col-md-8">
                                    <input class="form-control col-md-8" type="text" name="parenttown" id="parenttown" placeholder="Stad" required />
                                  </div>
                                </div>
                            </div>


                          <div class="form-group">
                              <div class="row">
                                <label for="email" class="col-md-6">E-mailadres<span class="req"> * </span></label>
                                <label for="phonenumber" class="col-md-6">Telefoonnummer<span class="req"> * </span></label>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="parentemail" id="parentemail" placeholder="naam@email.com" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="parentphone" id="parentphone" placeholder="0472263099" required />
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
                                <div class="form-group col-md-4">
                                    <div class='input-group' id='kid1birthdatepicker'>
                                      <input class="form-control" type="text" name="kid1birthdate" id="kid1birthdate"  />
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                    </div>
                                </div>
                              </div>

                              <div class="row">
                                <label class="col-md-8">Kind 2</label>
                                <label class="col-md-4">Geboortedatum</label>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid2firstname" id="kid2firstname" placeholder="Voornaam" />
                                </div>
                                <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="kid2lastname" id="kid2lastname" placeholder="Familienaam"  />
                                </div>
                                <div class="form-group col-md-4">
                                    <div class='input-group' id='kid2birthdatepicker'>
                                      <input class="form-control" type="text" name="kid2birthdate" id="kid2birthdate" />
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                    </div>
                                </div>
                              </div>

                              <div class="form-group">
                              <?php
                              $date_entered = date('dd-mm-Y');
                              echo '<input type="hidden" value="<' . $date_entered . '" name="dateregistered">';
                              echo '<div class="row">';
                              echo '<div class="col-md-12">';
                              echo '<input type="checkbox" required name="terms1" id="field_terms1"> <label for="terms1"> Ik teken dat ik op wielekes toestemming geef me te contacteren.<span class="req"> * </span></label>';
                              echo '</div>';
                              echo '</div>';
                              echo '<div class="row">';
                              echo '<div class="col-md-12">';
                              echo '<input type="checkbox" required name="terms" id="field_terms">  <label for="terms"> Ik teken het <a href="terms.php" title="Je kan het intern reglement lezen door op deze link te klikken">intern reglement</a>.<span class="req"> * </span></label>';
                              echo '</div>';
                              echo '</div>';
                              //echo '<input type="checkbox" required name="terms" onchange="this.setCustomValidity(validity.valueMissing ? 'Ik ga akkoord met het intern reglement' : '');" id="field_terms">   <label for="terms">Ik ga akkoord met het <a href="terms.php" title="Je kan de algemene voorwaarden lezen door op deze link te klikken">intern reglement</a>.<span class="req"> * </span></label>';
                              ?>
                              </div>
                          </div>

                          <div class="form-group">
                              <input class="btn btn-success" type="submit" onclick="registerMember()" value="Inschrijven">
                          </div>


                    </form>

                </div>

      </div>

    <script src="../libs/moment/2.16.0/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="../libs/toastr/2.1.3/toastr.min.js"></script>
    <script src="../libs/datetimepicker/bootstrap-datetimepicker.min.js"></script>

    <script src="../js/globalvars.js"></script>
    <script src="../js/signup.js"></script>

  </body>
</html>
