<template>
    <div class="page-content-container">
        <component :is="template" :params="params"/>
    </div>
</template>

<script>
export default {
    layout: 'page',
    watch: {
    '$route.query': '$fetch'
    },
    async fetch() {
      this.$store.commit('organization/setContainerResult', true)
       var args = { 
          isActive : 'true'
          , itemsPerPage: this.$store.state.accommodations.itemsPerPage 
          , 'nature.slug': 'vente'
          , 'order[id]': 'desc' 
          , 'type.slug' : this.$tradSlug(
              this.$store.state.i18n.currentLocale
              , this.$route.params.category
              , 'accommodationType'
            )
        }
        if(this.$store.state.search.nature === this.params.nature) {
          args = this.$getSearchStoreApiArgs(args, this.$store.state.search)  
        }
        if(this.$route.query.hasOwnProperty('page')) {
          args['page'] = this.$route.query.page
          args['source'] = 'JSON'
          await this.$store.dispatch('accommodations/getListBy', args)
        } else if(args['price[gte]'] > 0 || args['price[lte]'] > 0) {
          args['source'] = 'JSON'
          await this.$store.dispatch('accommodations/getListBy', args)
        }
        this.$store.commit('organization/setContainerResult', false)
    },
    data() {
        return {
            params: {
                currentCategory: this.$route.params.category,
                nature: 'vente',
                currentRouteName: 'selling-category',
                routeName: 'selling',
                routeCategoryName: 'selling-category',
                routeCategorySlugName: 'selling-category-slug'
            }
        }
    },
    computed: {
        template () {
            let name = 'Grid'
 
            return () => import(`~/components/theme-modern-immobilier/template/TemplateAccommodationsList${name}`)
        },
        structuredData() {
            

            const itemListObject = []
            this.$store.state.accommodations.list.forEach(function (accommodation, i) {
              
              var url = null
            if(null !== accommodation.primaryImage) {
                url = accommodation.primaryImage.url
            }
            
              itemListObject[i] = {
                "@type": ["Accommodation", "Product"],
                "name": accommodation.headline,
                "productID": accommodation.reference,
                "description": accommodation.metaDescription,
                "offers": {
                  "@type" : "Offer",
                  "price" : accommodation.price,
                  "priceCurrency": "EUR"

                },
                "floorSize":{
                "@type":"QuantitativeValue",
                "value": accommodation.floorSize
                            },
                "numberOfRooms":accommodation.numberOfRooms,
                "address": {
                    "@type": "PostalAddress",
                    // "streetAddress": this.$store.state.organization.item.addresses.address,
                    // "addressLocality": "Delray Beach",
                    "addressRegion": accommodation.place.name,
                    // "postalCode": this.$store.state.organization.item.addresses.postcode,
                    // "city": this.$store.state.organization.item.addresses.city,
                    // "addressCountry": this.$store.state.organization.item.addresses.country
                },
                "image": url
                // "priceRange": accommodation.price
              }  

            })
          return {
              "@context": "http://schema.org",
              "@graph": 
                [{
                    "@type":"WebPage",
                    "name": this.$store.state.pages.item.metaTitle,
                    "description": this.$store.state.pages.item.metaDescription,
                    "publisher": {
                                "@type": "ProfilePage",
                                "name": this.$store.state.organization.item.name
                                        }
                },
                [itemListObject]
                ]
            }
        }
    },
    async asyncData({ app, params, store, payload, query, route }) {

        let _category = null
        if (payload) {
            _category = app.$tradSlug(payload.locale, params.category, store.state.i18n.routes['selling-category'].entities.category)
            store.commit('accommodation_types/setItem', payload.type)
        } else {
            _category = app.$tradSlug(
                store.state.i18n.currentLocale
                , params.category
                , store.state.i18n.routes['selling-category'].entities.category
            )
            store.dispatch('accommodation_types/getOneBy', { slug: _category, isActive: 'true' }) 
        }

        
        
        const params1 = { slug: 'vente-' + _category, isActive: 'true' }
        const params2 = { slug: 'achat-vente-immobilier', isActive: 'true' }
        store.dispatch('pages/getOneByDefault', { params1, params2 })
        
        // store.dispatch('pages/getOneBy', params1)

        store.dispatch('accommodation_types/getList', { isActive: 'true', isLocation: 'false' })
       
        // const breadcrumb = [
        //   { 
        //     slug: '/' + store.state.i18n.currentLocale,
        //     name: app.i18n.t('accueil'),
        //     route: {
        //       name: 'index'
        //     }
        //   },
        //   { 
        //     slug: app.i18n.t('Vente'),
        //     name: app.i18n.t('Vente'),
        //     route: {
        //       name: 'selling'
        //     }
        //   },
        //   { 
        //     slug: store.state.accommodation_types.item.slug,
        //     name: store.state.accommodation_types.item.name,
        //     route: {
        //         name: 'selling-category',
        //         params: {
        //             category: app.$tradLinkSlug(
        //                 store.state.i18n.currentLocale
        //                 , store.state.accommodation_types.item.slug
        //                 , 'accommodationType'
        //             )
        //         }
        //     }
        //   }
        // ]
        // store.commit('pages/setBreadcrumb', breadcrumb)

        var args = { 
          isActive : 'true'
          , itemsPerPage: store.state.accommodations.itemsPerPage 
          , 'nature.slug': 'vente',
          'type.slug' : app.$tradSlug(
              store.state.i18n.currentLocale
              , route.params.category
              , 'accommodationType'
            )
        }
        if(store.state.search.nature === 'vente') {
          args = app.$getSearchStoreApiArgs(args, store.state.search)  
        }
        if(route.query.hasOwnProperty('page')) {
          args['page'] = route.query.page
          args['source'] = 'JSON'
          store.dispatch('accommodations/getListBy', args)
        } else if(args['price[gte]'] > 0 || args['price[lte]'] > 0) {
          args['source'] = 'JSON'
          store.dispatch('accommodations/getListBy', args)
        } else {
          args['source'] = 'JSON'
          store.dispatch('accommodations/getListBy', args)
        }
    },
    head() {
        let filename = (null !== this.$store.state.pages.item.primaryImage) ? this.$store.state.pages.item.primaryImage.filename: null
        
        let metaTitle = this.$i18n.t(this.$store.state.pages.item.metaTitle) + ' | ' +this.$store.state.organization.item.name
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
