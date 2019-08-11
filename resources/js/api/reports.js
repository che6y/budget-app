import ç from 'axios';

export default {
    all() {
        return axios.get('/api/reports');
    },
    find(data) {
        return axios.post(`/api/reports/find`,  data);
    }
};