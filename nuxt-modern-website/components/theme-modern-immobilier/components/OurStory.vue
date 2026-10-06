<template>
<div class="section-full bg-gray p-tb90">
  <div class="container">
      <div class="section-head text-left text-black">
            <h2 class="text-uppercase font-36"> {{ $t(content.title) }} </h2>
            <div class="wt-separator-outer">
                <div class="wt-separator bg-black"></div>
            </div>
        </div>
        <div class="section-content our-story">
            <div 
                v-for="(d, index) in content.data"
                :key="index"
                 class="row">
                    <div v-if=" d.t === 'd'  ">
                        <div class="col-md-6 col-sm-12">
                            <div class="wt-media our-story-pic lz-loading ratio-container unknown-ratio-container">
                                <img :src="getUrl(d.img)" alt="" class="lazyload__" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="wt-box our-story-detail">
                                <h4 v-if="d.title" class="text-uppercase"> {{ d.title }} </h4>
                                <p v-html="$t(d.description)"></p>
                            </div>
                        </div>
                </div>
                    <div v-else>
                        <div class="col-md-6 col-sm-12">
                            <div class="wt-box our-story-detail">
                                <h4 v-if="d.title" class="text-uppercase"> {{ d.title }} </h4>
                                <p v-html="$t(d.description)"></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="wt-media our-story-pic lz-loading ratio-container unknown-ratio-container">
                                <img :src="getUrl(d.img)" alt="" class="lazyload__" />
                            </div>
                        </div>
                  </div>
              </div>
          </div>
  </div>
</div>
</template>
<script>
export default {
    name: 'OurStory',
    props: {
      params: {
          type: Object
      }
    },
    data () {
        return {
            content: {
                "title":"Nos Engagements",
                "data":[
                    {
                       "img":"transparence.jpg",
                       // "title":"TRANSPARENCE",
                       "description": "Nous travaillons sur la base de mandats simples ou exclusifs, et de contrats formalisés par écrit et signés par les parties, afin de garantir les intérêts de chacun.",
                       "t":"d"
                    },
                    {
                       "img":"confiance.jpg",
                       // "title":"CONFIANCE",
                       "description": "Un climat de confiance réciproque est la base d'une relation saine et durable.<br/>Nous mettons tout en oeuvre pour privilégier cette conviction.",
                       "t":"g"
                    },
                    {
                       "img":"communication.jpg",
                       // "title":"COMMUNICATION",
                       "description": "NOUS SAVONS FAIRE PREUVE DE RÉACTIVITÉ ET DE REPORTING PAR L'UTILISATION DES MOYENS D'ÉCHANGES RAPIDES (TÉLÉPHONE, WHATSAPP, MESSENGER, ETC...).<br/>TOUTES LES INFORMATIONS IMPORTANTES SONT FORMALISÉES PAR ÉCRIT.<br/>des retours transparents sont transmis aux vendeurs de biens apres chaque visite pour OPTIMISATION et valorisation de la relation etablie.",
                       "t":"d"
                    },
                    {
                       "img":"disponibilite.jpg",
                       // "title":"DISPONIBILITE",
                       "description": "Nous sommes à l'écoute de nos clients et défendons la notion de service.<br/>Un accompagnement et un suivi professionnel entre la  signature d'un compromis et d'un acte final et une écoute particulière avec interventions rapides s'agissant de nos locataires.",
                       "t":"g"
                    }
                ]
            }
        }
    },
    methods: {
        getUrl(filename) {
            var format = 'blog_horizontal'
            if(this.$device.isMobile) {
                format = 'blog_horizontal'
            }

            
            if(!this.$device.isMacOS && !this.$device.iOS) {
              filename = filename.substr(0, filename.lastIndexOf('.'))
              filename = filename + '.webp'
            }

            if(null !== filename) {
              
                return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename.replace(/ /g, '%20')    
            }
            
            return null  
        }
    }
}
</script>
<style scoped>
.our-story .row:nth-child(2n+1) .our-story-detail h4{
    font-size: 24px;
line-height: 30px;
color:#3e2723
}
.our-story .row:nth-child(2n+1) .our-story-detail span{
    font-size: 20px;
line-height: 26px;
margin-bottom: 20px;
color:black
}
.our-story .row:nth-child(2n) .our-story-detail h4{
    font-size: 24px;
line-height: 30px;
color:#fff
}
.our-story .row:nth-child(2n) .our-story-detail span{
    font-size: 20px;
color:#fff
}

.our-story .ratio-container:after {
    /* ratio = calc(250 / 550 * 100%) */
    padding-bottom: 45.4545%;
}

.our-story img {
  z-index: 4;
}
</style>
