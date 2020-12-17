$(function () {

    $("#volunteerList").DataTable({
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"fl>rtp<"clear">',
//        "sDom": "<'row'<'col-sm-6'f><'col-sm-6'l>>" +
//                "<'row'<'col-sm-12'tr>>" +
//                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=getVolunteer",
        "oLanguage": {
            "sZeroRecords": "No records to display",
            "sSearch": "Search Volunteers:"
        },
        "bDeferRender": true,
        "iDeferLoading": 200,
        "iDisplayLength": 25,
        "aaSorting": [[5, 'desc']],
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

        "aoColumns": [
            /* name*/ {
                "sWidth": "10%",
                "bSortable": true
            },
            /* email */ {
                "sWidth": "5%"
            },
            /* telephone */ {
                "sWidth": "5%"
            },
            /* city */ {
                "sWidth": "5%"
            },
            /* state */ {
                "sWidth": "5%"
            },
            /* status */ {
                "sWidth": "5%"
            },
            /* categories */ {
                "sWidth": "5%"
            },
            /* edit */ {
                "sWidth": "1%",
                "bSortable": false
            }
        ]
    });

});
