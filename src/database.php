<?php

require_once('dbMongoDBConnector.php');

$dbMongoDB = dbMongoDBConnector::getConnect('user1', 'mypassword1', 'mongodb', 'hackathon');
// $dbMongoDB = dbMongoDBConnector::getConnect($_ENV['MONGODB_USER'], $_ENV['MONGODB_PASSWORD'], 
                                            // $_ENV['MONGO_SERVICE_HOST'], $_ENV['MONGODB_DATABASE']);
// $command = new MongoDB\Driver\Command(['ping' => 1]);
// $dbMongoDB->executeCommand('hackathon', $command);
// print_r($dbMongoDB->getServers());    
// print_r($dbMongoDB->getHost());

