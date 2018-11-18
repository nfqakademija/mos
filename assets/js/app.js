require('bootstrap');
const $ = require('jquery');
const randomString = require('random-string');

$(document).ready(function() {
  $('.participants').on('click', '.participant__toggle-additional-button', function(e) {
    e.preventDefault();
    $(this).parent().find('.participant__additional').toggle();
  });

  $('.participants').on('click', '.participant__username-generate-button', function(e) {
    e.preventDefault();
    $(this).parent().find('.participant__username').val(randomString());
  });

  $('.participants').on('click', '.participant__password-generate-button', function(e) {
    e.preventDefault();
    console.log($(this).html());
    $(this).parent().find('.participant__password').val(randomString());
  });

  const $collectionHolder = $('div.participants');
  const $addParticipantLink = $('.participant__add-button');

  $collectionHolder.data('index', $collectionHolder.find(':input').length);

  $('.participants').on('click', '.participant__add-button', function(e) {
    e.preventDefault();
    addTagForm($collectionHolder, $addParticipantLink);
    $(this).prev().find('.participant__username').val(randomString());
    $(this).prev().find('.participant__password').val(randomString());
  });

  $('.participants').on('click', '.participant__remove-button', function(e) {
    e.preventDefault();
    $(this).parent().remove();
  });
});

function addTagForm($collectionHolder, $addParticipantLink) {
  const prototype = $collectionHolder.data('prototype');

  const index = $collectionHolder.data('index');

  const newForm = prototype.replace(/__name__/g, index);

  $collectionHolder.data('index', index + 1);

  const $newFormLi = $('<div class="participant"></div>').append(newForm);

  $addParticipantLink.before($newFormLi);
}

