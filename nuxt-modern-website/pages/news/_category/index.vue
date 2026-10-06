<template>
    <div class="page-content-container">
        <NewsListing :params="params"/>
    </div>
</template>
<script>
import NewsListing from '~/components/theme-modern-immobilier/components/NewsListing'
export default {
    layout: 'page',
    components:{
        NewsListing
    },
    data: () => ({
            params: {},
            
        }
    ),
    computed: {
        structuredData() {
            return {
                "@type":"WebPage",
                "@context":"http://schema.org",
                "name": this.$store.state.tags.item.name,
                "description": this.$store.state.tags.item.name
            }
        }
    },
    async fetch({ app, params, store, payload }) {

        let _category = null
        if (payload) {
           
            _category = app.$tradSlug(payload.locale, params.category, store.state.i18n.routes['news-category'].entities.category)
            store.commit('tags/setItem', payload.tag)
            
        } else {
            
            _category = app.$tradSlug(store.state.i18n.currentLocale, params.category, store.state.i18n.routes['news-category'].entities.category)
            await store.dispatch('tags/getOneBy', { slug: _category, isActive: 'true' })
        }
        await store.dispatch('pages/getOneBy', { slug: 'lagence' })
        await store.dispatch('articles/getListBy', { 'tags.slug': _category, isActive: 'true' })
    },
    head() {
        let filename = (null !== this.$store.state.pages.item.primaryImage) ? this.$store.state.pages.item.primaryImage.filename: null
        
        let metaTitle = this.$i18n.t(this.$store.state.tags.item.metaTitle) + ' | ' +this.$store.state.organization.item.name
        let metaDescription = this.$i18n.t(this.$store.state.pages.item.metaDescription)

        return {
            htmlAttrs: {
              lang: this.$store.state.i18n.currentLocale,
            },
            title: metaTitle,
            __dangerouslyDisableSanitizers: ['script'],
            script: [{ innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }],
            meta: [
                { charset: 'utf-8' },
                { name: 'viewport', content: 'width=device-width, initial-scale=1' },
                { 
                    hid: 'description'
                    , name: 'description'
                    , content: metaDescription 
                },
                {
                  hid: `og:title`,
                  property: 'og:title',
                  content: metaTitle
                },
                {
                  hid: `og:description`,
                  property: 'og:description',
                  content: this.$store.state.pages.item.metaDescription
                },
                {
                  hid: `og:url`,
                  property: 'og:url',
                  content: process.env.URL_WEBSITE + this.$route.fullPath
                },
                {
                  hid: `og:type`,
                  property: 'og:type',
                  content: 'WebPage'
                },
                {
                  hid: `og:locale`,
                  property: 'og:locale',
                  content: this.$store.state.i18n.currentLocale
                },
                {
                  hid: `og:image`,
                  property: 'og:image',
                  content: process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename
                },
                {
                  hid: `og:site_name`,
                  property: 'og:site_name',
                  content: this.$store.state.organization.item.name
                },
                { hid: 'twitter:card', name: 'twitter:card', content: 'summary_large_image' },
                { hid: 'twitter:title', name: 'twitter:title', content: metaTitle },
                { hid: 'twitter:description', name: 'twitter:description', content: metaDescription },
                { hid: 'twitter:image', name: 'twitter:image', content: process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename }
            ]
        }
    }
}
</script>
