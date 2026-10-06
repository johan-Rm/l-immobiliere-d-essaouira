<template>
    <div class="details-produit  wt-box">
        <div class="reference text-capitalize">
            <p>
                <span class="">{{ $t('Référence') }}</span> : <span class="reference">{{ reference }}</span>
            </p>
        </div>
        <div class="info">
            <p class="name">
                <i class="fa fa-map-marker-alt"></i>&nbsp;
                <span class="name">{{ upper(place.name) }}</span>
            </p>
            <p v-if="floorSize > 0" class="amenities">
                {{ infosProperty(numberOfRooms, floorSize) }}
            </p>
            <p v-if="areaSize > 0" class="amenities text-italic">
                {{ areaSize }} M&#xB2; {{ $t('de surface de terrain') }}
            </p>
            <!-- pour test -->
            <!-- <ul>
                <li><i class="fas fa-check-square"></i></li>
                <li><i class="fas fa-bath"></i></li>
                <li><i class="fas fa-fire"></i></li>
                <li><i class="fas fa-wifi"></i></li>
                <li><i class="fas fa-hot-tub"></i></li>
                <li><i class="fas fa-swimming-pool"></i></li>
            </ul> -->
            <ul class="pictos m-tb15">
                <li 
                v-for="(value,index)  in getPictos(amenities)"
                :key="index">
                    <i :class="value.slugPicto" ></i>
                    <span > {{ $t(value.name) }}</span>
                </li>
            </ul>
        </div>
        <div class="price-content">
            <div class="label-price">
                {{ $t(labelPrice) }}
            </div>
            <div v-if="price > 0" class="price">
                {{ toCurrencyString(price) }}
            </div>
            <div v-if="rentalPriceType" class="price-type">
                  - {{ $t(rentalPriceType.name) }} -
            </div>
            <div v-if="label" class="label-content">
                  {{ $t(label.name) }}
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'
import toLower from 'lodash.tolower'
import upperFirst from 'lodash.upperfirst'
export default {
    name:'ProductDetails',
    computed: {
        ...mapState({
            labelPrice: state => state.accommodations.item.labelPrice,
            label: state => state.accommodations.item.label,
            rentalPriceType: state => state.accommodations.item.rentalPriceType,
            price: state => state.accommodations.item.price,
            amenities: state => state.accommodations.item.amenities,
            reference: state => state.accommodations.item.reference,
            numberOfRooms: state => state.accommodations.item.numberOfRooms,
            areaSize: state => state.accommodations.item.areaSize,
            floorSize: state => state.accommodations.item.floorSize,
            place: state => state.accommodations.item.place
        })
    },
    methods: {
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
            let label = (nbOfRooms > 1 )? 'chambres': 'chambre'

            return nbOfRooms + ' ' + this._i18n.t(label) + ' - ' 
                    + areaSize + ' m²' 
        },
        upper(string) {
            return upperFirst(this._i18n.t(string));
        },
        getPictos(data){
            const listpictos = [];
            data.forEach(function (picto, i) {
                if(picto.withPicto==true){
                    listpictos.push(picto)
               
                }
            })

            return listpictos
        }
    }
}
</script>
<style lang="scss" scoped>


.wt-box {
    text-align: center;
    position: none;
}
.side-bar{
    text-align: center;
}
p {
  text-transform: lowercase;
}
p::first-letter {
  text-transform: uppercase;
}

.wt-box p.text-italic {
    font-style: italic;
    font-size: 1.2em;
}

.wt-box p{
    font-size: 2rem;
    line-height: 1.3;
    font-weight: 400;
    color: #3e2723;
}
.wt-box .price{
    font-family: Arial;
    color: #3e2723;
    font-size: 3.9rem;
}

.card .price {
    font-weight: bold;
    color: black;
}

p.name {
    text-transform: inherit;
}

.card .wt-box span.reference {
    font-weight: bold;
    text-transform: uppercase;
    color: #000;
font-weight: 800}

.info p {
    margin: 0.3em;
}

.info, .reference {
    text-align:center;
}

.details-produit .amenities{
    font-weight: 600
}

.details-produit .pictos li {
    display:inline-block;
    padding:0 10px;
}
  @media screen and (max-width: 480px){
      .details-produit {
          padding-top:30px;
  
   }
}
.price-type {
    font-size: 2rem;
    text-transform : lowercase;
}

.price-content {
    padding:40px 20px;
}

.price-content .price {
    /*background: rgb(62, 39, 35);*/
    text-align:center;
    /*color:#92acbe;*/
}

.label-content {
    text-align:center;
    color: var(--color-secondary);
    font-weight:700;
    font-size:2.2em;
}
.pictos i {
  font-size: 2em;
  margin-right: 5px;
}
</style>