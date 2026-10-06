<template>

            <div class="accommodation-list-search-container">
                <div class="accommodation-list-search p-tb20 p-lr20">
                    <form ref="formTest" novalidate @submit.prevent="handleSubmit">
                    <div class="input-group search">
                        <div class="form-group col-md-12 m-b0">
                            <ul class="onglet-liste m-b0">
                                <template v-if="'vente' == params.nature">
                                <li>
                                    <input type="radio" v-model="form.type" id="purchase" value="vente" checked="checked">
                                    <label for="purchase">{{ $t('Achat') }}</label>
                                </li>
                                </template>
                                <template v-else>
                                    <li>
                                        <input type="radio" v-model="form.type" id="rental" value="longue-duree" checked="checked">
                                        <label for="rental">{{ $t('Longue durée') }}</label>
                                    </li>
                                    <li>
                                        <input type="radio" v-model="form.type" id="holidays" value="saisonniere">
                                        <label for="holidays">{{ $t('Vacances') }}</label>
                                    </li>
                                </template>
                            </ul>
                        </div> 
                        <div class="form-group col-md-6">
                            <input v-model="form.minBudget" type="text" class="form-control col-md-4" :placeholder="$t('Budget mini.')">
                        </div>
                        <div class="form-group col-md-6">    
                            <input v-model="form.maxBudget" type="text" class="form-control col-md-4" :placeholder="$t('Budget maxi.')">
                        </div>    
                        <div class="form-group m-b0">
                            <span class="input-group-btn-full text-uppercase ">
                                <button type="submit" class="text-uppercase site-button-secondry letter-spacing-2">
                                    <i class="fa fa-search"></i>{{ $t('lancer votre recherche') }}  
                                </button>
                            </span>
                        </div>
                       
                    </div>
                    </form>
                </div>
            </div>
</template>
<script>
export default {
    name:"SearchBarAccommodation",
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
            }
         }
    },
    computed:{
        checkData() {
            if(this.$store.state.search.minBudget > 0) {
                this.form.minBudget = this.$store.state.search.minBudget    
            }
            if(this.$store.state.search.maxBudget > 0) {
                this.form.maxBudget = this.$store.state.search.maxBudget    
            }
            if(this.data.length > 0) {
                return true
            }

            return false
        }
    },
    methods: {
        handleSubmit(){
            const args = { 
                isActive : 'true'
                , itemsPerPage: this.$store.state.accommodations.itemsPerPage 
                , 'nature.slug': this.params.nature
            }
            this.$store.commit('search/setNature', this.params.nature)
            if (this.$route.params.hasOwnProperty('category')) { // true
                args['type.slug'] = this.$route.params.category
            }

            if("" !== this.form.type && "vente" !== this.form.type) {
                this.$store.commit('search/setDuration', this.form.type)
                args['duration.slug'] = this.form.type
            } else {
                this.form.type = ""
            }

            this.$store.commit('search/setMinBudget', this.form.minBudget)
            if(this.form.minBudget > 0) {
                args['price[gt]'] = this.form.minBudget
            }
            this.$store.commit('search/setMaxBudget', this.form.maxBudget)
            if(this.form.maxBudget > 0) {
                args['price[lt]'] = this.form.maxBudget
            }
            
            this.getDataList(args)
        },
        getDataList(args) {
            this.$store.dispatch(
                'accommodations/getListBy'
                , args
            )
        },
        resetStoreSearch() {
            this.$store.commit('search/setNature', this.params.nature)
            this.$store.commit('search/setMinBudget', 0)
            this.$store.commit('search/setMaxBudget', 0)
            this.$store.commit('search/setDuration', "")
            this.form.minBudget = ""
            this.form.maxBudget = ""
            this.form.type = ""
        }
        },

    mounted() {
        if(this.$store.state.search.nature !== this.params.nature) {
            this.resetStoreSearch()
        }

        if ('vente' === this.params.nature) {
            return this.form.type = "vente"
                     
        } else {
            return this.form.type = "longue-duree"
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
@media screen and (max-width: 991px){
.input-group .form-control {
    margin-bottom: 15px;
}
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
    color: #fff;
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
    background: inherit;
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
</style>