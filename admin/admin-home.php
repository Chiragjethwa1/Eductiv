<?php
	require("../api/EductivAdministrator.php");
	if (! isset($_SESSION["username"])) {
		session_start();
		$_SESSION["username"] = "root";
	} else {
		header("location: .");
	}

	if (isset($_GET["logout"]) and $_GET["logout"] == "true") {
		session_unset();
		session_destroy();
	}
?>

<html>
	<head>
		<title><?php echo $_SESSION["username"] . " | Eductiv"; ?></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
		<script src="asset/script/jquery-3.5.1.min.js"></script>
		<script src="asset/script/main.js"></script>
		<script>
			$(document).ready(function() {
				$("#course-tbl").hide();
				$("#log-tbl").hide();
				$("#article-tbl").html("<tr><th>#</th><th>Title</th><th>Desc</th><th>POSTED BY</th><th>STATUS</th><th>FLAG</th></tr>");
				$.ajax({
					method: "GET",
					ContentType: "text/plain",
					accepts: "multipart/form-data",
					url: "api/EductivArticle.php",
					data: {
						service: "get-all"
					},
					success: function(response) {
						var res = JSON.parse(response);
						for (var i = 0; i <= res.article.length; i++) {
							$("#article-tbl").append(
								"<tr><td>" + res.article[i].article_id + "</td><td>" + res.article[i].article_title + "</td><td>" + res.article[i].article_desc + "</td><td>" + res.article[i].usercredential_username + "</td><td><a href='block.php?service=article&id=" + res.article[i].article_id + "'>" + 'BLOCK' + "</a></td><td>IRRELEVENT</td>" + res.article[i].article_status + "</td></tr>"
							);
						}
					}
				});

				$("#article-tab").click(function() {
					$("#article-tbl").show();
					$("#course-tbl").hide();
					$("#article-tbl").html("<tr><th>#</th><th>Title</th><th>Desc</th><th>POSTED BY</th><th>STATUS</th><th>FLAG</th></tr>");
					$.ajax({
						method: "GET",
						ContentType: "text/plain",
						accepts: "multipart/form-data",
						url: "api/EductivArticle.php",
						data: {
							service: "get-all"
						},
						success: function(response) {
							var res = JSON.parse(response);
							//alert(res.article[0].article_title);
							for (i = 0; i < res.article.length; i++) {
								$("#article-tbl").append(
									"<tr><td>" + res.article[i].article_id + "</td><td>" + res.article[i].article_title + "</td><td>" + res.article[i].article_desc + "</td><td>" + res.article[i].usercredential_username + "</td><td>" + res.article[i].article_status + "</td></tr>"
								);
							}
						}
					});
				});

				$("#course-tab").click(function() {
					$("#article-tbl").hide();
					$("#course-tbl").html("<tr><th>#</th><th>Title</th><th>Desc</th><th>POSTED BY</th><th>STATUS</th><th>FLAG</th></tr>");
					$("#course-tbl").show();
					$.ajax({
						method: "GET",
						ContentType: "text/plain",
						accepts: "multipart/form-data",
						url: "api/EductivCourse.php",
						data: {
							service: "get-all"
						},
						success: function(response) {
							var res = JSON.parse(response);
							for (i = 0; i <= res.course.length; i++) {
								$("#course-tbl").append(
									"<tr><td>" + res.course[i].course_id + "</td><td>" + res.course[i].course_title + "</td><td>" + res.course[i].course_desc + "</td><td>" + res.course[i].usercredential_username + "</td><td><a href='block.php?service=course&id=" + res.course[i].course_id + "'>" + res.course[i].course_status + "</a></td><td>MISGUIDING</td></tr>"
								);
							}
						}
					});
				});
			});
		</script>
	</head>
	<style>
		body {
				background-color: #1E1933;
				font-family: "Roboto";
				margin: 0 0 0 0;
				color: lightgrey;
		}

		.main-nav {
			background: #ff304f;
			height: 50px;
			text-align:center;
			color: #1E1933;
		}

		.main-nav * {margin-top: 5px;}
		.main-nav button {
			border: none;
			margin-left: 10px;
			padding: 8px 15px 8px 15px;
			width: auto;
			border-radius: 50px;
		}

		#article-tbl, #course-tbl {
			width: 90%;
			background-color: #ff304f;
			color: #1E1933;
		}

		#log-tbl {width: 90%;}

		#article-tbl tr, #course-tbl tr {
			border-bottom: #1e1933 solid 2px;
		}

		#article-tbl tr td, th, #course-tbl tr td, th {
			padding: 10px;
		}
	</style>
	<?php
		echo '<script>alert("Authentication successful\nWelcome, '.$_SESSION["username"].'");</script>';
	?>
<body>
	<nav class="main-nav">
		<span style="font-size: 25px;float:left;margin-left: 10px;"><b>EDU</b>ctiv</span>
		<button id="article-tab">ADD FIELD</button>
		<button id="article-tab">FLAGGED CONTENTS</button>
		<!-- <button id="article-tab">ARTICLE</button>
		<button id="course-tab">COURSE</button>
		<button id="logs-tab">LOGS</button> -->
		<span style="font-size: 18px;margin-left: 400px;">USER: <?php echo $_SESSION["username"]; ?></span>
		<button style="float:right;margin-right: 10px;" id="logout-tab"><a href="index.html?logout=true">LOG OUT</button>
	</nav>

	<div class="main-container"><br>
	<center>
		<h5 id="label" style="color:lightgrey;">BLOCKED CONTENT</h5>
		<table id="article-tbl"></table>
		<table id="course-tbl"></table>
		<!--<div id="log-tbl">
		<?php
			$log_file = fopen("../logs/eductiv_user_log.log", "r") or die("Unable to open file!");
			echo str_replace("[", "<br>[", fread($log_file, filesize("../logs/eductiv_user_log.log")));
			fclose($log_file);
		?>
		</div>-->
	</center>
	</div>

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

	<script src="asset/script/jquery-3.5.1.min.js"></script>
	
</body>
</html>