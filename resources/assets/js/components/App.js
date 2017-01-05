import React from 'react'
import NavBar from './NavBar'
import { Jumbotron } from 'reactstrap';

class App extends React.Component {
  render() {
    return (
      <div>
        <Jumbotron className="text-xs-center">
          <h1 className="display-5">A Clash of Clans Database</h1>
          <p>Built with <span className="lead"><i>Laravel</i></span> and <span className="lead"><i>ReactJS</i></span></p>
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
