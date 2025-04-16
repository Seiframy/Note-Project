// Select the button element with the class 'create_acc_link'
let button = document.querySelector('.create_acc_link');

// Add a click event listener to the button
button.addEventListener('click', function () {
    // Show a confirmation dialog and store the user's response (true for OK, false for Cancel)
    let userResponse = confirm('Make New Account?');
    // If the user clicks "OK", navigate to 'create_acc.html'
    if (userResponse) {
        window.location.href = 'create_acc.html';
    }
});





