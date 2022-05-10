<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Mark Quiz" />
	<meta name="keywords" content="HTML" />
	<meta name="author" content="Peter Luong" />
	<title>Mark Quiz</title>
</head>
</html>

<body>
	<h1>Quiz Marking</h1>

<?php
include("settings.php");
function sanitise_input($data) // Santise Function
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// *****************************************************
// ******* Personal Details Submission **********************************************
// *****************************************************
if (isset($_POST["ID"])) {
    $studentID = $_POST["ID"];
    //   echo "<p>StudentID: $studentID</p>";
} else {
    //if process not triggered by a form submit
    header("location: quiz.php");
}
if (isset($_POST["firstname"])) {
    $firstname = $_POST["firstname"];
    //   echo "<p>First Name: $firstname</p>";
} else {
    //if process not triggered by a form submit
    header("location: quiz.php");
}
if (isset($_POST["lastname"])) {
    $lastname = $_POST["lastname"];
    //    echo "<p>Last Name: $lastname</p>";
} else {
    //if process not triggered by a form submit
    header("location: quiz.php");
}
// *****************************************************
// ******* Sanitising & Error Message Output Function **********************************************
// *****************************************************
$studentID = sanitise_input($studentID);
$firstname = sanitise_input($firstname);
$lastname = sanitise_input($lastname);

$errMsg = "";
if (is_numeric($studentID) == false) {
    $errMsg .= "<p>Your Student ID must be a number.</p>";
}
if ($firstname == "") {
    $errMsg .= "<p>You must enter your first name.</p>";
} elseif (!preg_match("/^[a-z-A-Z]*$/", $firstname)) {
    $errMsg .= "<p>Only alpha letters & hypen allowed in your first name.</p>";
}
if ($lastname == "") {
    $errMsg .= "<p>You must enter your last name.</p>";
} elseif (!preg_match("/^[a-z-A-Z]*$/", $lastname)) {
    $errMsg .= "<p>Only alpha letters & hypen allowed in your last name.</p>";
}
// *****************************************************
// ******* Quiz Scoring Function **********************************************
// *****************************************************
if (isset($_POST["web3Creation"])) {
    $q1_answer = $_POST["web3Creation"];
} else {
    $q1_answer = "";
}
if ($q1_answer == "1999") {
    $q1 = 25;
} else {
    $q1 = 0;
}
if (isset($_POST["characteristics_c2"]) && isset($_POST["characteristics_c3"])) {
    $q2 = 20;
} elseif (isset($_POST["characteristics_c2"]) or isset($_POST["characteristics_c3"])) {
    $q2 = 10;
} else {
    $q2 = 0;
}
$q3_answer = $_POST["dropdown"];
if ($q3_answer == "dropdown_correct") {
    $q3 = 15;
} else {
    $q3 = 0;
}
if (isset($_POST["web3Creation2"])) {
    $q4_answer = $_POST["web3Creation2"];
} else {
    $q4_answer = "";
}
if ($q4_answer == "2006") {
    $q4 = 15;
} else {
    $q4 = 0;
}
if (isset($_POST["characteristics2_c2"]) && isset($_POST["characteristics2_c3"])) {
    $q5 = 25;
} elseif (isset($_POST["characteristics2_c2"]) or isset($_POST["characteristics2_c3"])) {
    $q5 = 15;
} else {
    $q5 = 0;
}
// *****************************************************
// ******* Result Display **********************************************
// *****************************************************
$overallScore = $q1 + $q2 + $q3 + $q4 + $q5;

if ($errMsg != "") {
    echo "<p>$errMsg</p>";
	return;
}
echo "<p>Student ID: $studentID<br>
	First Name: $firstname<br>
	Last Name: $lastname<br>
	Overall Score: $overallScore%<br>
	Attempts:<br>
	<p><a href=\"quiz.php\">Try Again?</a></p></p>";

$db = new mysqli($dbHost, $dbUser, $dbPass, $dbDb);
$db->query("CREATE TABLE IF NOT EXISTS students (studentId VARCHAR(32), firstName VARCHAR(32), lastName VARCHAR(32), PRIMARY KEY (studentId));");
$db->query("CREATE TABLE IF NOT EXISTS attempts (attemptId INT NOT NULL AUTO_INCREMENT, studentId VARCHAR(32), attemptTime DATETIME, attemptNum INT, score INT, PRIMARY KEY (attemptId), FOREIGN KEY (studentId) REFERENCES students(studentId));");

$stmt1 = $db->prepare("SELECT studentId FROM students WHERE studentId=?");
$stmt1->bind_param("s", $studentID);
$stmt1->execute();
$result1 = $stmt1->get_result();
if($result1->num_rows == 0){
	$stmt2 = $db->prepare("INSERT INTO students (studentId, firstName, lastName) VALUES (?, ?, ?)");
	$stmt2->bind_param("sss", $studentID, $firstname, $lastname);
	$stmt2->execute();
}
$stmt3 = $db->prepare("SELECT COUNT(*) FROM attempts WHERE studentId=?");
$stmt3->bind_param("s", $studentID);
$stmt3->execute();
$attempts = $stmt3->get_result()->fetch_row()[0];
if($attempts == 2){
	echo("You have already submitted 2 attempts.");
	return;
}

$stmt4 = $db->prepare("INSERT INTO attempts (studentId, attemptTime, attemptNum, score) VALUES (?, ?, ?, ?)");
$date = date("Y-m-d H:i:s");
$stmt4->bind_param("ssii", $studentID, $date, $attempts, $overallScore);
$stmt4->execute();

mysqli_close($db);

?>
</body>