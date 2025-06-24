<template>
  <div class="dropdown">
    <button class="btn btn-outline-success dropdown-toggle d-flex align-items-center" type="button"
      data-bs-toggle="dropdown" aria-expanded="false">

      <i class="bi bi-translate"></i>

    </button>
    <ul class="dropdown-menu dropdown-menu-end text-small">
      <li><button class="dropdown-item" :class="{ 'bg-success text-white': $i18n.locale === 'en' }"
          @click="switchLocale('en')">English<span v-if="$i18n.locale === 'en'" class="bi bi-check"></span></button></li>
      <li><button class="dropdown-item" :class="{ 'bg-success text-white' : $i18n.locale === 'pl' }"
          @click="switchLocale('pl')">Polski<span v-if="$i18n.locale === 'pl'" class="bi bi-check"></span></button></li>
    </ul>
  </div> 
</template>
<script>
export default {
  methods: {
    switchLocale(locale) {
      this.$i18n.locale = locale;
      localStorage.setItem('oddsportal|locale', locale);
    }
  }
}
</script>