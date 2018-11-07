import React, { Component, Fragment } from 'react';
import Menu from './Menu';
import Home from './Home';
import Profile from './Profile';
import { Route, Switch } from 'react-router-dom';
import Users from "./Users";

class App extends Component {
  render() {
    return (
      <Fragment>
        <Menu />
        <div className="app container">
          <Switch>
            <Route exact path="/" component={Home} />
            <Route path="/profile/view" component={Profile} />
            <Route path="/profile/viewlist" component={Users} />
          </Switch>
        </div>
      </Fragment>
    )
  }
}

export default App;