<?php
session_start();
if(!$_SESSION["id"]){
    header("Location:index.php");
}
$id = $_SESSION["id"];
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
    <link rel="stylesheet" href="/adminpage/adminstyle.css">
    

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
<section class="welcome">
        <h3>Welcome to</h3>
        <h2>Your Bookings</h2>
        </section>

<body>

<section class="actives" id="actives">
        <div class="details">
            <div class="recentRents">
                <div class="cardHeader">
                    <h2>Latest Bookings</h2>
                </div>
                <div class="container">
    <div class="row">
        <div class="col-md mt-5 mb-5">

            <table class="table table-image table-striped">
            <thead>
                        <tr>
                            <td>Car name</td>
                            <td>Location</td>
                            <td>Price</td>
                            <td>Date From</td>
                            <td>Date To</td>
                            <td>status</td>
                            <td></td>
                        </tr>

                    </thead>
                <tbody>
                <?php
                $sql="SELECT c.name,l.location,cc.totalPrice,cc.pick_up,cc.drop_off,cc.status,cc.custom_id,cc.car_id FROM car c,location l,rented_Car cc 
                WHERE c.ID=cc.car_id AND $id=cc.custom_id AND l.ID=c.location_id ORDER BY cc.drop_off ";
                $cars = $connect->query($sql);
                if (!$cars) {
                    die("Invalid Query: " . $connect->error);
                }
                while ($row = $cars->fetch_assoc()) {
                    echo "<tr>
                                 
                                  <td>" . $row['name'] . "</td>
                                  <td>" . $row['location'] . "</td>
                                  <td>" . $row['totalPrice'] . "</td>
                                  <td>" . $row['pick_up'] . "</td>
                                  <td>" . $row['drop_off'] . "</td>
                                  <td>" . $row['status'] . "</td>
                                  <td> <a name='remove'  href=\"deletebooking.php?id=".$row['car_id']."\" class='btn'><p class='fas fa-trash'></p>Cancel Reservation</a></td>
                                  <td> <a name='review'  href=\"review.php?id=".$row['custom_id']."\" class='btn'><p class='fas fa-bolt'></p>Review</a></td>
                                  </tr>";
                                  
                }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
            </div>
        </div>
    </section>


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