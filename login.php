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
    <link href="css/login.css" rel="stylesheet">
  </head>

  <body>
  <div class="wrapper">
    <div class="container">

      <form class="form-signin" method="POST" action="" >
        <?php
          echo '<img class="mb-4" src="images/opwielekes.jpg" alt="" width="122" height="140">';
      	  echo '<h1 class="h3 mb-3 font-weight-normal">' . $_SESSION["urlaccount"]["AccountName"] . '</h1>';
          echo '<label for="inputPassword" class="sr-only">Wachtwoord</label>';
          echo '<input type="password" name="password"  class="form-control" placeholder="Wachtwoord" required autofocus>';
          echo '<input type="hidden" name="loginID" class="form-control" value="' . $_SESSION["urlaccount"]["AccountID"] . '" />';
          echo '<button class="btn btn-lg btn-primary btn-block" type="submit">Aanmelden</button>';
          //echo '<p class="fullsigninlink"><a href="https://admin.opwielekes.be/index.php">Ander depot</a></p>';

          if (isset($_SESSION["error"])) {
              echo '<p class="bg-danger" style="padding: 10px; margin-top: 5px;">' . $_SESSION["error"] . '</p>';
              unset($_SESSION["error"]);
          }
        ?>
      </form>

    </div>

    <div class="push"></div>
  </div>
  <div class="footer">
          <span class="text-muted">Problemen of suggesties: <a href="mailto:webmaster@opwielekes.be?Subject=Opwielekes">webmaster</a>.</span>
  </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
