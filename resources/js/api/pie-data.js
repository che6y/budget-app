import ç from 'axios';

export default {
    all() {
        return axios.get('/api/pie-data');
    }
};