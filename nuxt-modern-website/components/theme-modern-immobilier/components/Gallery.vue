<template>
    <div class="gallery-blog blog-post wt-post-media clearfix m-b30">
        <ul class="grid-post">
            <li 
            v-for="(value,index) in data.imageGalleries"
            :key="index"
             >
                <div class="portfolio-item zoom-slow lz-loading ratio-container unknown-ratio-container">
                    <!-- <img
                    :width="$getImageSizeByFilterSets('width', 'grid_nostamp')" 
                    :height="$getImageSizeByFilterSets('height','grid_nostamp')"
                    class="img-responsive lazyload" 
                    :data-src="imagePath(value.image)" 
                    :alt="value.image.alt" /> -->

                     <img
                      width="800"
                      height="500"
                      :data-src="imagePath(value.image, 'mobile')"
                      :alt="value.image.alt"
                      class="img-responsive img-responsive-vertical mobile lazyload"
                      />
                      <img
                      width="800"
                      height="500"
                      :data-src="imagePath(value.image, 'desktop')"
                      :alt="value.image.alt"
                      class="img-responsive img-responsive-horizontal desktop lazyload"
                      />
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name:'Gallery',
    props: {
        data: {
          type: Object
        },
        params: {
          type: Object
        }
    },
    data () {
        return {
            zoom: ' ',
            isMobile: false
        }
    },
    methods: {
        getFormat: function(device) {
            let format = 'grid_nostamp'
            if('mobile' == device) {
              format = 'grid_nostamp'
            }

            return format
        },
        imagePath: function (image, device) {

            let format = this.getFormat(device)
            let filename = image.filename
            if(!this.$device.isMacOS && !this.$device.iOS) {
              filename = filename.substr(0, filename.lastIndexOf('.'))
              filename = filename + '.webp'
            }

            if (null !== image) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
            }

            return null

         
        }
    }
}
</script>
<style scoped>
    .gallery-blog .ratio-container:after {
      /* ratio = calc(500 / 800 * 100%) */
      padding-bottom: 62.5%;
    }

    .portfolio-item img {
        width: 100%;
    }
</style>
