<?php
$_core = new core();
$_com = new committee($_core->decode($_core->gpGet('id')));
$f = $_com->get_committee_data();
?>

<form class="form-horizontal" id="addCommitteeContactForm" method="post" role="form" action="../_lib/action.php?action=<?php echo $_core->encode('addCommitteeContact'); ?>">
    
    <div class="col-sm-12 col-xs-12" style="padding-bottom: 30px;">
        
        <h4 class="text-danger" style="padding-bottom: 10px;">ADD <?php echo $f['committee_name']; ?> COMMITTEE CONTACT<span class="pull-right"><button type="submit" class="btn btn-xs btn-primary">Add Contact/s</button></span></h4>
        
        <input type="hidden" name="committee_id" value="<?php echo $_core->gpGet('id'); ?>">
        
        <table id="addCommitteeContact" class="table table-bordered table-striped" style="font-size:12px;">
            <thead>
                <tr>
                    <th>CONTACT NAME</th>
                    <th>AGENCY NAME</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        
    </div>
    
    <div class="col-sm-12 col-xs-12" style="padding-bottom: 30px;">
        <span class="pull-right"><button type="submit" class="btn btn-xs btn-primary">Add Contact/s</button></span>
    </div>
    
</form>

<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/addCommitteeContact.js"></script>