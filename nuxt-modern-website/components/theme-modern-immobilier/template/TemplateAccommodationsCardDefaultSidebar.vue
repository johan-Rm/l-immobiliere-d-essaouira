<template>
<div class="page-content">
    <div class="card section-full p-tb90">
        <div class="container">
            <div class="row accommodation-top" :style="style">
                <div class="col-md-8 col-sm-12 gallery">
                    <template v-if="checkImagesGallery(gallery)">
                        <GalleryAccommodation :params="{ 'hideStamp': hideStamp }" :data="gallery" />
                    </template>
                </div>
                <div class="col-md-4 col-sm-12">
                    <aside class="side-bar">
                        <div class="details">
                            <ButtonCallContact/>
                            <ProductDetails/>
                            <div class="print-button">
                                <button v-if="pdf" @click="openPdf(pdfPath)" type="button" class="print col-md-12 site-button text-uppercase letter-spacing-2 font-weight-800" data-toggle="modal"> <i class="fa fa-print"></i> {{ $t('Imprimer') }}</button>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </aside>
                </div>
            </div>
            <div class="row m-t40">
                <div class="col-md-8 col-sm-12">
                    <template v-if="checkData(place)">
                        <DescriptionProductPlace :data="place"/>
                    </template>
                    <template v-if="checkData(description)">
                        <div class="wt-divider bg-black">
                            <i class="icon-dot c-square"></i>
                        </div>
                        <div class="wt-tabs bg-tabs">
                            <ul class="nav nav-tabs">
                                <li v-if="checkData(description)" class="active">
                                    <a data-toggle="tab" href="#tabDescription">
                                        {{ $t('description') }}
                                    </a>
                                </li>
                                <li v-if="checkData(ourOpinion)">
                                    <a data-toggle="tab" href="#tabOurOpinion">
                                        {{ $t('notre avis') }}
                                    </a>
                                </li>
                                 <li v-if="count(details)">
                                    <a data-toggle="tab" href="#tabDetails">
                                        {{ $t('détails') }}
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div v-if="checkData(description)"
                                    id="tabDescription" class="tab-pane active"
                                >
                                    <DescriptionDefault :data="getDescription($store.state.i18n.currentLocale, 'description', 'Accommodation', slug)"/>
                                </div>
                                <div v-if="checkData(ourOpinion)"
                                    id="tabOurOpinion" class="tab-pane"
                                >
                                    <OurOpinionDefault :data="getOurOpinion($store.state.i18n.currentLocale, 'ourOpinion', 'Accommodation', slug)"/>
                                </div>
                                 <div v-if="count(details)"
                                    id="tabDetails" class="tab-pane"
                                >
                                    <PropertyDetails :data="details" />
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-if="count(amenities)">
                        <div class="wt-divider bg-black"><i class="icon-dot c-square"></i></div>
                        <AmenitiesList :data="amenities" />
                    </template>
                    <template v-if="issetFloorPlan(pdfs)">
                        <div class="wt-divider bg-black"><i class="icon-dot c-square"></i></div>
                        <FloorPlan :data="getFloorPlan(pdfs)" />
                    </template>
                    <div class="wt-divider bg-black"><i class="icon-dot c-square"></i>
                    </div>
                    <div v-if="checkImagesGallery(gallery)" class="images-list col-md-12 col-sm-12">
                        <div class="row" v-for="i in Math.ceil(gallery.imageGalleries.length / 2)">
                        <div
                            class="masonry-item cat-1 col-lg-6 col-md-6 col-sm-12 m-b30 p-lr0"
                            v-for="(slide,index) in gallery.imageGalleries.slice((i - 1) * 2, i * 2)"
                            :key="index"
                        >
                            <div
                                class="lz-loading ratio-container unknown-ratio-container"
                                :class="getClassRatio(slide.image)"
                            >
                                <div class="portfolio-item wt-img-effect ow-img wt-img-effect zoom-slow">
                                    <!-- <img
                                        :width="$getImageSizeByFilterSets('width', 'grid')"
                                        :height="$getImageSizeByFilterSets('height', 'grid')"
                                        class="img-responsive lazyload image-link-popup"
                                        :data-src="imagePath(slide.image)"
                                        :alt="slide.image.alt"
                                        :data-mfp-src="imagePath(slide.image)"
                                    /> -->
                                    <img
                                  
                                    :width="$getImageSizeByFilterSets('width', getFormat('mobile'))"
                                    :height="$getImageSizeByFilterSets('height', getFormat('mobile'))"
                                    :data-src="getImagePath(slide.image, 'mobile')"
                                    :data-mfp-src="getImagePath(slide.image, 'mobile')"
                                    :alt="slide.image.alt"
                                    class="img-responsive img-responsive-vertical lazyload image-link-popup mobile lazyload"
                                    />
                                    <img
                                    :width="$getImageSizeByFilterSets('width', getFormat('desktop'))"
                                    :height="$getImageSizeByFilterSets('height', getFormat('desktop'))"
                                    :data-src="getImagePath(slide.image, 'desktop')"
                                    :data-mfp-src="getImagePath(slide.image, 'desktop')"
                                    :alt="slide.image.alt"
                                    class="img-responsive img-responsive-horizontal lazyload image-link-popup desktop lazyload"
                                    />
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <AccommodationColumn :data="getDataSharing" :params="params"/>
                </div>
            </div>
        </div>
    </div>
    <CallContactFormPopup/>
</div>
</template>
<script>
import { mapState } from 'vuex'
import CallContactFormPopup from '../components/CallContactFormPopup'
import GalleryAccommodation from '../components/GalleryAccommodation'
import RecentPosts from '../components/RecentPosts'
import SearchBar from '../components/SearchBar'
import VideoList from '../components/VideoList'
import AmenitiesList from '../components/AmenitiesList'
import FloorPlan from '../components/FloorPlan'
import DescriptionDefault from '../components/DescriptionDefault'
import OurOpinionDefault from '../components/OurOpinionDefault'
import PropertyDetails from '../components/PropertyDetails'
import AccommodationColumn from '../components/AccommodationColumn'
import DescriptionProductPlace from '../components/DescriptionProductPlace'
import NoData from '../components/NoData'
import ProductDetails from '../components/ProductDetails'
import ButtonCallContact from '../components/ButtonCallContact'
import { initPopup_vertical_center, inintMagnific_popup } from '~/plugins/custom_transform_to_export.js'
import toLower from 'lodash.tolower'
export default {
    name: 'AccommodationCardDefaultSidebar',
    props: {
        params: {
            type: Object
        }
    },
    data: () => ({
        isMobile: false,
        style: "background-image:url(" + process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + "ptn-1.png);"
      }
    ),
    components:{
            GalleryAccommodation
            ,RecentPosts
            ,SearchBar
            ,VideoList
            ,AmenitiesList
            ,FloorPlan
            ,DescriptionDefault
            ,PropertyDetails
            ,AccommodationColumn
            ,DescriptionProductPlace
            ,NoData
            ,OurOpinionDefault
            ,ProductDetails
            ,ButtonCallContact
            ,CallContactFormPopup
    },
    computed: {
        ...mapState({
            isActive: state => state.accommodations.item.isActive,
            metaTitle: state => state.accommodations.item.metaTitle,
            description: state => state.accommodations.item.description,
            gallery: state => state.accommodations.item.gallery,
            place: state => state.accommodations.item.place,
            ourOpinion: state => state.accommodations.item.ourOpinion,
            details: state => state.accommodations.item.details,
            slug: state => state.accommodations.item.slug,
            amenities: state => state.accommodations.item.amenities,
            pdfs: state => state.accommodations.item.pdfs,
            pdf: state => state.accommodations.item.slug,
            hideStamp: state => state.accommodations.item.hideStamp
        }),
        checkDataIsActive() {
            if(false === this.isActive) {
                return false
            }

            return true
        },
        pdfPath() {
            const slug = this.$tradLinkSlug(this.$store.state.i18n.currentLocale, this.pdf, 'accommodation')

            return process.env.URL_CDN + '/pdf/'+ this.$store.state.i18n.currentLocale + '-' + slug + '.pdf'
        },
        getDataSharing(){
            return{
                url: process.env.URL_WEBSITE + this.$route.fullPath ,
                title:`${ this.metaTitle } | ${ this.$store.state.organization.item.name }`,
                description: this.description
            }
        }
    },
    methods: {
        getFormat: function (device) {
            let format = 'grid'
            if('tablet' == device) {
              format = 'grid'
            }
            else if(
              'mobile' == device
              && (typeof window !== 'undefined' && window.innerWidth > 575.98)
            ) {
              format = 'grid'
            }
            else if(
              'mobile' == device
              && (typeof window !== 'undefined' && window.innerWidth <= 575.98)
            ) {
              format = 'grid'
            }

            return format
        },
        getOurOpinion(lang, fieldName, entityName, slug) {

          let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
          var html = this.$i18n.t(key)

          return html
        },
        openPdf(path) {
            window.open(path, '_blank', 'fullscreen=yes');
        },
        getClassRatio: function(image) {
            if (null !== image) {
              if (this.isPortrait(image)) {

                return 'isVertical'
              }
            }

            return 'isHorizontal'
        },
        checkImagesGallery(gallery) {
            // if(null !== gallery)
            if (null !== gallery) {
                return true
            }

            return false
        },
        isPortrait: function(image) {
          if (image.hasOwnProperty('dimensions')) {
            let w = image.dimensions[0]
            let h = image.dimensions[1]

            if(Number(h) > Number(w)) {
              return true
            }
          }

          return false
        },
        getImagePath: function (image, device) {
          if(null !== image) {
            let format = this.getFormat(device)
            let filename = image.filename
            if(!this.$device.isMacOS && !this.$device.iOS) {
              filename = filename.substr(0, filename.lastIndexOf('.'))
              filename = filename + '.webp'
            }

            return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
          }

          return null
        },
        imagePath: function (image) {
          const nostamp = (this.hideStamp)? '_nostamp': ''
          if (null !== image) {
            if (this.isPortrait(image)) {
              let format = 'vertical' + nostamp

              return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + image.filename
            }
            let format = 'grid' + nostamp

            let filename = image.filename
            if(!this.$device.isMacOS && !this.$device.iOS) {
              filename = filename.substr(0, filename.lastIndexOf('.'))
              filename = filename + '.webp'
            }

            return process.env.URL_CDN + process.env.PATH_FORMAT_MEDIA + format + process.env.PATH_DEFAULT_MEDIA + filename
          }

          return null
        },
        issetFloorPlan(data){
            var r = false
            data.forEach(function (value, key) {
                if(value.type.slug === "plan") {

                    r = true
                }
            })

            return r
        },
        getFloorPlan(data){
            var plan = {}
            data.forEach(function (value, key) {
                if(value.type.slug === "plan") {
                    plan = value
                }
            })

            return plan
        },
        toCurrencyString(number){
            return number.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' })
        },
        natureOfProperty(type, nature) {
            if('vente' == toLower(nature)) {
                return type + ' à vendre'
            } else  if('location' == toLower(nature)) {
                return type + ' à louer'
            }
        },
        infosProperty(nbOfRooms, areaSize) {
            nbOfRooms = 1
            let label = (nbOfRooms > 1 )? 'chambres': 'chambre'

            return nbOfRooms + ' ' + this._i18n.t(label) + ' - '
                    + areaSize + ' m²'
        },
        count(array) {
            if (Array.isArray(array) && array.length) {

                return true
            }

            return false
        },
        checkData(data) {

            if(data) {
                return true
            }
            return false
        },
        getDescription(lang, fieldName, entityName, slug) {

          let key = this.$getHtmlKey(lang, fieldName, entityName, slug)
          var html = this.$i18n.t(key)

          return html
        }
    },
    mounted () {
        if(this.$device.isMobile) {
          this.isMobile = true
        }
        this.$nextTick(function(){ initPopup_vertical_center(); inintMagnific_popup(); }.bind(this))
    }
}
</script>

<style type="text/css">
.print-button{
    text-align:center;
}

.images-list img.desktop {
    display: block;
}

.images-list img.mobile {
    display: none;
}

@media (max-width: 767.98px) {
    .images-list img.mobile {
        display: block;
    }

    .images-list img.desktop {
        display: none;
    }
}
@media (max-width: 991.98px) { 
    .images-list img.mobile {
        display: block;
    } 
}
@media (min-width: 576px) {
    .images-list .masonry-item {
        padding:0 5px;
    }
}
@media (min-width: 992px) {
    .print-button {
        position: absolute;
        bottom:0;
        width:100%;
    }
}
@media (min-width: 992px) {
    .details {
        position:relative;
        height:568.75px ;
    }
}
@media (min-width: 768px) {
    .row .gallery {
        padding-left: 0;
    }
}
.accommodation-top {
  background: rgb(146,172,190, 0.1);
}

.image-link-popup {
  cursor: pointer;
}

.images-list .masonry-item {
  max-height: 486px;
  overflow: hidden;
}

.images-list .ratio-container.isVertical:after {
  /* ratio = calc(552 / 360 * 100%) */
  padding-bottom: 153.3333%;
}

.images-list .ratio-container.isHorizontal:after {
  /* ratio = calc(500 / 800 * 100%) */
  padding-bottom: 62.5%;
}

img {
   text-indent: -9999px;
}

.lz-loading:before
{
  mix-blend-mode: inherit;
}

.images-list .portfolio-item {
    z-index: 500;
}
</style>
