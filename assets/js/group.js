import $ from 'jquery';
import * as M from 'materialize-css';
import randomString from 'random-string';

const initCollections = (collectionHolder, addButton) => {
  collectionHolder.append(addButton);
  collectionHolder.data('index', collectionHolder.find(':input').length);
};

const addItem = (addButton, collectionHolder, itemClass) => {
  const prototype = collectionHolder.data('prototype');
  const index = collectionHolder.data('index');
  const newForm = prototype.replace(/__name__/g, index);

  collectionHolder.data('index', index + 1);
  addButton.before(newForm);
  collectionHolder.find(`.${itemClass}:last`).hide().slideDown('fast');
};

const addParticipantButtonHandler = (e, addParticipantButton, participantCollectionHolder) => {
  const that = e.currentTarget;

  e.preventDefault();
  addItem(addParticipantButton, participantCollectionHolder, 'participant');
  $(that).parent().prev().find('.participant__password').val(randomString());
  M.updateTextFields();
};

const addTimeSlotButtonHandler = (e, addTimeSlotButton, timeSlotCollectionHolder) => {
  const that = e.currentTarget;

  e.preventDefault();
  addItem(addTimeSlotButton, timeSlotCollectionHolder, 'time-slot');
  M.Datepicker.init($(that).parent().prev().find('.datepicker'), {format: 'yyyy-mm-dd'});
  M.Timepicker.init($(that).parent().prev().find('.timepicker'), {twelveHour: false});
};

const jumpFromSurnameToNextParticipant = (e, addParticipantButton, participantCollectionHolder) => {
  const keyCode = e.keyCode || e.which;
  const that = e.currentTarget;

  if (keyCode === 9 || keyCode === 13) {
    e.preventDefault();

    if (!$(that).parents().eq(2).next().is('.participant')) {
      addItem(addParticipantButton, participantCollectionHolder, 'participant');
      $(that).parents().eq(2).next('.participant').find('.participant__password').val(randomString());
    }

    $(that).parents().eq(2).next('.participant').find('.participant__name').focus();
  }
};

const removeItem = e => {
  const that = e.currentTarget;

  e.preventDefault();
  $(that).parents().eq(2).slideUp('fast', function () {
    $(this).remove();
  });
};

export default () => {
  const addParticipantButton = $('<div class="clearfix"><i class="group-form__add-participant-button btn-floating btn-large blue darken-3 material-icons">add</i></div>');
  const participantCollectionHolder = $('div.participants');
  const addTimeSlotButton = $('<div class="clearfix"><i class="group-form__add-time-slot-button btn-floating btn-large blue darken-3 material-icons">add</i></div>');
  const timeSlotCollectionHolder = $('div.time-slots');

  initCollections(participantCollectionHolder, addParticipantButton);
  initCollections(timeSlotCollectionHolder, addTimeSlotButton);

  M.FormSelect.init($('.group-form__teacher'));
  M.Datepicker.init(timeSlotCollectionHolder.find('.datepicker'), {format: 'yyyy-mm-dd'});
  M.Timepicker.init(timeSlotCollectionHolder.find('.timepicker'), {twelveHour: false});

  $('.participant__additional').hide();
  $('.container .alert').fadeTo(5000, 500).slideUp(500, function () {
    $(this).slideUp(500)
  });
  $('.group-form__add-participant-button').on('click', e => addParticipantButtonHandler(e, addParticipantButton, participantCollectionHolder));
  $('.group-form__add-time-slot-button').on('click', e => addTimeSlotButtonHandler(e, addTimeSlotButton, timeSlotCollectionHolder));

  participantCollectionHolder.on('keydown', '.participant__surname', e => jumpFromSurnameToNextParticipant(e, addParticipantButton, participantCollectionHolder));
  participantCollectionHolder.on('click', '.participant__remove-button', removeItem);
  timeSlotCollectionHolder.on('click', '.time-slot__remove-button', removeItem);
};