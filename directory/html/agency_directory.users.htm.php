<style>
    .dataTables_wrapper .dt-buttons {
        float: right;
    }
    table.dataTable tbody td.srt-buttons {
        vertical-align: middle;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            ORGANIZATION DIRECTORY

            <?php
            $_core = new core();
            if (UserAccess::ManageLevel1()) {
                echo '<a href="add_agency" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> ADD NEW ORGANIZATION</a>';
            }
            ?>

            <a href="print_agency" id="print_friendly" target="_blank" class="btn btn-xs btn-danger pull-right">Print <span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
        </h3>
    </div>
    <div class="panel-body" style="max-height: 700px;overflow-y: scroll;">
        <table id="orgList" class="table table-bordered table-striped table-hover" width="100%">
            <thead>
            <tr>
                <th>ORG/LOCATION NAME</th>
                <th>TYPE</th>
                <th><?= strtoupper($_SESSION['Level1_Label']) ?></th>
                <th>PARTNER TYPE</th>
                <th>CONTACTS</th>
                <th>ADDRESS</th>
                <th>CITY</th>
                <th>STATE</th>
                <th>ZIPCODE</th>
                <th>PHONE</th>
                <th class="text-center"><a href="#" data-toggle="tooltip" title="SERVICE RESOURCE TRAINING"<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></th>
                <th>CATEGORY</th>
                <th>SRT ITEM NAME</th>
                <th>SRT CUSTOM ITEM NAME</th>
                <th>SRT TYPE</th>
                <?php
                if (UserAccess::ManageLevel1())
                {
                    echo '<th>STATUS</th>';
                }
                ?>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="locationServicesModal" tabindex="-1" role="dialog" aria-labelledby="locationServicesLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="locationServicesLabel">Services Resources Training</h4>
            </div>
            <div class="modal-body" style="max-height: 700px;overflow-y: scroll;">
                <table class="table" id="listAvailableLocationServices" width="100%">
                    <thead>
                    <tr>
                        <th>CATEGORY</th>
                        <th>TYPE</th>
                        <th>ITEMS</th>
<!--                        <th>SUB-ITEM</th>-->
<!--                        <th>SUB-ITEM 2</th>-->
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/sc-2.0.0/datatables.min.js"></script>
<script type="text/javascript" src="../js/agencyList.js"></script>



