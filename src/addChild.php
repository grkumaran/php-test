<?php

session_start();


if (!isset($_SESSION['user_id'])) 
    header('Location: index.php');

require_once('database.php');

// input data processing
$user_addition = '';
if (isset($_POST['formSubmission']) && $_POST['formSubmission'] === '1') {
    $query = new MongoDB\Driver\Query([], ['sort'=>['_id'=>-1]]);
    $cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
    foreach ($cursor as $document) {
        $new_id = $document->_id + 1;
        break;
    }

    // add new child data
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert([
        '_id' => $new_id,
        'parent_id' => $_SESSION['user_id'], 
        'name' => $_POST['childName'], 
        'user_id' => $_POST['childUsername'], 
        'email' => '', 
        'password' => $_POST['childPassword'], 
        'age' => $_POST['childAge'], 
        'address'=>'', 
        'city'=>'', 
        'country'=>'', 
        'phone'=>'', 
        'updatedAge' => new \MongoDB\BSON\UTCDateTime]);
    $result = $dbMongoDB->executeBulkWrite('hackathon.Users', $bulk);

    foreach($result as $res) {
        if ($res->nInserted !== 1) {
            echo 'Error in registering user';
            exit;
        }
        
        $user_addition = $_POST['childUsername'];
    }
}

require('database.php');
require('session_header.php');
require('page_header.php');

?>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>

<?php if ($user_addition != '') { ?>
<div class="col-lg-5 offset-lg-4 ">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Holy guacamole!</strong> You should check in on some of those fields below.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php } else { ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php } ?>


<form class="col-lg-9 offset-lg-2 " id="formRegitration" method="post">
<div class="card  col-lg-6 offset-lg-3 ">
  <div class="card-header h5">
    New child
  </div>
  <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-7">
            <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="childName"  id="childName" >
          </div>
          <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="Age" aria-label="Age" name="childAge" id="childAge" >
          </div>
        </div>
   </div>
  <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-7">
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" name="childUsername" id="childUsername" >
          </div>
          <div class="col-sm-5">
            <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="childPassword" id="childPassword" >
          </div>
        </div>
        <input type="hidden" name="formSubmission" value="1">
  </div>
  <div class="card-footer text-muted">
    <button type="button" class="btn btn-primary" onClick="javascript:handleSubmit()">Add Child</butto
  </div>
</div>
</form>



<script language="javascript">
    const reEmail = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    const rePassword = /^[A-Za-z0-9]{4,}$/i;
    const reUsername = /^[A-Za-z]{4,}$/i;
    const reName = /^[A-Za-z ]{4,}$/i;
    const reAge = /^[0-9]+$/i;
    
    function handleSubmit() {
        const formRegitration = document.getElementById('formRegitration');
        const childName = document.getElementById('childName');
        const childAge = document.getElementById('childAge');
        const childUsername = document.getElementById('childUsername');
        const childPassword = document.getElementById('childPassword');
        
        if (String(childName.value).match(reName)) 
            if (!isNaN(childAge.value) || childAge.value<5 ||childAge.value>12)
                if (String(childUsername.value).match(reUsername))
                    if (String(childPassword.value).match(rePassword))
                        formRegitration.submit()
                    else
                        alert('Error: Password should be alphanumeric and at least 4 characters')                        
                else
                    alert('Error: Username should`be in alphabet and at least 4 characters')                
            else
                alert('Error: Only child in the age range 5-12 are supported by this sytem at this moment')
        else
            alert('Error: Name can contain only alphabets and space')
    }

</script>
<?php require('page_footer.php'); ?>
