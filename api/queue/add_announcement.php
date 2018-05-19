<?php
// File: add_announcement.php
// SPDX-License-Identifier: GPL-3.0-or-later

if ($_SERVER['REQUEST_METHOD'] !== "POST")
{
  http_response_code(405);
  echo json_encode( invalid_method("POST") );
  die();
}

if (!isset($_SESSION['username']))
{
  http_response_code(401);
  echo json_encode( not_authenticated() );
  die();
}

if (!isset($_POST['course']))
{
  http_response_code(422);
  echo json_encode( missing_course() );
  die();
}

if (!isset($_POST['announcement']))
{
  http_response_code(422);
  echo json_encode( missing_announcement() );
  die();
}

$username     = $_SESSION['username'];
$course       = $_POST['course'];
$announcement = $_POST['announcement'];
$ta_courses   = $_SESSION["ta_courses"];

if (!in_array($course, $ta_courses))
{
  http_response_code(403);
  echo json_encode( not_authorized() );
  die();
}

$announcement = filter_var($announcement, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

$res = add_announcement($course, $announcement, $username);
if($res < 0)
{
  $return = return_JSON_error($res);
  http_response_code(500);
}else
{
  $return = array(
    "authenticated" => True,
    "success" => "Announcement set"
  );
  http_response_code(200);
}

echo json_encode($return);
?>
