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
                    Edit Team Links - <?= $teamName ?>
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalLink">+ ADD LINK</button></span>
                </h3>
            </div>
            <div class="panel-body">
                <table id="teamLinks" class="table table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>TItle</th>
                        <th>Url</th>
                        <th>Description</th>
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

<div class="modal fade" id="modalLink" tabindex="-1" role="dialog" aria-labelledby="myModalLinkLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLinkLabel">Add Team Link - <?= $teamName ?></h4>
            </div>
            <form class="form-horizontal" id="addLink" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('AddTeamLink'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-6  col-xs-6">
                            <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                            <input type="input" name="title" class="form-control" id="link_title" required>

                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <label for="url" class="control-label">URL <span class="text-danger">*</span></label>
                            <input type="url" name="url" class="form-control" id="url" placeholder="http://" required>
                            <div id="linkError" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="description">Description</label>
                            <input type="input" name="description" class="form-control" id="description">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>




<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script>
    $(function () {

        $("#teamLinks").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/tmajax.php?action=GetAllTeamLinks&tid=" + '<?= $_GET['tid'] ?>',
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Links:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']]
        });

        $("#url").change(function() {
            if (!/^https*:\/\//.test(this.value)) {
                this.value = "http://" + this.value;
            }
        });

        $("#link_title").keyup(function () {
            var title = $("#link_title").val();
            $("#link_title").val(ucwords(title));
        });
    });
</script>
