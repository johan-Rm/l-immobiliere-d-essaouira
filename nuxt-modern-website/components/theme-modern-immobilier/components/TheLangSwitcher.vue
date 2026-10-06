<template>
  <div class="lang-switcher">
      <nuxt-link class="lang_switcher"
          v-for="locale in locales"
          v-if="locale.code !== $store.state.i18n.currentLocale"
          :key="locale.code"
          :to="getSwitchLocaleRoute(locale.code)">
          <!-- {{ locale.name }} -->
          <img 
   
          :src="getImage(locale.name)" 
          :alt="locale.name" class="lazyload__" />
      </nuxt-link>
  </div>
</template>

<script>
import toLower from 'lodash.tolower'

export default {
  name: 'LangSwitcher',
   methods: {
      getImage(lang) {
          return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + toLower(lang) + "-flag-square-icon-32.png"
      }
  }
}
</script>

<style scoped>
  
.lang-switcher {
  width: 40px;
  text-align: center;
}

.site-header .lang_switcher { padding: 0 15px 0 0; }
.site-header .lang_switcher img { width: 18px; height: 18px; margin-bottom: 3px; }
@media (max-width: 767.98px) { 
  .site-header .lang_switcher img { width: 20px; height: 20px; }
  .site-header .lang_switcher { padding: 0 0 0 0; }
}

</style>