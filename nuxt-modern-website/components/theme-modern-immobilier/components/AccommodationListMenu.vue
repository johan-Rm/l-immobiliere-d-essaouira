<template>
    <ul id="isotope_menu_default" class="masonry-filter font-weight-600">
        <li 
            v-for="(value, index) in data"
            :key="index"
            class="button implicit subsection tagcloud" 
        >
            <nuxt-link
            :class="{ 'nuxt-link-exact-active' : (routeCategory == value.slug) }" 
                :to="getLocalizedRoute({
                    name: params.routeCategoryName
                    , params: {
                        category: $tradLinkSlug(
                            $store.state.i18n.currentLocale
                            , value.slug
                            , 'accommodationType'
                        )
                    }
                })"
            >
                {{ $t(value.name) }}
            </nuxt-link>
        </li>
        <li class="button implicit subsection tagcloud all">
          <nuxt-link :to="getLocalizedRoute({ name: params.routeName })">
               {{ $t('Tout') }}
           </nuxt-link>
         </li>
        </ul>        
</template>
<script>
export default {
  name: 'AccommodationListMenu',
  props: {
      data: {
        type: Array
    },
     params: {
        type: Object
    }
  },
  computed: {
    routeCategory() {
      if(this.$route.params.hasOwnProperty('category')) { 
        
        return this.$route.params.category
      }

      return false
    }
  }
}
</script>
<style lang="scss" scoped>


.gradi-black::before {
    opacity: 0.7;
}

.listing-badges {
    position:absolute;
    top:0;
    left:0;
    text-align: center;
    margin: 0;

    background-color: #000;
    padding: 10px 20px;
    color: #fff;
    width: 100%;
}

.listing-badges span {

    text-transform: uppercase;
    border-left: 2px solid #fff;
    border-right: 2px solid #fff;


}

.box {
    color:#fff;
    font-size: 15px;
    position: absolute;
    z-index: 20;
    font-weight: 500;
    bottom: 15px;
    left: 0;
    width:100%;
    padding: 0 60px 0 40px;
}


.site-button, .site-button-secondry {
    padding: 10px 10px;
    font-size: 12px;
}

.box .price{
    text-align:center;
}
.wt-post-text p:last-child {
    margin-right: 20px;
}
.box .type{
    background: #3e2723;
    padding: 5px 20px;
    border-radius: 50px;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    margin-right: 10px;
}

.wt-post-title span.areaSize {
    color: var(--color-secondary);
}
@media screen and (max-width: 480px){
    .box span {
        text-transform: uppercase;
        font-size: 0.9em;
    }
    .box-details span.amount, .box-details span.place {
        text-transform: uppercase;
        font-size: 1em;
        font-weight: 600;
    }
    .fa-map-marker-alt {
        font-size: 16px;
        color: #92acbe;
    }
}
@media screen and (max-width: 419px){
   .wt-post-title span.areaSize {
    display: inline-block;
}
}
.masonry-filter > li {
    margin-right: 5px;
    margin-bottom:10px;
}

.widget_tag_cloud a {
    background-color: var(--color-primary);
    color: #fff;
}
.widget_tag_cloud a:hover
, .widget_tag_cloud a.nuxt-link-exact-active
{
    background-color: var(--color-secondary);
    color: var(--color-primary);
}
.site-button-secondry {
    width: 100%;
}
.search .form-group{
    padding-right: 0px;
padding-left: 5px;
}
.site-button-secondry {
    background-color: var(--color-secondary);
}
.site-button-secondry {
    color: #fff;
}
.site-button-secondry:hover{
    background-color: var(--color-primary);
}
.search input{
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
    background: rgba(255, 255, 255, 0.5);
    border-color: var(--color-secondary);
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
    background-color: #92acbe;
    cursor:pointer
}
.onglet-liste label {
    cursor:pointer;
    background-color: #92acbe9e;
    color: #fff;
    font-size: 14px;
padding-left: inherit;
padding: 5px 15px !important;

position: relative;
text-align: center;;
}
.onglet-liste input[type="radio"] {
    display:none
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
    border-top-color: #92acbe;

}
.results{
line-height: 40px;
text-align: center;
text-align: center;color:
#92acbe;
background-color: #fff;
    font-weight: 600;
font-size: 15px;
}

.list-grid .search-container {
    position:relative;
}

.results.desktop {
    position:absolute;
    bottom:0;
    left:0;
}
@media (min-width: 992px) { 
    .results.mobile {
        display:none;
    }
}
@media (max-width: 991px) { 
    .results.desktop {
        display:none;
    }
    
    .list-grid .widget_tag_cloud

    {
        margin-bottom: 30px;    
    }
}
@media (min-width: 768px) { 
    .box-details .left {
        text-align: right;
    }
}

#isotope_menu_default .button.all a {
 background-color: var(--color-secondary-light);
 color: #fff;
}
</style>