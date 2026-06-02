<style>
    :root {
        /* AdminLTE color scheme */
        --primary-color: #3c8dbc;      /* Main blue */
        --primary-hover: #367fa9;      /* Darker blue */
        --secondary-color: #f4f4f4;    /* Light gray for backgrounds */
        --info-color: #00c0ef;         /* Light blue */
        --success-color: #00a65a;      /* Green */
        --warning-color: #f39c12;      /* Orange/yellow */
        --danger-color: #dd4b39;       /* Red */
        --gray-light: #d2d6de;         /* Light gray for borders */
        --gray-dark: #777;             /* Dark gray for text */
        --sidebar-bg: #222d32;         /* Dark sidebar background */
        --light-text: #f9f9f9;         /* Light text */
        --dark-text: #333;             /* Dark text */
        --box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    body {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #ecf0f5;
        color: var(--dark-text);
        font-size: 14px;
        line-height: 1.42857143;
    }

    /* Navbar styling */
    .navbar {
        background-color: var(--primary-color) !important;
        box-shadow: var(--box-shadow);
        border: none;
        padding: 0;
        min-height: 50px;
        border-radius: 0;
    }

    .navbar-brand {
        font-weight: 700;
        color: var(--light-text) !important;
        padding: 15px;
        height: 50px;
        font-size: 18px;
        line-height: 20px;
    }

    .nav-link {
        color: var(--light-text) !important;
        padding: 15px;
        transition: background 0.3s;
        line-height: 20px;
    }

    .nav-link:hover, .nav-link.active {
        background: var(--primary-hover) !important;
        color: var(--light-text) !important;
    }

    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.1);
        margin: 8px 15px;
    }

    .dropdown-menu {
        background-color: #fff;
        border: 1px solid var(--gray-light);
        border-radius: 3px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        padding: 5px 0;
    }

    .dropdown-item {
        color: var(--dark-text);
        padding: 10px 20px;
        font-size: 14px;
    }

    .dropdown-item:hover {
        background-color: var(--secondary-color);
        color: var(--dark-text);
    }

    .dropdown-divider {
        border-top-color: var(--gray-light);
        margin: 5px 0;
    }

    /* Logo styling */
    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo {
        width: 36px;
        height: 36px;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-circle {
        width: 100%;
        height: 100%;
        background: var(--primary-color);
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-inner {
        width: 60%;
        height: 60%;
        background-color: #fff;
        border-radius: 3px;
        position: relative;
    }

    .app-name {
        font-weight: 700;
        color: var(--light-text);
        letter-spacing: 0.5px;
    }

    /* Card styling (Box in AdminLTE) */
    .card {
        border: none;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        background-color: #fff;
        position: relative;
    }

    .card-header {
        background-color: #fff;
        color: var(--dark-text);
        border-radius: 3px 3px 0 0 !important;
        padding: 10px;
        border-bottom: 1px solid var(--gray-light);
        font-weight: 600;
    }

    .card-header h5 {
        margin-bottom: 0;
        font-weight: 600;
        font-size: 16px;
    }
    
    .card-body {
        background-color: #fff;
        padding: 10px;
    }
    
    .card-footer {
        background-color: var(--secondary-color);
        border-top: 1px solid var(--gray-light);
        padding: 10px;
        border-radius: 0 0 3px 3px;
    }

    /* Button styling */
    .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-hover) !important;
        color: white;
    }

    .btn-primary:hover, .btn-primary:focus {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
    }

    .btn-danger {
        background-color: var(--danger-color) !important;
        border-color: #d73925 !important;
    }

    .btn-danger:hover, .btn-danger:focus {
        background-color: #d73925 !important;
        border-color: #d73925 !important;
    }

    .btn-outline-primary {
        color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
    }

    .btn-outline-secondary {
        color: var(--gray-dark) !important;
        border-color: var(--gray-light) !important;
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color) !important;
        color: var(--dark-text) !important;
    }
    
    .btn-secondary {
        background-color: var(--gray-light) !important;
        border-color: var(--gray-light) !important;
        color: var(--dark-text);
    }

    /* Badge styling */
    .badge.bg-danger {
        background-color: var(--danger-color) !important;
    }

    .badge.bg-primary {
        background-color: var(--primary-color) !important;
    }

    .badge.bg-secondary {
        background-color: var(--gray-dark) !important;
    }

    .badge.bg-info {
        background-color: var(--info-color) !important;
    }

    .badge.bg-success {
        background-color: var(--success-color) !important;
    }

    .badge.bg-warning {
        background-color: var(--warning-color) !important;
    }

    /* User and conversation list styling */
    .user-list .list-group-item, 
    .conversation-list .list-group-item {
        border-left: none;
        border-right: none;
        transition: all 0.2s;
        background-color: #fff;
        color: var(--dark-text);
        border-color: var(--gray-light);
        padding: 10px 15px;
    }

    .user-list .list-group-item:hover,
    .conversation-list .list-group-item:hover {
        background-color: var(--secondary-color);
    }

    .conversation-list .active {
        background-color: rgba(60, 141, 188, 0.1) !important;
        border-left: 3px solid var(--primary-color) !important;
    }

    /* Chat area styling */
    .chat-body {
        height: 450px;
        overflow-y: auto;
        padding: 15px;
        background-color: #fff;
        border-radius: 0;
        border-top: 1px solid var(--gray-light);
        border-bottom: 1px solid var(--gray-light);
    }

    /* Message bubbles - Direct Messages style from AdminLTE */
    .chat-body .bg-primary {
        background-color: var(--primary-color) !important;
        border-radius: 3px;
        padding: 10px 15px;
        display: inline-block;
        max-width: 75%;
        word-break: break-word;
        margin-bottom: 10px;
    }

    .chat-body .bg-light {
        background-color: var(--secondary-color) !important;
        color: var(--dark-text);
        border-radius: 3px;
        padding: 10px 15px;
        display: inline-block;
        max-width: 75%;
        word-break: break-word;
        margin-bottom: 10px;
        border: 1px solid var(--gray-light);
    }

    /* Form styling */
    .form-control {
        border-radius: 3px;
        border: 1px solid var(--gray-light);
        padding: 6px 12px;
        background-color: #fff;
        color: var(--dark-text);
        height: 34px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: none;
        background-color: #fff;
        color: var(--dark-text);
    }

    .input-group .form-control {
        border-radius: 3px 0 0 3px;
    }

    .input-group .btn {
        border-radius: 0 3px 3px 0;
        padding: 6px 12px;
    }

    /* User avatar styling */
    .rounded-circle {
        border: 1px solid var(--gray-light);
    }

    .bg-primary.rounded-circle {
        background-color: var(--primary-color) !important;
        border: none;
    }

    /* Voice message player */
    .chat-body audio {
        max-width: 250px;
        height: 34px;
        border-radius: 3px;
        background-color: var(--secondary-color);
    }

    /* Recording button styles */
    #record-voice, #stop-recording {
        display: flex;
        align-items: center;
        border-radius: 3px;
    }

    #record-voice i, #stop-recording i {
        margin-right: 5px;
    }

    /* Recording animation */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    #recording-status {
        animation: pulse 1.5s infinite;
        color: var(--danger-color);
    }

    /* Image thumbnail in chat */
    .chat-body .img-thumbnail {
        max-height: 200px;
        cursor: pointer;
        border-radius: 3px;
        padding: 3px;
        background-color: #fff;
        border: 1px solid var(--gray-light);
    }
    
    /* Message transition effect */
    .message-item {
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Scrollbar styling */
    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-track {
        background: var(--secondary-color);
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: var(--gray-light);
        border-radius: 3px;
    }

    .chat-body::-webkit-scrollbar-thumb:hover {
        background: var(--gray-dark);
    }

    /* Footer styling */
    footer {
        background-color: #fff !important;
        color: var(--dark-text);
        padding: 10px 0;
        margin-top: 20px;
        border-top: 1px solid var(--gray-light);
        font-size: 13px;
    }

    footer a {
        color: var(--primary-color) !important;
        text-decoration: none;
    }

    footer a:hover {
        color: var(--primary-hover) !important;
        text-decoration: underline;
    }
    
    /* Form elements in AdminLTE style */
    .form-check-input {
        margin-top: 2px;
        background-color: #fff;
        border: 1px solid var(--gray-light);
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .modal-content {
        background-color: #fff;
        color: var(--dark-text);
        border-radius: 3px;
        border: 1px solid var(--gray-light);
    }
    
    .modal-header, .modal-footer {
        padding: 15px;
        border-color: var(--gray-light);
    }
    
    .modal-title {
        font-weight: 600;
        font-size: 18px;
    }
    
    .text-muted {
        color: var(--gray-dark) !important;
    }
    
    /* Box tools - AdminLTE specific components */
    .box-tools {
        position: absolute;
        right: 10px;
        top: 5px;
    }
    
    /* Small boxes / widgets */
    .small-box {
        border-radius: 3px;
        position: relative;
        display: block;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    
    .small-box > .inner {
        padding: 10px;
    }
    
    .small-box > .small-box-footer {
        position: relative;
        text-align: center;
        padding: 3px 0;
        color: #fff;
        background: rgba(0,0,0,0.1);
        display: block;
        text-decoration: none;
    }
    
    /* Info box */
    .info-box {
        display: block;
        min-height: 90px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 3px;
        margin-bottom: 15px;
    }
    
    .info-box-icon {
        display: block;
        float: left;
        height: 90px;
        width: 90px;
        text-align: center;
        font-size: 45px;
        line-height: 90px;
        background-color: var(--gray-light);
        border-radius: 3px 0 0 3px;
    }
    
    .info-box-content {
        padding: 5px 10px;
        margin-left: 90px;
    }
    
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
    
    /* Responsive styles for mobile */
    @media (max-width: 767.98px) {
        .chat-sidebar {
            margin-bottom: 20px;
        }
        
        .chat-body {
            height: 350px;
        }
        
        .btn {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .form-control {
            height: 30px;
            font-size: 13px;
        }
    }
    
    /* AdminLTE layout adjustments */
    .content-wrapper {
        background-color: #ecf0f5;
        min-height: 100vh;
        padding: 15px;
    }
    
    .main-header {
        position: relative;
        max-height: 100px;
        z-index: 1030;
    }
    
    .main-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 50px;
        min-height: 100%;
        width: 230px;
        z-index: 810;
        background-color: var(--sidebar-bg);
    }
    
    /* Adjustments for better mobile experience */
    @media (max-width: 767px) {
        .main-sidebar {
            padding-top: 100px;
        }
    }
</style>