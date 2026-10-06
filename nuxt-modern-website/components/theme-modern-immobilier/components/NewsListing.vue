<template>
    <div class="section-full p-tb90">
        <div class="container">
            <div class="news-listing ">
                <div
                    v-for="(value, index) in data"
                    :key="index"
                    class="blog-post blog-md date-style-1 clearfix bdr-2 bdr-gray-light m-b60 bg-gray"
                >
                    <div class="wt-post-media wt-img-effect">
                        <a href="javascript:void(0);">
                            <img 
                             :width="$getImageSizeByFilterSets('width', 'grid')" 
                            :height="$getImageSizeByFilterSets('height','grid')"
                            :src="imagePath(value.primaryImage)" 
                            :alt="value.primaryImage.alt" 
                            class="lazyload__" />
                        </a>
                    </div>
                    <div class="wt-post-info p-tb30 text-black">
                        <div class="wt-post-title ">
                            <h2 class="post-title"><a href="javascript:void(0);" class="text-black font-20 letter-spacing-4 font-weight-600"> {{ value.headline }} </a></h2>
                        </div>
                        <div class="wt-post-meta ">
                            <ul>
                                <li class="post-date"><strong> {{ value.datePublished }} </strong></li>
                            </ul>
                        </div>
                        <div class="wt-post-text">
                            <p> {{ value.articleResume }} </p>
                        </div>
                        <nuxt-link  class="v-button letter-spacing-4 font-12 text-uppercase" :to="getLocalizedRoute({
                                name: 'news-category-slug'
                                , params: {
                                    category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                                    , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                                }
                            })">
                            {{ $t('Lire la suite') }}
                        </nuxt-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    name: 'NewsListing',
    props: {
        params: {
          type: Object
        }
    },
    computed: {
        ...mapState({
            data : state => state.articles.list
        })
    },
    methods: {
        imagePath: function (image) {
            if(null !== image){
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'grid' + process.env.PATH_DEFAULT_MEDIA + image.filename      
            }
            
            return null 
        }
    }
}
</script>
