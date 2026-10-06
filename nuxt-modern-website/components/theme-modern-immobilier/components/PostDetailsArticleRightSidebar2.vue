<template>
    <div class="article section-full p-tb90">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <BlogPost />
                    <div class="wt-post-text">
                        <p> {{ $t(text) }}</p>
                    </div>
                   
                    
                </div>
            </div>
            <div class="row">
                <div v-if="count(videos)" class="col-md-8 col-sm-6">
                    <VideoList :data="videos"/>
                </div>
                <div class="col-md-4 col-sm-6">
                    <TagList :data="tags" :params="getParams"/>
                    <ShareList/>
                </div>
               <BlockQuoteArticle/>
                <RelatedArticles v-if="checkComponents()" :params="params"/>
                <!--<div class="col-md-4 col-sm-6">
                    <aside class="side-bar">
                    
                    </aside>
                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import BlockQuoteArticle from '~/components/theme-modern-immobilier/components/BlockQuoteArticle'
import BlogPost from '~/components/theme-modern-immobilier/components/BlogPost'
import TagList from '~/components/theme-modern-immobilier/components/TagList'
import ShareList from '~/components/theme-modern-immobilier/components/ShareList'
import VideoList from '~/components/theme-modern-immobilier/components/VideoList'
import RelatedArticles from '~/components/theme-modern-immobilier/components/RelatedArticles'

export default {
    name: 'PostDetailsArticleRightSidebar2',
    props: {
        params: {
            type: Object
        }
    },
    components: {
          BlogPost
          ,BlockQuoteArticle
          ,TagList
          ,ShareList
          ,VideoList
          ,RelatedArticles
    },
    computed: {
        ...mapState({
            videos: state => state.articles.item.videos,
            text: state => state.articles.item.text,
            tags: state => state.footer.tags,
            categories: state => state.articles.item.tags,
            components: state => state.articles.item.components,
            category: state => state.articles.item.category
        }),
        getParams() {
            let params = { 'accommodation_nature':  'vente' }
            let index = this.categories
                .map((category)=>category.slug)
                .indexOf('location')
            if(-1 !== index) {
                params = { 'accommodation_nature':  'location' }
            }

            return params
        }
    },
    methods:{
        count(array) {
            if(array.length < 1) {

                return false
            }

            return true
        },
        checkComponents: function() {
        
            let index = this.components
                .map((component)=>component.slug)
                .indexOf('related-articles')
            
            if(-1 !== index) {
                this.params['category'] = this.category.slug

                return true
            }

            return false
        }
    }
}
</script>
<style scoped>
.article .p-tb30 {
    padding-bottom: 0;
    padding-top: 0;
}
</style>