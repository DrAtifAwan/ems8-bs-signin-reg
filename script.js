// Get references to elements
const showRegisterButton = document.getElementById('showRegister');
const hideRegisterButton = document.getElementById('hideRegister');
const registerContainer = document.getElementById('register');

// Show Register Form
showRegisterButton.addEventListener('click', () => {
    registerContainer.style.display = 'block'; // Show the register container
    window.scrollTo(0, registerContainer.offsetTop); // Scroll to the register section
});

// Hide Register Form
hideRegisterButton.addEventListener('click', () => {
    registerContainer.style.display = 'none'; // Hide the register container
});
