var modal = document.getElementById("myModal");
var btn = document.getElementById("addRiceBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
document.getElementById("cancelButton").addEventListener("click", function() {

    document.getElementById("riceForm").reset();
});

document.getElementById("saveButton").addEventListener("click", function(event) {
    var name = document.getElementsByName("name")[0].value;
    var quantity = document.getElementsByName("quantity")[0].value;
    var kilograms = document.getElementsByName("kilograms")[0].value;
    var price = document.getElementsByName("price")[0].value;
    var expirationDate = document.getElementsByName("expiration_date")[0].value;

    if (name === "" || quantity === "" || kilograms === "" || price === "" || expirationDate === "") {
        alert("Please fill in all fields.");
        event.preventDefault(); 
    } else {
    }
});
