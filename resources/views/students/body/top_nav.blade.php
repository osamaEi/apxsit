<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="" class="nav-link"></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
   

    <!-- Notifications Dropdown Menu -->

    
    <!-- Dark Mode Toggle -->
    <li class="nav-item">
      <button id="theme-toggle" class="btn btn-link nav-link">
        <i class="fas fa-moon"></i> Dark Mode
      </button>
    </li>
 
    <!-- User Menu -->

  </ul>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script>
$(document).ready(function() {
  // Check for saved theme preference or system preference
  const savedTheme = localStorage.getItem('theme') || 
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  
  // Apply theme on initial load
  if (savedTheme === 'dark') {
    $('body').addClass('dark-mode');
    $('#theme-toggle').html('<i class="fas fa-sun"></i> Light Mode');
    applyDarkModeStyles();
  } else {
    $('body').removeClass('dark-mode');
    $('#theme-toggle').html('<i class="fas fa-moon"></i> Dark Mode');
  }
  
  // Toggle theme button
  $('#theme-toggle').click(function() {
    if ($('body').hasClass('dark-mode')) {
      // Switch to light mode
      $('body').removeClass('dark-mode');
      $(this).html('<i class="fas fa-moon"></i> Dark Mode');
      localStorage.setItem('theme', 'light');
      removeDarkModeStyles();
    } else {
      // Switch to dark mode
      $('body').addClass('dark-mode');
      $(this).html('<i class="fas fa-sun"></i> Light Mode');
      localStorage.setItem('theme', 'dark');
      applyDarkModeStyles();
    }
  });
  
  // Function to apply specific dark mode styles to fix visibility issues
  function applyDarkModeStyles() {
    // Base container and card styles
    $('.container, .container-fluid').css('background-color', '#121212');
    $('.info-card').css({
      'background-color': '#1e1e1e',
      'border-color': '#444'
    });
    $('.card-header').css({
      'background-color': '#2c2c2c',
      'border-color': '#444'
    });
    $('.card-header h3').css('color', '#f1f1f1');
    $('.card-body').css({
      'color': '#f1f1f1',
      'background-color': '#1e1e1e'
    });
    
    // Notification items
    $('.notification-item').css({
      'background-color': 'rgba(255, 255, 255, 0.08)',
      'border-color': '#444'
    });
    $('.notification-content h4').css('color', '#f1f1f1');
    $('.notification-content p').css('color', '#ddd');
    
    // Status indicators
    $('.status-indicator-text').css('font-weight', '600');
    $('.text-success').css('color', '#4cd964');
    $('.text-warning').css('color', '#ffcc00');
    $('.text-danger').css('color', '#ff3b30');
    $('.text-info').css('color', '#5ac8fa');
    $('.text-primary').css('color', '#007bff');
    
    // Progress elements
    $('.application-progress').css('background-color', '#1e1e1e');
    $('.progress-steps').css('border-color', '#444');
    $('.progress-step').css('color', '#ddd');
    $('.progress-step .step-icon').css({
      'background-color': '#2c2c2c',
      'border-color': '#666'
    });
    $('.progress-step.completed .step-icon').css({
      'background-color': '#007bff',
      'color': '#fff'
    });
    $('.progress').css('background-color', 'rgba(255, 255, 255, 0.1)');
    
    // Tables
    $('.table').css({
      'color': '#f1f1f1',
      'background-color': 'transparent'
    });
    $('.table-hover tbody tr:hover').css('background-color', 'rgba(255, 255, 255, 0.08)');
    $('.thead-light th').css({
      'background-color': '#2c2c2c',
      'color': '#f1f1f1',
      'border-color': '#444'
    });
    $('.table td, .table th').css('border-color', '#444');
    
    // Document checklist items
    $('.checklist-items').css('background-color', '#1e1e1e');
    $('.checklist-item').css({
      'color': '#f1f1f1',
      'border-color': '#444',
      'background-color': 'rgba(255, 255, 255, 0.05)'
    });
    $('.checklist-item.completed').css('background-color', 'rgba(0, 123, 255, 0.1)');
    $('.checklist-item.optional').css({
      'color': '#aaa',
      'background-color': 'transparent'
    });
    $('.check-icon').css('color', 'inherit');
    $('.fas.fa-check-circle').css('color', '#4cd964');
    $('.fas.fa-times-circle').css('color', '#ff3b30');
    $('.fas.fa-circle-notch').css('color', '#aaa');
    $('.check-status').css('color', '#ddd');
    
    // Contact information
    $('.contact-info').css('background-color', '#1e1e1e');
    $('.contact-item').css({
      'background-color': 'rgba(255, 255, 255, 0.05)',
      'border-color': '#444'
    });
    $('.contact-icon').css('color', '#007bff');
    $('.contact-details h4').css('color', '#f1f1f1');
    $('.contact-details p').css('color', '#ddd');
    
    // Next steps list
    $('.next-steps-list').css('background-color', '#1e1e1e');
    $('.next-step-item').css({
      'border-color': '#444',
      'background-color': 'rgba(255, 255, 255, 0.05)'
    });
    $('.step-number').css({
      'background-color': '#007bff',
      'color': '#fff'
    });
    $('.step-content h4').css('color', '#f1f1f1');
    $('.step-content p').css('color', '#ddd');
    
    // Buttons
    $('.btn-outline-primary').css({
      'color': '#007bff',
      'border-color': '#007bff'
    });
    $('.btn-outline-primary:hover').css({
      'background-color': '#007bff',
      'color': '#fff'
    });
    $('.btn-warning').css({
      'background-color': '#ffcc00',
      'border-color': '#e6b800',
      'color': '#212529'
    });
    $('.btn-info').css({
      'background-color': '#5ac8fa',
      'border-color': '#46b8da',
      'color': '#fff'
    });
    
    // Tab navigation
    $('.nav-tabs').css('border-color', '#444');
    $('.nav-tabs .nav-link').css({
      'color': '#ddd',
      'background-color': 'transparent',
      'border-color': 'transparent'
    });
    $('.nav-tabs .nav-link.active').css({
      'color': '#f1f1f1',
      'background-color': '#2c2c2c',
      'border-color': '#444 #444 #2c2c2c'
    });
    
    // Fix for icon colors in various contexts
    $('.card-header .fas').css('color', '#f1f1f1');
    $('.notification-icon .fas').css('color', 'inherit');
    $('.btn .fas').css('color', 'inherit');
    
    // Links
    $('a:not(.btn)').css('color', '#007bff');
    $('a:not(.btn):hover').css({
      'color': '#0056b3',
      'text-decoration': 'underline'
    });
  }
  
  // Function to remove applied dark mode styles
  function removeDarkModeStyles() {
    // Reset all the custom styles
    $('.container, .container-fluid').css('background-color', '');
    $('.info-card, .card-header, .card-body').css({
      'background-color': '',
      'border-color': '',
      'color': ''
    });
    $('.card-header h3').css('color', '');
    
    // Reset notification items
    $('.notification-item, .notification-content h4, .notification-content p').css({
      'background-color': '',
      'border-color': '',
      'color': ''
    });
    
    // Reset status indicators
    $('.status-indicator-text').css('font-weight', '');
    $('.text-success, .text-warning, .text-danger, .text-info, .text-primary').css('color', '');
    
    // Reset progress elements
    $('.application-progress, .progress-steps, .progress-step, .progress').css({
      'background-color': '',
      'border-color': '',
      'color': ''
    });
    $('.progress-step .step-icon, .progress-step.completed .step-icon').css({
      'background-color': '',
      'border-color': '',
      'color': ''
    });
    
    // Reset tables
    $('.table, .table-hover tbody tr:hover, .thead-light th, .table td, .table th').css({
      'color': '',
      'background-color': '',
      'border-color': ''
    });
    
    // Reset document checklist
    $('.checklist-items, .checklist-item, .checklist-item.completed, .checklist-item.optional').css({
      'color': '',
      'background-color': '',
      'border-color': ''
    });
    $('.check-icon, .fas.fa-check-circle, .fas.fa-times-circle, .fas.fa-circle-notch, .check-status').css('color', '');
    
    // Reset contact information
    $('.contact-info, .contact-item, .contact-icon, .contact-details h4, .contact-details p').css({
      'background-color': '',
      'border-color': '',
      'color': ''
    });
    
    // Reset next steps list
    $('.next-steps-list, .next-step-item, .step-number, .step-content h4, .step-content p').css({
      'background-color': '',
      'border-color': '',
      'color': ''
    });
    
    // Reset buttons
    $('.btn-outline-primary, .btn-outline-primary:hover, .btn-warning, .btn-info').css({
      'color': '',
      'background-color': '',
      'border-color': ''
    });
    
    // Reset tab navigation
    $('.nav-tabs, .nav-tabs .nav-link, .nav-tabs .nav-link.active').css({
      'color': '',
      'background-color': '',
      'border-color': ''
    });
    
    // Reset icon colors
    $('.card-header .fas, .notification-icon .fas, .btn .fas').css('color', '');
    
    // Reset links
    $('a:not(.btn), a:not(.btn):hover').css({
      'color': '',
      'text-decoration': ''
    });
  }
  
  // Also listen for system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    const newTheme = e.matches ? 'dark' : 'light';
    if (!localStorage.getItem('theme')) { // Only auto-switch if user hasn't manually set preference
      if (newTheme === 'dark') {
        $('body').addClass('dark-mode');
        $('#theme-toggle').html('<i class="fas fa-sun"></i> Light Mode');
        applyDarkModeStyles();
      } else {
        $('body').removeClass('dark-mode');
        $('#theme-toggle').html('<i class="fas fa-moon"></i> Dark Mode');
        removeDarkModeStyles();
      }
    }
  });
});
</script>