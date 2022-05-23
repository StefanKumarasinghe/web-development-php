<?php
session_start()
?>
<!DOCTYPE html>

<html>
   <head>
       <title>
           Administrator's view |  Developers
       </title>
       <meta charset="utf-8" />
       <meta name="description" content="Manage.php page to configure and view results and statistics" />
   <link rel="stylesheet" href="style.css" />
      </head>

       

   <body>
     
   <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "<p class='status'>Connected successfully</p>";
?>
   <div class="opening-block"><div><h1>DEVELOPER'S MODE</h1>
  <p class="intro-message" >Manage & Analytics mode...</p></div><div>
  <?php
  if (isset($_SESSION["verified"])) {
    $verified = $_SESSION["verified"];
  }else {
    $verified = false;
  }
  if (!$verified) {
  ?> 
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
  $password = $_POST["password"];
  if (isset($_POST["username"])) {
  $username = $_POST["username"];
  }
$sql = "SELECT adminId from admin where  password_hash = '$password' and username = '$username';";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {

  $_SESSION["verified"] = true;
  header('Location: '."manage.php");

}else{
  echo "<p class='warning message'>Sorry, unable to verify you as an authorised user</p>";
}
}

if ($verified) {
  $sql = "SELECT students.studentId as ID, firstName, lastName,score FROM students inner join attempts on students.studentId = attempts.studentId   ";
  $result = mysqli_query($conn, $sql);
?>
   </div>
 
 <div id ="main">
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
  echo "<p class='warning' >There are no records of attempts so far...</p>";
}

?>
</table>
<br/>
<h3>Attempts done by a given student</h3>
<form action ="">
  
<input type="text" name="student_attempts" placeholder="Enter student ID" />
<input type="submit" value="Search" />
</form>
<?php


if (isset($_GET["student_attempts"])) {
    $studentID=$_GET["student_attempts"];
    $sql = "SELECT studentId as ID, attemptNum,score FROM attempts where studentId = '$studentID'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
      // output data of each row
      ?>

      <table>
          <tr>
          <th>Student ID</th>
          <th>Attempt Number</th>
          <th>Score</th>
    </tr>
      <?php
      foreach ($result as $row) {
        echo "<tr><td>#" . $row["ID"]. "</td><td>" . $row["attemptNum"]. " </td><td>" . $row["score"]. "</td></tr>";
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

if (isset($_GET["student_delete"])) {
  $studentID_delete = $_GET["student_delete"];
  if (!empty($studentID_delete)){
    
    $sql = "DELETE FROM attempts where studentId='".$studentID_delete."'";
    if (mysqli_query($conn, $sql)) {
      echo "<p class='success'>Records have been successfully deleted. Refresh the page to see changes</p>";
      header('Location: '."manage.php");
      
    }
  }
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
  $studentID_change = $_GET["student_change"];
  if (isset($_GET["student_score"])) {
    $studentID_score = $_GET["student_score"];
  }  
  if (isset($_GET["student_attemptNum"])) {
    $student_attemptNum = $_GET["student_attemptNum"];
  } 
  
  if (!empty($studentID_change)){
    
    $sql = "UPDATE attempts SET score = '$studentID_score' WHERE  attemptNum =  '$student_attemptNum' and studentId = '$studentID_change' ;";
    $result = mysqli_query($conn, $sql);
    if (mysqli_query($conn, $sql)) {
      echo "<p class='warning'>Records have been successfully deleted. Refresh the page to see changes</p>";
    }
  }
}

}
mysqli_close($conn);
?>
</div>
</body>
</html>
