 function validateForm() {
    var email = document.getElementById("email").value;
    var password = document.getElementsByName("password")[0].value;
    var confirmPassword = document.getElementsByName("confirmPassword")[0].value;
    var phone = document.getElementsByName("num")[0].value;
    var dob = document.getElementsByName("dob")[0].value;
    var name = document.getElementsByName("name")[0].value;

    if (!email.includes("@") || !email.includes("."))
         {
        alert("Please enter a valid email format (example@gmail.com)");
        return false;
    }
    if (phone.length !== 11) 
        {
        alert("Phone number must be exactly 11 digits");
        return false;
    }
    if (password !== confirmPassword) 
        {
        alert("Passwords do not match");
        return false; 
    }
    if (dob === "") 
        {
        alert("Please select your date of birth");
        return false;
    }

    var birthDate = new Date(dob);
    var today = new Date();
    var age = today.getFullYear() - birthDate.getFullYear();

    if (age <= 20) 
        {
        alert("You must be older than 20 years");
        return false;
    }
     for (var i = 0; i < name.length; i++) 
        {
        var char = name[i];
        if (!((char >= 'A' && char <= 'Z') || (char >= 'a' && char <= 'z') || char === ' ')) {
            alert("Name should contain only letters");
            return false;
        }
    }
    return true; 
}
function checkEmailAjax(){
    let email = document.getElementById('email').value;

    if(email.trim() === ""){
        document.getElementById('emailMsg').innerHTML = "";
        return;
    }
    let xhttp = new XMLHttpRequest();
    xhttp.open('POST', 'checkEmail.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('email=' + encodeURIComponent(email));
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById('emailMsg').innerHTML = this.responseText;
        }
    }
}
