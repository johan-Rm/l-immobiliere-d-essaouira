<template>
    <div class="social-media">
      <div class="extra-nav">
        <div class="extra-cell">
            <div class="dropdown share-icon-btn">
                    <!-- <a href="javascript:;" class="site-search-btn dropdown-toggle" :title="$t('trouvez-nous sur les réseaux sociaux')" data-toggle="dropdown"><i class="fa fa-share-alt"></i></a> -->
                    <!-- <div class="dropdown-menu bg-primary"> -->
                    <div class="top-bar">
                        <ul class="social-bx list-inline">
                            <li
                                v-for="(value, index) in data"
                                :key="index"
                            >
                                <a :href="value.url" :class="getClassIcon(value)" target="_BLANK"></a>
                            </li>
                            <!-- <li><a href="javascript:void(0);" class="fa fa-facebook"></a></li>
                            <li><a href="javascript:void(0);" class="fa fa-twitter"></a></li>
                            <li><a href="javascript:void(0);" class="fa fa-linkedin"></a></li>
                            <li><a href="javascript:void(0);" class="fa fa-rss"></a></li>
                            <li><a href="javascript:void(0);" class="fa fa-youtube"></a></li>
                            <li><a href="javascript:void(0);" class="fa fa-instagram"></a></li> -->
                        </ul>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    name: 'SocialMedia',
    props: {
        params: {
            type: Object
        }
    },
    computed:{
        ...mapState({
            data: state => state.organization.list
        }),
        classname(string) {
            return string.toLowerCase()        
        }
    },
    methods: {
        getClassIcon: function(value) {
            return "fab fa-" + value.slug
        }
    }
}
</script>
<style type="text/css" scoped>
.share-icon-btn .social-bx.list-inline {
     display: flex;
        align-items: center;
}
.share-icon-btn .social-bx.list-inline > li {
     display: flex;
        align-items: center;
    margin: 10px 0px;
    text-align: center;
    margin-right: 5px;
}

.social-bx li a, .login-bx li a {
    color: #fff;
    font-size: 20px;
}

.social-media .dropdown-menu.bg_primary {

}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (max-width: 767.98px) { 
  .social-media.extra-nav {
    /* display: none; */
  }

    .share-icon-btn .social-bx.list-inline > li {
        display: flex;
        align-items: center;
        margin: 0px 0px;
        text-align: center;
        margin-right: 5px;
    }

    .social-bx li a, .login-bx li a {
        color: #fff;
        font-size: 25px;
    }
}

@media (min-width: 992px) and (max-width: 1199.98px) {
  .social-media.extra-nav {
    /*display: none;*/
  }
  
}
</style>