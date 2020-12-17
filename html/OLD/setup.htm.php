<div class="container" style="padding-top:70px;">

    <?php
    //lets create the basic tables
    $_db = new db();
    $_db->executeFile(ROOT . '/sql/tables.sql');

    //create upload directory for images and files
    if (!file_exists(ROOT . '/uploads')) {
        mkdir(ROOT . '/uploads', 0755);
        echo '<div class="panel panel-success">
                  <div class="panel-heading">Successful</div>
                  <div class="panel-body">
                    Directory /uploads successful
                  </div>
                </div>';
    } else {
        echo '<div class="panel panel-danger">
                  <div class="panel-heading">DIR Uploads</div>
                  <div class="panel-body">
                    Directory /uploads already exists
                  </div>
                </div>';
    }
    
    if ($_db->checkEmailExists("admin@admin", "users")) {
        include ROOT . '/html/inc.setupAdminPassword.html';
    }

    ?>

</div>


