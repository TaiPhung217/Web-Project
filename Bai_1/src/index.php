
<?php
    // error_reporting(0);
    // ini_set('display_errors', 0);

    session_start();

    // include 'connect_database.php';

    $username = "";
    $password = "";

    $db = mysqli_connect("localhost:3307", "root", "", "dtbase");


    if(isset($_POST['username']) && isset($_POST['password'])){

        if ($_POST['g-recaptcha-response']!="") {
            $secret = '6LebTm0kAAAAAP-B2T1I1_1f5Aa7PeBht5fuT_V5';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);

            if ($responseData->success) {

            $username = mysqli_real_escape_string($db, $_POST['username']);
            $password = mysqli_real_escape_string($db, md5($_POST['password']));


            #$user_check_query = "SELECT * FROM users WHERE username='$username'";
		    #$result = mysqli_query($db, $user_check_query);
            #$user = mysqli_fetch_assoc($result);

            $stmt = $db->prepare("select * from users where username=? limit 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if($user) {
                $_SESSION['name'] = $user['username'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                header('Location: welcome.php');
            }
            else{
                echo '<h3>Login false</h3>';
                    die();
            }
            }

        }
        else{
            echo ("Xin hãy xác minh captcha trước!!!");
        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Login</title>

    <style>
        .bg-img {
            width: 50%;
            height: auto;
            position: relative;
            left: 100px;
        }

        .container {
            display: flex;
            flex-direction: column;
        }

        input[type=text],
        input[type=password] {
            width: 200px;
            border-radius: 3px;
        }

        .btn {
            width: 100px;
            cursor: pointer;
            border-radius: 3px;
            margin-top: 5px;
        }
    </style>

</head>

<body>
<div class="bg-img">
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="container" method="POST">
    <h1>Login</h1>

    <label for="email"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <p><a href="/Bai_1/src/register.php">Bạn chưa có tài khoản</a></p>

    <div class="g-recaptcha" data-sitekey="6LebTm0kAAAAAGBtprFFIsR2Tm5_Wf8bvdo0ndkK"></div>

    <button type="submit" class="btn">Login</button>
  </form>
</div>

</body>

</html>