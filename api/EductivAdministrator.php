<?php

require("EductivDatabase.php");

class EductivAdministrator extends EductivDatabase
{
    private $admin_data = array(
		"username" => null,
        "password" => null,
        "privilege" => null
	);

    
    function __construct(array $admin_data = null)
	{
        if ($admin_data != null) {
            foreach ($admin_data as $key => $value) {
                if (in_array($key, array_keys($this->admin_data))) {
                    $this->admin_data[$key] = $value;
                }
            }
        }
	}


    function addAdmin()
    {
        if ($this->admin_data["privilege"]) {}  // $this->admin_data["privilege"]["create"] == true  ???
        $result = EductivAdministrator::query(
            "CREATE USER ? IDENTIFIED BY ?",
            'ss',
            array($this->admin_data["username"], $this->admin_data["password"])
        );

        if ($result["response"]) {
            $this->grantPrivilege();
            EductivAdministrator::writeLog("ADMIN", "info", "New administrator added [USER: $this->admin_data['username']]");
            return EductivAdministrator::putResponse("success", "message", "New administrator account created.");
        } else {
            EductivAdministrator::writeLog("ADMIN", "error", "While creating new administrator [USER: $this->admin_data['username'] PASSWORD: $this->admin_data['password']]");
            return EductivAdministrator::putResponse("error", "message", "Account creation failed.");
        }
    }


    function grantPrivilege($previlege, $object, $user)
    {
        $result = EductivAdministrator::query(
            "GRANT ? ON ? TO ?;",
            'sss',
            array($previlege, $object, $user)
        );

        if ($result["response"]) {
            EductivAdministrator::writeLog("ADMIN", "info", "Privileges granted [$user: PREVILEGE - $previlege]");
            return EductivAdministrator::putResponse("success", "message", "Privileges granted");
        } else {
            EductivAdministrator::writeLog("ADMIN", "error", "Grant failed [$user: PREVILEGES - $previlege]");
            return EductivAdministrator::putResponse("error", "message", "Privileges could not be granted");
        }
    }


    function revokePrivilege($object, $user)
    {
        $result = EductivAdministrator::query(
            "REVOKE ? ON ? FROM ?;",
            'sss',
            array($this->admin_data["previlege"], $object, $user)
        );

        if ($result["response"]) {
            EductivAdministrator::writeLog("ADMIN", "info", "Privileges revoked [$user: PREVILEGE - $previlege]");
            return EductivAdministrator::putResponse("success", "message", "Privileges revoked");
        } else {
            EductivAdministrator::writeLog("ADMIN", "error", "Revoke failed [$user: PREVILEGES - $previlege]");
            return EductivAdministrator::putResponse("error", "message", "Privileges could not be revoked");
        }
    }


    function authenticateAdmin()
    {
        $admin = new mysqli("localhost", "root", "root@eductiv");

        if ($admin->connect_error) {
            return false;
        }
        return true;
    }
}


/*if (isset($_POST["service"])) {
    switch ($_POST["service"]) {
        case "enter":
            try {
                $admin_data = array(
                    "username" => $_POST["admin-user"],
                    "password" => $_POST["admin-pwd"]
                );
                $admin = new EductivAdministrator($admin_data);
                die($admin->authenticateAdmin());
            } catch(Error $e) {
                die($e->getMessage());
            }
            break;
    }
}*/
