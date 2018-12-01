import $ from 'jquery';
import * as M from 'materialize-css';
import initGroupForm  from './group';
import groupParticipants from './participant';

$(document).ready(function () {
  M.Sidenav.init($('.sidenav'));
  initGroupForm();
  groupParticipants();
});





