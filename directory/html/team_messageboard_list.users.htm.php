<?php
$_core = new core();
//if (!UserAccess::ManageLevel1()) {
//    $_core->redir('directory');
//}

$_team = new Teams($_core->decode($_core->gpGet('tid')));
$teamName = $_team->GetTeamName();
?>
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                Edit Team Message Board - <?= $teamName ?>
                <span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalMessageBoard">+ POST</button></span>
            </h3>
        </div>
        <div class="panel-body">
            <table id="messageBoardList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                    <th>TITLE</th>
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

<!-- Modal MESSAGE BOARD-->
<div class="modal fade" id="modalMessageBoard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Post Team Message - <?= $teamName ?></h4>
            </div>
            <form class="form-horizontal" id="postMessage" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('PostTeamMessage'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Title</label>
                            <input type="input" name="title" class="form-control" id="message_title" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Message</label>
                            <textarea name="message"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/tinymce.min.js"></script>
<script>
    $(function () {

        $("#messageBoardList").DataTable({
            "bProcessing": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"top"f>rtp<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/tmajax.php?action=GetTeamMessageBoard&tid=" + '<?= $_GET['tid'] ?>',
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": 25,
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

            "aoColumns": [
                /* title*/ {
                    "sWidth": "15%"
                },
                /* status */ {
                    "sClass": "text-center",
                    "sWidth": "1%",
                    "bSortable": false
                },
                /* contact */ {
                    "sClass": "text-center",
                    "sWidth": "1%",
                    "bSortable": false
                },
                /* edit  */ {
                    "sClass": "text-center",
                    "sWidth": "1%",
                    "bSortable": false
                }
            ]
        });

        tinymce.init({
            selector: 'textarea',
            menubar: false,
            branding: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            default_link_target: "_blank",
            target_list: false,
            link_assume_external_targets: true
        });

        $(document).on('focusin', function(e) {
            var target = $(e.target);
            if (target.closest(".mce-window").length || target.closest(".tox-dialog").length) {
                e.stopImmediatePropagation();
                target = null;
            }
        });


        $("#message_title").keyup(function () {
            var title = $("#message_title").val();
            $("#message_title").val(ucwords(title));
        });
    });
</script>
