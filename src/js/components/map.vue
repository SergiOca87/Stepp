<template>
  <!-- clean it up, put styles in css -->
  <div class="map-wrapper">
    <!-- map canvas -->
    <div id="map-canvas"></div>

    <!-- map tooltip -->
    <div id="map-tooltip" ref="tooltip">
      <div v-if="marker">
        <figure>
          <img
            :src="marker.property.img != null ? marker.property.img.thumbnail : 'https://placehold.it/150x150'"
            :alt="marker.property.name"
          >
        </figure>
        <div class="item-meta">
          <div class="upper-info">
            <h3 class="size-7">{{ marker.property.name }}</h3>
            <div
              class="location size-8 opacity-30 secondary-font"
            >{{ marker.property.location.address }}, {{ marker.property.location.city }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// comment out to disable markerclusterer
import MarkerClusterer from "../markerclusterer.js";

window.GoogleMapsInit = () => {
  eventHub.$emit("GoogleMapLoaded");
};

const map_styles = [
  {
    featureType: "landscape.man_made",
    elementType: "all",
    stylers: [{ color: "#faf5ed" }, { lightness: "0" }, { gamma: "1" }]
  },
  {
    featureType: "poi.park",
    elementType: "geometry.fill",
    stylers: [{ color: "#bae5a6" }]
  },
  {
    featureType: "road",
    elementType: "all",
    stylers: [{ weight: "1.00" }, { gamma: "1.8" }, { saturation: "0" }]
  },
  {
    featureType: "road",
    elementType: "geometry.fill",
    stylers: [{ hue: "#ffb200" }]
  },
  {
    featureType: "road.arterial",
    elementType: "geometry.fill",
    stylers: [{ lightness: "0" }, { gamma: "1" }]
  },
  {
    featureType: "transit.station.airport",
    elementType: "all",
    stylers: [
      { hue: "#b000ff" },
      { saturation: "23" },
      { lightness: "-4" },
      { gamma: "0.80" }
    ]
  },
  { featureType: "water", elementType: "all", stylers: [{ color: "#a0daf2" }] }
];
var markerCluster = null;

export default {
  props: ["properties"],
  data: function() {
    return {
      map: null,
      marker: null,
      markers: [],
      marker_clusterer: null,
      tooltip_hovered: false
    };
  },
  created: function() {
    // if gmaps is aleady loaded, stop here
    if (this.$parent.gmaps === true) return;

    // add map script
    var gmap = document.createElement("script");
    gmap.setAttribute(
      "src",
      "https://maps.googleapis.com/maps/api/js?key=" +
        bootstrap.gmaps_key +
        "&callback=GoogleMapsInit"
    );
    gmap.setAttribute("type", "text/javascript");
    gmap.setAttribute("async", true);
    gmap.setAttribute("defer", true);
    document.body.appendChild(gmap);
  },
  watch: {
    properties: function() {
      this.maybeRender();
    }
  },
  mounted: function() {
    var vm = this;
    eventHub.$on("GoogleMapLoaded", function() {
      vm.$parent.gmaps = true;
      vm.maybeRender();
    });

    eventHub.$on("layoutSet", () => vm.maybeRender());

    // highlight markers on list hover
    eventHub.$on("highlightMarker", idx => vm.highlightMarker(idx));
    eventHub.$on("dehighlightMarker", idx => vm.dehighlightMarker(idx));
  },
  filters: {
    propertyType: function(val) {
      var output = [];

      // loop
      if (val) {
        val.forEach(function(v) {
          output.push(v.name);
        });
      }

      return output.join(", ");
    }
  },
  methods: {
    maybeRender: function(where) {
      // if parent layout not map or google not loaded, stop
      if (this.$parent.layout != "map" || this.$parent.gmaps !== true) return;

      //maybe render
      if (this.map == null) {
        // if map is not created, go ahead and create it first
        this.createMap();
        this.refreshMap();
      } else {
        this.refreshMap();
      }
    },
    createMap: function() {
      var vm = this;
      // create bounds
      this.bounds = new google.maps.LatLngBounds();

      // init map
      this.map = new google.maps.Map(document.getElementById("map-canvas"), {
        zoom: 5,
        center: new google.maps.LatLng(39.305, -76.617),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        scrollwheel: true,
        disableDefaultUI: true,
        zoomControl: true,
        zoomControlOptions: {
          position: google.maps.ControlPosition.LEFT_TOP
        },
        // draggable: !("ontouchend" in document),
        styles: map_styles
      });

      document
        .querySelector("#map-tooltip")
        .addEventListener("mouseenter", () => {
          vm.tooltip_hovered = true;
        });

      document
        .querySelector("#map-tooltip")
        .addEventListener("mouseleave", () => {
          var f = debounce(() => {
            vm.tooltip_hovered = false;
            // make tooltip invisible
            document.querySelector("#map-tooltip").removeAttribute("class");
            // if marker not set, stop
            if (!vm.marker) return;
            // reset opacity
            vm.marker.setOptions({ opacity: 1 });
            // cleanup marker
            vm.marker = null;
          }, 200);
          // debounce marker out
          f();
        });

      // listen for map bounds change
      google.maps.event.addListener(this.map, "idle", function() {
        eventHub.$emit("mapBoundsChanged", vm.map.getBounds());
        vm.prevent_map_centre = true;
      });
      // set overlay
      this.overlay = new google.maps.OverlayView();
      this.overlay.draw = function() {};
      this.overlay.setMap(this.map);
    },
    refreshMap: function() {
      // remove all existing map markers
      if (this.markers.length) {
        for (var i = 0; i < this.markers.length; i++) {
          this.markers[i].setMap(null);
        }
      }

      // clear clustered markers
      if (this.marker_clusterer != null) {
        this.marker_clusterer.clearMarkers();
      }

      // clear markers
      this.markers = [];

      for (var i = 0; i < this.properties.length; i++) {
        this.addMarker(this.properties[i]);
      }

      if (typeof MarkerClusterer !== "undefined") {
        this.marker_clusterer = new MarkerClusterer(this.map, this.markers, {
          gridSize: 50,
          maxZoom: 15,
          styles: [
            {
              url: bootstrap.theme_uri + "/src/img/m.svg",
              width: 54,
              height: 54,
              textColor: "#FFF",
              textSize: 13
            }
          ]
        });
      }

      // center map to boundling box
      if (!this.prevent_map_centre) {
        this.centerMap();
      }
      this.prevent_map_centre = false;
    },
    addMarker: function(property) {
      // proceed if it is
      var vm = this;

      // stop if no lat or lng
      if (!property.location.lat || !property.location.lng) return false;

      // calculate position
      var position = new google.maps.LatLng(
        property.location.lat,
        property.location.lng
      );

      // add marker
      var len = this.markers.push(
        new google.maps.Marker({
          position: position,
          map: this.map,
          property: property,
          style: {},
          optimized: false,
          icon: {
            path:
              "M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z",
            scale: 1.2,
            fillColor: "#000",
            fillOpacity: 1,
            strokeColor: "#ffffff",
            strokeWeight: 2,
            anchor: new google.maps.Point(8, 8)
          }
        })
      );

      // extend bounds
      this.bounds.extend(position);

      // go to property on marker click
      this.markers[len - 1].addListener("click", function() {
        // redirect to property url on click
        window.location = vm.markers[len - 1].property.url;
      });

      // mouseover
      this.markers[len - 1].addListener("mouseover", function() {
        var f = debounce(() => {
          vm.marker = vm.markers[len - 1];
          vm.highlightMarker(len - 1, false);
          vm.highlightListItem(len - 1);
          vm.mapTooltipPosition();
        }, 200);

        // debounce function
        f();
      });

      // mouseout
      this.markers[len - 1].addListener("mouseout", function() {
        var f = debounce(() => {
          vm.dehighlightMarker(len - 1);
          vm.dehighlightListItem(len - 1);
          // if tooltip is hovered, stop
          if (vm.tooltip_hovered) return;
          // cleanup marker
          vm.marker = null;
          // make tooltip invisible
          document.querySelector("#map-tooltip").removeAttribute("class");
        }, 200);

        // debounce
        f();
      });
    },
    centerMap: function() {
      // don't zoom in too far on only one marker
      if (this.bounds.getNorthEast().equals(this.bounds.getSouthWest())) {
        var extendPoint1 = new google.maps.LatLng(
          this.bounds.getNorthEast().lat() + 0.01,
          this.bounds.getNorthEast().lng() + 0.01
        );
        var extendPoint2 = new google.maps.LatLng(
          this.bounds.getNorthEast().lat() - 0.01,
          this.bounds.getNorthEast().lng() - 0.01
        );
        this.bounds.extend(extendPoint1);
        this.bounds.extend(extendPoint2);
      }

      this.map.fitBounds(this.bounds);
    },
    mapTooltipPosition: function() {
      // accidentally there could be no marker (debounce)
      if (!this.marker) return;

      var vm = this;
      // get position
      var proj = this.overlay.getProjection();
      var pos = proj.fromLatLngToContainerPixel(this.marker.getPosition());

      var el = document.querySelector("#map-tooltip");
      var canvas = document.querySelector("#map-canvas");

      // remove class attribute
      el.removeAttribute("class");

      // set style attr
      el.style.left = pos.x + "px";
      el.style.top = pos.y + "px";

      if (canvas.offsetWidth - el.offsetLeft - el.offsetWidth < 10) {
        el.style.left = pos.x - el.offsetWidth + "px";
        el.classList.add("on-right");
      }

      // add visible class first to be able to calculate height
      el.classList.add("visible");

      window.setTimeout(function() {
        if (canvas.offsetHeight - el.offsetTop - el.offsetHeight < 10) {
          el.style.top = pos.y - el.offsetHeight + "px";
          el.classList.add("on-bottom");
        }
      }, 50);
      
    },
    highlightListItem: function(idx) {
      var parent = document.querySelector(".map-view");
      var children = parent.querySelectorAll(".property-item");

      // remove active class from all other children
      if (children.length) {
        children.forEach(child => {
          child.classList.remove("active");
        });
      }

      // add class active to hovered item
      if (typeof children[idx] != "undefined") {
        children[idx].classList.add("active");

        // scroll the parent
        var header = parent.querySelector(".header");
        var st = children[idx].offsetTop;
        if (typeof header != "undefined" && header != null) {
          st -= header.clientHeight;
        }
        parent.querySelector(".list-view").scrollTop = st;
      }
    },
    dehighlightListItem: function(idx) {
      var parent = document.querySelector(".list-view");
      var children = parent.querySelectorAll(".property-item");

      // remove active class from all other children
      if (children.length) {
        children.forEach(child => {
          child.classList.remove("active");
        });
      }
    },
    highlightMarker: function(idx, pan) {
      if (this.map && this.markers[idx]) {
        this.markers[idx].setIcon({
          path:
            "M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z",
          scale: 1.2,
          fillColor: "#D47348",
          fillOpacity: 1,
          strokeColor: "#ffffff",
          strokeWeight: 2,
          anchor: new google.maps.Point(8, 8)
        });
        this.markers[idx].setZIndex(100000);

        // don't pan if not requested
        if(typeof pan == 'undefined') this.map.panTo(this.markers[idx].getPosition());
      }
    },
    dehighlightMarker: function(idx) {
      if (this.map && this.markers[idx]) {
        this.markers[idx].setIcon({
          path:
            "M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z",
          scale: 1.2,
          fillColor: "#4B5E71",
          fillOpacity: 1,
          strokeColor: "#ffffff",
          strokeWeight: 2,
          anchor: new google.maps.Point(8, 8)
        });
        this.markers[idx].setZIndex(1);
      }
    }
  }
};
</script>
<style lang="scss">
.map-wrapper {
  position: relative;
}
#map-canvas {
  min-height: 240px;
}
#map-tooltip {
  position: absolute;
  z-index: 100000;
  visibility: hidden;
  transform: translate(20px, -20px);
  background-color: #fff;
  opacity: 0;
  &:after {
    top: 18px;
    left: -8px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-color: rgba(255, 255, 255, 0);
    border-right-color: #fff;
    border-width: 8px;
    margin-left: -8px;
  }
  &.on-right {
    transform: translate(-30px, -30px);
    &:after {
      right: -8px;
      left: auto;
      border-color: rgba(255, 255, 255, 0);
      border-left-color: #fff;
      margin-left: auto;
      margin-right: -8px;
    }
  }
  &.on-bottom {
    transform: translate(30px, 20px);
    &:after {
      top: auto;
      bottom: 18px;
    }
    &.on-right {
      transform: translate(-30px, 20px);
    }
  }
  &.visible {
    // transition-duration: .2s;
    visibility: visible;
    opacity: 1;
  }
}
</style>