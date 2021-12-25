<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function bcrypt_encrypt_password($pw) {
    return password_hash($pw, PASSWORD_BCRYPT);
}



require_once('dbMongoDBConnector.php');
// $dbMongoDB = dbMongoDBConnector::getConnect('user1', 'mypassword1', 'mongodb', 'hackathon');
$dbMongoDB = dbMongoDBConnector::getConnect($_ENV['MONGODB_USER'], $_ENV['MONGODB_PASSWORD'], 
                                            $_ENV['MONGO_SERVICE_HOST'], $_ENV['MONGODB_DATABASE']);
$command = new MongoDB\Driver\Command(['ping' => 1]);
$dbMongoDB->executeCommand('hackathon', $command);
// print_r($dbMongoDB->getServers());    
// print_r($dbMongoDB->getHost());


