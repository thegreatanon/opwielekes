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
    <link href="css/login.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="POST" action="" >
        <?php
      	  echo '<h2 class="form-signin-heading">Op wielekes ' . $_SESSION["urlaccount"]["AccountName"] . '</h2>';
          echo '<input type="password" name="password" class="form-control" placeholder="Wachtwoord" required autofocus />';
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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
