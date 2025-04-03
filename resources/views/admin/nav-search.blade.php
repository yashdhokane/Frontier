<style>
    /* Styling for search bar and suggestions dropdown */
    .app-search {
        /* position: relative;
        width: 250px; */
    }

    #suggestions-list {
        background: #fff;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 250px;
        overflow-y: auto;
        display: none;
        position: absolute;
        width: 100%;
        z-index: 1000;
    }

    .suggestion-item {
        padding: 10px;
        cursor: pointer;
        color: #343a40;
        /* Text color */
    }

    .suggestion-item:hover {
        background: #f8f9fa;
    }

    .suggestion-item a {
        text-decoration: none;
        color: blue;
        /* Link color */
        display: block;
    }

    .srh-btn {
        background: none;
        border: none;
        cursor: pointer;
        /* display: none; */
    }

    .srh-btn i {
        color: #343a40;
    }

    .search-box .app-search {
        /* z-index: 110 !important;
    width: 150px !important;
    top: 10px !important;
    box-shadow: 1px 0 10px rgba(0, 0, 0, 0.1) !important;
    display: none !important;
    left: 150px !important;
    padding: 5px !important;
    font-size: 12px !important; */
    }

    .custom-search-input {
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-left:20px!important;
       // width: 350px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease-in-out;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .custom-search-input:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.5);
    }

    .custom-suggestions {
        display: none;
        position: absolute;
        width: 420px !important;
        background: white;
        border: 1px solid #ccc;
        list-style: none;
        padding: 0;
        margin: 5px 0;
        border-radius: 5px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }
</style>
<style>
.search-box .feather {
    width: 39px !important;
    }
    .search-container {
        position: relative;
        display: inline-block;
    }

    .custom-search-input {
        padding: 6px 35px 6px 12px; /* Adjusted padding for icon */
        border: 1px solid #ccc;
        border-radius: 5px;
        /* width: 350px; */
         margin-left:20px!important;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease-in-out;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .custom-search-input:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.5);
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #343a40;
        cursor: pointer;
        font-size: 16px;
    }
</style>
{{--
<a class="nav-link waves-effect waves-dark" href="javascript:void(0)">
    <i data-feather="search" class="feather-sm"></i>
</a>

<form class="app-search position-absolute" style="display:block!important;" action="{{ route('global.search') }}" method="GET" onsubmit="return validateSearchInput()">
    <input type="text" name="query" class="form-control" placeholder="Search &amp; enter" required id="search-query" autocomplete="off" />
    <button type="submit" class="srh-btn">
        <i data-feather="x" class="feather-sm"></i>
    </button>
    <!-- Suggestions Container -->
    <ul id="suggestions-list" class="list-group position-absolute w-100 mt-2" style="display:none;">
        <!-- Suggestions will be injected here      -->
    </ul>
</form>  --}}



<form id="search-form" action="{{ route('global.search') }}" method="GET" onsubmit="return validateSearchInput()"
    style="display:block; margin-top:15px!important;">
    <!-- <input type="text" name="query" placeholder="Search & enter" required id="search-query" autocomplete="off" style="padding:5px; border:1px solid #ccc; width:200px;" /> -->
   <!-- <input type="text" name="query" placeholder="Search & enter" required id="search-query" autocomplete="off"
        class="custom-search-input" /> -->


    <!--<button type="submit" style="background:none; border:none; cursor:pointer;">
        <i data-feather="search"></i>
    </button> -->
    <div class="search-container">
    <input type="text" name="query" placeholder="Search & enter" required id="search-query" autocomplete="off"
        class="custom-search-input" /><button type="submit" style="background:none; border:none; cursor:pointer;">
    <i class="search-icon" data-feather="search"></i></button>
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



            // if (query.length >= 3) {
            //     fetch("{{ route('global.globalSearchautosuggest') }}?query=" + query)
            //         .then(response => response.json())
            //         .then(data => {
            //             suggestionsList.innerHTML = '';

            //             if (data.results.data.length > 0) {
            //                 data.results.data.forEach(item => {
            //                     var listItem = document.createElement('li');
            //                     listItem.style.padding = "5px";
            //                     listItem.style.borderBottom = "1px solid #ddd";

            //                     let url = '';
            //                     let typeLabel = item.type === 'User' ? 'Customer' : item
            //                         .type; // Convert User to Customer
            //                     let shortDescription = item.short_description;

            //                     // Remove Employee ID if it is 0 or null
            //                     if (shortDescription.includes('Employee ID: 0') ||
            //                         shortDescription.includes('Employee ID: null')) {
            //                         shortDescription = shortDescription.replace(
            //                             /Employee ID: (0|null)/, '').trim();
            //                     }

            //                     // Define URLs based on type
            //                     switch (item.type) {
            //                         case 'Admin':
            //                             url =
            //                                 `https://dispatchannel.com/portal/multiadmin/show/${item.id}`;
            //                             break;
            //                         case 'Technician':
            //                             url =
            //                                 `https://dispatchannel.com/portal/technicians/show/${item.id}`;
            //                             break;
            //                         case 'User': // Customer
            //                             url =
            //                                 `https://dispatchannel.com/portal/customers/show/${item.id}`;
            //                             break;
            //                         case 'Dispatcher':
            //                             url =
            //                                 `https://dispatchannel.com/portal/dispatcher/show/${item.id}`;
            //                             break;
            //                         case 'Job':
            //                             url =
            //                                 `https://dispatchannel.com/portal/tickets/${item.id}`;
            //                             break;
            //                         case 'Service':
            //                             url =
            //                                 `https://dispatchannel.com/portal/book-list/services/${item.id}/edit`;
            //                             break;
            //                         case 'Product':
            //                             url =
            //                                 `https://dispatchannel.com/portal/book-list/parts/${item.id}/edit`;
            //                             break;
            //                         case 'Payment':
            //                             url =
            //                                 `https://dispatchannel.com/portal/invoice-detail/${item.id}`;
            //                             break;
            //                         case 'Manufacturer':
            //                             url =
            //                                 `https://dispatchannel.com/portal/manufacturer-edit/${item.id}/edit`;
            //                             break;

            //                         case 'EstimateTemplate':
            //                             url =
            //                                 `https://dispatchannel.com/portal/book-list/estimate/${item.id}/edit`;
            //                             break;
            //                     }

            //                     listItem.innerHTML = `<a href="${url}" style="text-decoration:none; color:#333; display:block;">
            //             <strong>${item.result} (${typeLabel})</strong>
            //             <small>${shortDescription}</small>
            //         </a>`;

            //                     suggestionsList.appendChild(listItem);
            //                 });

            //                 suggestionsList.style.display = 'block';
            //             } else {
            //                 suggestionsList.innerHTML =
            //                     '<li style="text-align:center; color:#999;">No results found.</li>';
            //                 suggestionsList.style.display = 'block';
            //             }
            //         })
            //         .catch(error => console.error("Error fetching search suggestions:", error));
            // }
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
