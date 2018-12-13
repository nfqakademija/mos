import $ from 'jquery';
import * as M from 'materialize-css';
import '../img/upload_info.png';
import initGroupForm  from './group';
import groupParticipants from './participant';
import reports from './report';

$(document).ready(function () {
  M.Sidenav.init($('.sidenav'));
  M.FormSelect.init($('select'));
  M.Datepicker.init($('.datepicker'));
  initGroupForm();
  groupParticipants();
  reports();
});





