<template>
    <div class="about-us section-full p-tb90 bg-gray">
        <div class="container">
            <div class="section-content ">
            	<div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <AboutUsCarousel/>
                </div>
            		<div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="m-about-containt text-black p-t30">
                            <span class="font-30 font-weight-300 text-uppercase">{{ $t('A propos de nous') }}</span>
                                <h2 class="font-40 text-uppercase"> {{ article.alternativeHeadline }} </h2>
                                <p class="text-uppercase"><b> {{ $t(article.pushForward) }} </b></p>

                                <div class="wt-post-text">
                                    <p v-html="getArticleBody($store.state.i18n.currentLocale, 'articleBody', 'Article', article.slug)" ></p>
                                </div>    
                                    
                                <!-- <nuxt-link class="btn-half site-button button-lg m-b15"
                                    :to="getLocalizedRoute(
                                        { name: 'news-category-slug'
                                        , params: {
                                            category: $tradLinkSlug($store.state.i18n.currentLocale, article.category.slug, 'tag')
                                            , slug: $tradLinkSlug($store.state.i18n.currentLocale, article.slug, 'article')
                                        }
                                    }
                                )">
                                <span> {{ $t('plus d\'infos') }} </span>
                                <em></em>
                            </nuxt-link> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import AboutUsCarousel from '~/components/theme-modern-immobilier/components/AboutUsCarousel'
export default {
    name: 'AboutUS',
    props: {
        params:{
            type: Object
        }
    },
    components:{
        AboutUsCarousel
    },
    computed: {
        ...mapState({
            article: state => state.about_us.item
        })
    },
    methods: {
        getArticleBody(lang, fieldName, entityName, slug) {
          let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
          var html = this.$i18n.t(key)

          return html
        }
    }
}
</script>

<style lang="scss">


.about-us .owl-carousel .owl-prev
, .about-us .owl-carousel .owl-next {
    background-color: var(--color-primary) !important;
    color: #ffffff !important;
}

.about-us  .m-about::after {
	border: 10px solid var(--color-primary);
}
</style>
