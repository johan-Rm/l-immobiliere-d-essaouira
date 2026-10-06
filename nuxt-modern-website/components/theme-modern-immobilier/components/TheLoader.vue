<template>
<div>
      <div v-if="submit" class="submit-page">
        <div class="bg-message">
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
              <i class="fa fa-pause"></i>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
              <p>{{ message }}</p>
            </div>
          </div>
        </div>
      </div>
      <div v-if="loading" class="loading-page">
        <img v-if="$device.isMobile" class="mobile" :src="getLogoStamp('stamp.png')" width="100"/>
        <img v-else class="desktop" :src="getLogoStamp('stamp.png')" width="200"/>
      </div>
</div>
</template>

<script>
export default {
  name:'TheLoader',
  data: () => ({
    submit: false,
    loading: false,
    message: ""
  }),
  methods: {
    begin() {
      this.message = this.$i18n.t('Merci de patienter') + "..."
      this.submit = true
    },
    end() {
      this.submit = false
    },
    start () {
      this.loading = true
    },
    finish () {
      this.loading = false
    },
    getLogoStamp(filename) {
        return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename
    }
  }
}
</script>

<style scoped>
.submit-page {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /*background: rgba(255, 255, 255, 0.8);*/
  text-align: center;
  padding-top: 200px;
  font-size: 30px;
  font-family: sans-serif;
  z-index: 100000;

}
.loading-page {
    position: fixed;
    text-align: center;
    top: 0;
    left: 0;
    padding-top: 200px;
    width: 100%;
    height: 100%;
    z-index: 100000;
}
.loading-page img {
    animation: blink 2s infinite;
}

.submit-page .bg-message
{
  background-color: black;
  opacity: 0.9;
  animation: none;
  width: 500px;
  display: inline-block;
  vertical-align: middle;
}

.submit-page .bg-message p
{
  animation: blink 2s infinite;
  color: white;
  padding:10px 0;
  margin:0;
}

.submit-page .bg-message .row
{
  padding:35px 20px
}

.submit-page i
{
  font-size:3rem;
  color:#fff;
}
@media (min-width: 992px) {
    .mobile {
        display:none;
    }
}
@media (max-width: 991px) {
    .desktop {
        display:none;
    }}

</style>
