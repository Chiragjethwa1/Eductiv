<?php

session_start();
try {
	require("EductivDatabase.php");
} catch (Error $e) {
	die($e->getMessage());
}

class EductivArticle extends EductivDatabase
{
	private $article_data = array(
		"id" => null,
		"title" => null,
		"image" => null,
		"name" => null,
		"username" => null,
		"field" => null,
		"keyword" => null,
		"status" => null,
		"content" => null
	);
	private const CONTENT = array("id", "type", "sequence", "item_type", "data");//???
	private const ITEM_TYPE = array("TEXT", "IMAGE", "FILE", "LINK");

	function __construct(array $article_data = null)
	{
		if ($article_data != null) {
			foreach ($article_data as $key => $value) {
				if (in_array($key, array_keys($this->article_data))) {
						$this->article_data[$key] = $value;
				}
			}
		}
	}

	public static function getArticles(int &$limit, int &$offset, string &$status)
	{
			$result = EductivArticle::query(
		"CALL GET_ARTICLES(?, ?, ?);",
					"iis",
					array($limit, $offset, $status), 
					true
		); 

			if ($result["response"]) {
					return EductivArticle::putResponse("success", "article", $result["data"]);
			} else {
		EductivArticle::writeLog("ARTICLE", "error", "Articles retrival failed (LIMIT: $limit, OFFSET: $offset, STATUS: $status)");
				throw new error(EductivArticle::putResponse("error", "message", "Not Found")); 
			}
	}

	
	static function getArticle(int &$article_id, string $status = null)
	{
		$result = EductivArticle::query(
			"CALL GET_ARTICLE(?, ?);",
			"is",
			array($article_id, (string)$status),
			true
		);

		if ($result["response"]) {
			$content = EductivArticle::query(
				"CALL GET_CONTENT('ARTICLE', ?);",
				"i",
				array($article_id),
				true
			);
			return EductivArticle::putResponse("success", "article", ["article" => $result["data"], "content" => $content["data"]]);
			
		} else {
			throw new error(EductivArticle::putResponse("error", "message", "Article Not Found")); 
		}
	}

	static function getContent(int &$article_id)
	{
        $result = EductivArticle::query(
            "CALL GET_CONTENT('ARTICLE', ?);",
            "i",
            array($article_id),
            true
        );
   
    	if ($result["response"]) {
	        return EductivArticle::putResponse("success", "content", $result["data"]);
	    } else {
			EductivArticle::writeLog("ARTICLE", "error", "Article body retrival failed (ARTICLE_ID: $article_id)");
	        throw new Error(EductivArticle::putResponse("error", "message", "Contents couldn't be retrived! Try again."));
	    }
	}

	public function updateContent(int $article_id, string $type, string $data)
	{
		$result = EductivArticle::query(
            "CALL UPDATE_CONTENT('ARTICLE', ?, ?, ?);",
            "iss",
            array($article_id, $type, $data)
        );
   
    	if ($result["response"]) {
	        return EductivArticle::putResponse("success", "message", "Article updated.");
	    } else {
			EductivArticle::writeLog("ARTICLE", "error", "Article body updation failed (ARTICLE_ID: $id, CONTENT_TYPE: $type, DATA: $data)");
	        throw new Error(EductivArticle::putResponse("error", "message", "Contents couldn't be updated! Try again."));
	    }
	}

	public function removeContent(int $article_id, string $type, string $data)
	{
		$result = EductivArticle::query(
            "CALL REMOVE_CONTENT('ARTICLE', ?, ?, ?);",
            "iss",
            array($article_id, $type, $data)
        );
   
    	if ($result["response"]) {
	        return EductivArticle::putResponse("success", "message", "Article updated.");
	    } else {
			EductivArticle::writeLog("ARTICLE", "error", "Article body removal failed (ARTICLE_ID: $id, CONTENT_TYPE: $type, DATA: $data)");
	        throw new Error(EductivArticle::putResponse("error", "message", "Contents couldn't be removed! Try again."));
	    }
	}

	public function addArticle()
	{
		$result = EductivArticle::query(
            "CALL ADD_ARTICLE(?, ?, ?, ?, ?, ?);",
            "ssssss",
            array(
                $this->article_data["username"],
                $this->article_data["title"],
				$this->article_data["status"],
                $this->article_data["field"],
                $this->article_data["keyword"],
				$this->article_data["image"]["name"]
			),
			true
        );

		if ($result["response"]) {
			$this->article_data["id"] = $result["data"]["new_article_id"];

			$path = "storage\\";
			$user = $_SESSION['username'];
			$cmd = "mkdir " . $path . $user . "\articles\\".$this->article_data["id"];
			exec($cmd);
			$cmd = "mkdir " . $path . $user . "\articles\\".$this->article_data["id"]."\\image";
			exec($cmd);
			$cmd = "mkdir " . $path . $user . "\articles\\".$this->article_data["id"]."\\video";
			exec($cmd);
			$cmd = "mkdir " . $path . $user . "\articles\\".$this->article_data["id"]."\\file";
			exec($cmd);

			if ($this->article_data["image"] != null and !empty($this->article_data["image"]["name"])) {
				rename($this->article_data["image"]["tmp_name"], "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/".$this->article_data["image"]["name"]);
			}

			if ($this->addContent()) {
				return EductivArticle::putResponse("success", "message", true);
			}
        } else {
			EductivArticle::writeLog("ARTICLE", "error", "Article creation failed (NEW_ARTICLE_DATA: " . json_encode($this->article_data) . ")");
        	throw new error(EductivArticle::putResonse("error", "message", "Article Posting Failed")); 
        }
	}

	private function addContent()
	{
		foreach ($this->article_data["content"] as $key => $value) {
			if (in_array($key, array_keys(self::ITEM_TYPE))) {
				if (in_array($value["type"], array_keys(self::ITEM_TYPE))) {
					switch (strtoupper($value["type"])) {
						case "TEXT":
						case "LINK":
							$result = EductivArticle::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->article_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;
		
						case "FILE":
							$path = "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/image/".$_SESSION["username"].$value["content"]["name"]; //actual path

							$path = "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/image/".$value["content"]["name"];

							rename($value["content"]["tmp_name"], $path);
							$value["content"] = $value["content"]["name"];
							
							$result = EductivArticle::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->article_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;

						case "IMAGE":
							$path = "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/image/".$_SESSION["username"].$value["content"]["name"]; //actual path

							$path = "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/image/".$value["content"]["name"];

							rename($value["content"]["tmp_name"], $path);
							$value["content"] = $value["content"]["name"];
							
							$result = EductivArticle::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->article_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;

						case "VIDEO":
							$path = "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/video/".$value["content"]["name"]; //actual path

							$path = "../storage/".$_SESSION["username"]."/articles/".$this->article_data["id"]."/video/".$value["content"]["name"];

							rename($value["content"]["tmp_name"], $path);
							$value["content"] = $value["content"]["name"];
							
							$result = EductivArticle::query(
								"CALL ADD_CONTENT(?, 'ARTICLE', ?, ?, ?);",
								"iiss",
								array(
									$this->article_data["id"],
									$value["seq"],
									$value["type"],
									$value["content"]
								)
							);
							break;
					}
				} else {
					EductivArticle::writeLog("ARTICLE", "error", "Invalid item type encountered (INVALID_TYPE: " . $item["type"] . " PACKET: " . json_encode($item) . ")");
				}
			}  else {
				EductivArticle::writeLog("ARTICLE", "error", "Invalid content key encountered (INVALID_KEY: $key" . "PACKET: " .json_encode($item) . ")");
			}
		}
		return true;
	}

	public function updateArticle()
	{
		$result = EductivArticle::query(
			"CALL UPDATE_ARTICLE(?, ?, ?, ?, ?, ?)",
			"ssssss",
			array(
				$this->article_data["username"],
                $this->article_data["title"],
                $this->article_data["desc"],
                $this->article_data["field"],
                $this->article_data["keyword"],
                $this->article_data["status"]
			)
		);

		if ($result["response"]) {
            return EductivArticle::putResponse("success","message","Article updated.");  
        } else {
			EductivArticle::writeLog("ARTICLE", "error", "Article update failed (UPDATE_DATA: " . json_encode($this->article_data) .")");
            throw new error(EductivArticle::putResonse("error","message","we ran into a problem")); 
        }
	}

	public function removeArticle()
	{
		$result = EductivArticle::query(
            "CALL REMOVE_ARTICLE(?);",
            "i",
            array($this->article_data["id"])
        );

        if ($result["response"]) {
            return EductivArticle::putResponse("success","message","Article removed.");  
        } else {
			EductivArticle::writeLog("ARTICLE", "error", "Article removal failed. (ARTICLE_ID: " . $this->article_data['id'] . ")");
            throw new error(EductivArticle::putResonse("error","message","we ran into a problem")); 
        }
	}
}
