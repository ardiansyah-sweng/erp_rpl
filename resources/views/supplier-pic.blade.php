<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier PIC</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h2>Form Supplier PIC</h2>
    <form id="supplierForm">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name">
        <span class="error-message" id="error-name"></span>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <span class="error-message" id="error-email"></span>

        <label for="phone">Nomor Telepon:</label>
        <input type="text" id="phone" name="phone">
        <span class="error-message" id="error-phone"></span>

        <button type="submit">Submit</button>
    </form>

    <script>
        document.getElementById("supplierForm").addEventListener("submit", function(event) {
            let isValid = true;

            let name = document.getElementById("name").value;
            let email = document.getElementById("email").value;
            let phone = document.getElementById("phone").value;

            document.getElementById("error-name").innerText = "";
            document.getElementById("error-email").innerText = "";
            document.getElementById("error-phone").innerText = "";

            if (name.trim() === "") {
                document.getElementById("error-name").innerText = "Nama wajib diisi!";
                isValid = false;
            }
            if (email.trim() === "" || !email.includes("@")) {
                document.getElementById("error-email").innerText = "Email tidak valid!";
                isValid = false;
            }
            if (phone.trim() === "" || isNaN(phone)) {
                document.getElementById("error-phone").innerText = "Nomor telepon tidak valid!";
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
