require('materialize-css/dist/js/materialize.min');
const $ = require('jquery');
const randomString = require('random-string');

$(document).ready(function() {
  $('.participant__additional').hide();

  const $collectionHolder = $('div.participants');

  clickEvents($collectionHolder);

  $collectionHolder.data('index', $collectionHolder.find(':input').length);
});

function addTagForm($collectionHolder) {
  const prototype = $collectionHolder.data('prototype');

  const index = $collectionHolder.data('index');

  const newForm = prototype.replace(/__name__/g, index);

  $collectionHolder.data('index', index + 1);

  $('.participants').append(newForm);
  $('.participants').find('.participant:last').hide().slideDown('fast');
}

function clickEvents($collectionHolder) {
  $(".container .alert-success").fadeTo(2000, 500).slideUp(500, function(){
    $(".container .alert-success").slideUp(500);
  });

  $('.participants').on('click', '.participant__toggle-additional-button', function(e) {
    e.preventDefault();
    $(this).parent().parent().parent().find('.participant__additional').slideToggle('slow');

    if($(this).text() === 'Less') {
      $(this).text('More');
      $(this).parent().parent().parent().removeClass('participant--active z-depth-5');
    } else {
      $(this).text('Less');
      $(this).parent().parent().parent().addClass('participant--active z-depth-5');
    }
  });

  $('.participants').on('click', '.participant__username-generate-button', function(e) {
    e.preventDefault();
    $(this).parent().parent().find('.participant__username').val(randomString());
  });

  $('.participants').on('click', '.participant__password-generate-button', function(e) {
    e.preventDefault();
    $(this).parent().parent().find('.participant__password').val(randomString());
  });

  $('.group-form__add-button').on('click', function(e) {
    e.preventDefault();
    addTagForm($collectionHolder);
    $(this).prev().find('.participant__username').val(randomString());
    $(this).prev().find('.participant__password').val(randomString());
    M.updateTextFields();
  });

  $('.participants').on('click', '.participant__remove-button', function(e) {
    e.preventDefault();

    $(this).parent().parent().parent().slideUp('fast', function(e) {
      $(this).remove();
    });

  });

  $('.participants').on('keydown', '.participant__surname', function(e) {
    const keyCode = e.keyCode || e.which;

    if(keyCode === 13) {
      e.preventDefault();
    }

    if ((keyCode === 9 || keyCode === 13) && !$(this).parent().parent().parent().next().is('.participant')) {
      addTagForm($collectionHolder);

      $(this).prev().find('.participant__username').val(randomString());
      $(this).prev().find('.participant__password').val(randomString());
    }
  });
}

