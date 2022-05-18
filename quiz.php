<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Quiz page">
        <meta name="author" content="Developers">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/wwwlogo.jpg">
        <link rel="stylesheet" href="styles/Mstyle.css" media="screen and (max-width:999px)">
        <link rel="stylesheet" href="styles/style.css"  media="screen and (min-width:999px)">
        <title>Quiz</title>
    </head>
    <body>
		<!-- Navigation bar -->
		<?php require("menu.inc"); ?>
		<!-- Yellow header banner -->
        <?php require("header.inc"); ?>
		<!-- Main content of page -->
        <main id="quiz-page">
            <h1>Quiz Time!</h1>
            <p>How long were you paying attention for?</p>
			<!-- Quiz section -->
            <form method="POST" action="markquiz.php" novalidate="novalidate" id="regform">
                <div class="flex-container-nowrap">
                    <div class="w-75">
                        <div class="w-75">
							<!-- Authentication section -->
                            <fieldset>
                                <legend>Authentication</legend>
                                <p>Please enter your personal details for submission</p>
                                <label class="inline-label" for="studentID">Student ID</label>   
                                <input type="text" id="studentID" placeholder="The ID must be between 7-10 digits" pattern="\d{7,10}" name="ID" required autofocus><br>
                                <label class="inline-label" for="firstname">First Name</label>  
                                <input type="text" id="firstname" placeholder="Please enter your first name" name="firstname" pattern="^[A-Z\- a-z]+$" maxlength="30" minlength="1" required><br>
                                <label class="inline-label" for="lastname">Last Name</label>  
                                <input type="text" id="lastname" placeholder="Please enter your last name" name="lastname" pattern="^[A-Z\- a-z]+$" maxlength="30" minlength="1" required><br>
                            </fieldset>
							<!-- Recap questions -->
                            <h2>Recap</h2>
                            <fieldset>
                                <legend>Text Based Questions</legend>
                                <label class="block-label questions" for="advantages1">Explain the advantages of Web 3.0 over Web 2.0</label>
                                <textarea rows="10" class="block" id="advantages1" placeholder="Please type the answer to question here..." name="advantages1"></textarea>
                            </fieldset>
							<!-- MCQ section -->
                            <fieldset>
                                <legend>MCQs for you</legend>
                                <p class="questions">When was Web 2.0 introduced?</p>
                                <input type="radio" value="1970" id="creation1" name="web2Creation">
								<label class="inline-label" for="creation1">1970</label>
                                <input type="radio" value="1999" id="creation2" name="web2Creation">
								<label class="inline-label" for="creation2">1999</label>
                                <input type="radio" value="2003" id="creation3" name="web2Creation">
								<label class="inline-label" for="creation3">2003</label>
                                <input type="radio" value="2006" id="creation4" name="web2Creation">
								<label class="inline-label" for="creation4">2006</label>
                                <p class="questions">What are characteristics of Web 2.0? Tick everything that applies</p>
                                <input type="checkbox" id="checkbox1" name="characteristics_c1" value="Rich user experience">
								<label for="checkbox1" class="inline-label">Rich user experience</label><br>
                                <input type="checkbox" id="checkbox2" name="characteristics_c2" value="User participation">
								<label for="checkbox2" class="inline-label">User participation</label><br>
                                <input type="checkbox" id="checkbox3" name="characteristics_c3" value="Read and write">
								<label for="checkbox3" class="inline-label">Read and write</label><br>
                                <input type="checkbox" id="checkbox4" name="characteristics_c4" value="Read only">
								<label for="checkbox4" class="inline-label">Read only</label><br>
                                <p class="questions" >Which from the below list is considered...</p>
                                <select name="dropdown" required>
                                    <option value="">Please select an option</option>
                                    <option value="userAccessibility">User accessibility</option>
                                    <option value="bandwidthRequirements">Bandwidth requirements</option>
                                    <option value="abilityToEdit">Ability to edit</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="w-75">
						<!-- Advanced questions part 1 -->
                        <fieldset>
                            <legend>Advance Questions Part 1</legend>
                            <label class="block-label questions" for="differences">What are the main differences between Web 1.0 and Web 2.0?</label>
                            <textarea rows="10" class="block" id="differences" placeholder="Please type the answer to question here..." name="differences"></textarea>
                        </fieldset>
						<!-- Advanced questions part 2 -->
                        <fieldset>
                            <legend>Advance Question 2</legend>
                            <p class="questions">When was Web 3.0 introduced?</p>
                            <input type="radio" value="1970" id="creation1_2" name="web3Creation">
							<label class="inline-label" for="creation1_2">1970</label>
                            <input type="radio" value="1987" id="creation2_2" name="web3Creation">
							<label class="inline-label" for="creation2_2">1987</label>
                            <input type="radio" value="2003" id="creation3_2" name="web3Creation">
							<label class="inline-label" for="creation3_2">2003</label>
                            <input type="radio" value="2006" id="creation4_2" name="web3Creation">
							<label class="inline-label" for="creation4_2">2006</label>
                            <p class="questions">What are functions of Web 3.0? Tick everything that applies</p>
                            <input type="checkbox" id="checkbox1_2" name="characteristics2_c1" value="Connectivity">
							<label for="checkbox1_2" class="inline-label">Connectivity</label><br>
                            <input type="checkbox" id="checkbox2_2" name="characteristics2_c2" value="3D Graphics">
							<label for="checkbox2_2" class="inline-label">3D Graphics</label><br>
                            <input type="checkbox" id="checkbox3_2" name="characteristics2_c3" value="Privacy">
							<label for="checkbox3_2" class="inline-label">Privacy</label><br>
                            <input type="checkbox" id="checkbox4_2" name="characteristics2_c4" value="Responsive design">
							<label for="checkbox4_2" class="inline-label">Responsive design</label><br>
                        </fieldset>
						<!-- Unit satisfaction questions -->
                        <fieldset>
                            <legend>Satisfactory test</legend>
                            <p>How confident are you in this unit</p>
                            <span class="label-rating">0&starf;</span>
							<input type="range" id="confidence" name="confidence" class="range-input" value="5" step="1" max="5" min="1">
							<span class="label-rating">5&starf;</span>
                            <p>Would you recommend this to your friend?</p>
                            <span class="label-rating">Nah</span>
							<input type="range" id="recommend" name="recommend" class="range-input" value="5" step="1" max="5" min="1">
							<span class="label-rating">Yes of Course!</span>
                        </fieldset>
                        <br>
                        <input type="submit" class="submit-btn" value="Submit">
						<input type="reset" class="submit-btn" value="Reset">
                    </div>
                </div>
            </form>
        </main>
		<!-- Page footer -->
		<?php require("footer.inc"); ?>
    </body>
</html>
