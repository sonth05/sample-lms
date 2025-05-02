<?php
/*  Messenger‑like Chat Widget — v3
    ▸ Hiển thị ngay tin nhắn vừa gửi (optimistic update)
    ▸ Sau khi server phản hồi sẽ đồng bộ lại & xoá trạng thái "sending"
*/

$userName  = $_SESSION['username'] ?? 'guest';
$courseId  = isset($course_id) ? (int)$course_id : 0; // lấy từ trang lồng vào
?>
<link rel="stylesheet" href="lophocchitiet.css">
<style>
#messenger-widget{position:fixed;bottom:20px;right:20px;width:360px;max-width:85vw;font-family:system-ui,sans-serif;z-index:9999}
#messenger-bubble{background:#0084ff;color:#fff;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 10px rgba(0,0,0,.3)}
#messenger-box{display:none;flex-direction:column;height:540px;background:#fff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.3);overflow:hidden}
#messenger-header{background:#0084ff;color:#fff;padding:12px 16px;font-weight:600;display:flex;justify-content:space-between;align-items:center}
#messenger-messages{flex:1;padding:16px;overflow-y:auto;background:#f0f2f5}
.message{           /* mỗi tin nhắn */
  display:flex;
  flex-direction:column;
  margin-bottom:8px;
}
.message.them{ align-items:flex-start; }
.message.me{ align-items:flex-end; }
.bubble{max-width:75%;padding:8px 12px;border-radius:18px;line-height:1.4;font-size:14px}
.message.me .bubble{background:#0084ff;color:#fff;border-bottom-right-radius:2px}
.message.them .bubble{background:#e4e6eb;color:#050505;border-bottom-left-radius:2px}
.message.sending{opacity:.6}
#messenger-input-box{padding:8px;border-top:1px solid #ddd;background:#fff}
#messenger-input-box form{display:flex}
#messenger-input{flex:1;border:0;background:#f0f2f5;padding:10px 12px;border-radius:20px;font-size:14px}
#messenger-send{margin-left:8px;background:#0084ff;color:#fff;border:0;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer}
.sender{
  font-size:9px;
  color:#777;
  margin-bottom:2px;
  max-width:75%;
  word-break:break-word;
}

.message.me .sender{ display:none; }


</style>

<div id="messenger-widget">
   <div id="messenger-bubble" title="Chat lớp">
      💬
   </div>

   <div id="messenger-box">
      <div id="messenger-header">
         Phòng chat lớp học
         <span id="messenger-close" style="cursor:pointer;">✕</span>
      </div>

      <div id="messenger-messages"></div>

      <div id="messenger-input-box">
         <form id="messenger-form" autocomplete="off">
            <input id="messenger-input" placeholder="Nhập tin nhắn..." />
            <button id="messenger-send" type="submit">➤</button>
         </form>
      </div>
   </div>
</div>

<script>
const bubble   = document.getElementById('messenger-bubble');
const box      = document.getElementById('messenger-box');
const closeBtn = document.getElementById('messenger-close');
const list     = document.getElementById('messenger-messages');
const form     = document.getElementById('messenger-form');
const input    = document.getElementById('messenger-input');
let   lastId   = 0;
const userName = <?php echo json_encode($userName); ?>;
const courseId = <?php echo (int)$courseId; ?>;

bubble.onclick = ()=>{ box.style.display='flex'; bubble.style.display='none'; load(); };
closeBtn.onclick = ()=>{ box.style.display='none'; bubble.style.display='flex'; };

/* ---------- Thêm tin nhắn vào DOM ---------- */
function escapeHTML(str){
  return str.replace(/[&<>"]/g,
      t=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[t]));
}

function appendMessage(msg, sender, isMine, temporary=false){
  const wrap = document.createElement('div');
  wrap.className = 'message '+(isMine?'me':'them')+(temporary?' sending':'');

  wrap.innerHTML = `
      <div class="sender">${escapeHTML(sender)}</div>
      <div class="bubble">${escapeHTML(msg)}</div>
  `;
  list.appendChild(wrap);
  list.scrollTop = list.scrollHeight;
  return wrap;
}

/* ---------- GỬI TIN ---------- */
form.addEventListener('submit', async e=>{
  e.preventDefault();
  const msg = input.value.trim();
  if(!msg) return;
  input.value='';

  // Hiển thị ngay (optimistic)
  const tempElem = appendMessage(msg,userName,true);

  try{
    const res = await fetch('chat_api.php',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({course_id:courseId,message:msg})
    });
    const data = await res.json();
    if(data.error){ console.error(data.error);}  
  }catch(err){ console.error(err);}  
  finally{
    // Xoá bản tạm & đồng bộ từ server
    if(tempElem) tempElem.remove();
    load();
  }
});

/* ---------- LẤY TIN ---------- */
async function load(){
  if(!courseId) return;
  try{
    const res  = await fetch(`chat_api.php?course_id=${courseId}&last_id=${lastId}`);
    const data = await res.json();
    if(!Array.isArray(data) || !data.length) return;
    // khi nhận từ server
data.forEach(m=>{
   appendMessage(m.message, m.sender, m.user_name === userName);
   lastId = Math.max(lastId, m.id);
});

// khi gửi optimistic
const tempElem = appendMessage(msg, userName, true, true);
  }catch(err){ console.error(err); }
}
setInterval(()=>{ if(box.style.display==='flex') load(); }, 2500);
</script>