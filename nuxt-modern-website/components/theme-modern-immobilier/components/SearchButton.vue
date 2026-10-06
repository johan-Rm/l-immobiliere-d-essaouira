<template>
<div class="search-button">
  <div class="extra-nav">
    <div class="extra-cell"> <a href="#search" class="site-search-btn" :title="$t('vous connaissez votre référence ?')"><i class="fa fa-search"></i></a>
    </div>
  </div>
  <div ref="search_container" id="search"> <span class="close"></span>
    <form role="search" id="searchform" class="radius-xl" novalidate @submit.prevent="handleSubmit">
      <div class="input-group">
        <input value="" v-model="reference" name="reference" type="search" :placeholder="$t('Saisir la référence')" /> <span class="input-group-btn"><button type="submit" class="search-btn"><i class="fa fa-search"></i></button></span>
      </div> <span class="error" v-if="error"> {{$t("Aucun bien ne correspond à cette référence")}} </span>
    </form>
  </div>
</div>                   
</template>
<script>
export default {
    name: 'SearchButton',
    data () {
        return{ reference: "",data: [] , cl : "hidden", error:false
        }
    },
    methods: {
    handleSubmit() {
      const locale = this.$store.state.i18n.currentLocale
      const params = { reference: this.reference, isActive: true }
      this.$axios.get('/accommodations'
      , { 
          params,
          validateStatus: function (status) {
            return status >= 200 && status < 500
        }
                })
      .then((response) => {
        if(response.data['hydra:member'].length == 1) {

          this.data = response.data['hydra:member'][0]
          if(this.data.nature.name === "Location") {
            this.$router.push(
              { 
                name: `renting-category-slug-${locale}`
                , params: 
                { 
                  category: this.$tradLinkSlug(this.$store.state.i18n.currentLocale
                  , this.data.type.slug, 'accommodationType')
                  , slug : this.$tradLinkSlug(this.$store.state.i18n.currentLocale, this.data.slug, 'accommodation') 
                } 
              }
            )
          } else {
            this.$router.push(
              { 
                name: `selling-category-slug-${locale}`
                , params: 
                { 
                  category: this.$tradLinkSlug(this.$store.state.i18n.currentLocale, this.data.type.slug, 'accommodationType')
                  , slug : this.$tradLinkSlug(this.$store.state.i18n.currentLocale, this.data.slug, 'accommodation') 
                } 
              }
            )
          }
          this.$refs.search_container.classList.remove('open')

        } else {
          this.error = true
        }

      }).catch(error => {
          this.error = true
          console.log(error)
          console.log('error store SearchButton.vue')
      })
    }  
  }
}
</script>
<style lang="scss" scoped>


.error{
    color: var(--color-secondary);
    text-align: center;
    display: block;
    font-size: 15px;
}
::placeholder{
    color: var(--color-secondary);
}
#searchform  {
  border: 1px solid #fff;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (max-width: 575.98px) { 
  .search-button .extra-nav {
    display: none; 
  }
}

@media (min-width: 992px) and (max-width: 1199.98px) {
  .search-button .extra-nav {
    /* display: none; */
  }
}

</style>