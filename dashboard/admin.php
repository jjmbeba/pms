<?php

require_once('../config/database.php');
require_once('../utils/functions.php');
require_once('../common/theme.php');

//Initialize variables
$successMessage = $statementErr  = '';

//Fetch all users in the database
$users = getUsers($conn);

//Fetch all parking lots
$lots = getAllLots($conn);

//Assign the number of users to a variable
$numberOfUsers = $users->num_rows;

//Assign the number of lots to a variable
$numberOfLots = $lots->num_rows;

$location = $_SERVER['PHP_SELF'];

if (isset($_GET['success'])) {
    $successMessage = $_GET['success'];
} elseif (isset($_GET['error'])) {
    $statementErr = $_GET['error'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<?php include 'header.php'; ?>

<body>
    <?php echo 'Welcome ' . $_SESSION['username']; ?>
    <div class="dashboard__container">
        <div class="sidebar">
            <h2>Reports</h2>
            <ul class="report__list">
                <li class="active" data-tab-target='#overview'>Overview</li>
                <li data-tab-target='#user'>Users</li>
                <li data-tab-target='#lots'>Parking lots</li>
            </ul>
        </div>
        <div class="report__area">
            <div id="overview" data-tab-content class="active overview">
                This is an overview :
                <div class="card__container">
                    <div class="card" id="userReportShortcut">
                        <span>Number of users : </span><br /> <span class="stat"><?php echo $numberOfUsers; ?></span>
                    </div>
                    <div class="card">
                        <span>Number of lots :</span><br /> <span class="stat"><?php echo $numberOfLots; ?></span>
                    </div>
                </div>
            </div>
            <div id="user" data-tab-content>
                <h1>User report</h1>
                <?php echo "<p style='margin-top:20px;'>Number of users: $numberOfUsers</p>"; ?>
                <table class="report__table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Account Type</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($users->num_rows > 0) {
                            while ($row = $users->fetch_assoc()) {
                                $userId = $row['id'];
                                $username = $row['username'];
                                $email = $row['email'];
                                $acType = $row['acType'];

                                //Display the data
                                echo "<tr>
                                <td>$userId</td>
                                <td>$username</td>
                                <td>$email</td>
                                <td>$acType</td>
                                <td>
                                    <button class='editUserBtn'>
                                        Edit
                                    </button>
                                    <dialog class='edit__modal editUserModal'>
                                    $username
                                    <div class='editForm__container'>
                                    <form action='../actions/editUser.php'>
                                    <img class='closeEditUserbtn' src='/pms/assets/closeDark.svg'/>
                                    <h1>Change user details</h1>
                                    <div class='input__container'>
                                        <select name='acType' class='input'>
                                            <option value='driver'>Driver</option>
                                            <option value='manager'>Parking Lot Manager</option>
                                            <option value='admin'>Administrator</option>
                                        </select>
                                        <label for='acType' class='input__label'>Account Type</label>
                                        <input type='hidden' name='id' value='$userId'/>
                                        <input type='hidden' name='redirect' value='$location'/>
                                    </div>
                                    <input class='submit__btn' type='submit' name='submit' value='Change user account'>
                                </form>
                                </div>
                                </dialog>
                                </td>
                                <td>
                                    <a class='delete__btn' href='../actions/deleteUser.php?id=$userId&redirect=$location'>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            
                            ";
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <div class="invalid" style="<?php echo $statementErr ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
                </div>
                <div class="success" style="<?php echo $successMessage ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/success.svg' alt='success'/>
                    $successMessage
                    </div>
                    "; ?>
                </div>
            </div>
            <div id="lots" data-tab-content>
                <h1>Parking lots report</h1>
                <?php echo "<p style='margin-top:20px;'>Number of lots: $numberOfLots</p>"; ?>
                <table class="report__table">
                    <?php
                    if ($lots->num_rows > 0) : ?>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Price</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $lots->fetch_assoc()) {
                                $fetchedId = $row['id'];
                                $fetchedName = $row['name'];
                                $fetchedLocation = $row['location'];
                                $fetchedCapacity = $row['capacity'];
                                $fetchedPrice = $row['price'];

                                //Display the data
                                echo "<tr>
                                <td>$fetchedId</td>
                                <td>$fetchedName</td>
                                <td>$fetchedLocation</td>
                                <td>$fetchedCapacity</td>
                                <td>$fetchedPrice</td>
                                <td>
                                    <button class='editLotBtn'>
                                        Edit
                                    </button>
                                    <dialog class='edit__modal editLotModal'>
                                    $fetchedId
                                    <div class='editForm__container'>
                                    <form method='POST' action='../actions/editLot.php'>
                                    <img class='closeEditLotbtn' src='/pms/assets/closeDark.svg'/>
                                    <h1>Change user details</h1>
                                    <div class='input__container'>
                                        <input name='name' value='$fetchedName' placeholder=' ' class='input'/>
                                        <label for='name' class='input__label'>Name</label>
                                    </div>
                                    <div class='input__container'>
                                        <select name='location' class='input'>
                                            <option value='nairobi'>Nairobi</option>
                                            <option value='kisumu'>Kisumu</option>
                                            <option value='mombasa'>Mombasa</option>
                                        </select>
                                        <label for='location' class='input__label'>Location</label>
                                    </div>
                                    <div class='input__container'>
                                        <input name='capacity' value='$fetchedCapacity' placeholder=' ' class='input'/>
                                        <label for='capacity' class='input__label'>Capacity</label>
                                    </div>
                                    <div class='input__container'>
                                        <input name='price' value='$fetchedPrice' placeholder=' ' class='input'/>
                                        <label for='price' class='input__label'>Price</label>
                                    </div>
                                    <input name='redirect' value='$location' type='hidden'/>
                                    <input name='id' value='$fetchedId' type='hidden'/>
                                    <input class='submit__btn' type='submit' name='submit' value='Change lot details'>
                                    </form>
                                    </div>
                                </dialog>
                                </td>
                                <td>
                                    <a class='delete__btn' href='../actions/deleteLot.php?id=$fetchedId&redirect=$location'>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            ";
                            } ?>
                        <?php endif ?>
                        </tbody>
                </table>
                <div class="invalid" style="<?php echo $statementErr ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
                </div>
                <div class="success" style="<?php echo $successMessage ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/success.svg' alt='success'/>
                    $successMessage
                    </div>
                    "; ?>
                </div>
                <dialog class='createLot__modal'>
                    <div class='editForm__container'>
                        <form method="POST" action='../actions/addLot.php'>
                            <img id='closeCreateModal__btn' src='/pms/assets/closeDark.svg' />
                            <h1>Register parking lot</h1>
                            <div class="input__container">
                                <input type="text" name="name" placeholder=" " class="input" value="<?php echo $name; ?>">
                                <label for="name" class="input__label">Name</label>
                                <div class="invalid" style="<?php echo $nameErr ? 'display:block;' : 'display:none;' ?>">
                                    <?php echo "
                                    <div style='display:flex; align-items:center; gap:5px;'>
                                    <img src='../assets/warning.svg' alt='warning'/>
                                    $nameErr
                                    </div>
                                    "; ?>
                                </div>
                            </div>
                            <div class="input__container">
                                <input type="number" name="capacity" placeholder=" " class="input" value="<?php echo $capacity; ?>">
                                <label for="capacity" class="input__label">Capacity</label>
                                <div class="invalid" style="<?php echo $capacityErr ? 'display:block;' : 'display:none;' ?>">
                                    <?php echo "
                                    <div style='display:flex; align-items:center; gap:5px;'>
                                    <img src='../assets/warning.svg' alt='warning'/>
                                    $capacityErr
                                    </div>
                                    "; ?>
                                </div>
                            </div>
                            <div class="input__container">
                                <input type="number" name="price" placeholder=" " class="input" value="<?php echo $price; ?>">
                                <label for="price" class="input__label">Price in Ksh.</label>
                                <div class="invalid" style="<?php echo $priceErr ? 'display:block;' : 'display:none;' ?>">
                                    <?php echo "
                                    <div style='display:flex; align-items:center; gap:5px;'>
                                    <img src='../assets/warning.svg' alt='warning'/>
                                    $priceErr
                                    </div>
                                    "; ?>
                                </div>
                            </div>
                            <div class='input__container'>
                                <select name='location' class='input'>
                                    <option value='nairobi'>Nairobi</option>
                                    <option value='kisumu'>Kisumu</option>
                                    <option value='mombasa'>Mombasa</option>
                                </select>
                                <label for='location' class='input__label'>Location</label>
                            </div>
                            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <input class='submit__btn' type='submit' name='submit' value='Create a lot'>
                        </form>
                    </div>
                </dialog>
            </div>
        </div>
    </div>
    </div>
    <script>
        const editUserBtns = document.querySelectorAll('.editUserBtn');
        const editLotBtns = document.querySelectorAll('.editLotBtn');
        const editUserModals = document.querySelectorAll('.editUserModal');
        const editLotModals = document.querySelectorAll('.editLotModal');
        const closeEditLotbtns = document.querySelectorAll('.closeEditLotbtn');
        const closeEditUserbtns = document.querySelectorAll('.closeEditUserbtn');

        editUserBtns.forEach((editUserBtn) => {
            editUserBtn.addEventListener('click', (e) => {
                const editUserDialog = e.target.nextElementSibling;
                editUserDialog.showModal();
            });
        });

        editLotBtns.forEach(editLotBtn => {
            editLotBtn.addEventListener('click', (e) => {
                const editLotDialog = e.target.nextElementSibling;
                editLotDialog.showModal();
            })
        })


        closeEditUserbtns.forEach((closeEditUserbtn) => {
            closeEditUserbtn.addEventListener('click', () => {
                editUserModals.forEach((modal) => {
                    modal.close();
                })
            })
        })

        closeEditLotbtns.forEach((closeEditLotbtn) => {
            closeEditLotbtn.addEventListener('click', () => {
                editLotModals.forEach((modal) => {
                    modal.close();
                })
            })
        })
    </script>
    <?php include 'footer.php'; ?>