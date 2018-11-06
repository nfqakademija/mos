import React from 'react';
import bootstrap from 'bootstrap';
import { render } from 'react-dom'
import App from './Components/App';
import { BrowserRouter as Router } from 'react-router-dom';

render(
  <Router>
    <App />
  </Router>,
  document.getElementById('root')
);
