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
  $(that).prev().find('.participant__password').val(randomString());
  M.updateTextFields();
};

const addTimeSlotButtonHandler = (e, addTimeSlotButton, timeSlotCollectionHolder) => {
  const that = e.currentTarget;

  e.preventDefault();
  addItem(addTimeSlotButton, timeSlotCollectionHolder, 'time-slot');
  M.Datepicker.init($(that).prev().find('.datepicker'), {format: 'yyyy-mm-dd'});
  M.Timepicker.init($(that).prev().find('.timepicker'), {twelveHour: false});
};

const removeItem = e => {
  const that = e.currentTarget;

  e.preventDefault();
  $(that).parents().eq(2).slideUp('fast', function () {
    $(this).remove();
  });
};

const jumpToSibling = e => {
  const keyCode = e.keyCode || e.which;
  const that = e.currentTarget;

  if (keyCode === 13) {
    e.preventDefault();
    $(that).parents().next().find('input').focus();
  }
};

const addParticipant = (that, addParticipantButton, participantCollectionHolder) => {
  if (!$(that).parents().eq(2).next().is('.participant')) {
    addItem(addParticipantButton, participantCollectionHolder, 'participant');
    $(that).parents().eq(2).next('.participant').find('.participant__password').val(randomString());
  }
};

const addTimeSlot = (that, addTimeSlotButton, timeSlotCollectionHolder) => {
  if (!$(that).parents().eq(2).next().is('.time-slot')) {
    addItem(addTimeSlotButton, timeSlotCollectionHolder, 'time-slot');
    M.Datepicker.init($(that).parents().eq(2).next().find('.datepicker'), {format: 'yyyy-mm-dd'});
    M.Timepicker.init($(that).parents().eq(2).next().find('.timepicker'), {twelveHour: false});
  }
};

const jumpToNextItem = (e, addButton, collectionHolder, addFunction) => {
  const keyCode = e.keyCode || e.which;
  const that = e.currentTarget;

  if (keyCode === 9 || keyCode === 13) {
    e.preventDefault();
    addFunction(that, addButton, collectionHolder);
  }

  $(that).parents().eq(2).next().children().first().children().first().find('input').focus();
};

const hideTooltip = ({ currentTarget }) => {
  const instance = M.Tooltip.getInstance($(currentTarget).find('.tooltipped'));
  instance.close();
};

const importParticipants = (participantCollectionHolder, addParticipantButton) => {
  const file = $('.group-form__import')[0].files[0];
  const errorHolder = $('.group-form__import-error');
  const reader = new FileReader();
  const textType = /text\/plain/;

  if(file.type.match(textType)) {
    reader.onload = () => {
      const names = reader.result.split('\n');

      for (let i = 0; i < names.length - 1; i++) {
        const name = names[i].split(' ');

        if (name.length === 2) {
          addItem(addParticipantButton, participantCollectionHolder, 'participant');
          importParticipant(participantCollectionHolder, name[0], 'participant__name');
          importParticipant(participantCollectionHolder, name[1], 'participant__surname');
          participantCollectionHolder.find('.participant:last').find('.participant__password').val(randomString());
          participantCollectionHolder.find('.participant:last')
            .find('.participant__username').val(`${name[0]}.${name[1]}.${randomString({length: 3})}`);
        }
      }
    };
    reader.readAsText(file);
    errorHolder.css('display', 'none');
  } else {
    errorHolder.css('display', 'block');
  }
};

const importParticipant = (collectionHolder, value, className) => {
  collectionHolder.find('.participant:last').find(`.${className}`).val(value);
  collectionHolder.find('.participant:last').find(`.${className}`).prev().addClass('active');
};

export default () => {
  const addParticipantButton = $('<div class="clearfix">' +
    '<i class="group-form__add-participant-button tooltipped btn-floating btn-large blue darken-3 material-icons"' +
    ' data-tooltip="Add participant" data-position="right">add</i></div>');
  const addTimeSlotButton = $('<div class="clearfix">' +
    '<i class="group-form__add-time-slot-button tooltipped btn-floating btn-large blue darken-3 material-icons" ' +
    'data-tooltip="Add timeslot" data-position="right">add</i></div>');
  const participantCollectionHolder = $('div.participants');
  const timeSlotCollectionHolder = $('div.time-slots');

  initCollections(participantCollectionHolder, addParticipantButton);
  initCollections(timeSlotCollectionHolder, addTimeSlotButton);

  M.Tooltip.init($('.tooltipped'), {outDuration: 0});
  M.FormSelect.init($('.group-form__teacher'));
  M.Datepicker.init(timeSlotCollectionHolder.find('.datepicker'), {format: 'yyyy-mm-dd'});
  M.Timepicker.init(timeSlotCollectionHolder.find('.timepicker'), {twelveHour: false});

  $('.participant__additional').hide();
  $('.container .alert').fadeTo(5000, 500).slideUp(500, function () { $(this).slideUp(500) });
  $('.group-form__import').on('change', () => importParticipants(participantCollectionHolder, addParticipantButton));

  addParticipantButton.on('click', e => addParticipantButtonHandler(e, addParticipantButton, participantCollectionHolder));
  addParticipantButton.on('mousedown', hideTooltip);
  addTimeSlotButton.on('click', e => addTimeSlotButtonHandler(e, addTimeSlotButton, timeSlotCollectionHolder));
  addTimeSlotButton.on('mousedown', hideTooltip);

  participantCollectionHolder.on('keydown', '.participant__surname',
      e => jumpToNextItem(e, addParticipantButton, participantCollectionHolder, addParticipant));
  participantCollectionHolder.on('click', '.participant__remove-button', removeItem);
  participantCollectionHolder.on('keydown', '.participant__name', jumpToSibling);
  timeSlotCollectionHolder.on('click', '.time-slot__remove-button', removeItem);
  timeSlotCollectionHolder.on('keydown', '.time-slot__date', jumpToSibling);
  timeSlotCollectionHolder.on('keydown', '.time-slot__start-time', jumpToSibling);
  timeSlotCollectionHolder.on('keydown', '.time-slot__duration',
      e => jumpToNextItem(e, addTimeSlotButton, timeSlotCollectionHolder, addTimeSlot));
};