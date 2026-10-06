<template>
<div class="widget recent-posts-entry  ">
    <h4 class="widget-title  text-uppercase"> {{ $t(' Derniers biens ') }} </h4>
    <div class="section-content">
    	<div
            v-for="(value, index) in data"
            :key="index"
            class="widget-post-bx"
        >
            <nuxt-link
                v-if="value.category"
                :to="getLocalizedRoute({
                    name: 'news-category-slug'
                    , params: {
                        category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                        , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                    }
                })">
                <div class="widget-post clearfix">
                    <div class="wt-post-media lz-loading ratio-container unknown-ratio-container">
                        <img
                        :width="$getImageSizeByFilterSets('width', 'mini_thumbnail')" 
                        :height="$getImageSizeByFilterSets('height','mini_thumbnail')" 
                        :data-src="imagePath(value.primaryImage)" 
                        :alt="value.primaryImage.alt" 
                        class="lazyload" />
                    </div>
                    <div class="wt-post-info">
                        <div class="wt-post-header">
                            <h6 class="post-title"> {{ value.alternativeHeadline }} </h6>
                        </div>
                       <!--  <div class="wt-post-meta">
                            <ul>
                                <li class="post-author"> {{ value.datePublished }} </li>
                            </ul>
                        </div> -->
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
    name: 'RecentPosts',
    computed: {
        ...mapState({
            data: state => state.footer.recent_posts
        })
    },
     methods: {
        imagePath: function (image) {
            if(null !== image) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'mini_thumbnail' + process.env.PATH_DEFAULT_MEDIA + image.filename
            }
            
            return null
        }
    }
}
</script>
