import * as M from 'materialize-css/dist/js/materialize';
const $ = require('jquery');
const randomString = require('random-string');

$(document).ready(function() {

  $('.participant__additional').hide();

  const addButton = $('<div class="clearfix"><i class="group-form__add-button btn-floating btn-large blue darken-3 material-icons">add</i></div>');

  const collectionHolder = $('div.participants');

  collectionHolder.append(addButton);

  events(collectionHolder, addButton);

  collectionHolder.data('index', collectionHolder.find(':input').length);
});

function events(collectionHolder, addButton) {
  $(".container .alert").fadeTo(2000, 500).slideUp(500, function(){
    $(".container .alert").slideUp(500);
  });

  $('.participants').on('click', '.participant__toggle-additional-button', function(e) {
    e.preventDefault();
    $(this).parents().eq(2).find('.participant__additional').slideToggle('slow');

    if($(this).text() === 'Less') {
      $(this).text('More');
      $(this).parents().eq(2).removeClass('participant--active z-depth-5');
    } else {
      $(this).text('Less');
      $(this).parents().eq(2).addClass('participant--active z-depth-5');
      M.AutoInit();
    }
  });

  $('.participants').on('click', '.participant__username-generate-button', function(e) {
    e.preventDefault();
    $(this).parents().eq(1).find('.participant__username').val(randomString());
  });

  $('.participants').on('click', '.participant__password-generate-button', function(e) {
    e.preventDefault();
    $(this).parents().eq(1).find('.participant__password').val(randomString());
  });

  $('.group-form__add-button').on('click', function(e) {
    e.preventDefault();
    addTagForm(collectionHolder, addButton);
    $(this).parent().prev().find('.participant__username').val(randomString());
    $(this).parent().prev().find('.participant__password').val(randomString());
    M.updateTextFields();
  });

  $('.participants').on('click', '.participant__remove-button', function(e) {
    e.preventDefault();

    $(this).parent().parent().parent().slideUp('fast', function(e) {
      $(this).remove();
    });
  });

  $('.participants').on('keydown', '.participant__name', function(e) {
    const keyCode = e.keyCode || e.which;

    if(keyCode === 13) {
      e.preventDefault();
      $(this).parents().eq(1).find('.participant__surname').focus();
    }
  });

  $('.participants').on('keydown', '.participant__surname', function(e) {
    const keyCode = e.keyCode || e.which;

    if(keyCode === 13 || keyCode === 9) {
      e.preventDefault();
    }

    if ((keyCode === 9 || keyCode === 13) && !$(this).parent().parent().parent().next().is('.participant')) {
      addTagForm(collectionHolder, addButton);
      $(this).parent().prev().find('.participant__username').val(randomString());
      $(this).parent().prev().find('.participant__password').val(randomString());
      $(this).parents().eq(2).next('.participant').find('.participant__name').focus();
    } else if (keyCode === 9 || keyCode === 13) {
      $(this).parents().eq(2).next('.participant').find('.participant__name').focus();
    }
  });
}

function addTagForm(collectionHolder, addButton) {
  const prototype = collectionHolder.data('prototype');

  const index = collectionHolder.data('index');

  const newForm = prototype.replace(/__name__/g, index);

  collectionHolder.data('index', index + 1);

  addButton.before(newForm);

  $('.participants').find('.participant:last').hide().slideDown('fast');
}

