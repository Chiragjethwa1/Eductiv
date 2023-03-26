<?php 

require("../../EductivDatabase.php");

class EductivCourse extends EductivDatabase {
	
	private $course_data = array(
		"id" => null,
		"username" => null, 
		"title" => null,
		"desc" => null,
		"prereq" => null,
		"timestamp" => null,
		"keyword" => null,
		"field" => null,
		"status" => null,
		"content" => null
	);
	private $eductiv_db;

	function addcourse()
	{ 
		$result = EductivCourse::query("CALL ADD_COURSE(?,?,?,?,?);","sssss", [$this->course_data["username"], $this->course_data["title"], $this->course_data["desc"], $this->course_data["prereq"], $this->course_data["status"]]);

		if($result["response"])
		{
			return EductivCourse::putResponse("success","message","Added successfully:)");
		}
		else{
			throw new error(EductivCourse::putResponse("ERROR","MESSAGE","Failed :("));
		}
	}

 	function getcourse()
 	{

		$result = EductivCourse::query("CALL GET_COURSE(?);","i", [$this->course_data["id"]], true);
		if($result["response"] )
		{
			return EductivCourse::putResponse("success","course",$result["data"]);
		}
		else{
			throw new error(EductivCourse::putResponse("error","message","GET Failed :("));
		}
 	}

	static function getCourses($limit, $offset, $status)
	{
		$result = EductivCourse::query("SELECT * FROM `eductiv_course` WHERE `course_status` = ? limit ? offset ?;","sii", [$status, $limit, $offset], true);

		if ($result["response"])
		{
			return EductivCourse::putResponse("success","course", $result["data"]);
		}
		else{
			throw new error(EductivCourse::putResponse("error","message","Courses not found :("));
		}

	}

	function __construct(&$course_data = NULL)
	{
		foreach ($course_data as $key => $value) {
			if (in_array($key, array_keys($this->course_data))) {
				$this->course_data[$key] = $value;
			}
		}
	}

	static function getContent(&$course_id) {
        $result = EductivCourse::query(
            "CALL GET_CONTENT(?,?)",
            "si",
            ["COURSE",$course_id],
            TRUE
        );
   
    	if ($result["response"]) {
	        return EductivCourse::putResponse("success", "course", $result["data"]);
	    } else {
	        throw new Error(EductivCourse::putResponse("error", "message", "Contents couldn't be retrived! Try again."));
	    }
	}

	public function updateCourse()
	{
		$result = EductivCourse::query("CALL UPDATE_COURSE(?,?,?,?,?,?,?);","sssisss", [
		$this->course_data['title'],
		$this->course_data['desc'],
		$this->course_data['status'],
		$this->course_data['id'],
		$this->course_data['prereq'],
		$this->course_data['field'],
		$this->course_data['keyword']]);

		if($result["response"] )
		{
			return EductivCourse::putResponse("success","message","course updated successfully");
		}
		else{
			throw new error(EductivCourse::putReponse("error","message","Course fail :("));
		}

	}

	function removeCourse()
	{

		$result = EductivCourse::query("CALL REMOVE_COURSE(?);","i", [$this->course_data["id"]]);

		if($result["response"])
		{
			return EductivCourse::putResponse("success","message","Course removed");
		}
		else{
			throw new error(EductivCourse::putResponse("error","message","Courses did not removed :("));
		}

	}

}
	
/*
	if ($_POST["service"] == "add")
	{
		try{
			$obj = new EductivCourse($article_data);
			die($obj->addCourse());
		}
		catch (Error $e){
			die($e->getMessage());
		}	
	}

	elseif ($_POST["service"] == "update") {

		try{
			$obj = new EductivCourse($article_data);
			die($obj->updateCourse());
		}
		catch (Error $e){
			die($e->getMessage());
		}		
	}

	elseif ($_POST["service"] == "remove") {
		
		try{
			$obj = new EductivCourse(array("id"=>$_POST["id"]));
			die($obj->removeCourse());
		}
		catch (Error $e){
			die($e->getMessage());
		}	

		}	
	elseif ($_POST["service"] == "get") {
		try{
			$obj = new EductivCourse(array("id" => $_POST["id"]));
			die($obj->getCourse());
		}
		catch (Error $e){
		die($e->getMessage());	
		}
	}

	else{
		EductivCourse::writeLog("ERROR","404 Bad request");
		EductivCourse::putResponse("ERROR", "MESSAGE","404 Bad request");
	}*/

	//echo json_encode(EductivCourse::getCourses());


	/*$arr2 = array("title"=> "noise","desc"=> "Gae", "status"=> "DRAFT", "id"=>27);
	$obj = new EductivCourse($arr2);
	echo $obj->updateCourse();

	//$obj->updateCourse($arr);
	//$obj->getcourse(1);


	echo $obj->addcourse($arr);
	echo var_dump($obj->getcourse(1));

	foreach($obj->getcourse(1) as $val)
	{
		echo $val;

	}	
*/	
	//$obj = new EductivCourse();

	if (isset($_GET["service"])) {
		switch ($_GET["service"]) {
			case "get-all":
				die(EductivCourse::getCourses(100, 0, "BLOCKED"));
				break;
		}
	}

?>