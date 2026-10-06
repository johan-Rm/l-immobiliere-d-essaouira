<template>
 <div class="navigation-default header-nav navbar-collapse collapse">
    <ul class="nav navbar-nav">
        <li class="elem-home" @click="itemClick('li')">
            <nuxt-link class="home" :to="getLocalizedRoute({ name: 'index' })">
                {{ $t('Accueil') }}
            </nuxt-link>
        </li>
        <li class="first-level" @click="itemClick('li')">
            <nuxt-link :to="getLocalizedRoute({ name: 'page'
           , params: {
                page: $tradLinkSlug($store.state.i18n.currentLocale, 'lagence', 'webPage')
               }
             })">
               {{ $t('L\'agence') }}
           </nuxt-link>
        </li>
        <li class="first-level" @click="itemClick('li')">
           <nuxt-link
            :class="activeSellingCss"
            :to="getLocalizedRoute({ name: 'selling' })">
               {{ $t('Vente') }}
           </nuxt-link>
        </li>
        <li class="first-level" @click="itemClick('li')">
            <nuxt-link
                :class="activeRentingCss"
                :to="getLocalizedRoute({ name: 'renting'/*, query: { page: 1 }*/ })">
               {{ $t('Location') }}
            </nuxt-link>
         </li>
         <li>
            <nuxt-link
            :class="activeServiceCss"
            :to="getLocalizedRoute({ name: 'page'
            , params: {
                page: $tradLinkSlug($store.state.i18n.currentLocale, 'nos-services', 'webPage')
                }
            })">
                {{ $t('Nos services') }}
            </nuxt-link>
            <ul class="sub-menu" >
                <li
                v-for="(value, index) in article_nosServices"
                :key="index"
                @click="itemClick('sub_menu_li')"
                 >
                    <nuxt-link class="capitalyze" :to="getLocalizedRoute({
                        name: 'news-category-slug'
                        , params: {
                            category: $tradLinkSlug($store.state.i18n.currentLocale, 'nos-services', 'tag')
                            , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                        }
                    })">
                        {{ $t( value.alternativeHeadline ) }}
                    </nuxt-link>
                </li>
            </ul>
        </li>
        <li class="first-level" @click="itemClick('li')">
            <nuxt-link :to="getLocalizedRoute({
                name: 'page'
                , params: {
                    page: $tradLinkSlug($store.state.i18n.currentLocale, 'contact', 'webPage')
                }
            })">
                {{ $t('Contact') }}
            </nuxt-link>
        </li>
        <li class="first-level" @click="itemClick('li')">
            <nuxt-link :to="getLocalizedRoute({
                name: 'page'
                , params: {
                    page: $tradLinkSlug($store.state.i18n.currentLocale, 'faq', 'webPage')
                }
            })">
                {{ $t('FAQ') }}
            </nuxt-link>
        </li>
    </ul>
</div>
</template>
<script>
import { init_mobile_nav } from '~/plugins/custom_transform_to_export.js'
import { trigger_mobile_nav } from '~/plugins/custom_transform_to_export.js'
import { mapState } from 'vuex'
export default {
    name:'NavigationDefault',
    computed: {
        ...mapState({
            article_nosServices: state => state.menu.article_nosServices
        }),
        activeSellingCss() {
            const routeName = this.$route.name.substring(0, this.$route.name.lastIndexOf("-"));
            if('selling-category' == routeName) {
                return 'nuxt-link-exact-active active-link'
            }

            return ''
        },
        activeRentingCss() {
            const routeName = this.$route.name.substring(0, this.$route.name.lastIndexOf("-"));
            if('renting-category' == routeName) {
                return 'nuxt-link-exact-active active-link'
            }

            return ''
        }
    },
    data () {
        return{
            activeServiceCss: ''
        }
    },
    methods: {
      itemClick(elem) {
        this.activeServiceCss = ''
        this.$store.commit('organization/setHeadbandIsLoaded', false)
        if(this.$route.params.hasOwnProperty('category')) {
            var labels = ['nos-services', 'our-services']
            if(labels.includes(this.$route.params.category)) {
                this.activeServiceCss = 'nuxt-link-exact-active active-link'
            }
        }

        let mobile = false
        if(this.$device.isMobile
            || (this.$device.isTablet && window.innerWidth < 769)
        ) {
            mobile = true
            trigger_mobile_nav(mobile)
        }
      }
  },
  mounted () {
      let mobile = false
      if(this.$device.isMobile
          || (this.$device.isTablet && window.innerWidth < 769)
      ) {
          mobile = true
          this.$nextTick(function(){
              init_mobile_nav(mobile)
          }.bind(this))
      }

  }
}
</script>
<style scoped>
.sub-menu a {
    text-align:left;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (min-width: 992px) and (max-width: 1199.98px) {
    .navigation-default .nav .elem-home {
      display: none;
    }
}
</style>
