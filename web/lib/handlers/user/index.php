<?php
require_once __dir__ . '/../../connecters/DataAccess.php';
require_once __dir__ . '/../../connecters/UserData.php';
require_once __dir__ . '/UserController.php';
require_once __dir__ . '/../../AuthCookie.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if(!AuthCookie::isValid()) {
    header("'HTTP/1.1 403 Forbidden'");
	echo json_encode(
        array("authorized" => false, "message" => "You do not have permission to be here.")
    );
    exit();
}

//echo 'i like pizza.';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /user
// everything else results in a 404 Not Found
// print_r($uri);
// exit();
if ($uri[3] !== 'user') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the user id is, of course, optional and must be a number:
$userId = null;
// if (isset($uri[4])) {
//     $userId = (int) $uri[4];
// } else {
// }
$userId = (isset($_REQUEST["id"])) ? $_REQUEST["id"] : 0;
// echo "userId: $userId";
// exit();

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:
$dbConnection = (new DataAccess())->getConnection();
$controller = new UserController($dbConnection, $requestMethod, $userId);
$controller->processRequest();