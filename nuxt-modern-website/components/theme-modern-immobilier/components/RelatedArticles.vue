<template>
    <div class="RelatedArticles section-content">
        <div class="section-content">
            <h2 class="post-title text-uppercase hidden"> {{ $t('articles similaires') }} : </h2>
            <client-only>
            <div class="owl-carousel_ blog-related-slider_ owl-btn-top-right_ row">
                <div v-for="(value, index) in data"
                    :key="index"
                    class="_item col-lg-6 col-md-6 col-sm-12"
                >
                        <div class="blog-post blog-grid date-style-1">
                        <div class="wt-post-media wt-img-effect zoom-slow lz-loading ratio-container unknown-ratio-container">
                            <nuxt-link
                                :to="getLocalizedRoute({
                                name: 'news-category-slug'
                                , params: {
                                    category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                                    , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                                }
                                    })">
                                    <img
                                    :width="$getImageSizeByFilterSets('width', 'grid_nostamp')"
                                    :height="$getImageSizeByFilterSets('height','grid_nostamp')"
                                    :data-src="imageMobilePath(value.primaryImage)"
                                    :alt="value.primaryImage.alt"
                                    class="img-responsive img-responsive-vertical mobile lazyload" />
                                    <img
                                    :width="$getImageSizeByFilterSets('width', 'grid')"
                                    :height="$getImageSizeByFilterSets('height','grid')"
                                    :data-src="imagePath(value.primaryImage)"
                                    :alt="value.primaryImage.alt"
                                    class="img-responsive img-responsive-horizontal desktop lazyload" />
                            </nuxt-link>
                        </div>
                        <div class="wt-post-info p-a20 bg-gray text-black">
                            <div class="wt-post-title ">
                                <h4 class="post-title">
                                    <nuxt-link
                                    class="text-black font-16 letter-spacing-2 font-weight-600"
                                    :to="getLocalizedRoute({
                                    name: 'news-category-slug'
                                    , params: {
                                        category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                                        , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                                    }
                                        })">
                                        {{ value.alternativeHeadline }}
                                    </nuxt-link>
                                </h4>
                            </div>
                            <div class="wt-post-meta ">
                            </div>
                            <div class="wt-post-text">
                                <p>
                                    <nuxt-link
                                    :to="getLocalizedRoute({
                                    name: 'news-category-slug'
                                    , params: {
                                        category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                                        , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                                    }
                                        })">
                                    {{ value.articleResume | truncate(params.truncate) }}
                                    </nuxt-link>
                                </p>
                            </div>
                            <nuxt-link
                                    class="site-button black radius-no text-uppercase"
                                    :to="getLocalizedRoute({
                                    name: 'news-category-slug'
                                    , params: {
                                        category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                                        , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                                    }
                                        })">
                                    <span class="font-12 letter-spacing-5"> {{ $t('plus d\'infos') }} </span>
                            </nuxt-link>
                        </div>
                    </div>
                </div>
            </div>
            </client-only>
        </div>
    </div>
</template>

<script>
import { initBlog_related_slider } from '~/plugins/custom_transform_to_export.js'
export default {
    name:"RelatedArticles",
    data () {
        // sub object are transform into variable
        return {
            data: [],
            isMobile: false,
            isTablet: false
        }
    },
    props: {
        params: {
          type: Object
        }
    },
    methods: {
        imagePath: function (image) {
            if(null !== image) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'grid' + process.env.PATH_DEFAULT_MEDIA + image.filename
            }

            return null
        },
        imageMobilePath: function (image) {
            if(null !== image) {
                var format = 'grid_nostamp'

                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + image.filename
            }

            return null
        },
        getDatePublished(date) {
            // moment.locale(this.$store.state.i18n.currentLocale)

            return this.$dayjs(date).format('YYYY-MM-DD')
            // return moment(date).format('YYYY-MM-DD')
            // return timezone.tz(date, "Europe/Berlin").format('YYYY-MM-DD')
        }
    },
    mounted () {
      if(this.$device.isMobile) {
        this.isMobile = true
      }
      if(this.$device.isTablet) {
        this.isTablet = true
      }
        import('~/data/articles.json').then((result) => {
            this.data = result["hydra:member"].filter(r => r.category.slug === this.params.category)
            this.$nextTick(function(){ initBlog_related_slider() }.bind(this))
        })
        // const params = { 'category.slug': this.params.category, isActive: true }
        // this.$axios.get('/articles'
        //     , {
        //         params,
        //         validateStatus: function (status) {
        //             return status >= 200 && status < 500
        //         }
        //     })
        // .then((response) => {
        //     this.data = response.data['hydra:member']
        //     this.$nextTick(function(){ initBlog_related_slider() }.bind(this))
        // }).catch(error => {
        //     console.log(error)
        //     console.log('error store RelatedArticles.vue')
        // })
    }
}
</script>
<style lang="scss">


.RelatedArticles .owl-carousel .owl-prev
, .RelatedArticles .owl-carousel .owl-next {
    background-color: var(--color-primary) !important;
    color: #ffffff !important;
}
.RelatedArticles .wt-post-title {
    height: 40px;
}
p a{
    color: #000
}

.RelatedArticles .ratio-container:after {
    /* ratio = calc(148 / 236 * 100%) */
    padding-bottom: 62.7118%;
}
.RelatedArticles .lz-loading a {
  z-index: 5;
}

</style>
