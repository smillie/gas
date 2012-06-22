<?php require 'header.php'; ?>


<div class="container">
<div class="hero-unit span6 offset2" style="text-align: center">
  <h1>GeekSoc Account System</h1>
  <form class="form-horizontal" action="login.php" method="post">
	  <fieldset>
	    <legend>Login</legend>
			<div class="control-group">
	      <label class="control-label" for="uid">Username</label>
	      <div class="controls">
	        <input type="text" class="input-xlarge" id="uid" name="uid">
	      </div>
	    </div>
			<div class="control-group">
	      <label class="control-label" for="password">Password</label>
	      <div class="controls">
        <input type="password" class="input-xlarge" id="password" name="password">
	      </div>
	    </div>
			<div class="">
				<button type="button" class="btn ">Create Account</button>
				<button type="submit" class="btn btn-primary">Login</button>
			</div>
	  </fieldset>
	</form>
</div>
</div>

<?php require 'footer.php'; ?>