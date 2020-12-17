$(function () {

    $('body').tooltip({selector: '[data-toggle="tooltip"]'});

    var table = $("#orgList").DataTable({
        "bProcessing": false,
        "sPaginationType": "full_numbers",
        "sDom": '<"top"Bf>rt<"clear">',
        "bStateSave": false,
        "sAjaxSource": "../_lib/ajax.php?action=ListOrgs",
        "oLanguage": {
            "sZeroRecords": "No records to display",
            "sSearch": "Search:"
        },
        "bDeferRender": true,
        "iDeferLoading": 20,
        "paging": false,
        // "iDisplayLength": 25,
        "aaSorting": [[0, 'asc']],
        // "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 10 ] },
            { "bSearchable": false, "aTargets": [ 10 ] },
            { "sClass": "srt-buttons", "aTargets": [ 10 ] },
            { "bVisible": false, "aTargets": [ 5, 11, 12, 13, 14 ] }
        ],
        "buttons": [
            {
                'extend': 'print',
                'text': 'Print Table View',
                exportOptions: {
                    stripHtml: false,
                    columns: [ 0, 1 ,2, 3, 4, 5, 6, 7, 8, 9 ]
                }
            }
        ]
    });

    // $('#orgList').on('search.dt', function() {
    //     // var searchValue = $('.dataTables_filter input').val().replace(/ /g,"+");
    //     // if (searchValue != '')
    //     // {
    //     //     $('#print_friendly').attr('href', 'print_agency?search=' + searchValue);
    //     // }
    //     // $("#orgList").find('.search').each(function () {
    //     //     var val = $(this).attr('data-id');
    //     //     console.log(val);
    //     // });
    // });

    $('#print_friendly').on('click', function() {
        var searchValue = $('.dataTables_filter input').val();
        if (searchValue != '')
        {
            var val = '';
            $("#orgList").find('.search').each(function () {
                val += $(this).attr('data-id') + ';';
            });
            // console.log(val.replace(/;+$/,''));
            $('#print_friendly').attr('href', 'print_agency_search?print=' + val.replace(/;+$/,''));
        }
    });

    // $('.buttons-print').addClass('btn btn-xs btn-danger');

    $('#orgList').on('click', '.locServices', function () {

        var $lid = $(this).data('id');

        $("#listAvailableLocationServices").DataTable().destroy();

        var locServices = $("#listAvailableLocationServices").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "cache": false,
            "sAjaxSource": "../_lib/agencyajax.php?action=SearchSRTModal&lid=" + $lid,
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 20,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']]
        });

        locServices.clear().draw();

        // locServices.ajax.reload();
        $('#locationServicesModal').modal('show');

    });

});
