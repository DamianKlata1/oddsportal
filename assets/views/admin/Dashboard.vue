<script setup>
import { ref, onMounted } from 'vue';
import Statcard from '../../components/admin/Statcard.vue';
import DashboardWidget from '../../components/admin/DashboardWidget.vue';
import apiPrivate from '/assets/api/apiPrivate.js';
import { useI18n } from 'vue-i18n';
import { formatRelativeTime, formatDateTime } from '../../helpers/formatters.js';
const { t } = useI18n();
const isLoading = ref(false);
const errorMessage = ref(null);
const registeredUsers = ref(0);
const eventsInDatabase = ref(0);
const activeLeagues = ref(0);
const newUsersLast7Days = ref(0);
const recentRegistrations = ref([]);
const commandStatuses = ref([]);

const stats = ref([
  { title: t('registered_users'), value: registeredUsers, icon: 'bi-people-fill', color: 'primary' },
  { title: t('events_in_database'), value: eventsInDatabase, icon: 'bi-calendar-event', color: 'warning' },
  { title: t('active_leagues'), value: activeLeagues, icon: 'bi-trophy-fill', color: 'success' },
  { title: t('new_users_last_7_days'), value: newUsersLast7Days, icon: 'bi-person-plus-fill', color: 'danger' },
]);

onMounted(async () => {
  isLoading.value = true;
  errorMessage.value = null;

  try {
    const [statsResponse, commandStatusesResponse, recentUsersResponse] = await Promise.all([
      apiPrivate().get('/api/admin/stats'),
      apiPrivate().get('/api/admin/command-statuses'),
      apiPrivate().get('/api/admin/recent-users')
    ]);

    console.log('Stats Response:', commandStatusesResponse.data);

    registeredUsers.value = statsResponse.data.registeredUsers;
    eventsInDatabase.value = statsResponse.data.eventsInDatabase;
    activeLeagues.value = statsResponse.data.activeLeagues;
    newUsersLast7Days.value = statsResponse.data.newUsersLast7Days;
    commandStatuses.value = commandStatusesResponse.data;
    recentRegistrations.value = recentUsersResponse.data;

  } catch (error) {
    errorMessage.value = t('dashboard_loading_error');
  } finally {
    isLoading.value = false;
  }
})

</script>

<template>
  <div>
    <h1 class="mt-4">{{ $t('dashboard') }}</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">{{ $t('main_app_info') }}</li>
    </ol>

    <div class="row">
      <Statcard v-for="stat in stats" :key="stat.title" :title="stat.title" :value="stat.value" :icon="stat.icon"
        :color="stat.color" />
    </div>

    <div class="row">
      <div class="col-lg-7">

        <DashboardWidget :title="$t('command_statuses')" icon="bi-arrow-repeat">
          <ul class="list-group list-group-flush">
            <li v-for="job in commandStatuses" :key="job.name"
              class="list-group-item d-flex justify-content-between align-items-center">
              <code :class="job.status === 'success' ? 'text-success' : 'text-danger'">{{ job.name }}</code>
              <div class="d-flex align-items-center gap-3">
                <span class="text-muted small" style="min-width: 120px; text-align: right;">
                  {{ formatRelativeTime(job.lastRunAt, locale) }}
                </span>
                <span class="badge" :class="job.status === 'SUCCESS' ? 'bg-success' : 'bg-danger'" style="width: 80px;">
                  {{ job.status }}
                </span>
              </div>
            </li>
          </ul>
        </DashboardWidget>

        <DashboardWidget :title="$t('recently_registered')" icon="bi-person-lines-fill">
          <ul class="list-unstyled">
            <li v-for="user in recentRegistrations" :key="user.email" class="mb-2">
              <strong>{{ user.email }}</strong>
              <span class="text-muted fst-italic float-end">{{ formatDateTime(user.time) }}</span>
            </li>
          </ul>
        </DashboardWidget>

      </div>
      <div class="col-lg-5">

        <DashboardWidget :title="$t('quick_actions')" icon="bi-lightning-fill">
          <div class="d-grid gap-2">
            <button class="btn btn-primary" type="button">{{ $t('run_full_sync') }}</button>
            <button class="btn btn-outline-secondary" type="button">{{ $t('manage_users') }}</button>
            <a href="#" class="btn btn-outline-danger" target="_blank">{{ $t('go_to_sentry') }}</a>
          </div>
        </DashboardWidget>

      </div>
    </div>
  </div>
</template>