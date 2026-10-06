<template>
    <div class="page-content-container">
        <component :is="template" :params="params"/>
    </div>
</template>
<script>
export default {
    layout: 'page',
    name: 'post',                         
    data() {
        return {
            params: { slug: String }
        }
    },
    computed: {
        template () {
          
            return () => import(`~/components/theme-modern-immobilier/template/TemplateArticleRightSidebar`)
        },
        structuredData() {
            var primaryImageUrl = null
            if(this.$store.state.articles.item.primaryImage) {
                primaryImageUrl = this.$store.state.articles.item.primaryImage.url
            }
           
            return {
                "@context": "http://schema.org",
                "@graph":
                [  
                    {
                    "@type": "Article",
                    "headline":this.$store.state.articles.item.headline,
                    "alternativeHeadline": this.$store.state.articles.item.alternativeHeadline,
                    "image": primaryImageUrl,
                    "author": this.$store.state.articles.item.userCreated.fullname, 
                    // "award": "Best article ever written",
                    // "editor": "Craig Mount", 
                    // "genre": "search engine optimization", 
                    // "keywords": "seo sales b2b", 
                    // "wordcount": "1120",
                    "publisher": {
                        "@type": "Organization",
                        "name": this.$store.state.organization.item.name,
                         "logo": {
                                    "@type": "ImageObject",
                                    "url": this.$store.state.organization.item.primaryImage.url
                                    }
                    },
                    // "url": "http://www.example.com",
                    "mainEntityOfPage": {
                        "@type": "WebPage",
                        "@id": this.$store.state.articles.item.url
                    },
                    "datePublished": this.$store.state.articles.item.dateModified,
                    "dateModified": this.$store.state.articles.item.dateModified,
                    // "dateCreated": this.$store.state.articles.item.primaryImage.dateCreated,
                    // "dateModified": this.$store.state.articles.item.primaryImage.datePublished,
                    // "description": "We love to do stuff to help people and stuff",
                    "articleBody": this.$store.state.articles.item.primaryImage.articleBody
                    }
                    // ,
                    // {
                    // "@type":"WebPage",
                    // "name": "Actualité",
                    // "description": this.$store.state.articles.item.metaDescription
                    // }
                ]
                }
        }
    },
    async fetch({ app, params, store, payload }) {
        let _slug = null
        if (payload) {
          
            _slug = app.$tradSlug(payload.locale, params.slug, store.state.i18n.routes['news-category-slug'].entities.slug)
            store.commit('articles/setItem', payload.article)
            
        } else {
            
            _slug = app.$tradSlug(store.state.i18n.currentLocale, params.slug, store.state.i18n.routes['news-category-slug'].entities.slug)
            store.dispatch('articles/getOneBy', { slug: _slug, isActive: 'true' })
        }
    },
    mounted() {
      // this.$store.commit('pages/setPushForward', this.$store.state.articles.item.pushForward)
      // this.$store.commit('pages/setPrimaryImage', this.$store.state.articles.item.primaryImage)
      // this.$store.commit('pages/setSecondaryImage', this.$store.state.articles.item.secondaryImage)
      // this.$store.commit('pages/setSlug', this.$store.state.articles.item.slug)
      // this.$store.commit('pages/setHeadline', this.$store.state.articles.item.headline)
      // console.log('mounted news')
      // const breadcrumb = [
      //       { 
      //         slug: '/' + this.$store.state.i18n.currentLocale,
      //         name: this.$i18n.t('accueil'),
      //         route: {
      //             name: 'index'
      //         }
      //       },
      //       // { 
      //       //   slug: store.state.articles.item.category.slug,
      //       //   name: store.state.articles.item.category.name
      //       // },
      //        { 
      //         name: this.$i18n.t(this.$store.state.articles.item.category.name),
      //         route: {
      //           name: 'page',
      //           params: {
      //             page: this.$tradLinkSlug(
      //               this.$store.state.i18n.currentLocale
      //               , this.$store.state.articles.item.category.slug
      //               , 'webPage'
      //             )
      //           }
      //         }
      //       },
      //       { 
      //         slug: this.$store.state.articles.item.slug,
      //         name: this.$store.state.articles.item.alternativeHeadline,
      //         route: {
      //             name: 'news-category-slug',
      //             params: {
      //                 category: this.$tradLinkSlug(
      //                     this.$store.state.i18n.currentLocale
      //                     , this.$store.state.articles.item.category.slug
      //                     , 'tag'
      //                 )
      //                 , slug: this.$tradLinkSlug(
      //                     this.$store.state.i18n.currentLocale
      //                     , this.$store.state.articles.item.slug
      //                     , 'article'
      //                 )
      //             }
      //         } 
      //       }
      //     ]
      // this.$store.commit('pages/setBreadcrumb', breadcrumb)
    },
    head() {
        let filename = (null !== this.$store.state.pages.item.primaryImage) ? this.$store.state.articles.item.primaryImage.filename: null

        let metaTitle = this.$i18n.t(this.$store.state.articles.item.metaTitle) + ' | ' +this.$store.state.organization.item.name
        let metaDescription = this.$i18n.t(this.$store.state.articles.item.metaDescription)

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
                  content: this.$store.state.articles.item.metaTitle
                },
                {
                  hid: `og:description`,
                  property: 'og:description',
                  content: this.$store.state.articles.item.metaDescription
                },
                {
                  hid: `og:url`,
                  property: 'og:url',
                  content: process.env.URL_WEBSITE + this.$route.fullPath
                },
                {
                  hid: `og:type`,
                  property: 'og:type',
                  content: 'Article'
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