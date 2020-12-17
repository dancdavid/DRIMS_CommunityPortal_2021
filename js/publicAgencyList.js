$(function () {

    $("#agencyList").DataTable({
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"fl>rtp<"clear">',
//        "sDom": "<'row'<'col-sm-6'f><'col-sm-6'l>>" +
//                "<'row'<'col-sm-12'tr>>" +
//                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "bStateSave": false,
        "sAjaxSource": "_lib/ajax.php?action=getPublicAgency",
        "oLanguage": {
            "sZeroRecords": "No records to display",
            "sSearch": "Search Agency:"
        },
        "bDeferRender": true,
        "iDeferLoading": 200,
        "iDisplayLength": 25,
        "aaSorting": [[0, 'asc']],
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

        "aoColumns": [
            /* agency name*/ {
                "sWidth": "15%"
            },
            /* address */ {
                "sWidth": "5%"
            },
            /* city */ {
                "sWidth": "5%"
            },
            /* phone */ {
                "sWidth": "5%"
            },
            /* service button*/ {
                "sWidth": "1%",
                "bSortable": false
            },
            /* service values */ {
                "sWidth": "1%",
                "visible": false
            }
        ]
    });

});
