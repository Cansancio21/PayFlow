<?php
include '../views/db.php';

// Handle all requests (GET/POST) using $_REQUEST
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    
    if ($action == 'add') {
        $b_code = $_REQUEST['b_code'];
        $b_BName = $_REQUEST['b_BName'];
        $b_name = $_REQUEST['b_name'];
        $b_involvedP = $_REQUEST['b_involvedP'];
        $b_date = $_REQUEST['b_date'];

        $stmt = $conn->prepare("INSERT INTO tbl_bills (b_code, b_BName, b_name, b_involvedP, b_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $b_code, $b_BName, $b_name, $b_involvedP, $b_date);

        if ($stmt->execute()) {
            $last_id = $stmt->insert_id;
            echo json_encode(["status" => "success", "message" => "New bill added successfully", "b_id" => $last_id]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
        exit;
    }
    elseif ($action == 'update') {
        $b_id = $_REQUEST['b_id'];
        $b_code = $_REQUEST['b_code'];
        $b_BName = $_REQUEST['b_BName'];
        $b_name = $_REQUEST['b_name'];
        $b_involvedP = $_REQUEST['b_involvedP'];
        $b_date = $_REQUEST['b_date'];

        $stmt = $conn->prepare("UPDATE tbl_bills SET b_code=?, b_BName=?, b_name=?, b_involvedP=?, b_date=? WHERE b_id=?");
        $stmt->bind_param("sssssi", $b_code, $b_BName, $b_name, $b_involvedP, $b_date, $b_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Bill updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating bill: " . $stmt->error]);
        }

        $stmt->close();
        exit;
    }
    elseif ($action == 'archive') {
        $b_id = $_REQUEST['b_id'];
        
        $stmt = $conn->prepare("SELECT * FROM tbl_bills WHERE b_id = ?");
        $stmt->bind_param("i", $b_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bill = $result->fetch_assoc();
        $stmt->close();

        if ($bill) {
            $stmt = $conn->prepare("INSERT INTO tbl_archive (b_id, b_code, b_BName, b_name, b_involvedP, b_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $bill['b_id'], $bill['b_code'], $bill['b_BName'], $bill['b_name'], $bill['b_involvedP'], $bill['b_date']);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM tbl_bills WHERE b_id = ?");
            $stmt->bind_param("i", $b_id);
            
            if ($stmt->execute()) {
                $result = $conn->query("SELECT COUNT(*) as count FROM tbl_archive");
                $row = $result->fetch_assoc();
                
                echo json_encode([
                    "status" => "success", 
                    "message" => "Bill archived successfully",
                    "archiveCount" => $row['count']
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error archiving bill"]);
            }
            
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Bill not found"]);
        }
        exit;
    }
    elseif ($action == 'get_bill') {
        $b_id = $_REQUEST['b_id'];
        
        $stmt = $conn->prepare("SELECT * FROM tbl_bills WHERE b_id = ?");
        $stmt->bind_param("i", $b_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $bill = $result->fetch_assoc();
            echo json_encode(["status" => "success", "bill" => $bill]);
        } else {
            echo json_encode(["status" => "error", "message" => "Bill not found"]);
        }
        
        $stmt->close();
        $conn->close();
        exit;
    }
    elseif ($action == 'get_archive_count') {
        $result = $conn->query("SELECT COUNT(*) as count FROM tbl_archive");
        $row = $result->fetch_assoc();
        echo json_encode(["status" => "success", "count" => $row['count']]);
        $conn->close();
        exit;
    }
    elseif ($action == 'get_archived_bills') {
        $bills = [];
        $result = $conn->query("SELECT * FROM tbl_archive");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $bills[] = $row;
            }
        }
        echo json_encode(["status" => "success", "bills" => $bills]);
        $conn->close();
        exit;
    }
}

// Fetch active bills from the database to display
$bills = [];
$result = $conn->query("SELECT * FROM tbl_bills");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bills[] = $row;
    }
}

// Get archive count
$archive_count = 0;
$result = $conn->query("SELECT COUNT(*) as count FROM tbl_archive");
if ($result) {
    $row = $result->fetch_assoc();
    $archive_count = $row['count'];
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills Management Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dash.css">
 
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-file-invoice-dollar"></i>
                <h2>PayFlow</h2>
            </div>
            <div class="menu">
                <div class="menu-item active"><i class="fas fa-home"></i><span>Dashboard</span></div>
                <div class="menu-item"><i class="fas fa-file-invoice"></i><span>Bills</span></div>
                <div class="menu-item"><i class="fas fa-archive"></i><span>Archive</span></div>
                <div class="menu-item"><i class="fas fa-chart-pie"></i><span>Reports</span></div>
                <div class="menu-item"><i class="fas fa-cog"></i><span>Settings</span></div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="page-title">
                    <i class="fas fa-file-invoice"></i>
                    <h1>Bills</h1>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary" id="addBillBtn">
                        <i class="fas fa-plus"></i> Add Bill
                    </button>
                </div>
            </div>
           
            <div class="tabs">
                <div class="tab active" data-tab="active">Active Bills</div>
                <div class="tab" data-tab="archived">
                    Archive <span class="tab-badge" id="archiveCount"><?php echo $archive_count; ?></span>
                </div>
            </div>
            
            <!-- Active Bills Container -->
            <div class="bills-container" id="activeBillsContainer">
                <table>
                    <thead>
                        <tr>
                            <th>B_ID</th>
                            <th>Code</th>
                            <th>Bname</th>
                            <th>Name</th>
                            <th>InvolvedP</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeBills">
                        <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><?php echo $bill['b_id']; ?></td>
                            <td><?php echo $bill['b_code']; ?></td>
                            <td><?php echo $bill['b_BName']; ?></td>
                            <td><?php echo $bill['b_name']; ?></td>
                            <td><?php echo $bill['b_involvedP']; ?></td>
                            <td><?php echo $bill['b_date']; ?></td>
                            <td class="action-icons">
                                <i class="fas fa-eye viewBillBtn" data-id="<?php echo $bill['b_id']; ?>"></i>
                                <i class="fas fa-edit editBillBtn" data-id="<?php echo $bill['b_id']; ?>"></i>
                                <i class="fas fa-trash deleteBillBtn" data-id="<?php echo $bill['b_id']; ?>"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Archived Bills Container -->
            <div class="bills-container" id="archivedBillsContainer" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>B_ID</th>
                            <th>Code</th>
                            <th>Bname</th>
                            <th>Name</th>
                            <th>InvolvedP</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="archivedBills">
                        <!-- Archived bills will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
            
            <div class="empty-state" id="emptyState" style="display: <?php echo empty($bills) ? 'block' : 'none'; ?>;">
                <i class="fas fa-file-invoice"></i>
                <h3>No bills found</h3>
                <p>When you add bills, they'll appear here</p>
                <button class="btn btn-primary" id="addFirstBillBtn" style="margin-top: 15px;">
                    <i class="fas fa-plus"></i> Add Your First Bill
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for Adding and Editing Bills -->
    <div class="modal" id="billModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Bill</h3>
                <button class="close-modal">×</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="billId">
                <div class="form-group">
                    <label for="billCode">Bill Code</label>
                    <input type="text" id="billCode" class="form-control" placeholder="Will be auto-generated" readonly>
                </div>
                <div class="form-group">
                    <label for="billVendor">Bills Name</label>
                    <input type="text" id="billVendor" class="form-control" placeholder="Enter vendor name" required>
                </div>
                <div class="form-group">
                    <label for="billAmount">Name</label>
                    <input type="text" id="billAmount" class="form-control" placeholder="Enter name" required>
                </div>
                <div class="form-group">
                    <label for="billInvolvedP">Person Involved</label>
                    <input type="text" id="billInvolvedP" class="form-control" placeholder="Enter person involved" required>
                </div>
                <div class="form-group">
                    <label for="billDate">Date</label>
                    <input type="date" id="billDate" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline close-modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBillBtn">Save Bill</button>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Bills -->
    <div class="modal" id="viewBillModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>View Bill Details</h3>
                <button class="close-modal">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Bill Code</label>
                    <p id="viewBillCode"></p>
                </div>
                <div class="form-group">
                    <label>Bills Name</label>
                    <p id="viewBillVendor"></p>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <p id="viewBillAmount"></p>
                </div>
                <div class="form-group">
                    <label>Person Involved</label>
                    <p id="viewBillInvolvedP"></p>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <p id="viewBillDate"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline close-modal">Close</button>
            </div>
        </div>
    </div>

    <script>
        const billModal = document.getElementById('billModal');
        const viewBillModal = document.getElementById('viewBillModal');
        const billCodeInput = document.getElementById('billCode');
        const billVendorInput = document.getElementById('billVendor');
        const billAmountInput = document.getElementById('billAmount');
        const billInvolvedPInput = document.getElementById('billInvolvedP');
        const billDateInput = document.getElementById('billDate');
        const saveBillBtn = document.getElementById('saveBillBtn');
        const activeBillsTable = document.getElementById('activeBills');
        const emptyState = document.getElementById('emptyState');
        const archiveCount = document.getElementById('archiveCount');

        // Initialize archive count
        archiveCount.textContent = '<?php echo $archive_count; ?>';

        // Event listeners
        document.getElementById('addBillBtn').addEventListener('click', openAddBillModal);
        document.getElementById('addFirstBillBtn').addEventListener('click', openAddBillModal);
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', closeModal);
        });
        saveBillBtn.addEventListener('click', saveBill);

        // Tab switching logic
        const tabs = document.querySelectorAll('.tab');
        const activeBillsContainer = document.getElementById('activeBillsContainer');
        const archivedBillsContainer = document.getElementById('archivedBillsContainer');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const tabType = this.getAttribute('data-tab');
                if (tabType === 'active') {
                    activeBillsContainer.style.display = 'block';
                    archivedBillsContainer.style.display = 'none';
                    emptyState.style.display = activeBillsTable.querySelectorAll('tr').length === 0 ? 'block' : 'none';
                } else if (tabType === 'archived') {
                    activeBillsContainer.style.display = 'none';
                    archivedBillsContainer.style.display = 'block';
                    emptyState.style.display = 'none';
                    loadArchivedBills();
                }
            });
        });

        // Function to load archived bills
        function loadArchivedBills() {
            fetch('?action=get_archived_bills')
                .then(response => response.json())
                .then(data => {
                    const archivedBillsTable = document.getElementById('archivedBills');
                    archivedBillsTable.innerHTML = '';

                    if (data.status === 'success' && data.bills.length > 0) {
                        data.bills.forEach(bill => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${bill.b_id}</td>
                                <td>${bill.b_code}</td>
                                <td>${bill.b_BName}</td>
                                <td>${bill.b_name}</td>
                                <td>${bill.b_involvedP}</td>
                                <td>${bill.b_date}</td>
                            `;
                            archivedBillsTable.appendChild(row);
                        });
                    } else {
                        archivedBillsTable.innerHTML = '<tr><td colspan="6">No archived bills found</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching archived bills:', error);
                    document.getElementById('archivedBills').innerHTML = '<tr><td colspan="6">Error loading archived bills</td></tr>';
                });
        }

        function openAddBillModal() {
            document.getElementById('modalTitle').innerText = "Add New Bill";
            document.getElementById('billId').value = '';
            billCodeInput.value = generateBillCode();
            billVendorInput.value = '';
            billAmountInput.value = '';
            billInvolvedPInput.value = '';
            billDateInput.value = '';
            billModal.style.display = 'flex';
        }

        function closeModal() {
            billModal.style.display = 'none';
            viewBillModal.style.display = 'none';
        }

        function saveBill() {
            const b_id = document.getElementById('billId').value;
            const b_code = billCodeInput.value;
            const b_BName = billVendorInput.value;
            const b_name = billAmountInput.value;
            const b_involvedP = billInvolvedPInput.value;
            const b_date = billDateInput.value;

            if (!b_BName || !b_name || !b_involvedP || !b_date) {
                alert('Please fill in all required fields');
                return;
            }

            const formData = new FormData();
            formData.append('action', b_id ? 'update' : 'add');
            formData.append('b_code', b_code);
            formData.append('b_BName', b_BName);
            formData.append('b_name', b_name);
            formData.append('b_involvedP', b_involvedP);
            formData.append('b_date', b_date);
            
            if (b_id) {
                formData.append('b_id', b_id);
            }

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (b_id) {
                        updateBillInTable({
                            b_id,
                            b_code,
                            b_BName,
                            b_name,
                            b_involvedP,
                            b_date
                        });
                    } else {
                        addBillToTable({
                            b_id: data.b_id,
                            b_code,
                            b_BName,
                            b_name,
                            b_involvedP,
                            b_date
                        });
                    }
                    closeModal();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function addBillToTable(bill) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${bill.b_id}</td>
                <td>${bill.b_code}</td>
                <td>${bill.b_BName}</td>
                <td>${bill.b_name}</td>
                <td>${bill.b_involvedP}</td>
                <td>${bill.b_date}</td>
                <td class="action-icons">
                    <i class="fas fa-eye viewBillBtn" data-id="${bill.b_id}"></i>
                    <i class="fas fa-edit editBillBtn" data-id="${bill.b_id}"></i>
                    <i class="fas fa-trash deleteBillBtn" data-id="${bill.b_id}"></i>
                </td>
            `;
            activeBillsTable.appendChild(row);
            emptyState.style.display = 'none';
        }

        function updateBillInTable(bill) {
            const rows = document.querySelectorAll('#activeBills tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells[0].textContent == bill.b_id) {
                    cells[1].textContent = bill.b_code;
                    cells[2].textContent = bill.b_BName;
                    cells[3].textContent = bill.b_name;
                    cells[4].textContent = bill.b_involvedP;
                    cells[5].textContent = bill.b_date;
                }
            });
        }

        function generateBillCode() {
            const prefix = "BILL";
            const randomNum = Math.floor(1000 + Math.random() * 9000);
            const year = new Date().getFullYear().toString().slice(-2);
            return `${prefix}-${year}-${randomNum}`;
        }

        function openEditBillModal(billId) {
            fetch(`?action=get_bill&b_id=${billId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const bill = data.bill;
                        document.getElementById('billId').value = bill.b_id;
                        billCodeInput.value = bill.b_code;
                        billVendorInput.value = bill.b_BName;
                        billAmountInput.value = bill.b_name;
                        billInvolvedPInput.value = bill.b_involvedP;
                        billDateInput.value = bill.b_date;

                        document.getElementById('modalTitle').innerText = "Edit Bill";
                        billModal.style.display = 'flex';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error fetching bill details:', error));
        }

        function openViewBillModal(billId) {
            fetch(`?action=get_bill&b_id=${billId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const bill = data.bill;
                        document.getElementById('viewBillCode').textContent = bill.b_code;
                        document.getElementById('viewBillVendor').textContent = bill.b_BName;
                        document.getElementById('viewBillAmount').textContent = bill.b_name;
                        document.getElementById('viewBillInvolvedP').textContent = bill.b_involvedP;
                        document.getElementById('viewBillDate').textContent = bill.b_date;

                        viewBillModal.style.display = 'flex';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error fetching bill details:', error));
        }

        function archiveBill(billId) {
            if (confirm('Are you sure you want to archive this bill?')) {
                const formData = new FormData();
                formData.append('action', 'archive');
                formData.append('b_id', billId);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const rows = document.querySelectorAll('#activeBills tr');
                        rows.forEach(row => {
                            const firstCell = row.querySelector('td');
                            if (firstCell && firstCell.textContent == billId) {
                                row.remove();
                                archiveCount.textContent = data.archiveCount;
                                if (activeBillsTable.querySelectorAll('tr').length === 0) {
                                    emptyState.style.display = 'block';
                                }
                            }
                        });
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Event delegation for edit, delete, and view buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('editBillBtn')) {
                const billId = e.target.getAttribute('data-id');
                openEditBillModal(billId);
            } else if (e.target.classList.contains('deleteBillBtn')) {
                const billId = e.target.getAttribute('data-id');
                archiveBill(billId);
            } else if (e.target.classList.contains('viewBillBtn')) {
                const billId = e.target.getAttribute('data-id');
                openViewBillModal(billId);
            }
        });

        // Check if table is empty on load
        document.addEventListener('DOMContentLoaded', function() {
            if (activeBillsTable.querySelectorAll('tr').length === 0) {
                emptyState.style.display = 'block';
            }
        });
    </script>
</body>
</html>