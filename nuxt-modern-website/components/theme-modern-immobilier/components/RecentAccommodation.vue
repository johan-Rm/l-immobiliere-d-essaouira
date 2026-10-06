<template>
<div class="widget recent-posts-entry recent-accommodation">
    <h2 class="widget-title  text-uppercase">{{ $t('Exclusivités') }} </h2>
    <div class="section-content">
    	<div
            v-for="(value, index) in data"
            :key="index"
            class="widget-post-bx"
        >
            <nuxt-link
                :to="getLocalizedRoute({
                    name: getRoute(value.nature)
                    , params: {
                        category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                        ,slug : $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                    }
                })">
                <div class="widget-post clearfix">
                    <div class="wt-post-media _lz-loading _ratio-container _unknown-ratio-container">
                        <img
                         :width="$getImageSizeByFilterSets('width', 'mini_thumbnail')" 
                        :height="$getImageSizeByFilterSets('height','mini_thumbnail')" 
                        :src="imagePath(value.primaryImage)" 
                        :alt="imageName(value.primaryImage)" 
                        class="_lazyload"/>
                    </div>
                    <div class="wt-post-info">
                        <div class="wt-post-header">
                            <h6 class="post-title"> {{ value.type.name }} </h6>
                        </div>
                        <div class="wt-post-meta">
                            <ul>
                                <li v-if="value.price > 0" class="post-author"><span class="amount">{{ toCurrencyString(value.price) }}</span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nuxt-link>
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    name: 'RecentAccommodation',
    computed: {
        ...mapState({
            data: state => state.footer.recent_accommodations
        })
    },
     methods: {
        getRoute(nature) {
            if('Location' == nature) {

                return 'renting-category-slug'
            }

            return 'selling-category-slug'
        }, 
        imageName: function (image) {
            if(null !== image) {
                return image.alt
            }
            
            return null
        },
        imagePath: function (image) {
            if(null !== image) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'mini_thumbnail' + process.env.PATH_DEFAULT_MEDIA + image.filename
            }
            
            return null
        },
        toCurrencyString(number){
            return number.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' })
        }
    }
}
</script>

<style lang="scss" scoped>
.recent-accommodation .ratio-container:after {
    /* ratio = calc(40 / 65 * 100%) */
    padding-bottom: 61.53%;
}
</style>