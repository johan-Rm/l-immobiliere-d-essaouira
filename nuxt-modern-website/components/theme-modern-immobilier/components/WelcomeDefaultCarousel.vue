<template>
<div class="welcome-default-carousel m-carousel-1 m-l100 ratio-container-parent">
  <div class="owl-carousel home-carousel-1 owl-btn-vertical-center ">
    <div
        v-for="(slide, index) in data"
        :key="index"
        class="item lz-loading ratio-container unknown-ratio-container"
        data-dot="<button role='button' class='owl-dot'></button>"
    >
        <div class="ow-img wt-img-effect zoom-slow">
            <a href="javascript:void(0);">
                <img
                v-if="isMobile"
                :width="$getImageSizeByFilterSets('width', getFormat('mobile'))"
                :height="$getImageSizeByFilterSets('height', getFormat('mobile'))"
                :data-src="getImagePath(slide.image, 'mobile')"
                :alt="slide.image.alt"
                class="mobile lazyload" />
                <img
                v-else-if="isTablet"
                :width="$getImageSizeByFilterSets('width', getFormat('tablet'))"
                :height="$getImageSizeByFilterSets('height', getFormat('tablet'))"
                :data-src="getImagePath(slide.image, 'tablet')"
                :alt="slide.image.alt"
                class="tablet lazyload" />
                <img
                v-else
                :width="$getImageSizeByFilterSets('width', getFormat('desktop'))"
                :height="$getImageSizeByFilterSets('height', getFormat('desktop'))"
                :data-src="getImagePath(slide.image, 'desktop')"
                :alt="slide.image.alt"
                class="desktop lazyload" />
            </a>
        </div>
    </div>
  </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
import { initHomeCarousel1 } from '~/plugins/custom_transform_to_export.js'
export default {
    name:"WelcomeDefaultCarousel",
    computed: {
        ...mapState({
            data: state => state.welcome.item.gallery.imageGalleries,
        }),
    },
    data () {
        return {
            isMobile: false,
            isTablet: false
         }
    },
    mounted () {
        if(this.$device.isMobile) {
          this.isMobile = true
        }
        if(this.$device.isTablet) {
          this.isTablet = true
        }
        this.$nextTick(function(){ initHomeCarousel1() }.bind(this))
    },
    methods: {
        getFormat: function (device) {
            let format = 'carousel'
            if('tablet' == device) {
              format = 'grid_nostamp'
            }
            else if(
              'mobile' == device
              && (typeof window !== 'undefined' && window.innerWidth > 575.98)
            ) {
              format = 'grid_nostamp'
            }
            else if(
              'mobile' == device
              && (typeof window !== 'undefined' && window.innerWidth <= 575.98)
            ) {
              format = 'carousel_medium'
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
.welcome-default-carousel .ratio-container:after {
    /* ratio = calc(345 / 555 * 100%) */
    padding-bottom: 62.162%;
}

.welcome-default-carousel.ratio-container-parent:after {
    /* ratio = calc(343 / 552 * 100%) */
    padding-bottom: 52.92%;
}

.welcome-default-carousel .lz-loading .ow-img.wt-img-effect {
  z-index: 10;
}

.welcome-default-carousel img.desktop {
    display: block;
}

.welcome-default-carousel img.tablet,
.welcome-default-carousel img.mobile {
  display: none;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (min-width: 768px) and (max-width: 991.98px) {
    .welcome-default-carousel img.tablet, .welcome-default-carousel img.mobile {
        display: block;
    }

    .welcome-default-carousel img.desktop
     {
        display: none;
    }

    .welcome-default-carousel .ratio-container:after {
        /* ratio = calc(385.4 / 620 * 100%) */
        padding-bottom: 62.16129%;
    }
}

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) and (max-width: 767.98px) {

}

/* Small devices (landscape phones, less than 768px) */
@media (max-width: 767.98px) {
    .welcome-default-carousel img.mobile {
        display: block;
    }

    .welcome-default-carousel img.desktop,
    .welcome-default-carousel img.tablet {
        display: none;
    }
}
</style>
