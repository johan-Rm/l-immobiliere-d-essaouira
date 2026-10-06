<template>
    <div class="floor-plans">
        <div class="wt-post-title ">
            <h2 class="post-title ">
                <a href="javascript:void(0);" class="text-black font-20 letter-spacing-2 font-weight-600 ">
                    {{ $t('plan d\'étage') }}
                </a>
            </h2>
        </div>
        <img 
        :width="$getImageSize(data, 'width')" 
        :height="$getImageSize(data, 'height')"
        :src="imagePath(data.filename)" 
        alt="floor-plans" 
        class="img-fluid lazyload__" 
        />
    </div>
</template>
<script>
export default {
    name: 'FloorPlan',
    props:{
        data:{
            type: Object
        }
    },
    methods: {
        imagePath: function (filename) {
          return process.env.URL_CDN + process.env.PATH_DEFAULT_DOCUMENT + filename
        }
    }
}
</script>
