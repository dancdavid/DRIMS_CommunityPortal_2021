<div class="col-sm-12 col-xs-12">
    <h4 class="text-danger">COMMITTEE LIST</h4>
    <?php
    if ($_SESSION['user_type'] === 'ADMIN') {
        echo '<span class="pull-right"><a href="add_committee" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> ADD NEW COMMITTEE</a></span>';
    }
    ?>

    <table id="committeeList" class="table table-bordered table-striped" style="font-size:12px;">
        <thead>
            <tr>
                <th>COMMITTEE NAME</th>
                <th>COMMITTEE DESCRIPTION</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<!--<script type="text/javascript" src="../js/dataTables.bootstrap.min.js"></script>-->
<script type="text/javascript" src="../js/committeeList.js"></script>