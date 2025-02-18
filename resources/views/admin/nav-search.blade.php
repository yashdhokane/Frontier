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
        display: none;
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
    <button type="submit" class="srh-btn" id="clear-search">
        <i data-feather="x" class="feather-sm"></i>
    </button>

    <!-- Suggestions Dropdown -->
    <ul id="suggestions-list" class="list-group position-absolute w-100 mt-2"></ul>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search-query");
        const suggestionsList = document.getElementById("suggestions-list");
        const clearSearchBtn = document.getElementById("clear-search");
        let selectedIndex = -1; // Track keyboard selection

        // Validate search input before submission
        function validateSearchInput() {
            if (searchInput.value.trim().length < 3) {
                alert("Please enter at least 3 characters for the search.");
                return false;
            }
            return true;
        }

        // Handle input event for live search suggestions
        searchInput.addEventListener("input", function () {
            const query = searchInput.value.trim();

            // Show clear button if input has value
            clearSearchBtn.style.display = query.length > 0 ? "inline-block" : "none";

            // Fetch suggestions only if query has at least 3 characters
            if (query.length >= 3) {
                fetch("{{ route('global.globalSearchautosuggest') }}?query=" + query)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = ''; // Clear previous suggestions
                        selectedIndex = -1;

                        if (data.length > 0) {
                            data.forEach((item, index) => {
                                const listItem = document.createElement('li');
                                listItem.classList.add('list-group-item', 'suggestion-item');
                                listItem.innerHTML = `<a href="javascript:void(0)" data-value="${item.result}">${item.result}</a>`;
                                listItem.addEventListener("click", function () {
                                    searchInput.value = item.result; // Set input value
                                    suggestionsList.style.display = 'none'; // Hide suggestions
                                    document.querySelector(".app-search").submit(); // Auto-submit
                                });
                                suggestionsList.appendChild(listItem);
                            });
                        } else {
                            suggestionsList.innerHTML = '<li class="list-group-item text-muted">No results found.</li>';
                        }
                        suggestionsList.style.display = 'block';
                    })
                    .catch(error => console.error("Error fetching search suggestions:", error));
            } else {
                suggestionsList.style.display = 'none';
            }
        });

        // Handle keyboard navigation in suggestions
        searchInput.addEventListener("keydown", function (event) {
            const items = suggestionsList.querySelectorAll(".suggestion-item a");

            if (items.length > 0) {
                if (event.key === "ArrowDown") {
                    event.preventDefault();
                    selectedIndex = (selectedIndex + 1) % items.length;
                } else if (event.key === "ArrowUp") {
                    event.preventDefault();
                    selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                } else if (event.key === "Enter") {
                    if (selectedIndex > -1) {
                        event.preventDefault();
                        searchInput.value = items[selectedIndex].dataset.value;
                        suggestionsList.style.display = "none";
                        document.querySelector(".app-search").submit();
                    }
                }

                // Highlight the selected item
                items.forEach((item, index) => {
                    item.parentElement.classList.toggle("active", index === selectedIndex);
                });
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener("click", function (event) {
            if (!event.target.closest('.app-search')) {
                suggestionsList.style.display = 'none';
            }
        });

        // Clear input when clicking the clear button
        clearSearchBtn.addEventListener("click", function () {
            searchInput.value = "";
            suggestionsList.style.display = "none";
            clearSearchBtn.style.display = "none";
        });
    });
</script>
