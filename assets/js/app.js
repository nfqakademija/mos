import React from 'react';
import 'bootstrap';
import { render } from 'react-dom'
import App from './components/App';
import { BrowserRouter as Router } from 'react-router-dom';
import { applyMiddleware, createStore } from 'redux';
import { Provider } from 'react-redux';
import logger from 'redux-logger';
import thunk from 'redux-thunk';
import rootReducer from './reducers/index';

const store = createStore(rootReducer, applyMiddleware(logger, thunk));

render(
  <Provider store={store}>
    <Router>
      <App />
    </Router>
  </Provider>,
  document.getElementById('root')
);
