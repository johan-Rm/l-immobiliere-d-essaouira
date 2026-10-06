<template> 
    <div class="gallery-accommodation portfolio blog-post blog-lg date-style-1 text-black">
        <div class="desktop">
            <div class="wt-post-media">
                <div v-if="carousel.length > 0" class="owl-carousel owl-fade-slider-one owl-btn-vertical-center owl-dots-bottom-right">
                    <div v-for="(slide, index) in getImages" 
                        :key="index"
                        class="item" 
                    >
                        <div class="aon-thum-bx lz-loading ratio-container unknown-ratio-container">
                            <img 
                             :width="$getImageSizeByFilterSets('width', 'grid')" 
                            :height="$getImageSizeByFilterSets('height','grid')"
                            :data-src="imagePath(slide.image)" 
                            :alt="slide.image.alt" 
                            class="lazyload" />
                        </div>
                    </div>
                    
                </div>    
                <div class="controls">    
                    <span class="play"><i class="fa fa-play"></i></span>
                    <span class="stop"><i class="fa fa-stop"></i></span>  
                </div> 
            </div>  
            <div v-if="carousel.length > 0" id='carousel-custom-dots' class='owl-dots row m-thumb'>
                <a href="javascript:void(0);" 
                v-for="(slide, index) in getImages" 
                :key="index" class="owl-dot col-md-3 lz-loading ratio-container unknown-ratio-container">
                    <img 
                     :width="$getImageSizeByFilterSets('width', 'grid')" 
                    :height="$getImageSizeByFilterSets('height','grid')"
                    class="img-responsive lazyload" 
                    :data-src="imagePath(slide.image)" 
                    />
                </a>
            </div>
        </div>
        <div class="mobile">
            <div class="wt-post-media">
            <div v-if="carousel.length > 0" class="">
                <div v-for="(slide, index) in getImages" 
                    :key="index"
                    class="item m-b5" 
                >
                    <div class="aon-thum-bx lz-loading ratio-container unknown-ratio-container">
                        <img
                         :width="$getImageSizeByFilterSets('width', 'grid')" 
                        :height="$getImageSizeByFilterSets('height','grid')"
                        :data-src="imagePath(slide.image)" 
                        :alt="slide.image.alt" 
                        class="lazyload"/>
                    </div>
                </div>
                
            </div>    
           
        </div>  
        </div>
    </div>
</template>

<script>
import { initOwl_fade_slider } from '~/plugins/custom_transform_to_export.js'
export default {
  name: 'GalleryAccommodation',
  props: {
        params: {
          type: Object
        },
        data: {
          type: Object
        }
    },
    data () {
        return{
          carousel: []
        }
      },
    mounted () {
        this.carousel = this.data.imageGalleries
        this.$nextTick(function(){ initOwl_fade_slider() }.bind(this))
    },
    computed: {
        getImages() {

            var imageList = this.carousel.filter(obj =>{ 
                if(false !== obj.inSlider) {

                    return obj  
                }
            })

            return imageList.slice(0,4)
        }
    },
    methods: {
        imagePath: function (image) {
            const nostamp = (this.params.hideStamp)? '_nostamp': ''
            if(null !== image) {
                var format = 'grid' + nostamp

                let filename = image.filename
                if(!this.$device.isMacOS && !this.$device.iOS) {
                  filename = filename.substr(0, filename.lastIndexOf('.'))
                  filename = filename + '.webp'
                }

                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename    
            }

            return null
        }
    }
}
</script>

<style lang="scss" scoped>


.owl-dots .owl-dot.active{
 opacity: 0.5;
}

.gallery-accommodation .owl-nav button i {
  font-size: 24px;
}

.controls{
    position: absolute;
    bottom:0;
    left:0;
    z-index:5000;
}
.controls span:hover{
    background-color:#79a3b1
}
.controls span{
    background-color: var(--color-primary);
    width: 35px;
    height: 35px;
    cursor: pointer;
    display: inline-block;
    color: white;
    text-transform:uppercase;
    line-height:35px;
    text-align: center;
    transition:all 300ms ease;
}

@media screen and (max-width: 992px) {  
    /*.controls{
        display:none;
    }*/
    .owl-dots{
        display:none;
    }
}

.portfolio.blog-post .mobile {
    display:none;
}

@media screen and (max-width: 480px) { 
    .portfolio.blog-post .mobile {
        display:block;
    }
    .portfolio.blog-post .desktop {
        display:none;
    }
} 

.blog-post {
  margin-bottom: 0;
}
.blog-post .wt-post-media {
    position:relative;
}
.mfp-bottom-bar{
    position: static;
}

.gallery-accommodation .ratio-container:after {
  /* ratio = calc(500 / 800 * 100%) */
  padding-bottom: 62.5%;
}

.gallery-accommodation .m-thumb .ratio-container:after {
  /* ratio = calc(100 / 191.25 * 100%) */
  /*padding-bottom: 52.2875%;*/
}

img {
  text-indent: -9999px; 
}
</style>