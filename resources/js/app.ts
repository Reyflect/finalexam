import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';

import "video.js/dist/video.min.js";
import "videojs-playlist/dist/videojs-playlist";


import { DefineComponent, createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

createInertiaApp({
  resolve: (name: string) => {
    const pages = import.meta.glob(['./components/**.vue'], { eager: true })

    return pages[`./components/${name}.vue`] as DefineComponent
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})
