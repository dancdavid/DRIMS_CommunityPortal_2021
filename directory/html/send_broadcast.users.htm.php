<?php
$_core = new core();
$_level = new Level1();
$_contactType = new ContactType();
$_contactLicenseType = new ContactLicenseType();
$_partner = new PartnerType();
?>
<div class="col-xs-12 col-sm-12">
    <div class="col-xs-1 col-sm-1"></div>

    <div class="col-xs-10 col-sm-10">
        <div class="jumbotron">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">Send Broadcast to ALL Organizations</h3>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" action="../_lib/action.php?action=<?php echo $_core->encode('sendBroadcast'); ?>" enctype="multipart/form-data" method="post">
                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label for="title" class="control-label">Title <font class="text-danger">*</font></label>
                                <input type="input" name="title" class="form-control" id="title" placeholder="Message Title" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label for="message" class="control-label">Message <font class="text-danger">*</font></label>
                                <textarea class="form-control" name="message" id="message" rows="10" placeholder="Your Message" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6 col xs-6">
                                <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <font class="text-danger">*</font></label>
                                <select id="level1" name="level_1[]" class="form-control" multiple required>
                                    <?= $_level->BuildLevelDropDown() ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col xs-6">
                                <label for="title" class="control-label">Partner Type</label>
                                <select id="partner_type" name="partner_type[]" class="form-control" multiple>
                                    <?= $_partner->BuildPartnerDropDown() ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6 col xs-6">
                                <label class="control-label" for="contact type">Contact Type</label>
                                <select name="contact_type[]" id="contact_type" class="form-control" multiple>
                                    <?= $_contactType->BuildContactDropDown() ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col xs-6">
                                <label class="control-label" for="contact license type">Contact License Type</label>
                                <select name="contact_license_type[]" id="contact_license_type" class="form-control" multiple>
                                    <?= $_contactLicenseType->BuildContactLicenseDropDown() ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12 attachment_wrapper">
                                <label class="control-label" for="add_logo">Add Attachment(s)</label>
                                <input id="upload_attachment" name="upload_attachment[]" type="file" multiple>
<!--                                <a href="javascript:void(0);" class="add_button" title="Add More Attachments">+ Add more attachments</a>-->
                            </div>

                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-danger pull-right" aria-label="Left Align">Send Broadcast 
                                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>

                </div><!--end body-->
            </div> <!--end panel-->
        </div>
    </div>

    <div class="col-xs-1 col-sm-1"></div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">SUCCESS!</h4>
            </div>
            <div class="modal-body">
                Your message has been sent to all selected agency contacts.
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">
                    Warning: Error Found
                </h4>
            </div>
            <div class="modal-body">
                No users could be found with your <?= $_SESSION['Level1_Label'] ?> selection.<br><br>
            Users may have notifications turned off -OR- No active contacts have been associated with the selection.
            </div>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="../js/tinymce.min.js"></script>
<script type="text/javascript" src="../js/fileinput.min.js"></script>
<script>

    var $_GET = '<?php echo $_core->gpGet('e'); ?>';

    $(function () {

        $('select').each(function() {
            $(this).select2({
                theme: "bootstrap"
            });
        });

        $('#successModal').modal({'show': false});
        if ($_GET === 'success') {
            $('#successModal').modal('show');
        }

        $('#errorModal').modal({'show': false});
        if ($_GET === 'nocontacts') {
            $('#errorModal').modal('show');
        }

        tinyMCE.triggerSave()

        tinymce.init({
            selector: 'textarea',
            menubar: false,
            plugins: [
                'advlist autolink lists print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            default_link_target: "_blank",
            target_list: false,
            link_assume_external_targets: true,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });

        $('#upload_attachment').fileinput({
            showPreview: true,
            showUpload: false,
            validateInitialCount: true,
            maxFileCount: 3,
            allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'ppt', 'pptx']
        });
    });
</script>