$(document).ready(function () {

    let height = $(window).height();
    $('#map').height(height);

    let map = L.map('map', {

        fullscreenControl: true,
        fullscreenControl: {
            pseudoFullscreen: false  
        },
        title: 'Pantalla completa',
        titleCancel: 'Salir pantalla completa'

    });

    //L.Browser.ie = true;


    //OpenStreetMaps
    let osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);


    L.geoJSON(provinces, {

        style: function () {
            return { color: '#8D6F6B' };

        }
    }).addTo(map);

    let spaces = L.geoJSON(dataSpaces, {
       
        onEachFeature: function (feature, layer) {
           
            layer.bindPopup('<p>' + feature.properties.espacio_nombre + '</p>');
            layer.on('click', function (e) {
                let singposting; // señalización
                if (e.target.feature.properties.espacio_senalizacion_externa == 0) {
                    singposting = 'No';
                } else {
                    singposting = 'Sí';
                }

                $('.leaflet-sidebar-pane-title').html(e.target.feature.properties.espacio_nombre);
                $('#info-text').html('<p><strong>Nombre: </strong>' + e.target.feature.properties.espacio_nombre + '</p>' + '<p><strong>Tipo: </strong>' + e.target.feature.properties.figura_nombre + '</p>' +
                    '<p><strong>Señalización externa: </strong>' + singposting + '</p>' +
                    '<div class="row justify-content-center mt-3"><input type="button" id="btnAdd" class="mr-2 btn btn-add-delete" title="Añadir a favoritos" value="Añadir favorito"></div>');
                $('.leaflet-sidebar').removeClass('collapsed');
                $('.leaflet-sidebar-content > .leaflet-sidebar-pane:first-child').addClass('active');
                let data = {
                    'id_user': parseInt(user['id_user']),
                    'id_type_element': 2,
                    'id_element': e.target.feature.properties.atr_gr_id,
                    'name': e.target.feature.properties.espacio_nombre,
                    'web': ''
                };
                $('#btnAdd').on('click', function (e) {
                    $.ajax({
                        type: 'POST',
                        url: '/profile/addFavourite',
                        data: data,
                        success: function (response) {
                            if (response.favourite === 1 || response.favourite === 2) {
                                $(e.target).prop("disabled", true);
                                $('#info-text').append('<p class="notice m-4 py-2 text-center">Guardado correctamente</p>');
                            } else if (response.favourite === 0) {
                                $('#info-text').append('<p class="notice-error m-4 py-2 text-center">No se ha podido guardar</p>');
                            }
                        }
                    });
                });
            });
        },


        style: function () {
            return { color: "green" };
        }

    }).addTo(map);


    var routes = new L.geoJSON(dataRoutes, {

        onEachFeature: function (feature, layer) {
            layer.bindPopup('<p>' + feature.properties.equip_b_nombre + '</p>');
            layer.on('click', function (e) {

                let length, time, ciclabilidad, difficulty, accessMode; // longitud, tiempo, ciclabilidad, dificultad y modo acceso

                if (e.target.feature.properties.senda_longitud == 0.0 || e.target.feature.properties.senda_longitud == 0 || e.target.feature.properties.senda_longitud == null) {
                    length = "Dato no disponible";
                } else {
                    length = (e.target.feature.properties.senda_longitud) / 1000;
                }

                if (e.target.feature.properties.senda_tiempo_recorrido == 0 || e.target.feature.properties.senda_tiempo_recorrido == 0 || e.target.feature.properties.senda_tiempo_recorrido == null) {
                    time = "Dato no disponible";
                } else {
                    if (e.target.feature.properties.senda_tiempo_recorrido < 60) {
                        time = '0:' + e.target.feature.properties.senda_tiempo_recorrido;
                    } else if (e.target.feature.properties.senda_tiempo_recorrido == 60) {
                        time = '1:00';
                    } else {
                        let totalMinutes = e.target.feature.properties.senda_tiempo_recorrido;
                        let hour = totalMinutes / 60;
                        hour = hour.toString();
                        let hourSplit = hour.split('.');
                        let minute = e.target.feature.properties.senda_tiempo_recorrido % 60;
                        time = hourSplit[0] + ':' + minute;
                    }

                }

                if (e.target.feature.properties.senda_ciclabilidad == 0.0 || e.target.feature.properties.senda_ciclabilidad == 0 || e.target.feature.properties.senda_ciclabilidad == null) {
                    ciclabilidad = "Dato no disponible";
                } else {
                    ciclabilidad = e.target.feature.properties.senda_ciclabilidad;
                }

                if (e.target.feature.properties.senda_dificultad == 0 || e.target.feature.properties.senda_dificultad == 0.0 || e.target.feature.properties.senda_dificultad == null) {
                    difficulty = "Baja";
                } else if (e.target.feature.properties.senda_dificultad == 1) {
                    difficulty = "Media";
                } else {
                    difficulty = "Alta";
                }

                if (e.target.feature.properties.equip_b_acceso_modo == null) {
                    accessMode = "Dato no disponible";
                } else {
                    accessMode = e.target.feature.properties.equip_b_acceso_modo;
                }

                $('.leaflet-sidebar-pane-title').html(e.target.feature.properties.equip_b_nombre);
                $('#info-text').html('<p><strong>Nombre: </strong>' + e.target.feature.properties.equip_b_nombre + '</p>' + '<p><strong>Longitud (Km): </strong>' + length + '</p>' +
                    '<p><strong>Tiempo (h): </strong>' + time + '</p>' +
                    '<p><strong>Ciclabilidad (%): </strong>' + ciclabilidad + '</p>' +
                    '<p><strong>Dificultad: </strong>' + difficulty + '</p>' +
                    '<p><strong>Modo de acceso: </strong>' + accessMode + '</p>' +
                    '<div class="row justify-content-center mt-3"><input type="button" id="btnAdd" class="mr-2 btn btn-add-delete" title="Añadir a favoritos" value="Añadir favorito"></div>');
                $('.leaflet-sidebar').removeClass('collapsed');
                $('.leaflet-sidebar-content > .leaflet-sidebar-pane:first-child').addClass('active');
                let data = {
                    'id_user': parseInt(user['id_user']),
                    'id_type_element': 3,
                    'id_element': e.target.feature.properties.atr_gr_id,
                    'name': e.target.feature.properties.equip_b_nombre,
                    'web': e.target.feature.properties.web
                };
                $('#btnAdd').on('click', function (e) {
                    $.ajax({
                        type: 'POST',
                        url: '/profile/addFavourite',
                        data: data,
                        success: function (response) {
                            if (response.favourite === 1 || response.favourite === 2) {
                                $(e.target).prop("disabled", true);
                                $('#info-text').append('<p class="notice m-4 py-2 text-center">Guardado correctamente</p>');
                            } else if (response.favourite === 0) {
                                $('#info-text').append('<p class="notice-error m-4 py-2 text-center">No se ha podido guardar</p>');
                            }
                        }
                    });
                });




            });

        },
        style: function (feature) {
            if (feature.geometry.type === "LineString") {
                return { color: "#D04A1C" };
            } else {
                return { color: "orange" };
            }

        }

    }).addTo(map);

    /**
     * Función para poner en mayúcula la primera letra de cada palabra de un string
     */
    function capitalize(name) {
        var word = name.toLowerCase().split(' ');

        for (var i = 0; i < word.length; i++) {
            word[i] = word[i].charAt(0).toUpperCase() + word[i].substring(1);
        }
        return word.join(' ');
    }

    var myIcon = L.icon({
        iconUrl: 'images/accesible-icon-brand.svg',
        iconSize: [38, 95],
        iconAnchor: [22, 94],
        popupAnchor: [-3, -76],
        shadowSize: [68, 95],
        shadowAnchor: [22, 94]
    });

    let hotels = L.geoJSON(dataHotels, {

        onEachFeature: function (feature, layer, monument_icon) {
             
            let name = capitalize(feature.properties.Nombre);
            layer.bindPopup('<p>' + name + '</p>');
            layer.on('click', function (e) {
                let accesible, web;
                if (e.target.feature.properties.accesible_discapacidad == "") {
                    accesible = "No hay información disponible";
                } else {
                    accesible = e.target.feature.properties.accesible_discapacidad;
                }

                if (e.target.feature.properties.web == "") {
                    web = "No hay información disponible";
                } else {
                    web = e.target.feature.properties.web;
                }

                $('.leaflet-sidebar-pane-title').html(e.target.feature.properties.Nombre);
                $('#info-text').html('<p><strong>Nombre: </strong>' + e.target.feature.properties.Nombre + '</p>' + '<p><strong>Tipo: </strong>' + e.target.feature.properties.tipo + '</p>' +
                    '<p><strong>Categoría: </strong>' + e.target.feature.properties.categoria + '</p>' +
                    '<p><strong>Plazas: </strong>' + e.target.feature.properties.Plazas + '</p>' +
                    '<p><strong>Provincia: </strong>' + e.target.feature.properties.provincia + '</p>' +
                    '<p><strong>Localidad: </strong>' + e.target.feature.properties.localidad + '</p>' +
                    '<p><strong>Dirección: </strong>' + e.target.feature.properties.direccion + '</p>' +
                    '<p><strong>Teléfono: </strong>' + e.target.feature.properties.telefono1 + '</p>' +
                    '<p><strong>Web: </strong>' + web + '</p>' +
                    '<p><strong>Email: </strong>' + e.target.feature.properties.Email + '</p>' +
                    '<p><strong>Accesible personas discapacitadas: </strong>' + accesible + '</p>' +
                    '<div class="row justify-content-center mt-3"><input type="button" id="btnAdd" class="mr-2 btn btn-add-delete" title="Añadir a favoritos" value="Añadir favorito"></div>');
                $('.leaflet-sidebar').removeClass('collapsed');
                $('.leaflet-sidebar-content > .leaflet-sidebar-pane:first-child').addClass('active');
                let data = {
                    'id_user': parseInt(user['id_user']),
                    'id_type_element': 1,
                    'id_element': e.target.feature.properties.id_registro,
                    'name': e.target.feature.properties.Nombre,
                    'web': e.target.feature.properties.web
                };
                $('#btnAdd').on('click', function (e) {
                    $.ajax({
                        type: 'POST',
                        url: '/profile/addFavourite',
                        data: data,
                        success: function (response) {
                            if (response.favourite === 1 || response.favourite === 2) {
                                $(e.target).prop("disabled", true);
                                $('#info-text').append('<p class="notice m-4 py-2 text-center">Guardado correctamente</p>');
                            } else if (response.favourite === 0) {
                                $('#info-text').append('<p class="notice-error m-4 py-2 text-center">No se ha podido guardar</p>');
                            }
                        }
                    });
                });

            });
            
        },
        style: function(){
            return{Maker: "red"};
        }
    });


    let monuments = L.geoJSON(dataMonuments, {
        onEachFeature: function (feature, layer) {
            layer.bindPopup('<p>' + feature.properties.nombre + '</p>');
            layer.on('click', function (e) {
                $('.leaflet-sidebar-pane-title').html(e.target.feature.properties.nombre);
                $('#info-text').html('<p><strong>Nombre: </strong>' + e.target.feature.properties.nombre + '</p>' + '<p><strong>Tipo de monumento: </strong>' + e.target.feature.properties.tipoMonumento + '</p>' +
                    '<p><strong>Clasificación: </strong>' + e.target.feature.properties.clasificacion + '</p>' +
                    '<p><strong>Tipo de construcción: </strong>' + e.target.feature.properties.tipoConstruccion + '</p>' +
                    '<p><strong>Provincia: </strong>' + e.target.feature.properties.provincia + '</p>' +
                    '<p><strong>Municipio: </strong>' + e.target.feature.properties.municipio + '</p>' +
                    '<p><strong>Localidad: </strong>' + e.target.feature.properties.localidad + '</p>' +
                    '<p><strong>Estilo predominante: </strong>' + e.target.feature.properties.estiloPredominante + '</p>' +
                    '<p class"monument-paragraph"><strong>Descripción: </strong>' + e.target.feature.properties.Descripcion + '</p>' + '<div class="row justify-content-center mt-3"><input type="button" id="btnAdd" class="mr-2 btn btn-add-delete" title="Añadir a favoritos" value="Añadir favorito"></div>');
                $('.leaflet-sidebar').removeClass('collapsed');
                $('.leaflet-sidebar-content > .leaflet-sidebar-pane:first-child').addClass('active');
                let data = {
                    'id_user': parseInt(user['id_user']),
                    'id_type_element': 5,
                    'id_element': e.target.feature.properties.identificador,
                    'name': e.target.feature.properties.nombre,
                    'web': e.target.feature.properties.web
                };
                $('#btnAdd').on('click', function (e) {
                    $.ajax({
                        type: 'POST',
                        url: '/profile/addFavourite',
                        data: data,
                        success: function (response) {
                            if (response.favourite === 1 || response.favourite === 2) {
                                $(e.target).prop("disabled", true);
                                $('#info-text').append('<p class="notice m-4 py-2 text-center">Guardado correctamente</p>');
                            } else if (response.favourite === 0) {
                                $('#info-text').append('<p class="notice-error m-4 py-2 text-center">No se ha podido guardar</p>');
                            }
                        }
                    });
                });
            });
        }
    }).addTo(map);

    //MOSTRAR MIRADORES
    let lookouts = L.geoJSON(dataLookouts, {

        onEachFeature: function (feature, layer) {
            let observaciones;
            layer.bindPopup('<p>' + feature.properties.equip_b_nombre + '</p>');
            layer.on('click', function (e) {



                if (e.target.feature.properties.equip_a_observaciones == "") {
                    observaciones = "No hay información disponible";
                } else {
                    observaciones = e.target.feature.properties.equip_a_observaciones;
                }

                $('.leaflet-sidebar-pane-title').html(e.target.feature.properties.equip_b_nombre);
                $('#info-text').html('<p><strong>Nombre: </strong>' + e.target.feature.properties.equip_b_nombre + '</p>' +
                    '<p><strong>Modo de acceso: </strong>' + e.target.feature.properties.equip_b_acceso_modo + '</p>' +
                    '<p><strong>Observaciones: </strong>' + observaciones + '</p>' + '<div class="row justify-content-center mt-3"><input type="button" id="btnAdd" class=" btn mr-2 btn-add-delete" title="Añadir a favoritos" value="Añadir favorito"></div>');
                $('.leaflet-sidebar').removeClass('collapsed');
                $('.leaflet-sidebar-content > .leaflet-sidebar-pane:first-child').addClass('active');
                let data = {
                    'id_user': parseInt(user['id_user']),
                    'id_type_element': 4,
                    'id_element': e.target.feature.properties.atr_gr_id,
                    'name': e.target.feature.properties.equip_b_nombre,
                    'web': e.target.feature.properties.web
                };
                $('#btnAdd').on('click', function (e) {
                    console.log(data);
                    $.ajax({
                        type: 'POST',
                        url: '/profile/addFavourite',
                        data: data,
                        success: function (response) {
                            if (response.favourite === 1 || response.favourite === 2) {
                                $(e.target).prop("disabled", true);
                                $('#info-text').append('<p class="notice m-4 py-2 text-center">Guardado correctamente</p>');
                            } else if (response.favourite === 0) {
                                $('#info-text').append('<p class="notice-error m-4 py-2 text-center">No se ha podido guardar</p>');
                            }
                        }
                    });
                });
            });
        }
    }).addTo(map);

    /**
     * Encontrar la localización del usuario y añadirla en el mapa
     */
    function find_user_location(e) {
        L.circle(e.latlng, {
            radius: 1500,
            color: "#F60813",
            opacity: 0.8
        }).addTo(map);

    }

    /**
     * Muestra un mensaje de error si no encuentra la localización del usuario
    */
    function error_user_location(e) {
        alert("No es posible encontrar su ubicación. Es posible que tenga que activar la geolocalización.");
    }

    map.on('locationerror', error_user_location);
    map.on('locationfound', find_user_location);
    map.locate({ setView: true, maxZoom: 7 });

    L.control.sidebar('sidebar').addTo(map);

    //Control de capas
    L.control.layers({
        'OSM': osm,
    },
        {
            'Espacios': spaces,
            'Senda': routes,
            'Miradores': lookouts,
            'Monumentos': monuments,
            'Alojamientos': hotels,
    }).addTo(map);
});
