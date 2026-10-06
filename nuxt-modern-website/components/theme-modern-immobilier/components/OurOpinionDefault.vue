<template>
    <div class="wt-post-info p-t30 bg-white ">
        <div class="wt-post-text" v-html="data"></div>
    </div>
</template>

<script>
export default {
    name: 'OurOpinionDefault',
    props:{
        data:{
            type:String
        }
    }
}
</script>