<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_db = new db();
?>
<style>
    .dataTables_wrapper .dt-buttons {
        float: right;
    }
    .loader-window {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 1051;
        background: rgba(255,255,255, 0.7);
    }

    .loader {
        position: absolute;
        top:30%;
        left:50%;
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #858585;
        width: 60px;
        height: 60px;
        margin-top:-30px;
        margin-left:-30px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Services / Resources / Training
                <a href="add_services2" class="btn btn-xs btn-danger pull-right">+ Add Service Resource Training</a>
            </h3>
        </div>
        <div class="panel-body" style="max-height: 700px;overflow-y: scroll;">

        <table class="table" id="listAvailableServices">
            <thead>
            <tr>

                <th>CATEGORY</th>
                <th>TYPE</th>
                <th>TYPE STATUS</th>
                <th>ITEM NAME</th>
                <th>SUB-ITEM</th>
                <th>SUB-ITEM 2</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        </div>
    </div>
</div>

<div id="LoaderWindow" class="loader-window">
    <div id="Loader" class="loader"></div>
</div>

<!--<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/sc-2.0.0/datatables.min.js"></script>
<script>
    $(function () {
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});

        setTimeout( function() {
            $("#LoaderWindow").hide();
        }, 5000);


        $("#listAvailableServices").DataTable({
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "sDom": '<"top"Bf>rtp<"clear">',
            // "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ajax.php?action=GetServices2",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Services:"
            },
            "bDeferRender": true,
            "iDeferLoading": 5,
            "iDisplayLength": 10,
            "buttons": [
                {
                    'extend': 'excelHtml5',
                    'text': 'Excel',
                    'title': 'Services / Resources / Training LIST - <?= date('M d, Y') ?>'
                },
                {
                    'extend': 'csvHtml5',
                    'title': 'Services / Resources / Training LIST - <?= date('M d, Y') ?>'
                },
                {
                    'extend': 'pdfHtml5',
                    'text': 'PDF',
                    'orientation': 'landscape',
                    'title': 'Services / Resources / Training LIST - <?= date('M d, Y') ?>'
                },
                {
                    'extend': 'print',
                    'title': 'Services / Resources / Training LIST - <?= date('M d, Y') ?>'
                }
            ]
            // order: [[1, 'asc']],
            // rowGroup: {
            //     dataSrc: [ 1 ]
            // }
            // ,
            // columnDefs: [ {
            //     targets: [ 1 ],
            //     visible: false
            // } ]
        });
    });
</script>


