$(function() {
 
    $("#editAgencyList").DataTable( {
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"fl>rtp<"clear">',
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=editAgencyList",
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
        /* agency name*/ {
            "sWidth":"15%"
        },
        /* edit info */ {
            "sClass":"text-center",
            "sWidth":"1%",
            "bSortable":false
        },
        /* edit contact */ {
            "sClass":"text-center",
            "sWidth":"1%",
            "bSortable":false
        },
        /* edit service */ {
            "sClass":"text-center",
            "sWidth":"1%",
            "bSortable":false
        }
        ]
    });
    
});
