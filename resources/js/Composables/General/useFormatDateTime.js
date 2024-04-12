import moment from 'moment';

export const useFormatDateTime = () => {
    //Must be format YYYY/mm/dd
    const formatDateVue3DP = (date) => {
        if (date) {
            return moment(date).locale('en').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
        } else {
            return null;
        }
    }

    //Must be format HH:mm:ss
    const formatTimeVue3DP = (time) => {
        const objectTime = {}
        const timeObj = moment(time, 'HH:mm:ss');
        objectTime.hours = timeObj.hours()
        objectTime.minutes = timeObj.minutes()
        objectTime.seconds = timeObj.seconds()

        return time ? objectTime : null
    }

    return {
        formatDateVue3DP, formatTimeVue3DP
    }
}