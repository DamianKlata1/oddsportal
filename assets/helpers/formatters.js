
import { formatInTimeZone } from 'date-fns-tz'
import { formatDistanceToNow } from 'date-fns';
import { pl, enUS } from 'date-fns/locale';

export const formatDateTime = (isoTime) => {
  if (!isoTime) return '—'
  try {
    const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC'
    return formatInTimeZone(isoTime, timeZone, 'yyyy-MM-dd HH:mm')
  } catch (error) {
    console.error('Date formatting error:', error)
    return isoTime
  }
}
const locales = {
  pl: pl,
  en: enUS
};
export function formatRelativeTime(dateString, currentLocale = 'en') {

  if (!dateString) {
    return '';
  }

  try {
    const date = new Date(dateString);
    const locale = locales[currentLocale] || enUS;

    return formatDistanceToNow(date, { addSuffix: true, locale: locale });
  } catch (error) {
    console.error("Błąd formatowania daty:", error);
    return dateString;
  }
}