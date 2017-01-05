import React from "react"
import {connect, PromiseState} from "react-refetch"
import PromiseStateContainer from "./core/PromiseStateContainer"
import PlayerCard from "./PlayerCard"

class PlayersView extends React.Component {

  render() {
    return (
      <PromiseStateContainer
        ps={PromiseState.all([this.props.playersFetch])}
        onFulfillment={([data]) => {

          return (
            <div id="players">
              <div className="row">
                <div className="col-lg-12">
                  <h1 className="text-xs-center mb-2">Active Players</h1>
                </div>
                {data.activePlayers.map((player) => {
                  return (
                    <PlayerCard player={player} key={player.id} />
                  )
                })
                }
              </div>
              <hr/>
              <div className="row">
                <div className="col-lg-12">
                  <h1 className="text-xs-center mb-2">The Dead</h1>
                </div>
                {data.inactivePlayers.map((player) => {
                  return (
                    <PlayerCard player={player} key={player.id} />
                  )
                })
                }
              </div>
            </div>
            )
          }
        }
      />
    )
  }

}

export default connect(props => ({
  playersFetch: '/api/players',
}))(PlayersView)
