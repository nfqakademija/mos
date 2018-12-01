import $ from 'jquery';
import * as M from 'materialize-css';
import randomString from 'random-string';

const addButton = $('<div class="clearfix"><i class="group-form__add-button btn-floating btn-large blue darken-3 material-icons">add</i></div>');
const collectionHolder = $('div.participants');

const addButtonClickHandler = function (e) {
  e.preventDefault();
  addParticipant();
  $(this).parent().prev().find('.participant__password').val(randomString());
  M.updateTextFields();
};

const jumpFromSurnameToNextParticipant = function (e) {
  const keyCode = e.keyCode || e.which;

  if (keyCode === 13 || keyCode === 9) {
    e.preventDefault();
  }

  if ((keyCode === 9 || keyCode === 13) && !$(this).parents().eq(2).next().is('.participant')) {
    addParticipant();
    $(this).parents().eq(2).next('.participant').find('.participant__password').val(randomString());
    $(this).parents().eq(2).next('.participant').find('.participant__name').focus();
  } else if (keyCode === 9 || keyCode === 13) {
    $(this).parents().eq(2).next('.participant').find('.participant__name').focus();
  }
};

const addParticipant = function () {
  const prototype = collectionHolder.data('prototype');
  const index = collectionHolder.data('index');
  const newForm = prototype.replace(/__name__/g, index);

  collectionHolder.data('index', index + 1);
  addButton.before(newForm);

  $('.participants').find('.participant:last').hide().slideDown('fast');
};

export default () => {
  collectionHolder.append(addButton);
  collectionHolder.data('index', collectionHolder.find(':input').length);
  M.FormSelect.init($('.group-form__teacher'));

  $('.participant__additional').hide();
  $(".container .alert").fadeTo(5000, 500).slideUp(500, function () { $(".container .alert").slideUp(500); });
  $('.group-form__add-button').on('click', addButtonClickHandler);
  $('.participants').on('keydown', '.participant__surname', jumpFromSurnameToNextParticipant);
};