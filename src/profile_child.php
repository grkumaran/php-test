<?php

session_start();

if (!isset($_SESSION['user_id'])) 
    header('Location: index.php');

require_once('database.php');

$err_msg = '';
$child_name = '';
$child_age = 0;
$child_user_id = '';

if (isset($_POST['formSubmission']) && $_POST['formSubmission'] === '1') {
    // update child profile
    $bulk = new MongoDB\Driver\BulkWrite;
    
    if (strlen($_POST['childPassword']) > 0) {          // password field is set
        $bulk->update(
                ['_id' => intval($_POST['_id']), 'parent_id' => intval($_SESSION['user_id'])],
                [ '$set' => [ 
                        'name' => $_POST['childName'], 
                        'age' => $_POST['childAge'],
                        'user_id' => $_POST['childUsername'],
                        'password' => $_POST['childPassword']
                        ]]
            );
    } else {
        $bulk->update(
                ['_id' => intval($_POST['_id']), 'parent_id' => intval($_SESSION['user_id'])],
                [ '$set' => [ 
                        'name' => $_POST['childName'], 
                        'age' => $_POST['childAge'],
                        'user_id' => $_POST['childUsername']
                        ]]
            );
    }
    $result = $dbMongoDB->executeBulkWrite('hackathon.Users', $bulk);
    
    if ($result->getModifiedCount() > 0) {
        $err_msg = 'Profile updated';
    } else {
        $err_msg = 'Profile update failed';
    }
}


// $childId = (isset($_GET['_id']) && intval($_GET['_id']) > 0) ? intval($_GET['_id'])
                // : (isset($_POST['_id']) && intval($_POST['_id']) > 0) ? intval($_POST['_id'])
                // : -1;
$childId = -1;
if (isset($_GET['_id']) && intval($_GET['_id']) > 0) {
    $childId = intval($_GET['_id']);
} elseif (isset($_POST['_id']) && intval($_POST['_id']) > 0) {
    $childId = intval($_POST['_id']);
}
    
if ($childId > 0) {
// get child details
        $query = new MongoDB\Driver\Query(['_id' => intval($childId), 'parent_id' => intval($_SESSION['user_id'])], []);
        $cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
        foreach ($cursor as $document) {
            if (isset($document->_id) && $document->_id > 0) {
                $child_name = $document->name;
                $child_age = $document->age;
                $child_user_id= $document->user_id;
                break;
            }
        }
        
}

require_once('session_header.php');
require_once('page_header.php');

?>
<p>&nbsp;</p>
<p>&nbsp;</p>

<form class="col-lg-9 offset-lg-2 " id="formRegitration" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<div class="card  col-lg-6 offset-lg-3 ">
  <div class="card-header h5">
    <?php echo $child_name; ?>'s profile
  </div>
 
  <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-7">
            <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="childName"  id="childName" value="<?php echo $child_name; ?>">
          </div>
          <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="Age" aria-label="Age" name="childAge" id="childAge" value="<?php echo $child_age; ?>">
          </div>
        </div>
   </div>
  <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-7">
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" name="childUsername" id="childUsername" value="<?php echo $child_user_id; ?>">
          </div>
          <div class="col-sm-5">
            <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="childPassword" id="childPassword" value="">
          </div>
          <div id="emailHelp" class="form-text">*If password field is not empty, password will be updated in record otherwise not</div>
        </div>
        <input type="hidden" name="formSubmission" value="1">
        <input type="hidden" name="_id" value="<?php echo $childId; ?>">
  </div>
  <div class="card-footer text-muted">
    <?php if ($err_msg != '') echo "<p>$err_msg</p>"; ?>
    <button type="button" class="btn btn-primary" onClick="javascript:handleSubmit()">Update profile</button>
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
                    if (String(childPassword.value).length > 0) 
                        if (String(childPassword.value).match(rePassword))
                            formRegitration.submit()
                        else
                            alert('Error: Password should be alphanumeric and at least 4 characters')
                    else
                        formRegitration.submit()
                else
                    alert('Error: Username should`be in alphabet and at least 4 characters')                
            else
                alert('Error: Only child in the age range 5-12 are supported by this sytem at this moment')
        else
            alert('Error: Name can contain only alphabets and space')
    }

</script>
<?php require('page_footer.php'); ?>
