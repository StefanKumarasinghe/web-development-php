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

    $errMsg = "";
    if (!is_numeric($studentID)) {
        $errMsg .= "<p>Your Student ID must be a number.</p>\n";
    }
    if (!preg_match("/\d{7,10}/", $studentID)) {
        $errMsg .= "<p>Digits must be between 7-10.</p>\n";
    }
    if ($firstname == "") {
        $errMsg .= "<p>You must enter your first name.</p>\n";
    } elseif (!preg_match("/^[a-z-A-Z]*$/", $firstname)) {
        $errMsg .= "<p>Only alpha letters & hypen allowed in your first name.</p>\n";
    }
    if ($lastname == "") {
        $errMsg .= "<p>You must enter your last name.</p>\n";
    } elseif (!preg_match("/^[a-z-A-Z]*$/", $lastname)) {
        $errMsg .= "<p>Only alpha letters & hypen allowed in your last name.</p>\n";
    }
    // *****************************************************
    // ******* Quiz Scoring Function **********************************************
    // *****************************************************
	
	if($_POST["advantages1"] != ""){
		$q0 = 5;
	} else {
		$q0 = 0;
		$errMsg .= "<p>Please enter an answer for 'Explain the advantages of Web 3.0 over Web 2.0'.</p>\n";
	}
	
    if (isset($_POST["web2Creation"])) {
        $q1_answer = $_POST["web2Creation"];
    } else {
        $q1_answer = "";
		$errMsg .= "<p>Please enter an answer for 'When was Web 2.0 introduced?'.</p>\n";
    }
	
    if ($q1_answer == "1999") {
        $q1 = 20;
    } else {
        $q1 = 0;
    }
    if (isset($_POST["characteristics_c2"]) && isset($_POST["characteristics_c3"]) && isset($_POST["characteristics_c1"]) && isset($_POST["characteristics_c4"])) {
        $q2 = 0;
    } elseif (isset($_POST["characteristics_c2"]) && isset($_POST["characteristics_c3"]) && isset($_POST["characteristics_c1"])) {
        $q2 = 0;
	} elseif (isset($_POST["characteristics_c2"]) && isset($_POST["characteristics_c3"]) && isset($_POST["characteristics_c4"])) {
        $q2 = 0;
	} elseif (isset($_POST["characteristics_c2"]) && isset($_POST["characteristics_c3"])) {
        $q2 = 20;
	} elseif (isset($_POST["characteristics_c2"]) or isset($_POST["characteristics_c3"])) {
        $q2 = 10;
    } else {
        $q2 = 0;
    }
    $q3_answer = $_POST["dropdown"];
    if ($q3_answer == "abilityToEdit") {
        $q3 = 15;
    } else {
		if($q3_answer == ""){
			$errMsg .= "<p>Please enter an answer for 'Which from the below list is considered...'.</p>\n";
		}
        $q3 = 0;
    }
	
	if($_POST["differences"] != ""){
		$q4 = 5;
	} else {
		$q4 = 0;
		$errMsg .= "<p>Please enter an answer for 'What are the main differences between Web 1.0 and Web 2.0?'.</p>\n";
	}
	
    if (isset($_POST["web3Creation"])) {
        $q5_answer = $_POST["web3Creation"];
    } else {
        $q5_answer = "";
		$errMsg .= "<p>Please enter an answer for 'When was Web 3.0 introduced?'.</p>\n";
    }

    if ($q5_answer == "2006") {
        $q5 = 15;
    } else {
        $q5 = 0;
    }
	
	if (isset($_POST["characteristics2_c2"]) && isset($_POST["characteristics2_c3"]) && isset($_POST["characteristics2_c1"]) && isset($_POST["characteristics2_c4"])) {
        $q5 = 0;
    } elseif (isset($_POST["characteristics2_c2"]) && isset($_POST["characteristics2_c3"]) && isset($_POST["characteristics2_c1"])) {
        $q5 = 0;
	} elseif (isset($_POST["characteristics2_c2"]) && isset($_POST["characteristics2_c3"]) && isset($_POST["characteristics2_c4"])) {
        $q5 = 0;
	} elseif (isset($_POST["characteristics2_c2"]) && isset($_POST["characteristics2_c3"])) {
        $q5 = 25;
	} elseif (isset($_POST["characteristics2_c2"]) or isset($_POST["characteristics2_c3"])) {
        $q5 = 15;
    } else {
        $q5 = 0;
    }

    // *****************************************************
    // ******* Result Display **********************************************
    // *****************************************************
    $overallScore = $q0 + $q1 + $q2 + $q3 + $q4 + $q5 + $q6;
	$quizResult = $overallScore < 50 ? "FAILED" : "PASSED";
	
	if($overallScore == 0){
		$errMsg .= "<p>Your score was 0. Please try again</p>\n";
	}
	
	if ($errMsg != "") {
		$errMsg .= "<p><a href=\"quiz.php\">Try Again</a></p>\n";
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
		Overall Score: $overallScore% - $quizResult <br>\n
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