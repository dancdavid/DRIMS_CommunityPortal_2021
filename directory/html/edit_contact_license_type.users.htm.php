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
                    Edit Contact License Type
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalAddContactLicenseType">+ Contact License Type</button></span>
                </h3>
            </div>
            <div class="panel-body">
                <table id="contactLicenseTypeTbl" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Contact License Type</th>
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
<div class="modal fade" id="modalAddContactLicenseType" tabindex="-1" role="dialog" aria-labelledby="myModalAddContactLicneseTypeLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalAddContactLicneseTypeLabel">Add Contact License Type</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="add_contact_type_form" role="form" method="post" action="../_lib/cltaction.php?action=<?= $_core->encode('AddContactLicenseType'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="Contact License Type" class="control-label"><span class="text-danger">*</span> Contact License Type</label>
                            <input type="input" name="contact_license_type" class="form-control" id="contact_license_type" required>
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

        $('#modalAddContactLicenseType').on('shown.bs.modal', function () {
            $('#contact_license_type').focus()
        });

        $("#contact_license_type").keyup(function () {
            var cType = $("#contact_license_type").val();
            $("#contact_license_type").val(ucwords(cType));
        });

        $("#contactLicenseTypeTbl").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/cltajax.php?action=GetAllContactLicenseList",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Contact License Type:"
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