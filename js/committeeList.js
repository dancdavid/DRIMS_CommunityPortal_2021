$(function() {
 
    $("#committeeList").DataTable( {
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"f>rtp<"clear">',
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=getCommittee",
        "oLanguage": {
            "sZeroRecords": "No records to display",
            "sSearch": "Search:"
        },
        "bDeferRender": true,
        "iDeferLoading": 200,
        "iDisplayLength": 25,
        "aaSorting":[[0,'asc']],
        "aLengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
 
        "aoColumns" : [
        /* name*/ {
            "sWidth":"5%"
        },
        /* desc */ {
            "sWidth":"15%"
        }
        ]
    });
    
});