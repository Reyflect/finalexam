//import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';

import "video.js/dist/video.min.js"; 
import "videojs-playlist/dist/videojs-playlist";


import {createApp} from 'vue';
import ListProduct from './components/ListProducts.vue';
import CreateProducts from './components/CreateProducts.vue';
import EditProducts from './components/EditProducts.vue';
import Videos from './components/VideoList.vue';



const app = createApp({});

app.component('my-video',Videos);
app.component('product-list',ListProduct);
app.component('create-product',CreateProducts);
app.component('edit-products',EditProducts);

app.mount('#app');
