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
            <form id="searchForm" class="flex justify-center">
                <select id="category" name="category" class="rounded-l-full">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 px-3 text-white rounded-r-full">Search</button>
            </form>
        </div>
    </div>

    <!-- Loader element with transparent overlay -->
    <!-- <div id="loader" class="hidden fixed top-0 left-0 w-full h-full z-50 flex justify-center items-center">
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-24 w-24"></div>
        <div class="overlay absolute inset-0 bg-gray-900 opacity-25"></div>
    </div> -->


    <div class="bg-white p-12 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="results">
            <!-- Cards will be dynamically added here -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to convert rating to star icons with custom color
        function getStarRating(rating) {
            let stars = '';
            const totalStars = 5;
            const roundedRating = Math.round(rating * 2) / 2; // Round rating to nearest 0.5
            const fullStars = Math.floor(roundedRating);
            const halfStars = Math.ceil(roundedRating - fullStars);

            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star text-yellow-500"></i>';
            }

            if (halfStars === 1) {
                stars += '<i class="fas fa-star-half-alt text-yellow-500"></i>';
            }

            const emptyStars = totalStars - fullStars - halfStars;
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star text-yellow-500"></i>';
            }

            return stars;
        }


        $(document).ready(function() {
            // Function to load nearby businesses when the page loads
            loadNearbyBusinesses();

            // Show loader when AJAX starts
            $(document).ajaxStart(function() {
                $('#loader').removeClass('hidden');
            });

            // Hide loader when AJAX stops
            $(document).ajaxStop(function() {
                $('#loader').addClass('hidden');
            });

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

            // Function to load nearby businesses
            function loadNearbyBusinesses() {
                // Fetch the user's live location
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        // Call the function to search businesses with live location
                        displayBusinesses(latitude, longitude);
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            function displayBusinesses(latitude, longitude) {
                var category = "restaurant";
                $.ajax({
                    type: 'GET',
                    url: '/search-businesses',
                    data: {
                        category: category,
                        location: latitude + ',' + longitude,
                    },
                    success: function(data) {
                        var resultsContainer = $('#results');
                        resultsContainer.empty();

                        $.each(data.results, function(index, result) {
                            // Get place details to retrieve website information
                            $.ajax({
                                type: 'GET',
                                url: '/get-place-details',
                                data: {
                                    place_id: result.place_id,
                                },
                                success: function(details) {
                                    // Check if the business has a website
                                    if (details.result.website) {
                                        // Creating card and appending details
                                        var card = $('<div class="bg-white p-4 rounded-lg shadow-md">');
                                        var logo = $('<img class="result-logo w-full mb-4 rounded-md" alt="' + result.name + '">');
                                        if (result.photos) {
                                            logo.attr('src', 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' + result.photos[0].photo_reference + '&key=AIzaSyD3X7LnXivxXQyxsb2WKVGAVjrV2f4blng');
                                        } else {
                                            logo.attr('src', '../images/default.jpg'); // Provide a default logo image
                                        }
                                        card.append(logo);
                                        card.append('<h5 class="text-lg font-bold">' + result.name + '</h5>');
                                        card.append('<p class="text-sm text-gray-600">' + result.vicinity + '</p>');
                                        if (result.rating) {
                                            const ratingStars = getStarRating(result.rating);
                                            card.append('<p class="text-sm text-grey-600">Rating: ' + ratingStars + ' (' + result.user_ratings_total + ' reviews)</p>');
                                        }
                                        // Create a flex container to hold website link and location icon
                                        var flexContainer = $('<div class="flex justify-between items-center"></div>');
                                        // Add the website link to the flex container
                                        var websiteLink = $('<a href="' + details.result.website + '" target="_blank" class="text-white text-center bg-blue-600 w-36 py-2 px-3 my-2 rounded-md">view</a>');
                                        flexContainer.append(websiteLink);
                                        // Add the location icon to the flex container
                                        var locationIcon = $('<a href="https://www.google.com/maps/search/?api=1&query=' + result.geometry.location.lat + ',' + result.geometry.location.lng + '" target="_blank" class="text-blue-500 w-24 flex justify-center"><i class="fa fa-map-marker"></i></a>');
                                        flexContainer.append(locationIcon);

                                        card.append(flexContainer);

                                        resultsContainer.append(card);
                                    }
                                },
                                error: function(error) {
                                    console.log('Error:', error);
                                }
                            });
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    },
                });
            }

            // Function to search businesses using AJAX
            function searchBusinesses(latitude, longitude) {
                var category = $('#category').val();
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
                                        // Check if the business has a website
                                        if (details.result.website) {
                                            // Creating card and appending details
                                            var card = $('<div class="bg-white p-4 rounded-lg shadow-md">');
                                            var logo = $('<img class="result-logo w-full mb-4 rounded-md" alt="' + result.name + '">');
                                            if (result.photos) {
                                                logo.attr('src', 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' + result.photos[0].photo_reference + '&key=AIzaSyD3X7LnXivxXQyxsb2WKVGAVjrV2f4blng');
                                            } else {
                                                logo.attr('src', '../images/default.jpg'); // Provide a default logo image
                                            }
                                            card.append(logo);
                                            card.append('<h5 class="text-lg font-bold">' + result.name + '</h5>');
                                            card.append('<p class="text-sm text-gray-600">' + result.vicinity + '</p>');
                                            if (result.rating) {
                                                card.append('<p class="text-sm text-gray-600">Rating: ' + result.rating + ' stars (' + result.user_ratings_total + ' reviews)</p>');
                                            }
                                            // Create a flex container to hold website link and location icon
                                            var flexContainer = $('<div class="flex justify-between items-center"></div>');
                                            // Add the website link to the flex container
                                            var websiteLink = $('<a href="' + details.result.website + '" target="_blank" class="text-white text-center bg-blue-600 w-36 py-2 px-3 my-2 rounded-md">view</a>');
                                            flexContainer.append(websiteLink);
                                            // Add the location icon to the flex container
                                            var locationIcon = $('<a href="https://www.google.com/maps/search/?api=1&query=' + result.geometry.location.lat + ',' + result.geometry.location.lng + '" target="_blank" class="text-blue-500 w-24 flex justify-center"><i class="fa fa-map-marker"></i></a>');
                                            flexContainer.append(locationIcon);

                                            card.append(flexContainer);
                                            resultsContainer.append(card);
                                        }
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