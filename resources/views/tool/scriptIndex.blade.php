<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#category_name').on('change', function() {
            var selectedStatus = $(this).val();
            if (selectedStatus) {
                $('#zero_config').DataTable().column(2).search('^' + selectedStatus + '$', true, false)
                    .draw();
            } else {
                // If no status is selected, clear the filter
                $('#zero_config').DataTable().column(2).search('').draw();
            }
        });

        $('#manufacturer_filter').on('change', function() {
            var selectedStatus = $(this).val();
            if (selectedStatus) {
                $('#zero_config').DataTable().column(3).search('^' + selectedStatus + '$', true, false)
                    .draw();
            } else {
                // If no status is selected, clear the filter
                $('#zero_config').DataTable().column(3).search('').draw();
            }
        });

        $('#stock_filter').on('change', function() {
            var selectedStatus = $(this).val();
            console.log(selectedStatus);
            if (selectedStatus) {
                var table = $('#zero_config').DataTable();
                table.rows().every(function() {
                    var rowData = this.data();
                    var cellContent = rowData[7]; // Assuming stock status is in column 7
                    var isVisible = false;
                    if (selectedStatus === 'in_stock' && cellContent.includes(
                        'ri-check-fill')) {
                        isVisible = true;
                    } else if (selectedStatus === 'out_of_stock' && cellContent.includes(
                            'ri-close-line')) {
                        isVisible = true;
                    }
                    if (isVisible) {
                        this.nodes().to$().show();
                    } else {
                        this.nodes().to$().hide();
                    }
                });
            } else {
                // If no status is selected, show all rows
                $('#zero_config').DataTable().rows().nodes().to$().show();
            }
        });






        $('#status_filter').on('change', function() {
            var selectedStatus = $(this).val();
            if (selectedStatus) {
                $('#zero_config').DataTable().column(8).search('^' + selectedStatus + '$', true, false)
                    .draw();
            } else {
                // If no status is selected, clear the filter
                $('#zero_config').DataTable().column(8).search('').draw();
            }
        });


        // Function to handle dropdown change event
        $('#service, #manufacturer').change(function() {
            var category_id = $('#service').val();
            var manufacturer_id = $('#manufacturer').val();

            // Send AJAX request
            $.ajax({
                url: '{{ route('productsaxaclist') }}', // Replace with your route
                method: 'GET',
                data: {
                    category_id: product_category_id,
                    manufacturer_id: product_manu_id
                },
                success: function(response) {
                    // Update the table with the response data
                    $('.product-table').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });


    });
</script>
