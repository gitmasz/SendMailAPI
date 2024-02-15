$(document).ready(function () {
  $("form.ajax-form").on("keyup keypress", function (e) {
    var keyCode = e.which || e.keyCode || 0;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  $("form.ajax-form input[type='text']").val("");
  $("form.ajax-form input[type='number']").val("");
  $("form.ajax-form input[type='email']").val("");
  $("form.ajax-form input[type='tel']").val("");
  $("form.ajax-form textarea").val("");
  $("form.ajax-form select option:selected").prop("selected", false)
  $("form.ajax-form input[type='checkbox']").prop("checked", false);
  $("form.ajax-form input[type='radio']").prop("checked", false);

  $.validator.addMethod("fullEmail", function (value) {
    if (value != '') { return value.match(/^[\-0-9a-zA-Z\.\+_]+@[\-0-9a-zA-Z\.\+_]+\.[a-zA-Z]{2,5}$/); }
    else { return true };
  }, 'Enter valid email address');
  $.validator.addMethod("maxOneHundred", function (value) {
    if (value != '') { return value.match(/^.{1,100}$/); }
    else { return true };
  }, 'Enter no more than 100 characters');

  var testMailRules = new Object();
  testMailRules['fullName'] = { required: true, maxOneHundred: true };
  testMailRules['email'] = { required: true, email: false, fullEmail: true };
  testMailRules['receiver'] = { required: true, email: false, fullEmail: true };
  testMailRules['regulationConsent'] = { required: true };

  $("form.test-mail").validate({
    rules: testMailRules,
    errorElement: 'span',
    submitHandler: function () {
      var apiUrl = 'mail-api/send-mail-api.php';
      var formData = $("form.test-mail").serialize().replace(/[^&]+=\.?(?:&|$)/g, '').replace(/\&$/g, '');
      var successBlock = 'div.form-container';
      var successInfo = '<div class="response-message"><div class="response-message__title">Thank you for your email.</div><div class="response-message__information">We kindly inform you that the message has been sent successfully.</div></div>';
      sendMailAPI($("form.test-mail"), apiUrl, formData, successBlock, successInfo);
    }
  });

  $(document).on('click', 'form.test-mail span.error', function () {
    $(this).hide();
  });
});
function sendMailAPI(formSelector, apiUrl, formData, successBlock, successInfo) {
  var submitTXT = formSelector.find('input[type=submit]').val();
  formSelector.find('div.form-info').remove();
  formSelector.find('input[type=submit]').attr('disabled', true);
  formSelector.find('input[type=submit]').addClass('animated');
  formSelector.find('input[type=submit]').val('•');
  var errorinfo = '<div class="form-info error">[status] [errorMessage]</div>';
  $.ajax({
    url: apiUrl,
    data: formData,
    type: 'post',
    dataType: 'json',
    success: function (data) {
      setTimeout(function () {
        formSelector.find('div.form-info').remove();
        formSelector.find('input[type=submit]').removeAttr('disabled');
        formSelector.find('input[type=submit]').removeClass('animated');
        formSelector.find('input[type=submit]').val(submitTXT);
        formSelector.closest(successBlock).html($(successInfo).hide().fadeIn("slow"));
      }, 600);
    },
    error: function (data) {
      setTimeout(function () {
        formSelector.find('div.form-info').remove();
        formSelector.find('input[type=submit]').removeAttr('disabled');
        formSelector.find('input[type=submit]').removeClass('animated');
        formSelector.find('input[type=submit]').val(submitTXT);
        try {
          formSelector.find('input[type=submit]').before($(errorinfo.replace('[status]', data.responseJSON["status"]).replace('[errorMessage]', data.responseJSON["message"])));
        } catch (error) {
          formSelector.find('input[type=submit]').before($(errorinfo.replace('[status]', 'Error: ').replace('[errorMessage]', 'Unknown error!')));
        }
      }, 600);
    }
  });
};