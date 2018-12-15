import $ from 'jquery';
import * as M from 'materialize-css';
import randomString from 'random-string';
import XLSX from 'xlsx';
import 'select2';
import printJS from 'print-js';

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
  collectionHolder.find('.group-form__empty').hide();
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

const removeItem = e => {
  const that = e.currentTarget;

  e.preventDefault();
  $(that).parents().eq(2).slideUp('fast', function () {
    const parent = $(this).parent();

    $(this).remove();
    checkIfCollectionIsEmpty(parent);
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

const jumpToNextItem = (e, addButton, collectionHolder, addFunction, itemClass) => {
  const keyCode = e.keyCode || e.which;
  const that = e.currentTarget;

  if (keyCode === 9 || keyCode === 13) {
    e.preventDefault();
    addFunction(that, addButton, collectionHolder);
    $(that).parents().eq(2).next().children().first().children().find(`.${itemClass}`).focus();
  }
};

const hideTooltip = ({currentTarget}) => {
  const instance = M.Tooltip.getInstance($(currentTarget).find('.tooltipped'));
  instance.close();
};

const importFromExcel = (participantCollectionHolder, addParticipantButton, file, reader) => {
  reader.onload = () => {
    const data = reader.result;
    const workbook = XLSX.read(data, {type: 'binary'});
    const sheet_name_list = workbook.SheetNames;

    sheet_name_list.forEach(y => {
      const worksheet = workbook.Sheets[y];
      let i = 0;
      let first = '';
      for (let z in worksheet) {
        if(z[0] === '!') continue;

        if(i === 0) {
          addItem(addParticipantButton, participantCollectionHolder, 'participant');
          importParticipant(participantCollectionHolder, worksheet[z].v, 'participant__name');
          first = worksheet[z].v;
          i++;
        } else {
          importParticipant(participantCollectionHolder, worksheet[z].v, 'participant__surname');
          participantCollectionHolder.find('.participant:last').find('.participant__password').val(randomString());
          participantCollectionHolder.find('.participant:last')
            .find('.participant__username').val(`${first}.${worksheet[z].v}.${randomString({length: 5})}`);
          i = 0;
        }
      }
    });
  };

  reader.readAsBinaryString(file);
};

const importFromArray = (participantCollectionHolder, addParticipantButton, values, count) => {
  for (let i = 0; i < count; i++) {
    const name = values[i].split(' ');

    if (name.length === 2) {
      addItem(addParticipantButton, participantCollectionHolder, 'participant');
      importParticipant(participantCollectionHolder, name[0], 'participant__name');
      importParticipant(participantCollectionHolder, name[1], 'participant__surname');
      participantCollectionHolder.find('.participant:last').find('.participant__password').val(randomString());
      participantCollectionHolder.find('.participant:last')
        .find('.participant__username').val(`${name[0]}.${name[1]}.${randomString({length: 5})}`);
    } else {
      return false;
    }
  }
  return true;
};

const importFromTextFile = (participantCollectionHolder, addParticipantButton, file, reader) => {
  reader.onload = () => {
    const names = reader.result.split('\n');
    importFromArray(participantCollectionHolder, addParticipantButton, names, names.length - 1);
  };
  reader.readAsText(file);
};

const importParticipants = (participantCollectionHolder, addParticipantButton) => {
  const file = $('.group-form__import__input')[0].files[0];
  const errorHolder = $('.group-form__import__error');
  const reader = new FileReader();
  const textType = /text\/plain/;
  const excelTypes = ["csv", "xlsx", "xls"];

  if(file && excelTypes.includes(file.name.split('.').pop())) {
    importFromExcel(participantCollectionHolder, addParticipantButton, file, reader);
    errorHolder.hide();
  } else if (file && file.type.match(textType)) {
    importFromTextFile(participantCollectionHolder, addParticipantButton, file, reader);
    errorHolder.hide();
  } else {
    errorHolder.show();
  }
};

const importParticipant = (collectionHolder, value, className) => {
  collectionHolder.find('.participant:last').find(`.${className}`).val(value);
  collectionHolder.find('.participant:last').find(`.${className}`).prev().addClass('active');
};

const checkIfCollectionIsEmpty = collectionHolder => {
  collectionHolder.find(':input').length / 10 === 0 && collectionHolder.find('.group-form__empty').show();
};

const togglePaste = () => {
  $('.group-form__paste__section').slideToggle('fast');
  $('.group-form__paste__error--format').hide();
  $('.group-form__paste__error--empty').hide();
};

const addFromPaste = (e, participantCollectionHolder, addParticipantButton) => {
  const names = $('.group-form__paste__text').val().split('\n').filter(name => name !== '');

  e.preventDefault();

  if(names.length > 0) {
    if(importFromArray(participantCollectionHolder, addParticipantButton, names, names.length) === false) {
      $('.group-form__paste__error--format').show();
      $('.group-form__paste__error--empty').hide();
    } else {
      $('.group-form__paste__text').val('');
      $('.group-form__paste__section').hide();
    }
  } else {
    $('.group-form__paste__error--empty').show();
    $('.group-form__paste__error--format').hide();
  }

};

export default () => {
  const addParticipantButton = $('<div class="clearfix">' +
    '<i class="group-form__add-participant-button tooltipped btn-floating btn-large blue lighten-1 ' +
    'material-icons" data-tooltip="Pridėti dalyvį" data-position="right">add</i></div>');
  const addTimeSlotButton = $('<div class="clearfix">' +
    '<i class="group-form__add-time-slot-button tooltipped btn-floating btn-large blue lighten-1 material-icons ' +
    '" data-tooltip="Pridėti paskaitos laiką" data-position="right">add</i></div>');
  const participantCollectionHolder = $('div.participants');
  const timeSlotCollectionHolder = $('div.time-slots');

  initCollections(participantCollectionHolder, addParticipantButton);
  initCollections(timeSlotCollectionHolder, addTimeSlotButton);

  checkIfCollectionIsEmpty(participantCollectionHolder);
  checkIfCollectionIsEmpty(timeSlotCollectionHolder);

  M.Tooltip.init($('.tooltipped'), {outDuration: 0});
  M.Datepicker.init(timeSlotCollectionHolder.find('.datepicker'), {format: 'yyyy-mm-dd'});
  M.Timepicker.init(timeSlotCollectionHolder.find('.timepicker'), {twelveHour: false});

  $('.group-form__teacher').select2({
    width: '100%',
    theme: "bootstrap4",
    language: {
      noResults: () => {
        return "Rezultatų nerasta";
      }
    }
  });
  $('.container .alert').fadeTo(5000, 500).slideUp(500, function () {
    $(this).slideUp(500)
  });
  $('.group-form__import__input').on('change', () => importParticipants(participantCollectionHolder, addParticipantButton));
  $('.group-form__paste__toggle-button').on('click', togglePaste);
  $('.group-form__paste__submit-button').on('click', e => addFromPaste(e, participantCollectionHolder, addParticipantButton));
  $('.form__print').on('click', () => printJS({
    printable: 'form__table',
    type: 'html',
    header: 'Dalyviai',
    scanStyles: false
  }));

  addParticipantButton.on('click', '.group-form__add-participant-button',
    e => addParticipantButtonHandler(e, addParticipantButton, participantCollectionHolder));
  addParticipantButton.on('mousedown', hideTooltip);
  addTimeSlotButton.on('click', '.group-form__add-time-slot-button',
    e => addTimeSlotButtonHandler(e, addTimeSlotButton, timeSlotCollectionHolder));
  addTimeSlotButton.on('mousedown', hideTooltip);

  participantCollectionHolder.on('keydown', '.participant__surname', e =>
    jumpToNextItem(e, addParticipantButton, participantCollectionHolder, addParticipant, 'participant__name'));
  participantCollectionHolder.on('click', '.participant__button--remove', removeItem);
  participantCollectionHolder.on('keydown', '.participant__name', jumpToSibling);
  timeSlotCollectionHolder.on('click', '.time-slot__button--remove', removeItem);
  timeSlotCollectionHolder.on('keydown', '.time-slot__date', jumpToSibling);
  timeSlotCollectionHolder.on('keydown', '.time-slot__start-time', jumpToSibling);
  timeSlotCollectionHolder.on('keydown', '.time-slot__duration',
    e => jumpToNextItem(e, addTimeSlotButton, timeSlotCollectionHolder, addTimeSlot, 'time-slot__date'));
};