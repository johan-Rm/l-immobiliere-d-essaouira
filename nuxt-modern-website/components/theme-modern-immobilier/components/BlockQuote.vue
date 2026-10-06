<template>
    <blockquote>
        <i class="fa fa-quote-left" aria-hidden="true"></i>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <img class="img-fluid lazyload__" :src="imagePath(data.image)" :alt="data.alt" />
            </div>
            <div class="col-md-9 col-sm-6">
                <span v-html="getText($store.state.i18n.currentLocale, 'text', 'WebPage', data.slug)"/> 
            </div>
            <span class="title p-r15"> – {{ $t(data.headline) }}</span>
        </div>
    </blockquote>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name:'BlockQuote',
    props: {
        data: {
          type: Object
        },
        params: {
          type: Object
        }
    },
    methods: {
        imagePath: function (image) {
           
            if(null !== image && typeof image !== 'undefined') {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'square_small' + process.env.PATH_DEFAULT_MEDIA + image.filename   
            }

            return null
        },
        getText(lang, fieldName, entityName, slug) {
          let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
          var html = this.$i18n.t(key)
         
          return html
        }
    }
}
</script>
<style scoped>
@media screen and (min-width: 1200px) {  
    blockquote .row { position: relative; }
    blockquote span.title {
        position: absolute;
        bottom: 0;
        right: 0;
        line-height:1em;
    }

}
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
