<template>
    <div v-show="secondaryImage" class="widget bg-white">
        <div class="widget-post m-b15">
            <img :src="imagePath(secondaryImage)" :alt="imageAlt(secondaryImage)" class="img-responsive lazyload__" />
        </div>
        <p v-if="text" v-html="$t(text)"></p>
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'VerticalImage',
    props: {
        params: {
          type: Object
        }
    },
    computed: {
        ...mapState({
            secondaryImage: state => state.accommodations.item.secondaryImage,
            text: state => state.accommodations.item.text
        })
    },
    methods: {
        imagePath: function (image) {
            if  (null !== image) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'vertical' + process.env.PATH_DEFAULT_MEDIA + image.filename      
            }
            
            return null
        },
        imageAlt: function(image) {
            if  (null !== image) {
                return image.alt      
            }
            
            return null
        }
    }
}
</script>
