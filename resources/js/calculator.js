import axios from 'axios';

const calculatorButtons = document.getElementsByClassName('calculatorBtn');
const result = document.getElementById('result');
const clearButton = document.getElementById('clearButton');
const equalButton = document.getElementById('equalButton');
const backButton = document.getElementById('backspace');
const history = document.getElementById('historyList');
const clearHistory = document.getElementById('clearHistory');
let expression = '';

const skippedButtons = ['equalButton', 'backspace'];


Array.from(calculatorButtons).forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (!skippedButtons.includes(btn.id)) {

            // Check if the button clicked is an operator and the last character is already an operator
            if (/[+\-x÷/]$/.test(expression) && /[+\-x÷]/.test(btn.textContent)) {
                // Replace the last operator with the new one
                expression = expression.slice(0, -1) + btn.textContent;
            } else {
                // if clicked button is operator and current result is 0 append the operator otherwise override result display
                if (expression === '' && /[+\-x÷/]$/.test(this.textContent)) {
                     expression = '0';
                }
                // Add the clicked button's text to the expression
                expression += btn.textContent;
            }

            // Update the result display
            result.textContent = expression;

        }
    });
});

// Clear expression
clearButton.addEventListener('click', function () {
    expression = '';
    result.textContent = '0';
});

// Clear history
if (clearHistory) {
    clearHistory.addEventListener('click', function () {
        axios.delete('/history/delete').then(function () {
            location.reload();
        })
    });
}

// Backspace
backButton.addEventListener('click', function () {
    expression = expression.slice(0, -1);
    result.textContent = expression || '0'; // Show '0' if expression is empty
});

// Add click event listener to equal button
equalButton.addEventListener('click', function () {
    evaluateExpression();
});

// Send the expression to the server using Axios
function evaluateExpression() {

    // Remove last character if it is operator
    if (/[+\-x÷/]$/.test(expression)) {
        expression = expression.slice(0, -1);
    }

    if (expression !== '') {
        axios.post('/calculate', { expression: expression }, {
            headers: {'Content-Type': 'application/json',},})
            .then(response => {
                // Update the result with the calculated value
                result.textContent = response.data.result;

                if (history) {
                    let item = document.createElement("li");
                    item.textContent = expression + '=' + response.data.result;
                    item.classList.add('text-base', 'leading-5');
                    history.appendChild(item);
                } else {
                    location.reload();
                }

                expression = response.data.result;
                axios.get('/history').then(r => response => {})
            })
            .catch(error => {
                // Handle errors from the server
                result.textContent = 'Error';
                console.error('Server Error:', error);
            });
    }
}
