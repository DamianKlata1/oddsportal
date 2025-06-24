<script setup>
defineProps({
    event: {
        type: Object,
        required: true
    },
    refreshingEventId: {
        type: Number,
        default: null
    },
    handleRefreshOutcomes: {
        type: Function,
        required: true
    }
});

import { useEventCard } from '/assets/composables/useEventCard.js';
import { formatDateTime, formatRelativeTime } from '/assets/helpers/formatters.js'
const {
    visibleOutcomesCount,
    initVisibleOutcomes,
    showMoreOutcomes,
    showLessOutcomes,
} = useEventCard();

import { useI18n } from 'vue-i18n';
const { t, locale } = useI18n();
</script>

<template>
    <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column">
            <div>
                <div v-if="event.sport && (event.sport.name || event.sport.logoPath)"
                    class="d-flex align-items-center mb-1">
                    <img v-if="event.sport.logoPath" :src="event.sport.logoPath"
                        :alt="event.sport.name ? event.sport.name + ' icon' : 'Sport icon'" class="me-2"
                        style="max-height: 20px; width: auto; vertical-align: middle;">
                    <i v-else-if="event.sport.iconClass" :class="'me-2'"
                        style="font-size: 1.2em; line-height: 1; vertical-align: middle;"></i>
                    <p v-if="event.sport.name" class="card-text text-muted small mb-0">
                        {{ event.sport.name }}
                    </p>
                </div>
                <div v-if="(event.league && event.league.name) || (event.region && event.region.logoPath)"
                    class="d-flex align-items-center mb-1">
                    <img v-if="event.region && event.region.logoPath" :src="event.region.logoPath"
                        :alt="event.region.name ? event.region.name + ' logo' : 'Region logo'" class="me-2"
                        style="max-height: 16px; width: auto; vertical-align: middle;">
                    <p v-if="event.league && event.league.name" class="card-text text-muted small mb-0">
                        {{ event.league.name }}
                    </p>
                </div>
                <!-- <p v-if="event.region && event.region.name" class="card-text text-muted mb-2 small">
                  <i class="bi bi-geo-alt"></i> {{ event.region.name }}
                </p> -->
                <h5 v-if="event.homeTeam && event.awayTeam" class="card-title mt-1">{{ event.homeTeam }} vs {{
                    event.awayTeam }}</h5>
                <p class="card-text text-muted mb-2" :title="formatDateTime(event.commenceTime)">
                    <i class="bi bi-calendar-event"></i> {{ formatRelativeTime(event.commenceTime, locale) }}
                </p>
            </div>

            <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h6 class="mb-0">{{ $t('best_odds') }}:</h6>

                    <button class="btn btn-sm btn-outline-success p-1" @click="handleRefreshOutcomes(event)"
                        :disabled="refreshingEventId === event.id" data-bs-toggle="tooltip" data-bs-placement="top"
                        :title="$t('refresh')">

                        <span v-if="refreshingEventId === event.id" class="spinner-border spinner-border-sm"
                            role="status" aria-hidden="true"></span>

                        <i v-else class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                <div v-if="event.bestOutcomes && event.bestOutcomes.length > 0">
                    <div v-if="!visibleOutcomesCount[event.id]">
                        {{ initVisibleOutcomes(event.id) }}
                    </div>

                    <ul class="list-unstyled">
                        <li v-for="(outcome, index) in event.bestOutcomes.slice(0, visibleOutcomesCount[event.id])"
                            :key="outcome.name + outcome.bookmaker.name + outcome.price" class="mb-1">
                            <strong>{{ outcome.name }}</strong> -
                            <span class="text-success">{{ outcome.price }}</span>
                            <small class="text-muted">({{ outcome.bookmaker.name }})</small>
                            <br />
                            <small class="text-muted" :title="formatDateTime(outcome.lastUpdate)">{{ $t('updated') }}:
                                {{
                                    formatRelativeTime(outcome.lastUpdate, locale) }}</small>
                        </li>
                    </ul>

                    <div class="mt-2 d-flex gap-2">
                        <button v-if="event.bestOutcomes.length > visibleOutcomesCount[event.id]"
                            class="btn btn-sm btn-outline-primary" @click="showMoreOutcomes(event.id)">
                            {{ $t('show_more') }}
                        </button>
                        <button v-if="visibleOutcomesCount[event.id] > 5" class="btn btn-sm btn-outline-secondary"
                            @click="showLessOutcomes(event.id)">
                            {{ $t('show_less') }}
                        </button>
                    </div>
                </div>
                <div v-else class="text-muted fst-italic">
                    {{ $t('no_odds_available_for_this_event') }}
                </div>
            </div>
        </div>
    </div>
</template>