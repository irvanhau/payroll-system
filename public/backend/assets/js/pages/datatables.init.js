$(document).ready(function () {
    $("#datatable").DataTable({
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });
    // var a = $("#datatable-buttons").DataTable({
    //     lengthChange: !1,
    //     language: {
    //         paginate: {
    //             previous: "<i class='mdi mdi-chevron-left'>",
    //             next: "<i class='mdi mdi-chevron-right'>"
    //         }
    //     },
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
    //     },
    //     buttons: ["copy", "excel", "pdf", "colvis"]
    // });
    // a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm"), $("#selection-datatable").DataTable({
    //     select: {
    //         style: "multi"
    //     },
    //     language: {
    //         paginate: {
    //             previous: "<i class='mdi mdi-chevron-left'>",
    //             next: "<i class='mdi mdi-chevron-right'>"
    //         }
    //     },
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
    //     }
    // }), $("#key-datatable").DataTable({
    //     keys: !0,
    //     language: {
    //         paginate: {
    //             previous: "<i class='mdi mdi-chevron-left'>",
    //             next: "<i class='mdi mdi-chevron-right'>"
    //         }
    //     },
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
    //     }
    // }), a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $(".dataTables_length select").addClass("form-select form-select-sm"), $("#alternative-page-datatable").DataTable({
    //     pagingType: "full_numbers",
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $(".dataTables_length select").addClass("form-select form-select-sm")
    //     }
    // }), $("#scroll-vertical-datatable").DataTable({
    //     scrollY: "350px",
    //     scrollCollapse: !0,
    //     paging: !1,
    //     language: {
    //         paginate: {
    //             previous: "<i class='mdi mdi-chevron-left'>",
    //             next: "<i class='mdi mdi-chevron-right'>"
    //         }
    //     },
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
    //     }
    // }), $("#complex-header-datatable").DataTable({
    //     language: {
    //         paginate: {
    //             previous: "<i class='mdi mdi-chevron-left'>",
    //             next: "<i class='mdi mdi-chevron-right'>"
    //         }
    //     },
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $(".dataTables_length select").addClass("form-select form-select-sm")
    //     },
    //     columnDefs: [{
    //         visible: !1,
    //         targets: -1
    //     }]
    // }), $("#state-saving-datatable").DataTable({
    //     stateSave: !0,
    //     language: {
    //         paginate: {
    //             previous: "<i class='mdi mdi-chevron-left'>",
    //             next: "<i class='mdi mdi-chevron-right'>"
    //         }
    //     },
    //     drawCallback: function () {
    //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $(".dataTables_length select").addClass("form-select form-select-sm")
    //     }
    // })
});
