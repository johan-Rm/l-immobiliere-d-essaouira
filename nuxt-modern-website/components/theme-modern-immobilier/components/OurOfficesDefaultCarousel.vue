<template>
<div class="our-offices-default-carousel m-carousel-1 m-l100">
  <div class="owl-carousel home-carousel-1 owl-btn-vertical-center">
    <client-only>
    <div
        v-for="(slide, index) in data"
        :key="index"
        class="item"
        data-dot="<button role='button' class='owl-dot ooo'></button>"
    >
        <div class="ow-img wt-img-effect zoom-slow lz-loading ratio-container unknown-ratio-container">
            <a href="javascript:void(0);">
            <img

            :width="$getImageSizeByFilterSets('width', getFormat('mobile'))"
            :height="$getImageSizeByFilterSets('height', getFormat('mobile'))"
            :data-src="getImagePath(slide.image, 'mobile')"
            :alt="slide.image.alt"
            class="mobile lazyload" />
              <img

              :width="$getImageSizeByFilterSets('width', getFormat('tablet'))"
              :height="$getImageSizeByFilterSets('height', getFormat('tablet'))"
              :data-src="getImagePath(slide.image, 'tablet')"
              :alt="slide.image.alt"
              class="tablet lazyload" />
              <img

              :width="$getImageSizeByFilterSets('width', getFormat('desktop'))"
              :height="$getImageSizeByFilterSets('height', getFormat('desktop'))"
              :data-src="getImagePath(slide.image, 'desktop')"
              :alt="slide.image.alt"
              class="desktop lazyload" />
            </a>
        </div>
    </div>
    </client-only>
  </div>
</div>
</template>
<script>
import { initHomeCarousel1 } from '~/plugins/custom_transform_to_export.js'
export default {
  name:"OurOfficesDefaultCarousel",
  props: {
      data: {
          type: Array
      }
  },
  mounted () {
      this.$nextTick(function(){ initHomeCarousel1() }.bind(this))
  },
  methods: {
    getFormat: function (device) {
      let format = 'carousel'
      if('tablet' == device) {
        format = 'grid_nostamp'
      } else if('mobile' == device) {
        format = 'carousel_small'
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
.our-offices-default-carousel .ratio-container:after {
    /* ratio = calc(345 / 555 * 100%) */
    padding-bottom: 62.162%;
}

.our-offices-default-carousel .lz-loading a {
  z-index: 10;
}


.our-offices-default-carousel img.desktop {
    display: block;
}

.our-offices-default-carousel img.tablet,
.our-offices-default-carousel img.mobile  {
    display: none;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (min-width: 768px) and (max-width: 991.98px) {
  .our-offices-default-carousel  img.tablet {
      display: block;
  }

  .our-offices-default-carousel  img.desktop,
  .our-offices-default-carousel  img.mobile {
      display: none;
  }

  .our-offices-default-carousel .ratio-container:after {
      /* ratio = calc(385.4 / 620 * 100%) */
      padding-bottom: 62.16129%;
  }
}

@media (max-width: 767.98px) {
  .our-offices-default-carousel img.mobile {
    display: block;
  }
}

</style>
