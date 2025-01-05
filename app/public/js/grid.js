
// variable to store data
let formData = {};


function Next(){
    console.log("here")
    // storing form data 
    const inputs = document.querySelectorAll("#formContent input, #formContent select, #formContent textarea");
    inputs.forEach(input => {
        formData[input.name] = input.value;
    });

    // Replace form content 
    const formContent = document.getElementById("formContent");
    formContent.innerHTML = `
        <label for="difficulty">Difficulty:</label><br>
        <select id="difficulty" name="difficulty" required>
            <option value="debutant">Beginner</option>
            <option value="intermediaire">Intermediate</option>
            <option value="expert">Expert</option>
        </select><br><br>

        <label for="black_cells">Black Cells (JSON):</label><br>
        <textarea id="black_cells" name="black_cells" required></textarea><br><br>

        <label for="horizontal_clues">Horizontal Clues (JSON):</label><br>
        <textarea id="horizontal_clues" name="horizontal_clues" required></textarea><br><br>

        <label for="vertical_clues">Vertical Clues (JSON):</label><br>
        <textarea id="vertical_clues" name="vertical_clues" required></textarea><br><br>
        
        
        <label for="solution">Solution:</label><br>
        <textarea id="solution" name="solution" required></textarea><br><br>

        <button type="button" onclick="SubmitForm()">Soumettre</button>
    `;
}


function SubmitForm() {
    const formContent = document.getElementById("formContent");
    console.log('saluttt2');
    console.log(formData);
    // Save the last step's data
    const inputs = formContent.querySelectorAll("input, select, textarea");
    inputs.forEach(input => {
        formData[input.name] = input.value;
    });

    console.log('bec');

    console.log(formData);

    // Send data to PHP
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.text())
    .then(result => {
        alert(result); // Show the result from the PHP script
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while submitting the form.");
    });
}