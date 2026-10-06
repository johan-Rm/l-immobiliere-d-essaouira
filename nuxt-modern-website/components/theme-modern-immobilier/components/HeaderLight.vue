<template>
    <header class="header-simple site-header header-style-1 nav-wide">
        <div class="main-bar-wraper color-fill">
            <div class="main-bar p-t10" :class="params.bg_primary">
                <div class="container">
                    <div class="logo-header">
                    	<div class="logo-header-inner logo-header-one">
                            <nuxt-link 
                            :to="getLocalizedRoute({ name: 'index' })"
                            class="header-link">
                                <img 
                                :width="$getImageSize(primaryImage, 'width')" 
                                :height="$getImageSize(primaryImage, 'height')"
                                class="img-responsive" 
                                :src="getPrimaryLogo(primaryImage)" 
                                :alt="primaryImage.alt" />
                            </nuxt-link>
                      </div>
                    </div>
                    <div class="extra-nav">
                        <div class="extra-cell"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'HeaderLight',
    head () {
      return {
        __dangerouslyDisableSanitizers: ['script'],
        script: [{ innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }]
      }
    },
    props: {
        params: {
            type: Object
        }
    },
    computed: {
        ...mapState({
            primaryImage: state => state.organization.item.primaryImage
        }),
        structuredData() {
            
            return { 
                "@context" : "http://schema.org",
                "@type": "Organization",
                "name": this.$store.state.organization.item.name,
                "legalName" : this.$store.state.organization.item.legalName,
                "url": this.$store.state.organization.item.url,
                "logo": this.$store.state.organization.item.primaryImage.url,
                "foundingDate": this.$store.state.organization.item.foundingDate,
                 // "founders": [
                 // {
                 // "@type": "Person",
                 // "name": "Patrick Coombe"
                 // },
                 // {
                 // "@type": "Person",
                 // "name": ""
                 // } ],
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": this.$store.state.organization.item.addresses.address,
                    // "addressLocality": "Delray Beach",
                    // "addressRegion": "FL",
                    "postalCode": this.$store.state.organization.item.addresses.postcode,
                    "city": this.$store.state.organization.item.addresses.city,
                    "addressCountry": this.$store.state.organization.item.addresses.country
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "contactType": "customer support",
                    "telephone": this.$store.state.organization.item.phone,
                    "email": this.$store.state.organization.item.email
                }
                //  ,
                //  "sameAs": [ 
                //  "http://www.freebase.com/m/0_h96pq",
                //  "http://www.facebook.com/elitestrategies",
                //  "http://www.twitter.com/delraybeachseo",
                //  "http://pinterest.com/elitestrategies/",
                //  "http://elitestrategies.tumblr.com/",
                //  "http://www.linkedin.com/company/elite-strategies",
                //  "https://plus.google.com/106661773120082093538"
                // ]}
            } 
        }
    },
    methods: {
        getPrimaryLogo(image) {
            return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + image.filename
        }
    }
}
</script>
<style type="text/css">

@media (min-width: 992px) and (max-width: 1199.98px) {
    .header-simple .logo-header {
        width: 180px;
    }

    .logo-header-inner img {
        max-width: 100%;
        max-height: 70px;
    }
}
</style>