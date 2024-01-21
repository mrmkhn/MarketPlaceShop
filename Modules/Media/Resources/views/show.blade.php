<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ads</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>

    <!-- Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>

    <style>
        #mapid {
            height: 280px;
        }
    </style>
</head>
<body class="antialiased">
<div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    <div class="row justify-content-center align-item-center">
        <div class="col-6">
            <div id="mapid"></div>
            {{--            @foreach($map as $item)--}}
            <div>
                <p id="lat">{{$map['lat']}}</p>
                <p id="lng">{{$map['lng']}}</p>
            </div>
            {{--            @endforeach--}}
        </div>
    </div>

    <div class="row justify-content-center align-item-center">
        <div class="col-6 mt-5">
            rest of information...
        </div>
    </div>
</div>

<script>
    var lat = document.getElementById("lat").innerHTML;
    var long = document.getElementById("lng").innerHTML;

    if (lat != null || lat != "") {
        var mymap = L.map('mapid').setView([lat, long], 13);

        L.marker([lat, long]).addTo(mymap);
    }else{
        var mymap = L.map('mapid').setView([51.505, -0.09], 13);
    }

    var accessToken = 'pk.eyJ1IjoibWlsYWRjbGljayIsImEiOiJja3JtNmRmYjYwOHQ1Mm5ycTBoOTFraW9tIn0.j47CLuc5OhKzSgL8RsolsA';

    // create Official Account in mapbox and get accessToken
    // mapbox فقط یک انتخاب هست و میتوانیم از سرویس هایی دیگر نیز استفاده کنیم
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: accessToken
    }).addTo(mymap);

</script>
</body>
</html>
