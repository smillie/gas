<?php require 'ldapconnect.php'; ?>
<?php
  if(isset($_SESSION['user'])) {
    header( 'Location: details.php' );
  }
  
  $pageTitle = "- Join";
  
  if (isset($_POST['register']) && strlen($_POST['firstname'])>2 &&  strlen($_POST['lastname'])>2 ) {
      $user = new User();
      $user -> setName($_POST['firstname'], $_POST['lastname']);
      $user -> setStudentNumber($_POST['studentnumber'];
      $user -> setEmail($_POST['email']);
      $uid = generateUsername($first, $last);
      
      $first = $user -> firstName();
      $last = $user -> lastName();
      $email = $user -> email();
      $stuno = $user -> studentNumber();
      
      if (count($user -> validate()) == 0) {
      
      $mysqli = new mysqli($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }
      
      /* Create the prepared statement */
      if ($stmt = $mysqli->prepare("INSERT INTO newusers (firstname, lastname, username, studentnumber, email) values (?, ?, ?, ?, ?)")) {

              /* Bind our params */
              $stmt->bind_param('sssis', $first, $last, $uid, $stuno, $email);

              /* Execute the prepared Statement */
              $stmt->execute();

              /* Close the statement */
              $stmt->close(); 
      }
      else {
              /* Error */
      }
      
      
      ircNotify("$first $last has joined GeekSoc and is awaiting account activation.");
      
      $adminEmail = <<<EOT
$first $last has joined GeekSoc. Please verify they have paid then create their account through GAS.

Username: $uid
First name: $first
Last name: $last
Student Number: $stuno
Email: $email

EOT;
      mailNotify("gsag@geeksoc.org", "[GeekSoc] New member registered", $adminEmail);
      
  }
}
?>
<?php require 'header.php'; ?>


<div class="container">
    <div class="row">
        <div class="span6 offset3" style="text-align: center">
          <!-- <h1 style="font-size:40px;">Join GeekSoc</h1> -->
          <form class="form-horizontal well" id="form" action="register.php" method="post">
            <fieldset>
              <legend>Join GeekSoc</legend>
              <?php if (isset($_POST['register'])) : ?>
                  <br />
                  <p>Thank you for joining GeekSoc <?php echo $first; ?>. An administrator will activate your account as soon as they confirm that you have paid the membership fee. You will receive an email when this happens.</p>
              <?php else : ?>
                  <div class="control-group">
                    <label class="control-label" for="firstname">First Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="firstname" id="firstname">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="lastname">Last Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="lastname" id="lastname">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="studentNumber">Student Number</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge digits" maxlength="9" name="studentnumber" id="studentnumber" >
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge required email" name="email" id="email" >
                    </div>
                  </div>

                  <div class="">
                      <br />
                      <p>By submitting this form you agree to abide by the <a href="http://geeksoc.org/wp-content/uploads/2011/12/tos.pdf">Terms of Service</a>.</p>
                     <a href="index.php" class="btn">Cancel</a>
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                  </div>
              <?php endif ?>
            </fieldset>
          </form>
        </div>
    </div>

<?php require 'footer.php'; ?>
