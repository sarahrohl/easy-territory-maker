var Map = (function(document, window, $) {
    var Construct = function (me, mini) {
        var options = {
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326"),
                units: "m",
                numZoomLevels: 20
            },
            map = this.map = new OpenLayers.Map(me.attr('id'), options),
            mapnik = new OpenLayers.Layer.OSM("OpenStreetMap"),
            gmap = new OpenLayers.Layer.Google("Google", {
                sphericalMercator: true
            }),
            ghyb = new OpenLayers.Layer.Google("Google Hybrid",{
                sphericalMercator: true,
                type: G_HYBRID_MAP
            }),
            bing = new OpenLayers.Layer.Bing({
                sphericalMercator: true
            }),
            wms = new OpenLayers.Layer.WMS("World Map"),
            territory = new OpenLayers.Layer.Vector("KML", {
                projection: map.displayProjection,
                strategies: [new OpenLayers.Strategy.Fixed()],
                protocol: new OpenLayers.Protocol.HTTP({
                    url: "kmlFolder.php?map=" + $('#mapId').val() + (mini ? '&mini' : '') + '&locality=' + $('#locality').val() ,
                    format: new OpenLayers.Format.KML({
                        extractStyles: true,
                        extractAttributes: true
                    })
                })
            });

        if (!mini) {
            territory.events.register("loadend", territory, function (e) {
                map.zoomToExtent(territory.getDataExtent());

                if ($('#locality').val() == "Apartment") {
                    me.find('div.baseLayersDiv input[value="Google Hybrid"]').next().click();
                } else {
                    me.find('div.baseLayersDiv input[value="Bing"]').next().click();
                }
            });
        } else {
            territory.events.register("loadend", territory, function (e) {
                map.zoomToExtent(territory.getDataExtent());
                map.zoomTo(14);
                map.updateSize();

                me.find('div.baseLayersDiv input[value="Google"]').next().click();
            });
        }

        map.addLayers([mapnik, gmap, ghyb, bing, wms, territory]);

        select = new OpenLayers.Control.SelectFeature(territory);

        map.addControl(select);

        var layerSwitch = new OpenLayers.Control.LayerSwitcher();
        map.addControl(layerSwitch);
    };

    return Construct;
})(document, window, jQuery);