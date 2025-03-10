<style>
    /* Styling for search bar and suggestions dropdown */
    .app-search {
        position: relative;
        width: 250px;
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
        color: #343a40; /* Text color */
    }

    .suggestion-item:hover {
        background: #f8f9fa;
    }

    .suggestion-item a {
        text-decoration: none;
        color: blue; /* Link color */
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
</style>

<a class="nav-link waves-effect waves-dark" href="javascript:void(0)">
    <i data-feather="search" class="feather-sm"></i>
</a>

<form class="app-search position-absolute" action="{{ route('global.search') }}" method="GET" onsubmit="return validateSearchInput()">
    <input type="text" name="query" class="form-control" placeholder="Search &amp; enter" required id="search-query" autocomplete="off" />
    <button type="submit" class="srh-btn">
        <i data-feather="x" class="feather-sm"></i>
    </button>
    <!-- Suggestions Container -->
    <ul id="suggestions-list" class="list-group position-absolute w-100 mt-2" style="display:none;">
        <!-- Suggestions will be injected here -->
    </ul>
</form>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    var searchForm = document.querySelector(".app-search"); // Ensure form is selected

    if (!searchForm) {
        console.error("Search form with class .app-search not found!");
        return;
    }

    // Validate search input before submitting the form
    function validateSearchInput() {
        var searchQuery = document.getElementById("search-query").value;

        if (searchQuery.length < 3) {
            alert("Please enter at least 3 characters for the search.");
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }

    // Handle Enter key inside the search input
    var searchInput = document.getElementById("search-query");

    if (searchInput) {
        searchInput.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Prevent default enter behavior

                if (validateSearchInput()) {
                    searchForm.submit(); // Submit the form
                }
            }
        });
    } else {
        console.error("Search input with ID #search-query not found!");
    }

    // Live search function with auto-suggest
    searchInput.addEventListener("input", function () {
        var query = this.value.trim();
        var suggestionsList = document.getElementById("suggestions-list");

        if (!suggestionsList) {
            console.error("Suggestions list not found!");
            return;
        }

        if (query.length === 0) {
            suggestionsList.style.display = 'none';
            return;
        }

        if (query.length >= 3) {
            fetch("{{ route('global.globalSearchautosuggest') }}?query=" + query)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';

                    if (data.results.data.length > 0) {
                        data.results.data.forEach(item => {
                            var listItem = document.createElement('li');
                            listItem.classList.add('list-group-item', 'suggestion-item');

                            let url = '';
                            switch (item.type) {
                                case 'Job':
                                    url = `https://dispatchannel.com/portal/tickets/${item.id}`;
                                    break;
                                case 'User':
                                    url = `https://dispatchannel.com/portal/dispatcher/show/${item.id}`;
                                    break;
                                case 'Customer':
                                    url = `https://dispatchannel.com/portal/customers/show/${item.id}`;
                                    break;
                                case 'Service':
                                    url = `https://dispatchannel.com/portal/book-list/services-list/${item.id}`;
                                    break;
                                case 'Product':
                                    url = `https://dispatchannel.com/portal/book-list/parts/${item.id}/edit`;
                                    break;
                            }

                            listItem.innerHTML = `
                                <a href="${url}" class="text-dark d-block">
                                    <strong>${item.result}</strong> 
                                    <small>${item.short_description}</small>
                                </a>
                            `;

                            suggestionsList.appendChild(listItem);
                        });

                        suggestionsList.style.display = 'block';
                    } else {
                        suggestionsList.innerHTML = '<li class="list-group-item text-center text-muted">No results found.</li>';
                        suggestionsList.style.display = 'block';
                    }
                })
                .catch(error => console.error("Error fetching search suggestions:", error));
        }
    });

    // Close the suggestions when clicking outside
    document.addEventListener("click", function (event) {
        if (!event.target.closest('.app-search')) {
            document.getElementById("suggestions-list").style.display = 'none';
        }
    });
});


</script>
