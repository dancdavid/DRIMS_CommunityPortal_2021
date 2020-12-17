<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_db = new db();
$_level = new Level1();
$_dash = new Dashboard();
$table = ($_GET['loc'] === 'dash') ? "cp_file_upload" : "cp_team_file_upload";
$f = $_dash->GetDocData($_core->decode($_core->gpGet('id')), $table);

$team = ($_GET['loc'] === 'team') ? 'TEAM' : 'Dashboard';
?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Edit <?= $team ?> Document
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" enctype="multipart/form-data" id="addDocumentForm" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('EditDocument'); ?>">
                    <input type="hidden" name="did" value="<?= $_GET['id'] ?>">
                    <input type="hidden" name="loc" value="<?= $_GET['loc'] ?>">
                    <div class="modal-body">

                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                                <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="doc_title" class="form-control" value="<?= $f['title'] ?>" required>
                            </div>
                            <div class="col-sm-6  col-xs-6">
                                <label for="status" class="control-label">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <?= $_db->buildDropMenu('cp_user_status',$f['status']) ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6  col-xs-6">
                                <label for="description" class="control-label">Description</label>
                                <input type="text" name="description" id="description" class="form-control" value="<?= $f['description'] ?>">
                            </div>
                            <?php
                            if (!empty($_GET['loc']) && $_GET['loc'] === 'dash')
                            {
                                echo '';
                                echo '<div class="col-sm-6 col-xs-6">';
                                echo '<label for="level1" class="control-label">'. $_SESSION['Level1_Label'] .' <span class="text-danger">*</span></label>';
                                echo '<select id="level1" name="level_1[]" class="form-control" multiple required>';
                                echo $_level->BuildLevelDropDown($f['level_1']);
                                echo '</select>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12  col-xs-12">
                                <label class="control-label" for="add_doc">Change Existing Document</label>
                                <input id="upload_docs" name="upload_docs" type="file">
                                Current Document: <div class="text-danger"><?= $f['file_name'] ?></div>
                            </div>
                        </div>
                        <script>
                            $(function () {
                                $('#upload_docs').fileinput({
                                    showPreview: false,
                                    allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'ppt', 'pptx']
                                });
                            })
                        </script>
                        <button type="submit" class="btn btn-success pull-right" data-dismiss="modal">EDIT</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
<script type="text/javascript" src="../js/fileinput.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(function () {
        $("#level1").select2({
            theme: "bootstrap"
        });
    });
</script>
