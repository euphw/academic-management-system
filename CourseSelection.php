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
extract($_POST);
$errorMsg = '';

//$registration = getRegistration($loginId);

if (isset($btnSelect)) {

    $_SESSION["semester"] = $semester;
    $registration = getRegistration($loginId, $semester);
    $registrationCode = array();
    foreach ($registration as $r) {
        //if ($r['SemesterCode'] == $_SESSION["semester"]) {
        $hours = $hours + $r['WeeklyHours'];
        $registrationCode[] = $r['CourseCode'];
        //}
    }
    $_SESSION["hours"] = $hours;
    //$_SESSION["registrationCode"] = $registrationCode;

    $courseOffer = getCourseOffer($semester);
    $availableCourse = array_diff($courseOffer, $registrationCode);

    $dispalyCourse = array();

    foreach ($availableCourse as $d) {
        $dispalyCourse[] = getAllCourses($d);
    }
    $_SESSION["dispalyCourse"] = $dispalyCourse;
}

if (isset($btnSubmit)) {
    $isValuddated = false;
    foreach ($courseSelected as $cs) {
        $hoursSelected = $hoursSelected + getWeeklyHoursByCode($cs);
    }

    if (!isset($courseSelected)) {
        $errorMsg = "You needs select at least one course!";
    } elseif ($hoursSelected + $_SESSION["hours"] > 16) {
        $errorMsg = "Your selection exceed the max weekly hours";
    } else {
        $isValuddated = true;
        foreach ($courseSelected as $cs) {
            addRegistration($loginId, $cs, $_SESSION["semester"]);
        }
        //$registration = getRegistration($loginId);
        $registration = getRegistration($loginId, $_SESSION["semester"]);
        $registrationCode = array();
        foreach ($registration as $r) {
            //if ($r['SemesterCode'] == $_SESSION["semester"]) {
            $hours = $hours + $r['WeeklyHours'];
            $registrationCode[] = $r['CourseCode'];
            //}
        }
        $_SESSION["hours"] = $hours;
        $courseOffer = getCourseOffer($semester);
        $availableCourse = array_diff($courseOffer, $registrationCode);

        $dispalyCourse = array();

        foreach ($availableCourse as $d) {
            $dispalyCourse[] = getAllCourses($d);
        }
        $_SESSION["dispalyCourse"] = $dispalyCourse;
    }
}


if (isset($btnClear)) {
    header("Location: CourseSelection.php");
}


include("./common/header.php");
?>
<div class="container">
    <h1>Course Selection</h1>
    <p>Welcome <?php echo $loginName; ?>! (not you? change user <a href="Logout.php">here</a>)</p>
    <p>You have registered <?php
        if ($_SESSION["hours"] == null) {
            echo 0;
        } else {
            echo $_SESSION["hours"];
        }
        ?> hours for the selected semester</p>
    <p>You can register <?php echo 16 - $_SESSION["hours"]; ?> more hours of course(.) for the semester</p>
    <p>Please note that Ins courses you have registered will not be displayed in he list</p>
</div>
<div class="container">
    <form action="CourseSelection.php" method="POST">
        <div style='margin-left: 45rem'>
            <select name='semester' id='semester' onchange="getSemester()">
                <option>Select your semester...</option>
                <?php
                $semesters = getAllSemester();
                foreach ($semesters as $s) {
                    echo '<option value="' . $s['SemesterCode'] . '" ';

                    if ($_SESSION["semester"] == $s["SemesterCode"]) {
                        echo 'selected';
                    }
                    echo '>' . $s['Year'] . ' ' . $s['Term'] . '</option>';
                }
                ?>

            </select>

            <input type='submit' id='SemesterSelected' name='btnSelect' hidden />
        </div>
        <p style="color: red"><?php
            echo $errorMsg;
            ?></p>
        <table class="table table-striped">
            <thead>
                <tr scope="row">
                    <th scope="col">Code</th>
                    <th scope="col" >Course Title</th>
                    <th scope="col">Hours</th>
                    <th scope="col">Select</th>
                </tr>
            </thead>  
            <tbody>

                <?php
                foreach ($_SESSION["dispalyCourse"] as $c) {
                    echo '<tr><td>' . $c->getCourseCode() . '</td>';
                    echo '<td>' . $c->getTitle() . '</td>';
                    echo '<td>' . $c->getWeeklyHour() . '</td>';
                    echo '<td><input type="checkbox" name="courseSelected[ ]" value="' . $c->getCourseCode() . '"/></td></tr>';
                }
                ?>
            </tbody>
            <tr scope="row">
                <th scope="col">&nbsp;</th>
                <td scope="col" >&nbsp;</td>
                <td scope="col">
                    <input type='submit' name='btnSubmit' value='Submit' />&nbsp;&nbsp;
                    <input type='submit' name='btnClear' value='Clear' />
                </td>
                <td scope="col">&nbsp;</td>
        </table>
    </form>
</div>
<?php include('./common/footer.php'); ?>
<script>
    function getSemester() {
        $('#SemesterSelected').click();
    }
</script>
