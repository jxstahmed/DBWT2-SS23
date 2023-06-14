import axios from 'axios';

let APIClient = axios.create({
    baseURL: "http://hochschule.local", withCredentials: true, // required to handle the CSRF token
});


export default APIClient