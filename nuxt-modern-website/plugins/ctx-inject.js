import Vue from 'vue'
import enFR from '../lang/translations/slug/en-fr.json'
import en from '../lang/translations/slug/en.json'
import fr from '../lang/translations/slug/fr.json'

export default ({ app, store }, inject) => {
    inject('tradSlug', (locale, key, entity) => {
          let slug = ''
          if('en' == locale) {
              slug = enFR.hasOwnProperty(entity)? (enFR[entity][key] || key): key
          } else {
              slug = fr.hasOwnProperty(entity)? (fr[entity][key] || key): key
          }

          return slug
    })
    inject('getImageSizeByFilterSets', (orientation, format) => {
      let filterSets = store.state.organization.filterSets

      if(filterSets.hasOwnProperty(format)) {
          // console.log("filterSets[format]")
          // console.log(filterSets[format])
         var sizes = filterSets[format].filters.thumbnail.size

        if('width'){

          return sizes[0]
        }
        if('height' == orientation && sizes.hasOwnProperty(1)) {

          return sizes[1]
        }
      }

      return null
    })
    inject('getImageSize', (image, orientation) => {

        var size = '100%'
        if(image.hasOwnProperty('dimensions')) {
          size = image.dimensions[0]
          if('height' == orientation) {
            size = image.dimensions[1]
          }
        }

        return size
    })
    inject('tradLinkSlug', (locale, key, entity) => {
          let slug = ''
          if('en' == locale) {
              slug = en.hasOwnProperty(entity)? (en[entity][key] || key): key
	      if(slug === undefined) {
                  key = enFR.hasOwnProperty(entity)? (enFR[entity][key] || key): key
                  slug = en.hasOwnProperty(entity)? (en[entity][key] || key): key
              }
          } else {
              slug = fr.hasOwnProperty(entity)? (fr[entity][key] || key): key
          }

          return slug
    })
    inject('getDefaultApiArgs', (store, params) => {
      return {
          isActive : 'true'
          , itemsPerPage: store.itemsPerPage
          , 'nature.slug': params.nature
      }
    })
    inject('getRouteApiArgs', (args, route) => {
        if (route.params.hasOwnProperty('category')) { // true
            args['type.slug'] = route.params.category
        }
        if (route.query.hasOwnProperty('page')) { // true
            args['page'] = route.query.page
        }

        return args
    })
    inject('getSearchStoreApiArgs', (args, store) => {
        // [gt] => greater than
        if(store.minBudget > 0) {
            args['price[gte]'] = store.minBudget
        }
        // [lt] => lower than
        if(store.maxBudget > 0) {
            args['price[lte]'] = store.maxBudget
        }
        if("" !== store.duration && "location" === store.nature) {
            args['duration.slug'] = store.duration
        }

        return args
     })
    inject('getHtmlKey', (lang, fieldName, entityName, slug) => {

      return lang.toLowerCase() + '.' + fieldName.toLowerCase() + '.' + entityName.toLowerCase() + '.' + slug
    })

}
