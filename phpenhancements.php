<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Enhancements page">
        <meta name="author" content="Developers">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/wwwlogo.jpg">
        <link rel="stylesheet" href="styles/Mstyle.css" media="screen and (max-width:999px)">
        <link rel="stylesheet" href="styles/style.css"  media="screen and (min-width:999px)">
        <title>Enhancements</title>
    </head>
    <body>
		<!-- Navigation bar -->
		<?php require("menu.inc"); ?>
		<!-- Yellow header banner -->
        <?php require("header.inc"); ?>
		<!-- Main content of page -->
        <main id="enhancements">
			<!-- Introduction to enhancements -->
            <h1>Php Enhancements</h1>
            <p>This page will explain all the extensions and enhancements that have been applied to the project. A reference has been given for each point</p>
		<h2>Key Enhancements</h2>
            <ul>
                <li>
                    <h3><em>Adminstration Login</em></h3>
                    <p>You will need the correct user password and username that matches one in the database and when it matches a session is created to allow you to view the webpage until the browser is open. By Default, the username is admin and the password is 1234</p>
                   <textarea cols="14" rows="10"> 
                         &#60;php
                         session_start()
                         ?>
                         &#60;php
                         if (isset($_SESSION["verified"])) {
                         $verified = $_SESSION["verified"];
                         }else {
                         $verified = false;
                         }
                         if (!$verified) {
                         ?> 
</textarea>
    <br />
    <a href="manage.php" class="reference" >Reference</a>
    <br />
			<!-- Admin mode demo -->
					<details>
                        <summary>Admin Page</summary>
                        <figure>
                            <img src="images/login.png" alt="login page">
                            <figcaption>The content is inaccessible unless the user knows the correct password and username</figcaption>
                        </figure>
                    </details>
                    <details>
                        <summary>Multiple Tries Lock</summary>
                        <figure>
                            <img src="images/lock.png" alt="lock page">
                            <figcaption>The user can only try 5 times before the system is locked and so this uses cookies. Extremely difficult and complex to code</figcaption>
                        </figure>
                    </details>
					<!-- Database model -->
                    <details>
                        <summary>Database Model</summary>
                        <figure>
                            <img src="images/table.png" alt="Table used to store admin details">
                            <figcaption>The structure/ table of the admin table used for checking</figcaption>
                        </figure>
                    </details>
			
                </li>
                <li>
					<!-- Relational Database section -->
                    <h3><em>Relational Database model</em></h3>
                    <p>The database is in the 3rd normal form and this is to create efficient and easier requests of data. This removes redunancy and partial dependancies and transistive dependancies and linked using primary and foreign keys</p>
			                    <textarea rows="14">SELECT students.studentId as ID, firstName, lastName,score FROM students inner join attempts on students.studentId = attempts.studentId;
                    </textarea>
                        <br />
        
                        <br />      
                    <details>
                        <summary>Database on MySQL</summary>
						<!-- SQL DATABASE -->
                        <figure>
                            <img src="images/relation.png" alt="Relational database">
                            <figcaption>Image of the table relation view</figcaption>
                        </figure>
						
                    </details>
                    <details>
                        <summary>Using Complex Searching algorithms</summary>
						<!-- SEARCH ALGORITHM -->
                        <figure>
                            <p>Using 4 if conditions and wild cards and the use of relational database, we are able to search records using either ID , name or both</p>
                            <img src="images/search.png" alt="Complex searching algorithm">
                            <figcaption>Image of the searching function in admin page</figcaption>
                        </figure>
						
                    </details>
                </li>
            </ul>
        </main>
		<!-- Page footer -->
		<?php require("footer.inc"); ?>
    </body>
</html>