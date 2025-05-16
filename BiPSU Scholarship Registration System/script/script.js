// Assuming you are using a database and fetching data via an API or AJAX
document.addEventListener("DOMContentLoaded", () => {
    // Function to fetch student name from the database
    async function fetchStudentName() {
        try {
            // Replace with your actual API endpoint
            const response = await fetch('/api/getStudentName');
            if (!response.ok) {
                throw new Error('Failed to fetch student name');
            }
            const data = await response.json();
            if (data && data.student_name) {
                // Replace the text in the h4 tag
                const h4Element = document.querySelector('h4');
                if (h4Element) {
                    h4Element.textContent = data.student_name;
                }
            }
        } catch (error) {
            console.error('Error fetching student name:', error);
        }
    }

    // Call the function to fetch and update the h4 tag
    fetchStudentName();
});