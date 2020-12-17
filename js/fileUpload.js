$(function() {
    $('#upload_attachment, #upload_attachment_message').fileinput({
        showPreview: true,
        showUpload: false,
        validateInitialCount: true,
        maxFileCount: 3,
        allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'ppt', 'pptx']
    });
});