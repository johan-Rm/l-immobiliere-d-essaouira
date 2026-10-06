<template>
<div class="page-content">
  <ContactForm/>
  <GoogleMap/>
  <OurExperts :params="params.OurExperts"/>
</div>
</template>

<script>
import ContactForm from '../components/ContactForm'
import OurExperts from '../components/OurExperts'
import GoogleMap from '../components/GoogleMap'
export default {
    name: 'TemplateContact',
    components : {
        ContactForm
      , OurExperts
      , GoogleMap
    },
    data() {
       return {
          params: {
             OurExperts: {}
          }
       }
    }
}
</script>
