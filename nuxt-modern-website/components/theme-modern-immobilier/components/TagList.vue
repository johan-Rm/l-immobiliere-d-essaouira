<template>
    <div class="widget widget_tag_cloud">
        <h2 class="widget-title" :class="params.class"> {{ $t('Types de bien') }} </h2>
        <div class="implicit subsection tagcloud">
            <h4>
            <nuxt-link
                v-for="(tag, index) in  getData"
                :key="index"
                :to="getLocalizedRoute({
                    name: getRouteName
                    , params: {
                        category: $tradLinkSlug($store.state.i18n.currentLocale, tag.slug, 'accommodationType')
                    }
                })">
                {{ $t(tag.name) }}
            </nuxt-link>
            </h4>
        </div>
    </div>
</template>
<script>
export default {
    name: 'TagList',
    props: {
        data: {
            type: Array
        },
        params: {
            type: Object
        }
    },
    computed: {
        getRouteName() {
            let name = 'selling-category'
            if(this.params.accommodation_nature == 'location') {
                name = 'renting-category'
            }

            return name
        },
        getData() {
            if (this.params.hasOwnProperty('limit')) {
                   return this.data.slice(0, this.params.limit)
            }

            return this.data
        }
    }
}
</script>
<style lang="scss" scoped>


.widget-title {
  text-transform: uppercase;
}

.widget_tag_cloud a {
  background-color: var(--color-primary);
  margin: 0px 2px 5px;
  color: #fff;
}

.widget_tag_cloud a:hover{
  background-color: var(--color-secondary);
  margin: 0px 2px 5px;
  color: #fff;
}

.size{
  color: #000;
  font-size: 18px;
}
</style>
