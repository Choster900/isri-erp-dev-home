import moment from 'moment';
moment.locale('en');

export const useFormatDateTime = () => {
    //Must be format YYYY/mm/dd
    const formatDateVue3DP = (date) => {
        moment.locale('en');
        return moment(date).format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
    }

    //Must be format HH:mm:ss
    const formatTimeVue3DP = (time) => {
        const objectTime = {}
        const timeObj = moment(time, 'HH:mm:ss');
        objectTime.hours = timeObj.hours()
        objectTime.minutes = timeObj.minutes()
        objectTime.seconds = timeObj.seconds()
        
        return objectTime
    }

    return {
        formatDateVue3DP, formatTimeVue3DP
    }
}