<template>
<button class="scroltop" aria-label="scroltop">
    <span class="fa fa-angle-up  relative" id="btn-vibrate"></span>
</button>
</template>
<script>
// import { initScrollTop } from '~/plugins/custom_transform_to_export.js'
export default {
    name: "TheScrollTop",
    mounted () {
        // this.$nextTick(function(){ initScrollTop() }.bind(this))
    },
}
</script>
<style lang="scss">
@media (max-width: 767.98px) {
    button.scroltop {
        bottom: 50px;
    }

    .renting-category-slug, .selling-category-slug {
        button.scroltop {
            bottom: 80px;
        }
    }
}
</style>