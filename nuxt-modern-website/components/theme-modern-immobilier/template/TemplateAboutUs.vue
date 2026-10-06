<template>
    <div class="page-content">
        <AboutUs :params="params.AboutUs"/>
        <OurExperts :params="params.OurExperts"/>
        <OurStory :params="params.OurStory"/>
        <OurServices :params="params.OurServices"/>
        <OurOfficesDefault :params="params.OurOfficesDefault"/>
        <GoogleMap :params="params.GoogleMap"/>
    </div>
</template>
<script>
export default {
    name: 'TemplateAboutUs',
    components : {
        AboutUs: () => import('../components/AboutUs'),
        OurOfficesDefault: () => import('../components/OurOfficesDefault'),
        OurExperts: () => import('../components/OurExperts'),
        OurStory: () => import('../components/OurStory'),
        OurServices: () => import('../components/OurServices'),
        GoogleMap: () => import('../components/GoogleMap')
    },
    data() {
       return {
           params: {
             AboutUs: {}
             , OurExperts: {}
             , OurServices: {}
             , OurStory: {}
             , OurOfficesDefault: {
                year: true
             }
             , GoogleMap: {}
           }
       }
    }
}
</script>