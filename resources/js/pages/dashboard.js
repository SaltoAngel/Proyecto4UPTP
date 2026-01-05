// Dashboard functionality
import { ExchangeRateService } from '../components/exchange-rate.js';

export class Dashboard {
    constructor() {
        this.exchangeService = new ExchangeRateService();
        this.init();
    }

    init() {
        this.initExchangeRate();
        this.initCharts();
        this.initCalendar();
    }

    initExchangeRate() {
        this.exchangeService.loadRate();
        // Update every 30 minutes
        setInterval(() => {
            this.exchangeService.loadRate();
        }, 30 * 60 * 1000);
    }

    initCharts() {
        this.initLineChart();
        this.initBarChart();
        this.initGeoChart();
    }

    initLineChart() {
        const canvas = document.getElementById('md-line');
        if (!canvas || !window.Chart) return;

        window.skeletonAttach('md-line', { type: 'rect', height: 170 });
        
        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['L','M','M','J','V','S','D'],
                datasets: [{
                    label: 'Visitas',
                    data: [12,19,3,5,2,3,10],
                    borderColor: '#fff',
                    backgroundColor: 'rgba(255,255,255,.2)'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        window.skeletonReady('md-line');
    }

    initBarChart() {
        const canvas = document.getElementById('md-bars');
        if (!canvas || !window.Chart) return;

        window.skeletonAttach('md-bars', { type: 'rect', height: 170 });
        
        new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['P','Q','R','S','T','U'],
                datasets: [{
                    label: 'Altas',
                    data: [5,4,6,7,3,4],
                    backgroundColor: '#fff'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        window.skeletonReady('md-bars');
    }

    initGeoChart() {
        // Geo chart implementation would go here
        // Keeping it simple for now
        const canvas = document.getElementById('md-geo');
        if (canvas) {
            window.skeletonAttach('md-geo', { type: 'rect', height: 280 });
            setTimeout(() => window.skeletonReady('md-geo'), 2000);
        }
    }

    initCalendar() {
        // Calendar implementation would go here
        // Keeping it simple for now
        console.log('Calendar initialized');
    }
}