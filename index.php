<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>OTP Verification</h2>
        <div id="errorMessage" class="alert alert-danger d-none"></div>
        <div id="successMessage" class="alert alert-success d-none"></div>
        
        <form id="otpForm">
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number (2547... format)</label>
                <input type="text" class="form-control" id="phoneNumber" placeholder="Enter phone number" required>
            </div>
            <button type="submit" class="btn btn-primary">Send OTP</button>
        </form>
    </div>

    <script>
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            const phoneNumber = document.getElementById('phoneNumber').value.trim();
            document.getElementById('successMessage').classList.add('d-none');
            document.getElementById('errorMessage').classList.add('d-none');

            // Log the phone number before sending it to the backend
            console.log("Phone number before sending to backend:", phoneNumber);

            // Validate phone number format: it must start with 254 followed by 9 digits
            const phoneRegex = /^254\d{9}$/;
            if (!phoneRegex.test(phoneNumber)) {
                document.getElementById('errorMessage').textContent = 'Invalid phone number format. Please use the correct format (e.g., 254798869590).';
                document.getElementById('errorMessage').classList.remove('d-none');
                return;
            }


            let formData=new FormData();
            formData.append("mobile",phoneNumber);

            // Make AJAX request to send OTP
            axios.post('sendOtp.php', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function (response) {
                const data = response.data;
                console.log("Response from backend:", data); // Log the response from the server to the console

                if (data.status === 'success') {
                    document.getElementById('successMessage').textContent = data.message;
                    document.getElementById('successMessage').classList.remove('d-none');
                } else {
                    document.getElementById('errorMessage').textContent = data.message;
                    document.getElementById('errorMessage').classList.remove('d-none');
                }
            })
            .catch(function (error) {
                console.log(error); // Log any errors to the console
                document.getElementById('errorMessage').textContent = 'An error occurred. Please try again.';
                document.getElementById('errorMessage').classList.remove('d-none');
            });
        });
    </script>
</body>
</html>
