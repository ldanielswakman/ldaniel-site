// Contact Form Dialog
$(document).ready(function() {

  // Continue button
  $('.dialog--contact [data-action="continue"]').on('click touchstart', function (e) {
    e.preventDefault();
    bubbleRun($(this).closest('.bubble-wrap'));
  });
  // Continue key combination (Shift + Enter)
  $('.dialog--contact .bubble').find('textarea, input').on('keypress keydown', function(e) {
    if(e.keyCode == 13 && e.shiftKey == true) {
      e.preventDefault();
      bubbleRun($(this).closest('.bubble-wrap'));
    }
  });
  // Form submission handling
  $('.dialog--contact form').on('submit', function(e) {
    e.preventDefault();
    postContactForm($(this));
  });
  // Dialog close button
  $('.dialog--contact [data-action="dialog-close"]').on('click touchstart', function (e) {
    closeContactForm();
  });

});
// Dialog close key (Esc)
$(document).on('keydown', function(e) {
  if(e.keyCode == 27) { closeContactForm() }
});

// open Contact From
function openContactForm() {
  $('body').addClass('dialogIsActive');
  setTimeout(function() {
    bubbleRun( $('.dialog--contact').find('.bubble-wrap#bubble1') );
  }, 500);
}

// close Contact Form
function closeContactForm() {
  $('.bubble-wrap').removeClass('isLoaded');
  $('form.conversation').removeClass('isSending');
  $('body').removeClass('dialogIsActive');
}

// 'Run' Bubble
function bubbleRun($obj, stophere) {
  nextTimeout = 0;
  $form = $obj.closest('form');

  if(!$obj.hasClass('isLoaded')) {
    $obj.addClass('isLoaded');
    if($form[0]) {
      $form.scrollTop($form[0].scrollHeight);
    }
    $obj.find('textarea, input').first().focus();
    nextTimeout = 1500;
  }

  if($obj.attr('data-autoplay') == 'false') {
    stophere = true;
  }

  if(stophere !== true) {
    $next = $obj.next();
    if(!$next.hasClass('bubble-wrap--right')) {
      setTimeout( function() { bubbleRun($next); }, nextTimeout);
    } else {
      setTimeout( function() { bubbleRun($next, true); }, nextTimeout);
    }
  }
}

// post Contact Form
function postContactForm($form) {
  if($form) {
    // Variables
    url = $form.attr('action');
    form_data = $form.serialize();
    $response_div = $form.find('#bubble_formresponse');
    $success_div = $form.find('#bubble_success');

    // Set states
    $form.addClass('isSending');
    $form.find('.field').removeClass('hasError');
    $response_div.find('.bubble__content').html('');
    bubbleRun($form.find('[type="submit"]').closest('.bubble-wrap'), true);

    console.log('submitting form...');

    // Make Ajax request
    $.post(url, form_data, function(data) {

      console.log('response! success = ' + data['success']);

      if(data['success'] == false) {

        $form.removeClass('isSending');

        error_msg = '';

        $.each(data['errors'], function(i, item) {
          $form.find('[name="' + i + '"]').addClass('hasError');
          error_msg += item[0];
        });
        $response_div.find('.bubble__content').html(error_msg);
        $response_div.addClass('isLoaded');

      } else if (data['success'] === true) {

        $form.find('#bubble_formsending, #bubble_formresponse').removeClass('isLoaded');
        $form.removeClass('isSending').addClass('isSuccess');
        bubbleRun($form.find('#bubble_success'));

      }
    });
  }
}
