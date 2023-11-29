<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class User {
    private $userId;
    private $name;
    private $phone;
    
    private $messages;
    
    public function __construct($userId, $name, $phone)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        
        $this->messages = array();
    }
    
    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhone() {
        return $this->phone;
    }
}

class Course {
    private $courseCode;
    private $title;
    private $weeklyHour;
    //private $semesterCode;
    
    
    
    public function __construct($courseCode, $title, $weeklyHour)
    {
        $this->courseCode = $courseCode;
        $this->title = $title;
        $this->weeklyHour = $weeklyHour;
        //$this->semesterCode = $semesterCode;
        
    }
    
    public function getCourseCode() {
        return $this->courseCode;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getWeeklyHour() {
        return $this->weeklyHour;
    }
    
//    public function getSemesterCode() {
//        return $this->semesterCode;
//    }
}

