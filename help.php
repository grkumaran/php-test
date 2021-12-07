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

    
<div class="card col-lg-6 offset-lg-3 ">
  <div class="card-header h3">
    Help
  </div>
  <div class="card-body">
  <ul>
    <li>Children's can take test at this address <a href="https://hk21-vsp-explorers-tst.container-crush-02-4044f3a4e314f4bcb433696c70d13be9-0000.eu-de.containers.appdomain.cloud/">Children's Happiness Quotient</a>
  </ul>
  </div>
</div>


<?php require('page_footer.php'); ?>
