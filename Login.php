<?php
include_once 'Functions.php';
include_once 'EntityClassLib.php';
session_start();

extract($_POST);
$loginErrorMsg = '';
$studentIdErrorMsg = '';
$passwordErrorMsg = '';
$isValuddated = true;

//$password = $txtPswd;
$hashedPassword = hash("sha256", $password);

if (isset($btnLogin)) {
    $studentIdErrorMsg = ValidateStudentId($studentId);
    if (trim($studentIdErrorMsg) != null) {
        $isValuddated = false;
    }

    if (!trim($password)) {
        $passwordErrorMsg = 'password can not be blank';
        $isValuddated = false;
    }

    if ($isValuddated == true) {
        try {
            $user = getUserByIdAndPassword($studentId, $hashedPassword);
        } catch (Exception $e) {
            die("The system is currently not available, try again later");
        }
        if ($user == null) {
            $loginErrorMsg = 'Incorrect User ID and Password Combination!';
        } else {
            $_SESSION['user'] = $user;
            header("Location: CourseSelection.php");
            exit();
        }
    }
} else if (isset($btnClear)) {

    $loginErrorMsg = '';
    $studentIdErrorMsg = '';
    $passwordErrorMsg = '';

    $studentId = '';
    $password = '';
}

include("./common/header.php");
?>
<div class="container">
    <h1>Log In</h1>

    <p>If you are new, you need to <a href='NewUser.php'>register</a></p>
    <br/>
    <form action='Login.php' method='post'>
        <p style='color:Red'> <?php echo $loginErrorMsg; ?> </p>
        <table>
<!--            <tr><td col  span='4' style='color:Red'><?php echo $loginErrorMsg; ?></td></tr>-->
            <tr>
                <th>Student ID:</th>
                <td><input type='text' name='studentId' size='30' value="<?php print(isset($studentId) ? $studentId : ''); ?>"/>
                <td col  span='2' style='color:Red'><?php echo $studentIdErrorMsg; ?></td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <th>Password:</th>
                <td><input type='password' name='password' size='30' value="<?php print(isset($password) ? $password : ''); ?>"/></td>
                <td col  span='2' style='color:Red'><?php echo $passwordErrorMsg; ?></td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type='submit' name='btnLogin' value='Login' />&nbsp;&nbsp;
                    <input type='submit' name='btnClear' value='Clear' />
                </td>
            </tr>
        </table>
    </form>
</div>
<?php include('./common/footer.php'); ?>
