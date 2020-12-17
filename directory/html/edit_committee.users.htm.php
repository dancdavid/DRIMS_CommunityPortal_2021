<?php 
$_core = new core(); 
$committee_id = $_core->decode($_core->gpGet('id'));
$_com = new committee($committee_id);

$f = $_com->get_committee_data();
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT <?php echo strtoupper($f['committee_name']); ?> COMMITTEE
                <?php
                if (isset($_GET['err'])) {
                    echo "<font class='text-danger'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> {$_GET['err']}</font>";
                }
                ?>
                </h3>
        </div>

        <table class="table">
            <form class="form-horizontal" enctype="multipart/form-data" id="addCommittee" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('editCommittee'); ?>">
                       
                <input type="hidden" name="committee_id" value="<?php echo $_core->gpGet('id'); ?>">
                       <tr>
                    <td>
<div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="name">Committee Name <font class="text-danger">*</font></label>
                                <input type="input" name="committee_name" class="form-control" id="contact_name" value="<?php echo $f['committee_name']; ?>" required>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12  col-xs-12">
                                <label for="description" class="control-label">Committee Description <font class="text-danger">*</font></label>
                                <textarea rows="10" name="committee_description" class="form-control" required><?php echo $f['committee_description']; ?></textarea>
                                
                            </div>
                        </div>

                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="status">Status</label>
                            <select name="committee_status">
                                <?php echo "<option value='{$f['committee_status']}'>{$f['committee_status']}</option>"; ?>
                                <option>______________</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="DELETED">DELETE</option>
                            </select>
                        </div>
                    </div>
                    </td>
                </tr>
   
                <tr>
                    <td>

                        <div class="col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-danger pull-right">EDIT <span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                        </div>

                    </td>
                </tr>

            </form>

        </table>
    </div>
</div>
