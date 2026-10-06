<template >
    <div v-if="data" class="related-product widget widget_gallery mfp-gallery">
        <h4 class="widget-title text-uppercase">{{ $t('Biens similaires') }}</h4>
        <ul>
            <li
                v-for="(value,index) in data"
                :key="index" >
                <div class="wt-post-thum">
                    <a :href="imagePath(value.primaryImage)" :data-href="createLink(value)"  :title="$t('Voir plus de détails')" :subtitle="value.headline" class="mfp-link lz-loading ratio-container unknown-ratio-container" >
                        <img 
                        :width="$getImageSizeByFilterSets('width', 'grid')" 
                        :height="$getImageSizeByFilterSets('height','grid')"
                        :data-src="imagePath(value.primaryImage)" 
                        
                        class="lazyload" />
                    </a>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import { inintMagnific_popup } from '~/plugins/custom_transform_to_export.js'
export default {
    name:'RelatedProduct',
    props: {
        data: {
            type: Array
        },
        params: {
            type: Object
        }
    },
    mounted () {
      this.$nextTick(function(){ inintMagnific_popup() }.bind(this))
    },
    methods: {
        imagePath: function (image) {
          if(null !== image) {
            return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'grid' + process.env.PATH_DEFAULT_MEDIA + image.filename  
          }

          return null
        },
        createLink(accommodation) {
 
          const route = {
            name: this.params['route-category-slug-name']
            , params: {
                category: this.$tradLinkSlug(this.$store.state.i18n.currentLocale, accommodation.type.slug, 'accommodationType')
              , slug : this.$tradLinkSlug(this.$store.state.i18n.currentLocale, accommodation.slug, 'accommodation')
            }
          }

          const locale = this.$store.state.i18n.currentLocale

          const baseRoute = Object.assign({}, route, { name: `${route.name}-${locale}` })

          // // Resolve localized route
          const resolved = this.$router.resolve(baseRoute)
          let { href } = resolved

          return href
        }
      }
}
</script>
<style scoped>
.widget_gallery li {
    margin-right: 3px;
}
.listing-badges[data-v-5cbbdb20] {
    position: relative;
    }

.related-product .ratio-container:after {
    /* ratio = calc(54 / 86 * 100%) */
    padding-bottom: 62.7907%;
}
</style>