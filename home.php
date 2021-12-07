<?php

session_start();


if (!isset($_SESSION['user_id'])) 
    header('Location: index.php');

require('database.php');
require('session_header.php');
require('page_header.php');

?>
<p>&nbsp;</p>
<p>&nbsp;</p>

    
<div class="card text-center col-lg-6 offset-lg-3 ">
  <div class="card-header h3">
    Welcome <?php echo $data_parent[2]; ?>
  </div>
  <div class="card-body">
    <p class="card-text">Add your child detail by clicking on following button to monitor and manage your child's daily emotional and psychological status.</p>
  </div>
  <div class="card-footer text-muted">
    <a href="addChild.php" class="btn btn-primary">Add Child details</a>
  </div>
</div>


<?php require('page_footer.php'); ?>
