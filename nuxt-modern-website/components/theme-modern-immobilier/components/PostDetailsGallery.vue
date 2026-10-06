<template>
                    <div class="blog-post blog-lg date-style-1 text-black">
                         <GalleryAccommodation/>
                          <div class="row">
                                <div class="col-md-8 col-sm-6">
                                    <TagList/>
                                </div>

                                 <div class="col-md-4 col-sm-6">
                                     <ShareList/>
                                 </div>    
                          </div>
                    <RelatedArticles/>
                </div>
</template>
<script>
import GalleryAccommodation from '~/components/theme-modern-immobilier/components/GalleryAccommodation'
import TagList from '~/components/theme-modern-immobilier/components/TagList'
import ShareList from '~/components/theme-modern-immobilier/components/ShareList'
import RelatedArticles from '~/components/theme-modern-immobilier/components/RelatedArticles'

export default {
    name: 'PostDetailsGallery',
    components:{
        slider
        ,TagList
        ,ShareList
        ,RelatedArticles
    }    
}
</script>