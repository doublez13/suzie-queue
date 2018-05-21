<?php
//File: courses.php
// SPDX-License-Identifier: GPL-3.0-or-later

switch($_SERVER['REQUEST_METHOD']){
  case "GET":
    $stud_courses = get_stud_courses($username);
    if (is_null($stud_courses)){
      $return = my_course_list_error();
      http_response_code(500);
    }else{
      $return = array(
        "authenticated" => True,
        "student_courses" => $stud_courses,
        "ta_courses"      => $ta_courses
      );
      http_response_code(200);
    }
    break;

  case "POST":
    if (!isset($_POST['course'])){
      http_response_code(422);
      echo json_encode( missing_course() );
      die();
    }
    $course   = $_POST['course'];
    $acc_code = NULL;
    if (isset($_POST['acc_code'])){
      $acc_code = $_POST['acc_code'];
    }
    $res = add_stud_course($username, $course, $acc_code);
    if ($res < 0){
      $return = return_JSON_error($res);
      http_response_code(500);
    }else{
      $return = array(
        "authenticated" => True,
        "success" => "Student Course Added Successfully"
      );
      http_response_code(200);
    }
    break;

  case "DELETE":
    if (!isset($_GET['course'])){
      http_response_code(422);
      echo json_encode( missing_course() );
      die();
    }
    $course = $_GET['course'];
    $res = rem_stud_course($username, $course);
    if ($res < 0){
      $return = return_JSON_error($res);
      http_response_code(500);
    }
    else{
      $return = array(
        "authenticated" => True,
        "success" => "Student Course Removed Successfully"
      );
      http_response_code(200);
    }
    break;

  default:
    http_response_code(405);
    echo json_encode( invalid_method("GET, POST, DELETE") );
    die();
}

echo json_encode($return);
?>
