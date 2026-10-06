<template>
  	<div id="pagination-accommodation" v-if="checkPageData">
	  	<ul class="pagination m-tb0">
	  		<li>
	          	<nuxt-link
	          		class="glyphicon glyphicon-fast-backward"
	          		v-show="pageNumber !== 1" 
					:title="$t('première')"
	      			:to="getLocalizedRoute({
                        name: params.currentRouteName
                        , params: {
                            category: $tradLinkSlug(
                                $store.state.i18n.currentLocale
                                , params.currentCategory
                                , getCategoryType
                            )
                        }
                        , query: { page: beginPageNumber }
                    })"
	      		>	
	      		</nuxt-link>
	  		</li>
	  		<li>
	          	<nuxt-link
	          		class="glyphicon glyphicon-backward"
	          		v-show="pageNumber > 2 "
					:title="$t('précédente')" 
	      			:to="getLocalizedRoute({
                        name: params.currentRouteName
                        , params: {
                            category: $tradLinkSlug(
                                $store.state.i18n.currentLocale
                                , params.currentCategory
                                , getCategoryType
                            )
                        }
                        , query: { page: prevPageNumber }
                    })"
	      		>	
	      		</nuxt-link>
	  		</li>
	  		<li>
	  			<form @submit.prevent="handleSubmit">
	  				<input v-model="form.page" :aria-label="$t('numéro de page')" name="page" type="text" class="input-sm">
	  			</form>
	  		</li>
	      	<!-- <li 
			  v-for="(number, index) in paginator"
			  :key="index"
			  >
	      		<nuxt-link v-if="number !== 0"
	      			:class="{'page-active':(pageNumber === number)}" 
	      			:to="getLocalizedRoute({
                        name: params.currentRouteName
                        , params: {
                            category: $tradLinkSlug(
                                $store.state.i18n.currentLocale
                                , params.currentCategory
                                , getCategoryType
                            )
                        }
                        , query: { page: number }
                    })"
                >
	      			{{ number }}
	      		</nuxt-link>
	      		<a v-else href="javascript:void(0);">...</a>
	  		</li> -->
	  		<li>
	          	<nuxt-link 
	          		class="glyphicon glyphicon-forward"
	          		v-show="pageNumber <= pageCount -1"
					:title="$t('suivante')"
	      			:to="getLocalizedRoute({
                        name: params.currentRouteName
                        , params: {
                            category: $tradLinkSlug(
                                $store.state.i18n.currentLocale
                                , params.currentCategory
                                , getCategoryType
                            )
                        }
                        , query: { page: nextPageNumber }
                    })"
	      		>
	      		</nuxt-link>
	  		</li>
	  		<li>
	          	<nuxt-link 
	          		class="glyphicon glyphicon-fast-forward"
	          		v-show="pageNumber <= pageCount -1"
					:title="$t('dernière')"
	      			:to="getLocalizedRoute({
                        name: params.currentRouteName
                        , params: {
                            category: $tradLinkSlug(
                                $store.state.i18n.currentLocale
                                , params.currentCategory
                                , getCategoryType
                            )
                        }
                        , query: { page: endPageNumber }
                    })"
	      		>
	      		</nuxt-link>
	  		</li>
	   	</ul> 
	   	<!-- <a href="#" v-scroll-to="'#container-result'">Scroll</a> -->
	</div>
</template>

<script>
export default {
  	data () {
	    return {
	    	beginPageNumber: 0,
	    	endPageNumber: 0,
	    	prevPageNumber: 0,
	    	nextPageNumber: 0,
	      	pageNumber: 0,
	      	pageCount: 0,
	      	itemsPerPage: 0,
	      	totalItems: 0,
	      	form: {
	      		page: 1
	      	}
	    }
  	},
  	props: {
        params: {
          type: Object
      }
    },
  	computed: {
 		paginator() {
			let data = []
		  	for(let i = 1; i <= this.pageCount; i++) {
		    	data.push(i)
		 	}
		 	let paginator = data


		 	if(data.length > 7) {
		 		var begin = data.slice(0, 3)
		 		var end = data.slice(-3)

		 		paginator = begin.concat(0).concat(end)
		 	}
		 	
		  	return paginator
		},
		checkPageData() {
			
			this.itemsPerPage = this.$store.state.accommodations.itemsPerPage,
	      	this.totalItems = this.$store.state.accommodations.totalItems
			this.pageCount = Math.ceil(this.totalItems / this.itemsPerPage)
			if(this.pageCount < 2) {
				return false
			}
			this.pageNumber = (this.$route.query.page) ? this.$route.query.page: 1 
		
			this.prevPageNumber = (this.$route.query.page) ? this.$route.query.page: 0
			this.prevPageNumber--
			this.nextPageNumber = (this.$route.query.page) ? this.$route.query.page: this.pageNumber 
			this.nextPageNumber++
			this.beginPageNumber = 1
			this.endPageNumber = this.pageCount

			this.form.page = this.pageNumber
			
			return true
		},
		getCategoryType() {
			if(this.params.hasOwnProperty('categoryType')) {

				return this.params.categoryType
			}

			return 'accommodationType'
		}
  	},
  	methods: {
  		handleSubmit() {
  			const locale = this.$store.state.i18n.currentLocale
  			
  			const currentRouteName= this.params.currentRouteName
  			if(this.params.hasOwnProperty('currentCategory')) {

				this.$router.push({ 
	 				name: `${currentRouteName}-${locale}`
	 				, params: { 
	 					category: this.$tradLinkSlug(
	 						this.$store.state.i18n.currentLocale
	 						, this.params.currentCategory
	 						, this.getRouteCategoryType()
	 					)
	 				}
	 				, query: { page: this.form.page }
	 			})
			} else {
				this.$router.push({ 
	 				name: `${currentRouteName}-${locale}`
	 				, query: { page: this.form.page }
	 			})
			}
  		},
  		getRouteCategoryType() {
			if(this.params.hasOwnProperty('categoryType')) {

				return this.params.categoryType
			}

			return 'accommodationType'
		}
  	}
}
</script>
<style lang="scss" scoped>


.pagination > li{

    float:left;
}
.pagination > li > a{

    background-color: var(--color-primary);
    color: #fffbfb;
}
.pagination > li > a:hover, .pagination > li > a.page-active{

    background-color: #92acbe;

}
.pagination .glyphicon {
	top: 0;
}

.pagination input[name="page"] {
	width:40px;
}
</style>
