<?php

try {
    require("api/EductivUser.php");

	if (isset($_REQUEST["service"])) {
		switch (strtolower($_REQUEST["service"])) {
			case "add":
				// Initializes sign-up details
				$user_data = array(
					"first_name" => $_REQUEST["first_name"],
					"last_name" => $_REQUEST["last_name"],
					"username" => $_REQUEST["username"],
					"email" => $_REQUEST["email"],
					"password" => $_REQUEST["password"]
				);
			
				$user = new EductivUser($user_data);
				die($user->addUser());
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
				
				$user = new EductivUser($user_data);
				die($user->updateUserProfile());
				break;

			case "remove":
				$user_data = array("username" => $_REQUEST["username"]);
				$user = new EductivUser($user_data);
				die($user->removeUser());
				break;

			case "get":
			$cmd = "mkdir E:/STUDY/xampp/htdocs/eductiv/storage/" . $_REQUEST["username"];
			//echo $cmd;
			exec("mkdir storage\\".$_REQUEST["username"]);
				if (isset($_REQUEST["username"]) and strlen((string) $_REQUEST["username"]) > 0) {
						die(EductivUser::getUserProfile($_REQUEST["username"]));
				} else {
					EductivUser::writeLog("USER", "error", "Unauthorized access attempt (service: " . $_REQUEST["service"] . ")");
					throw new Error(EductivUser::putResponse("error", "message", "401: Unauthorized access/attempt."));
				}
				break;

			case "check-session":
				$user = new EductivUser();
				die($user->checkSession());
				break;
			
			case "enter":
				// Initializes login credentials
				$user_credential = array(
					"username" => $_REQUEST["username"],
					"email" => $_REQUEST["email"],
					"password" => $_REQUEST["password"]
				);

				// Performs complete user data validation
				//EductivUser::validateUserData($user_credential, "enter");
		
				$user = new EductivUser($user_credential);
				die($user->authenticateUser($user_credential));
				break;


			case "exit":
				$user = new EductivUser();
				die($user->endSession());
				break;

			case "check-user":
				die(EductivUser::isExistingUser($_REQUEST["username"], $_REQUEST["email"]));
				break;
			
			default:
				throw new Error(EductivUser::putResponse("error", "message", "401: Unauthorized access/attempt"));
				break;
		}
	} else {
		EductivUser::writeLog("USER", "error", "Unauthorized access/attempt (No flags parsed)");
		throw new Error(EductivUser::putResponse("error", "message", "401: Unauthorized access/attempt."));
	}
} catch (Error $e) {
	die($e->getMessage());
}
