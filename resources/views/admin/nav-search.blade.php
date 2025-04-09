<form id="search-form" action="{{ route('global.search') }}" method="GET" onsubmit="return validateSearchInput()"
    style="display:block; margin-top:15px!important;">
	<div class="search-container">
		<input type="text" name="query" placeholder="Search & enter" required id="search-query" autocomplete="off" class="custom-search-input" /><button type="submit" style="background:none; border:none; cursor:pointer;"><i class="search-icon" data-feather="search"></i></button>
	</div>

    <ul id="suggestions-list" class="custom-suggestions">
        <!-- Suggestions will be injected here -->
    </ul>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var searchForm = document.getElementById("search-form");

        if (!searchForm) {
            console.error("Search form with ID #search-form not found!");
            return;
        }

        function validateSearchInput() {
            var searchQuery = document.getElementById("search-query").value;
            if (searchQuery.length < 3) {
                alert("Please enter at least 3 characters for the search.");
                return false;
            }
            return true;
        }

        var searchInput = document.getElementById("search-query");

        if (searchInput) {
            searchInput.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    if (validateSearchInput()) {
                        searchForm.submit();
                    }
                }
            });
        } else {
            console.error("Search input with ID #search-query not found!");
        }

        searchInput.addEventListener("input", function() {
            var query = this.value.trim();
            var suggestionsList = document.getElementById("suggestions-list");

            if (!suggestionsList) {
                console.error("Suggestions list with ID #suggestions-list not found!");
                return;
            }

            if (query.length === 0) {
                suggestionsList.style.display = 'none';
                return;
            }

 
            if (query.length >= 3) {
                fetch("{{ route('global.globalSearchautosuggest') }}?query=" + encodeURIComponent(
                        query))
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = '';

                        if (data.results.data.length > 0) {
                            data.results.data.forEach(item => {
                                let listItem = document.createElement('li');
                                listItem.style.padding = "5px";
                                listItem.style.borderBottom = "1px solid #ddd";

                                let url = '';
                                let typeLabel = item.type === 'User' ? 'Customer' : item
                                    .type; // Convert User to Customer
                                let shortDescription = item.short_description || '';

                                // Remove 'Employee ID: 0' or 'Employee ID: null'
                                shortDescription = shortDescription.replace(
                                    /Employee ID: (0|null)/, '').trim();

                                // Define URLs based on type
                                switch (item.type) {
                                    case 'Admin':
                                        url =
                                            `https://dispatchannel.com/portal/multiadmin/show/${item.id}`;
                                        break;
                                    case 'Technician':
                                        url =
                                            `https://dispatchannel.com/portal/technicians/show/${item.id}`;
                                        break;
                                    case 'User': // Customer
                                        url =
                                            `https://dispatchannel.com/portal/customers/show/${item.id}`;
                                        break;
                                    case 'Dispatcher':
                                        url =
                                            `https://dispatchannel.com/portal/dispatcher/show/${item.id}`;
                                        break;
                                    case 'Job':
                                        url =
                                            `https://dispatchannel.com/portal/tickets/${item.id}`;
                                        break;
                                    case 'Service':
                                        url =
                                            `https://dispatchannel.com/portal/book-list/services/${item.id}/edit`;
                                        break;
                                    case 'Product':
                                        url =
                                            `https://dispatchannel.com/portal/book-list/parts/${item.id}/edit`;
                                        break;
                                    case 'Payment':
                                        url =
                                            `https://dispatchannel.com/portal/invoice-detail/${item.id}`;
                                        break;
                                    case 'Manufacturer':
                                        url =
                                            `https://dispatchannel.com/portal/manufacturer-edit/${item.id}/edit`;
                                        break;
                                    case 'Estimate Template': // Adjusted for consistency
                                        url =
                                            `https://dispatchannel.com/portal/book-list/estimate/${item.id}/edit`;
                                        break;

                                    case 'Site Setting': // Adjusted for consistency
                                        url =
                                            `https://dispatchannel.com/portal/setting/buisness-profile`;
                                        break;

                                    case 'Working Hours': // Adjusted for consistency
                                        url =
                                            `https://dispatchannel.com/portal/setting/businessHours/business-hours`;
                                        break;
                                    case 'Service Area': // Adjusted for consistency
                                        url =
                                            `https://dispatchannel.com/portal/setting/service-area`;
                                        break;
                                    case 'State': // Adjusted for consistency
                                        url =
                                            `https://dispatchannel.com/portal/setting/tax`;
                                        break;
                                    default:
                                        url = '#'; // Fallback in case type is missing
                                }

                                listItem.innerHTML = `
                        <a href="${url}" style="text-decoration:none; color:#333; display:block;">
                            <strong>${item.result} (${typeLabel})</strong><br>
                            <small>${shortDescription}</small>
                        </a>
                    `;

                                suggestionsList.appendChild(listItem);
                            });

                            suggestionsList.style.display = 'block';
                        } else {
                            suggestionsList.innerHTML = `
                    <li style="text-align:center; color:#999;">No results found.</li>
                `;
                            suggestionsList.style.display = 'block';
                        }
                    })
                    .catch(error => console.error("Error fetching search suggestions:", error));
            }


        });

        document.addEventListener("click", function(event) {
            if (!event.target.closest("#search-form")) {
                document.getElementById("suggestions-list").style.display = 'none';
            }
        });
    });
</script>
