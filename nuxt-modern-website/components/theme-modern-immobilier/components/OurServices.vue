<template>
  <div class="our-services section-full bg-primary p-tb90">
    <div class="container">
      <div class="section-head text-left text-white">
        <h2 class="text-uppercase font-36">
          {{ $t('nos services') }}
        </h2>
        <h4 class="">
          {{ $t('Notre structure fonctionne en pôle immobilier couvrant tous vos besoins') }}...
        </h4>
        <div class="wt-separator-outer">
          <div class="wt-separator bg-white" />
        </div>
      </div>
      <div
        class="section-content circle-block-outer"
        data-toggle="tab-hover"
      >
        <div class="row">
          <div class="col-md-4 col-sm-12 m-b30">
            <div
              v-for="(value,index) in ourServices.slice(0, 2)"
              :key="index"
              class="wt-icon-box-wraper right p-a20 m-b50 text-white"
              :data-target="'#'+value.id"
              data-toggle="tab"
            >
              <nuxt-link
                :to="getLocalizedRoute({
                  name: 'news-category-slug'
                  , params: {
                    category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                    , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                  }
                })"
              >
                <div class="icon-content">
                  <h4 class="wt-tilte text-uppercase">
                    {{ $t(value.headline) }}
                  </h4>
                  <p>{{ $t(value.articleResume) }}</p>
                </div>
              </nuxt-link>
            </div>
          </div>
          <div class="col-md-4 col-sm-12 m-b30">
            <div class="circle-content-pic tab-content ">
              <div
                v-for="(value,index) in ourServices"
                :id="value.id"
                :key="index"
                class="tab-pane"
                :class="[index === 0 ? 'active' : '']"
              >
                <div class="wt-box">
                  <div class="wt-media text-primary m-b20 lz-loading ratio-container unknown-ratio-container">
                    <img
                      :width="$getImageSizeByFilterSets('width', getFormat('tablet'))"
                      :height="$getImageSizeByFilterSets('height', getFormat('tablet'))"
                      :src="getImagePath(value.primaryImage, 'tablet')"
                      :alt="value.primaryImage.alt"
                      class="lazyload tablet"
                      :class="[ checkTablet ? '': 'hidden' ]"
                    >
                    <img
                      :width="$getImageSizeByFilterSets('width', getFormat('desktop'))"
                      :height="$getImageSizeByFilterSets('height', getFormat('desktop'))"
                      :src="getImagePath(value.secondaryImage, 'desktop')"
                      :alt="value.secondaryImage.alt"
                      class="lazyload desktop"
                      :class="[ !checkTablet ? 'hidden': '' ]"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12 m-b30">
            <div
              v-for="(value,index) in ourServices.slice(2,4)"
              :key="index"
              class="wt-icon-box-wraper left p-a20 m-b50 text-white"
              :data-target="'#'+value.id"
              data-toggle="tab"
            >
              <nuxt-link
                :to="getLocalizedRoute({
                  name: 'news-category-slug'
                  , params: {
                    category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                    , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                  }
                })"
              >
                <div class="icon-content">
                  <h4 class="wt-tilte text-uppercase">
                    {{ $t(value.headline) }}
                  </h4>
                  <p> {{ $t(value.articleResume) }} </p>
                </div>
              </nuxt-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'
import { initHover_tab } from '~/plugins/custom_transform_to_export.js'
export default {
    name: 'OurServices',
    props: {
        params: {
          type: Object
        }
    },
    computed: {
        ...mapState({
            ourServices: state => state.our_services.list
        }),
        textLimit() {
          if(this.$device.isMobile
            || (this.$device.isTablet && window.innerWidth > 768)
          ) {

              return 180
          }

          return 380
       }
    },
    mounted() {
        this.$nextTick(function(){ initHover_tab() }.bind(this))
    },
    created() {
        this.$store.dispatch('our_services/getListBy', { 'tags.slug': 'nos-services' })
    },
    methods: {
      checkTablet(){
          if(this.$device.isTablet && window.innerWidth < 769 
            || this.$device.isMobile && window.innerWidth > 769) {

             return true
          }

          return false
      },
      getFormat: function(device) {
        let format = 'vertical_nostamp'
            if('tablet' == device) {
              format = 'grid_nostamp'
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
      },
      defineText(text) {
         // return this.$options.filters.truncate(this.$i18n.t(text), 300)
      }
    }
}
</script>
<style scoped>
.our-services .circle-block-outer .wt-media {
    max-height: 552px;
    overflow: hidden;
}

.our-services .circle-block-outer .wt-icon-box-wraper {
    transition: all 0.5s ease-out;
    border: 2px solid #fff;
    min-height: 250px;
}

.our-services .icon-content p {
    word-break: break-word;
}

.our-services .ratio-container:after {
    /* ratio = calc(560 / 360 * 100%) */
    padding-bottom: 152.5555%;
}

.our-services img.desktop {
    display: block;
}

.our-services img.tablet {
    display: none;
}



@media only screen and (max-width:380px) {
  p {
      line-height: 19px;
  }
}
@media only screen and (max-width:310px) {
  p {
      line-height: 16px;
  }
}


/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) {
    .circle-content-pic img {
      width: 560px;
    }
}

/* Small devices (landscape phones, less than 768px) */
@media (max-width: 767.98px) {
    .our-services .section-content .wt-icon-box-wraper {
      text-align: justify;
    }
}

/* Medium devices (tablets, less than 992px) */
@media (max-width: 991.98px) {

}

/* Large devices (desktops, less than 1200px) */
@media (max-width: 1199.98px) {

}

@media (min-width: 768px) and (max-width: 991.98px), {
    .our-services img.tablet {
        display: block;
    }

    .our-services img.desktop {
        display: none;
    }

    .our-services .ratio-container:after {
        /* ratio = calc(450 / 720 * 100%) */
        padding-bottom: 62.5%;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
 .our-services img.tablet {
        display: none;
  }

  .our-services img.desktop {
      display: block;
  }

  .our-services .ratio-container:after {
    /* ratio = calc(450 / 720 * 100%) */
    padding-bottom: 62.5%;
  }
}
</style>
