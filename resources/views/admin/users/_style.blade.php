{{-- Styles used only by the Manage Users pages --}}
<style>
    .page-bar { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
    .filters { display: flex; gap: 8px; flex-wrap: wrap; }
    .filters input, .filters select,
    .user-form input, .user-form select {
        padding: 10px 12px; border: 1px solid #d5e3ed; border-radius: 8px;
        font-size: 14px; color: #17324d; background: white; outline: none;
    }
    .btn { display: inline-block; padding: 10px 16px; border: none; border-radius: 8px; background: #0b5cab; color: white; font-size: 13px; font-weight: bold; text-decoration: none; cursor: pointer; }
    .btn:hover { background: #094a8c; }
    .btn-light { background: #e2f3ff; color: #0b5cab; }
    .btn-light:hover { background: #cfe9fb; }
    .btn-small { padding: 6px 10px; font-size: 12px; }
    .btn-danger { background: #dc3545; }
    .btn-danger:hover { background: #b92c3a; }
    .actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .actions form { margin: 0; }
    .status.off { background: #fdeaea; color: #dc3545; }
    .pager { display: flex; gap: 14px; justify-content: center; align-items: center; margin-top: 16px; font-size: 13px; color: #71869a; }
    .pager a { color: #0b5cab; font-weight: bold; text-decoration: none; }
    .table-wrap { overflow-x: auto; }
    .user-form { max-width: 620px; }
    .user-form .form-group { margin-bottom: 16px; }
    .user-form label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: bold; }
    .user-form input[type="text"], .user-form input[type="email"], .user-form input[type="password"], .user-form select { width: 100%; }
    .check { display: flex; align-items: center; gap: 8px; font-weight: normal; }
    .check input { width: auto; }
    .hint { color: #71869a; font-size: 12px; margin-bottom: 12px; }
    .errors { padding: 12px 12px 12px 30px; margin-bottom: 18px; background: #fdeaea; color: #dc3545; border-radius: 8px; font-size: 13px; }
</style>
