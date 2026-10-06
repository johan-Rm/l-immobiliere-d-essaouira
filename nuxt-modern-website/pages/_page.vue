<template>
    <div class="page-content-container">
        <component :is="template" :params="params"/>
    </div>
</template>
<script>
export default {
    name: 'WebPageDefault',
    layout: 'page',
    data() {
       return {
           params: {}
       }
       
    },
    computed: {
        template () {
            let name = 'RightSidebar'
 
            return () => import(`~/components/theme-modern-immobilier/template/TemplatePage${name}`)
        },
        structuredData() {
            return {
                "@context":"http://schema.org"
                , "@type": "WebPage"
                , "name": this.$store.state.pages.item.metaTitle
                , "description": this.$store.state.pages.item.metaDescription
                , "publisher": {
                    "@type": "ProfilePage",
                    "name": this.$store.state.organization.item.name
                }
            }
        }
    },
    async fetch({ app, params, store, payload }) {
      let _slug_page = null
      if (payload) {
        _slug_page = app.$tradSlug(payload.locale, params.page, store.state.i18n.routes.page.entities.page)
        store.commit('pages/setItem', payload.webPage)
      } else {
        _slug_page = app.$tradSlug(
            store.state.i18n.currentLocale
            , params.page
            , store.state.i18n.routes.page.entities.page
        )
        store.dispatch('pages/getOneBy', { slug: _slug_page, isActive: 'true' })
      }
    },
    mounted() {
      // this.$store.commit('pages/setSlug', this.$store.state.pages.item.slug)
      // this.$store.commit('pages/setHeadline', this.$store.state.pages.item.headline)
                   
      // const breadcrumb = [
      //   { 
      //     slug: '/' + this.$store.state.i18n.currentLocale,
      //     name: this.$i18n.t('accueil'),
      //     route: {
      //       name: 'index'
      //     }
      //   },
      //   { 
      //     slug: this.$store.state.pages.item.slug,
      //     name: this.$i18n.t(this.$store.state.pages.item.headline),
      //     route: {
      //       name: 'page',
      //       params: {
      //         page: this.$tradLinkSlug(
      //           this.$store.state.i18n.currentLocale
      //           , this.$store.state.pages.item.slug
      //           , 'webPage'
      //         )
      //       }
      //     }
      //   } 
      // ]
      // this.$store.commit('pages/setBreadcrumb', breadcrumb)
    },
    head() {
        let filename = (null !== this.$store.state.pages.item.primaryImage) ? this.$store.state.pages.item.primaryImage.filename: null
        
        let metaTitle = this.$i18n.t(this.$store.state.pages.item.metaTitle) + ' | ' +this.$store.state.organization.item.name
        let metaDescription = this.$i18n.t(this.$store.state.pages.item.metaDescription)

        return {
            htmlAttrs: {
              lang: this.$store.state.i18n.currentLocale,
            },
            title:  metaTitle,
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
