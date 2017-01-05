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
                <h2>Lv{clan.clanLevel} - {clan.clanPoints} Points - {clan.members} Members</h2>
                <h2>War: {clan.warWins} wins - {clan.warLosses} losses - {clan.warTies} ties</h2>
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
