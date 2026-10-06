<template>
<div>
    <ShareList :data="data" :params="getParams('share-list')" />
    <TagList v-if="tags" :data="tags" :params="getParams('tag-list')" />
    <RelatedProduct v-if="related" :data="related" :params="getParams('related-product')"/>
    <template v-if="count(videos)">
        <div class="wt-divider bg-black"><i class="icon-dot c-square"></i>
        </div>
        <VideoList :data="videos" />
    </template>
</div>
</template>
<script>
import { mapState } from 'vuex'
import ShareList from '~/components/theme-modern-immobilier/components/ShareList'
import TagList from '~/components/theme-modern-immobilier/components/TagList'
import VerticalImage from '~/components/theme-modern-immobilier/components/VerticalImage'
import OurClients from '~/components/theme-modern-immobilier/components/OurClients'
import RelatedProduct from '~/components/theme-modern-immobilier/components/RelatedProduct'
import VideoList from '~/components/theme-modern-immobilier/components/VideoList'
export default {
    name: 'AccommodationColumn',
    props: {
        data: {
            type: Object
        },
        params: {
            type: Object
        }
    },
    components:{
         ShareList
         ,TagList
         ,VerticalImage
         ,OurClients
         ,RelatedProduct
         ,VideoList
    },
    computed: {
         ...mapState({
            videos: state => state.accommodations.item.videos,
            tags: state => state.accommodation_types.list,
            nature: state => state.accommodations.item.nature,
            related: state => state.accommodations.listRelated
        })
    },
    methods: {
        getParams(component) {
            let params = {}
            switch (component) {
                case 'tag-list':
                    params = { 'accommodation_nature':  'vente', 'class' :"size" } 
                    if ('location' == this.nature.slug) {
                        params = { 'accommodation_nature':  'location', 'class' :"size" } 
                    }
                    break;
                case 'share-list':
                    params = { class: "p-tb30" , label: 'Partager ce bien' } 
                    
                    break;
                case 'related-product':
                    params = {
                        'route-category-slug-name': this.params.routeCategorySlugName
                    }

                    break;

            }

            return params
        },
        count(array) {
            if(array.length < 1) {

                return false
            }

            return true
        }
    }
}
</script>