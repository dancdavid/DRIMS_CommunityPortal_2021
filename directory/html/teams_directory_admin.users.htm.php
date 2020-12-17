<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}
$_level = new Level1();
?>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Teams
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="collapse" href="#collapseAddTeam" aria-expanded="false" aria-controls="collapseAddTeam">+ Team</button></span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="collapse" id="collapseAddTeam">
                    <div class="well">
                        <form class="form-horizontal" id="addTeamForm" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('AddTeam'); ?>">

                            <div class="form-group">
                                <div class="col-sm-6  col-xs-6">
                                    <label for="team name" class="control-label"><span class="text-danger">*</span> Team Name</label>
                                    <input type="input" name="team_name" class="form-control" id="team_name" required>
                                </div>
                                <div class="col-sm-6  col-xs-6">
                                    <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                                    <select id="level1" name="level_1[]" class="form-control" style="width:100%" multiple required>
                                        <?= $_level->BuildLevelDropDown() ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12  col-xs-12">
                                    <label for="team name" class="control-label"><span class="text-danger">*</span> Description</label>
                                    <input type="input" name="description" class="form-control" id="description" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12"><button type="submit" class="btn btn-success pull-right">SAVE</button></div>
                            </div>

                        </form>
                    </div>
                </div>
                <table id="teamTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Team Name</th>
                        <th><?= $_SESSION['Level1_Label'] ?></th>
                        <th>Description</th>
                        <th>Active Members</th>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script>
    $(function () {

        $("#level1").select2({
            theme: "bootstrap"
        });

        $('#collapseAddTeam').on('shown.bs.collapse', function () {
            $('#team_name').focus();
        });

        $('#collapseAddTeam').on('hidden.bs.collapse', function () {
            $('#team_name').val('');
            $('#description').val('');
        });

        $("#team_name").keyup(function () {
            var tName = $("#team_name").val();
            $("#team_name").val(ucwords(tName));
        });

        $("#teamTable").DataTable({
            "bProcessing": true,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/tmajax.php?action=GetTeams",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Teams:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']]
        });
    });
</script>