<?php
    error_reporting(0);
    ini_set('display_errors', 0);

    session_start();

    if($_SESSION['name']){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $target_dir = 'uploads/';
            $target_file = urldecode($target_dir . $_FILES["fileUpload"]["name"]);
            $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $flag = 1;
            $error = array();

            // Kiểm tra kích cỡ file upload
            if ($_FILES['fileUpload']['size'] > 3*1024*1024) {
                $error['fileUpload'] = 'Chỉ cho phép upload file dưới 3MB !';
                $flag = 0;
            }
            // Kiểm tra kiểu file
            $file_allow = array('jpg', 'png', 'jpeg', 'gif');
            if(!in_array($file_type, $file_allow)) {
                $error['fileUpload'] = "Sorry, File không được cho phép .";
                $flag = 0;		 
            }

            //Kiểm tra mime type
            $mime_type = mime_content_type($_FILES['fileUpload']['tmp_name']);
            $allowed_file_types = ['image/png', 'image/jpeg', 'application/pdf'];
            if (! in_array($mime_type, $allowed_file_types)) {
                $error['fileUpload'] = "Sorry, File không được cho phép .";
                $flag = 0;
            }

            //  Xử lí upload
            $target_file = strtok($target_file, chr(0));
            if (empty($error)) {
                $target_file = md5_file($target_file);
                if (move_uploaded_file($_FILES['fileUpload']['tmp_name'] , 'uploads/' . $target_file)) {
                    chmod($target_file, 0755); // thêm quyền không thể thực thi trước khi đưa tệp vào server
                    echo '
                    <div class="alert alert-success">
                        <strong>Upload thành công !</strong>
                    </div>
                    ';

                }else{
                    echo '
                    <div class="alert alert-danger">
                        <strong>Upload thất bại !</strong>
                    </div>
                    ';
                }
            }

        include 'connect_database.php';

        $user_check_query = "SELECT * FROM users WHERE username='{$_SESSION['name']}'";
		$result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        if($user) {
            $id = $user['id'];
            $username = $user['username'];
            $email = $user['email'];
        }
        }
    }
    else{
		header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <style>
        
        .container {
            display: flex;
        }

        img{
            width: 200px;
            height: 200px;
        }

        header {
            display: flex;
        }

        header a {
            margin-left: 50px;
        }

        .infor {
            width: 30%;
            border-right: 1px solid #333;
        }

        .upload {
            padding-left: 10px;
        }


    </style>

</head>
<body>
    <header>
        <h1> Welcome, <?php echo($_SESSION['name']); ?><h1>
        <a class="btn" href="logout.php">Logout</a>
    </header>
    <section>
        <div class="container">
            <div class="infor">
                <h2> Thông tin cá nhân </h2>
                <div>ID: <?php echo($_SESSION['id']); ?></div>
                <div>Username: <?php echo($_SESSION['name']); ?></div>
                <div>Email: <?php echo($_SESSION['email']); ?></div>
            </div>
        <div class="upload">
            <h2>Upload image</h2>
            <form method="post" enctype="multipart/form-data" class="form-upload" >
            <div class="form-row">
					<div class="col">
						<input type="file" name="fileUpload" id="fileUpload" class="btn btn-info">
					</div>
					<div class="col">
						<input type="submit" value="upload" name="submit" class="btn-upload btn btn-success float-right">
					</div>
				</div>
            </form>
        </div>

            <!-- hiển thị lỗi upload -->
            <?php
			
			if ($flag){
				echo "<img src='/Bai_1/src/uploads/".$target_file."' alt='Avatar'>";
			}else{
				echo '<img src="/Bai_1/src/uploads/default.png" alt="Avatar">';
			}

			if(!empty($error)){
				echo '
				<div class="alert alert-warning">
	  				<strong>Warning !</strong> "'.$error['fileUpload'].'"
				</div>
				';
			} 
			 ?>

        </div>
    </section>
    
</body>
</html>