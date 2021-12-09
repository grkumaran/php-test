<?php


class dbMongoDBConnector {
    private static $obj;
    
    private final function __construct() {
        echo __CLASS__ . " initialize only once";
    }
    
    public static function getConnect($dbUser, $dbPassword, $dbServer, $dbDatabase) {
        if (!isset(self::$obj)) {
            $dbConnectionStr = 'mongodb://' . $dbUser . ':' . $dbPassword . '@' . $dbServer . '/?authSource=' . $dbDatabase;
            
            // self::$obj = new MongoDB\Driver\Manager("mongodb://user1:mypassword1@mongodb/?authSource=hackathon");
            self::$obj = new MongoDB\Driver\Manager($dbConnectionStr);
            // self::$obj = new MongoDB\Client('mongodb://mongodb:5000');
        }
        
        return self::$obj;
    }

    // public function getHost() {
        // $rp = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
        // $server = self::$obj->selectServer($rp);
        // return $server->getHost();
    // }
    
    public function createCollection($dbName, $colName) {
        $command = new MongoDB\Driver\Command(["create" => $colName]);

        $cursor = self::$obj->executeCommand($dbName, $command);
        $response = $cursor->toArray()[0];
        return $response;
    }
    
}




/*


//bulk insert
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['x' => 1, 'y' => 'foo']);
$bulk->insert(['x' => 2, 'y' => 'bar']);
$bulk->insert(['x' => 3, 'y' => 'bar']);
$manager->executeBulkWrite('db.collection', $bulk);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'collection',
    'pipeline' => [
        ['$group' => ['_id' => '$y', 'sum' => ['$sum' => '$x']]],
    ],
    'cursor' => new stdClass,
]);
$cursor = $manager->executeCommand('db', $command);

/* The aggregate command can optionally return its results in a cursor instead
 * of a single result document. In this case, we can iterate on the cursor
 * directly to access those results. */
// foreach ($cursor as $document) {
    // var_dump($document);
// }
// ------------------------------------------------------------------------------------------

// $manager = new MongoDB\Driver\Manager();

/* Insert some documents so that our query returns information */
// $bulkWrite = new MongoDB\Driver\BulkWrite;
// $bulkWrite->insert(['name' => 'Ceres', 'size' => 946, 'distance' => 2.766]);
// $bulkWrite->insert(['name' => 'Vesta', 'size' => 525, 'distance' => 2.362]);
// $manager->executeBulkWrite("test.asteroids", $bulkWrite);

/* Query for all the items in the collection */
// $query = new MongoDB\Driver\Query( [] );

/* Query the "asteroids" collection of the "test" database */
// $cursor = $manager->executeQuery("test.asteroids", $query);

/* $cursor now contains an object that wraps around the result set. Use
 * foreach() to iterate over all the result */
// foreach($cursor as $document) {
    // print_r($document);
// }



//








