import axios from 'axios';

const axiosClient = axios.create({
    baseURL: 'http://localhost/api/',
    responseType: 'json',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

export default {
    helpGet: url => axiosClient.get(url).then(res => res.data),
}