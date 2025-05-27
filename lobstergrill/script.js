function navigateToDishDetails(page, id) {
    window.location.href = page + '?dish=' + encodeURIComponent(id);
}

document.addEventListener("DOMContentLoaded", function(){
    var cardLowStock = document.getElementById("card-low-stock");
    var lowStockModal = document.getElementById("low-stock-modal");
    var lowStockCloseBtn = document.getElementById("low-stock-close-btn");

    cardLowStock.onclick = function(){
        lowStockModal.style.display = "block";
    }
    lowStockCloseBtn.onclick = function(){
        lowStockModal.style.display = "none";
    }

});

document.addEventListener("DOMContentLoaded", function() {
    // Add Supplier Modal
    var addSupplierModal = document.getElementById("suppliers-modal");
    var addSupplierBtn = document.getElementById("sup-add-new-btn");
    var addSupplierCancelBtn = document.getElementById("sup-cancel-btn");

    addSupplierBtn.onclick = function() {
        addSupplierModal.style.display = "block";
    }
    addSupplierCancelBtn.onclick = function() {
        addSupplierModal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == addSupplierModal) {
            addSupplierModal.style.display = "none";
        }
    }


    // Edit Supplier Modal
    var editSupplierModal = document.getElementById("suppliers-edit-modal");
    var editSupplierCancelBtn = document.getElementById("edit-sup-cancel-btn");

    editSupplierCancelBtn.onclick = function() {
        editSupplierModal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == editSupplierModal) {
            editSupplierModal.style.display = "none";
        }
    }

    var editSupplierBtns = document.querySelectorAll(".sup-edit-btn");
    editSupplierBtns.forEach(function(btn) {
        btn.onclick = function() {
            var supplierId = this.getAttribute("data-id");
            var supplierName = this.getAttribute("data-name");
            var contactNumber = this.getAttribute("data-contact");
            var street = this.getAttribute("data-street");
            var city = this.getAttribute("data-city");
            var province = this.getAttribute("data-province");

            document.getElementById("supplier-id").value = supplierId;
            document.getElementById("edit-supplier-name").value = supplierName;
            document.getElementById("edit-contact-number").value = contactNumber;
            document.getElementById("edit-street").value = street;
            document.getElementById("edit-city").value = city;
            document.getElementById("edit-province").value = province;

            editSupplierModal.style.display = "block";
        }
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Add Employee Modal
    var addModal = document.getElementById("employee-modal");
    var addBtn = document.getElementById("emp-add-new-btn");
    var addCancelBtn = document.getElementById("emp-cancel-btn");
    
    addBtn.onclick = function() {
        addModal.style.display = "block";
    }
    addCancelBtn.onclick = function() {
        addModal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == addModal) {
            addModal.style.display = "none";
        }
    }

    // Handle image upload preview
    document.getElementById('profile-icon-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
    
        reader.onload = function(e) {
            document.getElementById('profile-icon').querySelector('img').src = e.target.result;
        };
    
        reader.readAsDataURL(file);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // Edit Employee Modal
    var editModal = document.getElementById("employee-edit-modal");
    var editCancelBtn = document.getElementById("edit-cancel-btn");

    editCancelBtn.onclick = function() {
        editModal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }

    var editBtns = document.querySelectorAll(".emp-edit-btn");
    editBtns.forEach(function(btn) {
        btn.onclick = function() {
            var userId = this.getAttribute("data-id");
            var firstName = this.getAttribute("data-fname");
            var lastName = this.getAttribute("data-lname");
            var email = this.getAttribute("data-email");
            var role = this.getAttribute("data-role");
            var iconPath = this.getAttribute("data-icon");

            document.getElementById("user-id").value = userId;
            document.getElementById("edit-first-name").value = firstName;
            document.getElementById("edit-last-name").value = lastName;
            document.getElementById("edit-email").value = email;
            document.getElementById("edit-role").value = role;
            document.getElementById('profile-icon-edit').querySelector('img').src = iconPath;

            editModal.style.display = "block";
        }
    });

    // Handle image upload preview
    document.getElementById('profile-icon-upload-edit').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
    
        reader.onload = function(e) {
            document.getElementById('profile-icon-edit').querySelector('img').src = e.target.result;
        };
    
        reader.readAsDataURL(file);
    });

    // Function to handle deletion of employee
    window.deleteEmployee = function(userId) {
        if (confirm("Are you sure you want to delete this employee?")) {
            window.location.href = 'employees.php?delete-employee=true&user-id=' + userId;
        }
    }
});



document.addEventListener("DOMContentLoaded", function() {
    // Add Record Modal
    var addRecModal = document.getElementById("record-modal");
    var addRecBtn = document.getElementById("add-new-rec-btn");
    var addRecCancelBtn = document.getElementById("rec-cancel-btn");
    var addRecForm = document.getElementById("record-form");

    addRecBtn.onclick = function() {
        addRecModal.style.display = "block";
        addRecForm.reset();
    }

    addRecCancelBtn.onclick = function() {
        addRecModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == addRecModal) {
            addRecModal.style.display = "none";
        }
    }

    // Edit Record Modal
    var editRecModal = document.getElementById("edit-record-modal");
    var editRecCancelBtn = document.getElementById("edit-rec-cancel-btn");
    var editRecForm = document.getElementById("edit-record-form");

    editRecCancelBtn.onclick = function() {
        editRecModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == editRecModal) {
            editRecModal.style.display = "none";
        }
    }

    document.querySelectorAll(".rec-edit-btn").forEach(function(btn) {
        btn.onclick = function() {
            var recordId = this.getAttribute("data-id");
            var date = this.getAttribute("data-date");
            var additionalQuantity = this.getAttribute("data-additional-quantity");
            var soldQuantity = this.getAttribute("data-sold-quantity");
            var wastedQuantity = this.getAttribute("data-wasted-quantity");

            document.getElementById("record-id").value = recordId;
            document.getElementById("edit-rec-date").value = date;
            document.getElementById("edit-additional-quantity").value = additionalQuantity;
            document.getElementById("edit-sold-quantity").value = soldQuantity;
            document.getElementById("edit-wasted-quantity").value = wastedQuantity;

            editRecModal.style.display = "block";
        }
    });

    document.querySelectorAll(".rec-delete-btn").forEach(function(btn) {
        btn.onclick = function() {
            var deleteDate = this.getAttribute("data-date");
            var deleteId = this.getAttribute("data-id");

            if(confirm("Are you sure you want to delete this record?")){
                window.location.href = 'dishRecord.php?dish=' + deleteId + '&delete-record=true&delete-date=' + deleteDate;
            } else {
                alert("Action cancelled.");
            }
        }
    });

    // Sort functionality
    document.getElementById('sort-options').addEventListener('change', function() {
        var selectedOption = this.value.split('_');
        var sortBy = selectedOption[0];
        var sortOrder = selectedOption[1];

        var urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort_by', sortBy);
        urlParams.set('sort_order', sortOrder);
        window.location.search = urlParams.toString();
    });
});








document.addEventListener("DOMContentLoaded", function() {
    // Add Dish Modal
    var addDishModal = document.getElementById("add-dish-modal");
    var addDishBtn = document.getElementById("add-new-dish-btn");
    var addDishCancelBtn = document.getElementById("add-dish-cancel-btn");
    var addDishForm = document.getElementById("add-dish-form");
    var addDishCategory = document.getElementById("add-dish-category");
    var newCategoryBox = document.getElementById("new-category-box");

    addDishBtn.onclick = function() {
        addDishModal.style.display = "block";
        addDishForm.reset();
    }
    addDishCancelBtn.onclick = function() {
        addDishModal.style.display = "none";
    }
    addDishCategory.onchange = function(){
        if(addDishCategory.value == "NewCategory"){
            newCategoryBox.style.display = "block";
        } else {
            newCategoryBox.style.display = "none";
        }
    }
    window.onclick = function(event) {
        if (event.target == addDishModal) {
            addDishModal.style.display = "none";
        }
    }

    // Edit Dish Modal
    var editDishModal = document.getElementById("edit-dish-modal");
    var editDishCancelBtn = document.getElementById("edit-dish-cancel-btn");
    var editDishForm = document.getElementById("edit-dish-form");
    var editDishCategory = document.getElementById("edit-dish-category");
    var editNewCategoryBox = document.getElementById("edit-new-category-box");

    editDishCancelBtn.onclick = function() {
        editDishModal.style.display = "none";
    }
    editDishCategory.onchange = function(){
        if(editDishCategory.value == "NewCategory"){
            editNewCategoryBox.style.display = "block";
        } else {
            editNewCategoryBox.style.display = "none";
        }
    }
    window.onclick = function(event) {
        if (event.target == editDishModal) {
            editDishModal.style.display = "none";
        }
    }

    document.querySelectorAll(".inv-edit-btn").forEach(function(btn) {
        btn.onclick = function() {
            var dishId = this.getAttribute("data-id");
            var dishName = this.getAttribute("data-name");
            var dishCategory = this.getAttribute("data-category");
            var dishPrice = this.getAttribute("data-price");
            var dishWeight = this.getAttribute("data-weight");
            var dishMinQuantity = this.getAttribute("data-minquantity");

            document.getElementById("edit-dish-id").value = dishId;
            document.getElementById("original-dish-name").value = dishName;
            document.getElementById("edit-dish-name").value = dishName;
            document.getElementById("edit-dish-category").value = dishCategory;
            document.getElementById("edit-dish-price").value = dishPrice;
            document.getElementById("edit-dish-weight").value = dishWeight;
            document.getElementById("edit-dish-minquantity").value = dishMinQuantity;

            editDishModal.style.display = "block";
        }
    });

    document.querySelectorAll(".inv-delete-btn").forEach(function(btn) {
        btn.onclick = function() {
            var deleteId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to delete this item?")) {
                fetch("", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        "delete-dish-id": deleteId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Item deleted successfully");
                        document.querySelector(`tr[data-id='${deleteId}']`).remove();
                    } else {
                        alert("Error deleting item: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Error deleting item: " + error.message);
                });
            }
        }
    });

    // Pagination functionality
    document.querySelectorAll(".pagination-link").forEach(function(link) {
        link.onclick = function(event) {
            event.preventDefault();
            window.location.href = this.getAttribute('href');
        }
    });

// Search functionality
document.querySelector('.search-bar input').addEventListener('input', function() {
    var searchQuery = this.value;
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "search": searchQuery
        })
    })
    .then(response => response.json())
    .then(data => {
        var tableBody = document.querySelector('.inventory-table tbody');
        tableBody.innerHTML = "";
        data.items.forEach(function(item) {
            var row = document.createElement('tr');
            row.setAttribute('data-id', item.inventory_pack_id);
            
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.category}</td>
                <td>${item.price}</td>
                <td>${item.weight}</td>
                <td>${item.minquantity}</td>
                <td class="view">
                    <form action="dishRecord.php" method="GET">
                        <input type="hidden" name="dish" value="${item.inventory_pack_id}">
                        <button type="submit" class="btn btn-primary">View Inventory</button>
                    </form>
                </td>
                <td>
                    <button class="inv-edit-btn"
                        data-id="${item.inventory_pack_id}"
                        data-name="${item.name}"
                        data-category="${item.category}"
                        data-price="${item.price}"
                        data-weight="${item.weight}"
                        data-minquantity="${item.minquantity}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="inv-delete-btn"
                        data-id="${item.inventory_pack_id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);

            // Reattach event listeners for new rows
            row.querySelector('.inv-edit-btn').addEventListener('click', function() {
                var dishId = this.getAttribute("data-id");
                var dishName = this.getAttribute("data-name");
                var dishCategory = this.getAttribute("data-category");
                var dishPrice = this.getAttribute("data-price");
                var dishWeight = this.getAttribute("data-weight");
                var dishMinQuantity = this.getAttribute("data-minquantity");

                document.getElementById("edit-dish-id").value = dishId;
                document.getElementById("original-dish-name").value = dishName;
                document.getElementById("edit-dish-name").value = dishName;
                document.getElementById("edit-dish-category").value = dishCategory;
                document.getElementById("edit-dish-price").value = dishPrice;
                document.getElementById("edit-dish-weight").value = dishWeight;
                document.getElementById("edit-dish-minquantity").value = dishMinQuantity;

                editDishModal.style.display = "block";
            });

            row.querySelector('.inv-delete-btn').addEventListener('click', function() {
                var deleteId = this.getAttribute("data-id");

                if (confirm("Are you sure you want to delete this item?")) {
                    fetch("", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: new URLSearchParams({
                            "delete-dish-id": deleteId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("Item deleted successfully");
                            document.querySelector(`tr[data-id='${deleteId}']`).remove();
                        } else {
                            alert("Error deleting item: " + data.message);
                        }
                    })
                    .catch(error => {
                        alert("Error deleting item: " + error.message);
                    });
                }
            });
        });

        // Hide pagination
        var pagination = document.querySelector('.pagination');
        pagination.style.display = 'none';
    })
    .catch(error => {
        alert("Error fetching search results: " + error.message);
    });
});

// Filter functionality
document.getElementById('filter-category').addEventListener('change', function() {
    var filterCategory = this.value;
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "filter-category": filterCategory
        })
    })
    .then(response => response.json())
    .then(data => {
        var tableBody = document.querySelector('.inventory-table tbody');
        tableBody.innerHTML = "";
        data.items.forEach(function(item) {
            var row = document.createElement('tr');
            row.setAttribute('data-id', item.inventory_pack_id);
            
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.category}</td>
                <td>${item.price}</td>
                <td>${item.weight}</td>
                <td>${item.minquantity}</td>
                <td class="view">
                    <form action="dishRecord.php" method="GET">
                        <input type="hidden" name="dish" value="${item.inventory_pack_id}">
                        <button type="submit" class="btn btn-primary">View Inventory</button>
                    </form>
                </td>
                <td>
                    <button class="inv-edit-btn"
                        data-id="${item.inventory_pack_id}"
                        data-name="${item.name}"
                        data-category="${item.category}"
                        data-price="${item.price}"
                        data-weight="${item.weight}"
                        data-minquantity="${item.minquantity}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="inv-delete-btn"
                        data-id="${item.inventory_pack_id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);

            // Reattach event listeners for new rows
            row.querySelector('.inv-edit-btn').addEventListener('click', function() {
                var dishId = this.getAttribute("data-id");
                var dishName = this.getAttribute("data-name");
                var dishCategory = this.getAttribute("data-category");
                var dishPrice = this.getAttribute("data-price");
                var dishWeight = this.getAttribute("data-weight");
                var dishMinQuantity = this.getAttribute("data-minquantity");

                document.getElementById("edit-dish-id").value = dishId;
                document.getElementById("original-dish-name").value = dishName;
                document.getElementById("edit-dish-name").value = dishName;
                document.getElementById("edit-dish-category").value = dishCategory;
                document.getElementById("edit-dish-price").value = dishPrice;
                document.getElementById("edit-dish-weight").value = dishWeight;
                document.getElementById("edit-dish-minquantity").value = dishMinQuantity;

                editDishModal.style.display = "block";
            });

            row.querySelector('.inv-delete-btn').addEventListener('click', function() {
                var deleteId = this.getAttribute("data-id");

                if (confirm("Are you sure you want to delete this item?")) {
                    fetch("", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: new URLSearchParams({
                            "delete-dish-id": deleteId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("Item deleted successfully");
                            document.querySelector(`tr[data-id='${deleteId}']`).remove();
                        } else {
                            alert("Error deleting item: " + data.message);
                        }
                    })
                    .catch(error => {
                        alert("Error deleting item: " + error.message);
                    });
                }
            });
        });

        // Hide pagination
        var pagination = document.querySelector('.pagination');
        pagination.style.display = 'none';
    })
    .catch(error => {
        alert("Error fetching filter results: " + error.message);
    });
});
});

// Add Delivery Log Record
document.addEventListener("DOMContentLoaded", function() {
    // Add Record Modal
    var addRecModal = document.getElementById("record-deli-modal");
    var addRecBtn = document.getElementById("add-new-deli-btn");
    var addRecCancelBtn = document.getElementById("deli-cancel-btn");
    var addRecForm = document.getElementById("deli-record-form");

    addRecBtn.onclick = function() {
        addRecModal.style.display = "block";
        addRecForm.reset();
    }

    addRecCancelBtn.onclick = function() {
        addRecModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == addRecModal) {
            addRecModal.style.display = "none";
        }
    }

    // Edit Record Modal
    var editRecModal = document.getElementById("edit-deli-record-modal");
    var editRecCancelBtn = document.getElementById("edit-deli-cancel-btn");
    var editRecForm = document.getElementById("edit-deli-record-form");

    editRecCancelBtn.onclick = function() {
        editRecModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == editRecModal) {
            editRecModal.style.display = "none";
        }
    }

    document.querySelectorAll(".deli-edit-btn").forEach(function(btn) {
        btn.onclick = function() {
            var recordId = this.getAttribute("data-id");
            var materialName = this.getAttribute("data-name");
            var weight = this.getAttribute("data-weight");
            var deliveryDate = this.getAttribute("data-date");

            document.getElementById("record-id").value = recordId;
            document.getElementById("edit-material-name").value = materialName;
            document.getElementById("edit-weight-in-kg").value = weight;
            document.getElementById("edit-date").value = deliveryDate;

            editRecModal.style.display = "block";
        }
    });

    document.querySelectorAll(".deli-delete-btn").forEach(function(btn) {
        btn.onclick = function() {
            var recordId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to delete this record?")) {
                fetch("delete_record.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        "record-id": recordId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Record deleted successfully");
                        document.querySelector(`tr[data-id='${recordId}']`).remove();
                    } else {
                        alert("Error deleting record: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Error deleting record: " + error.message);
                });
            }
        }
    });

// Sort functionality
document.getElementById('sort-options').addEventListener('change', function() {
    var selectedOption = this.value.split('_');
    var sortBy = selectedOption[0];
    var sortOrder = selectedOption[1];

    var urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort_by', sortBy);
    urlParams.set('sort_order', sortOrder);
    window.location.search = urlParams.toString();
});
});

// Function to handle sorting
function sortTable(sortBy, sortOrder) {
    var table = document.querySelector('.deli-table tbody');
    var rows = Array.from(table.rows);

    rows.sort(function(a, b) {
        var cellA = a.querySelector(`[data-sort='${sortBy}']`).textContent;
        var cellB = b.querySelector(`[data-sort='${sortBy}']`).textContent;

        if (sortOrder === 'ASC') {
            return cellA.localeCompare(cellB);
        } else {
            return cellB.localeCompare(cellA);
        }
    });

    rows.forEach(function(row) {
        table.appendChild(row);
    });
}

document.addEventListener("DOMContentLoaded", function() {
    var sortOptions = document.getElementById('sort-options');

    sortOptions.addEventListener('change', function() {
        var selectedOption = this.value.split('_');
        var sortBy = selectedOption[0];
        var sortOrder = selectedOption[1];

        sortTable(sortBy, sortOrder);
    });
});