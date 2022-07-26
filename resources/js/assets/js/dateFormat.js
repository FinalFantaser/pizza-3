export default function dateFormat (data) {
    const date = new Date(data);
    const options = {
        // era: 'long',
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        // weekday: 'long',
        timezone: 'UTC',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
    };
    return date.toLocaleString("ru", options)
}
