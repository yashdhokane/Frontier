@if (Route::currentRouteName() != 'dash')
    @extends('home')

    @section('content')
    @endif

    <div class="page-breadcrumb" style="margin-top: 0px !important; margin-top: 0px !important;">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <style>
            .container {
                width: 80%;
            }

            .card {
                background-color: #ffffff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                padding: 20px;
                margin-top: 30px;
            }

            .tabs {
                margin-left: 31% !important;
                display: flex;
                gap: 10px;
                margin-bottom: 20px;
            }

            .tab {
                margin-left: 1% !important;
                padding: 10px 15px;
                background-color: #ffffff;
                border: 1px solid #ccc;
                border-radius: 5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .tab.active {
                background-color: #4CAF50;
                color: white;
            }

            .close-tab {
                color: red;
                cursor: pointer;
            }

            .form-group {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-bottom: 20px;
            }

            .form-group input {
                padding: 12px;
                width: 70%;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-size: 1em;
                transition: all 0.3s ease;
            }

            .form-group input:focus {
                border-color: #4CAF50;
                outline: none;
            }

            .form-group button {
                padding: 12px 20px;
                font-size: 1em;
                color: white;
                background-color: #4CAF50;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .form-group button:hover {
                background-color: #45a049;
            }

            iframe {
                width: 100%;
                height: 500px;
                border: none;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .footer {
                text-align: center;
                margin-top: 40px;
                font-size: 0.9em;
                color: #777;
            }

            .footer a {
                color: #4CAF50;
                text-decoration: none;
            }

            .footer a:hover {
                text-decoration: underline;
            }

            .row {
                margin-top: 0px !important;
            }
        </style>
        <style>
            /* Example of enhanced styles */

            /* Enhanced button style */
            #new-tab-button {
                padding: 8px 20px;
                background-color: #4CAF50;
                /* Changed color */
                color: white;
                border: none;
                border-radius: 20px;
                cursor: pointer;
                font-size: 14px;
                margin-left: 15px;
            }

            /* Enhanced active tab style */
            .tab.active {
                background-color: #007bff;
                /* Darker shade for better contrast */
                color: white;
            }

            /* Enhanced search button style */
            #search-button {
                padding: 15px 30px;
                background-color: #007bff;
                /* Matching the primary color */
                color: white;
                border: none;
                border-radius: 50px;
                cursor: pointer;
                font-size: 16px;
                margin-left: 10px;
            }

            /* Enhanced scrollbar style */
            #tab-container::-webkit-scrollbar {
                height: 8px;
            }

            #tab-container::-webkit-scrollbar-thumb {
                background-color: #666;
                /* Adjusted color */
                border-radius: 4px;
            }

            #tab-container::-webkit-scrollbar-thumb:hover {
                background-color: #444;
                /* Darker hover color */
            }

            #tab-container::-webkit-scrollbar-track {
                background-color: #f1f1f1;
            }
        </style>

        <div class="row">
            <div class="col-sm-12 card card-body" style="padding:0px!important;">
                <!-- --------------------- start Default Form Elements ---------------- -->
                <div class="d-flex justify-content-end">
                    <!-- Clear Tabs Button -->
                    <button class="btn btn-danger btn-lg" onclick="confirmClearTabs()">
                        <i class="fas fa-trash-alt"></i> Clear All Tabs
                    </button>
                </div>

                <div class="tabs" id="tab-container"
                    style="display: flex; align-items: left; overflow-x: auto; white-space: nowrap; padding: 10px; background-color: #ffffff;">

                    <!-- New Tab Button -->
                    <button id="new-tab-button" type="button"
                        style="padding: 8px 20px; background-color: #28a745; color: white; border: none; border-radius: 20px; cursor: pointer; font-size: 14px; margin-left: 15px;">
                        + New Tab
                    </button>



                </div>
                <div class="">
                    <!-- Tabs Container (Sticky) -->
                    <div class="tabs-container"
                        style="position: sticky; top: 0; background-color: white; z-index: 10; padding: 10px; ">

                        <!-- Search Box and Button -->
                        <div class="form-group"
                            style="display: flex; align-items: center; justify-content: center; width: 100%; padding: 10px;">
                            <div class="col-md-8" style="display: flex; justify-content: center; width: 100%;">

                                <input type="text" id="search-box" placeholder="Enter Website URL"
                                    style="padding: 15px 20px; border: 1px solid #ccc; border-radius: 50px; width: 100%; font-size: 16px;">

                                <button id="search-button" type="button"
                                    style="padding: 15px 30px; background-color: #007bff; color: white; border: none; border-radius: 50px; cursor: pointer; font-size: 16px; margin-left: 10px;">
                                    Enter
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Tabs container (Scrollable) -->


                    <style>
                        /* Sticky Search Box and Button (Google-like Search) */
                        .tabs-container {
                            position: sticky;
                            top: 0;
                            background-color: white;
                            z-index: 10;
                            padding: 10px;

                            /* Adds shadow for a subtle elevation effect */
                        }

                        /* Search Bar Styling (Google-like) */
                        .form-group {
                            display: flex;
                            justify-content: center;
                            width: 100%;
                            padding: 10px;
                        }

                        .col-md-8 {
                            display: flex;
                            justify-content: center;
                            width: 100%;
                        }

                        input[type="text"] {
                            padding: 15px 20px;
                            border: 1px solid #ccc;
                            border-radius: 50px;
                            font-size: 16px;
                            width: 100%;
                            max-width: 600px;
                            /* Limits the width of the search box */
                        }

                        button#search-button {
                            padding: 15px 30px;
                            background-color: #007bff;
                            color: white;
                            border: none;
                            border-radius: 50px;
                            cursor: pointer;
                            font-size: 16px;
                            margin-left: 10px;
                        }

                        /* Scrollable Tabs Container */
                        .tabs {
                            display: flex;
                            align-items: center;
                            overflow-x: auto;
                            /* Allows horizontal scrolling */
                            white-space: nowrap;
                            /* Prevents wrapping of tabs */
                            padding: 10px;
                            background-color: #f1f1f1;
                        }

                        /* Tab Styling */
                        .tab {
                            display: inline-block;
                            padding: 10px 20px;
                            margin: 0 5px;
                            background-color: #f1f1f1;
                            border-radius: 20px 20px 0 0;
                            cursor: pointer;
                            font-size: 14px;
                            transition: background-color 0.3s ease;
                            white-space: nowrap;
                        }

                        .tab.active {
                            background-color: #007bff;
                            color: white;
                        }

                        .tab:hover {
                            background-color: #e0e0e0;
                        }

                        /* New Tab Button Styling */
                        #new-tab-button {
                            padding: 8px 20px;
                            background-color: #28a745;
                            color: white;
                            border: none;
                            border-radius: 20px;
                            cursor: pointer;
                            font-size: 14px;
                            margin-left: 15px;
                        }

                        /* Optional: Customizing the scrollbar for better visibility */
                        #tab-container::-webkit-scrollbar {
                            height: 8px;
                        }

                        #tab-container::-webkit-scrollbar-thumb {
                            background-color: #888;
                            border-radius: 4px;
                        }

                        #tab-container::-webkit-scrollbar-thumb:hover {
                            background-color: #555;
                        }

                        #tab-container::-webkit-scrollbar-track {
                            background-color: #f1f1f1;
                        }
                    </style>


                    <!-- Iframe Container -->
                    <div id="iframe-container" style="margin-top: 20px;"></div>

                    <script>
                        const routes = {
                            saveTabData: @json(route('saveTabData')),
                            getUserTabs: @json(route('getUserTabs'))
                        };
                    </script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchButton = document.getElementById('search-button');
                            const searchBox = document.getElementById('search-box');
                            const tabContainer = document.getElementById('tab-container');
                            const iframeContainer = document.getElementById('iframe-container');
                            const newTabButton = document.getElementById('new-tab-button');
                            let tabCount = 0;

                            // Function to create a new tab with URL and order
                            function createNewTab(url = '', order = null) {
                                tabCount++;
                                const tabId = `tab-${tabCount}`;
                                const iframeId = `iframe-${tabCount}`;
                                const tabOrder = order || tabCount;

                                // Extract domain name from the URL
                                const domainName = getDomainName(url);

                                // Create new tab button
                                const tab = document.createElement('div');
                                tab.classList.add('tab');
                                tab.setAttribute('data-tab-id', tabId);
                                tab.setAttribute('data-tab-order', tabOrder);
                                tab.innerHTML = `${domainName} <span class="close-tab" data-tab-id="${tabId}">&times;</span>`;
                                tab.addEventListener('click', () => switchTab(tabId, iframeId));
                                tabContainer.insertBefore(tab, newTabButton);

                                // Create new iframe
                                const iframe = document.createElement('iframe');
                                iframe.id = iframeId;
                                iframe.src = url || 'about:blank';
                                iframe.style.display = 'none'; // Start with iframe hidden
                                iframeContainer.appendChild(iframe);

                                // Switch to the new tab
                                switchTab(tabId, iframeId);
                            }

                            // Helper function to extract the domain name from the URL
                            function getDomainName(url) {
                                try {
                                    const domain = new URL(url).hostname;
                                    return domain.replace('www.', ''); // Remove "www." if present
                                } catch (e) {
                                    return 'Untitled'; // Default in case of an invalid URL
                                }
                            }

                            // Function to switch between tabs and update the document title
                            function switchTab(tabId, iframeId) {
                                // Deactivate all tabs and iframes
                                document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
                                document.querySelectorAll('iframe').forEach(iframe => iframe.style.display = 'none');

                                // Activate the selected tab and iframe
                                const activeTab = document.querySelector(`.tab[data-tab-id="${tabId}"]`);
                                activeTab.classList.add('active');
                                document.getElementById(iframeId).style.display = 'block';

                                // Update the document title with the active tab's domain name
                                const domainName = activeTab.textContent.trim().split(' ')[0];
                                document.title = domainName;
                            }

                            // Function to close a tab
                            function closeTab(tabId) {
                                const tab = document.querySelector(`.tab[data-tab-id="${tabId}"]`);
                                const iframeId = tab.getAttribute('data-tab-id').replace('tab-', 'iframe-');
                                const iframe = document.getElementById(iframeId);

                                // Remove the tab and iframe
                                tab.remove();
                                iframe.remove();

                                // Get the neighboring tab to focus on after closing
                                const remainingTabs = tabContainer.querySelectorAll('.tab');
                                const currentIndex = Array.from(remainingTabs).findIndex(tab => tab.getAttribute('data-tab-id') ===
                                    tabId);

                                // If there are remaining tabs, switch to the next or previous one
                                if (remainingTabs.length > 0) {
                                    const nextTab = remainingTabs[currentIndex] || remainingTabs[currentIndex - 1];
                                    const nextIframeId = nextTab.getAttribute('data-tab-id').replace('tab-', 'iframe-');
                                    switchTab(nextTab.getAttribute('data-tab-id'), nextIframeId);
                                }
                            }

                            // Function to save tab data
                            function saveTabData(url, order) {
                                const queryString = new URLSearchParams({
                                    tab_url: url,
                                    tab_order: order
                                }).toString();

                                fetch(`${routes.saveTabData}?${queryString}`, {
                                        method: 'GET',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                                'content')
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Tab data saved:', data);

                                        // Extract the domain name from the URL
                                        const domainName = getDomainName(url);

                                        // Update the tab's display name
                                        const activeTab = document.querySelector('.tab.active');
                                        if (activeTab) {
                                            activeTab.innerHTML =
                                                `${domainName} <span class="close-tab" data-tab-id="${activeTab.getAttribute('data-tab-id')}">&times;</span>`;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error saving tab data:', error);
                                    });
                            }

                            // Search button functionality
                            searchButton.addEventListener('click', function() {
                                let query = searchBox.value.trim();

                                // Ensure query is not empty
                                if (query) {
                                    // Add http:// if the query doesn't start with http:// or https://
                                    if (!/^https?:\/\//i.test(query)) {
                                        query = "https://" + query;
                                    }

                                    // Add .com if the query doesn't already end with .com, .org, .net, etc.
                                    if (!/\.[a-z]{2,}$/i.test(query)) {
                                        query += ".com";
                                    }

                                    // Open the query in the active iframe
                                    const activeTab = document.querySelector('.tab.active');
                                    if (activeTab) {
                                        const iframeId = activeTab.getAttribute('data-tab-id').replace('tab-', 'iframe-');
                                        const iframe = document.getElementById(iframeId);
                                        iframe.src = query;

                                        // Save tab data
                                        const tabOrder = activeTab.getAttribute('data-tab-order');
                                        saveTabData(query, tabOrder);

                                        // Update document title
                                        document.title = getDomainName(query);
                                    }
                                } else {
                                    alert("Please enter a search query.");
                                }
                            });

                            // New tab button functionality
                            newTabButton.addEventListener('click', () => createNewTab());

                            // Tab close functionality
                            tabContainer.addEventListener('click', function(event) {
                                if (event.target.classList.contains('close-tab')) {
                                    const tabId = event.target.getAttribute('data-tab-id');
                                    closeTab(tabId);
                                }
                            });

                            // Fetch and create tabs from server-side data
                            fetch(routes.getUserTabs)
                                .then(response => response.json())
                                .then(data => {
                                    // Sort tabs based on tab_order before creating
                                    data.sort((a, b) => a.tab_order - b.tab_order).forEach(tab => {
                                        createNewTab(tab.tab_url, tab.tab_order);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error fetching tabs:', error);
                                });
                        });
                    </script>



                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const tabContainer = document.getElementById('tab-container');

                            // Initialize Sortable for tabs container
                            const sortable = new Sortable(tabContainer, {
                                group: 'tabs', // Optional: To enable sorting between multiple containers
                                animation: 150, // Smooth animation while dragging
                                handle: '.tab', // Drag handle (can also be any child element inside tab)
                                onEnd(evt) {
                                    console.log('Tab moved:', evt.item); // Logs the moved tab
                                    // Optionally, save the new order to your backend
                                }
                            });
                        });
                    </script>



                </div>
                <!-- --------------------- end Default Form Elements ---------------- -->
            </div>
        </div>


    </div> <!-- /.row -->
    <!-- Confirmation Modal -->
    <div class="modal fade" id="clearTabsModal" tabindex="-1" role="dialog" aria-labelledby="clearTabsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clearTabsModalLabel">Confirmation</h5>

                </div>
                <div class="modal-body">
                    Are you sure you want to clear your entire tab?
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="clearTabsModalclose()" class="btn btn-secondary"
                        data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="clearTabs()">Yes, Clear</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmClearTabs() {
            $('#clearTabsModal').modal('show');
        }

        function clearTabsModalclose() {
            $('#clearTabsModal').modal('hide'); // Correct way to hide the modal
        }

        function clearTabs() {
            $.ajax({
                url: '{{ route('user.clearTabs') }}', // Replace with your route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $('#clearTabsModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                },
                error: function() {
                    alert('Failed to clear tabs. Please try again.');
                }
            });
        }
    </script>



    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
