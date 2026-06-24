<style>
/* ── side card ── */
.sa-side-card { background:#fff; border-radius:14px; overflow:hidden; }
.sa-side-body  { padding:24px 20px; display:flex; flex-direction:column; align-items:center; text-align:center; }
.sa-preview-avatar {
    width:80px; height:80px; border-radius:50%;
    background:linear-gradient(135deg,#1a6bff,#0a3d99); color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:32px; font-weight:700; margin-bottom:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.12); background-size:cover;
}
.sa-preview-name  { font-size:16px; font-weight:700; color:#1e293b; margin-bottom:4px; }
.sa-preview-email { font-size:12px; color:#94a3b8; margin-bottom:10px; }
.sa-type-pill { display:inline-flex; align-items:center; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
.sa-pill-ind { background:#dbeafe; color:#2563eb; }
.sa-pill-com { background:#fef9c3; color:#ca8a04; }
.sa-preview-meta { text-align:left; border-top:1px solid #f1f5f9; padding-top:14px; margin-top:14px; }
.sa-meta-row { display:flex; justify-content:space-between; font-size:12px; padding:5px 0; border-bottom:1px solid #f8fafc; }
.sa-meta-lbl { color:#94a3b8; display:flex; align-items:center; gap:5px; }
.sa-meta-val { color:#1e293b; font-weight:600; }

/* ── form cards ── */
.sa-card { background:#fff; border-radius:14px; overflow:hidden; }
.sa-card-hd { padding:13px 20px; background:linear-gradient(135deg,#1a6bff,#0a3d99); color:#fff; font-size:13px; font-weight:700; }
.sa-card-bd { padding:20px; }

/* inputs */
.sa-label { font-size:12px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.4px; margin-bottom:6px; display:block; }
.sa-input-wrap { position:relative; display:flex; align-items:center; }
.sa-ico { position:absolute; left:12px; color:#94a3b8; font-size:13px; z-index:1; }
.sa-input {
    width:100%; padding:9px 12px 9px 36px;
    border:1.5px solid #e2e8f0; border-radius:9px;
    font-size:13px; color:#1e293b; outline:none; background:#f8fafc;
    transition:border-color .15s;
}
.sa-input:focus { border-color:#1a6bff; background:#fff; }
.sa-select { appearance:none; cursor:pointer; padding-right:28px; }
.sa-err { font-size:11px; color:#dc2626; margin-top:4px; }

/* password eye */
.sa-pw-eye { position:absolute; right:10px; background:none; border:none; color:#94a3b8; cursor:pointer; font-size:13px; }
.sa-pw-eye:hover { color:#475569; }

/* file pick */
.sa-file-pick-wrap { display:flex; align-items:center; }
.sa-file-btn {
    display:inline-flex; align-items:center; gap:6px; cursor:pointer;
    padding:8px 14px; border-radius:9px; font-size:12px; font-weight:600;
    background:#f1f5f9; color:#475569; border:1.5px dashed #cbd5e1;
    transition:background .15s;
}
.sa-file-btn:hover { background:#e2e8f0; }

/* commission table */
.sa-comm-table { background:#f8fafc; border-radius:10px; padding:14px; border:1.5px solid #e2e8f0; }
.sa-comm-head { display:grid; grid-template-columns:1fr 120px 36px; gap:8px; margin-bottom:8px; }
.sa-comm-head span { font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px; padding:0 4px; }
.sa-comm-row { display:grid; grid-template-columns:1fr 120px 36px; gap:8px; margin-bottom:8px; align-items:center; }
.sa-comm-row .sa-input { padding-left:12px; }
.sa-btn-rm { width:32px; height:32px; border-radius:8px; border:none; background:#fee2e2; color:#dc2626; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:12px; }
.sa-btn-rm:disabled { background:#f1f5f9; color:#cbd5e1; cursor:not-allowed; }
.sa-add-row-btn { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:9px; font-size:12px; font-weight:600; background:#dcfce7; color:#16a34a; border:none; cursor:pointer; }
.sa-add-row-btn:hover { background:#bbf7d0; }

/* action buttons */
.sa-btn-cancel {
    padding:9px 18px; border-radius:9px; font-size:13px; font-weight:600;
    border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none;
    display:inline-flex; align-items:center; gap:5px; transition:background .15s;
}
.sa-btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }
.sa-btn-save {
    padding:9px 22px; border-radius:9px; font-size:13px; font-weight:700;
    background:linear-gradient(135deg,#1a6bff,#0a3d99); color:#fff; border:none;
    cursor:pointer; display:inline-flex; align-items:center; gap:5px;
    box-shadow:0 4px 12px rgba(26,107,255,.3); transition:opacity .15s;
}
.sa-btn-save:hover { opacity:.9; }
</style>
