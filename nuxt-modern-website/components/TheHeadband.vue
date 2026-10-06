<template>
  <div class="content-headband">
    <div
      class="wt-bnr-inr bg-parallax bg-bottom-center lz-loading _ratio-container _unknown-ratio-container"
    >
      <img
        v-if="data.primaryImage"
        :width="
          $getImageSizeByFilterSets(
            'width',
            getFormat(data.primaryImage, data.secondaryImage)
          )
        "
        :height="
          $getImageSizeByFilterSets(
            'height',
            getFormat(data.primaryImage, data.secondaryImage, true)
          )
        "
        class="img-responsive img-responsive-horizontal full zoom _lazyload"
       
        :src="imagePath(data.primaryImage, data.secondaryImage, false)"
        :alt="imageAlt(data.primaryImage)"
        @load="onImgLoad"
      >
      <img
        :width="
          $getImageSizeByFilterSets(
            'width',
            getFormat(data.primaryImage, data.secondaryImage, false)
          )
        "
        :height="
          $getImageSizeByFilterSets(
            'height',
            getFormat(data.primaryImage, data.secondaryImage, false)
          )
        "
        class="img-responsive img-responsive-vertical full zoom _lazyload"
        
        :src="imagePath(data.primaryImage, data.secondaryImage, true)"
        :alt="imageAlt(data.primaryImage)"
        @load="onImgLoad"
      >
      <div class="overlay-main bg-black opacity-04" />
      <div class="container">
        <div class="wt-bnr-inr-entry">
          <div class="banner-title-outer">
            <transition name="slide-fade">
              <div
                v-if="show"
                class="banner-title-name"
              >
                <client-only>
                  <h1
                    v-if="checkDevice()"
                    class="text-white text-uppercase letter-spacing-5 font-18 font-weight-300"
                  >
                    <span class="item-headband">{{
                      $t(data.alternativePushForward.nature)
                    }}</span>
                    <span class="item-headband">{{
                      $t(data.alternativePushForward.type)
                    }}</span>
                    <span
                      v-if="data.alternativePushForward.floorSize > 0"
                      class="item-headband"
                    >{{ $t(data.alternativePushForward.floorSize) }} m²</span>
                    <span class="item-headband">{{
                      $t(data.alternativePushForward.place)
                    }}</span>
                    <span v-if="data.alternativePushForward.price > 0" class="item-headband">{{ $t(data.alternativePushForward.price) }} €</span>
                  </h1>
                  <h1
                    v-else
                    class="text-white text-uppercase letter-spacing-5 font-18 font-weight-300"
                  >
                    {{ $t(data.pushForward).replace("- 0 m²", "") }}
                  </h1>
                </client-only>
              </div>
            </transition>
          </div>
          <div class="p-tb20">
            <div>
              <transition name="slide-fade">
                <ul
                  v-if="show"
                  class="wt-breadcrumb breadcrumb-style-2"
                >
                  <li
                    v-for="(value, index) in data.breadcrumb"
                    :key="index"
                  >
                    <nuxt-link
                      v-if="value.route"
                      :to="
                        getLocalizedRoute({
                          name: value.route.name,
                          params: value.route.params,
                        })
                      "
                    >
                      {{ value.name }}
                    </nuxt-link>
                    <span v-else>{{ value.name }}</span>
                  </li>
                </ul>
              </transition>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  name: "TheHeadband",
  props: {
    data: {
      type: Object,
    },
  },
  head() {
    return {
      __dangerouslyDisableSanitizers: ["script"],
      script: [
        {
          innerHTML: JSON.stringify(this.structuredData),
          type: "application/ld+json",
        },
      ],
    };
  },
  computed: {
    ...mapState({
      show(state) {
        return state.organization.headbandShow;
      },
    }),
    structuredData() {
      const itemListObject = [];
      this.data.breadcrumb.forEach(function (page, i) {
        itemListObject[i] = {
          "@type": "ListItem",
          position: page.name,
          item: {
            "@id": page.slug,
            name: page.name,
          },
        };
      });

      return {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        itemListElement: [itemListObject],
      };
    },
  },
  mounted() {
    // console.log('mounted headband')
    setTimeout(
      () => this.$store.commit("organization/setHeadbandShow", true),
      100
    );
  },
  updated() {
    // console.log('updated headband')
    setTimeout(
      () => this.$store.commit("organization/setHeadbandShow", true),
      100
    );
  },
  methods: {
    onImgLoad() {
      this.$store.commit("organization/setHeadbandIsLoaded", true);
    },
    checkDevice: function () {
      // console.log('checkDevice')
      // console.log(this.$device.isMobile)
      // console.log(this.data.alternativePushForward)
      if (this.data.hasOwnProperty("alternativePushForward")) {
        // console.log(Object.keys(this.data.alternativePushForward).length)
        if (
          true == this.$device.isMobile &&
          this.data.alternativePushForward &&
          Object.keys(this.data.alternativePushForward).length > 0
        ) {
          return true;
        }
      }

      return false;
    },
    getDescription: function () {
      if (
        this.data.hasOwnProperty("descriptionDetails") &&
        this.$device.isMobile
      ) {
        // nature, type, surface, place, price
        let html =
          '<div class="item-headband">' +
          this.data.descriptionDetails.nature +
          "</div>";

        return html;
      }

      return this.$i18n.t(this.data.description);
    },
    imageIsMobile() {
      return this.$device.isMobile 
        &&
        typeof window !== "undefined" 
        &&
        window.innerWidth <= 575.98
    },
    imageAlt: function (image) {
      // console.log('image alt headband')
      // console.log(image)
      // console.log(image.alt)

      if (null !== image) {
        return image.alt;
      }

      return null;
    },
    imageName: function (image) {
      if (null !== image) {
        return image.name;
      }

      return null;
    },
    verticalExist(primaryImage, secondaryImage, vertical = true) {
      var media = this.getMedia(primaryImage, secondaryImage, vertical);

      return media.vertical;
    },
    getFormat: function (primaryImage, secondaryImage, vertical = true) {
      var media = this.getMedia(primaryImage, secondaryImage, vertical);

      return media.format;
    },
    getMedia: function (image, secondaryImage, vertical = true) {
      var format = "large_nostamp";
      let isVertical = false

    
      if (
        // this.$device.isMobile &&
        typeof window !== "undefined" 
        &&
        // window.innerWidth <= 575.98
         vertical
      ) {
        format = "grid_nostamp";
        if (null !== secondaryImage) {
          image = secondaryImage
          isVertical = true
          format = "vertical_large_nostamp";
        }
      }

      return {
        format: format,
        image: image,
        "vertical": isVertical
      };
    },
    imagePath: function (primaryImage, secondaryImage, vertical) {
      var media = this.getMedia(primaryImage, secondaryImage, vertical);

      var format = media.format;
      var image = media.image;
      if (image.hasOwnProperty("filename")) {
        let filename = image.filename;
        if (!this.$device.isMacOS && !this.$device.iOS) {
          filename = filename.substr(0, filename.lastIndexOf("."));
          filename = filename + ".webp";
        }

        return (
          process.env.URL_CDN +
          process.env.PATH_FORMAT_MEDIA +
          format +
          process.env.PATH_DEFAULT_MEDIA +
          filename.replace(/ /g, "%20")
        );
      }

      return null;
    },
    checkData(data) {
      if (data.length === 0) {
        throw { statusCode: 404, message: "Post not found" };
        return false;
      }

      return true;
    },
  },
};
</script>
<style lang="scss" scoped>
/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all 0.9s ease;
}
.slide-fade-leave-active {
  transition: all 0.1s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}

.content-headband {
  overflow: hidden;
}
.content-headband {
  position: relative;
  display: block;
}

.full {
  position: absolute;
  height: auto;

  bottom: 0;
  left: 0;

  margin: auto;
  display: block;
}
h1 > span.item-headband {
  display: inline-block;
}
.zoom {
  animation: scale 99s linear infinite;
}

@keyframes scale {
  50% {
    -webkit-transform: scale(1.2);
    -moz-transform: scale(1.2);
    -ms-transform: scale(1.2);
    -o-transform: scale(1.2);
    transform: scale(1.2);
  }
}

.wt-breadcrumb li {
  text-transform: lowercase;
}
.wt-breadcrumb li::first-letter {
  text-transform: uppercase;
}
@media screen and (max-width: 480px) {
  .content-headband img {
    position: absolute;
    bottom: 0;
    height: 100%;
    width: 100%;
  }
}

.content-headband img {
  z-index: 0;
}

.content-headband .lz-loading:before {
  content: "";
  position: absolute;
  z-index: 0;
  top: 50%;
  left: 50%;
  margin-top: -32px;
  margin-left: -32px;
  width: 64px;
  height: 64px;
  border-radius: 64px;
  animation: scaleout 1.2s infinite ease-in-out;

  /* Tweak as needed */
  mix-blend-mode: soft-light;
  background-color: rgba(0, 0, 0, 0.9);
}

.wt-bnr-inr-entry {
  padding-top: 70px;
}

.wt-bnr-inr {
  height: 595px;
  max-height: 595px;

  overflow: hidden;
}

.wt-breadcrumb li a:hover {
  color: var(--color-secondary);
}
/*********************************************************
***************** RESPONSIVE BREAKPOINTS *****************
*** https://getbootstrap.com/docs/4.1/layout/overview/ ***

@media (min-width: 1200px) {
  .content-headband .wt-bnr-inr {
    height:750px;
    max-height:750px;
  }
  .content-headband .wt-bnr-inr-entry .banner-title-name {
    width: 640px;
  }
}

**********************************************************/
@media (min-width: 768px) and (max-width: 991.98px) {
  .content-headband .wt-bnr-inr {
    height: 330px;
    max-height: 330px;
  }
  .content-headband .wt-bnr-inr-entry .banner-title-name {
    width: 640px;
  }
}

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) and (max-width: 767.98px) {
  .content-headband .wt-bnr-inr {
    height: 330px;
    max-height: 330px;
  }
  .content-headband .wt-bnr-inr-entry .banner-title-name {
    width: 640px;
  }

  .content-headband .container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: inherit;
    margin-left: inherit;
  }
}

img {
  text-indent: -9999px;
}
</style>
