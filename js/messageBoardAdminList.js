$(function () {

    $("#messageBoardList").DataTable({
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"f>rtp<"clear">',
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=getMessageBoardList",
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
            /* level */ {
                "sWidth": "1%",
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

});
