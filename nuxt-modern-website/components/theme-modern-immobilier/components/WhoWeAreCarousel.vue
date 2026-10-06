<template>
<div class="who-we-are-carousel m-carousel-1">
    <div class="owl-carousel carousel-hover home-carousel-2 owl-btn-vertical-center">
        <div
            v-for="(slide, index) in carousel"
            :key="index"
            class="item"
        >
            <div class="wt-box">

                <div 
                    class="ratio-container unknown-ratio-container"
                    :class="{ 'lz-loading': !slide.image }"
                >
                    <div class="ow-img wt-img-effect _zoom-slow">
			<span class="label">{{ slide.image.name }}</span>
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
    </div>
    <div class="carousel-bg-img">
      <img
      :width="$getImageSizeByFilterSets('width', getFormat('desktop'))"
      :height="$getImageSizeByFilterSets('height', getFormat('desktop'))"
      :data-src="getImagePath(primaryImage, 'desktop')"
      :alt="primaryImage.alt"
      class="lazyload" />
    </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
import { initHomeCarousel2  } from '~/plugins/custom_transform_to_export.js'
export default {
  name: 'WhoWeAreCarousel',
  computed: {
      ...mapState({
          primaryImage: state => state.who_we_are.item.primaryImage,
          carousel: state => state.who_we_are.item.gallery.imageGalleries,
          filterSets: state => state.organization.filterSets
      })
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
      this.$nextTick(function(){ initHomeCarousel2() }.bind(this))
  },
  methods: {
    // getFormat: function (device) {
    //     // console.log(this.filterSe)
    //   let format = 'carousel'
    //   if('mobile' == device) {
    //     format = 'carousel_small'
    //   }
    //
    //   return format
    // },
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
<style lang="scss" scoped>
.who-we-are-carousel .ratio-container:after {
    /* ratio = calc(293 / 470 * 100%) */
    padding-bottom: 62.34%;
}

.who-we-are-carousel img.desktop {
    display: block;
}

.who-we-are-carousel img.mobile {
    display: none;
    text-align: center;
}

.who-we-are-carousel ow-img {
    z-index: 500;
}

.who-we-are-carousel .ow-img {
  .label {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: var(--color-primary);
    color: #fff;
    padding: 10px 0;
    text-transform: uppercase;
  }  
}

@media (min-width: 768px) and (max-width: 991.98px) {


    .who-we-are-carousel .ratio-container:after {
        /* ratio = calc(385.4 / 620 * 100%) */
        padding-bottom: 62.16129%;
    }

    .who-we-are-carousel img.mobile {
        display: block;
        text-align: middle;
    }
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (max-width: 767.98px) {
    .who-we-are-carousel img.mobile {
        display: block;
    }

    .who-we-are-carousel img.desktop {
        display: none;
    }
}

</style>
