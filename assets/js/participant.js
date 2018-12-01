import $ from 'jquery';
import * as M from 'materialize-css';
import randomString from 'random-string';

const generateUsernameUsingName = function () {
  let surname;
  let isEmpty = false;
  const oldValues = $(this).parents().eq(2).find('.participant__username').val().split('.');

  if (oldValues.length === 3) {
    surname = this.value !== '' ? `.${oldValues[1]}` : oldValues[1];
  } else if (oldValues.length === 2 && this.value !== '') {
    surname = `.${oldValues[0]}`;
  } else if (oldValues.length === 2 && this.value === '') {
    isEmpty = true;
  } else {
    surname = '';
  }

  $(this).parents().eq(2).find('.participant__username')
    .val(!isEmpty ? `${this.value}${surname}.${randomString({length: 3})}` : '');
};

const generateUsernameUsingSurname = function () {
  let isEmpty = false;
  const oldValues = $(this).parents().eq(2).find('.participant__username').val().split('.');
  const isNameEmpty = oldValues[0] === undefined || oldValues[0] === '';

  if (oldValues.length === 2 && this.value === '') {
    isEmpty = true;
  }

  $(this).parents().eq(2).find('.participant__username')
    .val(!isEmpty ? `${oldValues[0]}${this.value !== '' && !isNameEmpty ? '.' : ''}${this.value}.${randomString({length: 3})}` : '');
};

const toggleAdditionalSection = function (e) {
  e.preventDefault();
  $(this).parents().eq(2).find('.participant__additional').slideToggle('fast');

  if ($(this).text() === 'Less') {
    $(this).text('More');
    $(this).parents().eq(2).removeClass('participant--active z-depth-5');
  } else {
    $(this).text('Less');
    $(this).parents().eq(2).addClass('participant--active z-depth-5');
    let count = $(this).data('count') || 0;

    if (count === 0) {
      M.FormSelect.init($(this).parents().eq(2).find('select'));
      M.Datepicker.init($(this).parents().eq(2).find('.datepicker'), { format: 'yyyy-mm-dd' });
      $(this).data('count', ++count);
    }
  }
};

const removeParticipant = function (e) {
  e.preventDefault();
  $(this).parent().parent().parent().slideUp('fast', function () {
    $(this).remove();
  });
};

const generatePassword = function (e) {
  e.preventDefault();
  $(this).parents().eq(1).find('.participant__password').val(randomString());
};

const jumpFromNameToSurname = function (e) {
  const keyCode = e.keyCode || e.which;

  if (keyCode === 13) {
    e.preventDefault();
    $(this).parents().eq(1).find('.participant__surname').focus();
  }
};

export default () => {
  $('.participants').on('change', '.participant__name', generateUsernameUsingName);
  $('.participants').on('change', '.participant__surname', generateUsernameUsingSurname);
  $('.participants').on('click', '.participant__toggle-additional-button', toggleAdditionalSection);
  $('.participants').on('click', '.participant__password-generate-button', generatePassword);
  $('.participants').on('click', '.participant__remove-button', removeParticipant);
  $('.participants').on('keydown', '.participant__name', jumpFromNameToSurname);
};