<?php
$_core = new core();

$teamName = 'Team';
$admin = false;

if (!empty($_GET['tid'])) {
    $_team = new Teams($_core->decode($_core->gpGet('tid')));
    $teamName = $_team->GetTeamName();
    $admin = $_team->TeamMemberAdmin();
}

$_db = new db();

$message_id = $_core->decode($_core->gpGet('id'));

$dbh = $_db->initDB();
$qry = "select * from cp_team_message_board where id = :id";
$sth = $dbh->prepare($qry);
$sth->execute(array(":id" => $message_id));
$f = $sth->fetch(PDO::FETCH_OBJ);
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT TEAM MESSAGE</h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" enctype="multipart/form-data" id="postMessage" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('editMessage'); ?>">
                <input type="hidden" name="id" value="<?php echo $_core->gpGet('id'); ?>">
                <input type="hidden" name="tid" value="<?php echo $_core->gpGet('tid'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Title</label>
                            <input type="input" name="title" class="form-control" id="title" value="<?php echo $f->title; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Message</label>
                            <textarea name="message" rows="10"><?php echo $f->message; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12 attachment_wrapper">
                            <label class="control-label" for="attachments">Add File(s)</label>
                            <input id="upload_attachment_message" name="upload_attachment[]" type="file" multiple>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php
                        $_files = new TeamMessageFiles();
                        echo $_files->DeleteFilesTable($message_id,'cp_team_message_board_file_upload');
                        ?>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Status</label>
                            <select name="status" class="form_control">
                                <?php echo "<option value='{$f->status}'>{$f->status}</option>"; ?>
                                <option>______________</option>
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
<script type="text/javascript" src="../js/fileinput.min.js"></script>
<script type="text/javascript" src="../js/fileUpload.js"></script>

<script>
    $(function () {
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

        $('.delete').on('click', function(e) {
            e.preventDefault();
            var fileId = $(this).data('id');
            $.post("../_lib/tmajax.php?action=DeleteMessageFile", { id: fileId });
            $(this).closest('tr').remove();
        });

    });
</script>
