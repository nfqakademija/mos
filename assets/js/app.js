import $ from 'jquery';
import * as M from 'materialize-css';
import initGroupForm  from './group';
import groupParticipants from './participant';
import reports from './report';

$(document).ready(function () {
  M.Sidenav.init($('.sidenav'));
  initGroupForm();
  groupParticipants();
  reports();
});





