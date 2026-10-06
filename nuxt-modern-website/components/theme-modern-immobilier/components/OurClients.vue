<template>
    <div>
    <div class="section-full p-tb40">
        <div class="container">
            <div class="container">
                <div class="hilite-title p-lr20 m-tb20 text-right text-uppercase bdr-gray bdr-right">
                    <strong> {{ content.sub_title }} </strong>
                    <span class="text-black"> {{ content.sub_title1 }} </span>
                </div>
            </div>
        </div>
    </div>
    <div class="section-full bg-black p-tb40">
        <div class="container">
            <div class="section-content">
                <div class="section-content">
                    <div class="row">
                    	<div class="col-md-4 col-sm-12">
                            <div class="text-left">
                                <h2 class="text-uppercase font-36 text-white"> {{ content.title }}
                                </h2>
                                <div class="wt-separator-outer">
                                    <div class="wt-separator bg-white"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="section-content bg-white p-tb10">
                                <div v-if="items.length > 0" class="owl-carousel home-client-carousel owl-btn-center-v">
                                   <div v-for="client in items" v-bind:key="client.id"  class="item" >
                                        <div class="ow-client-logo">
                                            <div class="client-logo client-logo-media">
                                            <!-- <a href="javascript:void(0);"> -->
                                              <img 
                                              :src="client.img" alt="" class="lazyload__" />
                                            <!-- </a> -->
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { initHomeClientCarousel } from '~/plugins/custom_transform_to_export.js'

export default {
  name: 'OurClients',
  props: {
      params: {
        type: Array
      }
  },
  components: {
  },
  data: () => ({
      content: {
        "title":"Our Clients",
        "sub_title":"Clients",
        "sub_title1":"Says"
      }
      ,
      items: [
            {"id":"1","img":"http://thememajestic.com/modern/images/client-logo/w1.png"},
            {"id":"2","img":"http://thememajestic.com/modern/images/client-logo/w2.png"},
            {"id":"3","img":"http://thememajestic.com/modern/images/client-logo/w3.png"},
            {"id":"4","img":"http://thememajestic.com/modern/images/client-logo/w4.png"},
            {"id":"5","img":"http://thememajestic.com/modern/images/client-logo/w5.png"},
            {"id":"6","img":"http://thememajestic.com/modern/images/client-logo/w6.png"}
        ]
    }
  ),
  mounted () {
        this.$nextTick(function(){ initHomeClientCarousel() }.bind(this))
    },
}
</script>
