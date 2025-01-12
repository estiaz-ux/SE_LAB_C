<?php
// Start of the PHP script: Check if the form is submitted with 'confirm' set
if (isset($_POST['confirm'])) {
    include 'dbCon.php'; // Include the database connection script
    $con = connect();    // Establish the connection using the connect() function
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Booking Confirmation</title>
    <meta charset="utf-8">
    <!-- Importing necessary meta tags for responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include external stylesheets -->
  </head>
  <body>
    <!-- Display the banner section -->
    <section class="hero-wrap" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text align-items-center justify-content-center">
                <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="index.php">Home</a></span>
                        <span>Booking</span>
                    </p>
                    <h1 class="mb-3">Booking</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking confirmation section -->
    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Booking</span>
                    <h2>Confirm Your Booking</h2>
                </div>
            </div>

            <div class="row block-9 mb-4">
                <!-- Contact Information Section -->
                <div class="col-md-6 pr-md-5 flex-column">
                    <div class="row d-block flex-row">
                        <h2 class="h4 mb-4">Contact Information</h2>
                        <div class="col mb-3 d-flex py-4 border" style="background: white;">
                            <div class="align-self-center">
                                <!-- Dynamically display the reservation details -->
                                <p class="mb-0"><span>Name:</span> <a href=""><?php echo $reservation_name; ?></a></p>
                                <p class="mb-0"><span>Phone:</span> <a href="tel://1234567920"><?php echo $reservation_phone; ?></a></p>
                                <p class="mb-0"><span>Reservation Date:</span> <a href=""><?php echo $reservation_date; ?></a></p>
                                <p class="mb-0"><span>Reservation Time:</span> <a href=""><?php echo $reservation_time; ?></a></p>
                            </div>
                        </div>

                        <!-- Display selected tables -->
                        <div class="col mb-3 d-flex py-4 border" style="background: white;">
                            <div class="align-self-center">
                                <p class="mb-0"><span>Table No:</span>
                                    <?php 
                                    // Loop through selected tables and fetch their details from the database
                                    for ($p = 0; $p < count($_POST["table"]); $p++) {
                                        $t_id = $_POST['table'][$p];  
                                        $sql4 = "SELECT * FROM `restaurant_tables` WHERE id = '$t_id';";
                                        $result4 = $con->query($sql4);
                                        foreach ($result4 as $r4) {
                                    ?>  
                                    <a style="color: #FFB911;"><?php echo $r4['table_name']; ?></a>
                                    <?php } } ?>
                                </p>
                                <!-- Loop through chairs in the same manner -->
                                <p class="mb-0"><span>Chair No:</span>
                                    <?php 
                                    for ($q = 0; $q < count($_POST["chair"]); $q++) {
                                        $c_id = $_POST['chair'][$q];  
                                        $sql5 = "SELECT * FROM `restaurant_chair` WHERE id = '$c_id';";
                                        $result5 = $con->query($sql5);
                                        foreach ($result5 as $r5) {
                                    ?> 
                                    <a style="color: #FFB911;"><?php echo $r5['chair_no']; ?>,</a>
                                    <?php } } ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Items Section -->
                <div class="col-md-6 pr-md-5 flex-column">
                    <div class="row d-block flex-row">
                        <h2 class="h4 mb-4">Menu Item Information</h2>
                        <div class="col mb-3 d-flex py-4 border" style="background: white;">
                            <div class="align-self-center" style="width: 100%">
                                <!-- Display a table with selected menu items -->
                                <table style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Item Name</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Initialize total price
                                        $total_price = 0;
                                        for ($i = 0; $i < count($_POST["item"]); $i++) {
                                            $i_id = $_POST['item'][$i];
                                            $qty = $_POST["qty"][$i];

                                            // Fetch item details from the database
                                            $Itmsql = "SELECT * FROM `menu_item` WHERE id = '$i_id';";
                                            $Itmresult = $con->query($Itmsql);
                                            foreach ($Itmresult as $itmr) {
                                                $total_price += ($qty * $itmr['price']);
                                        ?> 
                                        <tr>
                                            <td><img style="height: 40px;width: 40px;" src="dashboard/item-image/<?php echo $itmr['image']; ?>"></td>
                                            <td><?php echo $itmr['item_name']; ?></td>
                                            <td><?php echo $itmr['price']; ?></td>
                                            <td><?php echo $qty; ?></td>
                                            <td><?php echo $qty * $itmr['price']; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total Price Section -->
                        <div class="col mb-3 d-flex py-4 border" style="background: white;">
                            <div class="align-self-center">
                                <p class="mb-0"><span>Total Price:</span> <a href=""><?php echo $total_price; ?> Tk</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form to confirm and finalize the booking -->
                <form action="manage-insert.php" method="POST">
                    <div class="col-lg-12" style="text-align: center;">
                        <div class="form-group">
                            <input type="text" name="transaction_id" class="form-control" placeholder="Transaction Id" required="">
                        </div>
                        <input type="hidden" name="res_id" value="<?php echo $res_id; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        <input type="hidden" name="reservation_name" value="<?php echo $reservation_name; ?>">
                        <input type="hidden" name="reservation_phone" value="<?php echo $reservation_phone; ?>">
                        <input type="hidden" name="reservation_date" value="<?php echo $reservation_date; ?>">
                        <input type="hidden" name="reservation_time" value="<?php echo $reservation_time; ?>">

                        <!-- Hidden inputs for tables, chairs, and items -->
                        <?php 
                        for ($r = 0; $r < count($_POST["table"]); $r++) {
                            $tbl_id = $_POST['table'][$r]; 
                        ?>
                        <input type="hidden" name="table[]" value="<?php echo $tbl_id; ?>">
                        <?php } 
                        for ($s = 0; $s < count($_POST["chair"]); $s++) { 
                            $chr_id = $_POST['chair'][$s]; 
                        ?>
                        <input type="hidden" name="chair[]" value="<?php echo $chr_id; ?>">
                        <?php } 
                        for ($t = 0; $t < count($_POST["item"]); $t++) { 
                            $i_id = $_POST['item'][$t];
                            $qty = $_POST['qty'][$t];
                        ?>
                        <input type="hidden" name="item[]" value="<?php echo $i_id; ?>">
                        <input type="hidden" name="qty[]" value="<?php echo $qty; ?>">
                        <?php } ?>
                        <input type="submit" value="Book" name="book" class="btn btn-primary py-3 px-5">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Include reusable components -->
    <?php include 'template/instagram.php'; ?>
    <?php include 'template/footer.php'; ?>
    <?php include 'template/script.php'; ?>
  </body>
</html>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    // Include the database connection
    include 'dbCon.php';
    $con = connect();

    // Extract submitted data
    $reservation_name = htmlspecialchars($_POST['reservation_name']);
    $reservation_phone = htmlspecialchars($_POST['reservation_phone']);
    $reservation_date = htmlspecialchars($_POST['reservation_date']);
    $reservation_time = htmlspecialchars($_POST['reservation_time']);
    $selected_tables = $_POST['table'] ?? [];
    $selected_chairs = $_POST['chair'] ?? [];
    $selected_items = $_POST['item'] ?? [];
    $quantities = $_POST['qty'] ?? [];
    $transaction_id = htmlspecialchars($_POST['transaction_id']);
    $total_price = 0;

    // Calculate the total price
    foreach ($selected_items as $key => $item_id) {
        $quantity = $quantities[$key];
        $item_query = $con->prepare("SELECT price FROM menu_item WHERE id = ?");
        $item_query->bind_param("i", $item_id);
        $item_query->execute();
        $item_result = $item_query->get_result()->fetch_assoc();
        $total_price += $item_result['price'] * $quantity;
    }

    // Save the booking in the database
    $insert_booking = $con->prepare("
        INSERT INTO reservations (name, phone, reservation_date, reservation_time, transaction_id, total_price) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $insert_booking->bind_param(
        "sssssi",
        $reservation_name,
        $reservation_phone,
        $reservation_date,
        $reservation_time,
        $transaction_id,
        $total_price
    );
    $insert_booking->execute();

    // Retrieve the last inserted reservation ID
    $reservation_id = $con->insert_id;

    // Save table and chair assignments
    foreach ($selected_tables as $table_id) {
        $con->query("INSERT INTO reservation_tables (reservation_id, table_id) VALUES ($reservation_id, $table_id)");
    }
    foreach ($selected_chairs as $chair_id) {
        $con->query("INSERT INTO reservation_chairs (reservation_id, chair_id) VALUES ($reservation_id, $chair_id)");
    }

    // Save ordered items
    foreach ($selected_items as $key => $item_id) {
        $quantity = $quantities[$key];
        $con->query("INSERT INTO reservation_items (reservation_id, item_id, quantity) VALUES ($reservation_id, $item_id, $quantity)");
    }

    // Close the database connection
    $con->close();
    echo "Booking confirmed successfully!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmation</h1>
    <form action="" method="POST">
        <label for="reservation_name">Name:</label>
        <input type="text" id="reservation_name" name="reservation_name" required><br><br>

        <label for="reservation_phone">Phone:</label>
        <input type="tel" id="reservation_phone" name="reservation_phone" required><br><br>

        <label for="reservation_date">Date:</label>
        <input type="date" id="reservation_date" name="reservation_date" required><br><br>

        <label for="reservation_time">Time:</label>
        <input type="time" id="reservation_time" name="reservation_time" required><br><br>

        <label for="transaction_id">Transaction ID:</label>
        <input type="text" id="transaction_id" name="transaction_id" required><br><br>

        <h3>Select Tables</h3>
        <input type="checkbox" name="table[]" value="1"> Table 1<br>
        <input type="checkbox" name="table[]" value="2"> Table 2<br>
        <input type="checkbox" name="table[]" value="3"> Table 3<br><br>

        <h3>Select Chairs</h3>
        <input type="checkbox" name="chair[]" value="1"> Chair 1<br>
        <input type="checkbox" name="chair[]" value="2"> Chair 2<br>
        <input type="checkbox" name="chair[]" value="3"> Chair 3<br><br>

        <h3>Order Menu Items</h3>
        <label for="item_1">Item 1 Quantity:</label>
        <input type="number" id="item_1" name="qty[]" value="0" min="0">
        <input type="hidden" name="item[]" value="1"><br>

        <label for="item_2">Item 2 Quantity:</label>
        <input type="number" id="item_2" name="qty[]" value="0" min="0">
        <input type="hidden" name="item[]" value="2"><br>

        <label for="item_3">Item 3 Quantity:</label>
        <input type="number" id="item_3" name="qty[]" value="0" min="0">
        <input type="hidden" name="item[]" value="3"><br><br>

        <button type="submit" name="confirm">Confirm Booking</button>
    </form>
</body>
</html>

