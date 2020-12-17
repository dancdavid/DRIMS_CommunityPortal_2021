<?php
$_core = new core();
if (empty($_GET['tid'])) {
    $_core->redir('directory');
}
$_team = new Teams($_core->decode($_core->gpGet('tid')));
$teamName = $_team->GetTeamName();
?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Edit Team Docs - <?= $teamName ?>
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#addDocumentModal">+ ADD DOCUMENT</button></span>
                </h3>
            </div>
            <div class="panel-body">
                <table id="dashboardDocs" class="table table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<!-- Modal Document-->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalDocumentLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalDocumentLabel">Add Team Document - <?= $teamName ?></h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="addDocumentForm" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('AddTeamDocument'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="doc_title" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="description" class="control-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label class="control-label" for="add_doc">Add Document</label>
                            <input id="upload_docs" name="upload_docs" type="file">
                            <small class="text-danger">* Allowed File Extensions: .pdf, .doc, .docx, .xls, .xlsx, .txt, .zip, .ppt, .pptx</small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="../js/fileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script>
    $(function () {
        $("#dashboardDocs").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/tmajax.php?action=GetAllTeamDocs&tid=" + '<?= $_GET['tid'] ?>',
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Docs:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']]
        });

        $('#upload_docs').fileinput({
            showPreview: false,
            allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'ppt', 'pptx']
        });

        $("#doc_title").keyup(function () {
            var title = $("#doc_title").val();
            $("#doc_title").val(ucwords(title));
        });


    });
</script>
