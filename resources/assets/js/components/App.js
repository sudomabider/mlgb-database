import React from 'react'
import NavBar from './NavBar'
import { Jumbotron } from 'reactstrap';

class App extends React.Component {
  render() {
    return (
      <div>
        <Jumbotron className="text-xs-center">
          <h1 className="display-4">MLGB Clan Database</h1>
          <p className="lead">A Clash of Clans related demo - Built with Laravel and ReactJS</p>
          <NavBar />
        </Jumbotron>

        <div className="container mt-1">
          {this.props.children}
        </div>
      </div>
    )
  }

}

export default App
