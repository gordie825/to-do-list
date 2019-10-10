
//Check password strength
let pass = document.getElementById("userpassword");
//Add event listener


function PasswordCheck(str){
    //password must have atleast 1 number, 1 //lowercase and 1 uppercase letter and //atleast 6 characters.
        let re =/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        return re.test(str);
}
    

pass.addEventListener("keyup",PasswordCheck);

//run code when user submits form
let form = document.getElementById("regform");


form.addEventListener("submit", function(){
    if(!PasswordCheck(form.password.value)){
        event.preventDefault();
        let pw_err = document.getElementById("password_error");
        pw_err.innerText = "Please enter a valid password.";
    }

    if(form.password.value != form.confirm_password.value){
        event.preventDefault();
        let con_pw_err = document.getElementById("confirm_error");
        con_pw_err.innerText = "Passwords do  not match.";
    }
    
});

