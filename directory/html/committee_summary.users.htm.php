<?php
$_core = new core();

$committee_id = $_core->decode($_core->gpGet('id'));
$_committee = new committee($committee_id);
//$_agency = new agency();

$fCommittee = $_committee->get_committee_data();
$fComContacts = $_committee->get_committee_contacts();

$mailGroup = "";

if (count($fComContacts) > 1) {
    $contacts = array();

    foreach ($fComContacts as $val) {
        $contacts[] = $val['contact_email'];
    }

    $mailto = implode(",", $contacts);

    $mailGroup = '<a href="mailto:' . $mailto . '" class="btn btn-xs btn-default pull-right" style="position:relative;top:-20px;"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>';
}
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                COMMITTEE SUMMARY
                <span class="pull-right"><button onclick="myPrint()" class="btn btn-xs btn-danger">Print <span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-sm-8 col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Committee Info</h3>
                    </div>
                    <!--<div class="panel-body">-->
                    <table class="table">
                        <tr>
                            <td>
                                <b>Name</b><br>
                                <?php echo $fCommittee['committee_name']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Description</b><br>
                                <?php echo $fCommittee['committee_description']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="edit_committee?id=<?php echo $_core->gpGet('id'); ?>" class="btn btn-xs btn-danger pull-right">EDIT</a>
                            </td>
                        </tr>
                    </table>

                    <!--</div>-->
                </div>
            </div>
            <!--            <div class="col-sm-4 col-xs-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Description</h3>
                                </div>
                                <div class="panel-body">
                                    
                                </div>
                            </div>
                        </div>-->
            <div class="col-sm-4 col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Contacts</h3>
                        <?php echo $mailGroup; ?>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach ($fComContacts as $col) {
                            $_agency = new agency($col['agency_id']);
                            echo "<b>" . $_agency->get_agency_name($col['agency_id']) . "</b><br>";
                            echo $col['contact_name'] . "<br>";
                            echo "Phone: " . $col['contact_telephone'] . "<br>";
                            echo "Cell: " . $col['contact_cellphone'] . "<br>";
                            echo "Fax: " . $col['contact_fax'] . "<br>";
                            echo "Email: <a href='mailto:{$col['contact_email']}'>" . $col['contact_email'] . "</a><br>";
                            
                            if ($_SESSION['user_type'] === 'ADMIN') {
                                echo "<form method='post' action='../_lib/action.php?action={$_core->encode('delCommitteeContact')}'>";
                                echo "<input type='hidden' name='committee_id' value='{$_core->gpGet('id')}'>";
                                echo "<button type='submit' name='committee_contact_id' value='{$col['committee_contact_id']}' class='btn btn-xs btn-danger'>DELETE <span class='glyphicon glyphicon-trash' aria-hidden='true'></span></button>";
                                echo "</form>";
                            }
                            
                            echo "<hr></hr>";
                        }

//                        if ($_SESSION['user_type'] === 'ADMIN') {
//                            echo '<button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#modalAddCommitteeContact">
//                            ADD CONTACTS <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
//                            </button>';
//                        }

                        if ($_SESSION['user_type'] === 'ADMIN') {
                            echo '<a class="btn btn-xs btn-primary pull-right" href="add_committeecontact?id=' . $_core->gpGet('id') . '">
                            ADD CONTACTS <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            </a>';
                        }
                        ?>

                        <div id="test"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<div class="modal fade" id="modalAddCommitteeContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Add Committee Contact</h4>
            </div>

            <div class="modal-body">

                <form class="form-horizontal" id="addCommitteeContactForm" role="form">
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
                </form>

            </div>

            </form>
        </div>
    </div>
</div>-->


<script>
    var committee_id = "<?php echo $_core->gpGet('id'); ?>";

    $(document).ready(function () {
        $('#addCommitteeContactForm').submit(function () {
            event.preventDefault();
            $.ajax({
                type: "post",
                url: "../_lib/action.php?action=<?php echo $_core->encode('addCommitteeContact'); ?>",
                data: $("#addCommitteeContactForm").serialize(),
                success: function (response) {
//                $(".result").html(response);
//                $(".result").append(response)
                    $("#test").html(response);

                }
            });

        });
    });

    function myPrint() {
        window.print();
    }
</script>

<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/addCommitteeContact.js"></script>
