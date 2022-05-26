<?php
session_start();
if(!$_SESSION["adminLoggedIn"]){
    header("Location:/wen/adminpage/index.php");
}
	$sname= "localhost";
	$unmae= "root";
	$password = "";
	$db_name = "carrental";
	
	$conn = mysqli_connect($sname, $unmae, $password, $db_name);
	
	if (!$conn) {
		echo "Connection failed!";
	}
$addingError=null;
$newImageName=null;   
$carBrandQuery = "SELECT * FROM brand";
$carBrandResult = mysqli_query($conn,$carBrandQuery);

$carGearQuery = "SELECT * FROM gear";
$carTypeQuery = "SELECT * FROM cartype";
$carEngineQuery = "SELECT * FROM engine";
$carColorQuery = "SELECT * FROM color";
$carLocationQuery = "SELECT * FROM `location`";
$carGearResult = mysqli_query($conn,$carGearQuery);
$carTypeResult = mysqli_query($conn,$carTypeQuery);
$carEngineResult = mysqli_query($conn,$carEngineQuery);
$carColorResult = mysqli_query($conn,$carColorQuery);
$carLocationResult = mysqli_query($conn,$carLocationQuery);


if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
   function addCar(){
    global $conn,$addingError,$newImageName;

    if(empty($_POST["plate"])){
        $addingError = "Car plate can not be empty";
        return;
    }
    else{
        $plate = $_POST["plate"];
    }
    if(empty($_POST["name"])){
        $addingError = "Car name can not be empty";
        return;
    }
    else{
        $name = $_POST["name"];
    }
    if(empty($_POST["brand"])){
        $addingError = "Car brand can not be empty";
        return;
    }
    else{
        $brand = intval($_POST["brand"]);
    }
    if(empty($_POST["modified"])){
        $addingError = "Car modified can not be empty";
        return;
    }
    else{
        $modified = $_POST["modified"];
    }
    if(empty($_POST["damagestatus"])){
        $addingError = "Car damagestatus can not be empty";
        return;
    }
    else{
        $damagestatus = $_POST["damagestatus"];
    }
    if(empty($_POST["gear"])){
        $addingError = "Car gear can not be empty";
        return;
    }
    else{
        $gear = intval($_POST["gear"]);
    }
    if(empty($_POST["type"])){
        $addingError = "Car type can not be empty";
        return;
    }
    else{
        $type = intval($_POST["type"]);
    }
    if(empty($_POST["engine"])){
        $addingError = "Car engine can not be empty";
        return;
    }
    else{
        $engine = intval($_POST["engine"]);
    }
    if(empty($_POST["color"])){
        $addingError = "Car color can not be empty";
        return;
    }
    else{
        $color = intval($_POST["color"]);
    }
    if(empty($_POST["location"])){
        $addingError = "Car location can not be empty";
        return;
    }
    else{
        $location = intval($_POST["location"]);
    }
    if(empty($_POST["price"])){
        $addingError = "Car price can not be empty";
        return;
    }
    else{
        $price = intval($_POST["price"]);
    }
    if(empty($_POST["year"])){
        $addingError = "Car year can not be empty";
        return;
    }
    else{
        $year = $_POST["year"];
    }
    
    if ($_FILES['carImage']) {
        $imageName = $_FILES['carImage']['name'];
        $tmpName = $_FILES['carImage']["tmp_name"];
        $img_ex = pathinfo($imageName, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);

        $allowed_exs = array("jpg", "jpeg", "png");
        if (in_array($img_ex_lc, $allowed_exs)) {
            $newImageName = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $imageUploadPath = 'C:/xampp/htdocs/wen/images/' . $newImageName;
            move_uploaded_file($tmpName, $imageUploadPath);
        } else {
            $addingError = "You cant upload files of this type";
            return;
        }
    } else {
        $addingError = "Unknown Error occured";
        return;
    }
 
    $stmt = $conn->prepare("INSERT INTO car(bran_id,modified,damage_status,`type_id`,gear_id,engine_id,`name`,color_id,`year`,pricing,location_id,car_plate,`image`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issiiisisiiss",$brand,$modified,$damagestatus,$type,$gear,$engine,$name,$color,$year,$price,$location,$plate,$newImageName);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location:admincars.php");
   }

   if(isset($_POST["addCar"])){
       addCar();
   }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car rental site</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="adminstyle.css">
    
    

</head>
<style>
* {
  box-sizing: border-box;
}

body {
  font-family: Arial, Helvetica, sans-serif;
}

/* Float four columns side by side */
.column {
  float: left;
  width: 25%;
  padding: 0 10px;
}

/* Remove extra left and right margins, due to padding */
.row {margin: 0 -5px;}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive columns */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
    display: block;
    margin-bottom: 20px;
  }
}

/* Style the counter cards */
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  padding: 16px;
  text-align: center;
  background-color: #f1f1f1;
}
</style>
<body>
<header>
        <div id="menu-bar" class="fas fa-bars"></div>
    
        <a href="../admin.php" class="logo"><color>HZ</color> CAR RENTAL</a>
    
        <nav class="navbar">
            <a href="admin.php">Home</a>
            <a href="admin.php#actives">Orders</a>
            <a href="admincars.php">Vehicles</a>
        </nav>
    
        <div class="icons">
            <a href="admin.php/?adminLogOut"><ikon class="fas fa-sign-out-alt" id="login-btn"><p>logout</p></ikon></a>            
        </div>
    </header>
    <section class="welcome">
        <h3>welcome to car section</h3>
    </section>
    
    </section>
    <section class="vehicles2">
        <div class="box-container">
            <div class="box">
                <div class="content">
                    <h3> <i class="fas fa-car"></i> Add a new car </h3>    
                </div>
                <form action="admincars.php" method="post" enctype="multipart/form-data">
                <div class="inputBox">
                        <h3>License Plate</h3>
                        <input type="text" name="plate" placeholder="license plate">
                    </div>
                    <div class="inputBox">
                        <h3>Car Name</h3>
                        <input type="text" name="name" placeholder="Car Name">
                    </div>
                    <div class="inputBox">
                    <h3>Car Brand</h3>
                <select class="form-control" name="brand">
                                <option value="" selected> Brand</option>
                                <?php while ($row2 = mysqli_fetch_array($carBrandResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['bran_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                    </div>
                    <div class="inputBox">
                        <h3>Modify Status</h3>
                        <select class="form-control" name="modified">
                            <option value="" selected>  </option>
                            <option value="YES"> YES </option>
                            <option value="NO"> NO </option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <h3>Damage Status</h3>
                        <select class="form-control" name="damagestatus">
                            <option value="" selected>  </option>
                            <option value="VERY CLEAR"> VERY CLEAR </option>
                            <option value="CLEAR"> CLEAR </option>
                            <option value="DAMAGED"> DAMAGED </option>
                            <option value="HALF DAMAGED"> HALF DAMAGED </option>
                        </select>
                    </div>
                    <div class="inputBox">
                    <select class="form-control" name="gear">
                                <option value="" selected> Gear</option>
                                <?php while ($row2 = mysqli_fetch_array($carGearResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['gear_type']; ?></option>
                                <?php endwhile; ?>
                            </select>
                    </div>
                    <div class="inputBox">
                    <select class="form-control" name="type">
                                <option value="" selected> Type</option>
                                <?php while ($row2 = mysqli_fetch_array($carTypeResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['type']. " / ". $row2["speed"]; ?></option>
                                <?php endwhile; ?>
                            </select>
                    </div>
                    <div class="inputBox">
                    <select class="form-control" name="engine">
                                <option value="" selected> Engine</option>
                                <?php while ($row2 = mysqli_fetch_array($carEngineResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['engine_type']; ?></option>
                                <?php endwhile; ?>
                            </select>
                    </div>
                    <div class="inputBox">
                    <select class="form-control" name="color">
                                <option value="" selected> Color</option>
                                <?php while ($row2 = mysqli_fetch_array($carColorResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['color']." / ". $row2["metal_type"]; ?></option>
                                <?php endwhile; ?>
                            </select>
                    </div>
                    <div class="inputBox">
                    <select class="form-control" name="location">
                                <option value="" selected> Location</option>
                                <?php while ($row2 = mysqli_fetch_array($carLocationResult)): ?>
                                    <option value="<?php echo $row2['ID']; ?>"><?php echo $row2['location']; ?></option>
                                <?php endwhile; ?>
                            </select>
                    </div>
                    <div class="inputBox">
                        <h3>Car Price</h3>
                        <input type="text" name="price" placeholder="Car Price">
                    </div>
                    <div class="inputBox">
                        <h3>Car Year</h3>
                        <input type="text" name="year" placeholder="Car Year">
                    </div>
                    <div class="form-floating mb-3">
                            <div class="input-group file" id="carImage">
                                <input type="file" class="form-control" name="carImage" id="carImage">
                            </div>
                        </div>
                    <input type="submit" name="addCar" value="Add">
                </form> 
                    
            </div>
            
        </div>

    </section>
    <section class="vehicles" id="vehicles">
    <div class="box-container">

        <div class="box" style='padding:30px;'>
            <div class="row">
            <?php $carQuery = "SELECT * FROM car";
             $counter = 2;
            $cars = $conn->query($carQuery);
            if (!$cars) {
                die("Invalid Query: " . $connect->error);
            }
            while ($row = $cars->fetch_assoc()) {
                echo"<div class='box'>
                <img src=../images/".$row['image'].">
                <div class='content'>
                    <h3> <i class='fas fa-car'></i>".$row['name']." </h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, nam!</p>
                    <div class='price'> $".$row['pricing']." <color>$180.00</color> </div>
                    <a name='remove'  href=\"delete.php?id=".$row['ID']."\" class='btn'><p class='fas fa-trash'></p>Remove this car</a>  
                    <a name='update'  href=\"newupdate.php?id=".$row['ID']."\" class='btn'><p class='fas fa-trash'></p> Update Car</a>                 
                </div>   
                </div>";


            }?>
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
    <?php
if ($addingError) {
    echo "<script type='text/javascript'>alert('$addingError');</script>";
}
?>
</body>
<script src="../script.js"></script>
<script src="admin.js"></script>
