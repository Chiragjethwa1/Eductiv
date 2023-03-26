<?php
	require("api/EductivAdministrator.php");
	if (! isset($_GET["service"])) {
		switch($_GET["service"]) {
			case "article":
				$result = EductivAdministrator::query(
					"UPDATE `eductiv_article` SET `article_status` = 'BLOCKED' WHERE `article_id` = ?;",
					'i',
					[$_GET["id"]]
				);
				
				if($result["response"]) {
					header("location: ");
				}
				break;
				
			case "course":
				$result = EductivAdministrator::query(
					"UPDATE `eductiv_course` SET `course_status` = 'BLOCKED' WHERE `course_id` = ?;",
					'i',
					[$_GET["id"]]
				);
				
				if($result["response"]) {
					header("location: dashboard.php");
				}
				break;
		}
	}
?>