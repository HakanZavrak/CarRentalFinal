<?php
session_start(); 
if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
	$sname= "localhost";
	$unmae= "root";
	$password = "";
	$db_name = "carrental";
	
	$conn = mysqli_connect($sname, $unmae, $password, $db_name);
	
	if (!$conn) {
		echo "Connection failed!";
	}

if (isset($_POST['uemail']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uemail = validate($_POST['uemail']);
	$pass = validate($_POST['password']);

	if (empty($uemail)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	}else if(empty($pass)){
        header("Location: index.php?error=Password is required");
	    exit();
	}else{
		$sql = "SELECT * FROM `admin` WHERE `email`='$uemail' AND admin_pass='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['email'] == $uemail && $row['admin_pass'] == $pass) {
            	$_SESSION['adminName'] = $row['name'];
            	$_SESSION['adminId'] = $row['id'];
				$_SESSION["adminLoggedIn"] = true;
            	header("Location: admin.php");
		        exit();
            }else{
				header("Location: index.php?error=Incorect User name or password");
		        exit();
			}
		}else{
			header("Location: index.php?error=Incorect User name or password");
	        exit();
		}
	}
	
}else{
	header("Location: index.php");
	exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
     <form action="index.php" method="post">
     	<h2>LOGIN</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
     	<label>Admin Email</label>
     	<input type="text" name="uemail" placeholder="Admin Email"><br>

     	<label>Admin Name</label>
     	<input type="password" name="password" placeholder="Password"><br>

     	<button type="submit">Login</button>
     </form>
</body>
</html>