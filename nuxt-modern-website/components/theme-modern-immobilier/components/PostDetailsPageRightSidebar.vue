<template>
    <div class="page-content">
        <div class="section-full p-tb90">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-sm-6">
                        <PagePost :params="params"/>
                    </div>
                    <div class="col-md-4 col-sm-6">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PagePost from '~/components/theme-modern-immobilier/components/PagePost'

export default {
    name: 'PostDetailsPageRightSidebar',
    props: {
        params: {
          type: Object
        }
    },
    components:{
        PagePost
    }
}
</script>
