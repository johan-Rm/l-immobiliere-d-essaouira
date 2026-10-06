export default function ({ store }) {
  	store.commit('organization/setBodyClass', "")
  	store.commit('organization/setHeadbandShow', false)
  	store.commit('organization/setSliderShow', false)
  	// console.log('middleware store')
}