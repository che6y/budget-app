export default {
    all() {
        return axios.get('/api/s-purchases');
    },
    post(data) {
        return axios.post(`/api/s-purchases/`, data);
    },
    update(id, data) {
        return axios.put(`/api/s-purchases/${id}`, data);
    },
    delete(id) {
        return axios.delete(`/api/s-purchases/${id}`);
    },
};