import $ from 'jquery';
import * as M from 'materialize-css';
import randomString from 'random-string';

const getOldValues = that => $(that).parents().eq(2).find('.participant__username').val().split('.');

const displayUsername = (that, username) => $(that).parents().eq(2).find('.participant__username').val(username);

const getUsernameFromName = (that, surname, isEmpty, oldValues) => {
  if (oldValues.length === 3) {
    surname = that.value !== '' ? `.${oldValues[1]}` : oldValues[1];
  } else if (oldValues.length === 2 && that.value !== '') {
    surname = `.${oldValues[0]}`;
  } else if (oldValues.length === 2 && that.value === '') {
    isEmpty = true;
  }

  return !isEmpty ?
    `${that.value}${surname}.${randomString({length: 3})}` :
    '';
};

const getUsernameFromSurname = (that, name, isEmpty, oldValues) => {
  name = oldValues[0] === undefined || oldValues[0] === '' ? name : oldValues[0];

  if (oldValues.length === 2 && that.value === '') {
    isEmpty = true;
  }

  return !isEmpty ?
    `${name}${that.value !== '' && name !== '' ? '.' : ''}${that.value}.${randomString({length: 3})}` :
    '';
};

const generateUsername = ({currentTarget}, getUsername) => {
  let name = '';
  let isEmpty = false;
  const that = currentTarget;
  const oldValues = getOldValues(that);
  const username = getUsername(that, name, isEmpty, oldValues);

  displayUsername(that, username);
};

const toggleAdditionalSection = e => {
  const that = e.currentTarget;

  e.preventDefault();
  $(that).parents().eq(2).find('.participant__additional').slideToggle('fast');

  if ($(that).text() === 'Less') {
    $(that).text('More');
    $(that).parents().eq(2).removeClass('participant--active z-depth-5');
  } else {
    let count = $(that).data('count') || 0;

    $(that).text('Less');
    $(that).parents().eq(2).addClass('participant--active z-depth-5');

    if (count === 0) {
      M.FormSelect.init($(that).parents().eq(2).find('select'));
      M.Datepicker.init($(that).parents().eq(2).find('.datepicker'), {format: 'yyyy-mm-dd'});

      $(that).data('count', ++count);
    }
  }
};

const generatePassword = e => {
  const that = e.currentTarget;

  e.preventDefault();
  $(that).parents().eq(1).find('.participant__password').val(randomString());
};

const jumpFromNameToSurname = e => {
  const keyCode = e.keyCode || e.which;
  const that = e.currentTarget;

  if (keyCode === 13) {
    e.preventDefault();
    $(that).parents().eq(1).find('.participant__surname').focus();
  }
};

export default () => {
  const participantsHolder = $('.participants');

  participantsHolder.on('change', '.participant__name', e => generateUsername(e, getUsernameFromName));
  participantsHolder.on('change', '.participant__surname', e => generateUsername(e, getUsernameFromSurname));
  participantsHolder.on('click', '.participant__toggle-additional-button', toggleAdditionalSection);
  participantsHolder.on('click', '.participant__password-generate-button', generatePassword);
  participantsHolder.on('keydown', '.participant__name', jumpFromNameToSurname);
};