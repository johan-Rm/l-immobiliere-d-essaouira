<template>
    <div class="page-wraper" :class="getRouteName">
      <ButtonWhatsApp/>
      <PageHeader/>
      <div class="page-content">
          <DemoNotice/>
          <div class="page-headband">
            <template v-if="checkRoute">
              <TheSlider/>  
            </template>
            <template v-else>
              <TheHeadband :data="headband"/>  
            </template>
          </div>
          <div class="page-content-wraper">
              <nuxt/>
          </div>
      </div>
      <PageFooter/>
      <TheScrollTop/>
    </div>
</template>

<script>
export default {
    name: 'page',
    components : {
        DemoNotice: () => import('../components/DemoNotice'),
        PageHeader: () => import('../components/TheHeader'),
        TheHeadband: () => import('../components/TheHeadband'),
        TheSlider: () => import('../components/TheSlider'),
        PageFooter: () => import('../components/TheFooter'),
        TheScrollTop: () => import('../components/TheScrollTop'),
        ButtonWhatsApp: () => import('../components/ButtonWhatsApp')
    },
    head() {

      const base = (process.env.URL_WEBSITE || 'https://www.immobiliere-essaouira.com').replace(/\/$/, '')

      // nettoie le path : sans query/hash, sans slash final sauf '/'
      let p = (this.$route.fullPath || '/').split('#')[0].split('?')[0]
      if (p.length > 1 && p.endsWith('/')) p = p.slice(0, -1)

      const canonical = `${base}${p}`

      // locale active dans ton store i18n
      const locale = this.$store.state.i18n.currentLocale || 'fr'

      // calcule les deux versions
      const hrefFr = `${base}${locale === 'fr' ? p : p.replace(/^\/en(\/|$)/, '/')}`
      const hrefEn = `${base}${locale === 'en' ? p : (p === '/' ? '/en/' : `/en${p}`)}`

      return {
        bodyAttrs: {
          class: this.$store.state.organization.bodyClass
        },
        link: [
          {
            rel: 'canonical',
            href: process.env.URL_WEBSITE + this.$route.path
          },
          { hid: 'alt-fr',  rel: 'alternate', hreflang: 'fr', href: hrefFr },
          { hid: 'alt-en',  rel: 'alternate', hreflang: 'en', href: hrefEn },
          { hid: 'alt-def', rel: 'alternate', hreflang: 'x-default', href: hrefFr }
        ],
        meta: [
            {
                hid: 'robots',
                name: 'robots',
                content: 'index, follow'
            }
        ]
      }
    },
    computed: {
      getRouteName() {
        return this.$route.name.substring(0, this.$route.name.lastIndexOf("-"))
      },
      checkRoute() {
        const regex = /^\/en\/?$/ig;
        const matches = regex.exec(this.$route.path);
     
        return '/' == this.$route.path || null !== matches
      },
      headband () {
        const routeName = this.$route.name.substring(0, this.$route.name.lastIndexOf("-"));
        switch (routeName) {
          case 'renting-category-slug':
            
            var primaryImage = null
            if(null !== this.$store.state.accommodations.item.primaryImage) {
              primaryImage = this.$store.state.accommodations.item.primaryImage
            }
            var secondaryImage = null
            if(null !== this.$store.state.accommodations.item.secondaryImage) {
              secondaryImage = this.$store.state.accommodations.item.secondaryImage
            }

            var pushForward = this.$store.state.accommodations.item.headline
            var alternativePushForward = { // nature, type, surface, place, price
              nature: this.$store.state.accommodations.item.nature.name,
              type: this.$store.state.accommodations.item.type.name,
              floorSize: this.$store.state.accommodations.item.floorSize,
              place: this.$store.state.accommodations.item.place.name,
              price: this.$store.state.accommodations.item.price
            }

            var breadcrumb = [
              { 
                slug: '/' + this.$store.state.i18n.currentLocale,
                name: this.$i18n.t('accueil'),
                route: {
                  name: 'index'
                }
              },
              { 
                slug: this.$i18n.t('Location'),
                name: this.$i18n.t('Location'),
                route: {
                  name: 'renting'
                }
              },
              { 
                slug: this.$store.state.accommodation_types.item.slug,
                name: this.$store.state.accommodation_types.item.name,
                route: {
                    name: 'renting-category',
                    params: {
                        category: this.$tradLinkSlug(
                            this.$store.state.i18n.currentLocale
                            , this.$store.state.accommodation_types.item.slug
                            , 'accommodationType'
                        )
                    }
                }
              }
            ]
            break;
          case 'selling-category-slug':
            
            var primaryImage = null
            if(null !== this.$store.state.accommodations.item.primaryImage) {
              primaryImage = this.$store.state.accommodations.item.primaryImage
            }
            var secondaryImage = null
            if(null !== this.$store.state.accommodations.item.secondaryImage) {
              secondaryImage = this.$store.state.accommodations.item.secondaryImage
            }

            var pushForward = this.$store.state.accommodations.item.headline
            var alternativePushForward = { // nature, type, surface, place, price
              nature: this.$store.state.accommodations.item.nature.name,
              type: this.$store.state.accommodations.item.type.name,
              floorSize: this.$store.state.accommodations.item.floorSize,
              place: this.$store.state.accommodations.item.place.name,
              price: this.$store.state.accommodations.item.price
            }
            var breadcrumb = [
              { 
                slug: '/' + this.$store.state.i18n.currentLocale,
                name: this.$i18n.t('accueil'),
                route: {
                  name: 'index'
                }
              },
              { 
                slug: this.$i18n.t('Vente'),
                name: this.$i18n.t('Vente'),
                route: {
                  name: 'selling'
                }
              },
              { 
                slug: this.$store.state.accommodation_types.item.slug,
                name: this.$store.state.accommodation_types.item.name,
                route: {
                    name: 'selling-category',
                    params: {
                        category: this.$tradLinkSlug(
                            this.$store.state.i18n.currentLocale
                            , this.$store.state.accommodation_types.item.slug
                            , 'accommodationType'
                        )
                    }
                }
              }
            ]
            break;
          case 'selling-category':
            var primaryImage = null
            if(null !== this.$store.state.pages.item.primaryImage) {
              primaryImage = this.$store.state.pages.item.primaryImage
            }
            var secondaryImage = null
            if(null !== this.$store.state.pages.item.secondaryImage) {
              secondaryImage = this.$store.state.pages.item.secondaryImage
            }

            var pushForward = this.$store.state.pages.item.pushForward
            var alternativePushForward = {}
            var breadcrumb = [
              { 
                slug: '/' + this.$store.state.i18n.currentLocale,
                name: this.$i18n.t('accueil'),
                route: {
                  name: 'index'
                }
              },
              { 
                slug: this.$i18n.t('Vente'),
                name: this.$i18n.t('Vente'),
                route: {
                  name: 'selling'
                }
              },
              { 
                slug: this.$store.state.accommodation_types.item.slug,
                name: this.$store.state.accommodation_types.item.name,
                route: {
                    name: 'selling-category',
                    params: {
                        category: this.$tradLinkSlug(
                            this.$store.state.i18n.currentLocale
                            , this.$store.state.accommodation_types.item.slug
                            , 'accommodationType'
                        )
                    }
                }
              }
            ]
          
            break;  
          case 'renting-category':
            var primaryImage = null
            if(null !== this.$store.state.pages.item.primaryImage) {
              primaryImage = this.$store.state.pages.item.primaryImage
            }
            var secondaryImage = null
            if(null !== this.$store.state.pages.item.secondaryImage) {
              secondaryImage = this.$store.state.pages.item.secondaryImage
            }

            var pushForward = this.$store.state.pages.item.pushForward
            var alternativePushForward = {}
            var breadcrumb = [
              { 
                slug: '/' + this.$store.state.i18n.currentLocale,
                name: this.$i18n.t('accueil'),
                route: {
                  name: 'index'
                }
              },
              { 
                slug: this.$i18n.t('Location'),
                name: this.$i18n.t('Location'),
                route: {
                  name: 'renting'
                }
              },
              {
                slug: this.$store.state.accommodation_types.item.slug,
                name: this.$store.state.accommodation_types.item.name,
                route: {
                    name: 'renting-category',
                    params: {
                        category: this.$tradLinkSlug(
                            this.$store.state.i18n.currentLocale
                            , this.$store.state.accommodation_types.item.slug
                            , 'accommodationType'
                        )
                    }
                } 
              }
            ]
          
            break;  
          case 'news-category-slug':
            var primaryImage = null
            if(null !== this.$store.state.articles.item.primaryImage) {
              primaryImage = this.$store.state.articles.item.primaryImage
            }
            var secondaryImage = null
            if(null !== this.$store.state.articles.item.secondaryImage) {
              secondaryImage = this.$store.state.articles.item.secondaryImage
            }

            var pushForward = this.$store.state.articles.item.pushForward
            var alternativePushForward = {}
            var breadcrumb = [
              { 
                slug: '/' + this.$store.state.i18n.currentLocale,
                name: this.$i18n.t('accueil'),
                route: {
                    name: 'index'
                }
              },
              // { 
              //   slug: store.state.articles.item.category.slug,
              //   name: store.state.articles.item.category.name
              // },
               { 
                name: this.$i18n.t(this.$store.state.articles.item.category.name),
                route: {
                  name: 'page',
                  params: {
                    page: this.$tradLinkSlug(
                      this.$store.state.i18n.currentLocale
                      , this.$store.state.articles.item.category.slug
                      , 'webPage'
                    )
                  }
                }
              },
              { 
                slug: this.$store.state.articles.item.slug,
                name: this.$store.state.articles.item.alternativeHeadline,
                route: {
                    name: 'news-category-slug',
                    params: {
                        category: this.$tradLinkSlug(
                            this.$store.state.i18n.currentLocale
                            , this.$store.state.articles.item.category.slug
                            , 'tag'
                        )
                        , slug: this.$tradLinkSlug(
                            this.$store.state.i18n.currentLocale
                            , this.$store.state.articles.item.slug
                            , 'article'
                        )
                    }
                } 
              }
            ]
            
            break;
          default:
            var primaryImage = null
            if(null !== this.$store.state.pages.item.primaryImage) {
              primaryImage = this.$store.state.pages.item.primaryImage
            }
            var secondaryImage = null
            if(null !== this.$store.state.pages.item.secondaryImage) {
              secondaryImage = this.$store.state.pages.item.secondaryImage
            }

            var pushForward = this.$store.state.pages.item.pushForward
            var alternativePushForward = {}
            var breadcrumb = [
              { 
                slug: '/' + this.$store.state.i18n.currentLocale,
                name: this.$i18n.t('accueil'),
                route: {
                  name: 'index'
                }
              },
              { 
                slug: this.$store.state.pages.item.slug,
                name: this.$i18n.t(this.$store.state.pages.item.headline),
                route: {
                  name: 'page',
                  params: {
                    page: this.$tradLinkSlug(
                      this.$store.state.i18n.currentLocale
                      , this.$store.state.pages.item.slug
                      , 'webPage'
                    )
                  }
                }
              } 
            ]
            
        }
// console.log('alternativePushForward')
// console.log(alternativePushForward)
        return {
            primaryImage: primaryImage,
            secondaryImage: secondaryImage,
            pushForward: pushForward,
            alternativePushForward: alternativePushForward,
            breadcrumb: breadcrumb
        }
      }
    }
}
</script>
