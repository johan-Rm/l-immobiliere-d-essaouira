<template>
    <header class="header-simple site-header header-style-1 nav-wide"  :class="deviceMode">
        <div class="sticky-header main-bar-wraper is-fixed color-fill">
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
                                    :alt="logoName"
                                />
                            </nuxt-link>

                      </div>

                    </div>
                    <button
                        @click="buttonMenuClick()"
                        v-show="toggle"
                        data-target=".header-nav"
                        data-toggle="collapse"
                        type="button"
                        class="navbar-toggle collapsed"
                        :class="cssRotate"
                        >
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar left"></span>
                        <span class="icon-bar middle"></span>
                        <span class="icon-bar right"></span>
                    </button>

                    <!-- <button
                        @click="buttonMenuClick()"
                        v-show="toggle"
                        type="button"
                        class="navbar-toggle collapsed"
                        :class="cssRotate"
                        >
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar left"></span>
                        <span class="icon-bar middle"></span>
                        <span class="icon-bar right"></span>
                    </button> -->

                    <div v-show="toggle" class="extra-nav">
                        <div class="extra-cell">
                            <LangSwitcher/>
                        </div>
                    </div>
                    <SearchButton/>
                    <SocialMedia :params="params"/>
                    <NavigationDefault/>
                </div>
            </div>
        </div>

    </header>
</template>
<script>
import { trigger_button_mobile_nav } from '~/plugins/custom_transform_to_export.js'
import { initSticky_header, init_site_search } from '~/plugins/custom_transform_to_export.js'
import { mapState } from 'vuex'
export default {
    name: 'HeaderSimple',
    head () {
      return {
        __dangerouslyDisableSanitizers: ['script'],
        script: [{ innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }]
      }
    },
    components : {
        LangSwitcher: () => import('./TheLangSwitcher'),
        NavigationDefault: () => import('./NavigationDefault'),
        SocialMedia: () => import('./SocialMedia'),
        SearchButton: () => import('./SearchButton')
    },
    props: {
        params: {
            type: Object
        }
    },
    data () {
      return{
        // closeClass: ""
        toggle: false,
        deviceMode: '',
        cssRotate: ''
      }
    },
    computed: {
        ...mapState({
            primaryImage: state => state.organization.item.primaryImage,
            logoName: state => state.organization.item.name
        }),
        isDisabled() {

            return false
        },
        structuredData() {
            return {
                "@context" : "http://schema.org",
                "@type": "Organization",
                "name": this.$store.state.organization.item.name,
                "legalName" : this.$store.state.organization.item.legalName,
                "url": this.$store.state.organization.item.url,
                "logo": this.getPrimaryLogo(this.$store.state.organization.item.primaryImage),
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
        buttonMenuClick() {
            // console.log('buttonMenuClick')
            let nav = document.querySelector(".main-bar .navigation-default")
            if(!nav.classList.contains("in")) {
                this.cssRotate = 'rotate'
            } else {
                this.cssRotate = ''
            }

            let mobile = false
            if(this.$device.isMobile
                || (this.$device.isTablet && window.innerWidth < 769)
            ) {
                mobile = true
                // this.$nextTick(function(){
                    trigger_button_mobile_nav(mobile)
                // }.bind(this))
            }
        },
        getPrimaryLogo(image) {

            return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + image.filename
        },
        checkCloseClass() {
            // let nav = document.querySelector(".main-bar .navigation-default")
            // console.log('checkCloseClass')
            // // console.log(document.body.classList)
            // // console.log('document.getElementsByTagName')
            // // console.log(document.querySelector(".main-bar .navigation-default"))
            // // console.log(nav.classList)
            // // console.log(nav.classList.contains("in"))
            // if(nav.classList.contains("in")) {
            //     console.log('not found')
            //     // document.body.classList.remove("menu-open")
            //     this.$store.commit('organization/setBodyClass', "")
            //     this.$store.commit('organization/setCloseNavbarClass', "")
            //     // this.closeClass = ""
            // } else {
            //     console.log('found')
            //     // this.closeClass = "rotate"

            //     if("menu-close" !== this.$store.state.organization.closeClass) {
            //         this.$store.commit('organization/setBodyClass', "menu-open")
            //         this.$store.commit('organization/setCloseNavbarClass', "rotate")
            //     } else {

            //         this.$store.commit('organization/setCloseClass', "")
            //     }

            //     // document.body.classList.add("menu-open")
            // }
        }
    },
    created() {


        // console.log("this.deviceMode")
        // console.log(this.deviceMode)
        // console.log(this.$device)

    },
    mounted () {
        this.toggle = true
        if(this.$device.isMobile
            || (this.$device.isTablet && window.innerWidth < 769)
        ) {
            this.deviceMode = 'mode-mobile'
        }
        // let mobile = false
        // if(this.$device.isMobile
        //     || (this.$device.isTablet && window.innerWidth < 769)
        // ) {
        //     mobile = true
        // }
        //
        // this.closeClass = ""
        // this.$nextTick(function(){
        //
        //     , initSticky_header()
        //     , init_site_search()
        // }.bind(this))
    }
}
</script>
<style type="text/css">


.navbar-toggle {

  border: none;
  margin-top: 14px;
  margin-bottom: 14px;
  margin-left: 10px;
  margin-right: 10px;
  padding: 12px 10px;
  width: 22px;
  height: 36px;

}
.navbar-toggle .icon-bar {
    background: #000;
    height: 3px;
    border-radius: 0;
    -webkit-border-radius: 0;
    position: absolute;
    top: 12px;
    left: 0px;
}

.navbar-toggle .icon-bar + .icon-bar {
     margin-top: 0px;
}
.navbar-toggle .icon-bar.middle {
    top: 20px;
}
.navbar-toggle .icon-bar.right {
    top: 28px;
}

.navbar-toggle.rotate .icon-bar.left
/*, body.menu-open .navbar-toggle. .icon-bar.left*/
{
    transform: rotate(135deg);
    top: 18px;
}
.navbar-toggle.rotate .icon-bar.middle
/*, body.menu-open .navbar-toggle .icon-bar.middle */
{
   display:none;
}
.navbar-toggle.rotate .icon-bar.right
/*, body.menu-open .navbar-toggle .icon-bar.right */
{
    transform: rotate(-135deg);
    top: 18px;
}


.has-child .submenu-toogle {
    color: #fff;
}



/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (max-width: 767.98px) {
    .sticky-wrapper {
        height: auto !important;
    }

    .site-header .lang_switcher[data-v-ace2bf60] {
        padding: 0 0 0 0;
    }

    .site-search-btn {
        padding: 0px;
        font-size: 18px;
    }

    .logo-header-inner {
        padding: 1rem 4rem 1rem 0;
    }
}

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .nav-wide .container {
    	padding: 0px 80px 0 40px;
    }
}

@media (max-width: 991.98px) {

  /*.logo-header-one img {
    width: 50px;
  }*/
  /* .nav-wide .container {
      padding: 0px 80px 0 40px;
  } */
}

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
