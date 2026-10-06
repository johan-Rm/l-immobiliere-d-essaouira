<template>
    <div class="footer-bottom" :class="params.bg_primary">

        <div class="container">
            <div class="row">
                <div class="wt-footer-bot-left">
                    <!-- mettre la date en dynamique -->
                    <span class="copyrights-text">© {{ new Date().getFullYear() }} {{ name }}</span>&nbsp;<a href="mailto:contact@graines-digitales.online" alt="CMS Headless, Site web Nuxt JS, Application Mobile Ionic"><span class="text-secondary">Designed By Graines Digitales</span></a><!-- pas de traduction sur copyright -->
                </div>
                <div class="wt-footer-bot-right">
                    <ul class="copyrights-nav pull-right">
                        <li>
                           <nuxt-link :to="getLocalizedRoute({
                              name: 'news-category-slug'
                              , params: {
                                  category: $tradLinkSlug($store.state.i18n.currentLocale, 'lagence', $store.state.i18n.routes['news-category-slug'].entities.category)
                                  , slug: $tradLinkSlug($store.state.i18n.currentLocale, 'informations-legales', $store.state.i18n.routes['news-category-slug'].entities.slug)
                              }
                          })">
                              {{ $t('Informations légales') }}
                          </nuxt-link>
                        </li>
                        <li>
                           <nuxt-link class="conseils" :to="getLocalizedRoute({
                              name: 'news-category-slug'
                              , params: {
                                  category: $tradLinkSlug($store.state.i18n.currentLocale, 'lagence', $store.state.i18n.routes['news-category-slug'].entities.category)
                                  , slug: $tradLinkSlug($store.state.i18n.currentLocale, 'honoraires', $store.state.i18n.routes['news-category-slug'].entities.slug)
                              }
                          })">
                              {{ $t('Honoraires') }}
                          </nuxt-link>
                        </li>
                        <li>
                           <nuxt-link :to="getLocalizedRoute({
                              name: 'page'
                              , params: {
                                  page: $tradLinkSlug($store.state.i18n.currentLocale, 'contact', 'webPage')
                              }
                          })">
                              {{ $t('Contactez nous') }}
                          </nuxt-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name:'CopyRight',
    props: {
        params: {
            type: Object
        }
    },
    computed: {
        ...mapState({
           name: state => state.organization.item.name
        })
    }
}
</script>
<style lang="scss" scoped>


@media only screen and (max-width:428px) {
  .text-secondary{
      display: inline-block;
  }
}

.site-footer .bg-primary .copyrights-nav li a.conseils {
  color: var(--color-secondary);
}
</style>