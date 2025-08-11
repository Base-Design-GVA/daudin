document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.property-contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', handleFormSubmission);
    }
    
    function handleFormSubmission(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        
        // Show loader
        submitButton.textContent = 'Envoi en cours...';
        submitButton.disabled = true;
        submitButton.style.opacity = '0.6';
        submitButton.style.cursor = 'not-allowed';
        
        // Get form data
        const formData = new FormData(form);
        
        // Send request
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('=== FORM SUBMISSION RESPONSE ===');
            console.log('Response Status:', response.status);
            console.log('Response Headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('=== IMMOMIG API RESPONSE DETAILS ===');
            console.log('Full Response Data:', data);
            
            if (data.success) {
                console.log('✅ SUCCESS: Form submitted successfully');
                console.log('Success Message:', data.message);
                if (data.data && data.data.immomig_response) {
                    console.log('Immomig API Response Data:', data.data.immomig_response);
                }
                showMessage('success', data.message);
                form.reset();
            } else {
                console.log('❌ ERROR: Form submission failed');
                console.log('Error Message:', data.message);
                if (data.errors) {
                    console.log('Error Details:', data.errors);
                }
                showMessage('error', data.message, data.errors);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'Une erreur est survenue. Veuillez réessayer.');
        })
        .finally(() => {
            // Reset button
            submitButton.textContent = originalButtonText;
            submitButton.disabled = false;
            submitButton.style.opacity = '1';
            submitButton.style.cursor = 'pointer';
        });
    }
    
    function showMessage(type, message, errors = null) {
        // Remove existing messages
        const existingMessage = document.querySelector('.form-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `form-message form-message--${type}`;
        
        let messageHTML = `<p>${message}</p>`;
        
        if (errors && errors.length > 0) {
            messageHTML += '<ul>';
            errors.forEach(error => {
                messageHTML += `<li>${error}</li>`;
            });
            messageHTML += '</ul>';
        }
        
        messageDiv.innerHTML = messageHTML;
        
        // Insert after form
        const form = document.querySelector('.property-contact-form');
        form.parentNode.insertBefore(messageDiv, form.nextSibling);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }
}); 