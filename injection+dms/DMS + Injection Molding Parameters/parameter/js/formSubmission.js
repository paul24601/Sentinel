// JavaScript to handle form submission and file previews

document.addEventListener("DOMContentLoaded", () => {
    // Handle form submission
    document.getElementById("parameterForm").addEventListener("submit", async (event) => {
        event.preventDefault(); // Prevent form from reloading the page

        // Gather form data
        const formData = new FormData(event.target);

        try {
            // Send data to the backend API
            const response = await fetch("http://localhost:5000/api/forms/submit", {
                method: "POST",
                body: formData,
            });

            const result = await response.json();
            displayResponseMessage(response.ok, result.message || result.error);
        } catch (error) {
            console.error("Error:", error);
            displayResponseMessage(false, "Failed to submit form.");
        }
    });

    // Handle image preview
    document.getElementById("uploadImage").addEventListener("change", (event) => {
        const file = event.target.files[0];
        const imagePreview = document.getElementById("imagePreview");

        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle video preview
    document.getElementById("uploadVideo").addEventListener("change", (event) => {
        const file = event.target.files[0];
        const videoSource = document.getElementById("videoSource");
        const videoPreview = document.getElementById("videoPreview");

        if (file) {
            const url = URL.createObjectURL(file);
            videoSource.src = url;
            videoPreview.load();
            videoPreview.style.display = "block";
        }
    });
});

// Function to display response messages
function displayResponseMessage(success, message) {
    const messageBox = document.getElementById("responseMessage");
    messageBox.textContent = message;
    messageBox.className = `alert ${success ? "alert-success" : "alert-danger"}`;
    messageBox.style.display = "block";
}
