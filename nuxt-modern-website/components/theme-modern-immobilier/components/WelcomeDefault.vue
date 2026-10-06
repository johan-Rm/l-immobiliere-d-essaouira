<template>
    <div class="section-full p-t90 bg-gray">
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-5 col-sm-12 text-black">
                        <span class="font-30 font-weight-600 text-black text-uppercase">{{ $t(alternativeHeadline) }}</span>
                        <div class="section-head text-uppercase" >
                            <h2 class="font-40">{{ $t(pushForward) }}</h2>
                        </div>
                        <p>{{ $t(articleResume) | truncate(300) }}</p>
                        <nuxt-link v-if="category.slug"
                        class="btn-half site-button button-lg m-b15"
                        :to="getLocalizedRoute({
                            name: 'news-category-slug'
                            , params: {
                                category: $tradLinkSlug($store.state.i18n.currentLocale, category.slug, 'tag')
                                , slug: $tradLinkSlug($store.state.i18n.currentLocale, slug, 'article')
                            }
                        })">
                        <span>{{ $t('plus d\'infos') }}</span>
                        <em></em>
                        </nuxt-link>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <WelcomeDefaultCarousel/>
                    </div>
                </div>
                <div class="hilite-title p-lr20 m-tb20 text-right text-uppercase bdr-gray bdr-right">
                    <strong class="text-primary" v-if="foundingDate">{{ foundingDate }} </strong>
                    <span class="text-black">{{ $t('D\'Experience Professionnelle') }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import WelcomeDefaultCarousel from '~/components/theme-modern-immobilier/components/WelcomeDefaultCarousel'
export default {
    name: 'WelcomeDefault',
    components:{
        WelcomeDefaultCarousel
    },
    props: {
        params: {
            type: Object
        }
    },
    head () {
        return {
           // __dangerouslyDisableSanitizers: ['script'],
           // script: [{ innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }],
        }
    },
    computed: {
        ...mapState({
            alternativeHeadline: state => state.welcome.item.alternativeHeadline,
            pushForward: state => state.welcome.item.pushForward,
            articleResume: state => state.welcome.item.articleResume,
            slug: state => state.welcome.item.slug,
            category: state => state.welcome.item.category,
            carousel: state => state.welcome.item.gallery.imageGalleries,
            currentLocale: state => state.i18n.currentLocale
        }),
        foundingDate() {
            let date = this.$store.state.organization.item.foundingDate
            let date1 = this.$dayjs().format('YYYY')
            let date2 = this.$dayjs(date).format('YYYY')
            
            return this.$dayjs(date1).diff(this.$dayjs(date2), "year") + ' ' + this.$t('ans')
        },
        structuredData(){
            this.d = this.$store.state.articles.item.headline
            return {
                "@type": "Article",
                "headline":this.$store.state.articles.item.headline,
                "alternativeHeadline": this.$store.state.articles.item.alternativeHeadline,
                "publisher": {
                    "@type": "Organization",
                    "name": this.$store.state.organization.item.name,
                },
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": this.$store.state.articles.item.url
                },
                "datePublished": this.$store.state.articles.item.datePublished,
                "dateModified": this.$store.state.articles.item.dateModified
            }
        }
    }
}
</script>