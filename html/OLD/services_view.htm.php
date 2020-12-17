<?php
require_once ('../config/config.php');
require_once (ROOT . '/_lib/classes/class.core.php');
require_once (ROOT . '/_lib/classes/class.db.php');
require_once (ROOT . '/_lib/classes/class.agency.php');
$_core = new core();
$agency_id = $_core->decode($_core->gpGet('id'));
$_agency = new agency($agency_id);
?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><a href="agency_summary?id=<?php echo $_core->gpGet('id'); ?>"><?php echo $_agency->get_agency_name($agency_id); ?></a></h4>
    </div>
    <div class="modal-body">
        <?php echo $_agency->build_services_view(); ?>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="popover"]').popover();
    })
</script>