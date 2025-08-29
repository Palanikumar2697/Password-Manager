<?php
session_start();
include ('./conn/conn.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user's name from the database
    $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $user_name = $row['name'];
    }
}

include ('./partials/header.php');
include ('./partials/modal.php');
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <a class="navbar-brand ml-4" href="home.php">Password Manager App</a>
    <div class="form-inline my-2 my-lg-0 ml-auto">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle link-unstyled" style="text-decoration: none; color: #eee;" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                <?php
                if (isset($user_name)) {
                    echo "Welcome, $user_name";
                } else {
                    echo "My Account";
                }
                ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <!-- View Account -->
                <a class="dropdown-item" style="cursor: pointer;" id="viewAccount" onclick="view_user(<?php echo $user_id ?>)">
                    <i class="fa-regular fa-user"></i> View Account
                </a>
                <div class="dropdown-divider"></div>
                <!-- Log Out -->
                <a class="dropdown-item" href="index.php">
                    <i class="fa-solid fa-lock"></i> Log Out
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="main">
    <div class="accounts-container">
        <h4 class="text-center"><strong><?php echo $user_name; ?>'s Accounts</strong></h4>
        <!-- Add Account Button -->
        <button type="button" class="btn btn-dark mb-3 float-right" data-toggle="modal" data-target="#addAccountModal">
            Add Account
        </button>

        <!-- All Accounts Table -->
        <div class="table-responsive">
            <table id="accountsTable" class="table table-hover task-list" >
                <thead class="text-center">
                    <tr class="center1">
                        <th scope="col">ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Account Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                        <th style="display:none;"></th>
                        <th scope="col">URL</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php 
                    $stmt = $conn->prepare("SELECT * FROM `tbl_accounts`");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    foreach ($result as $row) {
                        $accountID = $row['tbl_account_id'];
                         $created_at=$row['created_at'];
                        $accountName = $row['account_name'];
                        $username = $row['username'];
                        $password = $row['password'];
                        $link = $row['link'];
                        $description = $row['description'];
                    ?>
                    <tr class="text-center1">
                        <td id="accountID-<?= $accountID ?>"><?php echo $accountID ?></td>
                         <td id="created_at-<?= $created_at ?>"><?php echo $created_at ?></td>
                        <td id="accountName-<?= $accountID ?>"><?php echo $accountName ?></td>
                        <td id="username-<?= $accountID ?>"><?php echo $username ?></td>
                        <td id="password-<?= $accountID ?>" style="display:none;"><?php echo $password ?></td>
                        <td><input class="password-input" type="password" value="<?php echo $password ?>" onclick="togglePasswordVisibility(<?php echo $accountID ?>)" id="password-input-<?= $accountID ?>" readonly></td>
                        <td id="link-<?= $accountID ?>"><a href="<?php echo $link ?>" target="_blank"><?php echo $link ?></a></td>
                        <td id="description-<?= $accountID ?>"><?php echo $description ?></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-primary px-3 py-1 rounded-pill" 
                                    id="editBtn" onclick="update_account(<?php echo $accountID ?>)" title="Edit">
                                    <i class="fa-solid fa-pencil fa-sm"></i> <span>Edit</span>
                                </button>
                                <!-- Delete Button -->
                                <button class="btn btn-sm btn-danger px-3 py-1 rounded-pill ml-1" 
                                    id="deleteBtn" onclick="delete_account(<?php echo $accountID ?>)" title="Delete">
                                    <i class="fa-solid fa-trash fa-sm"></i> <span>Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<?php include('./partials/footer.php') ?>
