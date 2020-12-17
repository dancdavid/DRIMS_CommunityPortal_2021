<?php
$_core = new core();
if (!UserAccess::ManageLevel2()) {
    $_core->redir('directory');
}

$_level = new Level1();
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <ul class="nav nav-tabs">
            <li role="presentation" ><a href="edit_level12_users">Level 1 Users</a></li>
            <?php
            if (UserAccess::ManageLevel2()) {
                echo '<li role="presentation" class="active"><a href="#">Level 2 Users</a></li>';
            }
            ?>

        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">


                <button class="btn btn-sm btn-danger pull-right" data-toggle="collapse" href="#collapseAddUser" aria-expanded="false" aria-controls="collapseAddUser">+ ADD LEVEL
                    2 USER</button>
                <br><br>

                <div class="collapse" id="collapseAddUser">
                    <div class="well">
                        <table width="100%" id="level2AddForm" class="table table-bordered">
                            <form class="form-horizontal" enctype="multipart/form-data" id="addLevel2User" role="form" method="post"
                                  action="../_lib/agencyaction.php?action=<?= $_core->encode('AddLevel2User'); ?>">
                                <input type="hidden" name="aid" value="<?= $_core->encode($_SESSION['agency_id']) ?>">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="col-sm-6  col-xs-6">
                                                <label for="fname" class="control-label">First Name <span class="text-danger">*</span></label>
                                                <input type="input" name="first_name" class="form-control" id="first_name" required>
                                            </div>
                                            <div class="col-sm-6  col-xs-6">
                                                <label for="lname" class="control-label">Last Name <span class="text-danger">*</span></label>
                                                <input type="input" name="last_name" class="form-control" id="last_name" required>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                                <tr>
                                    <td>

                                        <div class="form-group">
                                            <div class="col-sm-4 col-xs-4" id="emailDiv">
                                                <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" id="contact_email" required>
                                                <span id="emailIcon"></span>
                                                <div id="error"></div>
                                            </div>
                                            <div class="col-sm-4 col-xs-4">
                                                <label class="control-label" for="phone">Phone <span class="text-danger">*</span></label>
                                                <input type="input" name="phone" class="form-control" id="contact_telephone" required>
                                            </div>
                                            <div class="col-sm-4 col-xs-4">
                                                <label for="cell" class="control-label">Cell</label>
                                                <input type="input" name="alt_phone" class="form-control" id="contact_cellphone">
                                            </div>

                                        </div>

                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="col-sm-4 col-xs-4">
                                                <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                                                <select id="level1" name="level_1[]" class="form-control" style="width: 100%" multiple required>
                                                    <?= $_level->BuildLevelDropDown() ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="col-sm-2 col-xs-2 col-sm-offset-10 col-xs-offset-10">
                                            <button type="submit" class="btn btn-success pull-right">ADD USER</button>
                                        </div>
                                    </td>
                                </tr>

                            </form>
                        </table>
                    </div>
                </div>

                <table id="level2users" class="table table-bordered table-striped" style="font-size:12px" width="100%">
                    <thead>
                    <tr>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>PHONE</th>
                        <th><?= strtoupper($_SESSION['Level1_Label']) ?></th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script src="../js/maskedinput.js"></script>
<script src="../js/validateEmail.js"></script>
<script>
    $(function () {

        $("#level1").select2({
            theme: "bootstrap"
        });

        $("#contact_telephone").mask("999-999-9999", {placeholder: " "});
        $("#contact_cellphone").mask("999-999-9999", {placeholder: " "});

        $("#first_name").keyup(function () {
            var firstName = $("#first_name").val();
            $("#first_name").val(ucwords(firstName));
        });

        $("#last_name").keyup(function () {
            var lastName = $("#last_name").val();
            $("#last_name").val(ucwords(lastName));
        });

        $("#level2users").DataTable({
            "bProcessing": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"top"fl>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ajax.php?action=getLevel2Users",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Users:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": 25,
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });

    });
</script>
