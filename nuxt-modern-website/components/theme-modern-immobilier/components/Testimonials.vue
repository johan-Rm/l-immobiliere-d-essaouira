<template>
    <!-- TESTIMONIALS SECTION START -->
    <div class="section-full p-t140 bg-repeat" :style=" style ">
        <div class="container">
            <div class="section-content">
                <!-- TITLE START -->
                <div class="section-head text-left">
                    <h2 class="text-uppercase font-36"> {{ $t("Témoignages") }} </h2>
                    <div class="wt-separator-outer">
                        <div class="wt-separator bg-black"></div>
                    </div>
                </div>
                <!-- TITLE END -->
                <!-- TESTIMONIAL 4 START ON BACKGROUND -->
                <div class="section-content">
                    <div class="owl-carousel testimonial-home">
                        <div v-for='t in reviews' class="item">
                            <div class="testimonial-6">
                                <div class="testimonial-pic-block">
                                    <div class="testimonial-pic">
                                        <img :data-src="t.profile_photo_url" width="132" height="132" alt="" class="lazyload pic">
                                    </div>
                                </div>
                                <div class="testimonial-text clearfix bg-white">
                                    <div class="testimonial-detail clearfix">
                                        <strong class="testimonial-name">
                                            {{ t.author_name }}
                                        </strong>
                                        <span class="testimonial-position p-t0">
                                            {{ t.relative_time_description }}
                                        </span>
                                    </div>
                                    <div class="testimonial-paragraph text-black p-t15">
                                        <span class="fa fa-quote-left"></span>
                                        <p> {{ t.text | truncate(300) }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TESTIMONIALS SECTION END -->
</template>

<script>
import { mapState } from 'vuex'
import { initTestimonialHome } from '~/plugins/custom_transform_to_export.js'
export default {
    name: 'Testimonials',
    props: {
        params: {
          type: Object
        }
    },
    computed: {
        ...mapState({
            reviews: state => state.testimonials.list
        })
    },
    data: () => ({
        style: "background-image:url(" + process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + "ptn-1.png);"
      }
    ),
    created () {
        // this.getDataAsync()
    },
    mounted () {
        this.$nextTick(function(){ initTestimonialHome() }.bind(this))
    }
}
</script>
<style scoped>
  
  .testimonial-pic-block {
    float: right;
    position: relative;
    z-index: 5001;
    margin: 10px 10px 0 0;
  }

  .testimonial-6 .testimonial-pic:after,
  .testimonial-grid-6 .testimonial-pic:after{
    left:0px;
    top:0px;
    width:0px;
    height:0px;
  }
  .testimonial-6 .testimonial-pic:before,
  .testimonial-grid-6 .testimonial-pic:before{
    left:0px;
    top:0px;
    width:0px;
    height:0px;
  }
  
  .testimonial-6 .testimonial-text, .testimonial-grid-6 .testimonial-text {
    padding: 0 30px 30px 30px;
  }

  .testimonial-6 .testimonial-detail, .testimonial-grid-6 .testimonial-detail {
    padding-top: 30px;
  }

</style>