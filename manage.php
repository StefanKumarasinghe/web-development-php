<?php
session_start()
?>
<!DOCTYPE html>
<?php
if (isset(($_SESSION["verified"]))){
  if ($_SESSION["verified"]) {
    setcookie('entries', 1, time() + (86400 * 30), "/"); // 86400 = 1 day
    $entries = 1;
  }
}
if (!(empty($_COOKIE['entries']))) {
 $entries = $_COOKIE['entries'];
}else {
  setcookie('entries', 1, time() + (86400 * 30), "/"); // 86400 = 1 day
}
if (empty($entries)) {
  $entries = 1;
}
function change() {
$entries = $_COOKIE['entries'];
$entries =  $entries + 1;
setcookie('entries', $entries, time() + (86400 * 30), "/"); // 86400 = 1 day
if ($entries > 5){
  header('Location: '."manage.php");
}

}

?>
<html lang="en">
   <head>
   <meta charset="utf-8">
        <meta name="description" content="Admin page of the website">
        <meta name="author" content="Developers">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/wwwlogo.jpg">
        <link rel="stylesheet" href="styles/Madmin.css" media="screen and (max-width:999px)">
        <link rel="stylesheet" href="styles/admin.css"  media="screen and (min-width:999px)">
        <title>Admin Mode | DEV</title>
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


  if ($entries>5) {
    echo "<div id='main'><h1>You have been locked out...</h1>
    <p>This is mainly because you have tried to login into the system multiple times and have failed all of them</p></div>";
  } else {


 

 

  if (isset($_SESSION["verified"])) {
    $verified = $_SESSION["verified"];
  }else {
    $verified = false;
  }
  if (!$verified) {
  ?> 
     <div class="opening-block">
       <div><h1>DEVELOPER'S MODE</h1>
  <p class="intro-message" >Manage & Analytics mode...</p>
  <p>*Requires administration rights and login credits to login*</p>
</div><div>
  <form class="password" action="manage.php" method="POST" >

  <label for="password">Username : </label> <input type="text" class="username" name="username" id="username"  placeholder="Username" /><label for="password">Password : </label>
    <input type="password" name="password" id="password"  placeholder="Secret key" />
    <input type="hidden" name="entries" value = <?php echo "'"."$entries"."'" ?> />
    <br/>
    <input type="submit" id="auth" value="Authenticate" />
</form>
</div>
</div>
<?php 
  }
?>
<?php
if (isset($_POST["password"])) {
  $password = sanitize_input($_POST["password"]);
  if (isset($_POST["username"])) {
  $username = sanitize_input($_POST["username"]);
  }
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `admin` (`adminId` int NOT NULL PRIMARY KEY, `username` varchar(255) NOT NULL, `password` varchar(255) NOT NULL);");
$sql = "SELECT adminId from admin where password = ? and username = ?;";
$result = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($result,'ss',$password,$username);
mysqli_stmt_execute($result);
$result = mysqli_stmt_get_result($result);
if (@mysqli_num_rows($result) > 0) {
  $_SESSION["verified"] = true;

  header('Location: '."manage.php");
}else{
  echo "<p class='warning message'>Sorry, unable to verify you as an authorised user. ".(5-$entries). " tries left..</p>";
  change();
  

}
}
$didDelete = false;

if ($verified) {
	if (isset($_GET["student_delete"])) {
  $studentID_delete = sanitize_input($_GET["student_delete"]);
  if (!empty($studentID_delete)){
    $sql = "DELETE FROM attempts where studentId= ?";
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
?>
</table>
<?php
} else {
  echo "<p class='warning'>There are no records of attempts so far...</p>";
}

?>
<br>
<h3>Attempts done by a given student</h3>
<form action ="manage.php">
<p>You can either type the first name or the student ID or both partially. This uses 4 "if-conditions" to see all possibilities</p>
<input type="text" name="studentname_attempts" placeholder="Student First Name">
<input type="text" name="student_attempts" placeholder="Enter student ID">

<select name="student_order">
<option value="">Sort by A-Z</option>
<option value="score">By Score (Highest)</option>
<option value="name">By Name</option>
</select>
<input type="submit" value="Search">
</form>
<?php
if ( (isset($_GET["studentname_attempts"])) or (isset($_GET["student_attempts"])))  {


    if (isset($_GET["student_order"]))
    $order = sanitize_input($_GET["student_order"]);
    if (!(empty($_GET["student_attempts"])) and !(empty($_GET["studentname_attempts"])) ) {
    $studentName = sanitize_input($_GET["studentname_attempts"]);
    $studentName = '%'.$studentName.'%';
    $id = '%'.sanitize_input($_GET["student_attempts"]).'%';

    switch ($order){
      case "score": 
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ?  and students.firstName LIKE ? ORDER BY score DESC";
      break;
      case "name": 
        $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? and students.firstName LIKE ? ORDER BY students.firstName";
        break;
      default:
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? and students.firstName LIKE ?";
      }
      $result = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($result,'ss',$id,$studentName);
  } else if (!(empty($_GET["student_attempts"])) and (empty($_GET["studentname_attempts"])) )
  {
    $id = '%'.sanitize_input($_GET["student_attempts"]).'%';
    switch ($order){
      case "score": 
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? ORDER BY score DESC";
      break;
      case "name": 
        $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ? ORDER BY students.firstName";
        break;
      default:
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where attempts.studentId LIKE ?";
      }
      $result = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($result,'s', $id);
   
  } else {
    $studentName = sanitize_input($_GET["studentname_attempts"]);
    $studentName = '%'.$studentName.'%';
    switch ($order){
      case "score": 
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where students.firstName LIKE ? ORDER BY score DESC";
      break;
      case "name": 
        $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where students.firstName LIKE ? ORDER BY students.firstName";
        break;
      default:
      $sql = "SELECT attempts.studentId as ID, students.firstName, students.lastName ,attemptNum,score from attempts inner JOIN students on students.studentId = attempts.studentId where students.firstName LIKE ?";
      }
      $result = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($result,'s', $studentName);
  }
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
	echo "</table>";
}
?>
<div><h3>Full Mark Students ( Attempt 1 )</h3></div>
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
  echo "</table>";
} else {
  echo "<p class='warning' >There are no records so far...</p>";
}



?>
<div><h3>Failed Students ( Final Attempt )</h3></div>
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
  echo "</table>";
}
else
{
  echo "<p class='warning' >There are no records so far...</p>";
}



?>
<h3>Delete Attempts for a USER</h3>
<form method = "GET" >
<input type="text" name="student_delete" placeholder="Enter student ID to DELETE" />
<input type="submit" class="delete-btn" value="CONFIRM DELETE" />
</form>
<?php

if($didDelete){
    echo "<p class='success'>Query has been processed</p>";
}

?>
<h3>Change Score using StudentID, Attempt & the new score</h3>
<form action="manage.php" method = "GET" >
<input type="text" name="student_change" placeholder="Enter student ID to CHANGE" />
<input type="number" name="student_attemptNum" max ="2" min="1" step="1" placeholder="Enter the Attempt" />
<input type="number" name="student_score" max ="100" min="0" step="1" placeholder="Enter the new score %" />
<br/><br/>
<input type="submit" class="delete-btn" value="CONFIRM CHANGE" />
</form>


</div>
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
}
mysqli_close($conn);
?>

</body>
</html>
