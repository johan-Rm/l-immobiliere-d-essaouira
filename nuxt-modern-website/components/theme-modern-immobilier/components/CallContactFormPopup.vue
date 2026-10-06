<template>
    <div class="contact-form-popup">
        <div id="with-form" class="modal fade" role="dialog" style="display: none;">
            <div class="modal-dialog" style="margin-top: 58.5px;">
                <div class="modal-content">
                    <div class="modal-header bg-secondry">
                        <button type="button" class="close" data-dismiss="modal" ref="closeButton">×</button>
                        <div class="section-head text-left text-black">
                            <h2 class="text-uppercase font-36">{{ $t('Contacter l\'agence')}}</h2>
                            <div class="wt-separator-outer">
                                <div class="wt-separator bg-primary"></div>
                            </div>
                        </div>
                    </div>
                     <div class="modal-body">
                        <form ref="formTest" id="demo-form" class="contact-form mb-lg" novalidate @submit.prevent="handleSubmit">
                            <div class="contact-one">
                                <!-- <notifications group="form-contact-popup" animation-type="velocity" :animation="animation" position="top center" width="100%"> -->
                                <notifications group="form-contact-popup" :animation="animation" position="top center" width="100%">
                                    <template slot="body" slot-scope="props">
                                        <div class="vue-notification-template vue-notification" :class="props.item.type">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3">
                                                    <i v-if="'error' === props.item.type" class="fa fa-exclamation-triangle"></i>
                                                    <i v-else class="fa fa-check-square"></i>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9">
                                                    <div class="notification-title">{{ props.item.title }}</div>
                                                    <div class="notification-content">{{ props.item.text }}</div>
                                                </div>
                                            </div>

                                        </div>
                                    </template>
                                </notifications>
                                <div class="form-group col-md-6">
                                    <input v-model="form.firstname" name="firstname" type="text" required :class="errors.fields.firstname" class="form-control" :placeholder="$t('Nom')">
                                </div>
                                <div class="form-group col-md-6">
                                    <input v-model="form.lastname" name="lastname" type="text" required :class="errors.fields.lastname"  class="form-control" :placeholder="$t('Prénom')">
                                </div>
                                <div class="form-group col-md-12">
                                    <input v-model="form.email" name="email" type="text" :class="errors.fields.email" class="form-control" required :placeholder="$t('E-mail')">
                                </div>
                                <div class="form-group col-md-12">
                                    <input v-model="form.phone" name="phone" type="text" :class="errors.fields.phone" class="form-control" required :placeholder="$t('Téléphone')">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea v-model="form.message" name="message" rows="3" :class="errors.fields.message" class="form-control " required :placeholder="$t('Message')"></textarea>
                                </div>

                                <button name="submit" :disabled="isDemo" type="submit" value="Submit" class="site-button black radius-no text-uppercase">
                                        <span class="font-12 letter-spacing-5"> {{ $t('Envoyer') }} </span>
                                </button>
                             </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="small-modal" class="modal fade" role="dialog" style="display: none;">
            <div class="modal-dialog" style="margin-top: 58.5px;">
                <div class="modal-content">
                    <div class="modal-header bg-secondry">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <div class="section-head text-left text-black">
                            <h2 class="font-36">{{ getFullName }}</h2>
                            <div class="wt-separator-outer">
                                <div class="wt-separator bg-primary"></div>
                            </div>
                        </div>
                    </div>
                     <div class="modal-body">
                <h4 class="text-uppercase font-36">{{ getPhone }}</h4>
                <p>{{ $t('Vous n\'avez pas réussi à joindre notre agent ?') }}
                <br>
                   {{ $t('N\'hésitez pas à lui envoyer un email') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!--<div id="Small-Modal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header bg-secondry">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title text-black">{{ $t('Contacter l\'agence') }} {{ getFullName }}</h4>
                <p>{{ getPhone }}</p>
                <p>{{ $t('Vous n\'avez pas réussi à joindre notre agent ?') }}</p>
                <p>{{ $t('N\'hésitez pas à lui envoyer un email') }}</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="site-button button-sm text-uppercase letter-spacing-2" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>-->
        <div v-if="loading" class="loading-page">
            <img v-if="$device.isMobile" class="mobile" :src="getLogoStamp('stamp.png')" width="100"/>
            <img v-else class="desktop" :src="getLogoStamp('stamp.png')" width="200"/>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    name:'CallContactFormPopup',
    computed: {
        isDemo() { return process.env.DEMO_MODE === 'true' },
        ...mapState({
            organizationName: state => state.organization.item.name,
            organizationPhone: state => state.organization.item.phone,
            agent: state => state.accommodations.item.realEstateAgent,
            reference: state => state.accommodations.item.reference
        }),
        getPhone() {
            if(this.agent && this.agent.person.phone) {

                return 'tel: ' + this.agent.person.phone
            }

            return 'tel: ' + this.organizationPhone
        },
        getFullName() {
             if(this.agent) {

                return this.agent.name
            }

            return this.organizationName
        }
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
                    firstname: this.$i18n.t('Veuillez saisir un prénom'),
                    lastname: this.$i18n.t('Veuillez saisir un nom'),
                    email: this.$i18n.t('Veuillez saisir un email'),
                    email_valid: this.$i18n.t('Veuillez saisir un email valide'),
                    phone: this.$i18n.t('veuillez saisir un numéro de téléphone'),
                    phone_valid: this.$i18n.t('veuillez saisir un numéro de téléphone valide'),
                    message: this.$i18n.t('Veuillez saisir un message')
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
                reference: "",
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
                   // opacity: [Math.random() * 0.5 + 0.5, 0]
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
        showNotification(type, title, messages) {
            this.$notify({
              group: 'form-contact-popup',
              clean: true
            })
            const message = messages[0]
            this.$notify({
            // https://www.npmjs.com/package/vue-notification
            // (optional)
            // Name of the notification holder
            group: 'form-contact-popup',
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
            this.form.reference = this.reference
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
                        console.log('error store CallContactFormPopup.vue')
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
                origin: 'contact form - ' + this.form.reference
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
                origin: 'contact form - ' + this.form.reference,
                recipient: this.$store.state.team.mainContact,
                sender: person['@id']
            }
           this.$axios.post('/messages', message)
              .then((response) => {

                const params = {
                    message: this.form.message,
                    firstname: this.form.firstname,
                    lastname: this.form.lastname,
                    phone: this.form.phone,
                    from: this.$store.state.organization.item.email,
                    to: this.form.email,
                    locale: this.$store.state.i18n.currentLocale,
                    reference: this.form.reference
                }
                this.$axios.post(process.env.URL_DMS + '/email/confirmation-contact', params)
                  .then((response) => {
                    this.loading = false
                    this.showNotification('success', this.errors.labels.success.title, [this.errors.labels.success.text])
                    this.resetForm()
                    // this.$refs.closeButton.click()

                }).catch((e) => { console.log(e) })

            }).catch((e) => { console.log(e) })
        },
        resetForm() {
          this.form.firstname = ""
          this.form.lastname = ""
          this.form.email = ""
          this.form.phone = ""
          this.form.message = ""
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
                this.errors.fields.email_valid = 'red'
                messages.push(this.errors.labels.email);
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
<style lang="scss" scoped>


.contact-one{
    border:none;
}
.section-head {
    margin-bottom: 0;
    padding: 0 15px;
}
.section-head h2 {
    margin-bottom: 5px;
}
 h4 {
    font-size: 12px;
    color: #000;
    line-height: 30px;
    margin-bottom: 0px;
    font-style: normal;
    text-transform: uppercase;
    letter-spacing: 3px;
}
.modal-body {
    position: relative;
    padding: 15px;
        padding-left: 15px;
    padding-left: 30px;
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


.vue-notification {
    padding: 28px;
    margin: inherit;
}

.vue-notification.success .notification-title
, .vue-notification.success .notification-content
, .vue-notification.success i {
    color: var(--color-primary) !important;
}

.vue-notification.error {
  background: var(--color-secondary);
  border-left-color: var(--color-secondary-light);
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
