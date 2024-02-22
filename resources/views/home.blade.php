<x-app-layout>
    <div name="header">
        <div class="banner flex justify-center items-center ">
            <div class="banner-content">
                <p class="text-blue-600">Welcome to</p>
                <p class="font-semibold text-6xl mb-2 text-white leading-tight">Stores Of Stores</p>
                <p class="text-white">Your Ultimate Shopping Companion!</p>
            </div>
        </div>
    </div>

    <div class="bg-slate-100 p-12 mb-5">
        <div class="col">
            <form id="searchForm">
                <select id="category" name="category">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="grid grid-cols-3 gap-4" id="results">
            <!-- Cards will be dynamically added here -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to handle form submission
            $('#searchForm').submit(function(event) {
                event.preventDefault();

                // Fetch the user's live location
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        // Call the function to search businesses with live location
                        searchBusinesses(latitude, longitude);
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });

            // Function to search businesses using AJAX
            function searchBusinesses(latitude, longitude) {
                var category = $('#category').val();

                // Debugging: Output data being sent in AJAX request to console
                console.log("Category:", category);
                console.log("Location:", latitude + ',' + longitude);

                $.ajax({
                    type: 'GET',
                    url: '/search-businesses',
                    data: {
                        category: category,
                        location: latitude + ',' + longitude, // Use live location
                    },
                    success: function(data) {
                        var resultsContainer = $('#results');
                        resultsContainer.empty();

                        $.each(data.results, function(index, result) {
                            // Check if the business belongs to the specified category
                            if (result.types.includes(category.toLowerCase())) {
                                // Get place details to retrieve website information
                                $.ajax({
                                    type: 'GET',
                                    url: '/get-place-details',
                                    data: {
                                        place_id: result.place_id,
                                    },
                                    success: function(details) {
                                        var card = $('<div class="bg-white p-4 rounded-lg shadow-md">');
                                        var logo = $('<img class="result-logo w-full mb-4 rounded-md" alt="' + result.name + '">');
                                        if (result.photos) {
                                            logo.attr('src', 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' + result.photos[0].photo_reference + '&key=AIzaSyD3X7LnXivxXQyxsb2WKVGAVjrV2f4blng');
                                        } else {
                                            logo.attr('src', 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='); // Provide a default logo image
                                        }
                                        card.append(logo);
                                        card.append('<h5 class="text-lg font-bold">' + result.name + '</h5>');
                                        card.append('<p class="text-sm text-gray-600">' + result.vicinity + '</p>');
                                        if (result.rating) {
                                            card.append('<p class="text-sm text-gray-600">Rating: ' + result.rating + ' stars (' + result.user_ratings_total + ' reviews)</p>');
                                        }
                                        if (details.result.website) {
                                            card.append('<a href="' + details.result.website + '" target="_blank" class="text-blue-500">Website</a>');
                                        }
                                        resultsContainer.append(card);
                                    },
                                    error: function(error) {
                                        console.log('Error:', error);
                                    }
                                });
                            }
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    },
                });
            }
        });
    </script>

</x-app-layout>