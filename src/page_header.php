<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <title>Children's Happiness Index</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #a3d2ed;">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><h2>Children's Happiness Index</h2></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <?php if (isset($_SESSION['user_id'])) { ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" aria-current="page" href="home.php">Home</a></li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Profile
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php foreach($data_child as $data) { ?>
                    <li><a class="dropdown-item" href="profile_child.php?_id=<?php echo $data[0] ?>"><?php echo $data[2] ?></a></li>
                <?php } ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="profile_parent.php">Your profile</a></li>
                <li><a class="dropdown-item" href="addChild.php">Add Child</a></li>
              </ul>
            </li>
            
            <?php if (count($data_child) <= 0 ) { ?>
            <li class="nav-item dropdown disabled"></li>
            <?php } else { ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Report
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php foreach($data_child as $data) { ?>
                    <li><a class="dropdown-item" href="childReport.php?_id=<?php echo $data[0] ?>"><?php echo $data[2] ?></a></li>
                <?php } ?>
              </ul>
            </li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" href="help.php">Help</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php" >Logout</a></li>
          </ul>
        </div>
        <?php } ?>
      </div>
    </nav>
<?php if (isset($_SESSION['user_id'])) { ?>
    <div class="card">
      <div class="card-body">
        Hi <b><?php echo $data_parent[2]; ?></b>
      </div>
    </div>
<?php } ?>
