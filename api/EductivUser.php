<?php

try {
	require("EductivDatabase.php");
} catch (Error $e) {
	die($e->getMessage());
}

class EductivUser extends EductivDatabase
{
	private $user_data = array(
		"first_name" => null,
		"last_name" => null,
		"role" => null,
		"profile_image" => null,
		"email" => null,
		"username" => null,
		"password" => null,
		"timestamp" => null
	);

	// CONSTRUCTOR
	function __construct(array $user_data = null)
	{
		if ($user_data != null) {
			foreach ($user_data as $key => $value) {
				if (in_array($key, array_keys($this->user_data))) {
					$this->user_data[$key] = $value;
				}
			}
		}
	}


	// STATIC METHODS
	private static function validateUserData(array &$raw_user_data, $flag)
	{
		if (strtolower($flag) == "add") {
			foreach (array_keys($raw_user_data) as $key) {
				switch (strtolower($key)) {
					case "username":
						if (empty($raw_user_data["username"])) {
							throw new Error(EductivUser::putResponse("error", "message", "Username must not be empty!"));
						} else if (strlen($raw_user_data["username"]) > 20 or strlen($raw_user_data["username"]) < 12) {
							throw new Error(EductivUser::putResponse("error", "message", "Invalid username length!"));
						}
						break;
					
					case "email":
						if (empty($raw_user_data["email"])) {
							throw new Error(EductivUser::putResponse("error", "message", "Email address must not be empty!"));
						} else if (! filter_var($raw_user_data["email"], FILTER_VALIDATE_EMAIL)) {
							throw new Error(EductivUser::putResponse("error", "message", "Invalid email address!"));
						}
						break;
					
					case "first_name":
						if (empty($raw_user_data["first_name"])) {
							throw new Error(EductivUser::putResponse("error", "message", "First name must not be empty!"));
						} else if (true) {}
						break;
					
					case "last_name":
						if (empty($raw_user_data["last_name"])) {
							throw new Error(EductivUser::putResponse("error", "message", "Last name must not be empty!"));
						} else if (true) {}
						break;

					case "role":
						if (empty($raw_user_data["role"])) {
							continue;
						}
						break;

					case "profile_image":
						if (empty($raw_user_data["profile_image"])) {
							continue;
						}
						break;

					case "password":
						if (empty($raw_user_data["password"])) {
							throw new Error(EductivUser::putResponse("error", "message", "Password must not be empty!"));
						} else if (strlen($raw_user_data["password"]) > 20 or strlen($raw_user_data["password"]) < 12) {
							throw new Error(EductivUser::putResponse("error", "message", "Invalid password length!"));
						}
						break;

					case "timestamp":
						break;

					default:
						// log the access.
						throw new Error(EductivUser::putResponse("error", "message", "Invalid access", true));
						break;				
				}
			}
			EductivUser::isExistingUser($raw_user_data["username"], $raw_user_data["email"]);
		} else if ($flag == strtolower("enter")) {
			if (empty($raw_user_data["username"]) and empty($raw_user_data["email"]) and empty($raw_user_data["password"])) {
				throw new Error(EductivUser::putResponse("error", "message", "Login credentials must not be empty!"));
			} elseif (empty($raw_user_data["username"]) and empty($raw_user_data["email"])) {
				throw new Error(EductivUser::putResponse("error", "message", "Username/Email must not be empty!"));
			} elseif (empty($raw_user_data["password"])) {
				throw new Error(EductivUser::putResponse("error", "message", "Password must not be empty!"));
			} elseif (! empty($raw_user_data["username"] and empty($raw_user_data["email"]))) {}
			elseif (! empty($raw_user_data["email"]) and empty($raw_user_data["username"])) {}
			//else {} unexpected circumstance ??
		} else {
			// log the access.
		}
	}

	static function isExistingUser(&$username, &$email)
	{
		$result = EductivUser::query("CALL IS_EXISTING_USER(?, ?);", "ss", [$username, $email], true);

		if ($result["response"]) {
			if ($result["data"]["USERNAME"] == $username && $result["data"]["EMAIL"] == $email) {
				throw new Error(EductivUser::putResponse("error", "message", "Account already exists!"));
			} elseif ($result["data"]["USERNAME"] == $username) {
				throw new Error(EductivUser::putResponse("error", "message", "Username already exists!"));
			} elseif ($result["data"]["EMAIL"] == $email) {
				throw new Error(EductivUser::putResponse("error", "message", "Email already exists!"));
			}
		} else {
			return EductivUser::putResponse("success", "message", "Username/Email can be used");
		}
	}

	static function getUserProfile(&$username)
	{
		if (isset($_SESSION["username"]) and $_SESSION["username"] == $username) {
			$username = $_SESSION["username"];
		}
        
		$result = EductivUser::query("CALL GET_USER_PROFILE(?);", 's', [$username], true);
		if ($result["response"]) {
			return EductivUser::putResponse("success", "profile", $result["data"]);
		} else {
			throw new Error(EductivUser::putResponse("error", "message", "Profile not found!"));
		}
	}


	// PUBLIC METHODS
	function addUser()
	{
		EductivUser::validateUserData($this->user_data, "add");

		if ($this->user_data["profile_image"] != null) {
			if (rename($this->$user_data["profile_image"]["tmp_name"], "storage/image/profile/" . $this->$user_data["username"] . $this->$user_data["profile_image"]["name"])) {
				$this->user_data["profile_image"] = $this->$user_data["username"] . $this->$user_data["profile_image"]["name"];
			} else {
				EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " File upload failed (" . $this->user_data["profile_image"]["tmp_name"] . " - " . $this->user_data["profile_image"]["name"] . ")");
				throw new Error(EductivUser::putResponse("error", "message", "Profile image was not uploaded!"));
			}
		}
		
		$this->user_data["password"] = password_hash($this->user_data["password"],	PASSWORD_DEFAULT);

		$result = EductivUser::query(
			"CALL ADD_USER(?, ?, null, null, null, ?, ?, ?);",
			"sssss",
			array(
				$this->user_data["first_name"],
				$this->user_data["last_name"],
				$this->user_data["username"],
				$this->user_data["email"],
				$this->user_data["password"]
				// 60 char. long hash, Bcrypt algorithm
			)
		);

		if ($result["response"]) {
			//$path = "storage/";
			$path = "storage\\";
			$cmd = "mkdir " . $path . $this->user_data['username'];
			exec($cmd);
			$cmd = "mkdir " . $path . $this->user_data['username'] . "\articles";
			exec($cmd);
			$cmd = "mkdir " . $path . $this->user_data['username'] . "\courses";
			exec($cmd);

			/*$cmd = "mkdir " . $path . $this->user_data['username'] . "/course";
			exec($cmd);
			$cmd = "mkdir " . $path . $this->user_data['username'] . "/course/image";
			exec($cmd);
			$cmd = "mkdir " . $path . $this->user_data['username'] . "/course/video";
			exec($cmd);
			$cmd = "mkdir " . $path . $this->user_data['username'] . "/course/file";
			exec($cmd);*/
			
			EductivUser::writeLog("USER", "INFO", "New user created [USER: " . $this->user_data['username'] . "]");
			return EductivUser::putResponse("success", "message", true);
		} else {
			EductivUser::writeLog("USER", "ERROR", "Sign up failed [USER: " . json_encode($this->user_data) . "]");
			return EductivUser::putResponse("error", "message", "Error while creating account. Try again.");
		}
	}

	function authenticateUser()
	{		
		$result = EductivUser::query(
			"CALL AUTHENTICATE_USER(?, ?);",
			"ss",
			array(
				$this->user_data["username"],
				$this->user_data["email"]
			),
			true
		);

		if ($result["response"]) {
			if (password_verify($this->user_data["password"], $result["data"]["pwdhash"])) {
				if ($this->user_data["username"] == 'null') {
					$this->user_data["username"] = $result["data"]["username"];
				}
				if ($this->startSession()) {
					EductivUser::writeLog("USER", "INFO", $this->user_data["username"] . " Logged in!");
					return EductivUser::putResponse("success", "message", true);
				}
			} else {
				EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " Authentication failed! Invalid Password (" . $this->user_data["password"] . ")");
				throw new Error(EductivUser::putResponse("error", "message", "Log in attempt failed! Invalid Password."));
			}
		} else {
			EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " Authentication failed!");
				throw new Error(EductivUser::putResponse("error", "message", "Log in attempt failed! Try again."));
		}
	}

	function updateUserProfile()
	{
		if ($this->user_data["profile_image"] != null) {
			if (rename($this->user_data["profile_image"]["tmp_name"], "storage/image/profile/" . $this->user_data["username"] . $this->user_data["profile_image"]["name"])) {
				$this->user_data["profile_image"] = $this->user_data["username"] . $this->user_data["profile_image"]["name"];
			} else {
				EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " File upload failed (" . $this->user_data["profile_image"]["tmp_name"] . " - " . $this->user_data["profile_image"]["name"] . ")");
				throw new Error(EductivUser::putResponse("error", "message", "Profile image was not uploaded!"));
			}
		}
		
		if ($this->user_data["password"] != null) {
			$this->user_data["password"] = password_hash($this->user_data["password"],	PASSWORD_DEFAULT);
		}

		$result = EductivUser::query(
			"CALL UPDATE_USER_PROFILE(?, ?, ?, ?, ?, ?, ?)",
			"sssssss",
			array(
				$this->user_data["first_name"],
				$this->user_data["last_name"],
				$this->user_data["role"],
				$this->user_data["profile_image"],
				$this->user_data["username"],
				$this->user_data["email"],
				$this->user_data["password"]
			)
		);

		if ($result["response"]) {
			return EductivUser::putResponse("success", "message", "Profile updated");
		} else {
			EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " Profile update failed (" . json_encode($this->user_data) . ")");
			throw new Error(EductivUser::putResponse("error", "message", "Profile update failed! Try again"));
		}
	}

	function removeUser()
	{
		session_start();
		if (isset($_SESSION["username"]) and $_SESSION["username"] == $this->user_data["username"]) {
			$this->user_data["username"] = $_SESSION["username"];

			$result = EductivUser::query("CALL REMOVE_USER(?);", 's', [$this->user_data["username"]]);
			if ($result["response"]) {
				EductivUser::writeLog("USER", "INFO", $this->user_data["username"] . " account removed");
				return EductivUser::putResponse("success", "message", "Account removed.");
				// redirect
			} else {
				EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " Unauthorized attempt to remove account");
				throw new Error(EductivUser::putResponse("error", "message", "Account removal failed! Try again"));
			}
		} else {
			EductivUser::writeLog("USER", "ERROR", $this->user_data["username"] . " Unauthorized attempt to remove account");
			throw new Error(EductivUser::putResponse("error", "message", "Unauthorized attempt"));
		}
	}

	private function startSession()
	{
		if (session_start()) {
			$_SESSION['username'] = $this->user_data['username'];
			return true;
		} else {
			return false;
		}
	}

	function checkSession()
	{
		session_start();
		if (isset($_SESSION["username"])) {
			if ($_SESSION["username"] == $this->user_data["username"]) {
				return true;
			}
		}
		return false;
	}

	function endSession()
	{
		session_start();
		return (session_unset() and session_destroy())
		? EductivUser::putResponse("success", "message", true)
		: EductivUser::putResponse("error", "message", session_status());
	}
}


/*
DUMMY USER
$user_data = array(
		"first_name" => "Thor",
		"last_name" => "Odinson",
		"email" => "thor@thunder.com",
		"username" => "ThorRock$@asgard",
		"password" => "lord@fthunder",
	);

try{
	echo "<script>var data = ".EductivUser::getUser($_GET["username"])."</script><pre><big><script>document.write('data = ' + JSON.stringify(data, undefined, 4));</script></big></pre>";
} catch (Error $e) {
	echo "<script>var data = ".$e->getMessage()."</script><pre><big><script>document.write('data = ' + JSON.stringify(data, undefined, 4));</script></big></pre>";
}*/
