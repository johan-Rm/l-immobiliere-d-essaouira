<template>
    <div >
        <h4 class="tagcloud text-uppercase"> {{ data.name }} </h4>
            <div class="portfolio-item wt-img-effect zoom-slow ">
                <a 
                    class=""
                    :href="data.link"
                    target="_blank"
                >
                    <img :src="imagePath(data.filename)" :alt="data.alt" class="img-responsive">
                </a>

            </div>
            <center>
                <a 
                    class="site-button black radius-no text-uppercase"
                    :href="data.link"
                    target="_blank"
                >
                    <span class="font-12 letter-spacing-5"> {{ $t('plus d\'infos') }} </span>
                </a>
            </center>
    </div>
</template>
<script>
export default {
    name:'TitleImageLink',
    props: {
        data: {
            type:Object
        }
    },
    methods:{
        imagePath: function (image) {
            if(null !== image) {
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + 'grid' + process.env.PATH_DEFAULT_MEDIA + image      
            }

            return null
        }
    }
}
</script>
<style scoped>
.site-button{
    width: 100%;
    text-align: center;
}

</style>
