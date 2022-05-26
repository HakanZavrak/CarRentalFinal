
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
<div class="container" style="margin-bottom: 500px;padding:30px">
    <div class="row">
        <div class="col-md mt-5 mb-5">
            <form class="row-g ms-5 ps-5" method="post" action="booking.php">
                <div class="row form-group">
                    <div class="col-lg-3 pb-2 pt-2">
                        <input type="text" class="form-control " placeholder="City">
                    </div>
                    <div class="col-lg-3 pb-2 pt-2">
                        <div class="input-group date">
                            <label for="pickUpDate"></label><input placeholder="Pick Up Date" class="form-control"
                                                                   type="text" onfocus="(this.type='date')"
                                                                   id="pickUpDate">
                        </div>
                    </div>
                    <div class="col-lg-3 pb-2 pt-2">
                        <div class="input-group date">
                            <label for="deliveryDate"></label><input placeholder="Delivery Date" class="form-control"
                                                                     type="text" onfocus="(this.type='date')"
                                                                     id="deliveryDate">
                        </div>
                    </div>
                    <div class="col-lg-3 pb-2 pt-2">
                        <button type="submit" class="btn btn-warning">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md mt-5 mb-5">
        <table class="table table-image">
            <thead>
            <tr>
                
                <th scope="col">Car Name</th>
                <th scope="col">Location</th>
                <th scope="col">Daily Price</th>
                <th scope="col">Car Type</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            
            $type = $_SESSION["type"];
            $city = $_SESSION["dropLocation"];
            $pickUpDate = $_SESSION["pickUp"];
            $deliveryDate = $_SESSION["dropOff"];


            $sql = 'SELECT c.* ,l.location,ct.type FROM car c,location l,cartype ct
WHERE l.ID=c.location_id AND ct.ID=c.type_id AND ct.ID="'.$type.'"AND l.ID="'.$city.'" AND c.ID NOT IN(SELECT cc.car_id FROM rented_car cc WHERE c.ID=cc.car_id AND "' . $pickUpDate . '" BETWEEN cc.pick_up AND cc.drop_off
                    OR ' . $deliveryDate .  ' BETWEEN cc.pick_up AND cc.drop_off)';
            $cars = $connect->query($sql);
            if (!$cars) {
                die("Invalid Query: " . $connect->error);
            }

            while ($row = $cars->fetch_assoc()) {
               
                echo "<tr>
                <td class='w-25'> <img class='img-fluid img-thumbnail' src=C:/xampp/htdocs/wen/images/" . $row['image'] . "></td> 
               
                                  <td>" . $row['name'] . "</td>
                                  <td>" . $row['location'] . "</td>
                                  <td>" . $row['pricing'] . "</td>
                                  <td>" . $row['type'] . "</td>
                                  <td><a class='btn btn-warning' href=\"payment.php?id=".$row['ID']."\">Buy</a></td>
                                  </tr>";
            }?>
            </tbody>
        </table>
    </div>
</div>

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

</body>
</html>