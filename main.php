<?php
$err = $name = $email = $tc = $password = $license = $dob =  "";
session_start();
if (!isset($_SESSION["userLoggedIn"])) {
    $_SESSION["userLoggedIn"] = false;
}

if (isset($_GET["logout"])) {
    session_destroy();
    header('Location: main.php');
    die();
}
if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {

    /**
     * @throws Exception
     */
    function test_input($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    /**
     * @throws Exception
     */
    function register()
    {
        global $err, $name, $email, $tc, $password, $license, $dob;
        $age = "";
        if (empty($_POST["name"])) {
            $err = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $err = "Only letters and white space allowed";
                return;
            }
        }

        if (empty($_POST["newemail"])) {
            $err = "Email is required";
            return;
        } else {
            $email = test_input($_POST["newemail"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = "Invalid email format";
                return;
            }
        }

        if (empty($_POST["newpwd"])) {
            $err = "Password is Required";
            return;
        } else {
            $password = test_input($_POST["newpwd"]);
            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
                $err = "Invalid Password";
                return;
            }
        }

        if (empty($_POST["newtc"])) {
            $err = "Tc Number is required";
            return;
        } else if (strlen($_POST["newtc"]) != 11) {
            $err = "Tc Number is not valid.";
            return;
        } else {
            $tc = test_input($_POST["newtc"]);
        }
        if (empty($_POST["dlicense"])) {
            $err = "License is required";
            return;
        } else {
            $license = test_input($_POST["dlicense"]);
        }
        if (empty($_POST["dob"])) {
            $err = "Date Of Birth is Required";
            return;
        } else {
            $now = date("d.m.y");
            $age = date_diff(date_create($_POST["dob"]), date_create($now));
        }
        if ($age->y < 18) {
            $err = "Your age must be greater than 18";
            return;
        } else {
            $dob = $_POST["dob"];
        }

        $servername = "localhost";
        $serverusername = "root";
        $serverpassword = "";
        $databasename = "carrental";

        $conn = new mysqli($servername, $serverusername, $serverpassword, $databasename);

        if ($conn->connect_error) {
            $conn->close();
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT `name`,email,id_no,DOB,license,custom_password,custom_status FROM customer WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "Email is exists";
            $conn->close();
            return;
        }
        $sql = "SELECT `name`,email,id_no,DOB,license,custom_password,custom_status FROM customer WHERE id_no='$tc'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "TC Number is exists";
            $conn->close();
            return;
        }
        $sql = "SELECT `name`,email,id_no,DOB,license,custom_password,custom_status FROM customer WHERE license='$license'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "License Number is exists";
            $conn->close();
            return;
        }
        $stmt = $conn->prepare("INSERT INTO customer (`name`,email,id_no,DOB,license,custom_password) VALUES(?,?,?,?,?,?)");
        $md5 = md5($password);
        $stmt->bind_param("ssssss", $name, $email, $tc, $dob, $license, $md5);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    /**
     * @throws Exception
     */
    function login()
    {
        global $err, $email, $password;
        if (empty($_POST["email"])) {
            $err = "Email is empty";
            return;
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = "Invalid email format";
                return;
            }
        }
        if (empty($_POST["password"])) {
            $err = "Password is empty";
            return;
        } else {
            $password = test_input($_POST["password"]);
        }
        $servername = "localhost";
        $serverusername = "root";
        $serverpassword = "";
        $dbname = "carrental";

        $conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
        if ($conn->connect_error) {
            $conn->close();
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT `ID`,`name`,email,id_no,DOB,license,custom_password,custom_status FROM customer WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['custom_status'] == 1) {
                    if ($row['custom_password'] == md5($password)) {
                        $_SESSION["name"] = $row['name'];
                        $_SESSION["id"] = $row['ID'];
                        $_SESSION["userLoggedIn"] = true;
                    } else {
                        $err = "Password is wrong";
                        $conn->close();
                        return;
                    }
                } else {
                    $err = "Your status is false";
                    $conn->close();
                    return;
                }
            }
            $conn->close();
        } else {
            $err = "Invalid Account";
            $conn->close();
        }
        header("Location:index.php");
    }

    if (isset($_POST["registerSubmit"])) {
        try {
            register();
        } catch (Exception $e) {
        }
    }
    if (isset($_POST["loginSubmit"])) {
        try {
            login();
        } catch (Exception $e) {
        }
    }


}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="login.css" />
    <title>Login and Register</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form method="post" action="main.php" class="sign-in-form">
            <h2 class="title">Sign in</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="email" placeholder="Email address" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" placeholder="Password" />
            </div>
            <input type="submit" name="loginSubmit" value="Login" class="btn solid" />
          </form>
          <form form action="main.php" method="post" class="sign-up-form">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="name" placeholder="Name" />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email"  name="newemail" placeholder="Email" />
            </div>
            <div class="input-field">
                <i class="fas fa-id-card"></i>
                <input type="text" placeholder="ID Number" name="newtc" />
              </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password"  name="newpwd" placeholder="Password" />
            </div>
            <p>Date of Birth</p>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="date" id="dob" name="dob" placeholder="Date of Birth" />
            </div>
            <p>Please add your Driver License</p>
            <div class="input-field">
                <i class="fa fa-id-card"></i>
                <input type="text" id="dlicense" name="dlicense" placeholder="Driver License" />
              </div>
            <input type="submit" name="registerSubmit" class="btn" value="Sign up" />
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>Don't have an account? Please Sign up
            </h3>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
          </div>
          <img src="images/arabs.png" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>If you already have an account, just sign in.</h3>  
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
          </div>
          <img src="images/png-transparent-cars-3-cruz-rami-removebg-preview.png" class="image" alt="" />
        </div>
      </div>
    </div>
    <?php
if ($err) {
    echo "<script type='text/javascript'>alert('$err');</script>";
}
?>
    <script src="login.js"></script>
  </body>
</html>