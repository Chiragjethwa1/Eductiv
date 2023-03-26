<?php

try {
    require("api/EductivGeneral.php");

	if (isset($_REQUEST["service"])) {
		switch (strtolower($_REQUEST["service"])) {
			case "fields":
				die(EductivGeneral::getAllFields($_REQUEST['field']));
				break;
				
			case "add-field":
				die(EductivGeneral::addField($_REQUEST['field'], $_REQUEST['keyword']));
				break;
		}
	} else {
		EductivGeneral::writeLog("USER", "error", "Unauthorized access/attempt (No flags parsed)");
		throw new Error(EductivGeneral::putResponse("error", "message", "401: Unauthorized access/attempt."));
	}
} catch (Error $e) {
	die($e->getMessage());
}
