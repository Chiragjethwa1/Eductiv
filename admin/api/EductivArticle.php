<?php 

require '../../EductivDatabase.php';


class EductivArticle extends EductivDatabase
{
        private $article_data = array(
                            "username" => null,
                            "title" => null,
                            "desc" => null,
                            "field" => null,
                            "keyword" => null,
                            "status" => null
                        );

//CONSTRUCTOR
   function __construct(array $article_data = null)
    {
        if ($article_data == null) {}

        else{
            foreach ($article_data as $key => $value) {
                if (in_array($key, array_keys($this->article_data))) {
                    $this->article_data[$key] = $value;                }
            }
        }
    }

//Staic Method
	public static function getArticles($from, $to, $status){
        $result = EductivArticle::query("CALL GET_ARTICLES(?,?,?)",
                                "iis", 
                                [$from,
                                $to, $status], 
                                true);

 

        if ($result["response"]) {
            return EductivArticle::putResponse("success","article",$result["data"]);
        }
        else{
           throw new error(EductivArticle::putResonse("error","message","we ran into a problem")); 
        }
    }   

    public static function getArticle($article_id)
    {
        $result = EductivArticle::query(
            "call GET_ARTICLE(?)",
            "i",
            [$article_id],
            TRUE
        );

        if ($result["response"]) {
            return EductivArticle::putResponse("success","article",$result["data"]);
        }
        else{
            throw new error(EductivArticle::putResonse("error","message","article not found :("));
        }
    }
    
    static function getContent(&$article_id) {
        $result = EductivArticle::query(
            "CALL GET_CONTENT(?,?)",
            "si",
            ["ARTICLE",$article_id],
            TRUE
        );
   
    	if ($result["response"]) {
	        return EductivArticle::putResponse("success", "content", $result["data"]);
	    } else {
	        throw new Error(EductivArticle::putResponse("error", "message", "Article contents couldn't be retrived! Refresh."));
	    }
	}

	public function addArticle() {
        $result = EductivArticle::query(
            "CALL ADD_ARTICLE(?,?,?,?,?,?);",
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
            return "WORKS";
        } else {return "NOPE";}
    }
  
    function updateArticle(){//Will be completed soon
	        $result = EductivArticle::query(
	            "CALL UPDATE_ARTICLE(?,?,?,?,?,?)",
	            "isssss",
	            [$update_data]
	        );

	        if ($result["response"]) {}       
	}
 
 	public function removeArticle()
    {
        $result = EductivArticle::query(
            "CALL REMOVE_ARTICLE(?)",
            "i",
            [$article_id]
        );

        if ($result["response"]) {
            return EductivArticle::putResponse("success","article","article removed");  
        }
        else{
            throw new error(EductivArticle::putResonse("error","message","we ran into a problem")); 
        }
    }

    public function flag($uname, $content_id,  $flag, $desc=null)
    {	
    	try {
    		
	    	$eductiv_db = new mysqli("localhost", "root", "", "eductiv");

        	$stmt = $eductiv_db-> prepare("INSERT INTO `eductiv_flag` VALUES(?, ?, 'ARTICLE', ?, ?, 'PENDING');");
        	
        	$stmt->bind_param("siss", $uname, $content_id, $flag, $desc);

	        if ($stmt->execute()) {
	            echo "Done";
	        } else {
	            echo "Problems";
	        }
	    	

    	} catch (Exception $e) {
    		echo $e->getMessage();
    	}


    }
}



//$a =1 ;
// echo EductivArticle::getContent($a);
if (isset($_GET["service"])) {
    switch ($_GET["service"]) {
        case "get-all":
            die(EductivArticle::getArticles(100, 0, "PUBLIC"));
            break;
    }
}
/*
$data = array("username" => "ThorRock$@asgard" ,
                        "title" => "title",
                        "desc" => "desc",
                        "field" => "field",
                        "keyword" => "Key",
                        "status" => "null");

$obj = new EductivArticle($data);

die($obj->flag("ThorRock$@asgard",3,"MISGUIDUNG"));*/

?>
