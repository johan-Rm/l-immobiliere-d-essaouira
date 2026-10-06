<template>
  <div class="m-about-us-carousel m-about m-l50 m-r50">
    <div class="owl-carousel about-us-carousel owl-btn-bottom-right">
      <client-only>
        <div
          v-for="(value, index) in carousel"
          :key="index"
          class="item"
        >
          <div class="ow-img wt-img-effect zoom-slow lz-loading ratio-container unknown-ratio-container">
            <a href="javascript:void(0);">
              <img
                :width="$getImageSizeByFilterSets('width', getFormat('mobile'))"
                :height="$getImageSizeByFilterSets('height', getFormat('mobile'))"
                :data-src="getImagePath(value.image, 'mobile')"
                :alt="value.image.alt"
                class="mobile lazyload"
                :class="[ isMobile ? '': 'hidden' ]"
              >
              <img
                :width="$getImageSizeByFilterSets('width', getFormat('desktop'))"
                :height="$getImageSizeByFilterSets('height', getFormat('desktop'))"
                :data-src="getImagePath(value.image, 'desktop')"
                :alt="value.image.alt"
                class="desktop lazyload"
                :class="[ isMobile ? 'hidden': '' ]"
              >
            </a>
          </div>
        </div>
      </client-only>
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'
import { initAboutusCarousel } from '~/plugins/custom_transform_to_export.js'
export default {
    name: 'AboutUS',
    computed: {
        ...mapState({
            carousel: state => state.about_us.item.galleryVertical.imageGalleries
        })
    },
    data () {
        return {
            isMobile: false
         }
    },
    mounted () {
        if(this.$device.isMobile) {
          this.isMobile = true
        }
        this.$nextTick(function(){ initAboutusCarousel() }.bind(this))
    },
    methods: {
      getFormat: function(device) {
        let format = 'vertical_large_nostamp'
        if('mobile' == device) {
          format = 'vertical_nostamp'
        }

        return format
      },
      getImagePath: function (image, device) {
        if(null !== image) {
          let format = this.getFormat(device)
          let filename = image.filename
          if(!this.$device.isMacOS && !this.$device.iOS) {
            filename = filename.substr(0, filename.lastIndexOf('.'))
            filename = filename + '.webp'
          }

          return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
        }

        return null
      }
    }
}
</script>
<style scoped>
.m-about-us-carousel .ratio-container:after {
    /* ratio = calc(455 / 724 * 100%) */
    padding-bottom: 159.1208%;
}
.m-about-us-carousel .lz-loading a {
  z-index: 10;
}


.m-about-us-carousel img.desktop {
    display: block;
}

.m-about-us-carousel img.mobile {
    display: none;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/

@media (min-width: 576px) and (max-width: 767.98px) {
  .m-about-us-carousel {
    width: 50%;
    height:50%;
  }

  .m-about-us-carousel .ratio-container:after {
    /* ratio = calc(445.533 / 280 * 100%) */
    padding-bottom: 153%;
  }
}

@media (max-width: 767.98px) {

  .m-about-us-carousel .ratio-container:after {
    /* ratio = calc(445.533 / 280 * 100%) */
    padding-bottom: 153%;
  }

  .m-about-us-carousel img.mobile {
        display: block;
  }

  .m-about-us-carousel img.desktop {
      display: none;
  }

}
</style>
