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
                background-color: #f1f1f1;
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

        <div class="row">
            <div class="col-sm-12" style="padding:0px!important;">
                <!-- --------------------- start Default Form Elements ---------------- -->
                <div class="card card-body">
                    <div class="tabs" id="tab-container" style="display: flex; align-items: center;  padding: 10px;">

                        <!-- Search Box and Button -->
                        <div class="form-group" style="display: flex; align-items: center; ">
                            <input type="text" id="search-box" placeholder="Enter Website URL"
                                style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 4px; width: 200px;">
                            <button id="search-button"
                                style="padding: 5px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Enter</button>
                        </div>

                        <!-- New Tab Button -->
                        <button id="new-tab-button"
                            style="padding: 5px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">+
                            New Tab</button>

                    </div>

                    <!-- Iframe Container -->
                    <div id="iframe-container" style="margin-top: 20px;"></div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchButton = document.getElementById('search-button');
                            const searchBox = document.getElementById('search-box');
                            const tabContainer = document.getElementById('tab-container');
                            const iframeContainer = document.getElementById('iframe-container');
                            const newTabButton = document.getElementById('new-tab-button');
                            let tabCount = 0;

                            function createNewTab(url = '') {
                                tabCount++;
                                const tabId = `tab-${tabCount}`;
                                const iframeId = `iframe-${tabCount}`;

                                // Create new tab button
                                const tab = document.createElement('div');
                                tab.classList.add('tab');
                                tab.setAttribute('data-tab-id', tabId);
                                tab.innerHTML = `Tab ${tabCount} <span class="close-tab" data-tab-id="${tabId}">&times;</span>`;
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

                            function switchTab(tabId, iframeId) {
                                // Deactivate all tabs and iframes
                                document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
                                document.querySelectorAll('iframe').forEach(iframe => iframe.style.display = 'none');

                                // Activate the selected tab and iframe
                                document.querySelector(`.tab[data-tab-id="${tabId}"]`).classList.add('active');
                                document.getElementById(iframeId).style.display = 'block';
                            }

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

                            newTabButton.addEventListener('click', () => createNewTab());

                            tabContainer.addEventListener('click', function(event) {
                                if (event.target.classList.contains('close-tab')) {
                                    const tabId = event.target.getAttribute('data-tab-id');
                                    closeTab(tabId);
                                }
                            });

                            // Add search functionality to load URL into the active tab
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
                                    }
                                } else {
                                    alert("Please enter a search query.");
                                }
                            });

                            // Create the initial tab
                            createNewTab();
                        });
                    </script>

                </div>
                <!-- --------------------- end Default Form Elements ---------------- -->
            </div>
        </div>


    </div> <!-- /.row -->

    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
