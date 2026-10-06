<template>
    <div class="scroltop buttons">
        <a class="btn-phone" :href="getPhone" :title="$t('Téléphone')" >
            <button type="button" class="site-button text-uppercase letter-spacing-2 font-weight-800 col-md-6 call" data-toggle="modal" >  <i class="fa fa-phone"></i>&nbsp;{{ $t('Téléphone') }}</button>
        </a>

        <button type="button" class="site-button text-uppercase letter-spacing-2 font-weight-800 contact" data-toggle="modal" data-target="#with-form"><i class="fa fa-envelope"></i>&nbsp;{{ $t('E-mail') }}</button>

        <button type="button" class="col-md-6 site-button text-uppercase letter-spacing-2 font-weight-800  m-b30 contact popup" data-toggle="modal" data-target="#small-modal"><i class="fa fa-phone"></i>&nbsp;{{ $t('Téléphone') }}</button>
        
        <div class="clear"></div>                      
    </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name:'ButtonCallContact',
    computed: {
        ...mapState({
            agent: state => state.accommodations.item.realEstateAgent,
            organizationPhone: state => state.organization.item.phone
        }),
        getPhone() {
            if(this.agent && this.agent.person.hasOwnProperty("phone")) {

                return 'tel: ' + this.agent.person.phone
            } 

            return 'tel: ' + this.organizationPhone
        }
    }
}
</script>
<style scoped>

.scroltop.buttons {
    padding:0;
}

.buttons .btn-phone {
  padding: 0;
}

.buttons{
    color:white;
    text-align:center;
}
.buttons .contact{
   background-color: white;
    color:black;
    border: solid 1px black;
}
.buttons .contact:hover{
    background-color: black;
    color:white;
}
 .buttons .popup {
        background-color: black;
    color:white;
    }

 .buttons .popup:hover {
        background-color: white;
    color:black;
    border: solid 1px black;
    }
    .buttons .contact , .buttons .popup{
        width: 50%
    }
    @media screen and (max-width: 1199px) and (min-width: 991px){ 
    .buttons .contact , .buttons .popup{
        font-size: 12px;
    }
    }
    @media screen and (max-width: 991px) { 
    .buttons .contact , .buttons .popup{
        width: 49%
    }
    }

@media screen and (max-width: 480px) {  
    .buttons{
        display: block;
        position: fixed; /* Lock location always on the scree */
        bottom: 0; /* Set to the bottom */
        left: 0; /* Set to the right */
        z-index: 500000;
        width: 100%;
    }
    .buttons .contact{
        margin-left:0px;
    }
    .buttons .print {
        display:none;
    }
    .buttons button {
       width: 50%;
       margin:0;
    }
    .buttons .contact{
        border: none;
    }
    .buttons button {
        float:left;
    }
}
@media screen and (max-width: 480px){
    .buttons .popup {
        display:none;
    }
}
@media screen and (min-width: 481px){
    button.call {
        display:none;
    }
}
</style>
