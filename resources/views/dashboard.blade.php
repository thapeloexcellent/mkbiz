<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="/search" method="get">
                        <input type="text" name="q" placeholder="Search...">
                        <button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="searchForm">
                        <input type="text" id="category" name="category" placeholder="Enter category">
                        <button type="submit">Search</button>
                    </form>
                    <ul id="results"></ul>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').submit(function(event) {
                event.preventDefault();

                var category = $('#category').val();

                $.ajax({
                    type: 'GET',
                    url: '/search-businesses',
                    data: {
                        category: category,
                        location: '41.40338, 2.17403', // Set your location or get it dynamically
                    },
                    success: function(data) {

                        console.log('data:', data);
                        // Handle the data and update the results
                        var resultsContainer = $('#results');
                        resultsContainer.empty();

                        // Loop through data.results and append results to the container
                        // Adjust this part based on the actual structure of your API response
                        $.each(data.results, function(index, result) {
                            resultsContainer.append('<li>' + result.name + '</li>');
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    },
                });
            });
        });
    </script>

</x-app-layout>