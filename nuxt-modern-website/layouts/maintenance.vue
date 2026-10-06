<template>
<div class="page-wraper">
  <TheHeader :params="params"/>
    <div class="page-content">
      <div class="page-content-wraper">
        <div class="page-content-container">
            <div class="page-content text_black m-t50 p-lr30">
              <div class="wt-icon-box-wraper bx-style-1 p-a30 center bg-white">
                  <div class="icon-xl m-b20">
                      <span class="icon-cell text-primary"><i class="fa fa-wrench"></i></span>
                  </div>
                  <div class="icon-content">
                    <h2 class="wt-tilte text-uppercase  letter-spacing-2 font-weight-800">
                      En
                      <br/>
                      construction
                    </h2>
                      <p>Notre <b>nouveau site</b> n'est pour le moment pas disponible sur <b>votre version mobile</b>, nous vous invitons à le découvrir sur un autre support et/ou revenir ultérieurement.</p>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  <TheFooter :params="params"/>
</div>
</template>                                                                                          
<script>
import TheHeader from '~/components/TheHeader'
import TheFooter from '~/components/TheFooter'
export default {
    name: 'maintenance',
    components : {
        TheHeader,
        TheFooter
    },
    data: () => (
        {
          params: {
            
              'bg_primary': 'bg-primary',
              'template' : 'Light' 
            
          }
        }
    )
}
</script>

<style lang="scss" scoped>

p {  
  word-break: break-word;  

}  
</style>
