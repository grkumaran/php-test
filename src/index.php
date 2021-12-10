<?php
// Report all PHP errors
error_reporting(E_ALL);

session_start();

require('database.php');

if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
} elseif (isset($_POST['formSubmission']) && $_POST['formSubmission'] === '1') {
    
    $login_email = $_POST['formEmail'];
    $login_password = $_POST['formPassword'];
    $formNewUser = isset($_POST['formNewUser']) ? true : false;

    if ($formNewUser) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert(['parent_id' => 0, 'name' => '', 'user_id' => '', 'email' => $login_email, 'password' => $login_password, 'age' => 0, 'address'=>'', 'city'=>'', 'country'=>'', 'phone'=>'', 'updatedAge' => '']);
        $result = $dbMongoDB->executeBulkWrite('hackathon.Users', $bulk);

        foreach($result as $res) {
            if ($res->nInserted !== 1) {
                echo 'Error in registering user';
                exit;
            }
        }
    }
    
    
    $flag_user_logged = false;
    $query = new MongoDB\Driver\Query(['email' => $login_email, 'password' => $login_password, 'parent_id' => 0], []);
    $cursor = $dbMongoDB->executeQuery('hackathon.Users', $query);
    foreach ($cursor as $document) {
        $flag_user_logged = true;
    }
    
    if ($flag_user_logged) {
        // user logged-in
        $_SESSION['user_id'] = $document->_id;
        header('Location: home.php');
    } else {
        header('Location: index.php');
    }
}

require('page_header.php');

?>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <form class="col-lg-5 offset-lg-4 " id="formLogin" method="post">
      <div class="row justify-content-center">
        <label for="exampleInputEmail1" class="form-label">Email 
        <input type="email" class="form-control" id="formEmail" name="formEmail" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" id="formPassword" name="formPassword">
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="formNewUser" name="formNewUser">
        <label class="form-check-label" for="exampleCheck1">New parent</label>
      </div>
      <button type="button" class="btn btn-primary" onClick="javascript:handleSubmit()">Submit</button>
      <input type="hidden" name="formSubmission" value="1">
    </form>

    <script language="javascript">
        const reEmail = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        const rePassword = /^[A-Za-z0-9]{8,}$/i;
        
        function handleSubmit() {
            const formLogin = document.getElementById('formLogin');
            const formMail = document.getElementById('formEmail');
            const formPassword = document.getElementById('formPassword');
            const formNewUser = document.getElementById('formNewUser');
            
            if (String(formMail.value).match(reEmail)) 
                if (String(formPassword.value).match(rePassword))
                    formLogin.submit()
                else
                    alert('Error: Password invalid (only alphanumeric character with atleast 8 character length allowed')
            else
                alert('Error: Email address incorrect')
        }
    
    </script>
    
<?php require('page_footer.php'); ?>
