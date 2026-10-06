<template>
  <div class="page-content-container">
    <component
      :is="template"
      :params="params"
    />
  </div>
</template>

<script>
export default {
    layout: 'page',
    async asyncData({ app, store, query, route }) {
        
        store.dispatch('pages/getOneBy', { slug: 'location-immobilier' })
        store.dispatch('accommodation_types/getList', { isActive: 'true', isLocation: 'true' }) 
        const breadcrumb = [
          { 
            slug: '/' + store.state.i18n.currentLocale,
            name: app.i18n.t('accueil'),
            route: {
              name: 'index'
            }
          },
          { 
            slug: app.i18n.t('Location'),
            name: app.i18n.t('Location'),
            route: {
              name: 'renting'
            }
          }
        ]
        store.commit('pages/setBreadcrumb', breadcrumb)

        var args = { 
          isActive : 'true'
          , itemsPerPage: store.state.accommodations.itemsPerPage 
          , 'nature.slug': 'location'
        }

        if(store.state.search.nature === 'location') {
          args = app.$getSearchStoreApiArgs(args, store.state.search)  
        }
        // args['duration.slug'] = store.state.search.duration ? store.state.search.duration : '' 
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
    data() {
       return {
           params: {
              nature: 'location',
              duration: 'saisonniere',
              currentRouteName: 'renting',
              routeName: 'renting',
              routeCategoryName: 'renting-category',
              routeCategorySlugName: 'renting-category-slug'
           }
       }
    },
    async fetch() {
      this.$store.commit('organization/setContainerResult', true)
       var args = { 
          isActive : 'true'
          , itemsPerPage: this.$store.state.accommodations.itemsPerPage 
          , 'nature.slug': 'location'
          , 'order[id]': 'desc' 
        }
        if(this.$store.state.search.nature === this.params.nature) {
          args = this.$getSearchStoreApiArgs(args, this.$store.state.search)  
        }
        // args['duration.slug'] = this.isEmpty(this.$store.state.search.duration ) ? '' : this.$store.state.search.duration
        if(this.$route.query.hasOwnProperty('page')) {
          args['page'] = this.$route.query.page
          args['source'] = 'JSON'
          await this.$store.dispatch('accommodations/getListBy', args)
        } else  {
          args['source'] = 'JSON'
          await this.$store.dispatch('accommodations/getListBy', args)
        }
        this.$store.commit('organization/setContainerResult', false)
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
    },
    computed: {
        template () {
            let name = 'Grid'
            
            return () => import(`~/components/theme-modern-immobilier/template/TemplateAccommodationsList${name}`)
        },
        structuredData() {
  
            
            const itemListObject = []
            const route = this.$route !== 'undefined' ? this.$route : null
            this.$store.state.accommodations.list.forEach(function (accommodation, i) {


              const path = process.env.URL_WEBSITE + route.fullPath + '/' + accommodation.slug
              var url = null
                if(null !== accommodation.primaryImage) {
                    url = accommodation.primaryImage.url
                }

              itemListObject[i] = {
                "@type": ["Accommodation", "Product"],
                "name": accommodation.headline,
                "productID": accommodation.reference,
                "description": accommodation.metaDescription,
                "floorSize":{
                "@type":"QuantitativeValue",
                "value": accommodation.floorSize
                            },
                "numberOfRooms":accommodation.numberOfRooms,
                "address": {
                    "@type": "PostalAddress",
                    // "streetAddress": accommodation.state.organization.item.addresses.address,
                    // "addressLocality": "Delray Beach",
                    "addressRegion": accommodation.place.name,
                    // "postalCode": this.$store.state.organization.item.addresses.postcode,
                    // "city": this.$store.state.organization.item.addresses.city,
                    // "addressCountry": this.$store.state.organization.item.addresses.country
                },
                "image": url,
                // "priceRange": accommodation.price,
                "offers": {
                  "@type": "Offer",
                  "priceCurrency": "EUR",
                  "price": accommodation.price,
                  "url": path
                }
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
    watch: {
      '$route.query': '$fetch'
    },
    mounted(){
      // this.params.duration = this.$store.state.search.duration
      this.$fetch()
    },

    methods: {
      isEmpty(value) {
        return (value == null || (typeof value === "string" && value.trim().length === 0))
      },
    }
}
</script>
