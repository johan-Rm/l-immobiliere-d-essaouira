<template>
    <div class="page-wraper">
      <PageHeader/>
      <div class="page-content">
          <div class="page-headband">
            <TheHeadband :data="headband"/>
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
import PageHeader from '~/components/TheHeader'
import TheHeadband from '~/components/TheHeadband'
import PageFooter from '~/components/TheFooter'
export default {
    name: 'quatreCentQuatre',
    components : {
        PageHeader,
        TheHeadband,
        PageFooter
    },
    computed: {
        headband () {
            return {
                primaryImage: { filename: 'immobiliere-essaouira.webp' },
                pushForward: '404 page not found',
                breadcrumb: []
            }
        }
    }
}

</script>

<style lang="scss">
@import '~/assets/scss/main.scss';
</style>
