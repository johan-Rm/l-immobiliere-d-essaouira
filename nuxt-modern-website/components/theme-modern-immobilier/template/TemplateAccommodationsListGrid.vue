<template>
<div class="page-content accommodation-list">
    <div class="list-grid section-full p-tb90">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 m-b30">
                    <HeadPage/>
                </div>
            </div>
            <div class="row m-b50 search-container">
                <div class="results text-uppercase letter-spacing-4 desktop container-fluid">
                  | {{ getResultText }} | 
                  <span 
                  class="color-secondary" 
                  v-if="$route.query.page">page {{ $route.query.page }}</span> 
                  <i 
                  v-show="$store.state.organization.containerResult" 
                  class="fa fa-spinner fa-spin"></i>
                </div>
                <div class="widget widget_tag_cloud filter-wrap col-md-8 m-b0">
                    <template v-if="types">
                        <AccommodationListMenu :params="params" :data="types" />
                    </template>
                </div>
                  <div class="col-md-4">
                    <div class="accommodation-list-search-container">
                        <div class="accommodation-list-search p-tb20 p-lr20">
                            <form ref="formTest" novalidate @submit.prevent="handleSubmit">
                                <div class="input-group search">
                                    <div class="form-group col-md-12 m-b0">
                                        <ul class="onglet-liste m-b0">
                                            <template v-if="'vente' == params.nature">
                                                <li>
                                                    <input type="radio" 
                                                      v-model="form.type" 
                                                      id="purchase" 
                                                      value="vente" 
                                                      checked="checked"
                                                    >
                                                    <label for="purchase">{{ $t('Achat') }}</label>
                                                </li>
                                            </template>
                                            <template v-else>
                                                <li>
                                                    <input type="radio" 
                                                      v-model="form.type" 
                                                      id="rental" 
                                                      value="longue-duree" 
                                                      :checked="form.type == checkedLocation"
                                                    >
                                                    <label for="rental">{{ $t('Longue durée') }}</label>
                                                </li>
                                                <li>
                                                    <input type="radio" 
                                                      v-model="form.type" 
                                                      id="holidays" 
                                                      value="saisonniere"
                                                      :checked="form.type == checkedLocation"
                                                    >
                                                    <label for="holidays">{{ $t('Vacances') }}</label>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input v-model="form.minBudget" type="text" class="form-control col-md-4" :aria-label="$t('Budget mini.')" :placeholder="$t('Budget mini.')">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input v-model="form.maxBudget" type="text" class="form-control col-md-4" :aria-label="$t('Budget maxi.')" :placeholder="$t('Budget maxi.')">
                                    </div>
                                    <div class="form-group m-b0"> <span class="input-group-btn-full text-uppercase ">
                                      <button type="submit" class="text-uppercase site-button-secondry letter-spacing-2 font-weight-800 ">
                                          <i v-if="!loading" class="fa fa-search"></i>
                                          <i v-else class="fa fa-circle-notch fa-spin"></i>
                                          {{ $t('lancer votre recherche') }}  
                                      </button>
                                  </span>
                                        <a class="search" href="#search">{{ $t('Vous connaissez votre référence ?') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="results text-uppercase letter-spacing-4 mobile m-b20">| {{ getResultText }} |</div>
            <div class="results text-uppercase letter-spacing-4 mobile m-b30">
              <span class="color-secondary" v-if="$route.query.page">page {{ $route.query.page }}</span>
            </div>
            <div class="results text-uppercase letter-spacing-4 mobile m-b5">
              <i 
                  v-show="$store.state.organization.containerResult" 
                  class="fa fa-spinner fa-spin"></i>
            </div>
            
            <div class="row">
                <div id="container-result" class="portfolio-wrap mfp-gallery news-grid clearfix">
                
                    <div v-if="checkData" class="container-list">
                        <div v-for="(value, index) in getAccommodations" :key="index" class="masonry-item col-lg-6 col-md-6 col-sm-6 m-b30">
                            <div class="wt-img-effect gradi-black">
                                <div class="lz-loading ratio-container unknown-ratio-container">
                                    <img 
                                    :width="$getImageSizeByFilterSets('width', 'grid')" 
                                    :height="$getImageSizeByFilterSets('height', 'grid')"
                                    :src="imagePath(value)" 
                                    :alt="value.primaryImage.alt" 
                                    class="lazyload" />
                                </div>

                                <div v-if="value.label" class="listing-badges"> <span class="featured letter-spacing-4 font-12 text-uppercase p-lr20"> {{ $t(value.label.name) }} </span>
                                </div>
                                <div class="box box-details row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div>
                                            <nuxt-link :title="$t('plus d\'infos')" :to="getLocalizedRoute({
                                                  name: params.routeCategorySlugName
                                                  , params: {
                                                      category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                                                      , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                                                  }
                                              })"> <span v-if="value.price > 0" class="amount">
                                                    {{ toCurrencyString(value.price) }}
                                                  </span>
                                                &nbsp;<span class="priceType text-lowercase" v-if="value.rentalPriceType">{{ value.rentalPriceType.name }}</span>
                                            </nuxt-link>
                                        </div>
                                    </div>
                                    <div class="left col-lg-6 col-md-6 col-sm-6">
                                        <nuxt-link :title="$t('plus d\'infos')" :to="getLocalizedRoute({
                                              name: params.routeCategorySlugName
                                              , params: {
                                                  category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                                                  , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                                              }
                                          })"> <span class="place"><i class="fa fa-map-marker-alt" ></i> {{ $t(value.place.name) }}</span>
                                        </nuxt-link>
                                    </div>
                                </div>
                                <div class="overlay-bx-2">
                                    <div class="line-amiation p-a40">
                                        <div class="wt-post-info text-white">
                                            <div class="wt-post-title row">
                                                <h3 class="post-title">
                                              <div class="col-lg-6 col-md-6 col-sm-6">
                                                      <nuxt-link 
                                                      class="text-white font-20 letter-spacing-4 text-uppercase"
                                                      :title="$t('plus d\'infos')"
                                                      :to="getLocalizedRoute({
                                                          name: params.routeCategorySlugName
                                                          , params: {
                                                              category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                                                              , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                                                          }
                                                      })">
                                                          {{ $t(value.type.name) }}
                                                      </nuxt-link>    
                                              </div>
                                              <div class="col-lg-6 col-md-6 col-sm-6">
                                                      <nuxt-link 
                                                      class="text-white font-20 letter-spacing-4 text-uppercase"
                                                      :title="$t('plus d\'infos')"
                                                      :to="getLocalizedRoute({
                                                          name: params.routeCategorySlugName
                                                          , params: {
                                                              category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                                                              , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                                                          }
                                                      })">

                                                          <template v-if="value.floorSize > 0" >
                                                              <span class="areaSize text-white ">{{ value.floorSize }} M&#xB2;</span>
                                                          </template>    

                                                      </nuxt-link>
                                              </div>

                                                  </h3>
                                            </div>
                                            <div class="wt-post-meta"></div>
                                            <div class="wt-post-text">
                                                <nuxt-link :title="$t('plus d\'infos')" :to="getLocalizedRoute({
                                                          name: params.routeCategorySlugName
                                                          , params: {
                                                              category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                                                              , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                                                          }
                                                      })">
                                                    <p v-if="value.description">{{ getDescription($store.state.i18n.currentLocale, 'description', 'Accommodation', value.slug) | truncate(100) }}</p>
                                                </nuxt-link>
                                            </div>
                                            <div class="vertical "> <span class="letter-spacing-4 font-14 text-uppercase">
                                                  {{ value.reference }}
                                              </span> 
                                            </div>
                                            <nuxt-link class="v-button letter-spacing-4 font-12 text-uppercase p-l20" :title="$t('plus d\'infos')" :to="getLocalizedRoute({
                                                  name: params.routeCategorySlugName
                                                  , params: {
                                                      category: $tradLinkSlug($store.state.i18n.currentLocale, value.type.slug, 'accommodationType')
                                                      , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'accommodation')
                                                  }
                                              })">{{ $t('plus d\'infos') }}</nuxt-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <NoData/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-6 m-b30">
                    <ThePaginationDefault :params="params" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-6 m-b30">
                    <BlockQuote v-if="blockquoteText" :data="getDataForBlockquote" :params="params" />
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
import BlockQuote from '../components/BlockQuote'
import ThePaginationDefault from '../components/ThePaginationDefault'
import NoData from '../components/NoData'
import HeadPage from '../components/HeadPage'
import AccommodationListMenu from '../components/AccommodationListMenu'
import head from 'lodash.head'
import shuffle from 'lodash.shuffle'
import { init_site_search } from '~/plugins/custom_transform_to_export.js'
import { EventBus } from '~/plugins/event-bus.js'
export default {
    name: 'AccommodationsListGrid',
    props: {
        params: {
          type: Object
      }
    },
    data () {
        return {
            form: {
                maxBudget: "",
                minBudget: "",
                type:""
            },
            checkedLocation: "",
            loading: false
         }
    },
    components: {
        BlockQuote
        ,NoData
        ,ThePaginationDefault
        ,HeadPage
        ,AccommodationListMenu
    },
    computed: {
        ...mapState({
            accommodations: state => state.accommodations.list,
            types: state => state.accommodation_types.list,
            alt: state => state.pages.item.name,
            primaryImage: state => state.pages.item.primaryImage,
            blockquoteText: state => state.pages.item.blockquote,
            blockquoteTitle: state => state.pages.item.blockquoteTitle
        }),
        getAccommodations() {
          return this.accommodations.slice(0, 10)
        },
        checkData() {
        
            if(this.$store.state.search.minBudget > 0) {
                this.form.minBudget = this.$store.state.search.minBudget    
            }

            if(this.$store.state.search.maxBudget > 0) {
                this.form.maxBudget = this.$store.state.search.maxBudget    
            }

            if(this.accommodations.length > 0) {
                return true
            }

            return false
        },
        getDataForBlockquote() {
            let image = null
            if(null !== this.accommodations) {
                image = head(shuffle(this.accommodations))
            }
            image = this.primaryImage

            return { 
                image: image.primaryImage,
                headline: this.blockquoteTitle,
                text: this.blockquoteText,
                
            }
        },
        getParams() {
            let params = { 'accommodation_nature':  'vente' }
            let index = this.categories
                .map((category)=>category.slug)
                .indexOf('location')
            if(-1 !== index) {
                params = { 'accommodation_nature':  'location' }
            }

            return params
        },
        getResultText() {
            const totalItems = this.$store.state.accommodations.totalItems

            return (totalItems <= 1) ? totalItems + " " + this.$i18n.t("logement trouvé"): totalItems + " " + this.$i18n.t("logements trouvés")
        }
    },

    methods: {
      isEmpty(value) {
        return (value == null || (typeof value === "string" && value.trim().length === 0))
      },
        handleSubmit(){
            this.loading = true
            this.$store.commit('organization/setContainerResult', true)
            const args = { 
                isActive : 'true'
                , itemsPerPage: this.$store.state.accommodations.itemsPerPage 
                , 'nature.slug': this.params.nature
            }
            this.$store.commit('search/setNature', this.params.nature)

            if (this.$route.params.hasOwnProperty('category')) { // true
                var _category = this.$tradSlug(
                    this.$store.state.i18n.currentLocale
                    , this.$route.params.category
                    , this.$store.state.i18n.routes['selling-category'].entities.category
                )
                args['type.slug'] = _category
            }

            if("" !== this.form.type && "vente" !== this.form.type) {
                this.$store.commit('search/setDuration', this.form.type)
                args['duration.slug'] = this.form.type
            } else {
                this.form.type = ""
            }

            this.$store.commit('search/setMinBudget', this.form.minBudget)
            if(this.form.minBudget > 0) {
                args['price[gte]'] = this.form.minBudget
            }
            this.$store.commit('search/setMaxBudget', this.form.maxBudget)
            if(this.form.maxBudget > 0) {
                args['price[lte]'] = this.form.maxBudget
            }
            
            
            this.getDataList(args)
            
        },
        async getDataList(args) {
          args['source'] = 'JSON'
            await this.$store.dispatch(
                'accommodations/getListBy'
                , args
            )
            this.loading = false
            this.$store.commit('organization/setContainerResult', false)
        },
        resetStoreSearch() {
            this.$store.commit('search/setNature', this.params.nature)
            this.$store.commit('search/setMinBudget', 0)
            this.$store.commit('search/setMaxBudget', 0)
            this.$store.commit('search/setDuration', "")
            this.form.minBudget = ""
            this.form.maxBudget = ""
            this.form.type = ""
        },
        toCurrencyString(number){

            return number.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' })
        },
        imagePath: function (accommodation) {

            var image = accommodation.primaryImage
            if(null !== image) {
                const nostamp = (accommodation.hideStamp)? '_nostamp': ''
                const format = 'grid' + nostamp

                let filename = image.filename
                if(!this.$device.isMacOS && !this.$device.iOS) {
                  filename = filename.substr(0, filename.lastIndexOf('.'))
                  filename = filename + '.webp'
                }

                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename      
            }

            return null
        },
        sanitizeHtml(string) {

          return string.replace(/(<([^>]+)>)/ig,"")
        },
        getDescription(lang, fieldName, entityName, slug) {

          let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
          var string = this.$i18n.t(key)
          string = this.sanitizeHtml(string)

          return string
        }
    },
    created() {
        // var args = this.$getDefaultApiArgs(this.$store.state.accommodations, { 'nature': this.params.nature })

        // this.$route.params.category = this.$tradSlug(
        //     this.$store.state.i18n.currentLocale
        //     , this.$route.params.category
        //   , this.$store.state.i18n.routes['selling-category'].entities.category
        // )
        // args = this.$getRouteApiArgs(args, this.$route)
        // args = this.$getSearchStoreApiArgs(args, this.$store.state.search)
        
        // this.getDataList(args)
        // EventBus.$on('page-change', data => {
        //   args['page'] = data
        //   console.log('change')
        //   console.log(args)
        //   console.log(this.$route)
        //   this.getDataList(args)
        // })
    },
    mounted() {
      this.$nextTick(function(){ init_site_search() }.bind(this))
      if(this.$store.state.search.nature !== this.params.nature) {
          this.resetStoreSearch()
      }
      
      if ('vente' === this.params.nature) {

          return this.form.type = "vente" 
      } else {
        
        if(!this.isEmpty(this.$store.state.search.duration)) {
          
          return this.form.type = this.$store.state.search.duration 
        }
        
        return this.form.type = "" // this.isEmpty(this.params.duration ) ? "" : this.params.duration
      }
      this.$store.commit('organization/setContainerResult', false)
    }
}
</script>

<style lang="scss" scoped>


button.text-uppercase.site-button-secondry.letter-spacing-2.font-weight-800 {
  color: var(--color-primary);
}
button.text-uppercase.site-button-secondry.letter-spacing-2.font-weight-800:hover {
  background-color: var(--color-secondary);
  color: #fff; // var(--color-primary);
}
input.form-control {
  color: #fff;
}
.gradi-black::before {
  opacity: 0.7;
}
.listing-badges {
  position: absolute;
  top: 0;
  left: 0;
  text-align: center;
  margin: 0;
  background-color: #000;
  padding: 10px 20px;
  color: #fff;
  width: 100%;
  z-index: 1;
}
.listing-badges span {
  text-transform: uppercase;
  border-left: 2px solid #fff;
  border-right: 2px solid #fff;
}
.box {
  color: #fff;
  font-size: 15px;
  position: absolute;
  z-index: 20;
  font-weight: 500;
  bottom: 15px;
  left: 0;
  width: 100%;
  padding: 0 60px 0 40px;
}
.line-amiation a.v-button {
  color: #fff;
  border-right: 2px solid;
  display: block;
  position: absolute;
  bottom: 5px;
  right: 20px;
  -webkit-transform: rotate(-90deg);
  transform: rotate(90deg);
  padding: 0 15px 0 0;
  border-left: inherit;
  -webkit-transform-origin: 100% 0;
  transform-origin: 100% 0;
}
.vertical {
  color: var(--color-secondary);
  transform: rotate(90deg);
  transform-origin: left top 0;
  left: 21px;
  position: absolute;
  font-weight: 800;
  top: 46px;
  text-align: right;
}
.site-button,
.site-button-secondry {
  padding: 10px 10px;
  font-size: 12px;
}
.box .price {
  text-align: center;
}
.wt-post-text p:last-child {
  margin-right: 20px;
}
.box .type {
  background: #3e2723;
  padding: 5px 20px;
  border-radius: 50px;
  color: #fff;
  font-size: 12px;
  font-weight: 600;
  margin-right: 10px;
}
.wt-post-title span.areaSize {
  color: #fff;
}
@media screen and (max-width: 480px) {
  .box span {
    text-transform: uppercase;
    font-size: 0.9em;
  }
  .box-details span.amount,
  .box-details span.place {
    text-transform: uppercase;
    font-size: 1em;
    font-weight: 600;
  }
  .fa-map-marker-alt {
    font-size: 16px;
    color: var(--color-secondary);
  }
}
@media screen and (max-width: 419px) {
  .wt-post-title span.areaSize {
    display: inline-block;
  }
}
.masonry-item img {
  z-index: 1;
}
.masonry-filter > li {
  margin-right: 5px;
  margin-bottom: 10px;
}
.widget_tag_cloud a {
  background-color: var(--color-primary);
  color: #fff;
}
.widget_tag_cloud a:hover,
.widget_tag_cloud a.nuxt-link-exact-active {
  background-color: var(--color-secondary);
  color: var(--color-primary);
}
.site-button-secondry {
  width: 100%;
}
.search .form-group {
  padding-right: 0px;
  padding-left: 5px;
}
.site-button-secondry {
  background-color: var(--color-secondary);
}
.site-button-secondry {
  color: #fff;
}
.site-button-secondry:hover {
  background-color: var(--color-primary);
}
.search input {
  border: 0px;
  border-bottom: 1px solid #000;
}
.accommodation-list-search {
  background: var(--color-primary);
}
.accommodation-list-search .form-group.m-b0 {
  margin-bottom: 0;
}
.input-group-btn-full .fa {
  margin-right: 5px;
}
.accommodation-list-search input {
  background: rgba(255, 255, 255, 1);
  background: transparent;
  border-color: var(--color-secondary);
  color: var(--color-secondary);
}
.onglet-liste {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: space-between;
}
ol,
ul {
  list-style: none;
}
.onglet-liste input:checked + label {
  background-color: var(--color-secondary);
  cursor: pointer;
  color: var(--color-primary);
}
.onglet-liste label {
  cursor: pointer;
  background-color: var(--color-secondary-light);
  color: #fff; // var(--color-secondary);
  font-size: 14px;
  padding-left: inherit;
  padding: 5px 15px !important;
  position: relative;
  text-align: center;
  ;
}
.onglet-liste input[type="radio"] {
  display: none
}
input[type="checkbox"] + label::before,
input[type="radio"] + label::before {
  border: none;
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
.results {
  line-height: 40px;
  text-align: center;
  text-align: center;
  color: #000;
  background-color: #fff;
  font-weight: 600;
  font-size: 15px;
}
.list-grid .search-container {
  position: relative;
}
.results.desktop {
  position: absolute;
  bottom: 0;
  left: 0;
}
@media (min-width: 992px) {
  .results.mobile {
    display: none;
  }
}
@media (max-width: 991px) {
  .results.desktop {
    display: none;
  }
  .list-grid .widget_tag_cloud {
    margin-bottom: 30px;
  }
}
@media (min-width: 768px) {
  .box-details .left {
    text-align: right;
  }
}
a.search {
  display: block;
  text-align: center;
  padding: 10px;
  margin-top: 10px;
  color: var(--color-secondary);
}
a.search:hover {
  color: #fff
}
.accommodation-list .ratio-container:after {
  /* ratio = calc(500 / 800 * 100%) */
  padding-bottom: 62.5%;
}


.accommodation-list .gradi-black::before {
  z-index: 2;
}

.accommodation-list .overlay-bx-2 {
  z-index: 3;
}


.accommodation-list .box-details span.place, 
.accommodation-list .box-details span.amount {

  font-size: 1.1em;
}

.box-details amount, .box-details .priceType {
  display: inline-block;
}
#container-result {
  position:relative;
}
.overlay-result {
  position:absolute;   
  left:0;
  top:0;
  width:100%;
  height:100%;
  
  background: #000;
  z-index:5001;
}

.fa-spin {
  letter-spacing: 0;
}
span.color-secondary {
  color: var(--color-secondary-light)
}
</style>