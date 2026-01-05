// Validation utilities
export class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = {};
    }

    static isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    static isValidPhone(phone) {
        const re = /^[\d\s\-\+\(\)]{7,15}$/;
        return re.test(phone.replace(/\s/g, ''));
    }

    showFieldError(fieldName, message) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        const feedback = field?.parentNode.querySelector('.validation-feedback');
        
        if (field && feedback) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            feedback.textContent = message;
            feedback.style.display = 'block';
            feedback.classList.add('validation-error');
        }
    }

    showFieldSuccess(fieldName) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        const feedback = field?.parentNode.querySelector('.validation-feedback');
        
        if (field && feedback) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            feedback.style.display = 'none';
        }
    }

    clearFieldError(fieldName) {
        const field = this.form.querySelector(`[name="${fieldName}"]`);
        const feedback = field?.parentNode.querySelector('.validation-feedback');
        
        if (field && feedback) {
            field.classList.remove('is-invalid', 'is-valid');
            feedback.style.display = 'none';
        }
    }

    clearAllErrors() {
        this.form.querySelectorAll('.is-invalid, .is-valid').forEach(field => {
            field.classList.remove('is-invalid', 'is-valid');
        });
        this.form.querySelectorAll('.validation-feedback').forEach(feedback => {
            feedback.style.display = 'none';
        });
    }
}