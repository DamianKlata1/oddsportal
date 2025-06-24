import { ref } from 'vue';
export function useEventCard() {
    const visibleOutcomesCount = ref({});

    const initVisibleOutcomes = (eventId) => {
        if (!(eventId in visibleOutcomesCount.value)) {
            visibleOutcomesCount.value[eventId] = 5;
        }
    };

    const showMoreOutcomes = (eventId) => {
        visibleOutcomesCount.value[eventId] += 5;
    };

    const showLessOutcomes = (eventId) => {
        visibleOutcomesCount.value[eventId] = Math.max(5, visibleOutcomesCount.value[eventId] - 5);
    };

    return {
        visibleOutcomesCount,
        initVisibleOutcomes,
        showMoreOutcomes,
        showLessOutcomes,
    };
}