<?php
session_start()
?>
<!DOCTYPE html>

<html>
   <head>
       <title>
           Administrator's view |  Developers
       </title>
       <meta charset="utf-8">
       <meta name="description" content="Manage.php page to configure and view results and statistics">
       <link rel="stylesheet" href="style.css">
      </head>

       

   <body>
     
   <?php
	include("settings.php");

function sanitize_input($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
// Create connection
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDb);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "<p class='status'>Connected successfully</p>";
?>

  <?php
  if (isset($_SESSION["verified"])) {
    $verified = $_SESSION["verified"];
  }else {
    $verified = false;
  }
  if (!$verified) {
  ?> 
     <div class="opening-block"><div><h1>DEVELOPER'S MODE</h1>
  <p class="intro-message" >Manage & Analytics mode...</p>
  <p>*Requires administration rights and login credits to login*</p>
</div><div>
  <form class="password" action="manage.php" method="POST" >

  <label for="password">Username : </label> <input type="text" class="username" name="username" id="username"  placeholder="Username" /><label for="password">Password : </label>
    <input type="password" name="password" id="password"  placeholder="Secret key" />
    <br/>
    <input type="submit" id="auth" value="Authenticate" /><div>
</form>
<?php 
  }
?>
</div></div>
<?php
if (isset($_POST["password"])) {
  $password = sanitize_input($_POST["password"]);
  if (isset($_POST["username"])) {
  $username = sanitize_input($_POST["username"]);
  }
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `admin` (`adminId` bigint(20) NOT NULL, `username` varchar(255) NOT NULL, `password` varchar(255) NOT NULL);");

$sql = "SELECT adminId from admin where password = ? and username = ?;";
$result = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($result,'ss',$password,$username);
mysqli_stmt_execute($result);
$result = mysqli_stmt_get_result($result);
if (@mysqli_num_rows($result) > 0) {
  $_SESSION["verified"] = true;
  header('Location: '."manage.php");
}else{
  echo "<p class='warning message'>Sorry, unable to verify you as an authorised user</p>";
}
}
$didDelete = false;
if ($verified) {
	if (isset($_GET["student_delete"])) {
  $studentID_delete = sanitize_input($_GET["student_delete"]);
  if (!empty($studentID_delete)){
    $sql = "DELETE FROM attempts where studentId=?";
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'s',$studentID_delete);
    mysqli_stmt_execute($result);
 
    if ( mysqli_stmt_execute($result)) {
		$didDelete = true;      
    }
  }
}



  $sql = "SELECT students.studentId as ID, firstName, lastName,score FROM students inner join attempts on students.studentId = attempts.studentId;";
  $result = mysqli_query($conn, $sql);
?>
   </div>
 <div id="main">
 <h2>Developer's mode | Manage & Analytics</h2>

 <h3>All Attempts</h3>

   <?php
if (mysqli_num_rows($result) > 0) {
  // output data of each row
  ?>
 <table>
      <tr>
      <th>Student ID</th>
      <th>Full Name</th>
</tr>
  <?php
  foreach ($result as $row) {
    echo "<tr><td>#" . $row["ID"]. "</td><td>" . $row["firstName"]. " " . $row["lastName"]. "</td></tr>";
  }
} else {
  echo "<p class='warning'>There are no records of attempts so far...</p>";
}

?>
</table>
<br/>
<h3>Attempts done by a given student</h3>
<form action ="manage.php">
<p>Please enter the student ID of the student. It doesn't have to be the exact. What about the first 3 digits?</p>
<input type="text" name="student_attempts" placeholder="Enter student ID">
<select name="student_order">
<option value="">Sort by A-Z</option>
<option value="score">By Score (Highest)</option>
<option value="name">By Name</option>
<option value="id">By ID</option>
</select>
<input type="submit" value="Search">
</form>
<?php


if (isset($_GET["student_attempts"])) {
    $studentID = sanitize_input($_GET["student_attempts"]);
    $studentID = '%'.$studentID.'%';
    if (isset($_GET["student_order"]))
    $order = sanitize_input($_GET["student_order"]);
    switch ($order){
    case "score": 
    $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? ORDER BY score DESC";
    break;
    case "id": 
    $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? ORDER BY ID";
    break;
    case "name": 
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? ORDER BY students.firstName";
      break;
    default:
    $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ?";
    }
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'s', $studentID);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);
    
    if (mysqli_num_rows($result) > 0) {
      // output data of each row
      ?>

      <table>
          <tr>
          <th>Student ID</th>
          <th>Student Name</th>
          <th>Attempt Number</th>
          <th>Score</th>
    </tr>
      <?php
      foreach ($result as $row) {
        echo "<tr><td>#" . $row["ID"]. "</td><td>" . $row["firstName"]." ".$row["lastName"]. " </td><td>" . $row["attemptNum"]. " </td><td>" . $row["score"]. "</td></tr>";
      }
    } else {
      echo "<p class='warning' >There are no records so far...</p>";
    }
}
?>
</table>
<div><h3>Full Mark Students</h3></div>
<?php

$sql = "SELECT students.studentId as ID, firstName, lastName,score FROM students inner join attempts on students.studentId = attempts.studentId where score = 100 and attemptNum=1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  ?>

  <table>
      <tr>
      <th>Student ID</th>
      <th>Full Name</th>
</tr>
  <?php
  foreach ($result as $row) {
    echo "<tr><td>#" . $row["ID"]. "</td><td>" . $row["firstName"]. " " . $row["lastName"]. "</td></tr>";
  }
} else {
  echo "<p class='warning' >There are no records so far...</p>";
}



?>
</table>
<div><h3>Failed Boys</h3></div>
<?php


$sql = "SELECT students.studentId as ID, firstName, lastName,score FROM students inner join attempts on students.studentId = attempts.studentId where score < 50 and attemptNum = 2";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  // output data of each row
?>

  <table>
      <tr>
      <th>Student ID</th>
      <th>Full Name</th>
</tr>
  <?php
  foreach ($result as $row) {
    echo "<tr><td>#" . $row["ID"]. "</td><td>" . $row["firstName"]. " " . $row["lastName"]. "</td></tr>";
  } 
}
else
{
  echo "<p class='warning' >There are no records so far...</p>";
}



?>
</table>
<h3>Delete Attempts for a USER</h3>
<form action = "" method = "GET" >
<input type="text" name="student_delete" placeholder="Enter student ID to DELETE" />
<input type="submit" class="delete-btn" value="CONFIRM DELETE" />
</form>
<?php

if($didDelete){
    echo "<p class='success'>Query has been processed</p>";
}

?>
<h3>Change Score using StudentID, Attempt & the new score</h3>
<form action = "" method = "GET" >
<input type="text" name="student_change" placeholder="Enter student ID to CHANGE" />
<input type="number" name="student_attemptNum" max ="2" min="1" step="1" placeholder="Enter the Attempt" />
<input type="number" name="student_score" max ="100" min="0" step="1" placeholder="Enter the new score %" />
<br/><br/>
<input type="submit" class="delete-btn" value="CONFIRM CHANGE" />
</form>



<?php

if (isset($_GET["student_change"])) {
  $studentID_change = sanitize_input($_GET["student_change"]);
  if (isset($_GET["student_score"])) {
    $studentID_score = sanitize_input($_GET["student_score"]);
  }  
  if (isset($_GET["student_attemptNum"])) {
    $student_attemptNum = sanitize_input($_GET["student_attemptNum"]);
  } 
  
  if (!empty($studentID_change)){
    $sql = "UPDATE attempts SET score = ? WHERE  attemptNum =  ? and studentId = ? ;";
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'iis', $studentID_score,$student_attemptNum,$studentID_change);
    mysqli_stmt_execute($result);
 
    if ( mysqli_stmt_execute($result)) {
      echo "<p class='success'>Query has been processed</p>";
    }
  }
}
}
mysqli_close($conn);
?>
</div>
</body>
</html>
