"<?php
// Database connection details
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "cart";   

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding items to the cart
if (isset($_POST['item_name'], $_POST['item_image'], $_POST['item_price'], $_POST['quantity'])) {
    $item_name = $_POST['item_name'];
    $item_image = $_POST['item_image'];
    $item_price = $_POST['item_price'];
    $quantity = $_POST['quantity'];

 // Input validation

    // Prepare SQL query to insert the item into the database
    $stmt = $conn->prepare("INSERT INTO cart (item_name, item_image, item_price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $item_name, $item_image, $item_price, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Item added to cart successfully!');</script>";
    } else {
        echo "<script>alert('Error adding item to cart: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

    if (isset($_POST['delete_item_id'])) {
        $delete_item_id = $_POST['delete_item_id'];
    
        // Prepare SQL query to delete the item from the database
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $delete_item_id);
    
        if ($stmt->execute()) {
            echo "<script>alert('Item deleted from cart successfully!');</script>";
        } else {
            echo "<script>alert('Error deleting item from cart: " . $stmt->error . "');</script>";
        }

    $stmt->close();
}


// Retrieve items from the cart
$sql = "SELECT * FROM cart";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Add your styles here */
        .cart-container { width: 80%; margin: 0 auto; }
        .cart-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; display: flex; gap: 20px; }
        .cart-item img { width: 100px; height: 100px; object-fit: cover; }
        .cart-details { flex: 1; }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 14px;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<header>
    <h1>Cart</h1>
    <nav>
        <ul>
            <li><a href="shop.html"><i class="fas fa-shopping-cart"></i> Shop More</a></li>
        </ul>
    </nav>
</header>

<div class="cart-container">
    <h2 style=" font-size: 3em;
            text-shadow: 4px 4px 4px rgba(37, 9, 9, 0.5); ">Your Cart</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='cart-item'>";
            echo "<img src='" . htmlspecialchars($row["item_image"]) . "' alt='" . htmlspecialchars($row["item_name"]) . "'>";
            echo "<div class='cart-details'>";
            echo "<h3>" . htmlspecialchars($row["item_name"]) . "</h3>";
            echo "<p>Price: " . htmlspecialchars($row["item_price"]) . " ETB</p>";
            echo "<p>Quantity: " . htmlspecialchars($row["quantity"]) . "</p>";
            echo "<form action='' method='POST' style='display:inline;'>
                  <input type='hidden' name='delete_item_id' value='" . $row["id"] . "' />
                  <button type='submit' class='delete-btn'>Delete</button>
                  </form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>
</div>

</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
