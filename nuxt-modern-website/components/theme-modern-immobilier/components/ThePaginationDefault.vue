<template>
  	<div id="pagination-accommodation" v-if="checkPageData">
	  	<ul class="pagination m-tb0">
	      <li 
        class="scroll-page"
			  v-for="(number, index) in paginator"
			  :key="index"
			  >
	      	<nuxt-link v-if="number !== 0"
	      			:class="{ 'page-active' : (pageNumber == number) }" 
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
                no-prefetch
                >
	      			{{ number }}
	      	</nuxt-link>
	  		</li>
	   	</ul> 
	</div>
</template>

<script>
// import { EventBus } from '~/plugins/event-bus.js'
import { initScrollPage } from '~/plugins/custom_transform_to_export.js'
export default {
  data () {
    return {
      pageNumber: 0,
      pageCount: 0,
      itemsPerPage: 0,
      totalItems: 0
    }
  },
  mounted () {
    this.$nextTick(function(){ initScrollPage() }.bind(this))
  },
  props: {
    params: {
        type: Object
    }
  },
  computed: {
 		paginator() {

      // console.log(' ')
      // console.log('------ paginator')

			const data = []
		  for(let i = 1; i <= this.pageCount; i++) {
		    	data.push(i)
		 	}

		  return data
		},
		checkPageData() {
			
      this.itemsPerPage = this.$store.state.accommodations.itemsPerPage
      this.totalItems = this.$store.state.accommodations.totalItems
      this.pageCount = Math.ceil(this.totalItems / this.itemsPerPage)
      this.pageNumber = (this.$route.query.page) ? this.$route.query.page: 1 
      
      // console.log('------ checkPageData')
      // console.log('itemsPerPage : ' + this.itemsPerPage)
      // console.log('totalItems : ' + this.totalItems)
      // console.log('pageCount : ' + this.pageCount)
      // console.log('pageNumber : ' + this.pageNumber)

			if(this.pageCount < 2) {

				return false
			}
			
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
  	pageChange(number) {
      // console.log('page-change : ' + number)
      
      // EventBus.$emit('page-change', number)
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

    background-color: var(--color-secondary);

}
.pagination .glyphicon {
	top: 0;
}

.pagination input[name="page"] {
	width:40px;
}
</style>
