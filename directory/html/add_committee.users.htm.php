<?php 
$_core = new core(); 
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">ADD COMMITTEE
                <?php
                if (isset($_GET['err'])) {
                    echo "<font class='text-danger'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> {$_GET['err']}</font>";
                }
                ?>
                </h3>
        </div>

        <table class="table">
            <form class="form-horizontal" enctype="multipart/form-data" id="addCommittee" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('addCommittee'); ?>">
                       
                       <tr>
                    <td>
<div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="name">Committee Name <font class="text-danger">*</font></label>
                                <input type="input" name="committee_name" class="form-control" id="contact_name" required>
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12  col-xs-12">
                                <label for="description" class="control-label">Committee Description <font class="text-danger">*</font></label>
                                <textarea rows="10" name="committee_description" class="form-control" required></textarea>
                                
                            </div>
                        </div>

                    </td>
                </tr>
   
                <tr>
                    <td>

                        <div class="col-sm-2 col-xs-2  col-sm-offset-8 col-xs-offset-8">
                        <button type="submit" name="add_committee_contacts" value="yes" class="btn btn-warning pull-right">ADD CONTACTS <span class="glyphicon glyphicon-user" aria-hidden="true"></span></button>
                        </div>
                        <div class="col-sm-2 col-xs-2">
                        <button type="submit" class="btn btn-danger pull-right">COMPLETE <span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                        </div>

                    </td>
                </tr>

            </form>

        </table>
    </div>
</div>
