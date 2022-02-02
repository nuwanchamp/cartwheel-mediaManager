import 'jquery/src/jquery';
import axios  from 'axios';
import Dropzone from "dropzone";
import 'dropzone/dist/dropzone.css';
import MediaThumb from './components/media-thumb';
import ModalView from './components/modal-view';
import Media from './components/media';

Vue.prototype.$http = axios;
window.Vue = Vue;
window.eventBus = new Vue();
window.axios = axios;
window.chosenMediaCollection = [];



jQuery(document).ready(function () {
    if (jQuery('#my-dropzone').length > 0) {
        let myDropZone = new Dropzone('#my-dropzone', {
            paramName: 'media',
            maxFilesize: 8,
        });
    }
});

// Vue.component('media-thumb', MediaThumb);
Vue.component('modal-view', ModalView );
Vue.component('media-collection', Media);

// var app = new Vue({
//     el: "#media-container",
//     methods: {
//         show: function(){
//             console.log('oh God,');
//         }
//     }
// })

