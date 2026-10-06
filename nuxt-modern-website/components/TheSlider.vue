<template>
<div class="slider-default">
    <div v-if="$device.isDesktop || $device.isTablet" 
        class="slider-container desktop section-full bg-gray wt-img-effect" 
        :style="getBackgroundImage(imageFilename)"
    >
        <component :is="template"></component>
    </div>
    <div v-else 
        class="slider-container mobile section-full bg-gray wt-img-effect"
        :style="getMobileBackgroundImage(imageFilename)"
    >
        <component :is="template"></component>
    </div>
</div>
</template>
<script>
export default {
    name: 'TheSlider',
    data () {
        return{
            // imageFilename: "location maison essaouira (7).jpg"
            // imageFilename: "immobiliere-essaouira.jpg"
            imageFilename: "immobiliere-essaouira-2.jpg"
        }
    },
    computed: {
        template () {
            const name = 'Default'
            
            return () => import(`~/components/theme-modern-immobilier/components/SliderContent${name}`)
        }
    },
    methods: {
        getBackgroundImage: function (imageFilename) {
            if(null !== imageFilename) {

                let filename = imageFilename
                if(!this.$device.isMacOS && !this.$device.iOS) {
                  filename = filename.substr(0, filename.lastIndexOf('.'))
                  filename = filename + '.webp'
                }
                let url = process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'large_nostamp' + process.env.PATH_DEFAULT_MEDIA + filename.replace(/ /g, '%20')

                return 'background-image:url(' + url + ')'
            }

            return null
        },
        getMobileBackgroundImage: function (imageFilename) {
            if(null !== imageFilename) {

                let filename = imageFilename
                if(!this.$device.isMacOS && !this.$device.iOS) {
                  filename = filename.substr(0, filename.lastIndexOf('.'))
                  filename = filename + '.webp'
                }
                let url = process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'grid_nostamp' + process.env.PATH_DEFAULT_MEDIA + filename.replace(/ /g, '%20')

                return 'background-image:url(' + url + ')'
            }

            return null
        }
    }
}
</script>
<style lang="scss" scoped>
.slider-container {
    height: 600px;
    background-repeat: no-repeat;
    background-attachment: fixed;
    // background-position: bottom;

}

/*
.slider-container.mobile {
    display:none;
}
.slider-container.desktop {
    display:block;
}
@media (max-width: 575.98px) { 
    .slider-container.mobile {
        display:block;    
    }
    .slider-container.desktop {
        display:none;
    }
}
*/

@media only screen and (max-width: 840px){
    .slider-container {
        height: 480px;
    }
}

@media (max-width: 767.98px) { 
    .slider-container {
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center center;
    }
}
</style>
