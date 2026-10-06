<template>
<div class="page-content">
    <div class="section-full p-tb90">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 p-t15">
                    <Gallery v-if="gallery" :data="gallery" :params="params"/>
                    <BlogPost/>
                    <BlockQuote 
                        v-if="blockquoteText" 
                        :data="getDataForBlockquote" 
                        :params="params"
                    />
                    <RelatedArticles v-if="checkComponents()" :params="getParams('related-articles')"/>  
                </div>
                <div class="col-md-4 col-sm-12">
                    <aside class="side-bar">
                        <ShareList :data="getDataSharing" :params="getParams('share-list')"/>
                        <TagList v-if="tags" :data="tags" :params="getParams('tag-list')"/>
                        <VideoList v-if="count(videos)" :data="videos"/>
                        <template v-if="checkData(media)" >
                            <TitleImageLink :data="media" />
                        </template>
                    </aside>
                </div>
            </div> 
        </div>
    </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
import BlockQuote from '../components/BlockQuote'
import BlogPost from '../components/BlogPost'
import TagList from '../components/TagList'
import ShareList from '../components/ShareList'
import VideoList from '../components/VideoList'
import Gallery from '../components/Gallery'
import RelatedArticles from '../components/RelatedArticles'
import TitleImageLink from '../components/TitleImageLink'
import head from 'lodash.head'
import shuffle from 'lodash.shuffle'
export default {
    name: 'TemplateArticleRightSidebar',
    props: {
        params: {
            type: Object
        }
    },
    components: {
          BlogPost
          ,BlockQuote
          ,TagList
          ,ShareList
          ,VideoList
          ,RelatedArticles
          ,Gallery
          ,TitleImageLink
    },
    computed: {
        ...mapState({
            slug: state => state.articles.item.slug,
            videos: state => state.articles.item.videos,
            text: state => state.articles.item.text, 
            tags: state => state.footer.tags,
            categories: state => state.articles.item.tags,
            components: state => state.articles.item.components,
            category: state => state.articles.item.category,
            gallery: state => state.articles.item.gallery,
            blockquoteText: state => state.articles.item.blockquote,
            blockquoteTitle: state => state.articles.item.blockquoteTitle,
            alt: state => state.articles.item.blockquoteTitle,
            primaryImage: state => state.articles.item.primaryImage,
            media: state => state.articles.item.media
        }),
        getDataForBlockquote() {
            let image = null
            if(null !== this.gallery) {
                image = head(shuffle(this.gallery.imageGalleries))
            }
            image = this.primaryImage
            
            return { 
                image: image,
                headline: this.blockquoteTitle,
                text: this.blockquoteText,
                alt: this.alt
            }    
        },
        getDataSharing(){
            return{
                url:process.env.URL_WEBSITE + this.$route.fullPath ,
                title :`${ this.$store.state.articles.item.metaTitle } | ${ this.$store.state.organization.item.name }`,
                description: this.$store.state.articles.item.metaDescription
            }
        }
    },
    methods:{
        count(array) {
            if(typeof array !== 'undefined') {
                if(array.length > 0) {

                    return true
                }
            }
           
            return false
        },
        checkData(data) {
          
            if(data) {
                return true
            }
            return false
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
        },
        getParams(component) {
            let params = {}
            switch (component) {
                case 'tag-list':
                        params = { 'accommodation_nature':  'vente', 'class' :"size" }
                        let index = this.categories
                            .map((category)=>category.slug)
                            .indexOf('location')
                        if(-1 !== index) {
                            params = { 'accommodation_nature':  'location', 'class' :"size" }
                        }
                    break;
                case 'share-list':
                    params = { class: "p-b30" , label: "partager cet article" } 
                    
                    break;
                case 'related-articles':

                    params = { items: "3", truncate: 70, category: this.slug }
                    break;
            }

            return params
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