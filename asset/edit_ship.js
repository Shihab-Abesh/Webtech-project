function confirmUpdate(){
      return confirm("Are you sure you want to update this ship?");
    }

    function filterShips(){
      var onlyActive = document.getElementById("onlyActive").value; 
      var rows = document.querySelectorAll("#shipsTable tbody tr");

      for(var i = 0; i < rows.length; i++) {
        var activeVal = rows[i].getAttribute("data-active"); 
        var show = true;

        if(onlyActive === "active") show = (activeVal === "1");
        if(onlyActive === "inactive") show = (activeVal === "0");

        rows[i].style.display = show ? "" : "none";
      }
    }
