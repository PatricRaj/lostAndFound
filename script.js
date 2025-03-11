document.getElementById('reportForm').addEventListener('submit', function(event) {
    const itemName = document.getElementById('itemName').value.trim();
    const itemDescription = document.getElementById('itemDescription').value.trim();

    if (!itemName || !itemDescription) {
        alert("Please fill out all fields.");
        event.preventDefault();
    }
});
