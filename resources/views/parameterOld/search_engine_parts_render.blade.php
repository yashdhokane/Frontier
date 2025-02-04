<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Google Parts Example</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!-- Button to Trigger Search -->
    <button style="display:none;" id="searchTrigger" data-product-name="yash">Search for Product</button>

    <!-- CSE Search Container -->
    <div id="google-cse-search-container"></div>

    <!-- Results Container -->
    <div id="searchResults"></div>

    <script>
        $(document).ready(function() {
            const apiKey = 'AIzaSyCqLCSWRGzZ_3ckbpGWieLTZrhjMInUz9A'; // Your API Key
            const cx = '53f16a882f4d545be'; // Your CSE ID

            // Function to execute the Google search using API
            function searchGoogle(query) {
                const searchUrl = `https://www.googleapis.com/customsearch/v1?q=${query}&key=${apiKey}&cx=${cx}`;

                $.get(searchUrl, function(data) {
                    console.log(data); // View the response for debugging

                    // Show search results
                    let resultsHtml = '';
                    if (data.items) {
                        data.items.forEach(function(item) {
                            resultsHtml += `<div>
                                <h3><a href="${item.link}" target="_blank">${item.title}</a></h3>
                                <p>${item.snippet}</p>
                            </div>`;
                        });
                    } else {
                        resultsHtml = '<p>No results found</p>';
                    }
                    $('#searchResults').html(resultsHtml);
                }).fail(function() {
                    console.error('Error fetching search results');
                    $('#searchResults').html('<p>Sorry, something went wrong while fetching results.</p>');
                });
            }

            // Handle button click to trigger search
            $('#searchTrigger').on('click', function() {
                const productName = $(this).data('product-name');
                console.log('Searching for:', productName);
                searchGoogle(productName);
            });

 

        });
    </script>

</body>

</html>
