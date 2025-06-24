import { useRoute, useRouter } from 'vue-router';
import isEqual from 'lodash/isEqual';

export function useUrlSync(stores) {
    const { leaguesStore, eventFiltersStore, paginationStore } = stores;
    const route = useRoute();
    const router = useRouter();

    const buildQueryParams = () => {
        const query = {};
        if (leaguesStore.selectedLeague?.id) query.league = String(leaguesStore.selectedLeague.id);
        if (eventFiltersStore.searchName) query.search = eventFiltersStore.searchName;
        if (eventFiltersStore.selectedDateKeyword) query.date = eventFiltersStore.selectedDateKeyword;
        if (paginationStore.currentPage > 1) query.page = String(paginationStore.currentPage);
        return query;
    };

    const pushUrlQuery = () => {
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
    
    return { buildQueryParams, pushUrlQuery, replaceUrlQuery };
}