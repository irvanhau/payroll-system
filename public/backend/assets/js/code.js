$(function () {
    $(document).on("click", "#postedInvoice", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");

        Swal.fire({
            title: "Are You Sure?",
            text: "Post This Invoice?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Post This Invoice!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire(
                    "Completed!",
                    "This Invoice Already Posted.",
                    "success"
                );
            }
        });
    });
});

$(function () {
    $(document).on("click", "#deleteItem", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are You Sure?",
            text: "Delete This Product?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delete!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire(
                    "Success!",
                    "This Product Already Deleted.",
                    "success"
                );
            }
        });
    });
});

$(function () {
    $(document).on("click", "#changeStatusNo", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are You Sure?",
            text: "Non Activate This Data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Non Activate!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire(
                    "Success!",
                    "This Data Already Non Activated.",
                    "success"
                );
            }
        });
    });
});

$(function () {
    $(document).on("click", "#changeStatusAc", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are You Sure?",
            text: "Activate This Data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Activate!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire(
                    "Success!",
                    "This Data Already Activated.",
                    "success"
                );
            }
        });
    });
});

function showJurnal(record_id, modul) {
    let rupiah = new Intl.NumberFormat("en-ID", {
        style: "currency",
        currency: "IDR",
    });

    $.get(`/jurnal/${record_id}/${modul}`, function (res) {
        buatTabel = "";
        buatTabel += "<thead class='font-weight-bold'>";
        buatTabel +=
            "<tr>" +
            "<td>COA Number</td>" +
            "<td>COA Name</td>" +
            "<td>Debit</td>" +
            "<td>Credit</td>" +
            "</tr>" +
            "</thead>";

        acct_fact_debit = res.acct_fact_debit;
        acct_fact_credit = res.acct_fact_credit;

        for (a = 0; a < acct_fact_debit.length; a++) {
            buatTabel += "<tbody>";
            buatTabel +=
                "<tr>" +
                "<td>" +
                res.acct_fact_debit[a].account_no +
                "</td>" +
                "<td>" +
                res.acct_fact_debit[a].account_name +
                "</td>" +
                "<td>" +
                rupiah.format(res.acct_fact_debit[a].debit) +
                "</td>" +
                "<td>" +
                rupiah.format(res.acct_fact_debit[a].credit) +
                "</td>" +
                "</tr>";
        }

        for (b = 0; b < acct_fact_credit.length; b++) {
            buatTabel +=
                "<tr>" +
                "<td>" +
                res.acct_fact_credit[b].account_no +
                "</td>" +
                "<td>" +
                res.acct_fact_credit[b].account_name +
                "</td>" +
                "<td>" +
                rupiah.format(res.acct_fact_credit[b].debit) +
                "</td>" +
                "<td>" +
                rupiah.format(res.acct_fact_credit[b].credit) +
                "</td>" +
                "</tr>";
        }

        buatTabel +=
            "<tr>" +
            "<td class='text-center' colspan='2'>Total</td>" +
            "<td>" +
            rupiah.format(res.total_debit) +
            "</td>" +
            "<td>" +
            rupiah.format(res.total_credit) +
            "</td>" +
            "</tr>" +
            "</tbody>";

        document.getElementById("tabelJurnal").innerHTML = buatTabel;
        $("#modal-jurnal").modal("toggle");
    });
}

function hanyaAngka(event) {
    var angka = event.which ? event.which : event.keyCode;
    if (angka != 46 && angka > 31 && (angka < 48 || angka > 57)) return false;
    return true;
}
