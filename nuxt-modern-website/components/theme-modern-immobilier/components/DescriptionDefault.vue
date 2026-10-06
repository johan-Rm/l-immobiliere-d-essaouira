<template>
    <div class="wt-post-info p-t30 bg-white ">
        <div class="wt-post-text" v-html="data"></div>
    </div>
</template>
<script>
export default {
    name: 'DescriptionDefault',
    props:{
        data:{
        type:String
        }
    }
}
</script>

<style lang="scss">
.wt-post-info ul {   
    
    padding-left: 25px;
    list-style: disc;
}

</style>
