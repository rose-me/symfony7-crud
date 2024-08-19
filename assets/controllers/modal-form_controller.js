import { Controller } from "stimulus";
import { Modal } from "bootstrap";
import $ from 'jquery';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];
    static values = {
        formUrl: String,
    };

    connect() {
        console.log('Connected Modal...');
    }

    openModal(event) {
        console.log(event);
        const modal = new Modal(this.modalTarget);
        modal.show();
    }

    closeModal(event) {
        console.log(event);
        this.modalTarget.classList.remove('show');
        this.modalTarget.setAttribute('aria-hidden', 'true');
        this.modalTarget.style.display = 'none';
        this.modalTarget.removeAttribute('role');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
    }

    async deleteForm() {
        const $form = $(this.modalBodyTarget).find('form');
        new Promise((resolve, reject) => {
            $.ajax({
                url: $form.prop('action'),
                method: $form.prop('method'),
                data: $form.serialize(),
                success: (response) => {
                    resolve(response);
                },
                error: (error) => {
                    reject(error);
                }
            });
        })
        .then((response) => {
            // Handle success, e.g., reload the page or remove the row
            console.log('Success:', response);
            window.location.replace(response.redirectUrl);
        })
        .catch((error) => {
            // Handle the error and close the modal
            console.error('Error:', error);
            console.error('Error:', error.responseJSON);
            this.closeModal();
            this.showFlashMessage(error.responseJSON.message, 'danger');
        });        
    }

    showFlashMessage(message, status) {
        const flashElement = document.getElementById('flash');

        flashElement.innerHTML = `
            <div class="alert alert-${status} alert-dismissible d-flex align-items-center fade show" role="alert">
                <i class="ti-alert me-2"></i>    
                <strong>${message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Automatically remove the flash message after a certain time
        // setTimeout(() => {
        //     flashElement.querySelector('.alert').remove();
        // }, 3000); // 3 seconds
    }

    async submitForm() {
        const $form = $(this.modalBodyTarget).find('form');
        this.modalBodyTarget.innerHTML = await $.ajax({
            url: $form.prop('action'),
            method: $form.prop('method'),
            data: $form.serialize(),
        });
    }
}