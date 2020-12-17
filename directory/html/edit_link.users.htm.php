<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_db = new db();
$_level = new Level1();
$_dash = new Dashboard();
$table = ($_GET['loc'] === 'dash') ? "cp_dashboard_links" : "cp_team_dashboard_links";
$f = $_dash->GetLinkData($_core->decode($_core->gpGet('id')), $table);
?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Edit Dashboard Link
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" id="editDocs" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('EditLink'); ?>">
                    <input type="hidden" name="lid" value="<?= $_GET['id'] ?>">
                    <input type="hidden" name="loc" value="<?= $_GET['loc'] ?>">
                    <div class="modal-body">

                        <div class="form-group">
                            <div class="col-sm-6  col-xs-6">
                                <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                                <input type="input" name="title" class="form-control" id="link_title" value="<?= $f['title'] ?>" required>

                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="url" class="control-label">URL <span class="text-danger">*</span></label>
                                <input type="url" name="url" class="form-control" id="url" placeholder="http://" value="<?= $f['url'] ?>" required>
                                <div id="linkError" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-sm-6 col-xs-6">
                                <label for="status" class="control-label">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <?= $_db->buildDropMenu('cp_user_status',$f['status']) ?>
                                </select>
                            </div>


                            <div class="col-sm-6 col-xs-6">
                                <label class="control-label" for="description">Description</label>
                                <input type="input" name="description" class="form-control" id="description" value="<?= $f['description'] ?>">
                            </div>
                        </div>
                        <div class="form-group">

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
                        <button type="submit" class="btn btn-success pull-right">EDIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(function () {
        $("#level1").select2({
            theme: "bootstrap"
        });

        $("#url").change(function() {
            if (!/^https*:\/\//.test(this.value)) {
                this.value = "http://" + this.value;
            }
        });

    });
</script>
