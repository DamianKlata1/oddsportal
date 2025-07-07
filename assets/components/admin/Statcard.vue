<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String, 
    color: String, 
});
</script>

<template>
    <div class="col-xl-3 col-md-6">
        <div class="card text-white mb-4" :class="`bg-${color}`">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-75 small">{{ title }}</div>
                    <div class="fs-4 fw-bold">{{ value }}</div>
                </div>
                <i :class="icon" class="fs-1"></i>
            </div>
        </div>
    </div>
</template>

<style scoped>
.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}
</style>