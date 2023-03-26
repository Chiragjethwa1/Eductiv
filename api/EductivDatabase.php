<?php

abstract class EductivDatabase
{

	protected static function query($statement, $types, array $parameter, bool $dql = false)
	{
		$eductiv_db = new mysqli(
			"DB HOST",
			"BD USER",
			"DB PWD",
			"DB NAME"
        );

        $stmt = $eductiv_db->prepare($statement);

		if ($types != '') {
			$stmt->bind_param($types, ...$parameter);
		}
		
		if ($stmt->execute()) {
			if ($dql) {
				$result = $stmt->get_result();
				if ($result->num_rows > 0) {
					if ($result->num_rows == 1) {
						$stmt->close();
						$eductiv_db->close();
						return array("response" => true, "data" => $result->fetch_assoc());
					} else {
						for ($i = 0; $i != $result->num_rows; $i++) {
							$data[$i] = $result->fetch_assoc();
						}
						$stmt->close();
						$eductiv_db->close();
						return array("response" => true, "data" => $data);
					}
				} else {
					$stmt->close();
					$eductiv_db->close();
					return array("response" => false);
				}
			} else {
				$stmt->close();
				$eductiv_db->close();
				return array("response" => true);
			}
		} else {
			$stmt->close();
			$eductiv_db->close();
			EductivDatabase::writeLog("DB", "error", "Statement Failed [" . $statement . " - " . json_encode($parameter) . "]");
			throw new Error(EductivDatabase::putResponse("error", "message", "Database error occurred! Please try again."));
		}
	}

    static function putResponse($response, $context, $data)
	{
		switch ($response) {
			case "success":
				switch ($context) {
					case "profile":
						return json_encode(array(
							"response" => "success",
							"profile" => $data
                        ));
                        
                    case "article":
                        return json_encode(array(
                            "response" => "success",
                            "article" => $data
                        ));

                    case "course":
                        return json_encode(array(
                            "response" => "success",
                            "course" => $data
                        ));
						break;
					
					case "people":
						return json_encode(array(
							"response" => "success",
							"people" => $data
						));
						break;

					case "field":
						return json_encode(array(
							"response" => "success",
							"field" => $data
						));
						break;

					case "message":
						return json_encode(array(
							"response" => "success",
							"message" => $data
						));
						break;

					/*case "content":
						return json_encode(array(
							"response" => "success",
							"message" => $data
						));
						break;*/
				}
				break;

			case "error":
				return json_encode(array(
					"response" => "error",
					"message" => $data
				));
				break;
		}
    }

	static function writeLog($module, $flag, $message)
	{
		switch (strtoupper($flag)) {
			case "ERROR":
				$log_msg = date("[Y-m-d G:H:i:s]  ") . $_SERVER["SERVER_NAME"] . " ERROR   - " . $message . "\n";
				$log_msg = date("[Y-m-d G:H:i:s]  ") . $_SERVER["SERVER_NAME"] . " ERROR   - " . $_SERVER["REMOTE_ADDR"] . " " . $message . "\n";
				break;

			case "INFO":
				$log_msg = date("[Y-m-d G:H:i:s]  ") . $_SERVER["SERVER_NAME"] . " INFO    - " . $message . "\n";
				$log_msg = date("[Y-m-d G:H:i:s]  ") . $_SERVER["SERVER_NAME"] . " INFO    - " . $_SERVER["REMOTE_ADDR"] . " " . $message . "\n";
				break;
		}

		switch (strtoupper($module)) {
			case "USER":
				error_log($log_msg, 3, "logs/eductiv_user_log.log");
				break;
			
			case "ADMIN":
				error_log($log_msg, 3, "logs/eductiv_admins_log.log");
				break;

			case "ARTICLE":
				error_log($log_msg, 3, "logs/eductiv_article_log.log");
				break;
			
			case "COURSE":
				error_log($log_msg, 3, "logs/eductiv_people_log.log");
				break;

			case "PEOPLE":
				error_log($log_msg, 3, "logs/eductiv_people_log.log");
				break;
			
			case "DB":
				error_log($log_msg, 3, "logs/eductiv_db_log.log");
				break;
		}
	}

	static function bindContent(&$pattern) // skip type and size ??
	{
		$content = [];
		$occ = ['t' => 0, 'l' => 0, 'i' => 0, 'f' => 0];
		$i = 0;
		$pattern = str_split($pattern);
		foreach ($pattern as $p) {
			switch ($p) {
				case 't':
					$content[$i] = array("seq" => $i, "type" => "TEXT", "content" => $_REQUEST["text"][$occ['t']]);
					$occ['t']++;
					$i++;
					break;
	
				case 'l':
					$content[$i] = array("seq" => $i, "type" => "LINK", "content" => $_REQUEST["link"][$occ['l']]);
					$occ['l']++;
					$i++;
					break;
				
				case 'f':
					$content[$i] = array(
						"seq" => $i,
						"type" => "FILE",
						"content" => array(
							"name" => $_FILES["file"]["name"][$occ['f']],
							"type" => $_FILES["file"]["type"][$occ['f']],
							"tmp_name" => $_FILES["file"]["tmp_name"][$occ['f']],
							"size" => $_FILES["file"]["size"][$occ['f']],
						));
					$occ['f']++;
					$i++;
					break;
				
				case 'i':
				case 'v':
					$content[$i] = array(
						"seq" => $i,
						"type" => "IMAGE",
						"content" => array(
							"name" => $_FILES["file"]["name"][$occ['f']],
							"type" => $_FILES["file"]["type"][$occ['f']],
							"tmp_name" => $_FILES["file"]["tmp_name"][$occ['f']],
							"size" => $_FILES["file"]["size"][$occ['f']],
						));
					$occ['i']++;
					$i++;
					break;
			}
		}
	
		return $content;
	}

	static function getFields()
	{
		$result = EductivDatabase::query("CALL GET_FIELDS();", '', [], true);

		if ($result["response"]) {
			return EductivDatabase::putResponse("success", "field", $result["data"]);
		}
	}
}

/*
try {
	if (isset($_REQUEST["service"])) {
		switch (strtolower($_REQUEST["service"])) {
			case "field":
				die(EductivDatabase::getFields());
				break;
		}
} else {
	EductivDatabase::writeLog("DB", "error", "Unauthorized access/attempt (No flags parsed)");
	throw new Error(EductivDatabase::putResponse("error", "message", "401: Unauthorized access/attempt."));
}
} catch (Error $e) {
die($e->getMessage());
}*/
