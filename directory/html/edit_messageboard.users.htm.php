<?php
$_core = new core();
$_db = new db();
$_level = new Level1();

$message_id = $_core->decode($_core->gpGet('id'));

$dbh = $_db->initDB();
$qry = "select * from cp_message_board where id = :id";
$sth = $dbh->prepare($qry);
$sth->execute(array(":id" => $message_id));
$f = $sth->fetch(PDO::FETCH_OBJ);
?>
<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT MESSAGE</h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" enctype="multipart/form-data" id="postMessage" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('editMessage'); ?>">
                <input type="hidden" name="id" value="<?php echo $_core->gpGet('id'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-8  col-xs-8">
                            <label for="title" class="control-label">Title</label>
                            <input type="input" name="title" class="form-control" id="title" value="<?php echo $f->title; ?>" required>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <label for="level" class="control-label"><?= $_SESSION['Level1_Label'] ?></label>
                            <select id="level1" name="level_1[]" class="form-control" multiple required>
                                <?= $_level->BuildLevelDropDown($f->level_1) ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Message</label>
                            <textarea name="message" rows="10"><?php echo $f->message; ?></textarea> 
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Status</label>
                            <select name="status" class="form_control">
                                <?php echo "<option value='{$f->status}'>{$f->status}</option>"; ?>
                                    <option value="ACTIVE">ACTIVE</option>
                                    <option value="DELETED">DELETE</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-primary pull-right">EDIT <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="../js/tinymce.min.js"></script>
<script>
    $(function () {
        $("#level1").select2({
            theme: "bootstrap"
        });

        tinymce.init({
            selector: 'textarea',
            menubar: false,
            branding: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            default_link_target: "_blank",
            target_list: false,
            link_assume_external_targets: true
        });

        $(document).on('focusin', function(e) {
            var target = $(e.target);
            if (target.closest(".mce-window").length || target.closest(".tox-dialog").length) {
                e.stopImmediatePropagation();
                target = null;
            }
        });

    });
</script>