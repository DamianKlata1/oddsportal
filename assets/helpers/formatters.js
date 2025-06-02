
import { formatInTimeZone } from 'date-fns-tz'

export const formatDateKeywordLabel = (keyword) => {
    return keyword
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ')
}
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
