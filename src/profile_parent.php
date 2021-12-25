<?php

session_start();

if (!isset($_SESSION['user_id'])) 
    header('Location: index.php');

require_once('database.php');

// input data processing
$err_msg = '';
if (isset($_POST['formSubmission']) && $_POST['formSubmission'] === '1') {
    // update parent profile
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
            [ '_id' => $_SESSION['user_id'], 'parent_id' => 0 ],
            [ '$set' => [ 'name' => $_POST['parentName'], 'phone' => $_POST['parentPhone'] ]]
        );
    $result = $dbMongoDB->executeBulkWrite('hackathon.Users', $bulk);
    
    if ($result->getModifiedCount() > 0) {
        $err_msg = 'Profile updated';
    } else {
        $err_msg = 'Profile update failed';
    }
}

require_once('session_header.php');
require_once('page_header.php');

?>
<p>&nbsp;</p>
<p>&nbsp;</p>

<form class="col-lg-9 offset-lg-2 " id="formRegitration" method="post">
<div class="card  col-lg-6 offset-lg-3 ">
  <div class="card-header h5">
    Your profile
  </div>
  <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-7">
            <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="parentName"  id="parentName" >
          </div>
        </div>
  </div>
  <div class="card-body">
        <div class="row g-3">
          <div class="col-sm-5">
            <input type="text" class="form-control" placeholder="Phone number" aria-label="Phone number" name="parentPhone" id="parentPhone" >
          </div>
        </div>
        <input type="hidden" name="formSubmission" value="1">
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
        const parentName = document.getElementById('parentName');
        const parentPhone = document.getElementById('parentPhone');
        const childUsername = document.getElementById('childUsername');
        const childPassword = document.getElementById('childPassword');
        
        if (String(parentName.value).match(reName)) 
            if (!isNaN(parentPhone.value) && !parentPhone.value<1000000 && String(parentPhone.value).match(reAge))
                formRegitration.submit()
            else
                alert('Error: Phone number invalid, it should be only numeric and at least 7 digits length')
        else
            alert('Error: Name can contain only alphabets and space')
    }

</script>
<?php require('page_footer.php'); ?>
