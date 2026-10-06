<template>
    <div class="who-we-are section-full p-t140 bg-repeat" :style="getBackgroundImage('ptn-1.png')">
        <div class="container">
            <div class="section-content">
            	<div class="row">
                    <div class="col-lg-6 col-md-4 col-sm-12">
                        <div class="wt-left-parttt">
                            <div class="text-black">
                                <span class="text-uppercase font-30 font-weight-300">{{ $t(headline) }}</span>
                                <h2 class="font-40 text-primary text-uppercase">
                                    {{ $t(alternativeHeadline) }}
                                </h2>
                                <p>{{ $t(textResume) | truncate(300) }}</p>       
                                <nuxt-link v-if="category.slug"
                                class="btn-half site-button button-lg m-b15"
                                :to="getLocalizedRoute({
                                    name: 'page'
                                    , params: {
                                        page: $tradLinkSlug($store.state.i18n.currentLocale, slug, 'webPage')
                                    }
                                })">
                                <span>{{ $t('plus d\'infos') }}</span>
                                <em></em>
                                </nuxt-link>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <WhoWeAreCarousel/>
                    </div>
                </div>
            </div>
            <!-- <div class="container">
            	<div class="year hilite-title p-lr20 m-tb20 text-left text-uppercase bdr-gray bdr-left">
                    <strong>{{ $store.state.organization.item.numberOfProjects }}+ {{ $t('Projets') }}</strong>
                    <span class="text-black">{{ $t('Accomplis') }}</span>
                </div>
            </div> -->
            <div v-if="params.year" class="hilite-title p-lr20 m-tb20 text-right text-uppercase bdr-gray bdr-right">
                <strong class="text-primary" v-if="foundingDate">{{ foundingDate }} </strong>
                <span class="text-black">{{ $t('D\'Experience Professionnelle') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import WhoWeAreCarousel from '~/components/theme-modern-immobilier/components/WhoWeAreCarousel'
export default {
    name: 'WhoWeAre',
    props: {
        params: {
            type: Object
        }
    },
    components:{
        WhoWeAreCarousel
    },
    computed: {
        ...mapState({
            headline: state => state.who_we_are.item.headline,
            alternativeHeadline: state => state.who_we_are.item.alternativeHeadline,
            textResume: state => state.who_we_are.item.textResume,
            slug: state => state.who_we_are.item.slug,
            category: state => state.who_we_are.item.category
        }),
        foundingDate() {
            let date = this.$store.state.organization.item.foundingDate
            let date1 = this.$dayjs().format('YYYY')
            let date2 = this.$dayjs(date).format('YYYY')
            
            return this.$dayjs(date1).diff(this.$dayjs(date2), "year") + ' ' + this.$t('ans')
        }
    },
    methods: {
        defineText(text) {
          return this.$i18n.t(text)
           // return this.$options.filters.truncate(this.$i18n.t(text), 300)
        },
        getBackgroundImage: function (filename) {
            if(null !== filename) {
                return 'background-image:url(' + process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename + ')'
            }

            return null
        }
    }
}
</script>
<style lang="scss">
.who-we-are {
    .text-primary {
        color: var(--color-primary) !important;
    }
}

.who-we-are .owl-carousel .owl-prev
, .who-we-are .owl-carousel .owl-next {
    background-color: var(--color-primary) !important;
    color: #ffffff !important;
}
</style>
