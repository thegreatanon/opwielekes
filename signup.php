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
    <link href="../css/login.css" rel="stylesheet">
  </head>

  <body>


      <div class="container">

        <!-- inspiration: https://bootsnipp.com/snippets/8ANqZ -->
        <div class="row">
              <div class="col-md-6">
                  <form class="form-signup" method="POST" action="" id="fileForm" role="form">
                    <?php
                      if (!isset($_SESSION["urlaccount"])) {
                        echo '<h2 class="form-signin-heading">Geen geldige link.</h2>';
                        exit;
                      } else {
                        echo '<h2 class="form-signup-heading">Inschrijven Op Wielekes ' . $_SESSION["urlaccount"]["AccountName"] . '</h2>';
                      }
                    ?>
                          <div class="form-group">
                              <label for="firstname">Voornaam ouder<span class="req">* </span>: </label>
                                  <input class="form-control" type="text" name="firstname" id = "txt" onkeyup = "Validate(this)" required />
                                      <div id="errFirst"></div>
                          </div>

                          <div class="form-group">
                              <label for="lastname">Achternaam ouder<span class="req">* </span>: </label>
                                  <input class="form-control" type="text" name="lastname" id = "txt" onkeyup = "Validate(this)" placeholder="hyphen or single quote OK" required />
                                      <div id="errLast"></div>
                          </div>

                          <div class="form-group">
                              <label for="email">E-mailadres<span class="req">* </span>: </label>
                                  <input class="form-control" required type="text" name="email" id = "email"  onchange="email_validate(this.value);" />
                                      <div class="status" id="status"></div>
                          </div>

                          <div class="form-group">
                          <label for="phonenumber">Telefoonnummer: </label>
                                  <input required type="text" name="phonenumber" id="phone" class="form-control phone" maxlength="28" onkeyup="validatephone(this);" placeholder="not used for marketing"/>
                          </div>

                          <div class="form-group">
                              <label for="username">User name:</label>
                                  <input class="form-control" type="text" name="username" id = "txt" onkeyup = "Validate(this)" placeholder="minimum 6 letters" required />
                                      <div id="errLast"></div>
                          </div>

                          <div class="form-group">
                              <label for="password">Password: </label>
                                  <input required name="password" type="password" class="form-control inputpass" minlength="4" maxlength="16"  id="pass1" /> </p>

                              <label for="password">Password Confirm: </label>
                                  <input required name="password" type="password" class="form-control inputpass" minlength="4" maxlength="16" placeholder="Enter again to validate"  id="pass2" onkeyup="checkPass(); return false;" />
                                      <span id="confirmMessage" class="confirmMessage"></span>
                          </div>

                          <div class="form-group">

                              <?php //$date_entered = date('m/d/Y H:i:s'); ?>
                              <input type="hidden" value="<?php //echo $date_entered; ?>" name="dateregistered">
                              <input type="hidden" value="0" name="activate" />
                              <hr>

                              <input type="checkbox" required name="terms" onchange="this.setCustomValidity(validity.valueMissing ? 'Ik ga akkoord met de Algemene Voorwaarden' : '');" id="field_terms">   <label for="terms">Ik ga akkoord met de <a href="terms.php" title="Je kan de algemene voorwaarden lezen door op deze link te klikken">algmene voorwaarden</a>.</label>
                          </div>

                          <div class="form-group">
                              <input class="btn btn-success" type="submit" name="submit_reg" value="Inschrijven">
                          </div>


                    </form>

                </div>
          </div>
      </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
