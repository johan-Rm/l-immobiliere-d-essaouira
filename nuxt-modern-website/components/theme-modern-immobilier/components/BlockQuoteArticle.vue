<template>
    <blockquote>
        <i class="fa fa-quote-left" aria-hidden="true"></i>
        <div class="row p-lr40">
            <div class="col-md-4 col-sm-6">
                <img class="img-fluid img-thumbnail lazyload__" :src="imagePath()" :alt="alt" />
            </div>
            <div class="col-md-8 col-sm-6">
                <span v-html="$t(text)"/> 
                <div class="p-t15">
                    <p> – {{ $t(headline) }}</p>
                </div>
            </div>
        </div>
    </blockquote>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name:'BlockQuoteArticle',
    computed: {
        ...mapState({
            headline: state => state.articles.item.blockquoteTitle,
            text: state => state.articles.item.blockquote,
            alt: state => state.articles.item.name,
            image: state => state.articles.item.primaryImage,
        })
    },
    methods: {
        imagePath: function () {
            if(null !== this.image.filename) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'grid' + process.env.PATH_DEFAULT_MEDIA + this.image.filename      
            }

            return null
        }
    }
}
</script>
<style scoped>
@media screen and (min-width: 1200px) {  
blockquote p {

    position: absolute;
    bottom: -121px;
    right: 0;

}}
.fa-quote-left {
    color:#555;
    font-size: 35px;
    position: absolute;
    left: 20px;
    top: 38px;
    font-style: normal;
    color: #3e2723;
}
blockquote:before { content: ''; }
</style>
