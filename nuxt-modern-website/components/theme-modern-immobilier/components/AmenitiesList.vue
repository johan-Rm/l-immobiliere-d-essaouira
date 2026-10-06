<template>
    <div class="properties-amenities mb-40">
        <div class="wt-post-title ">
            <h2 class="post-title ">
                <a href="javascript:void(0);" class="text-black font-20 letter-spacing-2 font-weight-600 ">
                    {{ $t('commodités') }}
                </a>
            </h2>
        </div>
        <div class="row">
            <ul class="amenities">
                <li
                    v-for="(value,index) in data"
                    :key="index"
                    class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <i class="fa fa-check-square"></i>  {{ value.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AmenitiesList',
    props:{
        data:{
            type:Array
        }
    }
}
</script>

<style lang="scss">
ul {   
    margin: 0;
    padding: 0;
    list-style: none;
}

.properties-amenities ul li i {
    color: var(--color-secondary);
}

.properties-amenities ul li i {
    margin-right: 15px;
    text-align: center;
    font-size: 15px;
    font-weight: 400;
}

</style>
