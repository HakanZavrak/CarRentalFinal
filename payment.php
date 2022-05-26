<?php
session_start();


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
    <!-- <script>
        document.querySelector('.img__btn').addEventListener('click', function () {
          document.querySelector('.cont').classList.toggle('s--signup');
        });
      </script> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car rental site</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <header>
        
        <div id="menu-bar" class="fas fa-bars"></div>
    
        <a href="#" class="logo"><color>HZ</color> CAR RENTAL</a>
    
        <nav class="navbar">
            <a href="index.html#home">Home</a>
            <a href="index.html#rent">Rent</a>
            <a href="index.html#vehicles">vehicles</a>
            <a href="index.html#services">Services</a>
            <a href="index.html#gallery">Gallery</a>
            <a href="index.html#contact">Contact</a>
        </nav>
    
        <div class="icons">
            <a href="login.html" class=""><ikon class="fas fa-user" id="login-btn">
            </ikon></a>
        </div>
    
    </header>
    <?php
        if (!isset($_POST["rent"])) {
            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            parse_str(parse_url($url)['query'], $params);
            $carID =  $params['id'];
            $_SESSION["carid"]=$carID;
            $customerID = $_SESSION["id"];
            
            $pickUpDate = $_SESSION["pickUp"];
            $dropOffDate = $_SESSION["dropOff"];

            $priceSql = "SELECT pricing FROM car WHERE ID=" . $carID;
            $pricing = $connect->query($priceSql);
            $row = $pricing->fetch_assoc();
            $dateDiff = date_diff(date_create($pickUpDate),date_create($dropOffDate));
            $days = $dateDiff->format("%a");
            $price = $row["pricing"] * $days;
            $_SESSION["price"] = $price;
            var_dump($price);
        }
            
        ?>
    <section class="payment" id="payment">
        <div class="box-container">

            <div class="box">
                <ikon class="fas fa-calendar-check"></ikon>
                <h3>Rent</h3>
            </div>
            <div class="box">
                <ikon class="fas fa-arrow-right"></ikon>
            </div>
            <div class="box">
                <ikon class="fas fa-car"></ikon>
                <h3>Car Selection</h3>
            </div>
            <div class="box">
                <ikon class="fas fa-arrow-right"></ikon>
            </div>
            <div class="box">
                <currikon class="fas fa-credit-card"></currikon>
                <h3>Payment</h3>
            </div>
        </div>
         </section>
         <section class="vehicles" id="vehicles">
            <div class="box-container">      
                <div class="box">
                    <div class="content">
                        <h3> Rental Details </h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                        <h3>Price<div class="price"> <?php echo "$" . $_SESSION["price"] . "  " ?><color> $180.00</color> </div></h3>                
                    </div>
                </div>
            </div>
        </section>


         <section class="paymentcard" id="paymentcard">
             <h2 style="color: coral; font-size: 50px;">Payment Information</h2>
             <div class="row">
            <form name="rent" action="payment.php?id=" . $carID method="POST">
                <div class="inputBox">
                    <h3>Card Number</h3>
                    <payikon class="fas fa-credit-card"></payikon>
                    <input type="number">
                </div>
                <div class="inputBox">
                    <h3>Expiration Date</h3>
                    <payikon class="fas fa-clock"></payikon>
                    <input type="month">
                </div>
                <div class="inputBox">
                    <h3>CVV</h3>
                    <payikon class="fas fa-credit-card"></payikon>
                    <input type="number">
                </div>
                <div class="inputBox">
                    <h3>Name On Card</h3>
                    <payikon class="fas fa-credit-card"></payikon>
                    <input type="text">
                </div>
                <button name="rent" class="paybtn">Rent Now</button>
            </form>
                </div>
         </section>
         <?php 
         
            if (isset($_POST["rent"])) {
                
                $customerID = $_SESSION["id"];
                $pickUpDate = $_SESSION["pickUp"];
                $dropOffDate = $_SESSION["dropOff"];    
                $carID=$_SESSION["carid"];
                $price = $_SESSION["price"];
                $rentSql = "INSERT INTO rented_car (custom_id, car_id, pick_up, drop_off, custom_damage, status, totalPrice) VALUES ($customerID,$carID,'$pickUpDate','$dropOffDate','NO','ACTIVE', $price)";
                
                if ($connect->query($rentSql) === TRUE) {
                    echo "<script type='text/javascript'>alert('Booking Complete.');</script>";
                  } else {
                    echo "Error: " . $rentSql . "<br>" . $conn->error;
                  }
            }
         ?>
         <section class="vehicles" id="vehicles">
             <h2 style="color: coral;font-size: 50px;"> Rental Policies</h2>
            <div class="box-container">      
                <div class="box">
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae quibusdam expedita sit in q
                            uia illo earum, veritatis aliquam omnis, eius tempora, quasi nisi odio quidem sapiente volupta
                            s quas libero incidunt quisquam quae debitis! Vero accusamus temporibus ducimus perferendis 
                            voluptatibus id totam, eveniet distinctio vitae quaerat iure obcaecati, magni odio, molestiae si
                            milique hic quibusdam sunt quo illo reiciendis ut! Aliquam, laudantium eaque! Dicta nobis, exercitat
                            ionem fuga et necessitatibus placeat saepe consequuntur unde. Ad expedita quae, voluptas autem solu
                            ta beatae culpa vitae, neque placeat aliquid voluptatum architecto! Quam tempora dolor minus enim 
                            laudantium nam officia sint modi earum necessitatibus quos, fugiat atque error inventore autem be
                            atae doloremque, aliquid alias hic? Similique voluptatibus consequuntur pariatur facere repudiand
                            ae. Quibusdam expedita, voluptates aut perspiciatis possimus ad sit ipsam praesentium quis esse, 
                            nulla et exercitationem id explicabo quisquam dolorem, obcaecati debitis doloribus in consequuntu
                            r optio quas. Laudantium deleniti cupiditate cumque, temporibus ullam autem delectus vitae non el
                            igendi obcaecati, placeat rem eos a hic maxime nobis minus accusamus assumenda reiciendis earum v
                            eniam. Voluptates a amet corporis illo deserunt ea numquam inventore. Aliquid quo id quia, quid
                            em blanditiis adipisci in recusandae mollitia totam tenetur rerum repellendus nulla accusantium
                             molestias sint eaque minima tempora quisquam vero deleniti magni exercitationem?</p>               
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
                <a href="index.html#home">home</a>
                <a href="index.html#rent">rent</a>
                <a href="index.html#vehicles">vehicles</a>
                <a href="index.html#services">services</a>
                <a href="index.html#gallery">gallery</a>
                <a href="index.html#contact">contact</a>
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
</body>
<script src="script.js"></script>