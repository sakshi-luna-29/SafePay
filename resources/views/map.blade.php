@extends('layouts.adminApp')
@section('title', 'maps')
@section('page-heading','maps')
@section('style')


@endsection
@section('content')
<div class="dashboard-content-one">
    <!-- Breadcubs Area Start Here -->
    <div class="row gutters-20">
        <div class="col-xl-12 col-sm-12 col-12">
            <div class="breadcrumbs-area">
                <h3>MAP INFO</h3>
                <div id="filter-container">
                    <label for="filter-gender">Filter by Gender:</label>
                    <select id="filter-gender">
                        <option value="all" class="nav sub-group-menu">All</option>
                        <option value="Male" class="nav sub-group-menu">Male</option>
                        <option value="Female" class="nav sub-group-menu">Female</option>
                    </select>
                </div>

            </div>
        </div>
    </div>
    <div class="row gutters-20">
        <div class="col-xl-9 col-sm-6 col-12">
            <div class="row gutters-20">

                <div id="map"> </div>
            </div>
        </div>
    </div>
    @endsection
    @section('script')
    <script>
        var map;
        var selectedGender = "";

        function initMap(selectedGender) {
            var people = @json($people);
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 0,
                    lng: 0
                },
                zoom: 2
            });

            // Add markers for each person
            people.forEach(function(person) {
                var latitude = person.split(',')[4];
                var longitude = person.split(',')[5];
                var first_name = person.split(',')[1];
                var last_name = person.split(',')[2];
                var name = first_name + " " + last_name;
                var gender = person.split(',')[3];

                var markerColor = gender === 'Male' ? 'blue' : 'pink';

                functionMap(latitude, longitude, map, name, markerColor);

            });

        }

        function functionMap(latitude, longitude, map, name, markerColor) {
            var marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(latitude),
                    lng: parseFloat(longitude),
                },
                map: map,
                title: name,
                icon: {
                    url: 'http://maps.google.com/mapfiles/ms/icons/' + markerColor + '-dot.png'
                }
            });
            // Add an info window to display person's name when marker is clicked
            var infowindow = new google.maps.InfoWindow({
                content: name
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        }

        document.getElementById('filter-gender').addEventListener('change', function() {
            var people = @json($people);
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 0,
                    lng: 0
                },
                zoom: 2
            });

            var selectedGender = this.value;
            if (selectedGender == "Male") {

                people.forEach(function(person) {
                    var latitude = person.split(',')[4];
                    var longitude = person.split(',')[5];
                    var first_name = person.split(',')[1];
                    var last_name = person.split(',')[2];
                    var name = first_name + " " + last_name;
                    var gender = person.split(',')[3];

                    if (gender == "Male") {
                        var markerColor = "blue";
                        functionMap(latitude, longitude, map, name, markerColor);
                    }

                });
            } else if (selectedGender == "Female") {
                people.forEach(function(person) {
                    var latitude = person.split(',')[4];
                    var longitude = person.split(',')[5];
                    var first_name = person.split(',')[1];
                    var last_name = person.split(',')[2];
                    var name = first_name + " " + last_name;
                    var gender = person.split(',')[3];

                    if (gender == "Female") {
                        var markerColor = "pink";
                        functionMap(latitude, longitude, map, name, markerColor);
                    }
                });
            } else {

            }
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzLIIRDYCMtLLtQQwONfepkBEcYJKEX9w&callback=initMap" async defer></script>
    @endsection