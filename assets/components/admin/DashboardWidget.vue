<script setup>
defineProps({
  title: String,
  icon: String, // np. 'bi-clock-history'
});
</script>

<template>
  <div class="card mb-4">
    <div class="card-header">
      <i :class="icon" class="me-1"></i>
      {{ title }}
    </div>
    <div class="card-body">
      <slot></slot>
    </div>
  </div>
</template>