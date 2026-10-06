    <template>
        <div>
            <div class="wt-post-title ">
                <h2 class="post-title ">
                    <a href="javascript:void(0);" class="text-black font-20 letter-spacing-2 font-weight-600 text-capitalize">
                     {{ $t('Situation de la propriété') }} 
                    </a>
                </h2>
            </div>
            <GmapMap style="width: 100%;" :options="mapStyle" :center="center" :map-type-id="mapTypeId" :zoom="18">
            <GmapMarker v-for="(value, index) in markers"
            :key="index" :position="value.position" @click="center=value.position"/>
            </GmapMap>
        </div>
    </template>
    
    <script>
    export default {
        name: 'MyMap',
        data() {
            return {
                center: { 
                    lat: 31.504575,
                    lng: -9.757526 
                        },
                mapTypeId: "roadmap",
                markers: [
                    { position: { lat: 31.504327, lng: -9.757570 } }
                ],
                OurExperts: {},
                mapStyle: {
                    styles:[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]
                }
            }
        }
    }
    </script>

    <style lang="scss" scoped>
    .vue-map-container {
    height: 450px;
    width: 100%;
    }
    </style>
