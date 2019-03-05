function get_message() {
  let input  = document.querySelector('#input').value;
  let user_id  = document.querySelector('#user_id').value;
  let you  = document.querySelector('#your_id').value;

  if (input.trim() !== '') {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function()
    {
      if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
      {
        // alert(xmlHttp.response);
        data = JSON.parse(xmlHttp.response);
        document.getElementById("input").value='';
        let element = document.getElementById('content');

        let e = document.createElement('div');
        e.className='message-wrapper blockquote right-align';

        e.innerHTML = data.message;

        element.appendChild(e);
      }
    }
    xmlHttp.open("post", "messages.php");
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.send('message='+ input+'& user_id=' + user_id+'& your_id=' + you);
  }

}


document.addEventListener('DOMContentLoaded', function(){
  let getMessage = setInterval(getAllMessages, 100);
  // alert(get_message());
  getAllMessages();
}, false);


function getAllMessages() {
  let user_id  = document.querySelector('#user_id').value;
  let you  = document.querySelector('#your_id').value;

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
    {
      // alert(xmlHttp.response);
      data = JSON.parse(xmlHttp.response);
      let output = "";
      let element = document.getElementById('content');
      data.forEach((message)=>{
        // document.getElementById("input").value='';


        // let e = document.createElement('div');
        if (message.user_id == you) {
          output += "<div class='message-wrapper blockquote-blue'>";
          output += message.message;
          output += '</div>';
          // e.className='message-wrapper blockquote-blue';
        }else {
          output += "<div class='message-wrapper blockquote right-align'>";
          output += message.message;
          output += '</div>';
          // e.className='message-wrapper blockquote';
        }
        // e.innerHTML = message.message;

        // element.appendChild(e);
      });

      element.innerHTML = output;
      let content = document.getElementById('content');
      let inner = document.getElementById('inner');
      inner.scrollTop = content.scrollHeight;
    }
  }
  xmlHttp.open("post", "allmessages.php");
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send('user_id=' + user_id+'& your_id=' + you);
}
