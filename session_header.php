<?php


$data_parent = Array();
$data_child = Array();

// get parent data
$query = new MongoDB\Driver\Query(['_id' => $_SESSION['user_id']], []);
$cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
foreach ($cursor as $document) {
    $data_parent = Array($document->_id, $document->parent_id, $document->name, $document->user_id, $document->age, $document->updatedAge, $document->email);
}

// get child list
$query = new MongoDB\Driver\Query(['parent_id' => $_SESSION['user_id']], []);
$cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
foreach ($cursor as $document) {
    array_push($data_child, Array($document->_id, $document->parent_id, $document->name, $document->user_id, $document->age, $document->updatedAge));
}

