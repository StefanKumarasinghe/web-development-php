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
} elseif (isset($_POST["characteristics2_c3"]) or isset($_POST["characteristics2_c3"])) {
    $q5 = 15;
} else {
    $q5 = 0;
}
// *****************************************************
// ******* Result Display **********************************************
// *****************************************************
$OverallScore = $q1 + $q2 + $q3 + $q4 + $q5;

if ($errMsg != "") {
    echo "<p>$errMsg</p>";
} else {
    echo "<p>StudentID: $studentID <br/>
		First Name: $firstname <br/>
		Last Name: $lastname <br/>
		Overall Score: $OverallScore% <br/>
		Attempts: <br/>
		<p><a href=\"quiz.php\">Try Again?</a></p></p>";
}
?>
</body>