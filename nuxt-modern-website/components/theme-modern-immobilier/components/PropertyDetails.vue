<template>
<div class="properties-amenities mb-40">
    <div class="wt-post-title ">
            <h2 class="post-title ">
                <a href="javascript:void(0);" class="text-black font-20 letter-spacing-2 font-weight-600 ">
                    {{ $t('détails') }}
                </a>
            </h2>
        </div>
    <div class="row row-amenities">
         <table class="amenities table">
            <tbody>
                <tr
                    v-for="(item, index) in data"
                    :key="index"
                >
                    <td><strong> {{ item.label }} : </strong></td>
                    <td class="value">{{ item.value }}</td>
                </tr>
                <!-- <tr>
                    <td><strong> {{ $t('Identifiant de la propriété') }} : </strong></td>
                    <td class="value">{{ data.id }}</td>
                </tr>
                <tr>
                    <td><strong> {{ $t('Prix') }} : </strong></td>
                    <td class="value">{{ data.price }}</td>
                </tr>
                <tr>
                    <td><strong> {{ $t('Type de propriété') }} : </strong></td>
                    <td class="value">{{ data.type.name }}</td>
                </tr>
                <tr>
                    <td><strong> {{ $t('Salle de bain') }} : </strong></td>
                    <td class="value">{{ data.numberOfRooms }}</td>
                </tr>
                <tr>
                    <td><strong> {{ $t('Propriété Taille du lot') }} : </strong></td>
                    <td class="value">{{ data.floorSize }}</td>
                </tr>
                <tr>
                    <td><strong> {{ $t('Aire d\'atterrissage') }} : </strong></td>
                    <td class="value">{{ data.areaSize }}</td>
                </tr>
                <tr>
                    <td><strong> {{ $t('Endroit') }} : </strong></td>
                    <td class="value">{{ data.place.name }}</td>
                </tr> -->
            </tbody>
        </table>
    </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
export default {
    name: 'PropertyDetails', 
    props:{
        data:{
            type:Array
        }
    }
}
</script>
<style scoped>
 ul{   margin: 0;
    padding: 0;
    list-style: none;
    color: #535353;
}
.properties-amenities ul li i {
    color: #79a3b1;
}
.properties-amenities ul li i {
    margin-right: 15px;
    text-align: center;
    font-size: 15px;
    font-weight: 400;
}
table.amenities td {

    padding:5px 20px 5px 0px;
    /* border-top:0; */
}
table.amenities td.value {
    color: #79a3b1;
    font-weight: 600;
}

.row-amenities {
    padding:0 15px
}
</style>
