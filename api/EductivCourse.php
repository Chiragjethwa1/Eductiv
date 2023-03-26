<?php 

session_start();
try {
	require("EductivDatabase.php");
} catch (Error $e) {
	die($e->getMessage());
}


class EductivCourse extends EductivDatabase
{
	private $course_data = array(
		"id" => null,
		"title" => null,
		"prereq" => null,
		"username" => null,
		"image" => null,
		"field" => null,
		"keyword" => null,
		"timestamp" => null,
		"status" => null,
		"content" => null
	);
	private $eductiv_db;

	
	function __construct(array $course_data = null)
	{
		foreach ($course_data as $key => $value) {
			if (in_array($key, array_keys($this->course_data))) {
				$this->course_data[$key] = $value;
			}
		}
	}


	function addCourse()
	{
		$result = EductivCourse::query(
            "CALL ADD_COURSE(?, ?, ?, ?, ?, ?);",
            "ssssss",
            array(
                $this->course_data["username"],
                $this->course_data["title"],
				$this->course_data["status"],
                $this->course_data["field"],
                $this->course_data["keyword"],
				//$this->course_data["image"]["name"]
				$this->course_data["image"]
			),
			true
        );

		if ($result["response"]) {
			$this->course_data["id"] = $result["data"]["new_course_id"];

			$path = "storage\\";
			$user = $_SESSION['username'];
			$cmd = "mkdir " . $path . $user . "\courses\\".$this->course_data["id"];
			exec($cmd);
			$cmd = "mkdir " . $path . $user . "\courses\\".$this->course_data["id"]."\\image";
			exec($cmd);
			$cmd = "mkdir " . $path . $user . "\courses\\".$this->course_data["id"]."\\video";
			exec($cmd);
			$cmd = "mkdir " . $path . $user . "\courses\\".$this->course_data["id"]."\\file";
			exec($cmd);

			if ($this->course_data["image"] != null and !empty($this->course_data["image"]["name"])) {
				rename($this->course_data["image"]["tmp_name"], "storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/".$this->course_data["image"]["name"]);
			}

			if ($this->addContent()) {
				return EductivCourse::putResponse("success", "message", true);
			}
        } else {
			EductivCourse::writeLog("ARTICLE", "error", "Article creation failed (NEW_ARTICLE_DATA: " . json_encode($this->course_data) . ")");
        	throw new error(EductivCourse::putResonse("error", "message", "Course Posting Failed")); 
        }
	}



 	function getCourse(int $course_id)
 	{
 		$stmt = $this->eductiv_db->prepare("CALL GET_COURSE(?);");
 		$stmt -> bind_param("i", $course_id);
 		
 		if ($stmt -> execute()) {
 			$result = $stmt->get_result();
 			if ($result->num_rows == 1)
 			{
 				$row = $result->fetch_assoc();
 				return $row;
 			}
 		} else {
 			echo "Stmt not executed!";
 		}
	}


	private function addContent()
	{
		foreach ($this->course_data["content"] as $key => $value) {
			if (in_array($key, array_keys(self::ITEM_TYPE))) {
				if (in_array($value["type"], array_keys(self::ITEM_TYPE))) {
					switch (strtoupper($value["type"])) {
						case "TEXT":
						case "LINK":
							$result = EductivCourse::query(
								"CALL ADD_CONTENT(?, 'COURSE', ?, ?, ?);",
								"iiss",
								array(
									$this->course_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;
		
						case "FILE":
							$path = "/app/storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/image/".$_SESSION["username"].$value["content"]["name"]; //actual path

							$path = "E:/STUDY/xampp/htdocs/eductiv/storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/image/".$_SESSION["username"].$value["content"]["name"];

							rename($value["content"]["tmp_name"], $path);
							$value["content"] = $_SESSION["username"].$value["content"]["name"];
							
							$result = EductivCourse::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->course_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;

						case "IMAGE":
							$path = "/app/storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/image/".$_SESSION["username"].$value["content"]["name"]; //actual path

							$path = "E:/STUDY/xampp/htdocs/eductiv/storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/image/".$_SESSION["username"].$value["content"]["name"];

							rename($value["content"]["tmp_name"], $path);
							$value["content"] = $_SESSION["username"].$value["content"]["name"]["name"];
							
							$result = EductivCourse::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->course_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;

						case "VIDEO":
							$path = "/app/storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/video/".$_SESSION["username"].$value["content"]["name"]; //actual path

							$path = "E:/STUDY/xampp/htdocs/eductiv/storage/".$_SESSION["username"]."/courses/".$this->course_data["id"]."/video/".$_SESSION["username"].$value["content"]["name"];

							rename($value["content"]["tmp_name"], $path);
							$value["content"] = $value["content"]["name"];
							
							$result = EductivCourse::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->course_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;
					}
				} else {
					EductivAdministrator::writeLog("ARTICLE", "error", "Invalid item type encountered (INVALID_TYPE: " . $item["type"] . " PACKET: " . json_encode($item) . ")");
				}
			}  else {
				EductivAdministrator::writeLog("ARTICLE", "error", "Invalid content key encountered (INVALID_KEY: $key" . "PACKET: " .json_encode($item) . ")");
			}
		}
		return true;
	}
	

	function removeCourse($course_id)
	{
		$this->course_data["course_id"] = $course_id;
		$stmt = $this->eductiv_db->prepare("CALL REMOVE_COURSE(?);");
		$stmt -> bind_param("i", $this->course_data["course_id"]);
		
		if($stmt->execute())
		{
			echo "success:)";
		} else {
			echo "gfy::";
		}
	}

}

	if(isset($_POST["submit"])) {
		foreach($_POST as $k => $v) {
			echo $k . ": " . $v;
		}

		foreach($_FILES as $k => $v) {
			echo $k . ": " . $v;
		}

		$p = 0;
		for ($i = 0; $i <= count($_POST["item_type"]); $i++) {
			switch ($_POST["item_type"]) {
				case "TEXT":
				case "LINK":
					$course_data["content"][$i] = array(
						"course_id" => 1,
						"sequence" => $i,
						"item_type" => "TEXT",
						"item_content" => $_POST["item_content"][$i]
					);
					break;

				case "IMAGE":
				case "FILE":
					if ($p == 0) {
					} else {
						$p++;
					}

					$course_data["content"][$i] = array(
						"course_id" => 1,
						"sequence" => $i,
						"item_type" => "FILE",
						"item_content" => $_FILES["item_content"][$p]
					);
					break;

				default:
					// log the error.
					break;
			}
		}
	}
