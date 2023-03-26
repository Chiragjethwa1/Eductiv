<?php
	require("../api/EductivAdministrator.php");
	$admin_data = [];
    if (isset($_REQUEST["username"]) and isset($_REQUEST["username"])) {
		$admin_data = array(
			"username" => $_REQUEST["username"],
			"password" => $_REQUEST["password"]
		);
    }
        $admin = new EductivAdministrator($admin_data);
        if ($admin->authenticateAdmin()) {
            session_start();
            $_SESSION["user"] = $admin_data["username"];
            header("location: admin-home.php");
        } else {
            echo "NOPE";
        }
?>