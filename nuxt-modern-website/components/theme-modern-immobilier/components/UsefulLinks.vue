<template>
    <div class="widget widget_services inline-links">
        <h2 class="widget-title"> {{ $t('Nos services') }} </h2>
        <ul>
            <li
            v-for="(value, index) in  data"
            :key="index" >

                <h4>
                    <!-- <i class="fas fa-angle-double-right"></i> -->
                <span class="angle-right-regular">&gt;&gt;</span>
                    <nuxt-link :to="getLocalizedRoute({
                        name: 'news-category-slug'
                        , params: {
                            category: $tradLinkSlug($store.state.i18n.currentLocale, value.category.slug, 'tag')
                            , slug: $tradLinkSlug($store.state.i18n.currentLocale, value.slug, 'article')
                        }
                    })">
                        <span v-if="value.alternativeHeadline">
                            {{ $t(value.alternativeHeadline) }}
                        </span>
                        <span v-else>
                            {{ $t(value.headline) }}
                        </span>
                    </nuxt-link>
                </h4>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    name: 'UsefulLinks',
    computed: {
        ...mapState({
            data: state => state.footer.useful_links
        })
    }
}
</script>
<style scoped>
h4{
    font-size: 11px;
    line-height: 0px;
    font-weight: 400;
}
.widget_services ul li {

  position: inherit;

}
</style>
