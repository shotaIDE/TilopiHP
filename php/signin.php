<?php

// Already submited ?
//var_dump($_REQUEST);
//var_dump($_POST);
$SigninSucceed = false;
$IsCorrectIDPass = true;

if (isset($_POST['signin_userid']) && isset($_POST['signin_password'])) {
    // Escape dangerous string from input(POST)
    $SigninUserID = $MyDB->Escape($_POST['signin_userid']);
    $SigninPassword = $_POST['signin_password'];

    // Check UserID is exist
    $query = "SELECT name, password FROM users WHERE userid = '$SigninUserID'";
    $result = $MyDB->Query($query);
    //var_dump($result);
    if (!$result) {
        $IsCorrectIDPass = false;
    }
    else {
        // Check Password is correct
        $row = $result->fetch_assoc();
        if ($row['password'] != $SigninPassword) {
            $IsCorrectIDPass = false;
        }
    }

    // Signin succeed ?
    if ($IsCorrectIDPass) {
        $SigninSucceed = true;
        echo 'Signin Succeed !';
    }
}

if ($SigninSucceed) {
    // Set signin information on session
    $_SESSION['signin_userid'] = $SigninUserID;
    $_SESSION['signin_name'] = $row['name'];

    // Redirect
    header('Location: /schedule/');
}
else {
?>
      <form class="form-horizontal" style="margin-bottom:15px;" method="post" action="signin/">
        <div class="form-group">
		  <label class="control-label col-sm-2" for="signin_userid">User ID</label>
		  <div class="col-sm-6">
		    <input type="text" id="signin_userid" name="signin_userid" class="form-control" placeholder="User ID">
          </div>
        </div>
	    <div class="form-group">
		  <label class="control-label col-sm-2" for="signin_password">Password</label>
		  <div class="col-sm-6">
		    <input type="password" id="signin_password" name="signin_password" class="form-control" placeholder="Password">
		  </div>
	    </div>
<?php
    if (!$IsCorrectIDPass) {
?>
        <div class="form-group has-error">
          <div class="col-sm-offset-2 col-sm-6">
            <span class="help-block">ユーザーIDまたはパスワードが不適切です．</span>
          </div>
        </div>
<?php
    }
?>
	    <div class="form-group">
		  <div class="col-sm-offset-2 col-sm-6">
		    <input type="submit" value="Sign In" class="btn btn-primary btn-lg">
		  </div>
	    </div>
	  </form>
<?php
}
?>