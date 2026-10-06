<template>
    <div class="blog-post date-style-1 blog-detail text-black  container-fluid">
        <div class="wt-post-title">
            <h2 class="post-title"><a href="javascript:void(0);" class="text-black font-20 letter-spacing-2 font-weight-600"> {{ $t(headline) }} </a></h2>
        </div>
        <div class="wt-post-meta ">
            <ul>
                <li v-if="datePublished" class="post-date"><strong> {{ getDatePublished(datePublished) }} </strong></li>
                <li class="post-author">
                    <!-- <a href="javascript:void(0);"> -->
                        <span> {{ $t('L\'Immobilière d\'Essaouira') }} </span>
                    <!-- </a> -->
                </li>
            </ul>
        </div>
        <div class="wt-post-text">
            <p v-html="getArticleBody($store.state.i18n.currentLocale, 'articleBody', 'Article', slug)" ></p>
        </div>
        <div class="wt-post-text">
            <p>{{ $t(text) }}</p>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
// import * as moment from 'moment'
export default {
    name: 'BlogPost',
    computed: {
        ...mapState({
            headline: state => state.articles.item.headline,
            text: state => state.articles.item.text,
            articleBody: state => state.articles.item.articleBody,
            datePublished: state => state.articles.item.datePublished,
            slug: state => state.articles.item.slug
        })
    },
    methods: {
        getDatePublished(date) {

            return this.$dayjs(date).format('YYYY-MM-DD')   
            // return moment(date).format('YYYY')
        },
        getArticleBody(lang, fieldName, entityName, slug) {
          let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
          var html = this.$i18n.t(key)

          return html
        }
    }
}
</script>
