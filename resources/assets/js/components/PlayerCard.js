import React from 'react'
import { Link } from 'react-router'
import { Card, CardImg, CardHeader, CardBlock, CardTitle } from 'reactstrap';

class PlayerCard extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <div className="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <Card>
          <CardHeader>
            <CardTitle className="mb-0" tag="h6">
              <Link to={`/players/${this.props.player.id}`}>
                {this.props.player.name}
              </Link>
            </CardTitle>
          </CardHeader>
          <CardBlock>
            <ul className="list-unstyled">
              <li>
                <CardImg className="img-responsive mr-1" src={this.props.player.league.icon_tiny} alt={this.props.player.league.name} />
                {this.props.player.record.trophies}
              </li>
            </ul>
          </CardBlock>
        </Card>
      </div>
    )
  }
}

PlayerCard.propTypes = {
  player: React.PropTypes.object.isRequired
};

export default PlayerCard