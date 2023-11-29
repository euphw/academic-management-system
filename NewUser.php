<?php
include_once "Functions.php";
include_once "EntityClassLib.php";
session_start();

extract($_POST);

$nameErrorMsg = '';
$studentIdErrorMsg = '';
$phoneErrorMsg = '';
$passwordErrorMsg = '';
$againErrorMsg = '';
$isValuddated = true;

if (isset($btnSubmit)) {

    $nameErrorMsg = ValidateName($name);
    $studentIdErrorMsg = ValidateStudentId($studentId);
    $phoneErrorMsg = ValidatePhone($phone);
    $passwordErrorMsg = ValidatePassword($password);

    if (trim($nameErrorMsg) != null) {
        $isValuddated = false;
    }

    if (trim($studentIdErrorMsg) != null) {
        $isValuddated = false;
    }

    if (trim($phoneErrorMsg) != null) {
        $isValuddated = false;
    }

    if (trim($passwordErrorMsg) != null) {
        $isValuddated = false;
    }

   
    $_SESSION["name"] = $name;
    $_SESSION["studentId"] = $studentId;
    $_SESSION["phone"] = $phone;
    $_SESSION["password"] = $password;
    $_SESSION["passwordAgain"] = $passwordAgain;
    $hashedPassword=hash("sha256",$password);
//     if (!isset(POST[$passwordAgain])) {
//        $againErrorMsg = 'password again can not be blank';
//        $isValuddated = false;
//    }else{
//        $againErrorMsg = 'blank';
//    }
//    
 
    if (!trim($passwordAgain)) {
        $againErrorMsg = 'Password again can not be blank';
        $isValuddated = false;
    } elseif ($passwordAgain != $password){
        $againErrorMsg = 'Password do not match';
       $isValuddated = false;
    }else{
        $againErrorMsg = '';
    }

    
    try {
        $checkUser = checkUserById($studentId);
        if ($checkUser == 1) {
            $studentIdErrorMsg = "A Student with this ID has already signed up";
            $isValuddated = false;
        }
    } catch (Exception $e) {
        die("The system is currently not available, try again later");
    }


    if ($isValuddated == true) {
        $_SESSION["infoValudated"] = $isValuddated;
        try {
            addNewUser($studentId, $name, $phone, $hashedPassword);
            header("Location: CourseSelection.php");
            exit();
        } catch (Exception $e) {
            die("The system is currently not available, try again later");
        }
       
        exit();
    }


    if (isset($name)) {
        $nameValue = $name;
    } else {
        $nameValue = '';
    }

    if (isset($studentId)) {
        $studentIdValue = $studentId;
    } else {
        $studentIdValue = '';
    }

    if (isset($phone)) {
        $phoneValue = $phone;
    } else {
        $phoneValue = '';
    }
    
    if (isset($password)) {
        $passwordValue = $password;
    } else {
        $passwordValue = '';
    }
    
    if (isset($passwordAgain)) {
        $passwordAgainValue = $passwordAgain;
    } else {
        $passwordAgainValue = '';
    }
} else if (isset($btnReset)) {

    $nameErrorMsg = '';
    $studentIdErrorMsg = '';
    $phoneErrorMsg = '';
    $passwordErrorMsg = '';
    $againErrorMsg = '';

    $nameValue = '';
    $studentIdValue = '';
    $phoneValue = '';
    $passwordValue = '';
    $passwordAgainValue='';
} else {
    if (isset($_SESSION["name"])) {
        $nameValue = $_SESSION["name"];
    }
    if (isset($_SESSION["studentId"])) {
        $studentIdValue = $_SESSION["studentId"];
    }
    if (isset($_SESSION["phone"])) {
        $phoneValue = $_SESSION["phone"];
    }
    if (isset($_SESSION["password"])) {
        $passwordValue = $_SESSION["password"];
    }
    if (isset($_SESSION["passwordAgain"])) {
        $passwordAgainValue = $_SESSION["passwordAgain"];
    }
}



include("./common/header.php");
?>
<div class="container">
    <h1>Sign Up</h1>
    <p>All fields are required</p>
    <hr>
    <form action="NewUser.php" method="POST">
        <div class="row">
            <div class="col-md-3">
                <label>Student ID:</label>
            </div>
            <div class="col-md-3"> 
                <input type="text" class="form-control inputText" name="studentId" value="<?php echo $studentIdValue ?>">
            </div>
            <div class="col-md-6 error">
                <?php echo'<p>' . $studentIdErrorMsg . '</p>'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label>Name:</label>
            </div>
            <div class="col-md-3"> 
                <input type="text" class="form-control inputText" name="name" value="<?php echo $nameValue ?>">
            </div>
            <div class="col-md-6 error">
                <?php echo'<p>' . $nameErrorMsg . '</p>'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label>Phone Number:</label>
            </div>
            <div class="col-md-3"> 
                <input type="tel" class="form-control inputText" name="phone" value="<?php echo $phoneValue ?>">
            </div>
            <div class="col-md-6 error">
                <?php echo'<p>' . $phoneErrorMsg . '</p>'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label>Password:</label>
            </div>
            <div class="col-md-3"> 
                <input type="password" class="form-control inputText" name="password" value="<?php echo $passwordValue ?>">
            </div>
            <div class="col-md-6 error">
                <?php echo'<p>' . $passwordErrorMsg . '</p>'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label>Password Again:</label>
            </div>
            <div class="col-md-3"> 
                <input type="password" class="form-control inputText" name="passwordAgain" value="<?php echo $passwordAgainValue ?>">
            </div>
            <div class="col-md-6 error">
                <?php echo'<p>' . $againErrorMsg . '</p>'; ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3 ">
                <input type='submit' class="btn btn-primary" name='btnSubmit' value='Submit' />&nbsp;&nbsp;
                <input type='submit' class="btn btn-primary" name='btnReset' value='Clear' />&nbsp;&nbsp;
            </div>
        </div>


    </form>
</div>
<?php include('./common/footer.php'); ?>
