<template>
  <div class="wt-post-info p-t30 bg-white">
    <div class="wt-post-title">
      <h2 class="post-title">
        <a
          href="javascript:void(0);"
          class="text-black font-20 letter-spacing-2 font-weight-600"
        >
        
        {{ $t(data.name) }}
        </a>
      </h2>
    </div>
    <div class="wt-post-text">
      <p>{{ $t(data.description) }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "DescriptionProductPlace",
  props: {
    data: {
      type: Object,
    },
  },
};
</script>
