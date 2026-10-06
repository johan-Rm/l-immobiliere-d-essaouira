<template>
<div class="page-content">
    <div class="section-full p-tb90">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 p-t15">
                    <Gallery v-if="gallery" :data="gallery" :params="params"/>
                    <PagePost :params="params"/>
                    <BlockQuote 
                        v-if="blockquoteText" 
                        :data="getDataForBlockquote" 
                        :params="params"
                    />
                    <RelatedArticles v-if="checkComponents()" :params="getParams('related-articles')"/>
                </div>
                <div class="col-md-4 col-sm-12">
                  <ShareList :data="getDataSharing" :params="getParams('share-list')"/>
                  <TagList v-if="tags" :data="tags" :params="getParams('tag-list')"/>
                  <VideoList v-if="count(videos)" :data="videos"/>
                </div>
            </div> 
        </div>
    </div>
</div>
</template>
<script>
import PagePost from '../components/PagePost'
import BlockQuote from '../components/BlockQuote'
import TagList from '../components/TagList'
import ShareList from '../components/ShareList'
import Gallery from '../components/Gallery'
import VideoList from '../components/VideoList'
import RelatedArticles from '../components/RelatedArticles'
import { mapState } from 'vuex'
import head from 'lodash.head'
import shuffle from 'lodash.shuffle'

export default {
    name: 'TemplatePageRightSidebar',
    props: {
        params: {
          type: Object
        }
    },
    components:{
        PagePost
        ,BlockQuote
        ,TagList
        ,ShareList
        ,VideoList
        ,RelatedArticles
        ,Gallery
    },
    computed:{
        ...mapState({
            videos: state => state.pages.item.videos,
            text: state => state.pages.item.text,
            tags: state => state.footer.tags,
            categories: state => state.pages.item.tags,
            components: state => state.pages.item.components,
            category: state => state.pages.item.category,
            data: state => state.pages.item,
            blockquoteText: state => state.pages.item.blockquote,
            blockquoteTitle: state => state.pages.item.blockquoteTitle,
            alt: state => state.pages.item.blockquoteTitle,
            gallery: state => state.pages.item.gallery
        }),
        getDataForBlockquote() {
            if(null !== this.gallery) {
                let image = head(shuffle(this.gallery.imageGalleries))
                
                return { 
                    image: image,
                    headline: this.blockquoteTitle,
                    text: this.blockquoteText,
                    alt: this.alt
                }
            }
        },
        getDataSharing(){
            return{
                url:process.env.URL_WEBSITE + this.$route.fullPath ,
                title :`${ this.data.metaTitle } | ${ this.$store.state.organization.item.name }`,
                description: this.data.metaDescription
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
        checkComponents: function() {
        
            let index = this.data.components
                .map((component)=>component.slug)
                .indexOf('related-articles')
            
            if(-1 !== index) {
                this.params['category'] = this.data.category.slug
                // this.params['nb_slide'] = 3;
                // this.params['truncate_length'] = 90;
                
                return true
            }

            return false
        },
        getParams(component) {
            let params = {}
            switch (component) {
                case 'tag-list':

                    params = { 'accommodation_nature':  'vente' }
                    let index = this.categories
                        .map((category)=>category.slug)
                        .indexOf('location')
                    if(-1 !== index) {
                        params = { 'accommodation_nature':  'location' }
                    }
                    break;
                case 'share-list':

                    params = { class: "p-b30" , label: 'Partager cette page' } 
                    break;
                case 'related-articles':
 
                    params = { items: "3", truncate: 80, category: this.category.slug }
                    break;
            }

            return params
        }
    }
}
</script>
