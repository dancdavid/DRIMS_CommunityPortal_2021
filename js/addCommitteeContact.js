$(function () {

    $("#addCommitteeContact").DataTable({
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"f>rt<"clear">', 
//        "sDom": "<'row'<'col-sm-6'f><'col-sm-6'l>>" +
//                "<'row'<'col-sm-12'tr>>" +
//                "<'row'<'col-sm-4'><'col-sm-8'p>>",
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=getAllContacts",
        "oLanguage": {
            "sZeroRecords": "No records to display",
            "sSearch": "Search Contacts:"
        },
        "bDeferRender": true,
        "iDeferLoading": 200,
        "iDisplayLength": -1,
        "aaSorting": [[0, 'asc']],
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

        "aoColumns": [
            /* name*/ {
                "sWidth": "5%"
            },
            /* agency */ {
                "sWidth": "20%"
            },
            /*  add button */ {
                "sWidth": "1%",
                "bSortable": false,

            }
        ]
    });

});
