<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="icon" href="/images/favicon.png">

    <title>Op Wielekes</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
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
                                    <input class="form-control"  type="text" name="parentfirstname" id="parentfirstname" placeholder="Voornaam" onkeyup = "Validate(this)" required />
                                  </div>
                                  <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="parentlastname" id="parentlastname" placeholder="Familienaam" onkeyup = "Validate(this)" required />
                                  </div>
                                </div>

                                <label class="control-label lb-md">Adres<span class="req"> * </span></label>

                                <div class="row">
                                  <div class="form-group col-md-8">
                                    <input class="form-control" type="text" name="parentstreet" id="parentstreet" placeholder="Straat" onkeyup = "Validate(this)" required />
                                  </div>
                                  <div class="form-group col-md-4">
                                    <input class="form-control" type="text" name="parentstreetnr" id="parentstreetnr" placeholder="Nummer" onkeyup = "Validate(this)" required />
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <input class="form-control col-md-5" type="text" name="parentpostal" id="parentpostal" placeholder="Postcode" onkeyup = "Validate(this)" required />
                                  </div>
                                  <div class="col-md-8">
                                    <input class="form-control col-md-8" type="text" name="parenttown" id="parenttown" placeholder="Stad" onkeyup = "Validate(this)" required />
                                  </div>
                                </div>
                            </div>


                          <div class="form-group">
                              <label for="email">E-mailadres<span class="req"> * </span></label>
                                  <input class="form-control" required type="text" name="parentemail" id="parentemail"  onchange="email_validate(this.value);" />
                                      <div class="status" id="status"></div>
                          </div>

                          <div class="form-group">
                          <label for="phonenumber">Telefoonnummer<span class="req"> * </span></label>
                                  <input required type="text" name="parentphone" id="parentphone" class="form-control phone" maxlength="28" onkeyup="validatephone(this);" placeholder=""/>
                          </div>

                          <div class="form-group">
                              <label for="username">Kind 1</label>
                              <div class="row">
                                <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="kid1firstname" id="kid1firstname" placeholder="Voornaam" onkeyup = "Validate(this)" />
                                </div>
                                <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="kid1lastname" id="kid1lastname" placeholder="Familienaam" onkeyup = "Validate(this)" />
                                </div>
                              </div>

                              <label for="username">Kind 2</label>
                              <div class="row">
                                <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="kid2firstname" id="kid2firstname" placeholder="Voornaam" onkeyup = "Validate(this)" />
                                </div>
                                <div class="form-group col-md-6">
                                    <input class="form-control" type="text" name="kid2lastname" id="kid2lastname" placeholder="Familienaam" onkeyup = "Validate(this)"  />
                                </div>
                              </div>

                              <?php
                              $date_entered = date('dd-mm-Y');
                              echo '<input type="hidden" value="<' . $date_entered . '" name="dateregistered">';
                              ?>


                              <input type="checkbox" required name="terms" onchange="this.setCustomValidity(validity.valueMissing ? 'Ik ga akkoord met de Algemene Voorwaarden' : '');" id="field_terms">   <label for="terms">Ik ga akkoord met de <a href="terms.php" title="Je kan de algemene voorwaarden lezen door op deze link te klikken">algemene voorwaarden</a>.<span class="req"> * </span></label>
                          </div>

                          <div class="form-group">
                              <input class="btn btn-success" type="submit" onclick="registerMember()" value="Inschrijven">
                          </div>


                    </form>

                </div>

      </div>

    <script src="../js/globalvars.js"></script>
    <script src="../js/signup.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
