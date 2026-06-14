<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Portal — DEVA Education</title>
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

/* ══ CSS VARIABLES ══ */
:root{
    --bg:       #060f1e;
    --bg2:      #0a1e3d;
    --dot:      rgba(255,255,255,.03);
    --top:      rgba(6,15,30,.9);
    --top-b:    rgba(255,255,255,.08);
    --c:        rgba(255,255,255,.04);
    --cb:       rgba(255,255,255,.08);
    --ch:       rgba(255,255,255,.07);
    --text:     #fff;
    --sub:      rgba(255,255,255,.45);
    --muted:    rgba(255,255,255,.28);
    --val:      rgba(255,255,255,.85);
    --div:      rgba(255,255,255,.06);
    --tab:      rgba(255,255,255,.04);
    --tabc:     rgba(255,255,255,.4);
    --tabh:     rgba(255,255,255,.8);
    --ml-b:     rgba(255,255,255,.07);
    --mh:       rgba(255,255,255,.05);
    --ma:       rgba(26,107,255,.15);
    --mn:       rgba(255,255,255,.85);
    --ml:       rgba(255,255,255,.35);
    --bub-t:    rgba(255,255,255,.09);
    --bub-tc:   rgba(255,255,255,.85);
    --bubbles:  transparent;
    --inp:      rgba(255,255,255,.07);
    --inp-b:    rgba(255,255,255,.1);
    --inp-c:    #fff;
    --inp-ph:   rgba(255,255,255,.3);
    --fab-ring: #060f1e;
    --modal-bg: rgba(0,0,0,.7);
    --modal-c:  #0d1e38;
    --modal-b:  rgba(255,255,255,.1);
    --form-inp: rgba(255,255,255,.07);
    --form-b:   rgba(255,255,255,.12);
    --form-c:   #fff;
    --form-ph:  rgba(255,255,255,.35);
    --sel-opt:  #0d1e38;
}
html.light{
    --bg:#f0f4f8; --bg2:#dce6f4; --dot:rgba(26,107,255,.04);
    --top:rgba(255,255,255,.93); --top-b:#e2e8f0;
    --c:#fff; --cb:#e2e8f0; --ch:#f8faff;
    --text:#0f172a; --sub:#64748b; --muted:#94a3b8; --val:#1e293b; --div:#f1f5f9;
    --tab:#f1f5f9; --tabc:#64748b; --tabh:#0f172a;
    --ml-b:#e2e8f0; --mh:#f8faff; --ma:#eef2ff; --mn:#1e293b; --ml:#94a3b8;
    --bub-t:#f1f5f9; --bub-tc:#1e293b; --bubbles:#f8faff;
    --inp:#f8faff; --inp-b:#e2e8f0; --inp-c:#1e293b; --inp-ph:#94a3b8;
    --fab-ring:#fff;
    --modal-bg:rgba(0,0,0,.45); --modal-c:#fff; --modal-b:#e2e8f0;
    --form-inp:#f8faff; --form-b:#e2e8f0; --form-c:#1e293b; --form-ph:#94a3b8;
    --sel-opt:#fff;
}
html{height:100%}
body{min-height:100%;font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;transition:background .3s,color .3s}
#sp-main{margin-left:220px;min-height:100vh;transition:margin-left .25s cubic-bezier(.4,0,.2,1)}
body.sb-collapsed #sp-main{margin-left:60px}

/* ── BG ── */
.sp-bg{position:fixed;inset:0;z-index:0;background:linear-gradient(160deg,var(--bg) 0%,var(--bg2) 45%,var(--bg) 100%);transition:background .3s}
.sp-bg::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 12% 15%,rgba(26,107,255,.11) 0%,transparent 45%),radial-gradient(circle at 88% 78%,rgba(26,107,255,.07) 0%,transparent 45%)}
.sp-dots{position:absolute;inset:0;background-image:radial-gradient(circle,var(--dot) 1px,transparent 1px);background-size:30px 30px}

/* ── TOPBAR ── */
.sp-top{position:fixed;top:0;left:220px;right:0;z-index:200;height:64px;background:var(--top);backdrop-filter:blur(20px);border-bottom:1px solid var(--top-b);display:flex;align-items:center;padding:0 20px;gap:10px;transition:background .3s,border-color .3s,left .25s cubic-bezier(.4,0,.2,1);}
body.sb-collapsed .sp-top{left:60px;}
@media(max-width:640px){
    .sp-top{left:0!important}
    #sp-main{margin-left:0!important}
    #sp-sidebar{transform:translateX(-100%);width:220px!important}
    body.sb-open #sp-sidebar{transform:translateX(0)}
    #sp-mob-burger{display:flex!important}
}
.sp-top-page{font-size:15px;font-weight:700;color:var(--text);margin-right:auto}
.sp-top-name{font-size:13px;color:var(--sub);white-space:nowrap;}
.sp-avatar-sm{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#1a6bff,#0a3d99);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden;box-shadow:0 0 0 2px rgba(26,107,255,.35)}
.sp-avatar-sm img{width:100%;height:100%;object-fit:cover}
.sp-icon-btn{width:36px;height:36px;border-radius:10px;border:1px solid var(--top-b);background:var(--c);color:var(--sub);display:flex;align-items:center;justify-content:center;font-size:14px;cursor:pointer;transition:all .2s;flex-shrink:0}
.sp-icon-btn:hover{background:rgba(26,107,255,.12);color:#1a6bff;border-color:rgba(26,107,255,.3)}
.sp-logout-btn{background:var(--c);border:1px solid var(--cb);color:var(--sub);font-size:12px;padding:6px 14px;border-radius:9px;cursor:pointer;transition:all .2s;font-family:inherit;white-space:nowrap}
.sp-logout-btn:hover{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#ef4444}

/* ── SIDEBAR ── */
#sp-sidebar{
    position:fixed;top:0;left:0;bottom:0;z-index:300;
    width:220px;
    background:linear-gradient(180deg,#060f1e 0%,#0a1e3d 60%,#060f1e 100%);
    border-right:1px solid rgba(26,107,255,.15);
    box-shadow:4px 0 24px rgba(0,0,0,.4);
    display:flex;flex-direction:column;
    transition:width .25s cubic-bezier(.4,0,.2,1),transform .25s;
    overflow:hidden;
}
#sp-sidebar.collapsed{width:60px}
body.sb-open #sp-sidebar{transform:translateX(0)}

.sb-brand{
    display:flex;align-items:center;gap:10px;padding:14px 14px 14px 16px;
    border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;
    min-height:64px;
    text-decoration:none;
}
.sb-brand-img{width:34px;height:34px;border-radius:9px;object-fit:cover;flex-shrink:0}
.sb-brand-txt{overflow:hidden;white-space:nowrap}
.sb-brand-name{font-size:13px;font-weight:800;background:linear-gradient(135deg,#fff,#93c5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.2}
.sb-brand-sub{font-size:10px;color:rgba(255,255,255,.38);display:block;margin-top:1px}
#sp-sidebar.collapsed .sb-brand-txt{display:none}
#sp-sidebar.collapsed .sb-brand{justify-content:center;padding:14px}

.sb-nav{flex:1;overflow-y:auto;overflow-x:hidden;padding:10px 8px}
.sb-nav::-webkit-scrollbar{width:3px}
.sb-nav::-webkit-scrollbar-track{background:transparent}
.sb-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:3px}

.sb-section{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.22);padding:10px 10px 4px;white-space:nowrap;overflow:hidden}
#sp-sidebar.collapsed .sb-section{opacity:0;height:0;padding:0;margin:0}

.sb-item{
    display:flex;align-items:center;gap:10px;padding:9px 10px;
    border-radius:10px;cursor:pointer;
    color:rgba(255,255,255,.5);font-size:13px;font-weight:500;
    transition:all .15s;white-space:nowrap;
    text-decoration:none;border:none;background:none;font-family:inherit;width:100%;
}
.sb-item:hover{background:rgba(26,107,255,.15);color:rgba(255,255,255,.85)}
.sb-item.active{background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;box-shadow:0 3px 12px rgba(26,107,255,.35)}
.sb-item i{width:18px;text-align:center;font-size:14px;flex-shrink:0}
.sb-item-txt{overflow:hidden;white-space:nowrap}
#sp-sidebar.collapsed .sb-item{justify-content:center;padding:10px}
#sp-sidebar.collapsed .sb-item-txt{display:none}
#sp-sidebar.collapsed .sb-item i{width:auto}

.sb-logout{margin:0 8px 12px;border-top:1px solid rgba(255,255,255,.06);padding-top:10px;flex-shrink:0}
.sb-logout .sb-item{color:rgba(239,68,68,.6)}
.sb-logout .sb-item:hover{background:rgba(239,68,68,.1);color:rgba(239,68,68,.9)}
#sp-sidebar.collapsed .sb-logout{margin:0 4px 12px;padding-top:10px}

/* toggle button inside sidebar top */
.sb-toggle{
    position:absolute;top:19px;right:10px;z-index:10;
    width:26px;height:26px;border-radius:7px;border:1px solid rgba(255,255,255,.1);
    background:rgba(255,255,255,.05);color:rgba(255,255,255,.5);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;font-size:11px;transition:all .2s;flex-shrink:0;
}
.sb-toggle:hover{background:rgba(26,107,255,.2);color:#fff;border-color:rgba(26,107,255,.3)}
#sp-sidebar.collapsed .sb-toggle{right:50%;transform:translateX(50%);top:19px}

/* topbar left already set above */

/* ── WRAP ── */
.sp-wrap{position:relative;z-index:1;padding:80px 24px 70px}

/* ── HERO ── */
.sp-hero{background:var(--c);border:1px solid var(--cb);border-radius:22px;padding:28px 32px;display:flex;align-items:center;gap:22px;margin-bottom:22px;backdrop-filter:blur(10px);transition:background .3s,border-color .3s;position:relative;overflow:hidden}
.sp-hero::before{content:'';position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(26,107,255,.1) 0%,transparent 70%);pointer-events:none}
.sp-hero-av{width:70px;height:70px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,#1a6bff,#0a3d99);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;overflow:hidden;box-shadow:0 0 0 4px rgba(26,107,255,.2),0 8px 24px rgba(26,107,255,.25)}
.sp-hero-av img{width:100%;height:100%;object-fit:cover}
.sp-hero-inf{flex:1;min-width:0}
.sp-hero-gr{font-size:12px;color:var(--sub);margin-bottom:3px}
.sp-hero-nm{font-size:22px;font-weight:700;color:var(--text);line-height:1.2;margin-bottom:4px}
.sp-hero-em{font-size:13px;color:var(--sub)}
.sp-status{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;margin-top:8px}
.sp-hero-actions{display:flex;flex-direction:column;gap:8px;flex-shrink:0}
.sp-action-btn{padding:9px 18px;border-radius:11px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:inherit;display:flex;align-items:center;gap:7px;transition:all .2s;white-space:nowrap}
.sp-action-btn.primary{background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;box-shadow:0 4px 14px rgba(26,107,255,.3)}
.sp-action-btn.primary:hover{opacity:.9;transform:translateY(-1px)}
.sp-action-btn.outline{background:var(--c);border:1px solid var(--cb);color:var(--val)}
.sp-action-btn.outline:hover{background:var(--ch);border-color:rgba(26,107,255,.3);color:#1a6bff}

/* ── STATS ── */
.sp-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px}
@media(max-width:700px){.sp-stats{grid-template-columns:repeat(2,1fr)}}
.sp-stat{background:var(--c);border:1px solid var(--cb);border-radius:16px;padding:16px;display:flex;align-items:center;gap:12px;transition:background .2s,transform .2s,border-color .3s;cursor:default}
.sp-stat:hover{background:var(--ch);transform:translateY(-2px)}
.sp-stat-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.sp-stat-val{font-size:20px;font-weight:700;color:var(--text);line-height:1}
.sp-stat-lbl{font-size:11px;color:var(--sub);margin-top:2px}

/* ── TABS ── */
.sp-tabs{display:flex;gap:4px;background:var(--tab);border:1px solid var(--cb);border-radius:14px;padding:5px;margin-bottom:20px;transition:background .3s,border-color .3s}
.sp-tab{flex:1;padding:9px 8px;border:none;border-radius:10px;font-size:13px;font-weight:500;cursor:pointer;background:transparent;color:var(--tabc);transition:all .18s;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:5px}
.sp-tab:hover{color:var(--tabh)}
.sp-tab.active{background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;box-shadow:0 4px 14px rgba(26,107,255,.28)}
.sp-tab-badge{font-size:10px;font-weight:700;padding:1px 6px;border-radius:8px;background:rgba(26,107,255,.2);color:#60a5fa}
.sp-tab.active .sp-tab-badge{background:rgba(255,255,255,.25);color:#fff}
html.light .sp-tab-badge{background:rgba(26,107,255,.1);color:#1a6bff}

/* ── PANELS ── */
.sp-panel{display:none}
.sp-panel.active{display:block}

/* ── CARD ── */
.sp-card{background:var(--c);border:1px solid var(--cb);border-radius:18px;padding:22px;margin-bottom:14px;transition:background .3s,border-color .3s}
.sp-card-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.sp-card-title{font-size:14px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px}
.sp-card-title i{color:#1a6bff}
.sp-card-act{padding:6px 14px;border-radius:9px;font-size:12px;font-weight:600;cursor:pointer;border:none;font-family:inherit;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;display:inline-flex;align-items:center;gap:6px;transition:all .2s}
.sp-card-act:hover{opacity:.9;transform:translateY(-1px)}

/* ── INFO GRID ── */
.sp-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0}
@media(max-width:580px){.sp-info-grid{grid-template-columns:1fr}}
.sp-info-item{padding:11px 0;border-bottom:1px solid var(--div)}
.sp-info-item:nth-child(odd){padding-right:22px}
.sp-info-lbl{font-size:11px;color:var(--muted);margin-bottom:3px}
.sp-info-val{font-size:13px;color:var(--val);font-weight:500}

/* ── TABLE ── */
.sp-table{width:100%;border-collapse:collapse}
.sp-table th{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;padding:0 10px 11px 0;font-weight:600;text-align:left;border-bottom:1px solid var(--div)}
.sp-table td{padding:13px 10px 13px 0;border-bottom:1px solid var(--div);font-size:13px;color:var(--val);vertical-align:middle}
.sp-table tr:last-child td{border-bottom:none}
.sp-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}

/* ── DOCS ── */
.sp-docs-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
@media(max-width:600px){.sp-docs-grid{grid-template-columns:1fr 1fr}}
.sp-doc{background:var(--c);border:1px solid var(--cb);border-radius:13px;padding:16px 12px;text-align:center;transition:all .2s}
.sp-doc.uploaded{border-color:rgba(16,185,129,.35)}
.sp-doc-ico{font-size:26px;margin-bottom:8px}
.sp-doc-nm{font-size:11px;color:var(--sub);margin-bottom:7px;font-weight:500}
.sp-doc-st{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;padding:2px 8px;border-radius:8px}
.sp-doc-st.ok{background:rgba(16,185,129,.15);color:#059669}
.sp-doc-st.no{background:rgba(239,68,68,.12);color:#dc2626}
.sp-doc-link{display:block;margin-top:7px;font-size:11px;color:#1a6bff;text-decoration:none;cursor:pointer}
.sp-doc-upload-btn{margin-top:8px;padding:5px 12px;border-radius:8px;border:none;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;font-size:11px;font-weight:600;cursor:pointer;font-family:inherit;width:100%;transition:opacity .2s}
.sp-doc-upload-btn:hover{opacity:.88}

/* ── MESSAGES PANEL ── */
.sp-msgs-wrap{background:var(--c);border:1px solid var(--cb);border-radius:18px;height:440px;overflow:hidden;display:flex;flex-direction:column;transition:background .3s,border-color .3s}
.sp-msgs-body{display:flex;flex:1;overflow:hidden}
.sp-msgs-list{width:210px;flex-shrink:0;border-right:1px solid var(--ml-b);overflow-y:auto}
.sp-conv-label{padding:10px 14px;font-size:10px;color:var(--muted);border-bottom:1px solid var(--div);text-transform:uppercase;letter-spacing:.07em;font-weight:600}
.sp-msg-item{padding:11px 14px;border-bottom:1px solid var(--div);cursor:pointer;transition:background .12s}
.sp-msg-item:hover{background:var(--mh)}
.sp-msg-item.active{background:var(--ma)}
.sp-msg-name{font-size:13px;font-weight:600;color:var(--mn)}
.sp-msg-last{font-size:11px;color:var(--ml);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sp-msgs-chat{flex:1;display:flex;flex-direction:column;overflow:hidden}
.sp-msgs-chat-hd{padding:11px 16px;border-bottom:1px solid var(--ml-b);font-size:13px;font-weight:600;color:var(--text);flex-shrink:0;display:flex;align-items:center;gap:8px}
.sp-msgs-av{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#1a6bff,#0a3d99);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0}
.sp-bubbles{flex:1;overflow-y:auto;padding:14px 16px;display:flex;flex-direction:column;gap:8px;background:var(--bubbles);transition:background .3s}
html.light .sp-bubbles{background:#f5f8ff}
.sp-bw{display:flex;align-items:flex-end;gap:8px}
.sp-bw.mine{flex-direction:row-reverse}
.sp-bubble{max-width:70%;padding:8px 12px;border-radius:14px;font-size:13px;line-height:1.5;word-break:break-word}
.sp-bubble.theirs{background:var(--bub-t);color:var(--bub-tc);border-bottom-left-radius:4px}
.sp-bubble.mine{background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;border-bottom-right-radius:4px}
.sp-btime{font-size:10px;color:var(--muted);margin-top:2px;text-align:right}
.sp-inp-wrap{padding:10px 12px;border-top:1px solid var(--ml-b);display:flex;gap:8px;flex-shrink:0;background:var(--c);transition:background .3s}
.sp-inp{flex:1;background:var(--inp);border:1px solid var(--inp-b);border-radius:20px;padding:8px 14px;color:var(--inp-c);font-size:13px;font-family:inherit;outline:none;resize:none;max-height:70px;transition:border-color .2s}
.sp-inp::placeholder{color:var(--inp-ph)}
.sp-inp:focus{border-color:#1a6bff}
.sp-send{width:36px;height:36px;border-radius:50%;border:none;flex-shrink:0;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center;transition:transform .15s}
.sp-send:hover{transform:scale(1.1)}

/* ── EMPTY ── */
.sp-empty{text-align:center;padding:32px 18px;color:var(--muted)}
.sp-empty i{font-size:30px;margin-bottom:10px;display:block;opacity:.3}
.sp-empty p{font-size:13px;margin:0}

/* ── FAB ── */
#sp-fab{position:fixed;bottom:28px;right:28px;z-index:9999;width:52px;height:52px;border-radius:50%;border:none;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;cursor:pointer;font-size:19px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(26,107,255,.45);transition:transform .2s,box-shadow .2s}
#sp-fab:hover{transform:scale(1.1);box-shadow:0 6px 28px rgba(26,107,255,.6)}
#sp-fab-badge{position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:9px;border:2px solid var(--fab-ring);display:none;align-items:center;justify-content:center;padding:0 4px}

/* ── FLOATING CHAT WINDOW ── */
#sp-fchat{position:fixed;bottom:92px;right:28px;z-index:9998;width:340px;height:480px;border-radius:16px;background:var(--modal-c);border:1px solid var(--modal-b);box-shadow:0 8px 40px rgba(0,0,0,.3);display:none;flex-direction:column;overflow:hidden;transition:background .3s,border-color .3s}
#sp-fchat.open{display:flex}
#sp-fchat-hd{background:linear-gradient(135deg,#0a1628,#0d2550);color:#fff;padding:12px 16px;display:flex;align-items:center;gap:10px;flex-shrink:0}
#sp-fchat-title{flex:1;font-weight:600;font-size:14px}
#sp-fchat-sub{font-size:11px;opacity:.6}
.sp-fchat-hbtn{background:rgba(255,255,255,.12);border:none;color:#fff;width:28px;height:28px;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;transition:background .15s;margin-left:auto}
.sp-fchat-hbtn:hover{background:rgba(255,255,255,.25)}
#sp-fchat-convlist{flex:1;overflow-y:auto}
#sp-fchat-msgs{flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px;background:rgba(0,0,0,.15)}
html.light #sp-fchat-msgs{background:#f5f8ff}
#sp-fchat-inp-wrap{padding:10px 12px;border-top:1px solid rgba(255,255,255,.1);display:flex;gap:8px;flex-shrink:0;background:rgba(0,0,0,.1)}
html.light #sp-fchat-inp-wrap{background:var(--c)}
#sp-fchat-inp{flex:1;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:20px;padding:7px 12px;color:#fff;font-size:13px;font-family:inherit;outline:none;resize:none}
html.light #sp-fchat-inp{background:var(--inp);border-color:var(--inp-b);color:var(--inp-c)}
#sp-fchat-inp::placeholder{color:rgba(255,255,255,.35)}
html.light #sp-fchat-inp::placeholder{color:var(--inp-ph)}
#sp-fchat-send{width:34px;height:34px;border-radius:50%;border:none;flex-shrink:0;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center}
.sp-fchat-conv{padding:10px 14px;cursor:pointer;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:10px;transition:background .12s}
.sp-fchat-conv:hover{background:rgba(255,255,255,.06)}
.sp-fchat-conv.active{background:rgba(26,107,255,.2)}
.sp-fchat-av{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1a6bff,#0a3d99);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;color:#fff;flex-shrink:0}
.sp-fchat-nm{font-size:13px;font-weight:600;color:#fff}
.sp-fchat-ls{font-size:11px;color:rgba(255,255,255,.4);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px}
html.light .sp-fchat-nm{color:var(--mn)}
html.light .sp-fchat-ls{color:var(--ml)}
html.light .sp-fchat-conv{border-color:var(--div)}
html.light .sp-fchat-conv:hover{background:var(--mh)}
html.light .sp-fchat-conv.active{background:var(--ma)}
#sp-fchat-back-bar{padding:10px 12px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:8px;flex-shrink:0;display:none}
html.light #sp-fchat-back-bar{border-color:var(--ml-b)}
#sp-fchat-back-btn{background:rgba(255,255,255,.1);border:none;color:#fff;width:28px;height:28px;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0}
html.light #sp-fchat-back-btn{background:rgba(26,107,255,.1);color:#1a6bff}
#sp-fchat-chat-name{font-size:13px;font-weight:600;color:#fff;flex:1}
html.light #sp-fchat-chat-name{color:var(--mn)}

/* ── MODAL ── */
.sp-modal-overlay{position:fixed;inset:0;z-index:10000;background:var(--modal-bg);backdrop-filter:blur(6px);display:none;align-items:center;justify-content:center;padding:20px}
.sp-modal-overlay.open{display:flex}
.sp-modal{background:var(--modal-c);border:1px solid var(--modal-b);border-radius:20px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;transition:background .3s,border-color .3s}
.sp-modal-hd{padding:20px 24px 16px;border-bottom:1px solid var(--modal-b);display:flex;align-items:center;justify-content:space-between}
.sp-modal-title{font-size:15px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px}
.sp-modal-title i{color:#1a6bff}
.sp-modal-close{background:none;border:none;color:var(--muted);cursor:pointer;font-size:16px;padding:4px;transition:color .15s}
.sp-modal-close:hover{color:var(--text)}
.sp-modal-body{padding:20px 24px}
.sp-form-group{margin-bottom:16px}
.sp-form-label{display:block;font-size:12px;font-weight:600;color:var(--sub);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em}
.sp-form-ctrl{width:100%;padding:9px 13px;border-radius:10px;border:1px solid var(--form-b);background:var(--form-inp);color:var(--form-c);font-size:13px;font-family:inherit;outline:none;transition:border-color .2s}
.sp-form-ctrl:focus{border-color:#1a6bff}
.sp-form-ctrl::placeholder{color:var(--form-ph)}
select.sp-form-ctrl option{background:var(--sel-opt);color:var(--form-c)}
.sp-modal-foot{padding:16px 24px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid var(--modal-b)}
.sp-btn-cancel{padding:8px 18px;border-radius:10px;border:1px solid var(--cb);background:transparent;color:var(--sub);font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all .2s}
.sp-btn-cancel:hover{background:var(--ch)}
.sp-btn-submit{padding:8px 22px;border-radius:10px;border:none;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;box-shadow:0 4px 14px rgba(26,107,255,.3);transition:all .2s}
.sp-btn-submit:hover{opacity:.9;transform:translateY(-1px)}
.sp-alert{padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:14px;display:none}
.sp-alert.success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#059669}
.sp-alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#dc2626}

/* doc upload modal */
.sp-doc-pick-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}
.sp-doc-pick{border:2px dashed var(--form-b);border-radius:12px;padding:14px 10px;text-align:center;cursor:pointer;transition:all .2s;position:relative}
.sp-doc-pick:hover{border-color:#1a6bff;background:rgba(26,107,255,.05)}
.sp-doc-pick.selected{border-color:#10b981;border-style:solid;background:rgba(16,185,129,.05)}
.sp-doc-pick-ico{font-size:20px;margin-bottom:5px}
.sp-doc-pick-nm{font-size:11px;color:var(--sub);font-weight:500}
.sp-doc-pick input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer}
.sp-doc-pick-done{font-size:10px;color:#059669;margin-top:3px;font-weight:600}
</style>
</head>

<body>
<div class="sp-bg"><div class="sp-dots"></div></div>

{{-- ══ SIDEBAR ══ --}}
<aside id="sp-sidebar">
    <button class="sb-toggle" onclick="spSidebarToggle()" title="Toggle sidebar">
        <i class="fas fa-chevron-left" id="sb-toggle-icon"></i>
    </button>

    <a href="{{ route('student.dashboard') }}" class="sb-brand">
        <img src="{{ asset('Apx.jpeg') }}" class="sb-brand-img" alt="APX">
        <div class="sb-brand-txt">
            <div class="sb-brand-name">DEVA Education</div>
            <span class="sb-brand-sub">Student Portal</span>
        </div>
    </a>

    <nav class="sb-nav">
        <div class="sb-section">Main</div>

        <button class="sb-item active" onclick="spSidebarNav('overview',this)">
            <i class="fas fa-th-large"></i>
            <span class="sb-item-txt">Dashboard</span>
        </button>

        <button class="sb-item" onclick="spSidebarNav('apps',this)">
            <i class="fas fa-file-alt"></i>
            <span class="sb-item-txt">Applications</span>
        </button>

        <button class="sb-item" onclick="spSidebarNav('docs',this)">
            <i class="fas fa-folder-open"></i>
            <span class="sb-item-txt">Documents</span>
        </button>

        <div class="sb-section">Communication</div>

        <button class="sb-item" onclick="spSidebarNav('msgs',this)">
            <i class="fas fa-comment-dots"></i>
            <span class="sb-item-txt">Messages</span>
        </button>

        <div class="sb-section">Quick Actions</div>

        <button class="sb-item" onclick="document.getElementById('modal-app').classList.add('open')">
            <i class="fas fa-plus-circle"></i>
            <span class="sb-item-txt">New Application</span>
        </button>

        <button class="sb-item" onclick="document.getElementById('modal-doc').classList.add('open')">
            <i class="fas fa-upload"></i>
            <span class="sb-item-txt">Upload Document</span>
        </button>
    </nav>

    <div class="sb-logout">
        <form action="{{ route('student.logout') }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="sb-item">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sb-item-txt">Logout</span>
            </button>
        </form>
    </div>
</aside>

<div id="sp-main">

@php
    $student   = Auth::guard('student')->user();
    $student->load(['applications.university','nationality','countryOfResidence','applyingDegree']);
    $appsCount = $student->applications->count();
    $docFields = ['passport_path','transcript_path','diploma_path','denklik_path','certificate_path','other_documents_path'];
    $docLabels = ['Passport','Transcript','Diploma','Denklik','Certificate','Other'];
    $docKeys   = ['passport','transcript','diploma','denklik','certificate','other_documents'];
    $docsCount = collect($docFields)->filter(fn($f)=>!empty($student->$f))->count();
    $sc = match($student->status??'New'){
        'In Review'         => ['rgba(14,165,233,.2)','#7dd3fc','fa-search'],
        'Pending Documents' => ['rgba(234,179,8,.2)', '#fde047','fa-exclamation-circle'],
        'Accepted'          => ['rgba(16,185,129,.2)','#6ee7b7','fa-check-circle'],
        'Rejected'          => ['rgba(239,68,68,.2)', '#fca5a5','fa-times-circle'],
        'Enrolled'          => ['rgba(16,185,129,.2)','#6ee7b7','fa-graduation-cap'],
        default             => ['rgba(99,102,241,.2)','#a5b4fc','fa-plus-circle'],
    };
    $universities = App\Models\University::orderBy('name')->get(['id','name']);
    // Responsible person: processed_by field
    $responsibleId = $student->processed_by;
@endphp

{{-- ══ TOPBAR ══ --}}
<div class="sp-top">
    <button class="sp-icon-btn" onclick="spSidebarToggle()" style="display:none" id="sp-mob-burger"><i class="fas fa-bars"></i></button>
    <div class="sp-top-page" id="sp-page-title">Dashboard</div>
    <span class="sp-top-name">{{ $student->first_name }} {{ $student->last_name }}</span>
    <button class="sp-icon-btn" onclick="spToggleTheme()" title="Toggle theme" id="sp-theme-btn">
        <i class="fas fa-moon" id="sp-theme-icon"></i>
    </button>
    <div class="sp-avatar-sm">
        @if($student->photo_path)<img src="{{ Storage::url($student->photo_path) }}" alt="">
        @else{{ strtoupper(substr($student->first_name,0,1)) }}{{ strtoupper(substr($student->last_name,0,1)) }}
        @endif
    </div>
    <form action="{{ route('student.logout') }}" method="POST" style="margin:0">
        @csrf
        <button type="submit" class="sp-logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </form>
</div>

{{-- ══ MAIN ══ --}}
<div class="sp-wrap">

    @if(session('success'))
    <div style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#059669;padding:12px 18px;border-radius:12px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- HERO --}}
    <div class="sp-hero">
        <div class="sp-hero-av">
            @if($student->photo_path)<img src="{{ Storage::url($student->photo_path) }}" alt="">
            @else{{ strtoupper(substr($student->first_name,0,1)) }}{{ strtoupper(substr($student->last_name,0,1)) }}
            @endif
        </div>
        <div class="sp-hero-inf">
            <div class="sp-hero-gr">Welcome back 👋</div>
            <div class="sp-hero-nm">{{ $student->first_name }} {{ $student->last_name }}</div>
            <div class="sp-hero-em">{{ $student->email }}</div>
            <div class="sp-status" style="background:{{ $sc[0] }};color:{{ $sc[1] }}">
                <i class="fas {{ $sc[2] }}"></i> {{ $student->status ?? 'New' }}
            </div>
        </div>
        <div class="sp-hero-actions">
            <button class="sp-action-btn primary" onclick="document.getElementById('modal-app').classList.add('open')">
                <i class="fas fa-plus"></i> New Application
            </button>
            <button class="sp-action-btn outline" onclick="document.getElementById('modal-doc').classList.add('open')">
                <i class="fas fa-upload"></i> Upload Document
            </button>
        </div>
    </div>

    {{-- STATS --}}
    <div class="sp-stats">
        <div class="sp-stat">
            <div class="sp-stat-ico" style="background:rgba(26,107,255,.18)"><i class="fas fa-file-alt" style="color:#60a5fa"></i></div>
            <div><div class="sp-stat-val">{{ $appsCount }}</div><div class="sp-stat-lbl">Applications</div></div>
        </div>
        <div class="sp-stat">
            <div class="sp-stat-ico" style="background:rgba(16,185,129,.18)"><i class="fas fa-folder-open" style="color:#6ee7b7"></i></div>
            <div><div class="sp-stat-val">{{ $docsCount }}/{{ count($docFields) }}</div><div class="sp-stat-lbl">Documents</div></div>
        </div>
        <div class="sp-stat">
            <div class="sp-stat-ico" style="background:rgba(234,179,8,.18)"><i class="fas fa-university" style="color:#fde047"></i></div>
            <div><div class="sp-stat-val">{{ $student->applications->pluck('university_id')->unique()->count() }}</div><div class="sp-stat-lbl">Universities</div></div>
        </div>
        <div class="sp-stat">
            <div class="sp-stat-ico" style="background:rgba(139,92,246,.18)"><i class="fas fa-calendar" style="color:#c4b5fd"></i></div>
            <div><div class="sp-stat-val">{{ $student->created_at->format('Y') }}</div><div class="sp-stat-lbl">Joined</div></div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="sp-tabs">
        <button class="sp-tab active" data-tab="overview" onclick="spTab('overview',this)"><i class="fas fa-th-large"></i> Overview</button>
        <button class="sp-tab" data-tab="apps" onclick="spTab('apps',this)">
            <i class="fas fa-file-alt"></i> Applications
            @if($appsCount)<span class="sp-tab-badge">{{ $appsCount }}</span>@endif
        </button>
        <button class="sp-tab" data-tab="docs" onclick="spTab('docs',this)">
            <i class="fas fa-folder"></i> Documents
            @if($docsCount < count($docFields))<span class="sp-tab-badge" style="background:rgba(234,179,8,.25);color:#ca8a04;">{{ count($docFields)-$docsCount }} missing</span>@endif
        </button>
        <button class="sp-tab" data-tab="msgs" onclick="spTab('msgs',this)"><i class="fas fa-comment-dots"></i> Messages</button>
    </div>

    {{-- PANEL: OVERVIEW --}}
    <div id="panel-overview" class="sp-panel active">
        <div class="sp-card">
            <div class="sp-card-hd">
                <div class="sp-card-title"><i class="fas fa-id-card"></i> Personal Information</div>
            </div>
            <div class="sp-info-grid">
                <div class="sp-info-item"><div class="sp-info-lbl">Full Name</div><div class="sp-info-val">{{ $student->first_name }} {{ $student->last_name }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Father Name</div><div class="sp-info-val">{{ $student->father_name ?? '—' }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Email</div><div class="sp-info-val">{{ $student->email }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Phone</div><div class="sp-info-val">{{ $student->phone_number ?? '—' }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Date of Birth</div><div class="sp-info-val">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : '—' }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Gender</div><div class="sp-info-val">{{ ucfirst($student->gender ?? '—') }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Nationality</div><div class="sp-info-val">{{ $student->nationality->name ?? '—' }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Country of Residence</div><div class="sp-info-val">{{ $student->countryOfResidence->name ?? '—' }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">Passport ID</div><div class="sp-info-val">{{ $student->passport_id ?? '—' }}</div></div>
                <div class="sp-info-item"><div class="sp-info-lbl">GPA</div><div class="sp-info-val">{{ $student->gpa ?? '—' }}</div></div>
            </div>
        </div>
    </div>

    {{-- PANEL: APPLICATIONS --}}
    <div id="panel-apps" class="sp-panel">
        <div class="sp-card">
            <div class="sp-card-hd">
                <div class="sp-card-title"><i class="fas fa-file-alt"></i> My Applications</div>
                <button class="sp-card-act" onclick="document.getElementById('modal-app').classList.add('open')">
                    <i class="fas fa-plus"></i> New Application
                </button>
            </div>
            @if($student->applications->count())
            <table class="sp-table">
                <thead><tr><th>#</th><th>University</th><th>Department</th><th>Degree</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @foreach($student->applications as $app)
                @php $ac = match($app->status??'Pending'){
                    'Approved','Accepted'=>['rgba(16,185,129,.15)','#059669'],
                    'Rejected'=>['rgba(239,68,68,.15)','#dc2626'],
                    'In Review'=>['rgba(14,165,233,.15)','#0284c7'],
                    default=>['rgba(234,179,8,.15)','#b45309']};
                @endphp
                <tr>
                    <td style="color:var(--muted);font-size:11px">#{{ $app->id }}</td>
                    <td style="font-weight:600;color:var(--text)">{{ $app->university->name ?? '—' }}</td>
                    <td>{{ $app->department ?? '—' }}</td>
                    <td>{{ $app->degree ?? '—' }}</td>
                    <td><span class="sp-badge" style="background:{{ $ac[0] }};color:{{ $ac[1] }}">{{ $app->status ?? 'Pending' }}</span></td>
                    <td style="color:var(--muted);font-size:12px">{{ $app->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="sp-empty"><i class="fas fa-file-circle-xmark"></i><p>No applications yet.</p>
                <button class="sp-card-act" style="margin:12px auto 0;display:inline-flex" onclick="document.getElementById('modal-app').classList.add('open')"><i class="fas fa-plus"></i> Add First Application</button>
            </div>
            @endif
        </div>
    </div>

    {{-- PANEL: DOCUMENTS --}}
    <div id="panel-docs" class="sp-panel">
        <div class="sp-card">
            <div class="sp-card-hd">
                <div class="sp-card-title"><i class="fas fa-folder-open"></i> My Documents</div>
                <button class="sp-card-act" onclick="document.getElementById('modal-doc').classList.add('open')">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <div class="sp-docs-grid">
                @foreach($docFields as $i => $field)
                @php $up = !empty($student->$field); @endphp
                <div class="sp-doc {{ $up ? 'uploaded' : '' }}">
                    <div class="sp-doc-ico">{{ $up ? '📄' : '📁' }}</div>
                    <div class="sp-doc-nm">{{ $docLabels[$i] }}</div>
                    @if($up)
                        <span class="sp-doc-st ok"><i class="fas fa-check"></i> Uploaded</span>
                        <a href="{{ Storage::url($student->$field) }}" target="_blank" class="sp-doc-link"><i class="fas fa-eye"></i> View</a>
                    @else
                        <span class="sp-doc-st no"><i class="fas fa-times"></i> Missing</span>
                        <button class="sp-doc-upload-btn" onclick="spOpenDocModal('{{ $docKeys[$i] }}')"><i class="fas fa-upload"></i> Upload</button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PANEL: MESSAGES --}}
    <div id="panel-msgs" class="sp-panel">
        <div class="sp-msgs-wrap">
            <div class="sp-msgs-body">
                <div class="sp-msgs-list">
                    <div class="sp-conv-label">Conversations</div>
                    <div id="sp-convs-inner">
                        <div class="sp-empty" style="padding:20px"><i class="fas fa-spinner fa-spin" style="font-size:18px"></i></div>
                    </div>
                </div>
                <div class="sp-msgs-chat">
                    <div class="sp-msgs-chat-hd">
                        <div class="sp-msgs-av" id="sp-chat-av" style="display:none"></div>
                        <span id="sp-chat-cname" style="color:var(--muted)">Select a conversation</span>
                    </div>
                    <div class="sp-bubbles" id="sp-bubbles">
                        <div class="sp-empty"><i class="fas fa-comment-dots"></i><p>Select a chat to start messaging</p></div>
                    </div>
                    <div class="sp-inp-wrap">
                        <textarea class="sp-inp" id="sp-msg-input" placeholder="Type a message…" rows="1"
                            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();spSend()}"></textarea>
                        <button class="sp-send" onclick="spSend()"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /.sp-wrap --}}

{{-- ══ FAB ══ --}}
<button id="sp-fab" onclick="spFabClick()" title="Messages">
    <i class="fas fa-comment-dots"></i>
    <span id="sp-fab-badge"></span>
</button>

{{-- ══ FLOATING CHAT WINDOW ══ --}}
<div id="sp-fchat">
    <div id="sp-fchat-hd">
        <div class="sp-fchat-av" id="sp-fchat-av-hd">💬</div>
        <div style="flex:1;min-width:0">
            <div id="sp-fchat-title">Messages</div>
            <div id="sp-fchat-sub">{{ $student->first_name }}</div>
        </div>
        <button class="sp-fchat-hbtn" onclick="spFabClick()" title="Close"><i class="fas fa-times"></i></button>
    </div>
    {{-- conv list panel --}}
    <div id="sp-fchat-list" style="flex:1;overflow-y:auto;display:flex;flex-direction:column">
        <div class="sp-conv-label" style="color:rgba(255,255,255,.4);border-color:rgba(255,255,255,.07)">Your Chats</div>
        <div id="sp-fchat-convs"><div class="sp-empty"><i class="fas fa-spinner fa-spin"></i></div></div>
    </div>
    {{-- chat panel --}}
    <div id="sp-fchat-chat" style="flex:1;display:none;flex-direction:column;overflow:hidden">
        <div id="sp-fchat-back-bar" style="display:flex">
            <button id="sp-fchat-back-btn" onclick="spFchatBack()"><i class="fas fa-arrow-left"></i></button>
            <div class="sp-fchat-av" id="sp-fchat-chat-av" style="width:28px;height:28px;font-size:11px"></div>
            <span id="sp-fchat-chat-name"></span>
            <button class="sp-fchat-hbtn" onclick="spFabClick()" style="margin-left:auto"><i class="fas fa-times"></i></button>
        </div>
        <div id="sp-fchat-msgs"></div>
        <div id="sp-fchat-inp-wrap">
            <textarea id="sp-fchat-inp" placeholder="Type a message…" rows="1"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();spFchatSend()}"></textarea>
            <button id="sp-fchat-send" onclick="spFchatSend()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

{{-- ══ MODAL: NEW APPLICATION ══ --}}
<div class="sp-modal-overlay" id="modal-app" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="sp-modal">
        <div class="sp-modal-hd">
            <div class="sp-modal-title"><i class="fas fa-file-alt"></i> New Application</div>
            <button class="sp-modal-close" onclick="document.getElementById('modal-app').classList.remove('open')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('student.applications.store') }}" method="POST" id="form-app">
            @csrf
            <div class="sp-modal-body">
                <div id="app-alert" class="sp-alert"></div>
                <div class="sp-form-group">
                    <label class="sp-form-label">University *</label>
                    <select name="university_id" class="sp-form-ctrl" required>
                        <option value="">— Select university —</option>
                        @foreach($universities as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sp-form-group">
                    <label class="sp-form-label">Department *</label>
                    <input type="text" name="department" class="sp-form-ctrl" placeholder="e.g. Computer Science" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="sp-form-group">
                        <label class="sp-form-label">Degree *</label>
                        <select name="degree" class="sp-form-ctrl" required>
                            <option value="">— Select —</option>
                            <option>Bachelor</option><option>Master</option><option>PhD</option><option>Associate</option>
                        </select>
                    </div>
                    <div class="sp-form-group">
                        <label class="sp-form-label">Language *</label>
                        <select name="language" class="sp-form-ctrl" required>
                            <option value="">— Select —</option>
                            <option>English</option><option>Turkish</option><option>Arabic</option><option>French</option><option>German</option>
                        </select>
                    </div>
                </div>
                <div class="sp-form-group">
                    <label class="sp-form-label">Semester *</label>
                    <select name="semester" class="sp-form-ctrl" required>
                        <option value="">— Select —</option>
                        <option>Fall</option><option>Spring</option><option>Summer</option>
                    </select>
                </div>
                <div class="sp-form-group">
                    <label class="sp-form-label">Notes (optional)</label>
                    <textarea name="notes" class="sp-form-ctrl" rows="3" placeholder="Any additional notes…" style="resize:vertical"></textarea>
                </div>
            </div>
            <div class="sp-modal-foot">
                <button type="button" class="sp-btn-cancel" onclick="document.getElementById('modal-app').classList.remove('open')">Cancel</button>
                <button type="submit" class="sp-btn-submit"><i class="fas fa-paper-plane"></i> Submit Application</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: UPLOAD DOCUMENT ══ --}}
<div class="sp-modal-overlay" id="modal-doc" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="sp-modal">
        <div class="sp-modal-hd">
            <div class="sp-modal-title"><i class="fas fa-upload"></i> Upload Documents</div>
            <button class="sp-modal-close" onclick="document.getElementById('modal-doc').classList.remove('open')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('student.documents.upload') }}" method="POST" enctype="multipart/form-data" id="form-doc">
            @csrf
            <div class="sp-modal-body">
                <div id="doc-alert" class="sp-alert"></div>
                <p style="font-size:12px;color:var(--sub);margin-bottom:14px">Click any document card to select a file. Accepted: JPG, PNG, PDF (max 2MB)</p>
                <div class="sp-doc-pick-grid">
                    @foreach($docKeys as $i => $key)
                    <div class="sp-doc-pick" id="pick-{{ $key }}">
                        <div class="sp-doc-pick-ico">{{ collect(['📕','📗','📘','📙','📒','📂'])[$i] }}</div>
                        <div class="sp-doc-pick-nm">{{ $docLabels[$i] }}</div>
                        <div class="sp-doc-pick-done" id="pick-done-{{ $key }}" style="display:none">✓ Selected</div>
                        <input type="file" name="{{ $key }}" accept=".jpg,.jpeg,.png,.pdf"
                            onchange="spDocPicked('{{ $key }}',this)">
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="sp-modal-foot">
                <button type="button" class="sp-btn-cancel" onclick="document.getElementById('modal-doc').classList.remove('open')">Cancel</button>
                <button type="submit" class="sp-btn-submit"><i class="fas fa-cloud-upload-alt"></i> Upload</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
(function(){
    var CSRF      = '{{ csrf_token() }}';
    var ME        = {{ Auth::guard('student')->id() }};
    var RESP_ID   = {{ $responsibleId ?? 'null' }};   // processed_by user id
    var URL_CONVS = '{{ route("chat.conversations") }}';
    var URL_SEND  = '{{ route("chat.send") }}';
    var URL_CREATE= '{{ route("chat.create") }}';

    var st = { convId:null, convs:[], totalUnread:0, pollT:null, msgT:null, fabOpen:false };

    /* ── THEME ── */
    window.spToggleTheme = function(){
        var light = document.documentElement.classList.toggle('light');
        document.getElementById('sp-theme-icon').className = light ? 'fas fa-sun' : 'fas fa-moon';
        localStorage.setItem('sp-theme', light ? 'light' : 'dark');
    };
    (function(){
        if(localStorage.getItem('sp-theme')==='light'){
            document.documentElement.classList.add('light');
            var ic = document.getElementById('sp-theme-icon');
            if(ic) ic.className = 'fas fa-sun';
        }
    })();

    /* ── SIDEBAR TOGGLE ── */
    window.spSidebarToggle = function(){
        var sb   = document.getElementById('sp-sidebar');
        var icon = document.getElementById('sb-toggle-icon');
        if(window.innerWidth <= 640){
            // mobile: slide in/out
            document.body.classList.toggle('sb-open');
            return;
        }
        var collapsed = sb.classList.toggle('collapsed');
        document.body.classList.toggle('sb-collapsed', collapsed);
        icon.className = collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
        localStorage.setItem('sp-sb', collapsed ? '1' : '0');
    };
    var pageNames = {overview:'Dashboard',apps:'Applications',docs:'Documents',msgs:'Messages'};
    window.spSidebarNav = function(name, btn){
        document.querySelectorAll('.sb-item').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        document.querySelectorAll('.sp-tab').forEach(function(t){ t.classList.remove('active'); });
        var ht = document.querySelector('.sp-tab[data-tab="'+name+'"]');
        if(ht) ht.classList.add('active');
        document.querySelectorAll('.sp-panel').forEach(function(p){ p.classList.remove('active'); });
        var panel = document.getElementById('panel-'+name);
        if(panel) panel.classList.add('active');
        var title = document.getElementById('sp-page-title');
        if(title) title.textContent = pageNames[name] || name;
        if(name==='msgs') spLoadConvs();
    };
    // restore collapsed state
    (function(){
        if(localStorage.getItem('sp-sb')==='1'){
            document.getElementById('sp-sidebar').classList.add('collapsed');
            document.body.classList.add('sb-collapsed');
            var ic = document.getElementById('sb-toggle-icon');
            if(ic) ic.className='fas fa-chevron-right';
        }
    })();

    /* ── TABS ── */
    window.spTab = function(name, btn){
        document.querySelectorAll('.sp-panel').forEach(function(p){p.classList.remove('active')});
        document.querySelectorAll('.sp-tab').forEach(function(b){b.classList.remove('active')});
        document.getElementById('panel-'+name).classList.add('active');
        if(btn && btn.classList) btn.classList.add('active');
        // sync sidebar active item
        document.querySelectorAll('.sb-item').forEach(function(b){ b.classList.remove('active'); });
        if(name==='msgs') spLoadConvs();
    };

    /* ── DOC MODAL ── */
    window.spOpenDocModal = function(key){
        document.getElementById('modal-doc').classList.add('open');
        setTimeout(function(){ document.getElementById('pick-'+key).querySelector('input').click(); }, 300);
    };
    window.spDocPicked = function(key, input){
        var done = document.getElementById('pick-done-'+key);
        var card = document.getElementById('pick-'+key);
        if(input.files.length){
            done.style.display = 'block';
            card.classList.add('selected');
        } else {
            done.style.display = 'none';
            card.classList.remove('selected');
        }
    };

    /* ── FAB / floating chat ── */
    window.spFabClick = function(){
        st.fabOpen = !st.fabOpen;
        var win = document.getElementById('sp-fchat');
        win.classList.toggle('open', st.fabOpen);
        if(st.fabOpen) spFchatLoadConvs();
    };

    function spFchatLoadConvs(){
        fetch(URL_CONVS, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                st.convs = data.conversations || [];
                renderFchatConvs();
                clearTimeout(st.pollT);
                st.pollT = setTimeout(spFchatLoadConvs, 8000);
            });
    }

    function renderFchatConvs(){
        var el = document.getElementById('sp-fchat-convs');
        st.totalUnread = 0;
        if(!st.convs.length){
            // If no conversation yet and we have a responsible person, offer to start one
            var html = '<div class="sp-empty"><i class="fas fa-comments"></i><p>No conversations yet.</p>';
            @if($responsibleId)
            html += '<button onclick="spStartConvWithAdmin()" style="margin:10px auto 0;display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border:none;border-radius:9px;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit"><i class=\"fas fa-paper-plane\"></i> Message your advisor</button>';
            @endif
            html += '</div>';
            el.innerHTML = html;
            updateFab(); return;
        }
        el.innerHTML = st.convs.map(function(c){
            st.totalUnread += (c.unread_count||0);
            var name = getFchatName(c);
            var last = c.last_message ? (c.last_message.body||'📎') : 'No messages yet';
            var unread = c.unread_count > 0 ? '<span style="background:#ef4444;color:#fff;font-size:10px;font-weight:700;min-width:16px;height:16px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;margin-left:auto">'+c.unread_count+'</span>' : '';
            return '<div class="sp-fchat-conv'+(st.convId===c.id?' active':'')+'" onclick="spFchatOpen('+c.id+',\''+esc(name)+'\')">'
                + '<div class="sp-fchat-av">'+initials(name)+'</div>'
                + '<div style="flex:1;min-width:0"><div class="sp-fchat-nm">'+esc(name)+'</div><div class="sp-fchat-ls">'+esc(last)+'</div></div>'
                + unread + '</div>';
        }).join('');
        updateFab();
    }

    function getFchatName(c){
        if(c.name) return c.name;
        var users = (c.users||[]);
        var studs = (c.students||[]).filter(function(s){return s.id!==ME});
        return users.map(function(u){return u.name})
                    .concat(studs.map(function(s){return (s.first_name||'')+' '+(s.last_name||'')}))
                    .join(', ') || 'Unknown';
    }

    function updateFab(){
        var b = document.getElementById('sp-fab-badge');
        if(st.totalUnread > 0){ b.style.display='flex'; b.textContent=st.totalUnread>99?'99+':st.totalUnread; }
        else { b.style.display='none'; }
    }

    window.spFchatOpen = function(id, name){
        st.convId = id;
        // Switch panels
        document.getElementById('sp-fchat-list').style.display = 'none';
        var chat = document.getElementById('sp-fchat-chat');
        chat.style.display = 'flex';
        document.getElementById('sp-fchat-chat-name').textContent = name;
        document.getElementById('sp-fchat-chat-av').textContent   = initials(name);
        document.getElementById('sp-fchat-title').textContent     = name;
        document.getElementById('sp-fchat-msgs').innerHTML = '<div class="sp-empty"><i class="fas fa-spinner fa-spin"></i></div>';
        clearTimeout(st.msgT);
        spFchatLoadMsgs();
    };

    window.spFchatBack = function(){
        st.convId = null;
        clearTimeout(st.msgT);
        document.getElementById('sp-fchat-chat').style.display = 'none';
        document.getElementById('sp-fchat-list').style.display = 'flex';
        document.getElementById('sp-fchat-title').textContent  = 'Messages';
        spFchatLoadConvs();
    };

    function spFchatLoadMsgs(){
        if(!st.convId) return;
        fetch('/chat/conversations/'+st.convId+'/messages', {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                renderFchatMsgs(data.messages||[]);
                clearTimeout(st.msgT);
                st.msgT = setTimeout(spFchatLoadMsgs, 4000);
            });
    }

    function renderFchatMsgs(msgs){
        var el = document.getElementById('sp-fchat-msgs');
        if(!msgs.length){
            el.innerHTML = '<div class="sp-empty"><i class="fas fa-comment-slash"></i><p>No messages yet.</p></div>';
            return;
        }
        var atBot = el.scrollHeight - el.scrollTop - el.clientHeight < 60;
        el.innerHTML = msgs.map(function(m){
            var mine = m.student_id === ME;
            var cls  = mine ? 'mine' : 'theirs';
            var time = m.created_at ? new Date(m.created_at).toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'}) : '';
            var body = m.body ? esc(m.body) : '<em style="opacity:.5">📎</em>';
            return '<div class="sp-bw '+cls+'"><div class="sp-bubble '+cls+'">'+body+'<div class="sp-btime">'+time+'</div></div></div>';
        }).join('');
        if(atBot) el.scrollTop = el.scrollHeight;
    }

    window.spFchatSend = function(){
        var inp  = document.getElementById('sp-fchat-inp');
        var body = inp.value.trim();
        if(!body || !st.convId) return;
        inp.value = '';
        var fd = new FormData();
        fd.append('conversation_id', st.convId);
        fd.append('body', body);
        fetch(URL_SEND, {method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(function(r){return r.json()})
            .then(function(){ spFchatLoadMsgs(); });
    };

    /* start conversation with admin/advisor */
    window.spStartConvWithAdmin = function(){
        if(!RESP_ID) return;
        fetch(URL_CREATE, {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({user_id: RESP_ID, type:'user'})
        })
        .then(function(r){return r.json()})
        .then(function(data){
            spFchatLoadConvs();
            setTimeout(function(){ spFchatOpen(data.conversation.id, 'Advisor'); }, 500);
        });
    };

    /* ── INLINE MESSAGES PANEL (tab) ── */
    function spLoadConvs(){
        fetch(URL_CONVS, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                st.convs = data.conversations || [];
                renderConvs();
            });
    }

    function renderConvs(){
        var el = document.getElementById('sp-convs-inner');
        st.totalUnread = 0;
        if(!st.convs.length){
            var html = '<div class="sp-empty"><i class="fas fa-comments"></i><p>No conversations yet.</p>';
            @if($responsibleId)
            html += '<button onclick="spStartAndOpen()" style="margin:10px auto 0;display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border:none;border-radius:9px;background:linear-gradient(135deg,#1a6bff,#0a3d99);color:#fff;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit"><i class=\"fas fa-paper-plane\"></i> Message your advisor</button>';
            @endif
            html += '</div>';
            el.innerHTML = html;
            updateFab(); return;
        }
        el.innerHTML = st.convs.map(function(c){
            st.totalUnread += (c.unread_count||0);
            var name = getFchatName(c);
            var last = c.last_message ? (c.last_message.body||'📎') : '';
            return '<div class="sp-msg-item'+(st.convId===c.id?' active':'')+'" onclick="spOpenConv('+c.id+',\''+esc(name)+'\')">'
                + '<div class="sp-msg-name">'+esc(name)+'</div>'
                + '<div class="sp-msg-last">'+esc(last)+'</div>'
                + '</div>';
        }).join('');
        updateFab();
    }

    window.spStartAndOpen = function(){
        if(!RESP_ID) return;
        fetch(URL_CREATE, {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({user_id: RESP_ID, type:'user'})
        })
        .then(function(r){return r.json()})
        .then(function(data){
            spOpenConv(data.conversation.id, 'Advisor');
        });
    };

    window.spOpenConv = function(id, name){
        st.convId = id;
        document.getElementById('sp-chat-cname').textContent = name;
        document.getElementById('sp-chat-cname').style.color = 'var(--text)';
        var av = document.getElementById('sp-chat-av');
        av.style.display = 'flex'; av.textContent = initials(name);
        document.querySelectorAll('.sp-msg-item').forEach(function(el,i){
            el.classList.toggle('active', st.convs[i] && st.convs[i].id === id);
        });
        document.getElementById('sp-bubbles').innerHTML = '<div class="sp-empty"><i class="fas fa-spinner fa-spin"></i></div>';
        clearTimeout(st.msgT);
        spLoadMsgs();
    };

    function spLoadMsgs(){
        if(!st.convId) return;
        fetch('/chat/conversations/'+st.convId+'/messages', {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                renderMsgs(data.messages||[]);
                clearTimeout(st.msgT);
                st.msgT = setTimeout(spLoadMsgs, 4000);
            });
    }

    function renderMsgs(msgs){
        var el = document.getElementById('sp-bubbles');
        if(!msgs.length){ el.innerHTML='<div class="sp-empty"><i class="fas fa-comment-slash"></i><p>No messages yet. Say hello!</p></div>'; return; }
        var atBot = el.scrollHeight - el.scrollTop - el.clientHeight < 60;
        el.innerHTML = msgs.map(function(m){
            var mine = m.student_id===ME;
            var cls  = mine?'mine':'theirs';
            var time = m.created_at?new Date(m.created_at).toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'}):'';
            var body = m.body?esc(m.body):'<em style="opacity:.5">📎 Attachment</em>';
            return '<div class="sp-bw '+cls+'"><div class="sp-bubble '+cls+'">'+body+'<div class="sp-btime">'+time+'</div></div></div>';
        }).join('');
        if(atBot) el.scrollTop = el.scrollHeight;
    }

    window.spSend = function(){
        var inp = document.getElementById('sp-msg-input');
        var body = inp.value.trim();
        if(!body || !st.convId) return;
        inp.value = '';
        var fd = new FormData();
        fd.append('conversation_id', st.convId);
        fd.append('body', body);
        fetch(URL_SEND, {method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(function(r){return r.json()})
            .then(function(){ spLoadMsgs(); });
    };

    /* ── helpers ── */
    function initials(name){ return (name||'?').split(' ').slice(0,2).map(function(w){return w[0]||''}).join('').toUpperCase(); }
    function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    /* poll badge always */
    setTimeout(function(){
        fetch(URL_CONVS, {headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function(r){return r.json()})
            .then(function(data){
                st.convs = data.conversations||[];
                st.totalUnread = 0;
                st.convs.forEach(function(c){ st.totalUnread+=(c.unread_count||0); });
                updateFab();
            });
    }, 3000);
})();
</script>
</div>{{-- /#sp-main --}}
</body>
</html>
