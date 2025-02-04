<!-- resources/views/clients/create.blade.php -->

@if (Route::currentRouteName() != 'dash')
    @extends('home')

    @section('content')
    @endif

    <div class="page-breadcrumb" style="margin-top: 0px !important;  margin-top: 0px !important;">

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

            .card h1 {
                text-align: center;
                color: #333;
                font-size: 2em;
                margin-bottom: 20px;
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
                margin-top: 20px;
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

                    <div class="form-group">
                        <input type="text" id="search-box" placeholder="Enter Website URL">
                        <button id="search-button">Enter</button>
                    </div>
                    <iframe id="search-results" width="100%" height="500px"></iframe>

                    <script>
                        // Ensure that the DOM is fully loaded before attaching event listeners
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchButton = document.getElementById('search-button');
                            const searchBox = document.getElementById('search-box');
                            const iframe = document.getElementById('search-results');

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

                                    // Dynamically update iframe src with the complete URL
                                    iframe.src = query;
                                } else {
                                    alert("Please enter a search query.");
                                }
                            });
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
