<?php require 'ldapconnect.php'; ?>
<?php
  if(isset($_SESSION['user'])) {
    header( 'Location: details.php' );
  }
  
  $pageTitle = "- Join";
?>
<?php require 'header.php'; ?>


<div class="container">
<div class=" span6 offset2" style="text-align: center">
  <!-- <h1 style="font-size:40px;">Join GeekSoc</h1> -->
  <form class="form-horizontal well" id="form" action="register.php" method="post">
    <fieldset>
      <legend>Join GeekSoc</legend>

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
          <p>By submitting this from you agree to abide by the Terms of Service.</p>
         <a href="index.php" class="btn">Cancel</a>
        <button type="submit" name="register" class="btn btn-primary">Register</button>
      </div>
    </fieldset>
  </form>
</div>

<?php require 'footer.php'; ?>
