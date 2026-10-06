export const actions = {
  async nuxtServerInit({ dispatch, store, context , error }) {
    	await dispatch('team/getMainContact')
    	await dispatch('team/getList')
      await dispatch('organization/getItem')
      await dispatch('organization/getFilterSets')
		  await dispatch('organization/getComponents')
      await dispatch('organization/getListBy', { 'type.slug':'lien-reseau-social' })
      await dispatch('menu/getArticleNosServices', { 'tags.slug': 'nos-services' })
      await dispatch('footer/getRecentAccommodations')
      await dispatch('footer/getUsefulLinks')
      await dispatch('footer/getTags', { isActive: 'true', isLocation: 'false' })
      await dispatch('footer/getReviewLinks')
      await dispatch('footer/getArticle', { slug: 'lagence' })
  }
}