<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_team = new Teams($_core->decode($_core->gpGet('tid')));

$f = $_team->GetTeamData();

$_level = new Level1();
?>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Editing Team
                    <span>
                        <a href="team_messageboard_list?tid=<?= $_GET['tid'] ?>" class="btn btn-xs btn-primary">Edit Posts</a>
                        <a href="edit_team_event?tid=<?= $_GET['tid'] ?>" class="btn btn-xs btn-primary">Edit Calendar</a>
                        <a href="edit_team_links?tid=<?= $_GET['tid'] ?>" class="btn btn-xs btn-primary">Edit Links</a>
                        <a href="edit_team_documents?tid=<?= $_GET['tid'] ?>" class="btn btn-xs btn-primary">Edit Documents</a>
                    </span>
                    <span class="pull-right"><a href="#" onclick="window.history.go(-1); return false;" class="text-primary"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Team Directory</a></span>
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" id="editTeamForm" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('EditTeam'); ?>">
                    <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                    <div class="form-group">
                        <div class="col-sm-6  col-xs-6">
                            <label for="team name" class="control-label"><span class="text-danger">*</span> Team Name</label>
                            <input type="input" name="team_name" class="form-control" id="team_name" value="<?= $f['team_name'] ?>" required>
                        </div>
                        <div class="col-sm-6  col-xs-6">
                            <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                            <select id="level1" name="level_1[]" class="form-control" style="width:100%" multiple required>
                                <?= $_level->BuildLevelDropDown($f['level_1']) ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10  col-xs-10">
                            <label for="description" class="control-label"><span class="text-danger">*</span> Description</label>
                            <input type="input" name="description" class="form-control" id="description" value="<?= $f['description'] ?>" required>
                        </div>
                        <div class="col-sm-2  col-xs-2">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" class="form-control">
                                <?= $_team->buildDropMenu('cp_user_status',$f['status'])?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-md-offset-4">
                            <button type="submit" class="btn btn-danger">EDIT TEAM DATA</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" data-toggle="collapse" href="#collapseAddTeamMembers" aria-expanded="false" aria-controls="collapseAddTeam">Add Members</button>

                        </div>
                    </div>

                </form>

                <div>
                    <?= $_team->BuildSelectedMembers() ?>
                </div>

                <div class="collapse" id="collapseAddTeamMembers">
                    <hr></hr>
                    <button type="button" id="saveTeamMembers" class="btn btn-success">Save Selected Team Members</button>
                    <br><br>
                    <form method="post" id="addMembersForm" action="../_lib/tmaction.php?action=<?= $_core->encode('AddTeamMembers') ?>">
                        <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                        <table id="addMembers" class="table table-striped">
                            <thead>
                            <tr>
                                <th>ADD</th>
                                <th>Admin</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Org Name</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </form>
                </div>

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

        $("#team_name").keyup(function () {
            var tName = $("#team_name").val();
            $("#team_name").val(ucwords(tName));
        });

        var table = $("#addMembers").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/tmajax.php?action=ListPotentialTeamMembers&tid=<?= $_GET['tid'] ?>",
            "oLanguage": {
                "sZeroRecords": "No records to display"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[2, 'asc']],
            "aoColumns": [
                {"sWidth": '5%', "bSortable": false},
                {"sWidth": '5%', "bSortable": false},
                {}, {}, {}
            ]
        });

        $('#saveTeamMembers').on('click', function (e) {
            e.preventDefault();
            $("#addMembersForm").submit();
        });

    });
</script>