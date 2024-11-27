function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
function regformvalidation() {
    var reg_first_name = getId("reg_firstname").value;
    var reg_last_name = getId("reg_lastname").value;
    var reg_user_name = getId("reg_username").value;
    var reg_email = getId("reg_email").value;
    var reg_password = getId("reg_password").value;
    var reg_confirm_password = getId("reg_confirmpassword").value;
    if(
        reg_first_name.trim() == "" ||
        reg_last_name.trim() == "" ||
        reg_user_name.trim() == "" ||
        reg_email.trim() == "" ||
        reg_password.trim() == "" ||
        reg_confirm_password.trim() == ""
    ) {
        alert("Empty input field are not allowed.");
        return false;
    } else if (reg_password.length < 8 || reg_password.length > 100) {
        alert("Password should be 8 - 100 characters long.");
        return false;
    } else if (!isValidEmail(reg_email)) {
        alert("Invalid email address.");
        return false;
    } else if (!/[a-z]/.test(reg_password)) {
        alert("Password must contain atleast one lower case letter.");
        return false;
    }  else if (!/[A-Z]/.test(reg_password)) {
        alert("Password must contain atleast one upper case letter.");
        return false;
    } else if (!/[\d]/.test(reg_password)) {
        alert("Password must contain atleast one number.");
        return false;
    } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(reg_password)) {
        alert("Password must contain atleast one special character.");
        return false;
    } else if (reg_password !== reg_confirm_password) {
        alert("Password does not match.");
        return false;
    }
}

function logformvalidation() {
    var log_name = getId("log_name").value;
    var log_pass = getId("log_pass").value;
    if(log_name.trim() == "" || log_pass.trim() == "") {
        alert("Empty input field are not allowed.");
        return false;
    }
}