<?php
session_start();
if(isset($_GET["adminLogOut"])){
    session_destroy();
    header("Location:/wen/adminpage/index.php");
}
if(!$_SESSION["adminLoggedIn"]){
    header("Location:/wen/adminpage/index.php");
}
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
$rentedCarQuery = "SELECT * from rented_car";
$totalCarQuery = "SELECT * from car";

$totalCustomerQuery = "SELECT * from customer";
$rentedCarResult = mysqli_query($connect,$rentedCarQuery);


$totalCarResult = mysqli_query($connect,$totalCarQuery);
$totalCar = mysqli_num_rows($totalCarResult);

$totalCustomerResult = mysqli_query($connect,$totalCustomerQuery);
$totalCustomer = mysqli_num_rows($totalCustomerResult);

$connect = new mysqli($hostname, $username, $password, $databaseName);
                        $sql = "SELECT cc.custom_id,cc.car_id,cu.name,l.location,c.name,cc.pick_up,cc.drop_off 
                                FROM rented_car cc INNER JOIN customer cu ON cu.ID= cc.custom_id 
                                INNER JOIN car c ON c.ID =cc.car_id INNER JOIN location l ON l.ID = c.location_id;";
                        $cars = $connect->query($sql);
                        if (!$cars) {
                            die("Invalid Query: " . $connect->error);
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
    <link rel="stylesheet" href="adminstyle.css">

</head>
<body>
    <header>
        <div id="menu-bar" class="fas fa-bars"></div>
    
        <a href="../admin.php" class="logo"><color>HZ</color> CAR RENTAL</a>
    
        <nav class="navbar">
            <a href="admin.php">Home</a>
            <a href="#actives">Orders</a>
            <a href="admincars.php">Vehicles</a>
        </nav>
    
        <div class="icons">
            <a href="admin.php/?adminLogOut"><ikon class="fas fa-sign-out-alt" id="login-btn"><p>logout</p></ikon></a>            
        </div>
    </header>
    <section class="welcome">
        <h3>Welcome to</h3>
        <h2>Dashboard</h2>
    </section>
    <!-- admin -->

    <section class="admin">
        <div class="cardBox">
            <div class="card">
                <div> 
                <h2 class="d-flex align-items-center mb-0">
                  <?php echo $totalCar ?> </h2>
                    <div class="cardName">Active Vehicles</div>
                </div>
                <div class="icons">
                    <ikon class="fas fa-car"> </ikon>
                </div>
            </div>
        </div>
        <div class="cardBox">
            <div class="card">
                <div> 
                <h2 class="d-flex align-items-center mb-0">
                  <?php 
                  $totalincome="SELECT SUM(totalPrice) FROM rented_car";
                  $totalmoney = mysqli_query($connect,$totalincome);
                  $row = mysqli_fetch_array($totalmoney);
                  echo $row[0] ?> </h2>
                    <div class="cardName">Income</div>
                </div>
                <div class="icons">
                    <ikon class="fas fa-dollar-sign"> </ikon>
                </div>
            </div>
        </div>
        <div class="cardBox">
            <div class="card">
                <div> 
                <h2 class="d-flex align-items-center mb-0">
                                                <?php echo $totalCustomer ?>
                    <div class="cardName">Active Users</div>
                </div>
                <div class="icons">
                    <ikon class="fas fa-users"> </ikon>
                </div>
            </div>
        </div>
    </section>

    <section class="actives" id="actives">
        <div class="details">
            <div class="recentRents">
                <div class="cardHeader">
                    <h2>Latest Rents</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>name</td>
                            <td>Location</td>
                            <td>Price</td>
                            <td>DateFrom</td>
                            <td>DateTo</td>
                            <td>status</td>
                        </tr>

                    </thead>
                    <tbody>
                <?php
                $sql="SELECT c.name,l.location,cc.totalPrice,cc.pick_up,cc.drop_off,cc.status FROM car c,location l,rented_Car cc 
                WHERE c.ID=cc.car_id  AND l.ID=c.location_id ORDER BY cc.pick_up";
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
                                  </tr>";
                }?>
                </tbody>
                </table>
            </div>
        </div>
    </section>
    <section class="footer" >

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
                <a href="admin.php">home</a>
                <a href="admin.php#actives">Orders</a>
                <a href="admincars.php">vehicles</a>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script src="../script.js"></script>
<script src="admin.js"></script>