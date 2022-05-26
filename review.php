<html lang="en">
<head>
    <script>
        document.querySelector('.img__btn').addEventListener('click', function () {
          document.querySelector('.cont').classList.toggle('s--signup');
        });
      </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car rental site</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/adminpage/adminstyle.css">
    

</head>
<body>
<section class="contact" id="contact">
    
<?php


if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "carrental";
    $connect = new mysqli($hostname, $username, $password, $databaseName);
    $err = null;
    if ($connect->connect_error) {
        $connect->close();
        die("Connection failed: " . $connect->connect_error);
    }
    if(empty($_POST["textArea1"]) || empty($_POST["subject"]) || empty($_POST["sendEmail"]) || empty($_POST["name"]) || empty($_POST["phoneNum"])){
        $err = "You have too fill the all blanks.";
    }
    $email = $_POST["sendEmail"];
    $name = $_POST["name"];
    $phoneNum = $_POST["phoneNum"];
    $subject = $_POST["subject"];
    $message = $_POST["textArea1"];

    $sql = "INSERT INTO contact(email,phoneNum,name,title,message) VALUES(?,?,?,?,?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssss",$email,$phoneNum,$name,$subject,$message);
    $stmt->execute();
    $stmt->close();
    $connect->close();
}
?>
    
    <h1 class="heading">
        <color>r</color>
        <color>e</color>
        <color>v</color>
        <color>i</color>
        <color>e</color>
        <color>w</color>
    </h1>

    <div class="row">
        <form action="index.php" method="post">
            <div class="inputBox">
                <input type="text" name="name" placeholder="Name">
                <input type="email" name="sendEmail" placeholder="Email">
            </div>
            <div class="inputBox">
                <input type="number" name="phoneNum" placeholder="Phone Number">
                <input type="text" name="subject" placeholder="Title">
            </div>
            <textarea placeholder="Your Message" name="textArea1" id="" cols="30" rows="10"></textarea>
            <input type="submit" name="contactButton"class="btn" value="Send Message">
        </form>

    </div>
    
</section>