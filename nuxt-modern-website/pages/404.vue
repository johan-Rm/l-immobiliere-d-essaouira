<template>
  <div class="error-page">
    <div class="alert alert-danger alert-xl">
        <strong>{{ $t('Oops!') }}</strong>{{ $t(' page non trouvé !') }}
    </div>
    <p>
      <nuxt-link :to="getLocalizedRoute({ name: 'index' })">
        {{ $t('Retour à la page d\'accueil') }}
    </nuxt-link>
   </p>
  </div>
</template>

<script>
// import utils from '~/plugins/utils.js'
export default {
    name: 'quatreCentQuatre',
    layout: '404',
    head () {
        return {
            htmlAttrs: {
              lang: this.$store.state.i18n.currentLocale,
            }
        }
    }    
}
</script>
<style scoped>
.error-page {
  text-align: center;
  margin: 30px 0;
  padding:0 10%;
}

.error-page a {
  text-decoration: none;
  color: #000;
}

.error-page a:hover,
.error-page a:active {
  color: #000;
}
</style>
