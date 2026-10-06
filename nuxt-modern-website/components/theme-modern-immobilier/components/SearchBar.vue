<template>   
    <div class="widget bg-white ">
        <h4 class="widget-title text-uppercase"> {{ $t('Chercher') }} </h4>
        <div class="search-bx">
            <form role="search" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Write your text">
                    <span class="input-group-btn">
                        <button type="submit" class="site-button"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
    </div>     
</template>
<script>
export default {
    name: 'SearchBar',
}
</script>