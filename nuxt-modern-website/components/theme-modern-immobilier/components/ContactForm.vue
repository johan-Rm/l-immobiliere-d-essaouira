<template>
        <div class="section-full p-tb80">
            <!-- <notifications group="form-contact" animation-type="velocity" :animation="animation" position="top center" width="100%"> -->
            <notifications group="form-contact" :animation="animation" position="top center" width="100%">
                <template slot="body" slot-scope="props">
                    <div class="vue-notification-template vue-notification" :class="props.item.type">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <i v-if="'error' === props.item.type" class="fa fa-exclamation-triangle"></i>
                                <i v-else class="fa fa-check-square"></i>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9">
                                <div class="notification-title">{{ props.item.title }}</div>
                                <div class="notification-content">{{ props.item.text }}</div>
                            </div>
                        </div>
                    </div>
                </template>
            </notifications>
            <div class="container">
                <div class="section-head text-left text-black">
                    <h2 class="text-uppercase font-36">{{ $t('Contacter l\'agence')}}</h2>
                    <div class="wt-separator-outer">
                        <div class="wt-separator bg-black"></div>
                    </div>
                </div>
                <div class="section-content">
                    <div class="wt-box">
                        <form ref="formTest" class="contact-form cons-contact-form" novalidate @submit.prevent="handleSubmit">
                        	<div class="contact-one p-a40 p-r150">
                                <div class="form-group col-md-6">
                                    <input :aria-label="$t('nom')" v-model="form.firstname" name="firstname" type="text" required :class="errors.fields.firstname" class="form-control" :placeholder="$t('Nom')">
                                </div>
                                <div class="form-group col-md-6">
                                    <input :aria-label="$t('prénom')" v-model="form.lastname" name="lastname" type="text" required :class="errors.fields.lastname"  class="form-control" :placeholder="$t('Prénom')">
                                </div>
                                <div class="form-group col-md-12">
                                    <input :aria-label="$t('e-mail')" v-model="form.email" name="email" type="text" :class="errors.fields.email" class="form-control" required :placeholder="$t('E-mail')">
                                </div>
                                 <div class="form-group col-md-12">
                                    <input :aria-label="$t('phone')" v-model="form.phone" name="phone" type="text" :class="errors.fields.phone" class="form-control" required :placeholder="$t('Téléphone')">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea :aria-label="$t('message')" v-model="form.message" name="message" rows="3" :class="errors.fields.message" class="form-control " required :placeholder="$t('Message')"></textarea>
                                </div>

                                <button name="submit" :disabled="isDemo" type="submit" value="Submit" class="site-button black radius-no text-uppercase">
                                        <span class="font-12 letter-spacing-5"> {{ $t('Envoyer') }} </span>
                                </button>

                                <div class="contact-info bg-black text-white p-a30">
                                    <div class="wt-icon-box-wraper left p-b30">
                                        <div class="icon-sm"><i class="iconmoon-smartphone-1"></i></div>
                                        <div class="icon-content text-white ">
                                            <h5 class="m-t0 text-uppercase"> {{ $t('Numéro de téléphone') }} </h5>

                                            <a 
                                                v-if="$device.isMobile" 
                                                class="btn-phone" 
                                                :href="`tel:${getTransactionsPhone(true)}`" 
                                                :title="$t('Téléphone')" 
                                            >
                                              <h6 class="text">Transactions: {{ getTransactionsPhone(false) }}</h6>
                                            </a>
                                            <h6 v-else class="text">Transactions: {{ getTransactionsPhone(false) }}</h6>
                                            <a 
                                                v-if="$device.isMobile" 
                                                class="btn-phone" 
                                                :href="`tel:${getLocationsPhone(true)}`" 
                                                :title="$t('Téléphone')" 
                                            >
                                              <h6 class="text">Locations: {{ getLocationsPhone(false) }}</h6>
                                            </a>
                                            <h6 v-else class="text">Locations: {{ getLocationsPhone(false) }}</h6>
                                        </div>
                                    </div>

                                    <div class="wt-icon-box-wraper left p-b30">
                                        <div class="icon-sm"><i class="iconmoon-email"></i></div>
                                        <div class="icon-content text-white">
                                            <h5 class="m-t0  text-uppercase"> {{ $t('E-mail') }} </h5>
                                            <h6 class="text"> {{ email }} </h6>
                                        </div>
                                    </div>

                                    <div v-if="addresses" class="wt-icon-box-wraper left">
                                        <div class="icon-sm"><i class="iconmoon-travel"></i></div>
                                        <div class="icon-content text-white">
                                            <h5 class="m-t0  text-uppercase"> {{ $t('ville & pays') }} </h5>
                                            <h6 class="text"> {{ addresses[0].city }}, {{ addresses[0].country }} </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
import { mapState } from 'vuex'
export default {
    name: 'ContactForm',
    computed: {
        isDemo() { return process.env.DEMO_MODE === 'true' },
        ...mapState({
           phone: state => state.organization.item.phone,
           email: state => state.organization.item.email,
           addresses: state => state.organization.item.addresses,
           organizationPhone: state => state.organization.item.phone,
           transactionsContact: state => state.team.list.find(({ person }) => person.contactRole === 'transactions') || state.team.list[0],
           locationsContact: state => state.team.list.find(({ person }) => person.contactRole === 'locations') || state.team.list[1],
        })
    },

    data () {

        return{
             loading: false,
             errors: {
                labels: {
                    error: {
                        title: this.$i18n.t('Une erreur est survenue'),
                        text: ''
                    },
                    success: {
                        title: this.$i18n.t('Votre message a bien été envoyé'),
                        text: this.$i18n.t('Nous vous répondrons dans les meilleurs délais')
                    },
                    firstname: this.$i18n.t('veuillez saisir un prénom'),
                    lastname: this.$i18n.t('veuillez saisir un nom'),
                    email: this.$i18n.t('veuillez saisir un email'),
                    email_valid: this.$i18n.t('veuillez saisir un email valide'),
                    phone: this.$i18n.t('veuillez saisir un numéro de téléphone'),
                    phone_valid: this.$i18n.t('veuillez saisir un numéro de téléphone valide'),
                    message: this.$i18n.t('veuillez saisir un message')
                },
                fields: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    phone: '',
                    message: ''
                }
            },
            form: {
                email: "",
                phone: "",
                firstname: "",
                lastname: "",
                message: ""
            },
            animation: {
              enter (element) {
                // https://www.npmjs.com/package/velocity-animate
                // http://velocityjs.org/
                 /*
                  *  "element" - is a notification element
                  *    (before animation, meaning that you can take it's initial height, width, color, etc)
                  */
                 let height = element.clientHeight

                 return {
                   // Animates from 0px to "height"
                   height: [height, 0],

                   // Animates from 0 to random opacity (in range between 0.5 and 1)
                   opacity: [Math.random() * 0.5 + 0.5, 0]
                 }
              },
              leave: {
                height: 0,
                opacity: 0
              }
            }
        }
    },
    methods: {
        getTransactionsPhone(removeSpace = false) {
            const phone = (this.transactionsContact && this.transactionsContact.person.phone) || this.organizationPhone || ''
            return removeSpace ? phone.replace(/ /g,""): phone
        },
        getLocationsPhone(removeSpace = false) {
            const phone = (this.locationsContact && this.locationsContact.person.phone) || this.organizationPhone || ''
            return removeSpace ? phone.replace(/ /g,""): phone
        },

        getPhone(phone) {
            return 'tel: ' + (phone || this.organizationPhone || '').replace(/ /g,"")
        },
        showNotification(type, title, messages) {
            this.$notify({
              group: 'form-contact',
              clean: true
            })
            const message = messages[0]
            this.$notify({
            // https://www.npmjs.com/package/vue-notification
            // (optional)
            // Name of the notification holder
            group: 'form-contact',
            // (optional)
            // Class that will be assigned to the notification
            type: type,
            // (optional)
            // Title (will be wrapped in div.notification-title)
            title: title,
            // Content (will be wrapped in div.notification-content)
            text: message,

            // (optional)
            // Overrides default/provided duration
            duration: 5000,

            // (optional)
            // Overrides default/provided animation speed
            speed: 1000,

            // (optional)
            // Data object that can be used in your template
            data: {},
            width: 300
          })

        },
        handleSubmit() {
            this.loading = true
            if(this.checkForm()){
                const params = { email: this.form.email }
                this.$axios.get('/people'
                    , {
                        params,
                        validateStatus: function (status) {
                            return status >= 200 && status < 500
                        }
                    })
                    .then((response) => {
                        if(0 == response.data['hydra:totalItems']) {
                            this.savePerson(response.data)
                        } else {
                            this.saveMessage(response.data['hydra:member'][0])
                        }
                    }).catch(error => {
                        console.log(error)
                        console.log('error store ContactForm.vue')
                    })
            }
        },
        savePerson() {
            const params = {
                email: this.form.email,
                phone: this.form.phone,
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                text: this.form.message,
                origin: "classic contact form"
            }
            this.$axios.post('/people', params )
              .then((response) => {
                this.saveMessage(response.data)
            }).catch((e) => { console.log(e) })
        },
        saveMessage(person) {
            const message = {
                subject: "unused subject",
                text: this.form.message,
                dateSent: this.$dayjs().format('YYYY-MM-DD H:m:s'),
                messageAttachment: null,
                origin: "classic contact form",
                recipient: this.$store.state.team.mainContact,
                sender: person['@id']
            }
           this.$axios.post('/messages', message)
              .then((response) => {

                this.loading = false

                const params = {
                    message: this.form.message,
                    firstname: this.form.firstname,
                    lastname: this.form.lastname,
                    phone: this.form.phone,
                    from: this.$store.state.organization.item.email,
                    to: this.form.email,
                    locale: this.$store.state.i18n.currentLocale
                }
                this.$axios.post(process.env.URL_DMS + '/email/confirmation-contact', params)
                  .then((response) => {
                    this.showNotification('success', this.errors.labels.success.title, [this.errors.labels.success.text])
                }).catch((e) => { console.log(e) })

                this.form.firstname = ""
                this.form.lastname = ""
                this.form.email = ""
                this.form.phone = ""
                this.form.message = ""

            }).catch((e) => { console.log(e) })
        },
        checkForm() {
            let messages = []
            this.errors.fields.firstname = ''
            this.errors.fields.lastname = ''
            this.errors.fields.email = ''
            this.errors.fields.phone = ''
            this.errors.fields.message = ''

            if (!this.form.firstname) {
                this.errors.fields.firstname = 'red'
                messages.push(this.errors.labels.firstname);
            }
            if (!this.form.lastname) {
                this.errors.fields.lastname = 'red'
                messages.push(this.errors.labels.lastname);
            }
           if (!this.form.email) {
                this.errors.fields.email = 'red'
                messages.push(this.errors.labels.email);
            } else if (!this.validEmail(this.form.email)) {
                this.errors.fields.email = 'red'
                messages.push(this.errors.labels.email_valid);
            }

            // if (!this.form.phone) {
            //     this.errors.fields.phone = 'red'
            //     messages.push(this.errors.labels.phone);
            // } else if (!this.validPhone(this.form.phone)) {
            //     this.errors.fields.phone = 'red'
            //     messages.push(this.errors.labels.phone_valid);
            // }

            if (!this.form.message) {
                this.errors.fields.message = 'red'
                messages.push(this.errors.labels.message);
            }

            if(!messages.length) {
                return true
            }
            this.loading = false

            this.showNotification('error', this.errors.labels.error.title, messages)

            return false
        },
        validEmail(email) {
            email = email.trim()
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        validPhone(phone) {
            phone = phone.trim()
            var re = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

            return re.test(phone);
        },
        getLogoStamp(filename) {

            return process.env.URL_CDN + process.env.PATH_DEFAULT_MEDIA + filename
        }
    }
}
</script>
<style lang="scss">

.contact-one .form-control.red {
    border-bottom-color: var(--color-secondary);
}
input::placeholder {
  color: red;
}
.page-content-container .vue-notification, .page-content-container .vue-notification-template{

  padding: 28px 20%;
}
.vue-notification {
  padding: 10px;
  margin: 0px;
  font-size: 12px;

  color: #ffffff;
  background: #44A4FC;

  &.warn {
    background: var(--color-secondary);
    border-left:none
  }

  &.error {
    background: var(--color-secondary);
    border-left:none
  }

  &.success {
    background: #68cd86;
    border-left-color: #42a85f;

  }
  &.success .notification-title, &.success .notification-content, &.success i {
    color: #3e2723 !important;
  }

}
@media screen and (max-width: 480px){
    .vue-notification {
  margin-top: 18%;
  }
}
.text{
    line-height: 24px;
    font-size: 12px;
font-weight: 400;
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
    animation: blink 1.2s infinite;
}

@media (min-width: 992px) {
    .mobile {
        display:none;
    }
}
@media (max-width: 991px) {
    .desktop {
        display:none;
    }
}
</style>
