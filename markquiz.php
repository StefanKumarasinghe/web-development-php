<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Main introduction page for site">
        <meta name="author" content="Developers">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/wwwlogo.jpg">
        <link rel="stylesheet" href="styles/Mstyle.css" media="screen and (max-width:999px)">
        <link rel="stylesheet" href="styles/style.css"  media="screen and (min-width:999px)">
        <title>Home</title>
    </head>
    <body>
		<!-- Navigation bar -->
		<?php require "menu.inc"; ?>
		<!-- Yellow header banner -->
        <header>
            <div>
                <p class="header-title">Quiz Marking</p>
            </div>
        </header>
		<main>
<?php

function sanitise_input($data){
    // Santise Function
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function handleForm() {
	include "settings.php";
    // *****************************************************
    // ******* Personal Details Submission **********************************************
    // *****************************************************
    if (isset($_POST["ID"])) {
        $studentID = $_POST["ID"];
        //echo "<p>StudentID: $studentID</p>";
    } else {
        // if process not triggered by a form submission
        header("location: quiz.php");
    }
    if (isset($_POST["firstname"])) {
        $firstname = $_POST["firstname"];
        //echo "<p>First Name: $firstname</p>";
    } else {
        // if process not triggered by a form submission
        header("location: quiz.php");
    }
    if (isset($_POST["lastname"])) {
        $lastname = $_POST["lastname"];
        //echo "<p>Last Name: $lastname</p>";
    } else {
        // if process not triggered by a form submission
        header("location: quiz.php");
    }
    // *****************************************************
    // ******* Sanitising & Error Message Output Function **********************************************
    // *****************************************************
    $studentID = sanitise_input($studentID);
    $firstname = sanitise_input($firstname);
    $lastname = sanitise_input($lastname);

    $error = false;
    $errMsg = "";
    if (is_numeric($studentID) == false) {
        $errMsg .= "<p>Your Student ID must be a number.</p>\n";
        $error = true;
    }
    if (!preg_match("/\d{7,10}/", $studentID)) {
        $errMsg .= "<p>Digits must be between 7-10.</p>\n";
        $error = true;
    }
    if ($firstname == "") {
        $errMsg .= "<p>You must enter your first name.</p>\n";
        $error = true;
    } elseif (!preg_match("/^[a-z-A-Z]*$/", $firstname)) {
        $errMsg .= "<p>Only alpha letters & hypen allowed in your first name.</p>\n";
        $error = true;
    }
    if ($lastname == "") {
        $errMsg .= "<p>You must enter your last name.</p>\n";
    } elseif (!preg_match("/^[a-z-A-Z]*$/", $lastname)) {
        $error = true;
        $errMsg .= "<p>Only alpha letters & hypen allowed in your last name.</p>\n";
        $error = true;
    }
    if ($error == true) {
        $errMsg .= "<p><a href=\"quiz.php\">Try Again</a></p>\n";
        $error = false;
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
    $passorfailed = "";
    if ($overallScore <= 50) {
        $passorfailed = "FAILED";
    } else {
        $passorfailed = "PASSED";
    }

    if ($errMsg != "") {
        echo $errMsg;
        return;
    }

	$db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDb);
    mysqli_query($db, "CREATE TABLE IF NOT EXISTS students (studentId VARCHAR(32), firstName VARCHAR(32), lastName VARCHAR(32), PRIMARY KEY (studentId));");
    mysqli_query($db, "CREATE TABLE IF NOT EXISTS attempts (attemptId INT NOT NULL AUTO_INCREMENT, studentId VARCHAR(32), attemptTime DATETIME, attemptNum INT, score INT, PRIMARY KEY (attemptId), FOREIGN KEY (studentId) REFERENCES students(studentId));");
	
	$stmt1 = mysqli_stmt_init($db); // check if student record exists
	mysqli_stmt_prepare($stmt1, "SELECT studentId FROM students WHERE studentId=?");
	mysqli_stmt_bind_param($stmt1, "s", $studentID);
	mysqli_stmt_execute($stmt1);
	mysqli_stmt_bind_result($stmt1, $result1);
	mysqli_stmt_fetch($stmt1);
	mysqli_stmt_close($stmt1);
	
    if ($result1 == null) { // no student record yet
		$stmt2 = mysqli_stmt_init($db);
		mysqli_stmt_prepare($stmt2, "INSERT INTO students (studentId, firstName, lastName) VALUES (?, ?, ?)");
		mysqli_stmt_bind_param($stmt2, "sss", $studentID, $firstname, $lastname);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_close($stmt2);
    }
	
	$stmt3 = mysqli_stmt_init($db); // check if student record exists
	mysqli_stmt_prepare($stmt3, "SELECT COUNT(*) FROM attempts WHERE studentId=?");
	mysqli_stmt_bind_param($stmt3, "s", $studentID);
	mysqli_stmt_execute($stmt3);
	mysqli_stmt_bind_result($stmt3, $attempts);
	mysqli_stmt_fetch($stmt3);
	mysqli_stmt_close($stmt3);	
	
	$attempts += 1;

    if ($attempts == 3) {
        echo "You have already submitted 2 attempts.";
        return;
    }
    echo "<h2>Personal Details</h2>";
    echo "<p>Student ID: $studentID<br>\n
		First Name: $firstname<br>\n
		Last Name: $lastname<br>\n
		Overall Score: $overallScore% - $passorfailed <br>\n
		Attempts: $attempts<br>\n";
    echo "<p class=\"tryagain\"><a href=\"quiz.php\">Try Again?</a></p>\n";
	
	$stmt4 = mysqli_stmt_init($db); // insert new attempt
	mysqli_stmt_prepare($stmt4, "INSERT INTO attempts (studentId, attemptTime, attemptNum, score) VALUES (?, ?, ?, ?)");
	$date = date("Y-m-d H:i:s");
	mysqli_stmt_bind_param($stmt4, "ssii", $studentID, $date, $attempts, $overallScore);
	mysqli_stmt_execute($stmt4);
	mysqli_stmt_close($stmt4);	
	
    mysqli_close($db);
}
handleForm();
?>
		</main>
		<!-- Page footer -->
		<?php require "footer.inc"; ?>
	</body>
</html>