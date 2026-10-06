<template>
  <div class="page-content-container test">
    <component :is="template" :params="params"/>
  </div>
</template>
<script>
export default {
    name: 'aboutUs',
    layout: 'page',
    data() {
       return {
           params: {}
       }
    },
    computed: {
      template () {
          let name = 'AboutUs'

          return () => import(`~/components/theme-modern-immobilier/template/Template${name}`)
      },
      structuredData() {
        return {
              "@context":"http://schema.org",
              "@type": "WebPage",
              "name": this.$store.state.pages.item.metaTitle,
              "description": this.$store.state.pages.item.metaDescription,
              "publisher": {
                  "@type": "ProfilePage",
                  "name": this.$store.state.organization.item.name
              }
          }
      }
    },
    async fetch({ app, params, store, payload }) {
        if (payload) {

            store.commit('pages/setItem', payload.webPage)
        } else {

            store.dispatch('pages/getOneBy', { slug: 'lagence' })
            store.dispatch('about_us/getOneBy', { slug: store.state.organization.modules[2].slug })
            store.dispatch('our_offices/getOneBy', { slug: store.state.organization.modules[4].slug })
        }
        store.dispatch('team/getList')
        const breadcrumb = [
              { 
                slug: '/' + store.state.i18n.currentLocale,
                name: app.i18n.t('accueil'),
                route: {
                  name: 'index'
                }
              },
              { 
                slug: app.i18n.t('lagence'),
                name: app.i18n.t('L\'agence'),
                route: {
                  name: 'page',
                  params: {
                    page: app.$tradLinkSlug(store.state.i18n.currentLocale, 'lagence', 'webPage')
                  }
                }
              }
              
              
            ]
        store.commit('pages/setBreadcrumb', breadcrumb)

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
            ]}
    }
}
</script>