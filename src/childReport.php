<?php

session_start();


if (!isset($_SESSION['user_id'])) 
    header('Location: index.php');

require_once('database.php');


$childData = Array();
if (isset($_GET['_id']) && $_GET['_id'] >0) {
    
    // get Child-User data
    $query = new MongoDB\Driver\Query(['_id' => intval($_GET['_id']), 'parent_id' => intval($_SESSION['user_id'])], []);
    $cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
    foreach ($cursor as $document) {
        array_push($childData, $document->name);
        array_push($childData, $document->user_id);
        array_push($childData, $document->age);
        
        array_push($childData, $document->updatedAge);
        // $updated_date = strtotime($document->updatedAge. ' UTC');
        // array_push($childData, date("Y-m-d H:i:s", $updated_date));
    }
    array_push($childData, Array());        // test details
    
    // get Child-Tests data
    $query = new MongoDB\Driver\Query(['child_id' => intval($_GET['_id'])], ['sort'=>['date'=>1]]);
    $cursor = $dbMongoDB->executeQuery('hackathon.Tests', $query);
    foreach ($cursor as $document) {
        $s = $document->date;
        array_push($childData[4], 
            Array( $s->toDateTime()->format('Y-m-d H:i:s'), $document->score_max, 
                    $document->score_earn, $document->grade, 
                    // $document->remarks
                    ));
    }
}

require_once('session_header.php');
require_once('page_header.php');


    // <div class="progress">
        // <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
    // </div>
?>

<p>&nbsp;</p>

<form class="col-lg-9 offset-lg-2 " id="formRegitration" method="post">
<div class="card  col-lg-11 offset-lg-0 ">
  <div class="card-header h5">
    Child: <b><?php echo $childData[0] ?></b> <small><i>[ <?php echo $childData[1] ?> / <?php echo $childData[2] ?> ]</i></small>
  </div>
  <div class="card-body">
<?php 
    $total_per = 0;
    if (count($childData[4]) > 0) {
        echo '<table class="table table-striped">';
        for($i=0; $i<count($childData[4]); $i++) { 
            if ($i===0) {
                echo '<thead><th>Date</th><th>Score max</th><th>Score earn</th><th>Grade</th></th></thead>';
            }
            
            $per = intval(($childData[4][$i][2]) / ($childData[4][$i][1]) * 100);
            $total_per += $per;
            echo '<tr><td>'.$childData[4][$i][0] . 
                        '</td><td>' . $childData[4][$i][1] .
                        '</td><td>' . $childData[4][$i][2] .
                        '</td><td>' . $childData[4][$i][3] .
                        '</td><td width="100"><div class="progress"><div class="progress-bar" role="progressbar" style="width: '.$per.'%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.$per.'%</div></div>' .
                  '</td></tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Child had not taken test yet</p>';
    }
?>
   </div>
<?php 
    if (count($childData[4]) > 0) {
        $overall_per = $total_per/count($childData[4]);
        
        echo '<div class="card-footer text-muted">';
        echo '<div class="progress">';
        echo '<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '. $overall_per .'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Overall - '. $overall_per .'%</div>';
        echo '</div>';
        echo '</div>';
    }
?>
 
</div>
</form>

<?php require_once('page_footer.php'); ?>
