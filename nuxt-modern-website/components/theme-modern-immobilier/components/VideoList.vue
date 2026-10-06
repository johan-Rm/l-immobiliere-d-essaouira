<template>
    <div class="m-b30">
        <div
        v-for="(value,index) in data"
        :key="index"
        class="wt-box">
            <div class="wt-post-title ">
                <h2 class="post-title ">
                    <a href="javascript:void(0);" class="text-black font-18 letter-spacing-2 font-weight-600 ">
                        {{ value.name }} 
                    </a>
                </h2>
            </div>
            <div class="wt-thum-bx wt-img-overlay1 wt-img-effect yt-thum-box">
                <img :src="imageYoutubeUrl(value.url)" alt="" class="lazyload__" />
                <div class="overlay-bx">
                    <div class="overlay-icon">
                        <a  :title="$t('démarrer la vidéo')" class="mfp-video"
                            :href="value.url">
                            <i class="fa fa-play wt-icon-box-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { init_magnific_video } from '~/plugins/custom_transform_to_export.js'
export default {
    name: 'VideoList',
    props: {
        data: {
            type:Array
        }
    },
    methods:{
        imageYoutubeUrl(url){
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
            var match = url.match(regExp)
            let id = null
            if ( match && match[7].length == 11 ){

               return 'http://img.youtube.com/vi/' + match[7] + '/0.jpg'
            } else {
                console.log("Could not extract video ID.")
            }
        }
    },
    mounted () {
        this.$nextTick(function(){ init_magnific_video() }.bind(this))
    }
}
</script>
<style>
@media screen and (max-width: 1024px)
{
    .overlay-bx
    , .wt-thum-bx .overlay-bx
    , .wt-box .overlay-bx
    , .ow-img .overlay-bx
    , .ow-portfolio-img .overlay-bx 
    {
        opacity: 1;
        visibility: visible;
    }
    .overlay-bx 
    {
        background: 
        #090909b3;
    }
}
</style>
