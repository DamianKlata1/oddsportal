import { ref, watch, onMounted, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import isEqual from 'lodash/isEqual';

export function useUrlSync(stores, tryFetchEvents) {
    const route = useRoute();
    const router = useRouter();
    const isApplyingUrlToStores = ref(false);

    const buildQueryParams = () => {
        const query = {};
        if (stores.leaguesStore.selectedLeague?.id) query.league = String(stores.leaguesStore.selectedLeague.id);
        if (stores.sportsStore.selectedSport?.id) query.sport = String(stores.sportsStore.selectedSport.id);
        if (stores.eventFiltersStore.searchName) query.search = stores.eventFiltersStore.searchName;
        if (stores.eventFiltersStore.selectedDateKeyword) query.date = stores.eventFiltersStore.selectedDateKeyword;
        if (stores.paginationStore.currentPage > 1) query.page = String(stores.paginationStore.currentPage);
        return query;
    };

    const pushUrlQuery = () => {
        if (isApplyingUrlToStores.value) return;
        const newQuery = buildQueryParams();
        if (!isEqual(newQuery, route.query)) {
            router.push({ query: newQuery }).catch(err => {
                if (err.name !== 'NavigationDuplicated') console.error('Router push error:', err);
            });
        }
    };

    const replaceUrlQuery = () => {
        const newQuery = buildQueryParams();
        if (!isEqual(newQuery, route.query)) {
            router.replace({ query: newQuery }).catch(err => {
                if (err.name !== 'NavigationDuplicated') console.error('Router replace error:', err);
            });
        }
    };

    const applyQueryToStores = async (querySource) => {
        isApplyingUrlToStores.value = true;

        await Promise.all([
            stores.eventFiltersStore.fetchDateKeywords()
        ]).catch(err => console.error("Error fetching initial lookup data:", err));
        
        const urlSportId = querySource.sport ? parseInt(querySource.sport, 10) : null;
        if (stores.sportsStore.selectedSport?.id !== urlSportId) {
            stores.sportsStore.selectById(urlSportId);
        }

        const urlLeagueId = querySource.league ? parseInt(querySource.league, 10) : null;
        if (stores.leaguesStore.selectedLeague?.id !== urlLeagueId) {
            stores.leaguesStore.selectById(urlLeagueId);
        }

        stores.eventFiltersStore.searchName = querySource.search || '';
        const dateOption = stores.eventFiltersStore.dateKeywords.find(opt => opt === querySource.date);
        stores.eventFiltersStore.selectedDateKeyword = dateOption ? querySource.date : 'upcoming';
        
        const urlPage = querySource.page ? parseInt(querySource.page, 10) : 1;
        if (stores.paginationStore.currentPage !== urlPage) {
            stores.paginationStore.setPage(urlPage);
        }

        await nextTick();
        isApplyingUrlToStores.value = false;

        replaceUrlQuery();
        tryFetchEvents();
    };

    watch(() => route.query, (newQuery) => {
        if (!isEqual(newQuery, buildQueryParams())) {
            applyQueryToStores(newQuery);
        }
    }, { deep: true });

    onMounted(() => {
        applyQueryToStores(route.query);
    });
    
    return { isApplyingUrlToStores, pushUrlQuery };
}