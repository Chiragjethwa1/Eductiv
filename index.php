<?php
    /*session_start();
	if (isset($_SESSION["username"]) or !empty($_SESSION["username"]) or !strlen((string) $_SESSION["username"]) <= 0) {
		header("location: /eductiv/dashboard");
	}*/
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Eductiv</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="asset/stylesheet/style.css">
        <script src="asset/script/jquery-3.5.1.min.js"></script>
        <script src="asset/script/Vibrant.js"></script>
        <script src="asset/script/main.js"></script>
        <script>
            getArticles(500,0,"null");
        </script>
    </head>

    <body>
        <div class="container">
            <div class="notification"></div>
                <div class="navigation">
                    <a href="#" class="brand"></a>

                    <nav class="menu">
                        <ul>
                            <li><a href="#" class="active bi bi-filter-square-fill">&nbsp;&nbsp;&nbsp;<span>Article</span></a></li>
                            <!-- <li><a href="#" class="bi bi-trophy-fill">&nbsp;&nbsp;&nbsp;<span>Course</span></a></li> -->
                            <!-- <li><a href="#" class="bi bi-people-fill">&nbsp;&nbsp;&nbsp;<span>People</span></a></li>
                            <li><a href="#" class="bi bi-info-circle-fill">&nbsp;&nbsp;&nbsp;<span>About</span></a></li> -->

                            <li style="margin-top: 100%;"><a href="#" class="bi bi-person-plus-fill">&nbsp;&nbsp;&nbsp;<span>Signup</span></a></li>
                            <li><a href="#" class="bi bi-shield-lock-fill">&nbsp;&nbsp;&nbsp;<span>Login</span></a></li>
                        </ul>
                    </nav>
            </div>

            <div class="content">
                <div class="full" style="z-index: 1;">
                    <div class="search-bar">
                        <input type="search" id="search-bar" placeholder="Search articles, courses, people....." autofocus>
                        <div class="search-result"></div>
                    </div>
                </div>

                <!-- Card sample -->
                <!-- 
                <div class="card-bg large" style="background-image: url(asset/image/img1.jpg);">
                    <div class="card" id="a1">
                        <a href="#" class="field">ENTERTAINMENT</a>
                        <a href="#" class="bookmark bi bi-bookmark"></a>
                        <a href="#"><h2 class="title">Marvel's Eternals Reveals The MCU's Newest (& Oldest) Super Team</h2></a>
                        <div class="keyword">
                            <a href="#">tv</a>
                            <a href="#">mcu</a>
                            <a href="#">tfatws</a>
                            <a href="#">anthonymackie</a>
                            <a href="#">sebastianstan</a>
                            <a href="#">marvelTV</a>
                            <a href="#">USAgent</a>
                        </div>
                        <div class="credit">
                            <a href="#"><div class="dp"></div>
                            <span class="user">Chirag Jethwa</a>
                            <p class="time">03 HOURS AGO</p>
                        </div>
                        <div class="reaction">
                                <span class="likes bi bi-heart">&nbsp;200K</span>
                                <span class="views bi bi-eye">&nbsp;20B
                                </span>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="modal" id="signup-modal">
                <div class="modal-body">
                    <button class="close bi bi-x"></button>
                    <form id="signup-form">
                        <div class="full" style="padding-right: 40px">
                            <h1 style="margin: 0;font-weight: 400;">Create Your<br>EDUctiv Account</h1>
                            
                            <ul style="margin-top:30px;font-size:0.9rem">
                            <li style="margin-bottom:5px">Username and Email address<br>must unique.</li>
                            <li style="margin-bottom:30px">Username and Password must be<br>
                            12 to 20 characters long.</li>
                            <li>You can help us enrich and improve content of this website by being a contributor by choosing the option<br><i>I Wish to Post/Contribute to This Site.</i></li>
                            <li></li></ul>
                        </div>

                        <!-- <img src="asset/image/logo.png" />
                        <p>Create your Account</p> -->

                        <fieldset>
                            <legend>
                                <label for="first-name-input">First Name</label>
                            </legend>
                            <input type="text" id="first-name-input" placeholder="Enter your first name" required>
                        </fieldset>

                        <fieldset>
                            <legend>
                                <label for="last-name-input">Last Name</label>
                            </legend>
                            <input type="text" id="last-name-input" placeholder="Enter your last name" required>
                        </fieldset>

                        <!-- <div class="" style="float: right;">ERRORS</div> -->

                        <fieldset>
                            <legend>
                                <label for="email-input">Email</label>
                            </legend>
                            <input type="email" id="email-input" placeholder="Enter your email address" required>
                        </fieldset>
                        <!-- <p style="position: absolute;">dsom</p> -->

                        <div>
                        <fieldset>
                            <legend>
                                <label for="username-input">Username</label>
                            </legend>
                            <input type="text" id="username-input" placeholder="Type a username" required>
                        </fieldset>
                    </div>
						<!--<div id="info"></div>-->

                        <fieldset>
                            <legend>
                                <label for="pwd-input">Password</label>
                            </legend>
                            <input type="password" id="pwd-input" placeholder="Enter password" required>
                            <span class="pwd-eye bi bi-eye"></span>
                        </fieldset>

                        <fieldset>
                            <legend>
                                <label for="pwd-cfm-input">Confirm Password</label>
                            </legend>
                            <input type="password" id="pwd-cfm-input" placeholder="Re-enter password" required><div class="pwd-eye bi bi-eye"></div>
                        </fieldset>

                        <!--<fieldset>
                            <legend>
                                <label for="bio-input">Bio</label>
                            </legend>
                            <textarea id="bio-input" placeholder="Tell us something about you"></textarea>
                        </fieldset> SHOULD BE ON PROFILE PAGE-->

                        <div>
                            <input type="checkbox" style="vertical-align: sub;height: 20px;min-width: 20px;" id="role-input" name="role" value="contributor"> <label for="role-input" style="font-size: 0.9rem;">I wish to post/contribute to this site.</label>
                        </div>

                        <div><input type="submit"value="Signup" /></div>
                    </form>
                </div>
            </div>

            <div class="modal" id="login-modal">
                <div class="modal-body">
                    <button class="close bi bi-x"></button>
                    <form id="login-form">
                        <div class="full">
                            <h1 style="margin: 0;font-weight: 400;">Login to Your<br>EDUctiv Account</h1>
                        </div>

                        <div>
                        <fieldset>
                            <legend>
                                <label for="username-lgn-input">Username</label>
                            </legend>
                            <input type="text" id="username-lgn-input" placeholder="Enter your username" required>
                        </fieldset>
                        </div>

                        <fieldset>
                            <legend>
                                <label for="pwd-lgn-input">Password</label>
                            </legend>
                            <input type="password" id="pwd-lgn-input" placeholder="Enter Your password" required>
                            <span class="pwd-eye bi bi-eye"></span>
                        </fieldset>

                        <div style="width: 335px;"><input type="submit" value="Login" /></div>
                    </form>
                </div>
            </div>

            <div class="modal" id="article-modal">
                <div class="modal-body">
                    <button class="close bi bi-x"></button>
                    <div class="article-container">
                        <div class="header">
                            <a href="#" class="field"></a>
                            <div class="title"></div>

                            <div class="sub-header">
                                <div class="credit">
                                    <a href="#" target="_blank"><div class="dp"></div></a>
                                    <div class="user"><a href="#" target="_blank"></a></div>
                                    <div class="time"></div>
                                </div>

                                <div class="reaction">
                                    <!-- <div class="view bi bi-eye">&nbsp;&nbsp;<span>6B</span></div>
                                    <div class="like"><button class="bi bi-heart">&nbsp;&nbsp;<span>200K</span></button></div> -->
                                    
                                    <div>
                                        <button id="article-keyword" class="bi bi-hash"></button>
                                    </div>

                                    <!-- <div class="flag">
                                    <button class="bi bi-flag">&nbsp;&nbsp;<span>5</span></button></div>-->
                                </div>

                                <div class="keywords">
                                    <div class="keyword-container">
                                        <a href="#">mcu</a>
                                        <a href="#">thefalcon</a>
                                    </div>
                                </div>
                            </div>

                        <div class="article-body">
                            <div class="segments"></div>
                            <!-- <div class="similars">More like this</div> -->
                        </div>
                    </div>
                </div>
			</div>
		</div>
        </div>
    </body>
</html>
