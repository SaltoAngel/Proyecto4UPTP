// Import CoreUI y expón su API en window para uso global
import * as coreui from '@coreui/coreui/dist/js/coreui.bundle.min.js';
window.coreui = coreui;

// Chart.js + ChartGeo (mapas) - expuestos globalmente
import { Chart, registerables } from 'chart.js';
import { ChoroplethController, BubbleMapController, GeoFeature, ColorScale, ProjectionScale } from 'chartjs-chart-geo';
import * as topojson from 'topojson-client';
import * as d3geo from 'd3-geo';

Chart.register(...registerables);
Chart.register(ChoroplethController, BubbleMapController, GeoFeature, ColorScale, ProjectionScale);

window.Chart = Chart;
window.ChartGeo = {
	ChoroplethController,
	BubbleMapController,
	GeoFeature,
	ColorScale,
	ProjectionScale,
	topojson,
	d3geo,
};

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Inicializa el Sidebar y el toggler cuando el DOM está listo
document.addEventListener('DOMContentLoaded', () => {
	const sidebarEl = document.querySelector('#sidebar');
	if (sidebarEl && window.coreui && window.coreui.Sidebar) {
		let instance = window.coreui.Sidebar.getInstance(sidebarEl);
		if (!instance) {
			instance = new window.coreui.Sidebar(sidebarEl);
		}
		const headerToggler = document.querySelector('.header-toggler');
		if (headerToggler) {
			headerToggler.addEventListener('click', (e) => {
				e.preventDefault();
				// En desktop controlamos sólo con clase para permitir animación CSS simétrica
				if (window.innerWidth >= 992) {
					document.body.classList.toggle('sidebar-collapsed');
				} else {
					// En móvil usamos CoreUI (overlay)
					instance.toggle();
				}
			});
		}
		const sidebarClose = document.querySelector('#sidebar .btn-close');
		if (sidebarClose) {
			sidebarClose.addEventListener('click', (e) => {
				e.preventDefault();
				if (window.innerWidth >= 992) {
					document.body.classList.add('sidebar-collapsed');
				} else {
					instance.toggle();
				}
			});
		}

		// Normalización al cambiar de tamaño
		window.addEventListener('resize', () => {
			if (window.innerWidth >= 992) {
				// Asegura que el componente CoreUI esté visible en desktop
				try { instance.show(); } catch(_) {}
			} else {
				// En móvil, no mantener márgenes
				document.body.classList.remove('sidebar-collapsed');
			}
		});
	}
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
