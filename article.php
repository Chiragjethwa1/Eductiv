<?php

try {
    require("api/EductivArticle.php");

	if (isset($_REQUEST["service"])) {
		switch (strtolower($_REQUEST["service"])) {
			case "add":
				if(!isset($_SESSION)) {
					session_start();
			   	}
				// Initializes sign-up details
				$article_data = array(
					"title" => $_REQUEST["title"],
					"image" => $_FILES["article-image"],
					"username" => $_SESSION["username"],
					"field" => $_REQUEST["field"],
					"keyword" => $_REQUEST["keyword"],
					"status" => $_REQUEST["status"],
					"content" => EductivArticle::bindContent($_REQUEST["pattern"])
				);

				// Performs complete user data validation
				//EductivArticle::validateArticleData($user_data, "add");

				$article = new EductivArticle($article_data);
				die($article->addArticle());
				break;

			case "update":
				$user_data = array(
					"first_name" => $_REQUEST["first_name"],
					"last_name" => $_REQUEST["last_name"],
					"gender" => $_REQUEST["gender"],
					"role" => $_REQUEST["role"],
					"profile_image" => $_FILES["profile_image"],
					"username" => $_REQUEST["username"],
					"email" => $_REQUEST["email"],
					"password" => $_REQUEST["password"]
				);
				
				$user = new EductivArticle($user_data);
				die($user->updateArticleData());
				break;

			case "remove":
				$user = new EductivArticle();
				die($user->removeUser());
				break;

			case "get":
				if (isset($_REQUEST["id"]) and strlen((string) $_REQUEST["id"]) > 0) {
					if(!isset($_REQUEST["status"])) {$_REQUEST["status"] = null;}
					die(EductivArticle::getArticle($_REQUEST["id"], $_REQUEST["status"]));
				} elseif(isset($_REQUEST["limit"]) and isset($_REQUEST["offset"]) and isset($_REQUEST["status"])) {
					die(EductivArticle::getArticles($_REQUEST["limit"], $_REQUEST["offset"], $_REQUEST["status"]));
				}else {
					EductivArticle::writeLog("ARTICLE", "error", "Unauthorized access attempt (service: " . $_REQUEST["service"] . ")");
					throw new Error(EductivArticle::putResponse("error", "message", "401: Unauthorized access/attempt."));
				}
				break;

			case "like":
				$user = new EductivArticle(["id" => $_REQUEST["id"]]);
				die($user->likeArticle());
				break;

			case "view":
				$user = new EductivArticle(["id" => $_REQUEST["id"]]);
				die($user->viewArticle());
				break;
			
			case "enter":
				// Initializes login credentials
				$user_credential = array(
					"username" => $_REQUEST["username"],
					"email" => $_REQUEST["email"],
					"password" => $_REQUEST["password"]
				);

				// Performs complete user data validation
				EductivArticle::validateUserData($user_credential, "enter");
		
				$user = new EductivArticle($user_credential);
				die($user->authenticateUser($user_credential));
				break;


			case "exit":
				$user = new EductivArticle();
				die($user->endSession());
				break;

			default:
				throw new Error(EductivArticle::putResponse("error", "message", "401: Unauthorized access/attempt"));
				break;
		}
	} else {
		EductivArticle::writeLog("USER", "error", "Unauthorized access/attempt (No flags parsed)");
		throw new Error(EductivArticle::putResponse("error", "message", "401: Unauthorized access/attempt."));
	}
} catch (Error $e) {
	die($e->getMessage());
}
