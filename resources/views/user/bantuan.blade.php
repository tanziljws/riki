@extends('layouts.app')

@section('title', 'Bantuan - Chat AI')

@section('content')
<section class="help-page">
  <div class="container">
    <h1 class="title">Bantuan <span class="gold">AI</span></h1>
    <p class="subtitle">Tanyakan apa saja seputar sekolah atau informasi umum. Didukung oleh Gemini.</p>

    @php($userName = auth()->check() ? auth()->user()->name : 'Sahabat')
    <div class="chat-shell">
      <div id="chat" class="chat-window" aria-live="polite" aria-label="Percakapan Bantuan AI">
        <div class="msg bot">
          <div class="bubble">Halo {{ $userName }}! Saya asisten AI. Bagaimana saya bisa membantu?</div>
        </div>
      </div>
      <form id="composer" class="composer" autocomplete="off">
        <input id="prompt" type="text" placeholder="Tulis pertanyaan kamu..." aria-label="Pesan" />
        <button type="submit" class="send" aria-label="Kirim">Kirim</button>
      </form>
    </div>
  </div>
</section>

<style>
* { box-sizing: border-box; }
.help-page { padding: 20px 0; }
.container { max-width: 900px; margin: 0 auto; padding: 0 20px; }
.title { text-align:center; color:#004aad; font-weight:800; margin:0 0 6px; }
.gold { color:#d4af37; }
.subtitle { text-align:center; color:#555; margin:0 0 16px; }

.chat-shell { background:#fff; border:1px solid #e5e7eb; border-radius:16px; box-shadow:0 8px 24px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; height: 70vh; min-height: 480px; }
.chat-window { flex:1; padding:16px; overflow:auto; background:linear-gradient(180deg,#f8fafc,#fff); }
.msg { display:flex; margin-bottom:12px; }
.msg.bot { justify-content:flex-start; }
.msg.user { justify-content:flex-end; }
.bubble { max-width: 78%; padding:12px 14px; border-radius:14px; line-height:1.5; box-shadow:0 2px 12px rgba(0,0,0,.06); }
.msg.bot .bubble { background:#ffffff; color:#1f2937; border:1px solid #eef2f7; }
.msg.user .bubble { background:#004aad; color:#fff; }

.composer { display:flex; gap:10px; padding:10px; border-top:1px solid #e5e7eb; background:#fff; }
#prompt { flex:1; border:1px solid #cbd5e1; border-radius:12px; padding:12px 14px; font-size:1rem; }
#prompt:focus { outline:none; border-color:#004aad; box-shadow:0 0 0 3px rgba(0,74,173,.15); }
.send { border:none; background:#004aad; color:#fff; padding:12px 16px; border-radius:12px; font-weight:700; cursor:pointer; }
.send:disabled { opacity:.6; cursor:not-allowed; }

@media (max-width: 768px) {
  .container { padding: 0 12px; }
  .chat-shell { height: 70vh; min-height: 400px; }
  .bubble { max-width: 86%; }
}
@media (max-width: 480px) {
  .title { font-size: 1.4rem; }
  .subtitle { font-size: .95rem; }
}
</style>

<script>
(function(){
  const chatEl = document.getElementById('chat');
  const form = document.getElementById('composer');
  const input = document.getElementById('prompt');
  const history = [];

  function appendMessage(role, text){
    const msg = document.createElement('div');
    msg.className = 'msg ' + (role === 'user' ? 'user' : 'bot');
    const bubble = document.createElement('div');
    bubble.className = 'bubble';
    bubble.textContent = text;
    msg.appendChild(bubble);
    chatEl.appendChild(msg);
    chatEl.scrollTop = chatEl.scrollHeight;
  }

  async function askGemini(message){
    const payload = { message, history, model: 'gemini-1.5-flash' };
    const res = await fetch('/api/bantuan/chat', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    const body = await res.json().catch(()=>null);
    if(!res.ok){
      let msg = (body && body.error) ? body.error : 'Gagal menghubungi server';
      const apiMsg = body && body.details && body.details.error && body.details.error.message;
      const raw = body && body.details ? (typeof body.details === 'string' ? body.details : JSON.stringify(body.details)) : '';
      const statusText = `status ${res.status}`;
      if (apiMsg) msg += ` (${apiMsg})`;
      if (raw && !apiMsg) msg += ` (${raw})`;
      throw new Error(`${msg} [${statusText}]`);
    }
    return body;
  }

  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const text = (input.value || '').trim();
    if(!text) return;
    appendMessage('user', text);
    history.push({ role: 'user', content: text });
    input.value = '';
    input.disabled = true; form.querySelector('button').disabled = true;

    try{
      const data = await askGemini(text);
      const reply = data.reply || '(Tidak ada jawaban)';
      appendMessage('model', reply);
      history.push({ role: 'model', content: reply });
    }catch(err){
      appendMessage('model', 'Maaf, terjadi kesalahan: ' + (err && err.message ? err.message : String(err)));
    }finally{
      input.disabled = false; form.querySelector('button').disabled = false; input.focus();
    }
  });
})();
</script>
@endsection
