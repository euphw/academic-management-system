<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

//-------- Sign up validation --------
function ValidateName($name) {
    if (!trim($name)) {
        $nameErrorMsg = 'The name can not be blank';
    } else {
        $nameErrorMsg = '';
    }
    return $nameErrorMsg;
}

function ValidateStudentId($studentId) {
    if (!trim($studentId)) {
        $studentIdErrorMsg = 'The student Id can not be blank';
    } else {
        $studentIdErrorMsg = '';
    }
    return $studentIdErrorMsg;
}

function ValidatePhone($phone) {
    $pattern = '/^[2-9]\d\d-[1-9][1-9][1-9]-\d\d\d\d$/';
    if (!trim($phone)) {
        $phoneErrorMsg = 'Phone number can not be blank';
    } elseif (preg_match($pattern, $phone) == 1) {
        $phoneErrorMsg = '';
    } else {
        $phoneErrorMsg = 'Incorrect phone number';
    }
    return $phoneErrorMsg;
}

function ValidatePassword($password) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/';
    if (!trim($password)) {
        $passwordErrorMsg = 'password can not be blank';
    } elseif (preg_match($pattern, $password) == 1) {
        $passwordErrorMsg = '';
    } else {
        $passwordErrorMsg = 'Password is at least 6 characters long, contains at least one upper case, one
lowercase and one digit.';
    }
    return $passwordErrorMsg;
}

//-------- Sign up validation --------
//---------------- PDO ----------------

function getPDO() {
    $dbConnection = parse_ini_file("Lab5.ini");
    extract($dbConnection);
    return new PDO($dsn, $scriptUser, $scriptPassword);
}

function checkUserById($userId) {
    $pdo = getPDO();

//    $sql = "SELECT * FROM `Student` WHERE StudentId ='$userId'";
//    $resultSet = $pdo->query($sql);
    $sql = "SELECT * FROM `Student` WHERE StudentId =:userId";
    $resultSet = $pdo->prepare($sql);
    $resultSet->execute(['userId'=>$userId]);
    if ($resultSet) {
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return 1;
        } else {
            return 0;
        }
    } else {
        throw new Exception("Query failed! SQL statement: $sql");
    }
}

function getUserByIdAndPassword($userId, $password) {
    $pdo = getPDO();

//    $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = '$userId' AND Password = '$password'";
//    $resultSet = $pdo->query($sql);
    $sql = "SELECT StudentId, Name, Phone FROM Student WHERE StudentId = :userId AND Password = :password";
    $resultSet = $pdo->prepare($sql);
    $resultSet->execute(['userId'=>$userId,'password'=>$password]);
    if ($resultSet) {
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['StudentId'], $row['Name'], $row['Phone']);
        } else {
            return null;
        }
    } else {
        throw new Exception("Query failed! SQL statement: $sql");
    }
}

function addNewUser($userId, $name, $phone, $password) {
    $pdo = getPDO();

    $sql = "INSERT INTO Student (StudentId, Name, Phone, Password) VALUES( '$userId', '$name', '$phone', '$password')";
    $pdoStmt = $pdo->query($sql);
}

function getAllCourses($CourseCode) {
    $pdo = getPDO();

    $sql = "SELECT CourseCode, Title, WeeklyHours FROM Course WHERE CourseCode='$CourseCode'";

    $resultSet = $pdo->query($sql);
    //return $resultSet;
    if ($resultSet) {
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Course($row['CourseCode'], $row['Title'], $row['WeeklyHours']);
        } else {
            return null;
        }
    } else {
        throw new Exception("Query failed! SQL statement: $sql");
    }
    //$courses = array();
//    foreach ($resultSet as $row) {
//        $courses[] = $row["CourseCode"] . ' - ' . $row["Title"];
//    }
//    return $courses;
}

function getWeeklyHoursByCode($CourseCode) {
    $pdo = getPDO();

    $sql = "SELECT WeeklyHours,CourseCode FROM Course WHERE CourseCode = '$CourseCode'";

    $resultSet = $pdo->query($sql);
    foreach ($resultSet as $row) {
        $hours = $row["WeeklyHours"] + $hours;
    }

    return $hours;
}

function getAllSemester() {
    $pdo = getPDO();

    $sql = "SELECT SemesterCode, Year, Term FROM Semester";

    $resultSet = $pdo->query($sql);
//   return $resultSet;
    $semesters = array();

    foreach ($resultSet as $row) {
        // $semesters[] = $row["Year"] . ' ' . $row["Term"];
        $semesters[] = $row;
    }
    return $semesters;
}

function getCourseOffer($semesterCode) {
    $pdo = getPDO();

    //$sql = "SELECT CourseOffer.CourseCode, CourseOffer.SemesterCode, Course.Title, Course.WeeklyHours FROM CourseOffer JOIN Course ON CourseOffer.CourseCode=Course.CourseCode AND CourseOffer.SemesterCode='$semesterCode'";
    $sql = "SELECT CourseCode FROM CourseOffer WHERE SemesterCode='$semesterCode'";

    $resultSet = $pdo->query($sql);
    //return $resultSet;
    $courses = array();

    foreach ($resultSet as $row) {
        $courses[] = $row["CourseCode"];
    }
    return $courses;
}

function getRegistration($studentId, $semesterCode) {
    $pdo = getPDO();

    $sql = "SELECT Registration.StudentId, Registration.CourseCode, Registration.SemesterCode, Course.Title, Course.WeeklyHours, Semester.Year, Semester.Term FROM Registration JOIN Course ON Registration.CourseCode=Course.CourseCode JOIN Semester ON Semester.SemesterCode=Registration.SemesterCode AND Registration.StudentId='$studentId' AND Registration.SemesterCode='$semesterCode' ";
    //$sql = "SELECT CourseCode FROM Registration WHERE StudentId='$studentId' AND SemesterCode='$semesterCode' ";

    $resultSet = $pdo->query($sql);
    //return $resultSet;
    $semesters = array();

    foreach ($resultSet as $row) {
        // $semesters[] = $row["Year"] . ' ' . $row["Term"];
        $semesters[] = $row;
    }
    return $semesters;
}

function addRegistration($studentId, $courseCode, $semesterCode) {
    $pdo = getPDO();

    $sql = "INSERT INTO Registration (StudentId, CourseCode, SemesterCode) VALUES( '$studentId', '$courseCode', '$semesterCode')";
    $pdoStmt = $pdo->query($sql);
}

function DeleteRegistration($studentId, $courseCode) {
    $pdo = getPDO();

    $sql = "DELETE FROM Registration WHERE StudentId = '$studentId' AND CourseCode = '$courseCode'";
    $pdoStmt = $pdo->query($sql);
}


