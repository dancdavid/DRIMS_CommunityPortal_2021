<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}
?>
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT MESSAGE BOARD</h3>
        </div>
        <div class="panel-body">
            <table id="messageBoardList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th><?= $_SESSION['Level1_Label'] ?></th>
                    <th>STATUS</th>
                    <th>SUBMITTED BY</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/messageBoardAdminList.js"></script>