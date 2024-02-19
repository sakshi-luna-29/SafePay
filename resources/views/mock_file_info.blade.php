@extends('layouts.adminApp')
@section('title', 'mock file info')
@section('page-heading','Mock File INfo')
@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">


@endsection
@section('content')
<!-- Sidebar Area End Here -->
<div class="dashboard-content-one">
    <!-- Breadcubs Area Start Here -->
    <div class="row gutters-20">
        <div class="col-xl-12 col-sm-12 col-12">
            <div class="breadcrumbs-area">
                <h3>MOCK FILE INFO</h3>

                <div class="log_user">
                    <label for="#">TOtal Male:</label>

                    <button type="button" class="btn btn-primary"> {{$maleCount}}</button>
                </div>
                <div>
                    <label for="#">TOtal Female:</label>

                    <button type="button" class="btn btn-danger">{{$femaleCount}} </button>
                </div>

                <div>
                    <label for="location">Filter By Location:</label>
                    <select id="location">
                        <option value="">ALL</option>

                        <option value="London">London</option>
                        <option value="Paris">Paris</option>
                        <option value="Kansas City">Kansas City</option>
                    </select>
                </div>
                <div>
                    <div class="form-group mb-0 position-relative icons_set">
                        <label for="location">Search:</label>

                        <input type="text" class="form-control" placeholder="Search" name="name" id="search-input" style="margin-top: 12px;">
                        <i class="far fa-search"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gutters-20">
        <div class="col-xl-9 col-sm-6 col-12">
            <!-- Breadcubs Area End Here -->
            <!-- Dashboard summery Start Here -->
            <div class="row gutters-20">
                <table id="data-table" class="table1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <th>GENDER</th>
                            <th>LATITUDE</th>
                            <th>LONGITUDE</th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tbody id="data-body"> -->

                        @foreach($markers as $marker)
                        <tr>
                            <td>{{ $marker['id'] }}</td>
                            <td>{{ $marker['first_name'] }}</td>
                            <td>{{ $marker['last_name'] }}</td>
                            <td>{{ $marker['gender'] }}</td>
                            <td>{{ $marker['lat'] }}</td>
                            <td>{{ $marker['lon'] }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="data-container"></div>

            </div>
            <div>
                @if ($currentPage > 1)
                <a href="?page={{ $currentPage - 1 }}">Previous</a>
                @endif

                Page {{ $currentPage }} of {{ $totalPages }}

                @if ($currentPage < $totalPages) <a href="?page={{ $currentPage + 1 }}">Next</a>
                    @endif
            </div>

        </div>
    </div>
    @endsection
    @section('script')
    <script>
        document.getElementById('location').addEventListener('change', function() {
            var selectedLocation = this.value;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/filter-location',
                type: 'POST',
                data: {
                    location: selectedLocation,
                    _token: csrfToken, // Include the CSRF token in the request data
                },
                success: function(response) {
                    var tbody = $('#data-table tbody');
                    tbody.empty(); // Clear existing rows

                    // Iterate over data and add rows to the table
                    response.forEach(function(response1) {

                        var row = $('<tr>');
                        row.append('<td>' + response1.id + '</td>');
                        row.append('<td>' + response1.first_name + '</td>');
                        row.append('<td>' + response1.last_name + '</td>');
                        row.append('<td>' + response1.gender + '</td>');
                        row.append('<td>' + response1.lat + '</td>');
                        row.append('<td>' + response1.lon + '</td>');
                        tbody.append(row);

                    });

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        });

        document.getElementById('search-input').addEventListener('keyup', function() {

            var query = $(this).val().trim();
            var selected = this.value;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/search',
                type: 'POST',
                data: {
                    query: selected,
                    _token: csrfToken, // Include the CSRF token in the request data
                },
                success: function(response) {
                    var tbody = $('#data-table tbody');
                    tbody.empty(); // Clear existing rows

                    // Iterate over data and add rows to the table
                    response.forEach(function(response2) {

                        var elements = response2.split(',');

                        var row = $('<tr>');
                        row.append('<td>' + elements[0] + '</td>');
                        row.append('<td>' + elements[1] + '</td>');
                        row.append('<td>' + elements[2] + '</td>');
                        row.append('<td>' + elements[3] + '</td>');
                        row.append('<td>' + elements[4] + '</td>');
                        row.append('<td>' + elements[5] + '</td>');

                        tbody.append(row);

                    });

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        });
    </script>
    @endsection