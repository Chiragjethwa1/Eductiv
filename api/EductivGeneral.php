<?php

try {
	require("EductivDatabase.php");
} catch (Error $e) {
	die($e->getMessage());
}

class EductivGeneral extends EductivDatabase
{
	// STATIC METHODS
	static function getAllFields($field)
	{
		$result = EductivGeneral::query("CALL GET_FIELDS(?);", 's', [$field], true);
		if ($result["response"]) {
			return EductivGeneral::putResponse("success", "field", $result["data"]);
		} else {
			throw new Error(EductivGeneral::putResponse("error", "message", "No Fields Found!"));
		}
	}
	
	static function addField($field, $keyword = 'null')
	{
		$result = EductivGeneral::query("CALL ADD_FIELD(?, ?);", 'ss', [$field, $keyword]);
		if ($result["response"]) {
			return EductivGeneral::putResponse("success", "message", "New Field Added");
		} else {
			throw new Error(EductivGeneral::putResponse("error", "message", "Field Insertion Failed!"));
		}
	}
}
