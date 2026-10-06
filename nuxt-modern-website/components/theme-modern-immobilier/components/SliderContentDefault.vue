<template>
<div class="slider-content-default">
  <transition name="fade">
  <div v-if="show" class="container-caption">
    <div class="title-caption">{{ $t('Spécialiste') }} <span class="text-secondary">{{ $t('en immobilier') }}</span> {{ $t('depuis 2004') }}</div>
    <div class="text-caption">{{ $t('Achats-Ventes, estimations, locations longue durée, locations saisonnières, conciergerie et gestion') }}</div>
  </div>
  </transition>
  
  <div class="quick-search">
    <span class="simili-title"><strong>{{ $t('Rechercher') }}</strong>
      <br> {{$t('votre bien') }} 
    </span>
    <form @submit.prevent="handleSubmit">
      <div class="container-fluid">
        <ul class="onglet-liste">
          <li>
            <input type="radio" v-model="form.type" id="purchase" value="vente" checked="checked">
            <label class="bg-primary" for="purchase">{{ $t('Achat') }}</label>
          </li>
          <li>
            <input type="radio" v-model="form.type" id="rental" value="longue-duree">
            <label for="rental">{{ $t('Location') }}</label>
          </li>
          <li>
            <input type="radio" v-model="form.type" id="holidays" value="saisonniere">
            <label for="holidays">{{ $t('Vacances') }}</label>
          </li>
        </ul>
        <div class="input-group">
          <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <input aria-label="Budget mini" v-model="form.pricemin" type="text" class="form-control col-md-4" :placeholder="$t('Budget mini.')">
          </div>
          <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <input aria-label="Budget maxi" v-model="form.pricemax" type="text" class="form-control col-md-4" :placeholder="$t('Budget maxi.')">
          </div>
          <div class="form-group col-md-12">
            <span class="input-group-btn">
              <button type="submit" class="site-button"><i class="fa fa-search"></i> {{ $t('Lancer votre recherche') }}</button>
            </span>
          </div>
        </div>
        <a href="#search">
          <button class="m-b15 site-button-secondry letter-spacing-2" type="button">{{ $t('Vous connaissez votre référence ?') }}</button>
        </a>
      </div>
    </form>
  </div>

</div>
</template>
<script>
import { mapState } from 'vuex'
import { init_site_search } from '~/plugins/custom_transform_to_export.js'
export default {
    name: 'SliderContentDefault',
    data: function() {
        return {
            form: {
                type: "vente",
                pricemax: "",
                pricemin: ""
            }
        }
    },
    computed: {
      ...mapState({
        show(state) {

          return state.organization.sliderShow
        }
      })
    },
    methods: {
      handleSubmit() {
        this.$store.commit('organization/setContainerResult', true)
        const locale = this.$store.state.i18n.currentLocale
        const type = this.form.type
        if(this.form.pricemin > 0) {
            this.$store.commit('search/setMinBudget', this.form.pricemin)
        } else {
            this.form.pricemin = ""
        }
        if(this.form.pricemax > 0) {
            this.$store.commit('search/setMaxBudget', this.form.pricemax)
        } else {
            this.form.pricemax = ""
        }
        if(type !== "vente") {
            this.$store.commit('search/setNature', 'location')
            this.$store.commit('search/setDuration', this.form.type)
            this.$router.push({ name: `renting-${locale}` })
        } else {
            this.$store.commit('search/setNature', this.form.type)
            this.$router.push(`/${type}`)
        }
      }
    },
    mounted () {
      // console.log('mounted slider')
      setTimeout(() => this.$store.commit('organization/setSliderShow', true), 100)
      this.$nextTick(function(){ init_site_search() }.bind(this))
    },
    updated() {
      // console.log('updated slider')
      setTimeout(() => this.$store.commit('organization/setSliderShow', true), 100)
      this.$nextTick(function(){ init_site_search() }.bind(this))
    }
}
</script>
<style lang="scss" scoped>

/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all .9s ease;
}
.slide-fade-leave-active {
  transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(-10px);
  opacity: 0;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .8s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

.quick-search {
    right: 140px;
    width: 320px;
    position: absolute;
    padding: 0;
    top: 0;
    height: 100%;
    z-index: 1;
    background: linear-gradient(180deg,rgba(0,0,0,.65) 0,#000);
}

.full {
  position: absolute;
  height: auto;
  bottom: 0;
  left: 0;
  margin: auto;
  display: block;
}

.zoom {
  animation: scale 40s linear infinite;
}
  
@keyframes scale {
  50% {
    -webkit-transform:scale(1.2);
    -moz-transform:scale(1.2);
    -ms-transform:scale(1.2);
    -o-transform:scale(1.2);
    transform:scale(1.2);
  }
}
.simili-title {
    font-size: 22px;
    color: #fff;
    display: block;
    font-family: Source Sans Pro,sans-serif;
    line-height: 1;
    margin: 0 0 20px;
        margin-bottom: 20px;
    text-align: center;
    text-transform: uppercase;
    margin-bottom: 40px;
    margin-top: 40px;
}
.onglet-liste {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
}
ol, ul {
    list-style: none;
}
.onglet-liste input:checked + label {
    background-color: var(--color-secondary);
    cursor:pointer;
    // color: var(--color-secondary);
    color: var(--color-primary);
}
.onglet-liste label {
  cursor:pointer;
  background-color: var(--color-secondary-light);
  color: #fff;
  font-size: 14px;
  padding: 7px 12px !important;
  position: relative;
  text-align: center;
}

input[type="checkbox"] + label::before, input[type="radio"] + label::before{
    border:none;
    background-color: #fff0;
}
input[type="radio"]:checked + label::before {
    border: none;
}
.onglet-liste label::after {
    height: 0;
    width: 0;
    border: 7.5px solid transparent;
    border-top-color: transparent;
    content: "";
    display: block;
    position: absolute;
    left: 50%;
    margin-left: -7.5px;
    top: 100%;
}

.onglet-liste input:checked + label::after {
    height: 0;
    width: 0;
    border: 7.5px solid transparent;
    border-top-color: transparent;
    border-top-color: var(--color-secondary);

}
.form-control {
    color: #fff;
    background-color: #fff0;
    border: none;
    border-bottom: 1px solid
}
.input-group {
    padding-bottom: 30px;
}
.site-button {
    background-color: var(--color-secondary);
    padding: 9px 15px;
    width: 100%;
    margin-top: 25px;
    color: var(--color-primary);
    font-weight: 600;
}
.input-group-btn{
text-align: center
}
.site-button:hover{
    background-color: var(--color-secondary);
    color:#fff;
    padding: 9px 15px;
}
.site-button-secondry{
    font-size: 10px;
}
.form-group{
    padding-right: 0px;
padding-left: 0px;
}
.form-group {
    margin-bottom: 10px;
}
.site-button .site-button-secondry {
    width: 100%;
}
.site-button-secondry {
    background-color: #efefef00;
}

.site-button-secondry {
    color: #fff;
}/*media query----------------------------------------*/
@media only screen and (max-width: 840px){

.simili-title {
    margin-bottom: 0px;

}
}
@media only screen and ( max-width:800px ){

.container-caption{
display: none;
}
}
@media only screen and (max-width: 640px) {

    .quick-search {
        right: 0px;
    width: 100%;
    }
    form .container-fluid {
    padding-right: 25%;
    padding-left: 25%;
}
.simili-title {
    margin-bottom: 0px
}
.site-button {
    margin-top: 10px;
}
}
@media only screen and (max-width: 544px) {
.quick-search {
        right: 0px;
    width: 100%;
    }
   form .container-fluid {
    padding-right: 20%;
    padding-left: 20%;
}
.simili-title{
    margin-top: 8px;
}
.site-button {
    margin-top: 7px;
}
}
@media only screen and (max-width: 458px) {
 form .container-fluid {
    padding-right: 15%;
    padding-left: 15%;
}
.simili-title{
    margin-top: 8px;
}
.site-button {
    margin-top: 7px;
}
}
@media only screen and (max-width: 390px) {
 form .container-fluid {
    padding-right: 10%;
    padding-left: 10%;
}
.simili-title{
    margin-top: 8px;
}
.site-button {
    margin-top: 7px;
}
}

.container-caption {
    position: absolute;
    bottom: 40px;
    left: 0px;
    font-size: 1.2em;
    width: 40%;
    padding:10px 20px 10px 80px;
    background: linear-gradient(180deg,rgba(0,0,0,.65) 0,#000);
}

.container-caption .title-caption {
   font-size: 2em;
   font-weight: bold; 
}
.title-caption{
z-index: 6; 
text-transform:uppercase;
color:#fff;
}
.text-caption {
    z-index: 7; color:#fff
}

.quick-search {
    padding: 90px 0 0 0;
}
    
@media (max-width: 767.98px) { 

    .quick-search {
        padding: 90px 0 0 0;
    }
}
</style>