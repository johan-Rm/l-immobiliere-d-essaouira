<template>
    <div class="page-wraper">
        <ButtonWhatsApp/>
      <PageHeader/>
      <div class="page-content">
          <DemoNotice/>
          <div class="page-headband">
            <!-- <TheSlider/> -->
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
import ButtonWhatsApp from '~/components/ButtonWhatsApp'
import PageHeader from '~/components/TheHeader'
import TheSlider from '~/components/TheSlider'
import PageFooter from '~/components/TheFooter'
import TheScrollTop from '~/components/TheScrollTop'
export default {
    name: 'default',
    components : {
        DemoNotice: () => import('../components/DemoNotice'),
        PageHeader,
        TheSlider,
        PageFooter,
        TheScrollTop,
        ButtonWhatsApp
    },
    head () {

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
                    content: 'index, follow' // ⚡️ par défaut
                },
                {
                    hid: 'x-robots-tag',
                    name: 'x-robots-tag',
                    content: 'index, follow' // ⚠️ ça existe en HTML, mais Google ne le lit qu’en header HTTP
                }
            ]
        }
    }
}
</script>

