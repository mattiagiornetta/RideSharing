<head>
    <style>
        #map {
            height: 500px;
        }
    </style>
        <link 
            rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script 
            src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/@mapbox/polyline@1.2.1/src/polyline.min.js"></script>
    </head>

<body>
    <div id="map"></div>
</body>

<script>
    var a = [9.123773,45.574028];
    var b = [9.160191,45.593082];
    var endpoint = "http://localhost/CarPooling/calcRoute.php?point_ax=" + a[1] + "&point_ay=" + a[0] + "&point_bx=" + b[1] + "&point_by=" + b[0];
    
    var map = L.map('map').setView([46.337746, 9.928791], 13);

    fetch(endpoint)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var route = data.paths[0].points; 
            var latlngs = polyline.decode(route);

            var path = L.polyline(latlngs, { color: 'blue' }).addTo(map);
            map.fitBounds(path.getBounds());
        })
        .catch(error => {
            console.error('Error:', error);
        });
</script>
