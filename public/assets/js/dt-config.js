<script>
    $(document).ready(function () {
        var table = $('#pc-dt-simple').DataTable({
            lengthChange: false,
            // buttons: {
            //     dom: {
            //         button: {
            //             className: "btn btn-outline-primary"
            //         }
            //     },
            //     // buttons: [ 'copy', 'excel', 'pdf', 'print']
            // },

        });

        table.buttons().container()
            .appendTo('#pc-dt-simple_wrapper .col-md-6:eq(0)');
    });

</script>
