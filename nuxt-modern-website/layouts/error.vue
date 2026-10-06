<template>
  <div class="error-page">
    <div class="alert alert-danger alert-xl">
        <strong>{{ $t('Oops!') }}</strong>{{ $t(' quelque chose s\'est mal passé!') }}
    </div>
    <p>
    	<nuxt-link :to="getLocalizedRoute({ name: 'index' })">
    		{{ $t('Retour à la page d\'accueil') }}
		</nuxt-link>
	 </p>
  </div>
</template>

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
  color: salmon;
}
</style>
