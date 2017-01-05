import React from 'react'
import { render } from 'react-dom'
import { Router, Route, browserHistory, IndexRoute } from 'react-router'
import App from './components/App'
import NoMatch from './components/core/NoMatch'
import HomeView from './components/HomeView'
import PlayersView from './components/PlayersView'
import PlayerView from './components/PlayerView'
import fetchIntercept from 'fetch-intercept';

const unregister = fetchIntercept.register({
  request: function (url, config) {
    // Modify the url or config here
    console.log(config);
  }
});

render((
  <Router history={browserHistory}>
    <Route path="/" component={App}>
      <IndexRoute component={HomeView} />
      <Route path="/players" component={PlayersView} />
      <Route path="/players/:id" component={PlayerView} />
      <Route path="*" component={NoMatch} />
    </Route>
  </Router>
), document.getElementById('app'));

/* Helpers */
JSON.decode = function(str) {
  try {
    return JSON.parse(str);
  } catch (e) {
    return str;
  }
};