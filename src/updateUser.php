<?php

session_start();


if (!isset($_SESSION['user_id'])) 
    header('Location: index.php');

require_once('database.php');

$childData = Array();
if (isset($_GET['_id']) && $_GET['_id'] >0) {
    
    // get Child-User data
    $query = new MongoDB\Driver\Query(['_id' => intval($_GET['_id']), 'parent_id' => intval($_SESSION['user_id'])], []);
    // $query = new MongoDB\Driver\Query(['_id' => intval($_GET['_id'])], []);
    $cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
    foreach ($cursor as $document) {
        array_push($childData, $document->name);
        array_push($childData, $document->user_id);
        array_push($childData, $document->age);
        array_push($childData, $document->updatedAge);
    }
    array_push($childData, Array());        // test details
    
    // get Child-Tests data
    $query = new MongoDB\Driver\Query(['child_id' => intval($_GET['_id'])], ['sort'=>['date'=>1]]);
    $cursor = $dbMongoDB->executeQuery('hackathon.Tests', $query);
    foreach ($cursor as $document) {
        array_push($childData[4], 
            Array(   $document->date, $document->score_max, 
                    $document->score_earn, $document->grade, 
                    // $document->remarks
                    ));
    }
}

require_once('session_header.php');
require_once('page_header.php');

?>

<form class="col-lg-9 offset-lg-2 " id="formRegitration" method="post">
<form class="row g-3 needs-validation" novalidate>
  <div class="col-md-4">
    <label for="validationCustom01" class="form-label">First name</label>
    <input type="text" class="form-control" id="validationCustom01" value="Mark" required>
    <div class="valid-feedback">
      Looks good!
    </div>
  </div>
  <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Last name</label>
    <input type="text" class="form-control" id="validationCustom02" value="Otto" required>
    <div class="valid-feedback">
      Looks good!
    </div>
  </div>
  <div class="col-md-4">
    <label for="validationCustomUsername" class="form-label">Username</label>
    <div class="input-group has-validation">
      <span class="input-group-text" id="inputGroupPrepend">@</span>
      <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
      <div class="invalid-feedback">
        Please choose a username.
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <label for="validationCustom03" class="form-label">City</label>
    <input type="text" class="form-control" id="validationCustom03" required>
    <div class="invalid-feedback">
      Please provide a valid city.
    </div>
  </div>
  <div class="col-md-3">
    <label for="validationCustom04" class="form-label">State</label>
    <select class="form-select" id="validationCustom04" required>
      <option selected disabled value="">Choose...</option>
      <option>...</option>
    </select>
    <div class="invalid-feedback">
      Please select a valid state.
    </div>
  </div>
  <div class="col-md-3">
    <label for="validationCustom05" class="form-label">Zip</label>
    <input type="text" class="form-control" id="validationCustom05" required>
    <div class="invalid-feedback">
      Please provide a valid zip.
    </div>
  </div>
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
      <label class="form-check-label" for="invalidCheck">
        Agree to terms and conditions
      </label>
      <div class="invalid-feedback">
        You must agree before submitting.
      </div>
    </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" type="submit">Submit form</button>
  </div>
</form>
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
<?php require_once('page_footer.php'); ?>
