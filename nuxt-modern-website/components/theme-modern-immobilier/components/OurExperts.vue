<template>
    <component :is="template" :params="params"></component>
</template>

<script>
export default {
    name:'OurExperts',
    data () {
        return{
            params: {}
        }
    },
    computed: {
        template () {
            const name = 'Full'
            
            return () => import(`~/components/theme-modern-immobilier/components/OurExperts${name}`)
        }
    }
}
</script>
