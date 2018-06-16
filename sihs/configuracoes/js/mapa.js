function initialize() {

  // Exibir mapa;
  var myLatlng = new google.maps.LatLng(-14.794540224324848, -39.27917088657762);
  var mapOptions = {
    zoom: 18,
    center: myLatlng,
    panControl: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.SATELLITE, 'map_style']
    }
  }


  // Parâmetros do texto que será exibido no clique;
  var contentString = '<p>Rua Almirante Tamandaré, 502, Centro, Itabuna/Ba</p>';
  var infowindow = new google.maps.InfoWindow({
      content: contentString,
      maxWidth: 700
  });


  // Exibir o mapa na div #mapa;
  var map = new google.maps.Map(document.getElementById("mapa"), mapOptions);


  // Marcador personalizado;
  var image = 'https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678111-map-marker-32.png';
  var marcadorPersonalizado = new google.maps.Marker({
      position: myLatlng,
      map: map,
      icon: image,
      animation: google.maps.Animation.DROP
  });


  // Exibir texto ao clicar no ícone;
  google.maps.event.addListener(marcadorPersonalizado, 'click', function() {
    infowindow.open(map,marcadorPersonalizado);
  });
}


// Função para carregamento assíncrono
function loadScript() {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDVEnbCxTVJXm0PC6RnuhGwOFr6Pke8J2o&sensor=true&callback=initialize";
  document.body.appendChild(script);
}

window.onload = loadScript;