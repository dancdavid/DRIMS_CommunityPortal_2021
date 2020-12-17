<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_db = new db();

$category = '';
$title = '';
$description = '';
$addServiceId = '';


$toggleItemTable = (!empty($_GET['iid'])) ? "" : "style='display:none'";
$toggleItem = (!empty($_GET['sid'])) ? "" : "style='display:none'";
$toggleSubItem = (!empty($_GET['iid'])) ? "" : "style='display:none'";
$toggleSubItem2 = (!empty($_GET['siid'])) ? "" : "style='display:none'";

$sectionTitleBtn = (!empty($_GET['sid'])) ? '<button class="btn btn-danger pull-left">EDIT</button>' : '<button class="btn btn-success pull-left">SAVE</button>';
$sectionItemBtn = (!empty($_GET['iid'])) ? '<button class="btn btn-danger pull-right">EDIT</button>' : '<button class="btn btn-success pull-right">SAVE</button>';
$sectionSubItemBtn = (!empty($_GET['siid'])) ? '<button class="btn btn-danger pull-right">EDIT</button>' : '<button class="btn btn-success pull-right">SAVE</button>';

//ITEMS SECTION
$addItemId = '';
$addSubItemId = '';
$addSubItem2Id = '';
$item = '';
$subItem = '';
$catStatus = '';
$itemStatus = 'ACTIVE';
$subItemStatus = 'ACTIVE';
$type = '';


if (!empty($_GET['sid'])) {
    $_service = new Services();
    $fService = $_service->EditServices($_db->decode($_db->gpGet('sid')));

    $category = $fService['category'];
    $type = $fService['type'];
    $description = $fService['description'];
    $addServiceId = "<input type='hidden' name='sid' value='{$_db->encode($fService['id'])}'>";
    $catStatus = $fService['status'];


}

if (!empty($_GET['iid'])) {
    //ITEM
    $fItem = $_service->EditItems($_db->decode($_db->gpGet('iid')));

    $addItemId = "<input type='hidden' name='iid' value='{$_db->encode($fItem['id'])}'>";
    $item = $fItem['item'];
    $itemStatus = $fItem['status'];
}

if (!empty($_GET['siid'])) {
    //ITEM
    $fSubItem = $_service->EditSubItems($_db->decode($_db->gpGet('siid')));

    $addSubItemId = "<input type='hidden' name='siid' value='{$_db->encode($fSubItem['id'])}'>";
    $subItem = $fSubItem['sub_item'];
    $subItemStatus = $fSubItem['status'];
}
?>
<style>
    td {
        padding: 20px;
    }
</style>

<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Services / Resources / Training
                <a href="services_view2" class="pull-right"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> View Services Resources Training</a>
            </h3>
        </div>
        <div class="panel-body">

            <table width="100%" border>
                <tr>
                    <!--                    CATEGORY-->
                    <td width="50%" valign="top">
                        <form action="../_lib/servicesaction.php?action=<?= $_core->encode('AddService') ?>" id="addService" method="post" class="form-horizontal">
                            <?= $addServiceId ?>
                            <?= $addItemId ?>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="category" class="label-control">Category</label>
                                    <select name="category" id="category" class="form-control col-md-4" required>
                                        <?= $_db->buildDropMenu('cp_services', $category) ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label for="type" class="label-control">Type Name</label>
                                    <input type="text" name="type" id="type" class="form-control" value="<?= $type ?>" required/>
                                    <?php
                                    if (!empty($_GET['e']) && $_GET['e'] === 'duplicate') {
                                        echo "<div class='text-danger'>Duplicate Type found. Please use a different Type</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="description" class="label-control">Description</label>
                                    <input type="text" name="description" id="description" class="form-control" value="<?= $description ?>"/>
                                </div>
                            </div>

                            <?php

                            echo '<div class="form-group">'; // 1-1 form-group START

                            if (!empty($_GET['sid'])) {

                                echo '<div class="col-md-3">';
                                echo '<label for="Status" class="label-control">Status</label>';
                                echo '<select name="status" class="form-control" required>';
                                echo $_db->buildDropMenu('cp_user_status', $catStatus);
                                echo '</select>';
                                echo '</div>';

                            }

                            echo '<div class="col-md-1">';
                            echo '<label for="edit" class="label-control" style="color:#fff">edit</label>';
                            echo $sectionTitleBtn;
                            echo '</div>';

                            if (isset($_GET['sid']) && !empty($_GET['sid'] && empty($_GET['iid']))) {
                                echo '<div class="col-md-1 col-md-offset-3">';
                                echo '<label for="copy" class="label-control" style="color:#fff">copy</label>';
                                echo '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#copyModal">Copy Items and Sub-Items from:</button>';
                                echo '</div>';
                            }


                            echo '</div>'; // 1-1 FORM GROUP END

                            ?>

                        </form>
                    </td>


                    <!--                    ITEM-->
                    <td valign="top">
                        <div <?= $toggleItem ?>>
                            <form action="../_lib/servicesaction.php?action=<?= $_core->encode('AddItem') ?>" id="addItem" method="post" class="form-horizontal">
                                <?= $addServiceId ?>
                                <?= $addItemId ?>
                                <div class="form-group">
                                    <div class="col-md-7">
                                        <label for="item" class="label-control">Item Name</label>
                                        <input type="text" name="item" id="item" class="form-control" value="<?= $item ?>" required/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="Status" class="label-control">Status</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <?= $_db->buildDropMenu('cp_user_status', $itemStatus) ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="label-control" style="color:#fff;">edit</label>
                                        <?= $sectionItemBtn ?>
                                    </div>
                                </div>
                            </form>
                            <?php
                            if (isset($_GET['iid'])) {
                                echo '<a href="add_services2?sid=' . $_GET['sid'] . '" class="btn btn-warning">+ Add Another Item</a>';
                            }

                            //List of other Items under current ServiceId
                            if (isset($_GET['iid'])) {
                                echo $_service->BuildListOfItems($_GET['sid']);
                            }
                            ?>
                        </div>

                    </td>
                </tr>
            </table>


            <div class="col-md-12" style="padding:10px;">&nbsp;</div>

            <table width="100%" id="addSubItems" <?= $toggleItemTable ?> border>
                <tr>
                    <!--                    SUB-ITEM-->
                    <td width="50%" valign="top">

                        <div <?= $toggleSubItem ?>>
                            <form action="../_lib/servicesaction.php?action=<?= $_core->encode('AddSubItem') ?>" id="addItem" method="post" class="form-horizontal">
                                <?= $addServiceId ?>
                                <?= $addItemId ?>
                                <?= $addSubItemId ?>
                                <div class="form-group">
                                    <div class="col-md-7">
                                        <label for="sub item" class="label-control">Sub-Item Name</label>
                                        <input type="text" name="sub_item" id="sub_item" class="form-control" value="<?= $subItem ?>" required/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="Status" class="label-control">Status</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <?= $_db->buildDropMenu('cp_user_status', $subItemStatus) ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="label-control" style="color:#fff;">edit</label>
                                        <?= $sectionSubItemBtn ?>
                                    </div>
                                </div>
                            </form>
                            <?php
                            if (isset($_GET['siid'])) {
                                echo '<a href="add_services2?sid=' . $_GET['sid'] . '&iid='.$_GET['iid'].'" class="btn btn-warning">+ Add Another Sub-Item</a>';
                            }

                            //                            List of other Items under current ServiceId
                            if (isset($_GET['iid'])) {
                                echo $_service->BuildListOfSubItems($_GET['iid']);
                            }
                            ?>
                        </div>

                    </td>

                    <!--                    SUB-ITEM 2-->
                    <td valign="top">

                        <div <?= $toggleSubItem2 ?>>

                            <form action="../_lib/servicesaction.php?action=<?= $_core->encode('AddSubItem2') ?>" id="addSubItem2" method="post" class="form-horizontal">
                                <?= $addServiceId ?>
                                <?= $addItemId ?>
                                <?= $addSubItemId ?>
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <label for="subitem2">Sub-Item 2 Name</label>
                                        <input type="text" name="sub_item_2" id="sub_item_2" class="form-control" required/>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="label-control">&nbsp;</label>
                                        <button class="btn btn-success pull-right">+ ADD</button>
                                    </div>
                                </div>
                            </form>


                            <?php
                            if (!empty($_core->gpGet('siid'))) {
//                                $itemId = $_core->decode($_core->gpGet('iid'));
                                $subItemId = $_core->decode($_core->gpGet('siid'));
                                $qry = "select * from cp_services_sub_item_2 where sub_item_id = :siid";
                                $dbh = $_db->initDB();
                                $sth = $dbh->prepare($qry);
                                $sth->execute([':siid' => $subItemId]);


                                while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
                                    echo '<form action="../_lib/servicesaction.php?action=' . $_core->encode('AddSubItem2') . '" id="addSubItem" method="post" class="form-horizontal">';
                                    echo $addServiceId;
                                    echo $addItemId;
                                    echo $addSubItemId;
                                    echo '<input type="hidden" name="siid2" value="' . $_core->encode($f->id) . '">';
                                    echo '<div class="form-group">';

                                    echo '<div class="col-md-9">';
                                    echo '<label for="subitem">Sub-Item 2 Name</label>';
                                    echo '<input type="text" name="sub_item_2" id="sub_item_2" class="form-control" value="' . $f->sub_item_2 . '" required>';
                                    echo '</div>';


                                    $activeChk = ($f->status === 'ACTIVE') ? 'selected' : '';
                                    $inActiveChk = ($f->status === 'IN-ACTIVE') ? 'selected' : '';
                                    echo '<div class="col-md-2">';
                                    echo '<label for="subitem2">Status</label>';
                                    echo '<select name="status" class="form-control" required>';
                                    echo '<option value="ACTIVE" ' . $activeChk . '>ACTIVE</option>';
                                    echo '<option value="IN-ACTIVE" ' . $inActiveChk . '>IN-ACTIVE</option>';
                                    echo '</select>';
                                    echo '</div>';

                                    echo '<div class="col-md-1">';
                                    echo '<label class="label-control" style="color:#fff;">edit</label>';
                                    echo '<button class="btn btn-danger pull-right">EDIT</button>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</form>';

                                }

                            }

                            ?>
                        </div>

                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="myCopyLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Copy</h4>
            </div>
            <div class="modal-body">
                <?php
                //COPY SECTION
                if (!empty($_GET['sid'])) {
                    $serviceId = $_core->decode($_core->gpGet('sid'));
                    $itemId = $_core->decode($_core->gpGet('iid'));

                    if (!$_service->CheckIFItemsExists($serviceId) && $_service->CheckIFServicesExists()) {
                        echo $_service->BuildCopyDropDown($serviceId);
                    }
                }
                ?>
            </div>

        </div>
    </div>
</div>


<script>
    $(function () {

        $("#title").keyup(function () {
            var title = $("#type").val();
            $("#type").val(ucwords(title));
        });

        $("#item").keyup(function () {
            var item = $("#item").val();
            $("#item").val(ucwords(item));
        });

    })
</script>


