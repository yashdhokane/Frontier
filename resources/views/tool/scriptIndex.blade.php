<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#zero_config').DataTable();

        function filterRows1() {
            var selectedTechnician = $('#technician_filter1').val(); // Technician name selected in the filter
            var selectedStatus = $('#status_filter1').val();
            var selectedStockStatus = $('#stock_filter1').val();

            table.rows().every(function() {
                var rowData = this.data();
                var showRow = true;
                var technicianNamesAttr = $(this.node()).find('td:eq(1) h6.user-name').attr('data-bs-original-title');
                var technicianNames = technicianNamesAttr ? technicianNamesAttr.split('<br>') : [];

                // Check if selected technician's name is in the list of technician names
                if (selectedTechnician && selectedTechnician !== 'All') {
                    var found = false;
                    for (var i = 0; i < technicianNames.length; i++) {
                        if (technicianNames[i].trim().toLowerCase() === selectedTechnician.toLowerCase()) {
                            found = true;
                            break;
                        }
                    }
                    showRow = found;
                }

                // Check Stock Filter
                if (selectedStockStatus) {
                    var stockStatusCell = rowData[5]; // Assuming stock status is in column 5
                    if (selectedStockStatus === 'in_stock' && !stockStatusCell.includes('ri-check-fill')) {
                        showRow = false;
                    } else if (selectedStockStatus === 'out_of_stock' && !stockStatusCell.includes('ri-close-line')) {
                        showRow = false;
                    }
                }

                // Check Product Status Filter
                if (selectedStatus) {
                    var productStatus = rowData[6]; // Assuming status is in column 6
                    showRow = showRow && productStatus === selectedStatus;
                }

                $(this.node()).toggle(showRow);
            });

            // Redraw DataTable after filtering
            table.draw();
        }

        // Event Listeners for Filters
        $('#technician_filter1, #status_filter1, #stock_filter1').on('change', function() {
            filterRows1();
        });

        // Initialize on page load
        filterRows1();
    });
</script>

<script>
    $(document).ready(function() {
        var table = $('#zero_config').DataTable();

        function filterRows() {
            var selectedTechnician = $('#technician_filter').val(); // Technician name selected in the filter
            var selectedStatus = $('#status_filter').val();
            var selectedStockStatus = $('#stock_filter').val();

            table.rows().every(function() {
                var rowData = this.data();
                var showRow = true;
                var technicianNamesAttr = $(this.node()).find('td:eq(1) h6.user-name').attr('data-bs-original-title');
                var technicianNames = technicianNamesAttr ? technicianNamesAttr.split('<br>') : [];

                // Check if selected technician's name is in the list of technician names
                if (selectedTechnician && selectedTechnician !== 'All') {
                    var found = false;
                    for (var i = 0; i < technicianNames.length; i++) {
                        if (technicianNames[i].trim().toLowerCase() === selectedTechnician.toLowerCase()) {
                            found = true;
                            break;
                        }
                    }
                    showRow = found;
                }

                // Check Stock Filter
                if (selectedStockStatus) {
                    var stockStatusCell = rowData[5]; // Assuming stock status is in column 5
                    if (selectedStockStatus === 'in_stock' && !stockStatusCell.includes('ri-check-fill')) {
                        showRow = false;
                    } else if (selectedStockStatus === 'out_of_stock' && !stockStatusCell.includes('ri-close-line')) {
                        showRow = false;
                    }
                }

                // Check Product Status Filter
                if (selectedStatus) {
                    var productStatus = rowData[6]; // Assuming status is in column 6
                    showRow = showRow && productStatus === selectedStatus;
                }

                $(this.node()).toggle(showRow);
            });

            // Redraw DataTable after filtering
            table.draw();
        }

        // Event Listeners for Filters
        $('#technician_filter, #status_filter, #stock_filter').on('change', function() {
            filterRows();
        });

        // Initialize on page load
        filterRows();
    });
</script>