<template>
    <component :is="template" :params="params"></component>
</template>
<script>
export default {
    name: 'TheFooter',
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
            // console.log("process.env.URL_API")
            // console.log(process.env.URL_API)
            let name = 'Full'
            if (this.params.hasOwnProperty('template')) {
                name = 'Light'
            }

            return () => import(`~/components/theme-modern-immobilier/components/Footer${name}`)
        }
    }
}
</script>