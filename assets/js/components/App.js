import React, { Component, Fragment } from 'react';
import Menu from './Menu';
import Home from './Home';
import Profile from './Profile';
import { Route, Switch } from 'react-router-dom';

class App extends Component {
  render() {
    return (
      <Fragment>
        <Menu />
        <Switch>
          <Route exact path="/" component={Home} />
          <Route exact path="/profile/view" component={Profile} />
        </Switch>
      </Fragment>
    )
  }
}

export default App;