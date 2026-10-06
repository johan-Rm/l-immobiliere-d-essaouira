<template>
    <footer class="site-footer footer-large footer-wide" :class="params.bg_primary">
        <div class="footer-top" :class="params.bg_primary">
            <div class="container" :class="params.bg_primary">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="widget widget_about p-r20">
                            <div class="clearfix p-b15">
                                <nuxt-link :to="getLocalizedRoute({ name: 'index' })">
                                    <img 
                                    :width="$getImageSize(primaryImage, 'width')" 
                                    :height="$getImageSize(primaryImage, 'height')"
                                    :data-src="getSecondaryLogo(primaryImage)" 
                                    :alt="primaryImage.alt" 
                                    class="lazyload" />
                                </nuxt-link>
                            </div>
                            <p class="text-justify">
                                {{ $t(articleResume) | truncate(200) }}
                            </p>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 p-t5">
                        
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 p-t5" :class="params.bg_primary">
                        
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 p-t5">
                        
                    </div>
                </div>
            </div>
        </div>
       <CopyRight :params="params"/>
    </footer>
</template>

<script>
import { mapState } from 'vuex'
import CopyRight from '~/components/theme-modern-immobilier/components/CopyRight'
export default {
    name: 'FooterLight',
    props: {
        params: {
            type: Object
        }
    },
    components: {
        CopyRight
    },
    computed: {
        ...mapState({
            primaryImage: state => state.organization.item.primaryImage,
            articleResume: state => state.footer.article.articleResume
        })
    },
    methods: {
        getSecondaryLogo(image) {
            return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + image.filename
        }
    }
}
</script>

<style scoped>
.footer-top .container .row > div {
    position: inherit;
}
</style>
