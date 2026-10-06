<template>
    <div>
        <div class="wt-post-title">
            <h2 class="post-title"><a href="javascript:void(0);" class="text-black font-20 letter-spacing-2 font-weight-600"> {{ $t(alternativeHeadline) }} </a></h2>
        </div>
        <div class="wt-post-text">
            <p v-html="getText($store.state.i18n.currentLocale, 'text', 'WebPage', slug)" ></p>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name:"HeadPage",
    computed: {
        ...mapState({
            alternativeHeadline: state => state.pages.item.alternativeHeadline,
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