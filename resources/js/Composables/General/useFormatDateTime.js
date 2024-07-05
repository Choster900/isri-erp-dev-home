import moment from 'moment';

/**
 * Custom composable to format date and time using Moment.js.
 * @returns {Object} - Functions to format date and time.
 */
export const useFormatDateTime = () => {
    /**
     * Format a given date into a specific string format.
     * @param {string|Date} date - The date to format.
     * @returns {string|null} - Formatted date string or null if the date is invalid.
     */
    const formatDateVue3DP = (date) => {
        if (date) {
            // Format the date to 'ddd MMM DD YYYY HH:mm:ss [GMT]ZZ'
            return moment(date).locale('en').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
        } else {
            return null;
        }
    }

    /**
     * Format a given time into an object containing hours, minutes, and seconds.
     * @param {string} time - The time to format, must be in 'HH:mm:ss' format.
     * @returns {Object|null} - An object with hours, minutes, and seconds or null if the time is invalid.
     */
    const formatTimeVue3DP = (time) => {
        if (time) {
            // Parse the time string into a moment object
            const timeObj = moment(time, 'HH:mm:ss');
            // Create an object to hold the time components
            const objectTime = {
                hours: timeObj.hours(),
                minutes: timeObj.minutes(),
                seconds: timeObj.seconds()
            };
            return objectTime;
        } else {
            return null;
        }
    }

    return {
        formatDateVue3DP,
        formatTimeVue3DP
    }
}
