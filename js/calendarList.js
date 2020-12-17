$(function () {

    $("#calendarList").DataTable({
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"f>rtp<"clear">',
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=getCalendarList",
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
                "sWidth": "20%"
            },
            /* event date*/ {
                "sWidth": "5%"
            },
            /* level 1 */ {
                "sWidth": "10%"
            },
            /* status */ {
                "sClass": "text-center",
                "sWidth": "1%",
                "bSortable": false
            },
            /* contact */ {
                "sClass": "text-center",
                "sWidth": "5%"
            },
            /* edit  */ {
                "sClass": "text-center",
                "sWidth": "1%",
                "bSortable": false
            }
        ]
    });


});
