<template>
    <div class="our-experts section-full bg-white">
        <div class="container-fluid">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 bg-repeat" :style="getBackgroundImage('ptn-1.png')">
                        <div class="_wt-left-part m-experts p-tb90">
                            <div class="section-head text-left text-black">
                                <h2 class="text-uppercase font-36"> {{ $t('nos experts') }} </h2>
                                <h4 class="">{{ $t('Une équipe pluridisciplinaires à votre service') }}</h4>
                                <div class="wt-separator-outer">
                                    <div class="wt-separator bg-primary"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div
                                v-for="(value,index)  in data.slice(1, 5)"
                                :key="index"
                                class="col-md-4 col-sm-12 col-xs-12 col-xs-100pc m-tb15">
                                    <div class="wt-team-six bg-white">
                                        <div class="wt-team-media wt-thum-bx wt-img-overlay1">
					    <div 
                                                class="_lz-loading ratio-container unknown-ratio-container"
                                                :style="getBackgroundImage('avatar.png')"
                                            >
                                              <template v-if="value.primaryImage">

                                             
                                                <img v-if="$device.isMobile" 
                                                    :width="$getImageSize(value.primaryImage, 'width')" 
                                                    :height="$getImageSize(value.primaryImage, 'height')"
                                                    :data-src="getImagePath(value.primaryImage, 'mobile')" 
                                                    :alt="getAltImg(value.person.firstname)" 
                                                    class="lazyload" 
                                                />
                                                <img v-else 
                                                    :width="$getImageSize(value.primaryImage, 'width')" 
                                                    :height="$getImageSize(value.primaryImage, 'height')"
                                                    :data-src="getImagePath(value.primaryImage, 'desktop')"
                                                    :alt="getAltImg(value.person.firstname)" 
                                                    class="lazyload" 
                                                />
                                                </template>
                                            </div>
                                        </div>
                                        <div class="wt-team-info text-center bg-white p-lr10 p-tb20">
                                            <h4 class="wt-team-title m-a0">
                                                <!-- <a href="javascript:void(0);"> -->
                                                    {{ value.person.firstname }} <span class=" text-uppercase">{{ value.person.lastname }}</span>
                                                <!-- </a> -->
                                            </h4>
                                            <p class="m-b0 text-center" v-html="getValue(value.description)"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'OurExpertsFull',
    props: {
        params: {
          type: Object
        }
    },
    computed: {
        ...mapState({
            data: state => state.team.list,
            logoName: state => state.organization.item.name
        })
    },
    methods: {
        getValue(value) {
            return value.replace(/\n/g, '<br>')
        },

        getAltImg: function(name) {

            return name + ' | ' + this.logoName
        },
        getBackgroundImage: function (filename) {
            if(null !== filename) {
                return 'background-image:url(' + process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename + ')'
            }

            return null
        },
        getMainImagePath: function (image, device) {
          if(null !== image) {
            let format = 'team_square'
            if('mobile' == device && (typeof window !== 'undefined' && window.innerWidth < 575.98)) {
              format = 'team_square_small'
            }

            let filename = image.filename
            if(!this.$device.isMacOS && !this.$device.iOS) {
              filename = filename.substr(0, filename.lastIndexOf('.'))
              filename = filename + '.webp'
            }

            return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
          }
              
          return null
        },
        getImagePath: function (image, device) {
          if(null !== image) {
            let format = 'team_square_medium'
            if('mobile' == device) {
              format = 'team_square_small'
            }

            let filename = image.filename
            if(!this.$device.isMacOS && !this.$device.iOS) {
              filename = filename.substr(0, filename.lastIndexOf('.'))
              filename = filename + '.webp'
            }

            return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
          }
              
          return null
        }
    }
}
</script>



<style lang="scss" scoped>

.our-experts .wt-team-six .wt-team-info::after
, .our-experts .wt-team-six .wt-team-info::before {
    background-color: var(--color-primary);
}
.our-experts .wt-team-six .overlay-bx {
    opacity: 0.5;
}
.wt-team-title a {
  cursor: auto;
}

.our-experts img.desktop {
    display: block;
}

.our-experts img.mobile {
    display: none;
}

.our-experts h4 {
  color: var(--color-primary);
}

.our-experts p {
  min-height: 48px;
}

/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***
**********************************************************/
@media (min-width: 768px) and (max-width: 991.98px) { 
    .our-experts .team-outer {
        margin-top: 0;
    }
}

@media (max-width: 767.98px) { 
    .our-experts img.mobile {
        display: block;
    }

    .our-experts img.desktop {
        display: none;
    }
}

</style>
