<template>
    <div class="blog-post date-style-1 blog-detail text-black">
        <h4 class="widget-title text-uppercase">
          {{ $t(headline) }}
        </h4>
        <p v-html="getText($store.state.i18n.currentLocale, 'text', 'WebPage', slug)"></p>
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'PagePost',
    props: {
        params: {
          type: Object
        }
    },
    computed: {
        ...mapState({
            headline: state => state.pages.item.headline,
            text: state => state.pages.item.text,
            slug: state => state.pages.item.slug
        })
    },
    methods: {
      getText(lang, fieldName, entityName, slug) {
        let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
        var html = this.$i18n.t(key)

        return html
      }
    }
}
</script>