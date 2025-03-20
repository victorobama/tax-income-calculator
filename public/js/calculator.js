document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("tax-form");
    const resultContainer = document.getElementById("result");

    form.addEventListener("submit", function(event) {
        event.preventDefault();
        const income = document.getElementById("income").value;

        if (!income || income <= 0) {
            alert("Please enter a valid income.");
            return;
        }

        fetch("/api/v1/taxes/calculate", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ income: income })
        })
            .then(response => response.json())
            .then(data => {
                resultContainer.innerHTML = `
                <h2>Tax Calculation Results</h2>
                <div class="results">
                    <p><strong>Gross Annual Salary:</strong> £${data.gross_annual_salary}</p>
                    <p><strong>Gross Monthly Salary:</strong> £${data.gross_monthly_salary}</p>
                    <p><strong>Net Annual Salary:</strong> £${data.net_annual_salary}</p>
                    <p><strong>Net Monthly Salary:</strong> £${data.net_monthly_salary}</p>
                    <p><strong>Annual Tax Paid:</strong> £${data.annual_tax_paid}</p>
                    <p><strong>Monthly Tax Paid:</strong> £${data.monthly_tax_paid}</p>
                </div>
            `;
            })
            .catch(error => {
                console.error("Error fetching tax data:", error);
                resultContainer.innerHTML = "<p style='color: red;'>Error fetching tax data. Please try again.</p>";
            });
    });
});