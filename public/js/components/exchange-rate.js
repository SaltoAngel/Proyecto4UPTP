// Exchange Rate Service
export class ExchangeRateService {
    constructor() {
        this.apiUrl = '/api/exchange-rate'; // We'll create this endpoint
        this.elements = {
            rate: document.getElementById('usd-rate'),
            change: document.getElementById('usd-change'),
            date: document.getElementById('rate-date')
        };
    }

    async loadRate() {
        try {
            const response = await fetch(this.apiUrl);
            const data = await response.json();
            
            if (data.success) {
                this.updateDisplay(data.data);
            } else {
                this.showError();
            }
        } catch (error) {
            console.error('Error loading exchange rate:', error);
            this.showError();
        }
    }

    updateDisplay(data) {
        if (!this.elements.rate) return;

        // Update rate
        this.elements.rate.textContent = `Bs. ${data.rate}`;
        
        // Update change
        if (this.elements.change) {
            const changeEl = this.elements.change.querySelector('span');
            if (changeEl) {
                changeEl.className = data.isPositive ? 'text-success font-weight-bolder' : 'text-danger font-weight-bolder';
                changeEl.innerHTML = `${data.isPositive ? '+' : ''}${data.change}% <i class="material-icons text-sm">${data.isPositive ? 'arrow_upward' : 'arrow_downward'}</i>`;
            }
        }
        
        // Update date
        if (this.elements.date) {
            const dateParts = data.date.split('-');
            const dateFormatted = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
            this.elements.date.textContent = `Última actualización: ${dateFormatted}`;
        }
    }

    showError() {
        if (this.elements.rate) {
            this.elements.rate.textContent = 'Error';
        }
        if (this.elements.date) {
            this.elements.date.textContent = 'No disponible';
        }
    }
}