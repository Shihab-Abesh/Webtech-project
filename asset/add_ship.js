function validateShipForm(){
   var name = document.getElementById("ship_name").value.trim();
   var cap  = document.getElementById("capacity").value.trim();
     var route= document.getElementById("route").value.trim();
   var fee  = document.getElementById("fee").value.trim();

    if(name === "" || cap === "" || route === "" || fee === "")
      {
      alert("Please fill in all fields.");
      return false;
      }
    if(isNaN(cap) || parseFloat(cap) <= 0)
      {
        alert("Capacity must be a number greater than 0.");
        return false;
      }
    if(isNaN(fee) || parseFloat(fee) <= 0)
      {
       alert("Fee must be a number greater than 0.");
        return false;
      }

      return true;
    
    }
