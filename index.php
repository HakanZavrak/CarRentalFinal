<?php
session_start();
if(isset($_GET["logout"])){
   session_destroy();
   header("Location:index.php");
   die();
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

$locationQuery = "SELECT ID,location FROM location";
$locationResult = mysqli_query($conn,$locationQuery);
$dropLocationResult = mysqli_query($conn,$locationQuery);
$typequery="SELECT * FROM cartype";
$typeresult=mysqli_query($conn,$typequery);
if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {

    function search(){
        if($_SESSION["userLoggedIn"]){
            if(!empty($_POST["addLocation"]) && !empty($_POST["dropLocation"]) && !empty($_POST["pickUp"]) && !empty($_POST["dropOff"])){
                $day = date_diff(date_create($_POST["pickUp"]),date_create($_POST["dropOff"]));
                $today =date_create(date("d-m-Y"));
                $currentAndPickupDate = date_diff(date_create($_POST["pickUp"]),$today)->format("%r%a");
                $currentAndDeliveryDate = date_diff(date_create($_POST["dropOff"]),$today)->format("%r%a");
                if($currentAndDeliveryDate>0 || $currentAndPickupDate >0){
                    echo "<script type='text/javascript'>alert('The pickup date or delivery date cannot be earlier than today.');</script>";
                    return;
                }

                $day = $day->format("%r%a");
                if($day<1){
                    echo "<script type='text/javascript'>alert('The pickup date cannot be later than the delivery date. .');</script>";
                }
                else {
                    $_SESSION["addLocation"] = $_POST["addLocation"];
                    $_SESSION["dropLocation"] = $_POST["dropLocation"];
                    $_SESSION["pickUp"] = $_POST["pickUp"];
                    $_SESSION["dropOff"] = $_POST["dropOff"];
                    $_SESSION["type"]=$_POST["type"];
                    header("Location:selection.php");
                }
            }
            else{
                echo "<script type='text/javascript'>alert('You have to fill the blanks.');</script>";
            }
        }
        else{
            echo "<script type='text/javascript'>alert('You have to login');</script>";
        }
    }
    if(isset($_POST["rentButton"])){
            search();
    }

}
?>
<!DOCTYPE html>
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

</head>
<body>
    
<!-- header -->

<header>

    <div id="menu-bar" class="fas fa-bars"></div>
    <a href="#" class="logo"><color>HZ</color> CAR RENTAL</a>

    <nav class="navbar">
        <a href="#home">Home</a>
        <a href="#rent">Rent</a>
        <a href="#vehicles">vehicles</a>
        <a href="#services">Services</a>
        <a href="#gallery">Gallery</a>
        <a href="#contact">Contact</a>
        <a href="mybookings.php">My Bookings</a>
    </nav>
    <?php if(!$_SESSION["userLoggedIn"]){ ?>
        <div class='icons'>
            <a href='main.php' class=''><ikon class='fas fa-user' id='login-btn' ><p>login</p>
            </ikon></a>   
        </div>
    <?php }
    else {?>
            <div class='icons'>
            <a href='index.php?logout' class=''><ikon class='fas fa-user' id='login-btn' ><p>logout</p>
            </ikon></a>   
        </div>
        <?php }?>
</header>
<!-- anasayfa -->

<section class="home" id="home">

    <div class="content">
        <h3>RENT A CAR AND DRIVE WITH US</h3>
        <p>roads are waiting for you</p>
    </div>
    <div class="video-container">
        <video src="images/GT3 458 [4K].mp4" id="video-slider" loop autoplay muted></video>
    </div>

</section>

<!-- kiralama kısmı -->

<section class="rent" id="rent">

    <h1 class="heading" >
        <color>R</color>
        <color>E</color>
        <color>N</color>
        <color>T</color>
        <color class="space"></color>
        <color>N</color>
        <color>O</color>
        <color>W</color>
    </h1>

    <div class="row">
        <div class="image">
            <img src="images/9c342a813860096ff3630f492f15c0ec.jpg" alt="">
        </div>

        <form action="index.php" method="post">
            <div class="inputBox">
                <h3>Pick-Up Location</h3>
                <select class="form-control" name="addLocation">
                                <option value="" selected> Location</option>
                                <?php while ($row1 = mysqli_fetch_array($locationResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['location']; ?></option>
                                <?php endwhile; ?>
                            </select>
            </div>
            <div class="inputBox">
                <h3>Drop-Off Location</h3>
                <select class="form-control" name="dropLocation">
                                <option value="" selected> Location</option>
                                <?php while ($row2 = mysqli_fetch_array($dropLocationResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['location']; ?></option>
                                <?php endwhile; ?>
                            </select>
            </div>
            <div class="inputBox">
                <h3>Car type</h3>
                <select class="form-control" name="type">
                                <option value="" selected> Type</option>
                                <?php while ($row3 = mysqli_fetch_array($typeresult)): ?>
                                    <option value="<?php echo $row3['ID']; ?>"><?php echo $row3['type']; echo " / "; echo $row3['speed']; ?></option>
                                <?php endwhile; ?>
                            </select>
            </div>
            <div class="inputBox">
                <h3>Pick-Up Date</h3>
                <input name="pickUp" type="date">
            </div>
            <div class="inputBox">
                <h3>Drop-Off Date</h3>
                <input name="dropOff" type="date">
            </div>
            <input type="submit" name="rentButton" class="btn" value="Rent Now"></a>
        </form>

    </div>
</section>
<!-- arabalar -->

<section class="vehicles" id="vehicles">

    <h1 class="heading">
        <color>V</color>
        <color>E</color>
        <color>H</color>
        <color>I</color>
        <color>C</color>
        <color>L</color>
        <color>E</color>
        <color>S</color>
    </h1>

    <div class="box-container">

        <div class="box">
            <img src="images/HD-wallpaper-ferrari-f8-tributo-2019-2.jpg" alt="">
            <div class="content">
                <h3> <i class="fas fa-car"></i> Ferrari </h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                <div class="price"> $130.00 <color>$180.00</color> </div>
                <a href="#rent" class="btn">Rent Now</a>
            </div>
        </div>

        <div class="box">
            <img src="images/porsche-911-turbo-s-1920x1080_785794-mm-90.jpg" alt="">
            <div class="content">
                <h3> <i class="fas fa-car"></i> Porsche </h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                <div class="price"> $90.00 <color>$120.00</color> </div>
                <a href="#rent" class="btn">Rent Now</a>
            </div>
        </div>

        <div class="box">
            <img src="images/images1.jpg" alt="">
            <div class="content">
                <h3> <i class="fas fa-car"></i> Chevrolet </h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                <div class="price"> $90.00 <color>$120.00</color> </div>
                <a href="#rent" class="btn">Rent Now</a>
            </div>
        </div>

        <div class="box">
            <img src="images/aston-dbs-martin-superleggera-wallpaper-preview.jpg" alt="">
            <div class="content">
                <h3> <i class="fas fa-car"></i> Aston Martin </h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                <div class="price"> $110.00 <color>$140.00</color> </div>
                <a href="#rent" class="btn">Rent Now</a>
            </div>
        </div>

        <div class="box">
            <img src="images/mclaren-720s-track-pack.jpg" alt="">
            <div class="content">
                <h3> <i class="fas fa-car"></i> McLaren </h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                <div class="price"> $160.00 <color>$220.00</color> </div>
                <a href="#rent" class="btn">Rent Now</a>
            </div>
        </div>

        <div class="box">
            <img src="images/lamborghini-aventador-s-yellow.jpg" alt="">
            <div class="content">
                <h3> <p class="fas fa-car"></p> Lamborghini </h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                <div class="price"> $120.00 <color>$180.00</color> </div>
                <a href="#rent" class="btn">Rent Now</a>
            </div>
        </div>
    </div>
    <div class="content2">
        <a href="cars.html" class="btnn">Show More</a>
    </div>
</section>

<!-- servisler -->

<section class="services" id="services">

    <h1 class="heading">
        <color>s</color>
        <color>e</color>
        <color>r</color>
        <color>v</color>
        <color>i</color>
        <color>c</color>
        <color>e</color>
        <color>s</color>
    </h1>

    <div class="box-container">

        <div class="box">
            <ikon class="fas fa-key"></ikon>
            <h3>Fast Access</h3>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore commodi earum, quis voluptate exercitationem ut minima itaque iusto ipsum corrupti!</p>
        </div>
        <div class="box">
            <ikon class="fas fa-credit-card"></ikon>
            <h3>Online & Secured Payment</h3>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore commodi earum, quis voluptate exercitationem ut minima itaque iusto ipsum corrupti!</p>
        </div>
        <div class="box">
            <ikon class="fas fa-shield-alt"></ikon>
            <h3>Safe Cars</h3>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore commodi earum, quis voluptate exercitationem ut minima itaque iusto ipsum corrupti!</p>
        </div>
        <div class="box">
            <ikon class="fas fa-globe-asia"></ikon>
            <h3>Worldwide</h3>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore commodi earum, quis voluptate exercitationem ut minima itaque iusto ipsum corrupti!</p>
        </div>
        <div class="box">
            <ikon class="fas fa-dollar-sign"></ikon>
            <h3>Luxury cars</h3>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore commodi earum, quis voluptate exercitationem ut minima itaque iusto ipsum corrupti!</p>
        </div>
        <div class="box">
            <ikon class="fas fa-phone"></ikon>
            <h3>7/24 Services</h3>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore commodi earum, quis voluptate exercitationem ut minima itaque iusto ipsum corrupti!</p>
        </div>

    </div>

</section>
<!-- galeri -->

<section class="gallery" id="gallery">

    <h1 class="heading">
        <color>g</color>
        <color>a</color>
        <color>l</color>
        <color>l</color>
        <color>e</color>
        <color>r</color>
        <color>y</color>
    </h1>

    <div class="box-container">

        <div class="box">
            <img src="images/18.jpg" alt="">
        </div>
        <div class="box">
            <img src="images/12.jpg" alt="">
        </div>
        <div class="box">
            <img src="images/13.jpg" alt="">
        </div>
        <div class="box">
            <img src="images/14.jpg" alt="">
        </div>
        <div class="box">
            <img src="images/15.jpg" alt="">
        </div>


    </div>

</section>
<!-- iletisim -->

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
        <color>c</color>
        <color>o</color>
        <color>n</color>
        <color>t</color>
        <color>a</color>
        <color>c</color>
        <color>t</color>
        <color class="space"></color>
        <color>U</color>
        <color>S</color>
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
<!-- footer -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3>about us</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda quas magni pariatur est accusantium voluptas enim nemo facilis sit debitis.</p>
        </div>
        <div class="box">
            <h3>Locations</h3>
            <p>Turkey</p>
            <p>USA</p>
            <p>Japan</p>
            <p>Germany</p>
        </div>
        <div class="box">
            <h3>Are you lost</h3>
            <a href="#home">home</a>
            <a href="#rent">rent</a>
            <a href="#vehicles">vehicles</a>
            <a href="#services">services</a>
            <a href="#gallery">gallery</a>
            <a href="#contact">contact</a>
        </div>
        <div class="box">
            <h3>Socials</h3>
            <a href="#">facebook</a>
            <a href="#">instagram</a>
            <a href="#">twitter</a>
            <a href="#">linkedin</a>
        </div>

    </div>
    <h1 class="credit"> created by <color> Hakan Zavrak </color>

</section>
<script src="script.js"></script>
</body>
</html>