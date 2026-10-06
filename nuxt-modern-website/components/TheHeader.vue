<template>
    <component :is="template" :params="params"></component>
</template>
<script>
export default {
    name: 'TheHeader',
    props: {
        params: {
            type: Object,
            default: () => ({
                bg_primary: 'bg-primary'
            })
        }
    },
    computed: {
        template () {  
            let name = 'Simple'
            if (this.params.hasOwnProperty('template')) {
                name = 'Light'
            }

            return () => import(`~/components/theme-modern-immobilier/components/Header${name}`)
        }
    }
}
</script>
