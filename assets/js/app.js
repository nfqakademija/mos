require('bootstrap');
const $ = require('jquery');
const randomString = require('random-string');

$(document).ready(function() {



  $('.participants').on('click', '.participant__toggle-additional-button', function(e) {
    e.preventDefault();
    $('.participant__additional').toggle();
  });

  $('.participants').on('click', '.participant__username-generate-button', function(e) {
    e.preventDefault();
    generateUsername();
  });

  $('.participants').on('click', '.participant__password-generate-button', function(e) {
    e.preventDefault();
    generatePasword();
  });

  // Get the ul that holds the collection of tags
  const $collectionHolder = $('div.participants');
  const $addParticipantLink = $('.participant__add-button');

  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  $collectionHolder.data('index', $collectionHolder.find(':input').length);

  $addParticipantLink.on('click', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // add a new tag form (see code block below)
    addTagForm($collectionHolder, $addParticipantLink);

    generateUsername();
    generatePasword();
  });

});

function addTagForm($collectionHolder, $addParticipantLink) {
  // Get the data-prototype explained earlier
  const prototype = $collectionHolder.data('prototype');

  // get the new index
  const index = $collectionHolder.data('index');

  // Replace '$$name$$' in the prototype's HTML to
  // instead be a number based on how many items we have
  const newForm = prototype.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);

  // Display the form in the page in an li, before the "Add a tag" link li
  const $newFormLi = $('<div class="participant"></div>').append(newForm);

  $addParticipantLink.before($newFormLi);

  // handle the removal, just for this example
  $('.participants').on('click', '.participant__remove-button',function(e) {
    console.log("wtf");
    e.preventDefault();

    $(this).parent().remove();
  });
}

function generateUsername() {
  $('.participant__username').val(randomString());
}

function generatePasword() {
  $('.participant__password').val(randomString());
}

