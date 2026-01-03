function showFields(){
      var bk = document.getElementById("bkash").checked;
      var mc = document.getElementById("mastercard").checked;

      document.getElementById("bkashFields").style.display = bk ? "block" : "none";
      document.getElementById("mcFields").style.display = mc ? "block" : "none";
    }

    function validMobile(m){
      if(m.length != 11) return false;
      for(var i=0;i<m.length;i++){
        if(m[i] < '0' || m[i] > '9') return false;
      }
      if(m[0] != '0' || m[1] != '1') return false;
      return true;
    }

    function validCard(c){
      if(c.length < 13 || c.length > 19) return false;
      for(var i=0;i<c.length;i++){
        if(c[i] < '0' || c[i] > '9') return false;
      }
      return true;
    }

    function validateForm(){
      var amt = document.getElementById("amount").value.trim();
      if(amt == ""){
        alert("Please enter an amount.");
        return false;
      }
      if(isNaN(amt) || parseFloat(amt) <= 0){
        alert("Amount must be a number greater than 0.");
        return false;
      }

      var bk = document.getElementById("bkash").checked;
      var mc = document.getElementById("mastercard").checked;

      if(!bk && !mc){
        alert("Please select a withdrawal method.");
        return false;
      }

      if(bk){
        var m = document.getElementById("bkash_mobile").value.trim();
        if(m == ""){
          alert("Please enter bKash mobile number.");
          return false;
        }
        if(!validMobile(m)){
          alert("Warning: Wrong mobile number. Use 11 digits, starts with 01.");
          return false;
        }
      }

      if(mc){
        var owner = document.getElementById("card_owner").value.trim();
        var card  = document.getElementById("card_no").value.trim();

        if(owner == ""){
          alert("Please enter card owner name.");
          return false;
        }
        if(card == ""){
          alert("Please enter card number.");
          return false;
        }
        if(!validCard(card)){
          alert("Warning: Wrong card number. Use only digits and 13 to 19 digits.");
          return false;
        }
      }

      return confirm("Are you sure you want to submit this withdrawal?");
    }

    window.onload = showFields;
