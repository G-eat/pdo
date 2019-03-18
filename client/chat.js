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

  let user  = document.querySelector('#user_id').value;
  let you  = document.querySelector('#your_id').value;
  let messageSeen = setInterval(function(){messagesSeen(you,user)}, 100);

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
        let data = new Date(message.created_at);

        // let e = document.createElement('div');
        if (message.user_id == you) {
          output += "<div class='message-wrapper blockquote-blue'>";
          output += `<span style='overflow-wrap: break-word'>${message.message}</span>`;
          output += `<div class='right grey-text' style='font-size:0.8rem'>${data.toLocaleTimeString()}</div>`;
          output += '</div>';
          // e.className='message-wrapper blockquote-blue';
        }else {
          output += "<div class='message-wrapper blockquote right-align'>";
          output += `<span style='overflow-wrap: break-word'>${message.message}</span>`;
          output += `<div class='left grey-text' style='font-size:0.8rem'>${data.toLocaleTimeString()}</div>`;
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


function messagesSeen(you,user) {
  let xmlHttp = new XMLHttpRequest();

  xmlHttp.open("post", "messagesSeen.php");
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.send('user_id=' + user+'& your_id=' + you);
}
