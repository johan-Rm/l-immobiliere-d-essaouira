<template>
<div class="page-content">
  <h1 class="hidden" >{{ $store.state.organization.item.url }}</h1>
  <WelcomeDefault :params="params.welcome"/>
  <WhoWeAre :params="params.whoWeAre"/>
  <BlogDefault :params="params.blog"/>
  <OurServices :params="params.ourServices"/>
  <OurExperts :params="params.ourExperts"/>
</div>
</template>
<script>
export default {
    name: 'TemplateIndex',
    components : {
        WelcomeDefault: () => import('../components/WelcomeDefault'),
        WhoWeAre: () => import('../components/WhoWeAre'),
        BlogDefault: () => import('../components/BlogDefault'),
        OurServices: () => import('../components/OurServices'),
        OurExperts: () => import('../components/OurExperts')
    },
    data() {
       return {
           params: {
             welcome: {}
             , whoWeAre: {}
             , ourExperts: {}
             , blog: {}
             , ourServices: {}
             , testimonials: {}
           }
       }
    }
}
</script>
