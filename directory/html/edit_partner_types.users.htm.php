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
                    Edit Partner Types
                    <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalAddPartnerType">+ Partner Type</button></span>
                </h3>
            </div>
            <div class="panel-body">
                <table id="partnerTypeTbl" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Partner Type</th>
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
<div class="modal fade" id="modalAddPartnerType" tabindex="-1" role="dialog" aria-labelledby="myModalAddPartnerTypeLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalAddPartnerTypeLabel">Add Partner Type</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="add_partner_type_form" role="form" method="post" action="../_lib/ptaction.php?action=<?= $_core->encode('AddPartnerType'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="Partner Type" class="control-label"><span class="text-danger">*</span> Partner Type</label>
                            <input type="input" name="partner_type" class="form-control" id="partner_type" required>
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

        $('#modalAddPartnerType').on('shown.bs.modal', function () {
            $('#partner_type').focus()
        });

        $("#partner_type").keyup(function () {
            var pType = $("#partner_type").val();
            $("#partner_type").val(ucwords(pType));
        });

        $("#partnerTypeTbl").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ptajax.php?action=GetAllPartnerType",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Partner Type:"
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