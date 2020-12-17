<?php
$_core = new core();
$agency_id = $_core->decode($_core->gpGet('id'));
$agency_location_id = $_core->decode($_core->gpGet('lid'));
$_agency = new agency($agency_id);


//if ( !UserAccess::ManageLevel1() || !UserAccess::ManageMyOrg($agency_id) ) {
//    $_core->redir('directory');
//}

$_db = new db();


$agencyLocationData = $_agency->GetLocationData($agency_location_id);

$filter = (!empty($_GET['filter'])) ? filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING) : "";
?>

<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                ADD Services Resources Training for Location: <?= strtoupper($agencyLocationData['location_name']); ?>
                <span class="pull-right"><a href="agency_summary?id=<?= $_GET['id'] ?>" class="text-primary"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Org Summary</a></span>
            </h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-2">
                        <label for="filter" class="control-label">Filter</label>
                        <select id="filter_category" class="form-control">
                            <?php
                            $selService = ($filter === 'SERVICE') ? 'selected' : '';
                            $selTraining = ($filter === 'TRAINING') ? 'selected' : '';
                            $selResource = ($filter === 'RESOURCES') ? 'selected' : '';

                            if (empty($filter)) {
                                echo "<option value=''>Select Category</option>";
                            } else {
                                echo "<option value='add_org_services_locations?id={$_GET['id']}&lid={$_GET['lid']}' style='color:red'>CLEAR FILTER</option>";
                                echo "<optgroup label='____________'></optgroup>";
                            }
                            ?>
                            <option value="add_org_services_locations?id=<?= $_GET['id'] ?>&lid=<?= $_GET['lid']?>&filter=SERVICE" <?= $selService ?>>SERVICE</option>
                            <option value="add_org_services_locations?id=<?= $_GET['id'] ?>&lid=<?= $_GET['lid']?>&filter=TRAINING" <?= $selTraining ?>>TRAINING</option>
                            <option value="add_org_services_locations?id=<?= $_GET['id'] ?>&lid=<?= $_GET['lid']?>&filter=RESOURCES" <?= $selResource ?>>RESOURCES</option>
                        </select>
                    </div>
                </div>
            </form>

            <?php
            $qry = "select 
                        UPPER(LEFT(type,1)) as first_letter
                        , id
                        , type
                        , category
                        , description 
                        from cp_services 
                        where `status` = 'ACTIVE' 
                        and parent_agency_id = :pid";

            $qry .= (!empty($filter)) ? " and category = '{$filter}'" : "";
            $qry .= " order by type";

            $dbh = $_db->initDB();
            $sth = $dbh->prepare($qry);
            $sth->execute([':pid' => $_SESSION['parent_agency']]);

            $data = $sth->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);

            $_service = new Services();

            $htm = '';
            $htm .= "<table class='table'>";

            $i = 0;
            foreach ($data as $letter => $srtData) {

                if ($i == 0) {
                    $htm .= "<tr>";
                }

                $htm .= "<td style='border-top: none;'>";
                $htm .= "<h3>" . $letter . "</h3>";
                $htm .= "<ul>";

                foreach ($srtData as $row) {
                    $selectedService = ($_service->ChkIfLocationServiceSelected($agency_id, $agency_location_id, $row['id'])) ? '<span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span>' : '';

                    $htm .= '<li><a href="#" class="srt_type" data-toggle="modal" data-target="#srtModal" data-id="' . $_db->encode($row['id'])
                        . '" data-attr="' . $row['type']
                        . '" data-tip="tooltip" data-placement="top" title="' . $row['description'] . '">'
                        . $row['type'] . '</a> <small>('. $row['category'] .')</small> '.$selectedService.' </li>';
                }

                $htm .= "</ul>";
                $htm .= "</td>";

                $i++;

                if ($i == 2) {
                    $htm .= "</tr>";
                    $i = 0;
                }

            }

            $htm .= "</table>";

            echo $htm;

            ?>

        </div>
    </div>
</div>

<div class="modal fade" id="srtModal" tabindex="-1" role="dialog" aria-labelledby="srtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../_lib/agencyaction.php?action=<?= $_core->encode('AddAgencyLocationServices2') ?>" class="form-horizontal">
                <input type="hidden" name="aid" value="<?= $_GET['id'] ?>">
                <input type="hidden" name="lid" value="<?= $_GET['lid'] ?>">
                <input type="hidden" name="sid" id="sid" value="">
                <input type="hidden" name="filter" id="filter" value="<?php if (!empty($_GET['filter'])) echo $_GET['filter'] ?>">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="panel">
                        <div class="panel-body" id="srt_questionaire" style="max-height: 550px;overflow-x: scroll;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('body').tooltip({selector: '[data-tip="tooltip"]'});

        $('#filter_category').on('change', function () {
            var filterCat = $('#filter_category').val();
            window.location.href = filterCat;
        });

        $(".srt_type").on('click', function () {
            var srtTypeName = $(this).data('attr');
            var srtTypeId = $(this).data('id');

            $('#modalLabel').html(srtTypeName);
            $("#srt_questionaire").animate({ scrollTop: 0 });
            $("#sid").val(srtTypeId);

            $.ajax({
                url: "../_lib/agencyajax.php?action=GetAgencyLocationServices2&id=<?= $_GET['id'] ?>" + "&lid=<?= $_GET['lid'] ?>" + "&sid=" + srtTypeId,
                success: function (srtQuestion) {
                    $("#srt_questionaire").html(srtQuestion);
                }
            });
        });

        $("#srtModal").modal('hide.bs.modal', function () {
            $("#srt_questionaire").html('');
        });
    });
</script>
