import ç from 'axios';

export default {
    all() {
        return axios.get('/api/categories');
    },
    find(id) {
        return axios.get(`/api/categories/${id}`);
    },
    post(data) {
        return axios.post(`/api/categories/`, data);
    },
    update(id, data) {
        return axios.put(`/api/categories/${id}`, data);
    },
    delete(id) {
        return axios.delete(`/api/categories/${id}`);
    },
};