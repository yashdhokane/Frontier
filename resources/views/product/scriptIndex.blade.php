<!-- Include jQuery and DataTables library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
{{--
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> --}}

<script>
    $(document).ready(function() {
        // Initialize the DataTable
        var table = $('#zero_config').DataTable();

        // Category filter
        $('#category_name').on('change', function() {
            var selectedCategory = $(this).val();
            if (selectedCategory) {
                table.column(2).search('^' + selectedCategory + '$', true, false).draw();
            } else {
                table.column(2).search('').draw();
            }
        });

        // Manufacturer filter
        $('#manufacturer_filter').on('change', function() {
            var selectedManufacturer = $(this).val();
            if (selectedManufacturer) {
                table.column(3).search('^' + selectedManufacturer + '$', true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Status filter
        $('#status_filter').on('change', function() {
            var selectedStatus = $(this).val();
            if (selectedStatus) {
                table.column(5).search('^' + selectedStatus + '$', true, false).draw();
            } else {
                table.column(5).search('').draw();
            }
        });



        $('#category_name1').on('change', function() {
            var selectedCategory = $(this).val();
            if (selectedCategory) {
                table.column(2).search('^' + selectedCategory + '$', true, false).draw();
            } else {
                table.column(2).search('').draw();
            }
        });

        // Manufacturer filter
        $('#manufacturer_filter1').on('change', function() {
            var selectedManufacturer = $(this).val();
            if (selectedManufacturer) {
                table.column(3).search('^' + selectedManufacturer + '$', true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Status filter
        $('#status_filter1').on('change', function() {
            var selectedStatus = $(this).val();
            if (selectedStatus) {
                table.column(5).search('^' + selectedStatus + '$', true, false).draw();
            } else {
                table.column(5).search('').draw();
            }
        });


        

        // Stock filter (if needed)
        $('#stock_filter').on('change', function() {
            var selectedStock = $(this).val();
            table.rows().every(function() {
                var rowData = this.data();
                var cellContent = rowData[7]; // Assuming stock status is in column 7
                var isVisible = false;
                if (selectedStock === 'in_stock' && cellContent.includes('ri-check-fill')) {
                    isVisible = true;
                } else if (selectedStock === 'out_of_stock' && cellContent.includes('ri-close-line')) {
                    isVisible = true;
                }
                if (isVisible) {
                    this.nodes().to$().show();
                } else {
                    this.nodes().to$().hide();
                }
            });
        });

        // Dropdown change event for category and manufacturer
        $('#service, #manufacturer').change(function() {
            var category_id = $('#service').val();
            var manufacturer_id = $('#manufacturer').val();

            // Send AJAX request
            $.ajax({
                url: '{{ route('productsaxaclist') }}', // Replace with your route
                method: 'GET',
                data: {
                    category_id: category_id,
                    manufacturer_id: manufacturer_id
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
