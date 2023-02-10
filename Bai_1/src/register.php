
<?php
  session_start();

  $username = "";
  $email    = "";
  $password_1 = "";
  $password_2 = "";
  $errors = array();


  if (isset($_POST['reg_user']) ) {

    if ($_POST['g-recaptcha-response']!="") {

      include 'connect_database.php';

  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    if (empty($username)) {
      array_push($error, "Username is required");
    }

    if (empty($email)) { array_push($errors, "Email is required"); }

    if ($password_1 != $password_2) {
      array_push($errors, "Mật khẩu không khớp");
      }

    $user_check_query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
      if ($user['username'] === $username ){
        array_push($errors, "username đã tồn tại");
      }

      if ($user['email'] === $email) {
        array_push($errors, "email đã tồn tại");
      }
    }

    if (count($errors) == 0){

      $secret = '6LebTm0kAAAAAP-B2T1I1_1f5Aa7PeBht5fuT_V5';
      $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
      $responseData = json_decode($verifyResponse);

      if ($responseData->success) {
        $password = md5($password_1);

        $query = "INSERT INTO users (username,email, password) 
  			  VALUES('$username','$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Tạo tài khoản thành công. Xin hãy đăng nhập lại.";

        header("Location: index.php");
      }
    }
    }
    else{
      array_push($errors, "Xin hãy xác minh captcha trước");
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Đăng ký</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <style>
    * {
  margin: 0px;
  padding: 0px;
}
body {
  font-size: 120%;
  background: #F8F8FF;
}

.header {
  width: 30%;
  margin: 50px auto 0px;
  color: white;
  background: #5F9EA0;
  text-align: center;
  border: 1px solid #B0C4DE;
  border-bottom: none;
  border-radius: 10px 10px 0px 0px;
  padding: 20px;
}
form, .content {
  width: 30%;
  margin: 0px auto;
  padding: 20px;
  border: 1px solid #B0C4DE;
  background: white;
  border-radius: 0px 0px 10px 10px;
}
.input-group {
  margin: 10px 0px 10px 0px;
}
.input-group label {
  display: block;
  text-align: left;
  margin: 3px;
}
.input-group input {
  height: 30px;
  width: 93%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid gray;
}
.btn {
  padding: 10px;
  font-size: 15px;
  color: white;
  background: #5F9EA0;
  border: none;
  border-radius: 5px;
}
.error {
  width: 92%; 
  margin: 0px auto; 
  padding: 10px; 
  border: 1px solid #a94442; 
  color: #a94442; 
  background: #f2dede; 
  border-radius: 5px; 
  text-align: left;
}
.success {
  color: #3c763d; 
  background: #dff0d8; 
  border: 1px solid #3c763d;
  margin-bottom: 20px;
}
  </style>

</head>
<body>
  <div class="header">
  	<h2>Đăng ký</h2>
  </div>
	
  <form method="post" action="register.php">
    
    <?php  if (count($errors) > 0) : ?>
      <div class="error">
  	    <?php foreach ($errors as $error) : ?>
  	      <p><?php echo $error ?></p>
  	      <?php endforeach ?>
      </div>
    <?php  endif ?>


  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>">
  	</div>

    <div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="<?php echo $email; ?>">
  	</div>
  	
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>

    <div class="g-recaptcha" data-sitekey="6LebTm0kAAAAAGBtprFFIsR2Tm5_Wf8bvdo0ndkK"></div>

    <div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Đăng ký</button>
  	</div>
  	<p>
  		Bạn đã có tài khoản? <a href="index.php">Đăng nhập</a>
  	</p>
  </form>
</body>
</html>