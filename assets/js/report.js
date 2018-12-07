import * as M from 'materialize-css';
import $ from 'jquery';

export default () => {
  const initialOptions = {
    format: 'yyyy-mm-dd',
    defaultDate: new Date(),
    setDefaultDate: true
  };

  M.Datepicker.init($('.report__date-from'), initialOptions);
  M.Datepicker.init($('.report__date-to'), initialOptions);
};