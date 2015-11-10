<?php
require_once './checkLogin.php';
require_once './config/dbconnect.php';
require_once './functions/functions.php';
$query = "select r.id,r.`roomNumber`,r.floor from room r left join ordertb ot "
        . "on r.id = ot.roomId where ot.is_held IS NULL or ot.is_held = 0 ;";
$result2 = mysqli_query($connection, $query);
$query = "select datediff(endDate,startDate) as dateDiffBtnStartAndEndr,datediff(`endDate`, "
        . "date(now())) as dateDiffBtnEndAndCurr, `startDate`,`endDate`,roomId from ordertb;";
$result3 = mysqli_query($connection, $query);
while ($dateCapture = mysqli_fetch_array($result3)) {
    $dateDiffStartEnd = $dateCapture['dateDiffBtnStartAndEndr'];
    $dateDiffEndCurr = $dateCapture['dateDiffBtnEndAndCurr'];
    if (!($dateDiffStartEnd > 0 && $dateDiffEndCurr > 0 )) {
        $roomId = $dateCapture['roomId'];
        $updateQery = "UPDATE `hotel_db`.`ordertb` SET `is_held` = '0' WHERE ordertb.`roomId` = $roomId ;";
        mysqli_query($connection, $updateQery);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // checks if button value is set
    // if (!empty($_POST['surname']) && !empty($_POST['firstname']) && !empty($_POST['middlename'])) {
    $firstname = clean_input($connection, $_POST['firstname']);
    $middlename = clean_input($connection, $_POST['middlename']);
    $surname = clean_input($connection, $_POST['surname']);
    $phonenumber = clean_input($connection, $_POST['phonenumber']);
    $email = clean_input($connection, $_POST['email']);
    $gender = clean_input($connection, $_POST['Gender']);
    $roomId = clean_input($connection, $_POST['room']);
    $startDate = clean_input($connection, $_POST['startDate']);
    $endDate = clean_input($connection, $_POST['endDate']);
    $startDatex = date_create($startDate);
    $endDatex = date_create($endDate);
    $dateDiffStartandEnd = date_diff($startDatex, $endDatex);
    $dateDiffStartandEnd = $dateDiffStartandEnd->format("%R%a");
    $currentDate = date("Y-m-d");
    $currentDate = date_create($currentDate);
    $dateDiffCurrEnd = date_diff($currentDate, $endDatex);
    $dateDiffCurrEnd = $dateDiffCurrEnd->format("%R%a");
    if ($dateDiffStartandEnd > 0 && $dateDiffCurrEnd >= 0) {
        $query = "select email from customer where email='$email';";
        $result = mysqli_query($connection, $query);
        $numberOfEmails = mysqli_num_rows($result);
        if ($numberOfEmails > 0) {
            $emailExist = "customer with this email exist";
        } else {
            $query = "insert into customer (firstname,middlename,surname,phone_number,email,gender) values ('$firstname','$middlename','$surname','$phonenumber','$email','$gender')"; // insert customer info into customer table
            $result = mysqli_query($connection, $query);
            if ($result)
                $success = 'Customer was added';
        }
        $query = "select  id from customer where email = '$email';";
        $resultSet = mysqli_query($connection, $query);
        $customer = mysqli_fetch_array($resultSet);
        $customerId = $customer['id'];
        $query = "insert into ordertb (id, `customerId`, `roomId`, `startDate`, `endDate`,is_held) values (NULL , $customerId,$roomId , '$startDate', '$endDate',1);"; // adds a new customer and set is_held column to 1
        mysqli_query($connection, $query);
        $query = "select r.id,r.`roomNumber`,r.floor from room r left join ordertb ot "
                . "on r.id = ot.roomId where ot.is_held IS NULL or ot.is_held = 0 ;";
        $result2 = mysqli_query($connection, $query);
    }else {
        $dateError = "Please set valid date interval";
        $firstnamex = $firstname;
        $middlenamex = $middlename;
        $surnamex = $surname;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hotel System</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
            <p><?php echo 'Hi '.$_SESSION['loggedIn']; ?> | <a href="./logout.php">Logout</a></p> 
            <p style="color: red;"><?php if (isset($error)) echo $error; ?></p>
            <p style="color: green;"><?php if (isset($success)) echo $success; ?></p>
            <p style="color: red;"><?php if (isset($emailExist)) echo $emailExist; ?></p>
            <p style="color: red;"><?php if (isset($dateError)) echo $dateError; ?></p>

            <form method="POST" autocomplete="on">
                <p>First Name</p>
                <input type="text" name="firstname" value="<?php if(isset($firstnamex)) echo $firstname; ?>" />
                <p>Middle Name</p>
                <input type="text" name="middlename" value="" />
                <p>Surname</p>
                <input type="text" name="surname"  />
                <p>Phone Number</p>
                <input type="text" name="phonenumber"/>
                <p>Email</p>
                <input type="email" name="email" />
                <p>gender</p>
                <input type="radio" name="Gender" value="Male"/> Male<br>
                <input type="radio" name="Gender" value="Female" checked="" /> Female
                <p>Rooms</p>
                <select name="room">
                    <?php while ($data = mysqli_fetch_array($result2)) { ?>
                        <option value="<?php echo $data['id'] ?>"><?php echo 'Room #' . $data['roomNumber'] . ': ' . $data['floor'] . ' floor'; ?></option>
                    <?php } ?>
                </select><br>
                <p>Start Date</p>
                <input type="date" name="startDate" required=""/>
                <p>End Date</p>
                <input type="date" name="endDate" required=""/>
                <p></p>
                <input type="submit" value="Register"/>
            </form>
        </div>
    </body>
</html>