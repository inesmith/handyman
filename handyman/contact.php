<?php
// Include the config file to establish the connection
include('config.php');

// Initialize variables for form data and error messages
$name = $email = $phone = $service = $date = $message = "";
$nameErr = $emailErr = $phoneErr = $dateErr = "";
$successMessage = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean_input($_POST["name"]);
    $email = clean_input($_POST["email"]);
    $phone = clean_input($_POST["phone"]);
    $service = clean_input($_POST["service"]);
    $date = clean_input($_POST["date"]);
    $message = clean_input($_POST["message"]);

    // Validate input data
    if (empty($name)) {
        $nameErr = "Name is required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }
    if (empty($phone)) {
        $phoneErr = "Phone number is required";
    }
    if (empty($date)) {
        $dateErr = "Date and time are required";
    }

    // If no errors, insert booking into the database
    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($dateErr)) {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, service, date, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $service, $date, $message);

        // Execute the query and check if successful
        if ($stmt->execute()) {
            // Clear the form data
            $name = $email = $phone = $service = $date = $message = "";

            // Display success message
            $successMessage = "Booking successful! We will get back to you shortly.";

            // Use JavaScript to redirect to the homepage after a few seconds
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'homepage.html';
                    }, 3000); // 3 second delay before redirect
                  </script>";
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}

// Function to clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handyman Services - Contact Us</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

     <!-- Navigation Bar -->
     <nav class="navbar">
        <ul>
            <li><a href="homepage.html">Home</a></li>
            <li><a href="aboutpage.html">About Us</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="contact.php">Contact / Booking</a></li>
        </ul>
    </nav>

    <header>
        <h1>Get In Touch With Us</h1>
    </header>

    <section>
        <h2>Book a Service</h2>
        <!-- Display success message if booking was successful -->
        <?php if (!empty($successMessage)) : ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="name">Name & Surname:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <span class="error"><?php echo $nameErr; ?></span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <span class="error"><?php echo $emailErr; ?></span>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" required>
            <span class="error"><?php echo $phoneErr; ?></span>

            <label for="address">Address:</label>
            <input type="address" id="address" name="address" value="<?php echo $phone; ?>" required>
            <span class="error"><?php echo $phoneErr; ?></span>

            <label for="service">Service Needed:</label>
            <select id="service" name="service" required>
                <option value="electrical" <?php if ($service == "electrical") echo "selected"; ?>>Electrical</option>
                <option value="plumbing" <?php if ($service == "plumbing") echo "selected"; ?>>Plumbing</option>
                <option value="home_equipment" <?php if ($service == "home_equipment") echo "selected"; ?>>Home Equipment</option>
                <option value="general_maintenance" <?php if ($service == "general_maintenance") echo "selected"; ?>>General Maintenance</option>
            </select>

            <label for="date">Preferred Date & Time:</label>
            <input type="datetime-local" id="date" name="date" value="<?php echo $date; ?>" required>
            <span class="error"><?php echo $dateErr; ?></span>

            <label for="message">Message:</label>
            <textarea id="message" name="message"><?php echo $message; ?></textarea>

            <label for="message">Emergency Contact: 
            <p class="emergency">For urgent repairs, call us immediately at 0797272830.</p></label>
    
    

            <button type="submit" class="cta-button">Submit Booking</button>
            <button type="button" class="cancel-button" onclick="window.location.href='homepage.html'">Cancel</button>
        </form>
    </section>

        <!-- FAQ Section -->
    <section class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq">
            <h3>What areas do you service?</h3>
            <p>We provide handyman services in and around the local area, covering a radius of 50km from the city center.</p>
        </div>
        <div class="faq">
            <h3>How soon can a handyman be available?</h3>
            <p>For urgent repairs, we offer same-day services, depending on availability. For general maintenance tasks, we recommend booking at least 24 hours in advance.</p>
        </div>
        <div class="faq">
            <h3>What payment methods do you accept?</h3>
            <p>We accept all major credit/debit cards, bank transfers, and cash payments for on-site services.</p>
        </div>
        <div class="faq">
            <h3>Do you provide emergency services?</h3>
            <p>Yes, we provide emergency services for electrical and plumbing issues. You can contact us directly for immediate assistance.</p>
        </div>
        <div class="faq">
            <h3>Can I get a quote before booking?</h3>
            <p>Yes, you can request a free quote for any of our services. Just fill in the contact form or give us a call, and we'll provide a detailed estimate.</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-info">
        <h2>Contact Information</h2>
        <p><strong>Phone:</strong> <a href="tel:+1234567890">+27 79 727 2830</a></p>
        <p><strong>Email:</strong> <a href="mailto:info@handymanservices.com">info@handymanservices.com</a></p>
        <div class="social-media">
            <h3>Follow Us</h3>
            <a href="https://www.facebook.com/handymanservices" target="_blank">Facebook</a> |
            <a href="https://www.twitter.com/handymanservices" target="_blank">Twitter</a> |
            <a href="https://www.instagram.com/handymanservices" target="_blank">Instagram</a>
        </div>
    </section>
</body>
</html>
