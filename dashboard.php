<?php
    session_start();
	if (!isset($_SESSION["username"]) or empty($_SESSION["username"]) or strlen((string) $_SESSION["username"]) <= 0) {
		header("location: /eductiv");
	}
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
        $(document).ready(function() {
            $("#postCourse").click(function() {
                $("#course-post-modal").css("visibility", "visible");
            });
        });
        </script>
    </head>

    <body>
        <div class="container">
            <div class="notification"></div>

            <div class="navigation">
                <a href="#" class="brand"></a>

                <nav class="menu">
                    <ul>
                        <li><a href="#" class="bi bi-vector-pen">&nbsp;&nbsp;&nbsp;<span>Post</span></a></li>
                        <li><a href="#" class="bi bi-grid-fill">&nbsp;&nbsp;&nbsp;<span>Browse</span></a></li>
                        <li><a href="#" class="bi bi-grid-1x2-fill active">&nbsp;&nbsp;&nbsp;<span>Dashboard</span></a></li>
                        <!-- <li><a href="#" class="bi bi-bookmarks-fill">&nbsp;&nbsp;&nbsp;<span>Saved</span></a></li>
                        <li><a href="#" class="bi bi-info-circle-fill">&nbsp;&nbsp;&nbsp;<span>About</span></a></li> -->
                    </ul>
                </nav>
            </div>

            <div class="content">
                <div class="full" style="z-index: 1;">
                    <div class="field-bar">
                        <div class="left">
                            <ul class="field-items">
                                <li><button class="bi bi-filter-square-fill active" href="#">&nbsp;&nbsp;&nbsp;<span>Article</span></button></li>
                                <li><button id="postCourse" class="bi bi-trophy-fill">&nbsp;&nbsp;&nbsp;<span>Course</span></button></li>
                                <!-- <li><button class="bi bi-people-fill" href="#">&nbsp;&nbsp;&nbsp;<span>People</span></button></li> -->

                                <li class="right user-menu"><button  class="user-status bi- bi-person-fill" style="padding: 0;border-radius: 100%;color: #FF304F;"></button>
                                <div class="user-option">
                                    <div><?php echo $_SESSION["username"]; ?></div>
                                    <div id="logout">Logout</div>
                                </div>
                                </li>

                                <!-- <li class="right user-field">
                                    <button style="padding: 10px 0px 10px 30px;">Cinematography<span class="bi bi-chevron-down"></span></button>
                                <div class="field-option">
                                    <div>My Account</div>
                                    <div>Computer Science</div>
                                </div>
                                </li> -->

                                <!-- <li class="right"><button class="filter bi bi-funnel-fill" href="#"></button>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
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
                </div>

                <div class="card-bg" style="background-image: url(asset/image/img3.jpg);">
                    <div class="card">
                        <a href="#" class="field">COMPUTER SCIENCE</a>
                        <a href="#" class="bookmark bi bi-bookmark"></a>
                        <a href="#" title="title/desc"><h2 class="title">The Falcon & Winter Soldier's Emily VanCamp Was Shocked Sharon Carter Wasn't Pardoned</h2></a>
                        <div class="credit">
                            <a href="#"><div class="dp bi bi-person-fill" style="background-image: url();"></div>
                            <span class="user">Deepak Parmar</a>
                            <p class="time">03 HOURS AGO</p>
                        </div>
                        <div class="reaction">
                                <span class="likes bi bi-heart">&nbsp;40</span>
                                <span class="views bi bi-eye">&nbsp;6B</span>
                        </div>
                    </div>
                </div>

                <div class="card-bg wide" style="background-image: url(asset/image/img3.jpg);">
                    <div class="card">
                        <a href="#" class="field">COMPUTER SCIENCE</a>
                        <a href="#" class="bookmark bi bi-bookmark"></a>
                        <a href="#" title="title/desc"><h2 class="title">The Falcon & Winter Soldier's Emily VanCamp Was Shocked Sharon Carter Wasn't Pardoned</h2></a>
                        <div class="credit">
                            <a href="#"><div class="dp"></div>
                            <span class="user">Mit Pandya</a>
                            <p class="time">03 HOURS AGO</p>
                        </div>
                        <div class="reaction">
                                <span class="likes bi bi-heart">&nbsp;200k</span>
                                <span class="views bi bi-eye">&nbsp;20
                                </span>
                        </div>
                    </div>
                </div>

                <div class="card-bg" style="background-image: url(asset/image/img3.jpg);">
                    <div class="card">
                        <a href="#" class="field">COMPUTER SCIENCE</a>
                        <a href="#" class="bookmark bi bi-bookmark"></a>
                        <a href="#" title="title/desc"><h2 class="title">The Falcon & Winter Soldier's Emily VanCamp Was Shocked Sharon Carter Wasn't Pardoned</h2></a>
                        <div class="credit">
                            <a href="#"><div class="dp"></div>
                            <span class="user">Deepak Parmar</a>
                            <p class="time">03 HOURS AGO</p>
                        </div>
                        <div class="reaction">
                                <span class="likes bi bi-heart">&nbsp;40</span>
                                <span class="views bi bi-eye">&nbsp;6B</span>
                        </div>
                    </div>
                </div>

                <div class="full">
                    <div>FOOTER</div>
                </div>-->
            </div>

            <div class="modal" id="article-modal">
                <div class="modal-body">
                    <button class="close bi bi-x"></button>
                    <div class="article-container">
                        <div class="header">
                            <a href="#" class="field">ENTERTAINMENT</a>
                            <div class="title">The Falcon & Winter Soldier's Emily VanCamp Was Shocked Sharon Carter Wasn't Pardoned</div>

                            <div class="sub-header">
                                <div class="credit">
                                    <a href="#" target="_blank"><div class="dp"></div></a>
                                    <div class="user"><a href="#" target="_blank">John Doe</a></div>
                                    <div class="time">published an hour ago</div>
                                </div>

                                <div class="reaction">
                                    <div class="view bi bi-eye">&nbsp;&nbsp;<span>6B</span></div>
                                    <div class="like"><button class="bi bi-heart">&nbsp;&nbsp;<span>200K</span></button></div>
                                    
                                    <div>
                                    <button id="article-keyword" class="bi bi-hash"></button>
                                    </div>

                                    <!-- <div class="flag">
                                    <button class="bi bi-flag">&nbsp;&nbsp;<span>5</span></button></div> -->

                                    <?php
                                        if (isset($_SESSION["username"]) or !empty($_SESSION["username"]) or !strlen((string) $_SESSION["username"]) <= 0) {
                                            echo '<div class="edit">
                                            <button class="bi bi-pencil-square">&nbsp;&nbsp;<span>Edit</span></button></div>';
                                        }
                                    ?>
                                </div>

                                <div class="keywords">
                                    <div class="keyword-container">
                                        <a href="#">mcu</a>
                                        <a href="#">thefalcon</a>
                                    </div>
                                </div>
                            </div>

                        <div class="article-body">
                            <div class="segments">
                            </div>
                            <!-- <div class="similars">More like this</div> -->
                        </div>
                    </div>
                </div>
			</div>

			</div>

            <div class="modal" id="post-modal">
                <div class="modal-body">
                    <button class="close bi bi-x"></button>
                    <div class="form-container">
                        <form id="post-form">
                            <textarea id="article-title-input" maxlength="90" name="title" placeholder="Title of Article" required></textarea>
                            
                            <div class="row-two">
                                <div class="field-keyword">
                                    <select name="field" id="article-field-input" required>
                                        <option value="" disabled selected>Select a Field</option>
                                    </select>
                                    <input type="search" id="article-keyword-input" placeholder="Feature Related Keywords">
                                    <div class="keywords">&nbsp;&nbsp;&nbsp;&nbsp;No Keywords Featured</div>
                                </div>
                                <label for="article-img-input">
                                    <div class="article-image">
                                        <div class="img-preview"></div>
                                        <span class="bi bi-image-fill">&nbsp;&nbsp;Add a Related Image</span>
                                        <input type="file" name="article-image" id="article-img-input" accept="image/*" style="display: none;">
                                    </div>
                                    <small>Featured image will fit automatically (no need to adjust)</small>
                                </label>
                            </div>

                            <div class="article-body">
                                <div class="article-content-area">
                                </div>
                                <div class="widgets">
                                    <button type="button" class="bi bi-textarea-t" data-wdgt="t"></button>
                                    <button type="button" class="bi bi-image" data-wdgt="i"></button>
                                    <button type="button" class="bi bi-camera-video-fill" data-wdgt="v"></button>
                                    <button type="button" class="bi bi-file-earmark-plus-fill" data-wdgt="f"></button>
                                    <button type="button" class="bi bi-link-45deg" data-wdgt="l"></button>
                                </div>

                            </div>

                            <div class="action">
                                <input type="submit">
                                <div><label for="status" style="float:right">Save as Draft </label><input type="checkbox" id="status" style="width: 20px;height: 20px;margin-right: 10px;" name="status" value="DRAFT"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" id="course-post-modal">
                <div class="modal-body">
                    <button class="close bi bi-x"></button>
                    <div class="form-container">
                        <form id="course-post-form">
                            <textarea id="article-title-input" maxlength="90" name="title" placeholder="Course Title" required></textarea>
                            
                            <div class="row-two">
                                <div class="field-keyword">
                                    <select name="field" id="article-field-input" required>
                                        <option value="" disabled selected>Select a Field</option>
                                        <option value="CHESS" >CHESS</option>
                                        <option value="SPORTS" >SPORTS</option>
                                        <option value="COMPUTER SCIENCE" >COMPUTER SCIENCE</option>
                                    </select>
                                    <input type="search" id="article-keyword-input" placeholder="Feature Related Keywords">
                                    <div class="keywords">&nbsp;&nbsp;&nbsp;&nbsp;No Keywords Featured</div>
                                </div>
                                <label for="article-img-input">
                                    <div class="article-image">
                                        <div class="img-preview"></div>
                                        <span class="bi bi-image-fill">&nbsp;&nbsp;Add a Related Image</span>
                                        <input type="file" name="course-image" id="article-img-input" accept="image/*" style="display: none;">
                                    </div>
                                    <small>Featured image will fit automatically (no need to adjust)</small>
                                </label>
                            </div>

                            <div class="article-body">
                                <div class="article-content-area">
                                </div>
                                <div class="widgets">
                                    <button type="button" class="bi bi-textarea-t" data-wdgt="t"></button>
                                    <!-- <button type="button" class="bi bi-image" data-wdgt="i"></button> -->
                                    <button type="button" class="bi bi-camera-video-fill" data-wdgt="v"></button>
                                    <!-- <button type="button" class="bi bi-file-earmark-plus-fill" data-wdgt="f"></button> -->
                                    <button type="button" class="bi bi-link-45deg" data-wdgt="l"></button>
                                </div>

                            </div>

                            <div class="action">
                                <input type="submit">
                                <div><label for="status" style="float:right">Save as Draft </label><input type="checkbox" id="status" style="width: 20px;height: 20px;margin-right: 10px;" name="status" value="DRAFT"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
