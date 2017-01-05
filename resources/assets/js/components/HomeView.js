import React from 'react'
import {connect, PromiseState} from "react-refetch"
import PromiseStateContainer from "./core/PromiseStateContainer"

class HomeView extends React.Component {
  render() {
    return (
      <PromiseStateContainer
        ps={PromiseState.all([this.props.clanFetch])}
        onFulfillment={([data]) => {
          const clan = data.clan;
          return (
            <div>
              <div className="text-xs-center">
                <h1>{clan.name}</h1>
                <p className="lead">Lv{clan.clanLevel} - {clan.clanPoints} Points - {clan.members} Members</p>
                <p className="lead">War: {clan.warWins} wins - {clan.warLosses} losses - {clan.warTies} ties</p>
                <hr/>
                <p className="text-muted">
                  [incredible data analysis here]
                </p>
              </div>
            </div>
          )
        }}
      />
    )
  }
}

export default connect(props => ({
  clanFetch: '/api/clan',
}))(HomeView)
