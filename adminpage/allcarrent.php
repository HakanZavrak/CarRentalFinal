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
                        $id = $_GET['id'];
                        



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
<section class="actives" id="actives">
        <div class="details">
            <div class="recentRents">
                <div class="cardHeader">
                    <h2>This cars all bookings</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>car ID</td>
                            
                            <td>DATE FROM</td>
                            <td>DATE TO</td>
                            <td>PRICE</td>
                            
                        </tr>

                    </thead>
                    <tbody>
                <?php
                $sql="SELECT car_id,pick_up,drop_off,totalPrice FROM rented_car WHERE car_id='.$id.'";
                $cars = $connect->query($sql);
                if (!$cars) {
                    die("Invalid Query: " . $connect->error);
                }
                while ($row = $cars->fetch_assoc()) {
                    echo "<tr>
                                 
                                  <td>" . $row['car_id'] . "</td>
                                 
                                  
                                  <td>" . $row['pick_up'] . "</td>
                                  <td>" . $row['drop_off'] . "</td>
                                  <td>" . $row['totalPrice'] . "</td>
                               
                                  </tr>";
                }?>
                </tbody>
                </table>
            </div>
        </div>
    </section>