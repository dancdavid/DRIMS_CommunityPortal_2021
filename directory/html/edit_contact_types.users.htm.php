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
                    Edit Contact Type
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalAddContactType">+ Contact Type</button></span>
                </h3>
            </div>
            <div class="panel-body">
                <table id="contactTypeTbl" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Contact Type</th>
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
<div class="modal fade" id="modalAddContactType" tabindex="-1" role="dialog" aria-labelledby="myModalAddContactTypeLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalAddContactTypeLabel">Add Contact Type</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="add_contact_type_form" role="form" method="post" action="../_lib/ctaction.php?action=<?= $_core->encode('AddContactType'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="Contact Type" class="control-label"><span class="text-danger">*</span> Contact Type</label>
                            <input type="input" name="contact_type" class="form-control" id="contact_type" required>
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

        $('#modalAddContactType').on('shown.bs.modal', function () {
            $('#contact_type').focus()
        });

        $("#contact_type").keyup(function () {
            var cType = $("#contact_type").val();
            $("#contact_type").val(ucwords(cType));
        });

        $("#contactTypeTbl").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ctajax.php?action=GetAllContactList",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Contact Type:"
            },
            "ordering": false,
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,

            "aoColumns": [
                /* contact type */ {
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