<?php
require_once 'functions.php';

// Default index page
router('GET', '^/$', function () {
    header('Content-Type: application/json');
    echo '{"functions":[';
    echo '  {"method" :    "/updateOrdersPrinted" , "type" : "POST" , "comment" : "Updated the orders as printed and being prepared." },';
    echo '  {"method" :    "/updateOrdersLoaded" , "type" : "POST" , "comment" : "Updated the orders as processed and loaded." },';
    echo '  {"method" :    "/addOrUpdateClientsByPhone" , "type" : "POST" , "comment" : "Adds a new customer or updates his card-id" },';
    echo '  {"method" :    "/updateClientBalance" , "type" : "POST" , "comment" : "Updates client points and balance to new amount" },';
    echo '  {"method" :    "/CancelOrder" , "type" : "POST" , "comment" : "Cancels an order" },';
    echo '  {"method" :    "/AddOrUpdateOrder" , "type" : "POST" , "comment" : "Adds or updates an order" },';
    echo '  {"method" :    "/storeOnline/nn" , "type" : "GET" , "comment" : "Brings a store online" }';
    echo '  {"method" :    "/storeOffline/nn" , "type" : "GET" , "comment" : "Brings a store offline" }';
    echo '  {"method" :    "/getStoreOnlineStatus/nn" , "type" : "GET" , "comment" : "Gets a store online status" }';
    echo ']}';
});

router('POST', '^/updateOrdersPrinted$', function () {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    updateOrdersPrinted($json,$raw);
});    
        
router('POST', '^/CancelOrder$', function () {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    CancelOrder($json);
});
        
router('POST', '^/AddOrUpdateOrder$', function () {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    AddOrUpdateOrder($json,$raw);
});
    
router('POST', '^/AddOrUpdateOrder$', function () {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    AddOrUpdateOrder($json,$raw);
});
    
    
router('POST', '^/updateClientBalance$', function () {
    $json = json_decode(file_get_contents('php://input'), true);
    UpdateCustomerBalance($json);
});
        
router('POST', '^/addOrUpdateClientsByPhone$', function () {
    $json = json_decode(file_get_contents('php://input'), true);
    addOrUpdateCustomerByPhone($json);
});

router('GET', '^/storeOnline/(?<id>\d+)$', function ($params) {
    header('Content-Type: application/json');
//    var_dump($params);
    setStoreOnLine($params);
});

router('GET', '^/storeOffline/(?<id>\d+)$', function ($params) {
    header('Content-Type: application/json');
    setStoreOffLine($params);
});

router('GET', '^/getStoreOnlineStatus/(?<id>\d+)$', function ($params) {
    header('Content-Type: application/json');
    getStoreOnlineStatus($params);
});
    
// POST request to /users
router('POST', '^/users$', function () {
    header('Content-Type: application/json');
    $json = json_decode(file_get_contents('php://input'), true);

    // ob_start();
    // var_dump($json);
    // error_log("POST JSON: " . ob_get_clean());
    
    echo json_encode([
        'result' => 1
    ]);
});

header("HTTP/1.0 404 Not Found");
echo '404 Not Found';
?>