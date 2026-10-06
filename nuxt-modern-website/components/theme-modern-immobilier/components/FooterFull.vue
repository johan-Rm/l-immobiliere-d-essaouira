<template>
    <footer class="site-footer footer-large footer-wide" :class="params.bg_primary">
        <div class="footer-top" :class="params.bg_primary">
            <div class="container" :class="params.bg_primary">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6" :class="classTablet()">
                        <div class="widget widget_about p-r20">
                            <div class="clearfix p-b15">
                                <nuxt-link :to="getLocalizedRoute({ name: 'index' })">
                                    <img
                                    :width="$getImageSize(primaryImage, 'width')"
                                    :height="$getImageSize(primaryImage, 'height')"
                                    :data-src="getSecondaryLogo(primaryImage)"
                                    :alt="logoName"
                                    class="lazyload" />
                                </nuxt-link>
                            </div>
                            <p class="text-justify">
                                {{ $t(articleResume) | truncate(200) }}
                            </p>
                            <ReviewLink/>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 p-t5">
                        <UsefulLinks/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 p-t5" :class="params.bg_primary">
                        <TagList :data="tags" :params="getParams"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 p-t5">
                        <RecentAccommodation/>
                        <!-- <pre>{{ $device }}</pre> -->
                    </div>
                </div>
            </div>
        </div>
       <CopyRight :params="params"/>
    </footer>
</template>

<script>
import { mapState } from 'vuex'

// import RecentAccommodation from '~/components/theme-modern-immobilier/components/RecentAccommodation'
// import UsefulLinks from '~/components/theme-modern-immobilier/components/UsefulLinks'
// import TagList from '~/components/theme-modern-immobilier/components/TagList'
// import CopyRight from '~/components/theme-modern-immobilier/components/CopyRight'
// import ReviewLink from '~/components/theme-modern-immobilier/components/ReviewLink'

export default {
    name: 'FooterFull',
    props: {
        params: {
            type: Object
        }
    },
    components: {
        RecentAccommodation: () => import('./RecentAccommodation'),
        UsefulLinks: () => import('./UsefulLinks'),
        TagList: () => import('./TagList'),
        CopyRight: () => import('./CopyRight'),
        ReviewLink: () => import('./ReviewLink'),
        // RecentAccommodation
        // ,UsefulLinks
        // ,TagList
        // ,CopyRight
        // ,ReviewLink
    },
    computed: {
        ...mapState({
            tags: state => state.footer.tags,
            primaryImage: state => state.organization.item.primaryImage,
            articleResume: state => state.footer.article.articleResume,
            logoName: state => state.organization.item.name
        }),
        getParams() {
            this.params['limit'] = 12

            return this.params
        }
    },
    methods: {
        classTablet() {

            if(this.$device.isTablet && typeof window !== 'undefined' && window.innerWidth > 768) {

               return 'tablet_land'
            }

            return ''
        },
        getSecondaryLogo(image) {
            return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + image.filename
        }
    }
}
</script>

<style scoped >
.footer-top .container .row > div {
    position: inherit;
}
.logo-footer {
    vertical-align: top;
    font-size: inherit;
}
.widget_logo p {
    text-align: justify;
    padding:0 15px 0 0 ;
}
.widget-title{
    font-size: 18px;
line-height: 24px;
}

.footer-top .tablet_land {
    min-height: 650px
}
</style>
