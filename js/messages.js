var msg = {
    // Ajax Fetch
    ajax : (data, after) => {
      // Form Data
      let form = new FormData();
      for (const [k,v] of Object.entries(data)) { form.append(k, v); }
  
      // Fetch
      fetch("messages-ajax.php", { method:"POST", body:form })
      .then(res => res.text())
      .then(txt => after(txt))
      .catch(err => console.error(err));
    },
  
    // Show Messages
    uid : null,  // current selected user
    show : uid => {
      // Set Selected User ID
      msg.uid = uid;
  
      // Get HTML Elements
      let hForm = document.getElementById("uSend"),
          hTxt = document.getElementById("mTxt"),
          hUnread = document.querySelector(`#usr${uid} .uUnread`),
          hMsg = document.getElementById("uMsg");
  
      // Set Selected User
      for (let r of document.querySelectorAll(".uRow")) {
        if (r.id=="usr"+uid) { r.classList.add("now"); }
        else { r.classList.remove("now"); }
      }
  
      // Show Message Form
      hForm.style.display = "flex";
      hTxt.value = "";
      hTxt.focus();
  
      // Ajax Load Messages
      hMsg.innerHTML = "";
      msg.ajax({
        req : "show",
        uid : uid
      }, txt => {
        hMsg.innerHTML = txt;
        hUnread.innerHTML = 0;
      });
    },
    
    // Send Message
    send : () => {
      let hTxt = document.getElementById("mTxt");
      msg.ajax({
        req : "send",
        to : msg.uid,
        msg : hTxt.value
      }, txt => {
        if (txt == "OK") {
          msg.show(msg.uid);
          hTxt.value = "";
        } else { alert(txt); }
      });
      return false;
    }
  };