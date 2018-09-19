<?php

function router($httpMethods, $route, $callback, $exit = true)
{
    static $path = null;
    if ($path === null) {
        $path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $scriptName = dirname(dirname($_SERVER['SCRIPT_NAME']));
        
        // error_log("Script1: " . $scriptName);
        
        $scriptName = str_replace('\\', '/', $scriptName);
        
        // error_log("Script2: " . $scriptName);
        // error_log("Path1: " . $path);
        
        $len = strlen($scriptName . 'api/service.php/');
        if ($len > 0 && $scriptName !== 'api/service.php/') {
            $path = substr($path, $len);
        }
        
        // error_log("Path2: " . $path);
    }
    if (!in_array($_SERVER['REQUEST_METHOD'], (array) $httpMethods)) {
        return;
    }
    // error_log("Route: " . $route);
    
    $matches = null;
    $regex = '/' . str_replace('/', '\/', $route) . '/';
    
    // error_log("Regx: " . $regex);
    
    if (!preg_match_all($regex, $path, $matches)) {
        error_log("No match! ");
        return;
    }
    
    // ob_start();
    // var_dump($matches);
    // error_log("Matches: " . ob_get_clean());
    
    if (empty($matches)) {
        $callback();
    } else {
        $params =[]; // array();
        foreach ($matches as $k => $v) {
            if (!is_numeric($k) && !isset($v[1])) {
                $params[$k] = $v[0];
            }
        }
        $callback($params);
    }
    if ($exit) {
        exit;
    }
}

function UpdateCustomerBalance($param) {
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok");

    if (strlen($param["tel"])!=10){
        $result->result = 0;
        $result->message = "Telphone number has to be 10 digits long.";
    }else{
                
        chdir("..");
        include_once 'dbinfo.php';
        chdir("api");
        
        $conn = new mysqli($servername, $username, $password , $db);
        
        // Check connection
        if (mysqli_connect_errno()) {
            $result->result = 0;
            $result->message = "Database connection issue.";
        }else{
            $sql = "UPDATE profiles SET credit= credit +( " . $param["balance"] . "), points=points + (" . $param["points"] . ") WHERE phone = '" . $param["tel"] . "'";
            $sqlResult = $conn->query($sql);
            
            if (!$sqlResult){
                $result->message = "Could not find this customer!.";
            }else{
            }
        }
        $conn->close();
    }
    echo json_encode($result);
}

function setStoreOnLine($params){
    setStoreOnlineStatus($params["id"],1);
}

function setStoreOffLine($params){
    setStoreOnlineStatus($params["id"],0);
}

function updateOrdersPrinted($params){
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok");
    
//     ob_start();
//     var_dump($params);
//     error_log("params: " . ob_get_clean());
    
    chdir("..");
    include_once 'dbinfo.php';
    chdir("api");
    
    $conn = new mysqli($servername, $username, $password , $db);
    
    // Check connection
    if (mysqli_connect_errno()) {
        $result->result = 0;
        $result->message = "Database connection issue.";
        echo json_encode($result);
        return;
    }
    
    $usql = "UPDATE orders SET status = 'D' WHERE ID in (";
    $ids = "";
    foreach ($params["orders"] as $item) {
        if ($ids != ""){
            $ids = $ids . ",";
        }
        $ids = $ids . $item;
    }
    $usql = $usql . $ids . ")";
    
//     error_log("SQL: " . $usql);
    
    $status = $conn->query($usql);
    
    if ($status){
    }else{
        $result->result = 0;
        $result->message = "Operation failed.";
    }
    
    echo json_encode($result);
}

function updateOrdersLoaded($params){
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok");
    
//     ob_start();
//     var_dump($params);
//     error_log("params: " . ob_get_clean());
        
    chdir("..");
    include_once 'dbinfo.php';
    chdir("api");
    
    $conn = new mysqli($servername, $username, $password , $db);
    
    // Check connection
    if (mysqli_connect_errno()) {
        $result->result = 0;
        $result->message = "Database connection issue.";
        echo json_encode($result);
        return;
    }
        
    $usql = "UPDATE orders SET status = 'P' WHERE ID in (";
    $ids = "";
    foreach ($params["orders"] as $item) {
        if ($ids != ""){
            $ids = $ids . ",";
        }
        $ids = $ids . $item;
    }
    $usql = $usql . $ids . ")";
    
//     error_log("SQL: " . $usql);
    
    $status = $conn->query($usql);
    
    if ($status){
    }else{
        $result->result = 0;
        $result->message = "Invalid store.";
    }
    
    echo json_encode($result);
}

function getStoreOnlineStatus($params){
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok" , 'status' => "ONLINE");
    
    chdir("..");
    include_once 'dbinfo.php';
    chdir("api");
    
    $conn = new mysqli($servername, $username, $password , $db);
    
    // Check connection
    if (mysqli_connect_errno()) {
        $result->result = 0;
        $result->message = "Database connection issue.";
        return;
    }
    
    $sql = "SELECT * FROM locations WHERE ID = " . $params["id"];
    $sqlResult = $conn->query($sql);
    
    if (!$sqlResult){
        $result->result = 0;
        $result->message = "Database connection issue.";
        echo json_encode($result);
        return;
    }
    
    $row = $sqlResult->fetch_array(MYSQLI_ASSOC);
    
    if ($row["ID"]==""){
        $result->result = 0;
        $result->message = "Invalid store.";
        echo json_encode($result);
        return;
    }
    
    if ($row["online"]==0){
        $result->status = "OFFLINE";
    }
    
    echo json_encode($result);
}

function setStoreOnlineStatus($store, $status){
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok");
    
    chdir("..");
    include_once 'dbinfo.php';
    chdir("api");
    
    $conn = new mysqli($servername, $username, $password , $db);
    
    // Check connection
    if (mysqli_connect_errno()) {
        $result->result = 0;
        $result->message = "Database connection issue.";
        return;
    }
    
    $usql = "UPDATE locations SET online = " . $status . " WHERE ID = " . $store;
    $status = $conn->query($usql);
    
    if ($status){
    }else{        
        $result->result = 0;
        $result->message = "Invalid store.";
    }
    
    echo json_encode($result);
    
}


function  AddOrUpdateOrder($param , $raw) {
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok");
    
    ob_start();
    var_dump($param);
    error_log("params: " . ob_get_clean());
    
    $store = $param["store"];
    $business = $param["business"];
    
    chdir("..");
    include_once 'dbinfo.php';
    chdir("api");
    
    $conn = new mysqli($servername, $username, $password , $db);
    
    // Check connection
    if (mysqli_connect_errno()) {
        $result->result = 0;
        $result->message = "Database connection issue.";
        echo json_encode($result);
        return;
    }
    $sql = "SELECT * FROM locations WHERE ID = " . $store ;
    $sqlResult = $conn->query($sql);
    
    if (!$sqlResult){
        $result->result = 0;
        $result->message = "Database connection issue.";
        echo json_encode($result);
        return;            
    }
    
    $row = $sqlResult->fetch_array(MYSQLI_ASSOC);
    
    if ($row["ID"]==""){
        $result->result = 0;
        $result->message = "Invalid store.";
        echo json_encode($result);
        return;
    }
    
    if ($business!=$row["business"]){
        $result->result = 0;
        $result->message = "Invalid business/store combination.";
        echo json_encode($result);
        return;
    }
    
    $usql = "INSERT INTO `orders`( `status`, `action`, `business`, `store`, `profile`, `orderdata` ) VALUES ('" . "N" .
    "', '" . $param["action"]. "', " . $business . "," . $store . "," . $param["customerID"] . ",?)";
    
    error_log("SQL: " . $usql);
    
    $stmt = $conn->prepare($usql);
    $stmt->bind_param("s", $raw);
    
    if (!$stmt->execute()){
        $result->result = 0;
        $result->message = "Order could not be saved.";
    }
    
    echo json_encode($result);
    
}

function  CancelOrder($param) {
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok");
    
    //     ob_start();
    //     var_dump($param);
    //     error_log("params: " . ob_get_clean());
    
    $orderID = $param["oid"];
    
    chdir("..");
    include_once 'dbinfo.php';
    chdir("api");
    
    $conn = new mysqli($servername, $username, $password , $db);
    
    // Check connection
    if (mysqli_connect_errno()) {
        $result->result = 0;
        $result->message = "Database connection issue.";
        echo json_encode($result);
        return;
    }
    
    $usql = "UPDATE `orders` SET `status` = 'C' WHERE ID = " . $orderID ;
       
    if (!$conn->query($usql)){
        $result->result = 0;
        $result->message = "Order could not be cancelled.";
    }
    
    echo json_encode($result);
    
}

function addOrUpdateCustomerByPhone($param) {
    header('Content-Type: application/json');
    $result = (object) array('result' => 1 , 'message' => "Ok"  , 'membershipid' => $param["cardid"] , 
            'points' => "" , 'balance' => "" , 'email' => $param["email"]);
    if (strlen($param["tel"])!=10){
        $result->result = 0;
        $result->message = "Telphone number has to be 10 digits long.";
    }else{
        
//         ob_start();
//         var_dump($param);
//         error_log("params: " . ob_get_clean());
        
        chdir("..");
        include_once 'dbinfo.php';
        chdir("api");
        
        $conn = new mysqli($servername, $username, $password , $db);
        
        // Check connection
        if (mysqli_connect_errno()) {
            $result->result = 0;
            $result->message = "Database connection issue.";
        }else{
            $sql = "SELECT * FROM profiles WHERE phone = '" . $param["tel"] . "'";
            $sqlResult = $conn->query($sql);
            
            if (!$sqlResult){
                error_log("No result");
            }else{
                $row = $sqlResult->fetch_array(MYSQLI_ASSOC);

                if ($row["ID"]==""){
                    chdir("..");
                    include_once 'util.php';
                    chdir("api");
                    
                    $email = $param["email"] ;
                    $token = getGUID();
                    // Memeber is not registed on the web, register him
                    if ($email==""){
                        // No email provided, use phone@freetoppingpizza for email !
                        $email = $param["tel"] . "@bsmarter.ca" ;
                        $result->email = $email;
                    }
                    $usql = "INSERT INTO `profiles`( `email`, `fullname`, `password`, `phone`, `accountType`, `membership` , `token`) VALUES ('" .$email .
                            "', '" . $param["name"]. "', '" . $param["tel"]. "', '" . $param["tel"]. "', 0 , '" . $param["cardid"]. "','" . $token . "')";                    
                    $status = $conn->query($usql);
//                    error_log("SQL ->" . $usql);
                    if ($status){
                        sendConfirmationEmail($email, $param["name"], $token);
                        $result->message = "User added to web.";
                        $result->result = 4;
                    }else{
                        $result->result = 0;
//                        error_log("Err ->" . $conn->error);
                        if (strpos($conn->error,"Duplicate")){
                            $result->message = "Duplicate email";
                        }else{
                            $result->message = "Database issue." . $conn->error;
                        }
                    }
                }else{
                    $result->membership = $row["membership"];
                    // Always update points and balance
                    $usql = "UPDATE profiles SET points = " . $param["points"] . " , credit = " . $param["balance"];
                    $emailChanged = false;
                    
                    if (strlen($param["email"])!=0){
                        if ($param["email"] != $row["email"]){
                            $usql = $usql . " ,email = '" . $param["email"] . "'" ;
                            $emailChanged = true;
                        }
                    }else{
                        $result->email = $row["email"];
                    }
                                    
                    if (strlen($param["cardid"])==0){
                        // No membership card on desktop, see if we have one here, if not generate one and give him the id.
                        if (strlen($row["membership"])!=0){
                            $result->membershipid = $row["membership"];
                            $result->message = "Membership returned.";
                            $result->result = 2;
                        }else{
                            $result->membershipid = "189317-" . str_pad($row["ID"], 7, '0', STR_PAD_LEFT);;
                            $usql = $usql . " ,membership = '" . $result->membershipid . "'";
                            $result->message = "Membership generated.";
                            $result->result = 3;
                        }
                    } elseif ($row["membership"]!=$param["cardid"]){
                        $usql = $usql . " ,membership = '" . $param["cardid"] . "'";
                        $result->message = "Membership updated.";
                    }
                    $usql = $usql . " WHERE phone = '" . $param["tel"] . "'";
//                    error_log("SQL ->" . $usql);
                    $status = $conn->query($usql);
                    
                    if ($status){
                        if ($emailChanged){
                            chdir("..");
                            include_once 'util.php';
                            chdir("api");
                            
                            sendChangeConfirmationEmail($param["email"], $param["name"]);
                            $result->message = "User's email address is changed.";
                            $result->result = 5;
                        }
                    }else{
                        $result->result = 0;
//                        error_log("Err ->" . $conn->error);
                        if (strpos($conn->error,"Duplicate")){
                            $result->message = "Duplicate email, you can't change user's email.";
                        }else{
                            $result->message = "Database issue." . $conn->error;
                        }
                    }
                }
            }
            $conn->close();
        }
    }
    echo json_encode($result);
}


?>
