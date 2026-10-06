<template>
<div class="section-full p-t140 bg-repeat" :style="getBackgroundImage('ptn-1.png')">
  <div class="container">
    <div class="section-content">
      <!-- <div class="section-head text-left">
        <h2 class="text-uppercase font-36">{{ $t('Médias') }}</h2>
        <h4 class="">{{ $t('Ils parlent de nous') }}...</h4>
        <div class="wt-separator-outer">
          <div class="wt-separator bg-black"></div>
        </div>
      </div> -->
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div 
          v-for="(value,index) in data.slice(1,2)" 
          :key="index" 
          class="overlay-wraper bg-no-repeat bg-cover latest-blog-dark-outer p-a20 m-b30" 
          :style="'background-image:url('+ imagePath(value.primaryImage) + ');'"
          >
            <nuxt-link :to="getLocalizedRoute({
                                name: 'news-category-slug'
                                , params: {
                                    category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                                    , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                                }
                            })">
              <div class="overlay-main bg-black opacity-04"></div>
              <div class="latest-blog-dark text-uppercase p-a20">
                <h4 class="m-a0"><span class="text-white"> {{ $t(value.alternativeHeadline) }} </span></h4>
                <div class="v-date text-white font-weight-700">{{ datePublished(value.lastReviewed) }}</div>
              </div>
            </nuxt-link>
          </div>
        </div>
        <div class="col-md-6 col-sm-12">
          <div 
          v-for="(value,index) in data.slice(2,3)" 
          :key="index" 
          class="overlay-wraper bg-no-repeat bg-cover latest-blog-dark-outer2 m-b30" 
          :style="'background-image:url('+ imagePathFull(value.secondaryImage) + ');'"
          >
            <div class="overlay-main bg-black opacity-04"></div>
            <div class="latest-blog-square text-white">
                <nuxt-link :to="getLocalizedRoute({
                    name: 'news-category-slug'
                    , params: {
                        category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                        , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                    }
                })">
                <h4 class="m-t0 m-b10"><span class="text-white font-30 text-uppercase">{{ $t(value.alternativeHeadline) }}</span></h4>
                <p class="font-weight-300">{{ $t(value.articleResume) | truncate(200) }}</p> <span class="letter-spacing-4 font-12 text-white text-uppercase"> {{ $t('plus d\'infos') }} </span>
                <div class="v-date text-white font-weight-700 text-uppercase">{{ datePublished(value.lastReviewed) }}</div>
              </nuxt-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
    name: 'BlogDefault',
    props: {
        params: {
            type: Object
        }
    },
    data () {
        return {
            data: []
        }
    },
    methods: {
        imagePath: function (image) {

            var format = 'blog_horizontal'
            if(this.$device.isMobile) {
                format = 'blog_horizontal_small'
            }
            // console.log('imagePath')
// .replace(/\(/g, '\(')
            var filename = image.filename.replace(/ /g, '%20').replace(/\(/g, '%28').replace(/\)/g, '%29')
            var url = process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
            // console.log(url)
            if(null !== image) {
              
                return url
            }
            
            return null
        },
        imagePathFull: function (image) {
            
            var format = 'blog'
            if(this.$device.isMobile) {
                format = 'blog_vertical'
            }
            if(null !== image) {

                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + image.filename.replace(/ /g, '%20')  
            }
            
            return null
        },
        datePublished(date) {

            return this.$dayjs(date).format('YYYY-MM-DD')
        },
        getBackgroundImage: function (filename) {
            if(null !== filename) {
                return 'background-image:url(' + process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename + ')'
            }

            return null
        }
    },
    created () {
        const params = { itemsPerPage: 3, 'order[datePublished]': 'desc', isActive: true }

         import('~/data/articles.json').then((result) => {
            // this.data = result["hydra:member"].filter(r => r.category.slug === this.params.category)
            // var data = result["hydra:member"].filter(function(elem) {
            //   return elem.tags.find(r => r.slug === params['tags.slug']);
            // })
            
            var data = result["hydra:member"].sort((a, b) => new Date(b.datePublished) - new Date(a.datePublished))
            this.data = data.slice(0, params.itemsPerPage)
        })

        // this.$axios.get('/articles'
        //     , { 
        //         params,
        //         validateStatus: function (status) {
        //             return status >= 200 && status < 500
        //         } 
        //     })
        // .then((response) => {
        //   this.data = response.data['hydra:member']
        // }).catch(error => {
        //     console.log(error)
        //     console.log('error store BlogDefault.vue')
        // })
    }
}
</script>
<style lang="scss" scoped>


.latest-blog-dark .v-date {
    position: absolute;
    left: -20px;
    top: 50px;
    transform: rotate(-90deg);
}

.latest-blog-dark .v-date.text-primary {
    color: var(--color-primary);
}

.text-primary {
    color: var(--color-primary);
}

h4 {
  color: var(--color-primary);
}
</style>