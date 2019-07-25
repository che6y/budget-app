import ç from 'axios';

export default {
    all() {
        return axios.get('/api/options');
    },
    find(data) {
        return axios.get(`/api/options/find`, { params: {data}})
    },
    update(id, data) {
        return axios.put(`/api/options/${id}`, data);
    },
};