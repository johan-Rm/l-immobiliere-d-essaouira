<template>
    <div class="page-content-container">
        <component :is="template" :params="params"/>
    </div>
</template>

<script>
export default {
    layout: 'page',
    data() {
      return {
        params: {
            nature: "location",
            routeName: 'renting',
            routeCategoryName: 'renting-category',
            routeCategorySlugName: 'renting-category-slug'
        }
      }
    },
    computed: {
        template () {
            let name = 'DefaultSidebar'
            
            return () => import(`~/components/theme-modern-immobilier/template/TemplateAccommodationsCard${name}`)
        },
        structuredData() {
          const url = this.$route !== 'undefined' ? process.env.URL_WEBSITE + this.$route.fullPath : process.env.URL_WEBSITE 
            var primaryImageUrl = null
            if(this.$store.state.accommodations.item.primaryImage) {
                primaryImageUrl = this.$store.state.accommodations.item.primaryImage.url
            }

            return {
                "@context": "http://schema.org",
                "@graph":[
                  {
                      "@type": ["Accommodation", "Product"],
                      "name": this.$store.state.accommodations.item.headline,
                      "productID": this.$store.state.accommodations.item.reference,
                      "description": this.$store.state.accommodations.item.metaDescription,
                      "floorSize":{
                        "@type":"QuantitativeValue",
                        "value": this.$store.state.accommodations.item.floorSize
                      },
                      "numberOfRooms":this.$store.state.accommodations.item.numberOfRooms,
                      "address": {
                        "@type": "PostalAddress",
                        // "streetAddress": this.$store.state.organization.item.addresses.address,
                        // "addressLocality": "Delray Beach",
                        "addressRegion": this.$store.state.accommodations.item.place.name,
                        // "postalCode": this.$store.state.organization.item.addresses.postcode,
                        // "city": this.$store.state.organization.item.addresses.city,
                        // "addressCountry": this.$store.state.organization.item.addresses.country
                      },
                      "image": primaryImageUrl,
                      // "priceRange": this.$store.state.accommodations.item.price
                      "offers": {
                        "@type": "Offer",
                        "priceCurrency": "EUR",
                        "price": this.$store.state.accommodations.item.price,
                        "url": url
                      }
                  },
                  {
                    "@type":"WebPage",
                    "name": "location",
                    "description": this.$store.state.accommodations.item.metaTitle ,
                    "publisher": {
                                      "@type": "ProfilePage",
                                  "name": this.$store.state.organization.item.name
                                          }
                  }
              ]
          }
        }
    },
    async fetch({ app, params, store, payload }) {
        
        let _category = null
        let _slug = null
        if (payload) {
            
            _category = app.$tradSlug(payload.locale, params.category, store.state.i18n.routes['renting-category'].entities.category)
            _slug = app.$tradSlug(payload.locale, params.slug, store.state.i18n.routes['renting-category-slug'].entities.slug)
            store.commit('accommodations/setItem', payload.accommodation)
            store.commit('accommodation_types/setItem', payload.type)

        } else {

            _category = app.$tradSlug(store.state.i18n.currentLocale, params.category, store.state.i18n.routes['renting-category'].entities.category)
            let _slug = app.$tradSlug(store.state.i18n.currentLocale, params.slug, store.state.i18n.routes['renting-category-slug'].entities.slug)
        
       
            store.dispatch('accommodations/getOneBy', { slug: _slug, type: _category })
            store.dispatch('accommodation_types/getOneBy', { slug: _category })
        }
        
        store.dispatch('accommodation_types/getList', { isActive: 'true', isLocation: 'true' })
        await store.dispatch('accommodations/getListRelated', {
            isActive : 'true'
            , page: 1
            , 'nature.slug': 'location'
            , 'type.slug': _category
            , itemsPerPage: 12
        })       
    },
    mounted() {
        // this.$store.commit('pages/setPushForward', this.$store.state.accommodations.item.headline)
        // this.$store.commit('pages/setPrimaryImage', this.$store.state.accommodations.item.primaryImage)
        // this.$store.commit('pages/setSecondaryImage', this.$store.state.accommodations.item.secondaryImage)
        // this.$store.commit('pages/setSlug', this.$store.state.accommodations.item.slug)
        
        // const breadcrumb = [
        //       { 
        //         slug: '/' + this.$store.state.i18n.currentLocale,
        //         name: this.$i18n.t('accueil'),
        //         route: {
        //           name: 'index'
        //         }
        //       },
        //       { 
        //         slug: this.$i18n.t('Location'),
        //         name: this.$i18n.t('Location'),
        //         route: {
        //           name: 'renting'
        //         }
        //       },
        //       { 
        //         slug: this.$store.state.accommodation_types.item.slug,
        //         name: this.$store.state.accommodation_types.item.name,
        //         route: {
        //             name: 'renting-category',
        //             params: {
        //                 category: this.$tradLinkSlug(
        //                     this.$store.state.i18n.currentLocale
        //                     , this.$store.state.accommodation_types.item.slug
        //                     , 'accommodationType'
        //                 )
        //             }
        //         }
        //       }
        //     ]
        // this.$store.commit('pages/setBreadcrumb', breadcrumb)

        // const alternativePushForward = { // nature, type, surface, place, price
        //   nature: this.$store.state.accommodations.item.nature.name,
        //   type: this.$store.state.accommodations.item.type.name,
        //   floorSize: this.$store.state.accommodations.item.floorSize,
        //   place: this.$store.state.accommodations.item.place.name,
        //   price: this.$store.state.accommodations.item.price
        // }
        // this.$store.commit('pages/setAlternativePushForward', alternativePushForward)
    },
    head() {        
        let filename = (null !== this.$store.state.pages.item.primaryImage) ? this.$store.state.accommodations.item.primaryImage.filename: null

        let metaTitle = this.$i18n.t(this.$store.state.accommodations.item.metaTitle) + ' | ' +this.$store.state.organization.item.name
        let metaDescription = this.$i18n.t(this.$store.state.accommodations.item.metaDescription)

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
                  content: this.$store.state.accommodations.item.metaTitle
                },
                {
                  hid: `og:description`,
                  property: 'og:description',
                  content: this.$store.state.accommodations.item.metaDescription
                },
                {
                  hid: `og:url`,
                  property: 'og:url',
                  content: process.env.URL_WEBSITE + this.$route.fullPath
                },
                {
                  hid: `og:type`,
                  property: 'og:type',
                  content: 'Accommodation'
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
}
</script>
