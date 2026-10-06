<template>
<div class="container-fuild">
    <div class="bg-dark">
      <h1 class="section-title underlined" >
        {{ $t('Aucun bien immobilier') }}<span>&nbsp;{{ $t('n\'a été trouvé') }}</span>
      </h1>
    </div>
</div>         
</template>
<script>
import { mapState } from 'vuex'
export default {
    name : "NoData"
}
</script>
<style lang="scss">

.bg-dark{
    //background-color: var(--color-primary) !important;
    width: 100%;
min-height: 200px;
color: var(--color-primary);
text-align: center
}
  .section-title{
font-size: 2rem;
text-transform: uppercase;
font-weight: 700;
margin-bottom: 0;
padding: 2rem 0;
text-align: center;
color:var(--color-secondary);
  }
  .section-title span{
      color:var(--color-primary)
  }
  .section-title.underlined::after {
    content: " ";
    position: relative;
    display: block;
    height: 3px;
    width: 40%;
    background: var(--color-secondary);
    margin: 1.5rem auto 0;
}
</style>
