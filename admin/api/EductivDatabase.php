<?php

abstract class EductivDatabase
{
    private const SERVER = array(
		"host" => "localhost",
		"user" => "root",
		"password" => "",
		"database" => "eductiv"
	);

	protected static function query($statement, $types, array $parameter, bool $dql = false)
	{
        $eductiv_db = new mysqli(
			self::SERVER["host"],
			self::SERVER["user"],
			self::SERVER["password"],
			self::SERVER["database"]
        );

        if ($eductiv_db->connect_error) {
			EductivDatabase::writeLog("DB", "error", $eductiv_db->connect_errno . ": " . $eductiv_db->connect_error);
			throw new Error(EductivDatabase::putResponse(
				"error",
				"message",
				"Database connection failed! Please try again."
			));
		} else {
			EductivDatabase::writeLog("DB", "error", "Database connection initiated.");
		}

        $stmt = $eductiv_db->prepare($statement);
        $stmt->bind_param($types, ...$parameter);
		
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

					case "message":
						return json_encode(array(
							"response" => "success",
							"message" => $data
						));
						break;
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
				$log_msg = date("[Y-m-d G:H:i:s] ") . $_SERVER["SERVER_NAME"] . " ERROR   - " . $message . "\n";
				break;

			case "INFO":
				$log_msg = date("[Y-m-d G:H:i:s] ") . $_SERVER["SERVER_NAME"] . " INFO   - " . $message . "\n";
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
}

?>