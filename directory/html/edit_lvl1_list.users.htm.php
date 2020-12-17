<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}
?>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Edit <?= $_SESSION['Level1_Label'] ?>
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalAddLevel">+ Add <?= $_SESSION['Level1_Label'] ?></button></span>
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-inline" method="post" action="../_lib/lvlaction.php?action=<?= $_core->encode('RenameLevel1Label') ?>">
                    <div class="form-group">
                        <label for="level1tag" class="label-control">Rename <?= $_SESSION['Level1_Label'] ?> Label</label>
                        <input type="text" name="label" id="level1Label" class="form-control" required>
                    </div>
                    <div class="form-group"><button type="submit" class="btn btn-danger">RENAME</button></div>
                </form>
                <div class="col-md-12"><hr></div>
                <table id="level1List" class="table table-striped">
                    <thead>
                    <tr>
                        <th><?= $_SESSION['Level1_Label'] ?></th>
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


<!-- ADD LEVEL -->
<div class="modal fade" id="modalAddLevel" tabindex="-1" role="dialog" aria-labelledby="myModalAddLevelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLevel1Label">Add <?= $_SESSION['Level1_Label'] ?></h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="add_level1_form" role="form" method="post" action="../_lib/lvlaction.php?action=<?= $_core->encode('AddLevel'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="Level 1" class="control-label"><span class="text-danger">*</span> <?= $_SESSION['Level1_Label'] ?></label>
                            <input type="input" name="level_1" class="form-control" id="level1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script>
    $(function () {

        $('#modalAddLevel').on('shown.bs.modal', function () {
            $('#level1').focus()
        });

        $("#level1").keyup(function () {
            var lvl1 = $("#level1").val();
            $("#level1").val(ucwords(lvl1));
        });

        $("#level1Label").keyup(function () {
            var lvl1Label = $("#level1Label").val();
            $("#level1Label").val(ucwords(lvl1Label));
        });

        $("#level1List").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/lvlajax.php?action=GetAllLvl1List",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "ordering": false,

            "aoColumns": [
                /* level 1*/ {
                    "sWidth": "100%",
                    "bSortable": false
                },
                {
                    "visible" : false,
                    "bSortable": false,
                    "searchable" : true
                }
            ]
        });
    });
</script>