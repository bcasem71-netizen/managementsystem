// Update footer year automatically.
const yearNode = document.getElementById('year');
if (yearNode) {
    yearNode.textContent = new Date().getFullYear();
}

// Client-side validation for the add student form.
const form = document.getElementById('studentForm');
const formMessage = document.getElementById('formMessage');

if (form) {
    form.addEventListener('submit', (event) => {
        const studentName = document.getElementById('student_name')?.value.trim() || '';
        const rollNumber = document.getElementById('roll_number')?.value.trim() || '';
        const email = document.getElementById('email')?.value.trim() || '';
        const weightRaw = document.getElementById('weight')?.value.trim() || '';

        const errors = [];

        // Required field checks.
        if (!studentName) errors.push('Student name is required.');
        if (!rollNumber) errors.push('Roll number is required.');
        if (!email) errors.push('Email is required.');

        // Email format check.
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailPattern.test(email)) {
            errors.push('Please enter a valid email address.');
        }

        // Numeric weight check.
        const weight = Number(weightRaw);
        if (!weightRaw) {
            errors.push('Weight is required.');
        } else if (Number.isNaN(weight) || weight <= 0) {
            errors.push('Weight must be a positive number.');
        }

        // Show feedback and prevent submit if validation fails.
        if (errors.length > 0) {
            event.preventDefault();
            if (formMessage) {
                formMessage.className = 'form-message error';
                formMessage.textContent = errors.join(' ');
            }
        } else if (formMessage) {
            formMessage.className = 'form-message success';
            formMessage.textContent = 'Validation passed. Submitting form...';
        }
    });
}
