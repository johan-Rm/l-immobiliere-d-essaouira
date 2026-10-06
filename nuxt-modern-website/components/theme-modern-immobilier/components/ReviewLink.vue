<template>
    <center>
        <button type="button" class="col-md-12 reviewlink site-button text-uppercase letter-spacing-1 font-weight-800">
          <i class="fa fa-star"></i>
          <a 
          target="_BLANK" 
          class="avis-client" 
          href="https://www.google.com/maps/place/L'Immobili%C3%A8re+d'Essaouira/@31.5043024,-9.7576013,17z/data=!4m8!3m7!1s0xdad9a486d21cf41:0xe6d4967573947fe0!8m2!3d31.5043024!4d-9.7576013!9m1!1b1!16s%2Fg%2F1tdg4kyw?entry=ttu">
            {{ $t('Avis clients') }}
          </a>
          <i class="fa fa-star"></i>
        </button>
    </center>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'ReviewLink',
    computed: {
        ...mapState({
            reviewLink: state => state.footer.reviewLinks
        })
    },
    methods: {
      getReviewLink: function (reviewLink) {
        var links = reviewLink
        if(this.$device.isMobile) {
            return links.mobile
        } 

        return links.desktop
      }
    }
  }

</script>
<style lang="scss" scoped>

.reviewlink {
    color: var(--color-secondary);
    background: inherit;
}
.site-button, .site-button-secondry {
  font-size: 12px;
  cursor: pointer;
  line-height: 1.42857;
}
.reviewlink a {
    color: var(--color-secondary);
    font-weight: bold;
}
</style>
