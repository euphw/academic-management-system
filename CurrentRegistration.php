<?php
include_once 'Functions.php';
include_once 'EntityClassLib.php';
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: Login.php");
    exit();
}

$user = $_SESSION['user'];
$loginName = $user->getName();
$loginId = $user->getUserId();
$errorMsg = '';
extract($_POST);

if (isset($btnDelete)) {
    if (!isset($deleteSelected)) {
        $errorMsg = 'You select 0 course.';
    }

    foreach ($deleteSelected as $d) {
        DeleteRegistration($loginId, $d);
    }
}
if (isset($btnClear)) {
    header("Location: CurrentRegistration.php");
}
include("./common/header.php");
?>
<div class="container">
    <h1>Course Registration</h1>
    <p>Hello <?php echo $loginName; ?>! (not you? change user <a href="Logout.php">here</a>), the followings are your current registrations</p>
    <p style="color: red"><?php
        echo $errorMsg;
        ?></p>
    <form action="CurrentRegistration.php" method="POST">
        <table class="table table-striped">
            <thead>
                <tr scope="row">
                    <th scope="col">Year</th>
                    <th scope="col">Term</th>
                    <th scope="col">Course Code</th>
                    <th scope="col">Course Title</th>
                    <th scope="col">Hours</th>
                    <th scope="col">Select</th>
                </tr>
            </thead>  
            <tbody>
                <?php
                $semesters = getAllSemester();
                foreach ($semesters as $s) {
                    $registration = getRegistration($loginId, $s['SemesterCode']);
                    $hours = 0;
                    foreach ($registration as $r) {
                        $hours = $hours + $r['WeeklyHours'];
                        echo '<tr><td>' . $r['Year'] . '</td>';
                        echo '<td>' . $r['Term'] . '</td>';
                        echo '<td>' . $r['CourseCode'] . '</td>';
                        echo '<td>' . $r['Title'] . '</td>';
                        echo '<td>' . $r['WeeklyHours'] . '</td>';
                        echo '<td><input type="checkbox" name="deleteSelected[ ]" value="' . $r['CourseCode'] . '"/></td></tr>';
//                         echo '<tr><td>' . $r->getYear(). '</td>';
//                        echo '<td>' . $r->getTerm() . '</td>';
//                        echo '<td>' . $r['CourseCode'] . '</td>';
//                        echo '<td>' . $r['Title'] . '</td>';
//                        echo '<td>' . $r['WeeklyHours'] . '</td>';
//                        echo '<td><input type="checkbox" name="deleteSelected[ ]" value="' . $r['CourseCode'] . '"/></td></tr>';
                    }
                    if ($hours != 0) {
                        echo '<tr><td></td><td></td><td></td><td></td><td>Toal weekly Hours ' . $hours . '</td><td></td>';
                    }
                }
                ?>
            </tbody>
            <tr scope="row">
                <th scope="col">&nbsp;</th>
                <td scope="col" >&nbsp;</td>
                <td scope="col" >&nbsp;</td>
                <td scope="col" >&nbsp;</td>
                <td scope="col">
                    <input type='submit' name='btnDelete' value='DeleteSelected'onclick="return confirm('The selected registrations will be deleted!')" />&nbsp;&nbsp;
                    <input type='submit' name='btnClear' value='Clear' />
                </td>
                <td scope="col">&nbsp;</td>
        </table>
    </form>
    <p><?php
//                echo count($semesters);
//                echo count($registration);
//                echo $hours;
        ?></p>
</div>
<?php include('./common/footer.php'); ?>
