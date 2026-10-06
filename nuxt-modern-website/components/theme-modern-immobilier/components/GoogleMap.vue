<template>
  <div style="position: relative;" class="m-tb40">
    <!-- <div class="placeDiv" style="background: #fff;position: absolute;  z-index: 1000;left: 10px;top: 10px;width: 300px;height: 150px;">
        <div class="placecard__container" style="margin:10px " >
          <div class="placecard__left" >
            <h4 class="wt-tilte text-uppercase" > {{ data.name }} </h4>
            <p class="placecard__info">{{ data.addresses[0].address }}<br/>
              {{ data.addresses[0].postcode }} {{ data.addresses[0].city }}, {{ data.addresses[0].country }}
            </p>
          </div>
          <div class="reviewlink">
            <a target="_BLANK" :href="reviewLink">
                {{ $t('Voir les avis') }}
            </a>
            <i class="fa fa-star"></i>
          </div>
        </div>
    </div>
    <gmap-map
      ref="gmap"
      :center="center"
      :zoom="14"
      style="position: relative; width:100%;  height: 400px;"
      :options="{
       zoomControl: true,
       mapTypeControl: false,
       scaleControl: false,
       streetViewControl: false,
       rotateControl: false,
       fullscreenControl: true,
       disableDefaultUi: false
     }"
    >

      <GmapMarker 
        :position="marker.position"
        :clickable="true"
        :draggable="true"
        :label="marker.label"
        :icon="icon"
        @click="center=marker.position"
      />

    </gmap-map> -->
<iframe :title="$t('L\'Immobilière d\'Essaouira')" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d38491.930907051894!2d-9.769361124257527!3d31.490136641834262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdad9a486d21cf41%3A0xe6d4967573947fe0!2sL&#39;Immobili%C3%A8re%20d&#39;Essaouira!5e0!3m2!1sfr!2sma!4v1575064891991!5m2!1sfr!2sma" frameborder="0" style="position: relative; width:100%;  height: 400px; border:0;" allowfullscreen=""></iframe>

  </div>
</template>
<script>
import { mapState } from 'vuex'
import ReviewLink from '~/components/theme-modern-immobilier/components/ReviewLink'
  export default {
    name: "GoogleMap",
    components:{
      ReviewLink
    },
    computed: {
        ...mapState({
            // data: state => state.organization.item,
        })
    },
    data() {

      const _marker =
        {
          name: "House of Aleida Greve",
          description: "description 1",
          date_build: "",
          position: { lat: 31.504318, lng: -9.757574 },
          label: { text: "L'Immobilière d'Essaouira", color: "#3e2723" },
          reviewlink: this.$store.state.footer.reviewLinks.desktop
        }

      return {

            icon: {
              url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            },
        //a default center for the map
        mapTypeId: 'roadmap',
        center: { lat: 31.504327, lng: -9.757570 },
        mapStyle: {},
            //     styles: [{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]
            // },
        map: null,
        infoContent: this.getInfoWindowContent(_marker),
        infoWindowPos: {
          lat: 31.504318, lng: -9.757574 
        },
        infoWinOpen: true,
        currentMidx: null,
        //optional: offset infowindow so it visually sits nicely on top of our marker
        infoOptions: {
          
        },
        marker:
          {
            name: _marker.name,
            description: _marker.description,
            date_build: _marker.date_build,
            position: _marker.position,
            label: _marker.label,
            reviewlink: _marker.reviewlink
          }
      }
    },
    methods: {
      getInfoWindowContent: function (marker) {
        return (`<div class="card">
          <div class="card-content">
            <div class="media">
              <div class="media-content">
                <p class="title is-4">${marker.name}</p>
              </div>
            </div>
            <div class="content">
              ${marker.description}
              <br>
              <time datetime="2016-1-1">${marker.date_build}</time>
              <center>
                <button type="button" class="col-md-12 reviewlink site-button text-uppercase letter-spacing-2 font-weight-800">
                    <i class="fa fa-star"></i>
                        <a target="_BLANK" href="${marker.reviewlink}">
                          voir les avis
                        </a>
                    <i class="fa fa-star"></i>
                </button>
            </center>
            </div>
          </div>
        </div>`)
      }
    }
  }
</script>
<style lang="scss" scoped>
.vue-map-container {
  height: 450px;
  //max-width: 992px;
  width:
   100%;
}
.reviewlink {
    color: #92acbe;
}
.reviewlink a {
    color: #92acbe;
    font-weight: bold;
}
</style>

