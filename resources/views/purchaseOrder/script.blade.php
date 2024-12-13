<script>

    // Global variable to store the selected row
    let selectedRow = null;
    let itemIndex = 0; // Initialize the index variable globally

    // Open the item modal and store the selected row
    function openItemModal(button) {
        selectedRow = button.closest("tr");
        const itemModal = new bootstrap.Modal(document.getElementById("itemModal"), {});
        itemModal.show();
    }

    // Add selected item details to the table
    function addItemToTable(code, description, price) {
        if (!selectedRow) return;

        // Populate the selected row with item details
        selectedRow.querySelector(".item-code").value = code;
        selectedRow.querySelector(".item-description").value = description;
        selectedRow.querySelector(".price").value = price;
        selectedRow.querySelector(".quantity").value = 1; // Default quantity
        selectedRow.querySelector(".item-total").innerText = (price * 1).toFixed(2);

        calculateSubtotal();

        // Close modal
        const itemModal = bootstrap.Modal.getInstance(document.getElementById("itemModal"));
        itemModal.hide();
    }

    // Add a new item row to the table
    function addItem() {
        const table = document.getElementById("items-table");
        const row = document.createElement("tr");

        row.innerHTML = `
        <td>${itemIndex + 1}</td>
        <td>
            <button type="button" class="btn btn-link" onclick="openItemModal(this)">Select Item</button>
        </td>
        <td>
       
            <input type="text" name="quotation_items[${itemIndex}][cQuoItemProductCode]" class="form-control item-code" readonly>
        </td>
        <td>
        
            <input type="text" name="quotation_items[${itemIndex}][cQuoItemDescription]" class="form-control item-description" readonly>
        </td>
        <td>
        
            <input type="number" name="quotation_items[${itemIndex}][iQuoItemQuantity]" class="form-control quantity" value="1" min="1" oninput="calculateItemTotal(this)">
        </td>
        <td>
        
            <input type="number" name="quotation_items[${itemIndex}][yQuoItemPriceUnit]" class="form-control price" value="0" min="0" step="0.01" oninput="calculateItemTotal(this)">
        </td>
        <td>
            <span class="item-total">0.00</span>
            
            <input type="hidden" name="quotation_items[${itemIndex}][yQuoItemTotal]" class="item-total-input" value="0">
        </td>
        <td>
            <button type="button" class="btn btn-danger" onclick="removeItem(this)">Remove</button>
        </td>
    `;

        table.appendChild(row);
        itemIndex++;
    }

    // Display company details based on the selected company
    function loadCompanyDetails(companyId) {
        if (!companyId) {
            clearCompanyDetails();
            return;
        }

        fetch(`/api/companies/${companyId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("company-logo").src = `/storage/logos/${data.logo}`;
                document.getElementById("company-name").innerText = data.description;
                document.getElementById("company-address").innerText = data.address;
                document.getElementById("company-contact").innerText = `${data.phone} | ${data.email}`;
            })
            .catch(error => console.error("Error:", error));
    }

    function clearCompanyDetails() {
        document.getElementById("company-logo").src = "";
        document.getElementById("company-name").innerText = "";
        document.getElementById("company-address").innerText = "";
        document.getElementById("company-contact").innerText = "";
    }

    // Display customer details based on the selected option
    function displayCustomerDetails(select) {
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            document.getElementById("customer-details").style.display = "block";
            document.getElementById("customer-name").innerText = selectedOption.getAttribute("data-name");
            document.getElementById("customer-company").innerText = selectedOption.getAttribute("data-company");
            document.getElementById("customer-address").innerText = selectedOption.getAttribute("data-address");
            document.getElementById("customer-phone").innerText = selectedOption.getAttribute("data-phone");
            document.getElementById("customer-email").innerText = selectedOption.getAttribute("data-email");
        } else {
            document.getElementById("customer-details").style.display = "none";
            clearCustomerDetails();
        }
    }

    function clearCustomerDetails() {
        document.getElementById("customer-name").innerText = "";
        document.getElementById("customer-company").innerText = "";
        document.getElementById("customer-address").innerText = "";
        document.getElementById("customer-phone").innerText = "";
        document.getElementById("customer-email").innerText = "";
    }

    // Update item total when quantity or price changes
    function calculateItemTotal(input) {
        const row = input.closest("tr");
        const quantity = parseFloat(row.querySelector(".quantity").value) || 0;
        const price = parseFloat(row.querySelector(".price").value) || 0;
        const total = quantity * price;

        row.querySelector(".item-total").textContent = total.toFixed(2);
        row.querySelector(".item-total-input").value = total.toFixed(2);

        calculateSubtotal();
    }

    // Calculate subtotal (sum of all item totals)
    function calculateSubtotal() {
        const rows = document.querySelectorAll("#items-table tr");
        let subtotal = 0;

        rows.forEach(row => {
            const total = parseFloat(row.querySelector(".item-total").innerText) || 0;
            subtotal += total;
        });

        document.getElementById("subtotal").innerText = subtotal.toFixed(2);
        calculateTotal();
    }

    // Calculate total amount
    function calculateTotal() {
        const subtotal = parseFloat(document.getElementById("subtotal").innerText) || 0;
        const discount = parseFloat(document.getElementById("discount").value) || 0;
        const tax = parseFloat(document.getElementById("additional-tax").value) || 0;
        const shipping = parseFloat(document.getElementById("shipping").value) || 0;

        const total = subtotal - (subtotal * discount / 100) + (subtotal * tax / 100) + shipping;

        document.getElementById("total-amount").textContent = total.toFixed(2);
        document.getElementById("hidden-total-amount").value = total.toFixed(2);
    }

    // Remove an item row
    function removeItem(button) {
        const row = button.closest("tr");
        row.remove();
        updateRowNumbers();
        calculateSubtotal();
    }

    // Update row numbers after item removal
    function updateRowNumbers() {
        const rows = document.querySelectorAll("#items-table tr");
        rows.forEach((row, index) => {
            row.querySelector("td:first-child").textContent = index + 1;
        });
    }

    // Handle tab switching
    function handleTabSwitch(event) {
        const target = event.target;

        if (target.tagName === "BUTTON") {
            document.querySelectorAll(".tab-pane").forEach(tab => tab.classList.remove("show", "active"));

            const targetTab = document.querySelector(target.getAttribute("data-bs-target"));
            if (targetTab) {
                targetTab.classList.add("show", "active");
            }
        }
    }

    document.getElementById("itemTab").addEventListener("click", handleTabSwitch);

    // Initialize tabs on page load
    document.addEventListener("DOMContentLoaded", () => {
        const tabButtons = document.querySelectorAll("#itemTab .nav-link");

        tabButtons.forEach(button => {
            button.addEventListener("click", () => {
                tabButtons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");
                tabButtons.forEach(btn => btn.setAttribute("aria-selected", "false"));
                button.setAttribute("aria-selected", "true");
            });
        });
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        const formData = new FormData(this);
        for (const [key, value] of formData.entries()) {
            console.log(key, value);
        }
    });


</script>